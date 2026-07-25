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
        }])->latest()->get(); // ->paginate(10);

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|string|in:draft,published,archived',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['author_id'] = auth()->id();

        BlogPost::create($validated);

        return redirect()->route('blog.index')->with('success', 'Post created successfully.');
    }

    public function show(BlogPost $blog): Response
    {
        $blog->load(['allComments' => fn ($query) => $query->latest()]);
        $blog->setRelation('comments', $blog->allComments);

        return Inertia::render('blog/show', [
            'post' => $blog,
        ]);
    }

    public function edit(BlogPost $blog): Response
    {
        return Inertia::render('blog/edit', [
            'post' => $blog,
        ]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|string|in:draft,published,archived',
        ]);

        if ($validated['title'] !== $blog->title) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $blog->id);
        }

        $blog->update($validated);

        return redirect()->route('blog.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()->route('blog.index')->with('success', 'Post deleted successfully.');
    }

    public function approveComment(BlogComment $comment): RedirectResponse
    {
        $comment->update(['approved' => true]);

        return back()->with('success', 'Comment approved.');
    }

    public function destroyComment(BlogComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment removed.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            BlogPost::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
