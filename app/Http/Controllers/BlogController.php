<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        $posts = BlogPost::with(['comments' => function ($query) {
            $query->latest()->limit(5);
        }])->where('status', 'published')->latest()->get(); // ->paginate(10);

        return Inertia::render('blog/index', [
            'posts' => $posts,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('blog/create');
    }

    public function store(Request $request): RedirectResponse
    {
        $slug = Str::slug($request->title);
        $request->merge(['slug' => $slug]);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|string|in:draft,published,archived',
        ]);

        $validated['author_id'] = auth()->id();

        BlogPost::create($validated);

        return redirect()->route('blog.index')->with('success', 'Post created successfully.');
    }

    public function show(BlogPost $post): Response
    {
        $post->load(['comments' => function ($query) {
            $query->where('approved', true)->latest();
        }]);

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }

    public function edit(BlogPost $post): Response
    {
        return Inertia::render('Blog/Edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,'.$post->id,
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|string|in:draft,published,archived',
        ]);

        $post->update($validated);

        return redirect()->route('blog.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('blog.index')->with('success', 'Post deleted successfully.');
    }

    public function storeComment(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $validated['post_id'] = $post->id;

        BlogComment::create($validated);

        return back()->with('success', 'Comment submitted for approval.');
    }
}
