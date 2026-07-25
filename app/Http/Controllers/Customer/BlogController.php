<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::where('status', 'published')
            ->with('author')
            ->withCount(['comments' => fn ($query) => $query->where('approved', true)])
            ->latest()
            ->get();

        return view('Themes.blog', compact('posts'));
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->status === 'published', 404);

        $post->load([
            'author',
            'comments' => fn ($query) => $query->where('approved', true)->latest(),
        ]);

        $recentPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('Themes.blog-show', compact('post', 'recentPosts'));
    }

    public function storeComment(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $post->comments()->create($validated);

        return back()->with('success', 'Comment submitted for approval.');
    }
}
