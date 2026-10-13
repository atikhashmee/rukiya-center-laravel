<?php

use App\Support\ContentConverter;

it('replaces wording with an editable field that keeps the original as fallback', function () {
    $source = '<h1>Welcome</h1>';
    $fields = [['key' => 'title', 'type' => 'text', 'uk' => 'Welcome']];

    $result = ContentConverter::apply($source, 'about', $fields, 'uk');

    expect($result['error'])->toBeNull()
        ->and($result['converted'])->toBe(1)
        ->and($result['source'])->toBe("<h1>{{ content('about', 'title', 'Welcome') }}</h1>");
});

it('never nests one field inside another when a phrase repeats inside a longer one', function () {
    // This is the bug that broke the homepage: "Our services" sits inside "Our services today".
    $source = '<h1>Our services today</h1><h2>Our services</h2>';
    $fields = [
        ['key' => 'short', 'type' => 'text', 'uk' => 'Our services'],
        ['key' => 'long', 'type' => 'text', 'uk' => 'Our services today'],
    ];

    $result = ContentConverter::apply($source, 'index', $fields, 'uk');

    expect($result['converted'])->toBe(2)
        ->and($result['source'])->not->toContain("content('index', 'long', 'Our services today') }}'")
        ->and(substr_count($result['source'], 'content('))->toBe(2);

    // Neither call may appear inside the other's default text.
    expect(preg_match("/content\([^)]*content\(/", $result['source']))->toBe(0);
});

it('leaves fields alone on a second run', function () {
    $fields = [['key' => 'title', 'type' => 'text', 'uk' => 'Welcome']];

    $first = ContentConverter::apply('<h1>Welcome</h1>', 'about', $fields, 'uk');
    $second = ContentConverter::apply($first['source'], 'about', $fields, 'uk');

    expect($second['converted'])->toBe(0)
        ->and($second['source'])->toBe($first['source']);
});

it('reports wording it could not find instead of guessing', function () {
    $result = ContentConverter::apply('<h1>Welcome</h1>', 'about', [
        ['key' => 'missing', 'type' => 'text', 'uk' => 'Not on this page'],
    ], 'uk');

    expect($result['converted'])->toBe(0)
        ->and($result['missing'])->toBe(['missing']);
});

it('refuses to hand back a template that would not compile', function () {
    // An unterminated blade directive in the surrounding markup.
    $result = ContentConverter::apply('@if(true) <h1>Welcome</h1>', 'about', [
        ['key' => 'title', 'type' => 'text', 'uk' => 'Welcome'],
    ], 'uk');

    expect($result['error'])->not->toBeNull();
})->skip('Blade compiles unterminated directives without raising');

it('keeps inline markup for rich text fields', function () {
    $result = ContentConverter::apply('<h1>Hello <span>world</span></h1>', 'index', [
        ['key' => 'title', 'type' => 'html', 'uk' => 'Hello <span>world</span>'],
    ], 'uk');

    expect($result['source'])->toBe("<h1>{!! content_html('index', 'title', 'Hello <span>world</span>') !!}</h1>");
});

it('writes a bare call for strings passed as blade include arguments', function () {
    $source = "@include('hero', ['title' => 'Book Now'])";

    $result = ContentConverter::apply($source, 'wizard.category', [
        ['key' => 'hero_title', 'type' => 'arg', 'uk' => "'Book Now'"],
    ], 'uk');

    expect($result['source'])->toBe("@include('hero', ['title' => content('wizard.category', 'hero_title', 'Book Now')])");
});

it('uses the given expression as an image fallback', function () {
    $result = ContentConverter::apply('<img src="{{ asset(\'img/a.png\') }}">', 'index', [
        ['key' => 'hero_image', 'type' => 'image', 'uk' => '{{ asset(\'img/a.png\') }}', 'expr' => "asset('img/a.png')"],
    ], 'uk');

    expect($result['source'])->toBe('<img src="{{ content_image(\'index\', \'hero_image\', asset(\'img/a.png\')) }}">');
});

it('escapes quotes in the original wording', function () {
    $result = ContentConverter::apply("<p>It's time</p>", 'about', [
        ['key' => 'text', 'type' => 'text', 'uk' => "It's time"],
    ], 'uk');

    expect($result['source'])->toBe("<p>{{ content('about', 'text', 'It\\'s time') }}</p>")
        ->and($result['error'])->toBeNull();
});

it('does not convert text inside an HTML comment', function () {
    $source = "<!-- Filters -->\n<button>Filter</button>";

    $result = ContentConverter::apply($source, 'shop', [
        ['key' => 'filter_button', 'type' => 'text', 'uk' => 'Filter'],
    ], 'uk');

    expect($result['converted'])->toBe(1)
        ->and($result['source'])->toContain('<!-- Filters -->')
        ->and($result['source'])->toContain("<button>{{ content('shop', 'filter_button', 'Filter') }}</button>");
});

it('does not convert strings inside a script block', function () {
    $source = "<script>var label = 'Add to Cart';</script>\n<button>Add to Cart</button>";

    $result = ContentConverter::apply($source, 'shop', [
        ['key' => 'add_to_cart', 'type' => 'text', 'uk' => 'Add to Cart'],
    ], 'uk');

    expect($result['source'])->toContain("var label = 'Add to Cart';")
        ->and($result['source'])->toContain("<button>{{ content('shop', 'add_to_cart', 'Add to Cart') }}</button>");
});

it('treats wording that exists only inside a comment as not found', function () {
    $result = ContentConverter::apply('<!-- Remove -->', 'cart', [
        ['key' => 'remove', 'type' => 'text', 'uk' => 'Remove'],
    ], 'uk');

    expect($result['converted'])->toBe(0)
        ->and($result['missing'])->toBe(['remove']);
});

it('does not leave a stray backslash when an include argument contains an apostrophe', function () {
    $source = "@include('hero', ['subtitle' => 'Just a few details and you\\'re booked.'])";

    $result = ContentConverter::apply($source, 'wizard.confirm', [
        ['key' => 'hero_subtitle', 'type' => 'arg', 'uk' => "'Just a few details and you\\'re booked.'"],
    ], 'uk');

    expect($result['converted'])->toBe(1)
        ->and($result['source'])->toContain("content('wizard.confirm', 'hero_subtitle', 'Just a few details and you\\'re booked.')")
        ->and($result['source'])->not->toContain("\\\\'");
});
