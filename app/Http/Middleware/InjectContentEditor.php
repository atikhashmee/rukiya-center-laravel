<?php

namespace App\Http\Middleware;

use App\Support\ContentEditMode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Turns the markers left by content()/content_html() into editable elements and adds
 * the in-page editor, but only while the page is open inside the admin's visual editor.
 */
class InjectContentEditor
{
    private const MARKER = "\u{2063}";

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! ContentEditMode::active($request) || ! $this->isHtml($response)) {
            return $response;
        }

        $html = $response->getContent();

        if (! is_string($html) || ! str_contains($html, self::MARKER)) {
            return $response;
        }

        $response->setContent($this->injectEditor($this->markFields($html)));

        return $response;
    }

    private function isHtml(Response $response): bool
    {
        return str_contains((string) $response->headers->get('Content-Type'), 'text/html')
            && method_exists($response, 'getContent');
    }

    /** Replace the marker pairs with spans the editor script can pick up. */
    private function markFields(string $html): string
    {
        $m = preg_quote(self::MARKER, '/');

        return preg_replace_callback(
            "/{$m}CF:([^:]+):([^:]+):([^\\x{2063}]+){$m}(.*?){$m}\\/CF{$m}/su",
            function (array $match) use ($html) {
                [$whole, $page, $key, $type, $value] = $match;

                // A marker inside a tag (someone used content() in an attribute) cannot
                // become an element: drop the markers and leave the plain value.
                $before = substr($html, 0, strpos($html, $whole) ?: 0);
                if (strrpos($before, '<') > strrpos($before, '>')) {
                    return $value;
                }

                return sprintf(
                    '<span class="cf-editable" data-cf-page="%s" data-cf-key="%s" data-cf-type="%s">%s</span>',
                    e($page),
                    e($key),
                    e($type),
                    $value,
                );
            },
            $html,
        ) ?? $html;
    }

    private function injectEditor(string $html): string
    {
        $tag = '<link rel="stylesheet" href="'.asset('css/content-editor.css').'">'
            .'<script src="'.asset('js/content-editor.js').'" defer></script>';

        return str_contains($html, '</body>')
            ? substr_replace($html, $tag.'</body>', strrpos($html, '</body>'), strlen('</body>'))
            : $html.$tag;
    }
}
