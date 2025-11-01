@extends('themes.layouts.layout')

@section('content')
    <main>

        <!-- Section 1: Image Left, Text Right -->
  <section class="py-16 px-4 md:px-8">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden md:flex">
      <div class="md:w-1/2">
        <img class="w-full h-full object-cover" src="https://picsum.photos/600/400" alt="Service Image 1">
      </div>
      <div class="md:w-1/2 p-8 flex flex-col justify-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Our First Service</h2>
        <p class="text-gray-600 leading-relaxed">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam non erat ut mauris tincidunt efficitur. Integer placerat, urna vel fermentum tincidunt, metus dolor luctus libero, a volutpat odio velit quis nisl.
        </p>
        <p class="text-gray-600 leading-relaxed mt-4">
          Phasellus eleifend ipsum sed purus dictum, a volutpat odio velit quis nisl. Proin ac mauris euismod, laoreet leo sit amet, tristique quam.
        </p>
      </div>
    </div>
  </section>

  <!-- Section 2: Image Right, Text Left -->
 <section class="py-16 px-4 md:px-8">
  <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden md:flex md:flex-row-reverse">
    <div class="md:w-1/2">
      <img class="w-full h-full object-cover" src="https://picsum.photos/600/400" alt="Service Image 2">
    </div>
    <div class="md:w-1/2 p-8 flex flex-col justify-center">
      <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Second Service</h2>
      <p class="text-gray-600 leading-relaxed">
        Suspendisse potenti. Fusce commodo magna eget nulla commodo, sit amet malesuada libero consequat. In hac habitasse platea dictumst.
      </p>
      <p class="text-gray-600 leading-relaxed mt-4">
        Curabitur tincidunt, lorem ac tincidunt sollicitudin, felis ex consectetur purus, a auctor elit turpis in elit.
      </p>
    </div>
  </div>
</section>
        <div class="w-300 mx-auto px-4 bg-yellow-700">
            <h1>Hello world</h1>
        </div>
         <!-- Counselling Page -->
        <div id="counselling" class="page">
            <section class="container">
                <div class="hero">
                    <h1>{{ $service_type }} Services</h1>
                    <p>Professional therapeutic support for your emotional and psychological well-being</p>
                </div>
                <div class="services-grid">
                    @foreach ($services as $service)
                        <div class="service-card flex flex-col">
                            <h3>{{ $service->name }}</h3>
                            <div class="price-tag">£{{ $service->price }} per session (60 minutes)</div>
                            <p>One-on-one sessions tailored to your personal needs and goals.</p>
                            <ul style="text-align: left; margin-top: 1rem;">
                                <li>Anxiety and Depression Support</li>
                                <li>Stress Management</li>
                                <li>Life Transitions</li>
                                <li>Relationship Issues</li>
                            </ul>
                            <a href="{{ route("customer.book.preview", ["service" => $service->id]) }}" class="border border-blue-500 p-2 hover:bg-blue-900 hover:text-white">Book Now</a>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </main>
@endsection