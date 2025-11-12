    @extends('Themes.layouts.app')
    @push('css')
           <script src="https://unpkg.com/lucide@latest"></script>
    @endpush
    @section('content')
        <!-- HEADER / NAVIGATION (Consistent) -->
        <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
            @include('Themes.layouts.nav')
        </header>
        <!-- HEADER END -->
        <main class="flex-grow py-16 px-4 sm:px-8">
            <div class="max-w-7xl mx-auto">
                @if ($service_type == "counseling")
                    <div class="text-center mb-12">
                        <h1 class="text-5xl font-extrabold text-gray-900 mb-3">Spiritual Counseling Paths</h1>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Start your journey with a complimentary session to establish trust and clarity before committing to deep, transformative guidance.
                        </p>
                    </div>
                @elseif ($service_type == "rukiya")
                    <div class="text-center mb-12">
                        <h1 class="text-5xl font-extrabold text-gray-900 mb-3">Rukiya: Spiritual Healing & Restoration</h1>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                            Select the path that aligns with your spiritual needs. We offer gentle guidance for minor concerns and intensive intervention for complex challenges.
                        </p>
                    </div>
                @elseif ($service_type == "istekhara")
                <div class="text-center mb-12">
                    <h1 class="text-5xl font-extrabold text-gray-900 mb-3">Istekhara Guidance Options</h1>
                    <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                        Seek divine guidance and clarity for life's important decisions. Choose the path that matches the complexity of your situation.
                    </p>
                </div>
                @endif

                <!-- Service Selection Area -->
                <div id="service-selection" class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    @foreach ($services as $service)
                        <div id="free-option" class="bg-white rounded-2xl shadow-2xl p-8 border-t-8 border-indigo-600 cursor-pointer transition duration-300 hover:scale-[1.01] hover:shadow-indigo-400/50">
                            <div class="flex items-start mb-6">
                                <i data-lucide="{{ $service->icon }}" class="w-10 h-10 text-indigo-600 mr-4"></i>
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900">{{ $service->title }}</h2>
                                    <p class="text-md text-gray-500">{{ $service->tagline }}</p>
                                </div>
                            </div>
                            <p class="text-gray-700 mb-6 leading-relaxed">
                                {{ $service->description }}
                            </p>
                            <ul class="space-y-2 text-gray-600 mb-8">
                                @foreach ($service->features as $feature)
                                    <li class="flex items-center"><i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i> {{ $feature }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route("customer.book.preview", ["service" => $service->id]) }}" class="w-full px-4 py-3 text-lg font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition duration-300 shadow-lg transform hover:scale-[1.01]">
                                {{ $service->submit_button_text }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
@endsection

@push('scripts')
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
       
    </script>
@endpush