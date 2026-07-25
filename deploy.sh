#!/usr/bin/env bash
#
# Deploy DK Healing Centre (Laravel + Inertia/React) to Hostinger shared hosting.
#
# Shared hosting has no Node.js, so the frontend is built HERE (locally) and the
# compiled public/build/ assets are transferred to the server over SSH. PHP-side
# code is deployed by having the server pull straight from git.
#
# Usage: ./deploy.sh
#
set -euo pipefail

# --- Configuration ------------------------------------------------------
SSH_PORT=65002
SSH_TARGET="u306024954@145.79.25.21"
REMOTE_PATH="/home/u306024954/domains/dkhealingcenter.com/public_html"
BRANCH="main"
# -------------------------------------------------------------------------

SSH="ssh -p ${SSH_PORT} ${SSH_TARGET}"
step() { printf '\n\033[1;34m==>\033[0m %s\n' "$1"; }
die() { printf '\n\033[1;31mERROR:\033[0m %s\n' "$1" >&2; exit 1; }

# --- 0. Preflight: don't deploy uncommitted local work by accident -------
if [ -n "$(git status --porcelain)" ]; then
    die "You have uncommitted local changes. Commit or stash them, then re-run."
fi

CURRENT_BRANCH="$(git rev-parse --abbrev-ref HEAD)"
if [ "${CURRENT_BRANCH}" != "${BRANCH}" ]; then
    die "You're on '${CURRENT_BRANCH}', not '${BRANCH}'. Switch branches before deploying."
fi

step "Pushing ${BRANCH} to origin"
git push origin "${BRANCH}"

# --- 1. Build frontend locally -------------------------------------------
step "Regenerating Wayfinder route/action helpers"
php artisan wayfinder:generate

step "Building frontend assets (npm run build)"
npm run build

# --- 2. Transfer built assets to the server -------------------------------
step "Uploading public/build/ to server"
if command -v rsync >/dev/null 2>&1 && ${SSH} 'command -v rsync' >/dev/null 2>&1; then
    rsync -az --delete -e "ssh -p ${SSH_PORT}" public/build/ "${SSH_TARGET}:${REMOTE_PATH}/public/build/"
else
    echo "  rsync unavailable on one end - falling back to tar over ssh"
    ${SSH} "mkdir -p '${REMOTE_PATH}/public/build' && rm -rf '${REMOTE_PATH}/public/build'/*"
    tar czf - -C public build | ${SSH} "tar xzf - -C '${REMOTE_PATH}/public'"
fi

# --- 3. Deploy code + run server-side steps -------------------------------
step "Deploying code and running server-side steps"
${SSH} bash -s <<REMOTE_SCRIPT
set -euo pipefail
cd "${REMOTE_PATH}"

echo "  -> git fetch + hard reset to origin/${BRANCH}"
git fetch origin "${BRANCH}"
git reset --hard "origin/${BRANCH}"

echo "  -> composer install"
composer install --no-dev --optimize-autoloader --no-interaction

echo "  -> entering maintenance mode"
php artisan down --retry=15 || true

# If anything below fails, the site is left in maintenance mode on purpose:
# new code + un-migrated schema can crash worse than a maintenance page would.
# Fix the issue, then run 'php artisan up' on the server manually.
run_migrations() {
    echo "  -> running migrations"
    php artisan migrate --force

    echo "  -> storage:link (skipped if it already exists)"
    # Not "php artisan storage:link": this host has both symlink() and exec()
    # disabled in php.ini, so Laravel can't create the link from PHP at all.
    # A raw shell "ln" isn't constrained by PHP's disable_functions, so do it directly.
    [ -L public/storage ] || ln -s "\$(pwd)/storage/app/public" "\$(pwd)/public/storage"

    echo "  -> rebuilding caches"
    php artisan optimize:clear
    php artisan optimize

    echo "  -> leaving maintenance mode"
    php artisan up
}

if ! run_migrations; then
    echo ""
    echo "  !! Deploy step failed - site left in maintenance mode." >&2
    echo "  !! SSH in, investigate, then run: php artisan up" >&2
    exit 1
fi
REMOTE_SCRIPT

step "Deployed successfully."
