@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                Our Blog
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                Reflections, guidance, and insights from the DK Healing Centre team.
            </p>
        </div>
    </section>

    <!-- Blog Content -->
    <main class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if($posts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="group bg-brand-cream/30 border border-brand-gold/15 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
                            <div class="relative h-48 bg-brand-cream/50 flex items-center justify-center overflow-hidden">
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-12 h-12 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                                @endif
                            </div>
                            <div class="p-5 space-y-2 flex-1 flex flex-col">
                                <span class="text-[11px] text-slate-400 uppercase tracking-wider">
                                    {{ $post->created_at->format('M j, Y') }}
                                    @if($post->author)
                                        &middot; {{ $post->author->name }}
                                    @endif
                                </span>
                                <h3 class="text-lg font-serif font-bold text-brand-teal leading-tight">{{ $post->title }}</h3>
                                <p class="text-sm text-slate-500 leading-relaxed line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}</p>
                                <span class="inline-block mt-auto pt-2 text-brand-gold text-sm font-semibold group-hover:text-brand-goldDark transition">
                                    Read More →
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-brand-gold/30 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                    <h3 class="text-xl font-serif font-bold text-brand-teal mb-2">No posts yet</h3>
                    <p class="text-sm text-slate-500">Check back soon for new articles.</p>
                </div>
            @endif
        </div>
    </main>
@endsection
