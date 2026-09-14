// Translucent tones read well on both light and dark backgrounds.
export const tones = {
    success: 'bg-emerald-500/10 text-emerald-700 ring-emerald-600/20 dark:text-emerald-300 dark:ring-emerald-400/25',
    warning: 'bg-amber-500/10 text-amber-700 ring-amber-600/20 dark:text-amber-300 dark:ring-amber-400/25',
    danger: 'bg-rose-500/10 text-rose-700 ring-rose-600/20 dark:text-rose-300 dark:ring-rose-400/25',
    info: 'bg-sky-500/10 text-sky-700 ring-sky-600/20 dark:text-sky-300 dark:ring-sky-400/25',
    accent: 'bg-violet-500/10 text-violet-700 ring-violet-600/20 dark:text-violet-300 dark:ring-violet-400/25',
    neutral: 'bg-muted text-muted-foreground ring-border',
} as const;

export type Tone = keyof typeof tones;

const statusTone: Record<string, Tone> = {
    completed: 'success',
    paid: 'success',
    succeeded: 'success',
    active: 'success',
    published: 'success',
    approved: 'success',
    verified: 'success',
    confirmed: 'info',
    in_progress: 'info',
    processing: 'info',
    assessment_required: 'accent',
    new: 'accent',
    pending: 'warning',
    draft: 'neutral',
    inactive: 'neutral',
    cancelled: 'danger',
    canceled: 'danger',
    failed: 'danger',
    refunded: 'danger',
};

/** Base pill classes plus a tone; use on a <span>. */
export const badgeClasses = (tone: Tone) =>
    `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset ${tones[tone]}`;

/** Pill classes for a status string such as "paid" or "in_progress". Unknown statuses are neutral. */
export const statusClasses = (status: string | null | undefined) =>
    badgeClasses(statusTone[String(status ?? '').toLowerCase()] ?? 'neutral');

export const statusLabel = (status: string | null | undefined) =>
    status ? status.charAt(0).toUpperCase() + status.slice(1).replaceAll('_', ' ') : '—';
