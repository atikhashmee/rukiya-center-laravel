@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Breadcrumb -->
    <section class="bg-brand-cream/50 border-b border-brand-gold/10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-xs text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-brand-teal transition">Home</a>
                <span>/</span>
                <a href="{{ route('posts.index') }}" class="hover:text-brand-teal transition">Blog</a>
                <span>/</span>
                <span class="text-brand-teal font-medium">{{ $post->title }}</span>
            </nav>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <article>
                <span class="text-xs text-slate-400 uppercase tracking-wider">
                    {{ $post->created_at->format('l, F j, Y') }}
                    @if($post->author)
                        &middot; By {{ $post->author->name }}
                    @endif
                </span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-brand-teal leading-tight mt-2 mb-6">{{ $post->title }}</h1>

                @if($post->featured_image)
                    <div class="rounded-2xl overflow-hidden mb-8 bg-brand-cream/50">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full max-h-[420px] object-cover">
                    </div>
                @endif

                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                    {!! $post->content !!}
                </div>
            </article>

            <!-- Comments -->
            <section class="mt-16 border-t border-brand-gold/15 pt-10">
                <h2 class="text-2xl font-serif font-bold text-brand-teal mb-6">
                    {{ $post->comments->count() }} {{ \Illuminate\Support\Str::plural('Comment', $post->comments->count()) }}
                </h2>

                <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 mb-10">
                    <h3 class="text-lg font-serif font-bold text-brand-teal mb-4">Leave a Comment</h3>

                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <ul class="list-disc list-inside text-xs text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('posts.comment.store', $post) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Name <span class="text-brand-crimson">*</span></label>
                                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Your name"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com (optional)"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-brand-teal mb-1">Comment <span class="text-brand-crimson">*</span></label>
                            <textarea name="comment" rows="4" required placeholder="Share your thoughts..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">{{ old('comment') }}</textarea>
                        </div>
                        <p class="text-xs text-slate-400">Comments are reviewed before they appear publicly.</p>
                        <button type="submit" class="bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                            Submit Comment
                        </button>
                    </form>
                </div>

                @if($post->comments->count() > 0)
                    <div class="space-y-6">
                        @foreach($post->comments as $comment)
                            <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-brand-teal text-sm">{{ $comment->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $comment->created_at->format('M j, Y') }}</span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Recent Posts -->
            @if($recentPosts->count() > 0)
                <section class="mt-20">
                    <h2 class="text-2xl font-serif font-bold text-brand-teal mb-8">More Articles</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($recentPosts as $item)
                            <a href="{{ route('posts.show', $item->slug) }}" class="group bg-brand-cream/30 border border-brand-gold/15 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative h-32 bg-brand-cream/50 flex items-center justify-center overflow-hidden">
                                    @if($item->featured_image)
                                        <img src="{{ $item->featured_image }}" alt="{{ $item->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <svg class="w-8 h-8 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                                    @endif
                                </div>
                                <div class="p-4 space-y-1">
                                    <h3 class="font-serif font-bold text-brand-teal text-sm leading-tight">{{ $item->title }}</h3>
                                    <span class="text-xs text-slate-400">{{ $item->created_at->format('M j, Y') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection
