@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    @include('Themes.wizard.partials.hero', [
        'step' => 5,
        'title' => 'Book an Appointment',
        'subtitle' => 'Just a few details and you\'re booked.',
    ])

    <main class="py-16 bg-slate-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Booking Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-brand-teal text-white p-6 rounded-2xl space-y-4 sticky top-24 shadow-lg">
                        <h2 class="text-lg font-serif font-bold">Your Appointment</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-slate-300">Service</span>
                                <span class="font-semibold text-right">{{ $service->title }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-slate-300">Category</span>
                                <span class="font-semibold">{{ $service->category->name }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-slate-300">Practitioner</span>
                                <span class="font-semibold">{{ $instructor->name }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-slate-300">Date</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse(request('booking_date'))->format('l, M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-slate-300">Time</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse(request('booking_time'))->format('g:i A') }}</span>
                            </div>
                            @if($donationAddon > 0)
                                <div class="flex justify-between gap-4">
                                    <span class="text-slate-300">Gift of Ruqyah</span>
                                    <span class="font-semibold">£{{ number_format($donationAddon, 2) }}</span>
                                </div>
                            @endif
                            <div class="border-t border-white/15 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-slate-300">Total</span>
                                    @if($service->price_type === 'FIXED')
                                        <span class="text-xl font-serif font-bold text-brand-gold">£{{ number_format($service->price_value + $donationAddon, 2) }}</span>
                                    @elseif($service->price_type === 'DONATION')
                                        <span class="text-xl font-serif font-bold text-brand-gold">Min. £{{ number_format($service->min_donation + $donationAddon, 2) }}</span>
                                    @elseif($service->price_type === 'RESERVATION' && $donationAddon <= 0)
                                        <span class="text-sm font-bold text-yellow-400">Assessment Required</span>
                                    @elseif($service->price_type === 'FREE' && $donationAddon <= 0)
                                        <span class="text-xl font-serif font-bold text-green-400">Free</span>
                                    @else
                                        <span class="text-xl font-serif font-bold text-brand-gold">£{{ number_format($donationAddon, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <form method="POST" action="{{ route('wizard.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">
                        <input type="hidden" name="booking_date" value="{{ request('booking_date') }}">
                        <input type="hidden" name="booking_time" value="{{ request('booking_time') }}">
                        <input type="hidden" name="donation_addon" value="{{ $donationAddon }}">

                        <!-- Section 1: Your Information -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                            @include('Themes.wizard.partials.section-header', ['n' => 1, 'title' => 'Your Information'])
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">First Name <span class="text-brand-crimson">*</span></label>
                                    <input type="text" name="first_name" required value="{{ old('first_name') }}" placeholder="First name"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 bg-white">
                                    @error('first_name') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Last Name <span class="text-brand-crimson">*</span></label>
                                    <input type="text" name="last_name" required value="{{ old('last_name') }}" placeholder="Last name"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 bg-white">
                                    @error('last_name') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Phone <span class="text-brand-crimson">*</span></label>
                                    <div class="flex gap-2">
                                        <select name="phone_country" class="w-24 px-2 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                            <option value="GB" {{ old('phone_country') == 'GB' ? 'selected' : '' }}>🇬🇧 +44</option>
                                            <option value="US" {{ old('phone_country') == 'US' ? 'selected' : '' }}>🇺🇸 +1</option>
                                            <option value="BD" {{ old('phone_country') == 'BD' ? 'selected' : '' }}>🇧🇩 +880</option>
                                            <option value="PK" {{ old('phone_country') == 'PK' ? 'selected' : '' }}>🇵🇰 +92</option>
                                            <option value="SA" {{ old('phone_country') == 'SA' ? 'selected' : '' }}>🇸🇦 +966</option>
                                            <option value="AE" {{ old('phone_country') == 'AE' ? 'selected' : '' }}>🇦🇪 +971</option>
                                            <option value="IN" {{ old('phone_country') == 'IN' ? 'selected' : '' }}>🇮🇳 +91</option>
                                            <option value="TR" {{ old('phone_country') == 'TR' ? 'selected' : '' }}>🇹🇷 +90</option>
                                        </select>
                                        <input type="tel" name="phone_number" required value="{{ old('phone_number') }}" placeholder="Phone number"
                                            class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 bg-white">
                                    </div>
                                    @error('phone_number') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Email <span class="text-brand-crimson">*</span></label>
                                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="your@email.com"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 bg-white">
                                    @error('email') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: More About You -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                            @include('Themes.wizard.partials.section-header', ['n' => 2, 'title' => 'More About You'])
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Gender <span class="text-brand-crimson">*</span></label>
                                    <select name="gender" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                        <option value="">Select an option</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Language <span class="text-brand-crimson">*</span></label>
                                    <select name="language" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                        <option value="">Select an option</option>
                                        <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="Arabic" {{ old('language') == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                                        <option value="Urdu" {{ old('language') == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                                        <option value="Bengali" {{ old('language') == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                                        <option value="Turkish" {{ old('language') == 'Turkish' ? 'selected' : '' }}>Turkish</option>
                                        <option value="Somali" {{ old('language') == 'Somali' ? 'selected' : '' }}>Somali</option>
                                        <option value="Malay" {{ old('language') == 'Malay' ? 'selected' : '' }}>Malay</option>
                                        <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                                        <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Ethnic Origin <span class="text-brand-crimson">*</span></label>
                                    <input type="text" name="ethnic_origin" required value="{{ old('ethnic_origin') }}" placeholder="e.g. Arab, South Asian, Black African..."
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Age Range <span class="text-brand-crimson">*</span></label>
                                    <select name="age" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                        <option value="">Select an option</option>
                                        <option value="under_18" {{ old('age') == 'under_18' ? 'selected' : '' }}>Under 18</option>
                                        <option value="18-25" {{ old('age') == '18-25' ? 'selected' : '' }}>18 – 25</option>
                                        <option value="26-35" {{ old('age') == '26-35' ? 'selected' : '' }}>26 – 35</option>
                                        <option value="36-45" {{ old('age') == '36-45' ? 'selected' : '' }}>36 – 45</option>
                                        <option value="46-55" {{ old('age') == '46-55' ? 'selected' : '' }}>46 – 55</option>
                                        <option value="56-65" {{ old('age') == '56-65' ? 'selected' : '' }}>56 – 65</option>
                                        <option value="65_plus" {{ old('age') == '65_plus' ? 'selected' : '' }}>65+</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Mahram / Guardian (highlighted — policy-critical) -->
                        <div class="bg-white border-2 border-brand-gold/50 rounded-2xl p-6 sm:p-7 relative">
                            <span class="absolute -top-3 left-6 bg-brand-crimson text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">Required policy</span>
                            @include('Themes.wizard.partials.section-header', [
                                'n' => 3,
                                'title' => 'Who Will Accompany You?',
                                'hint' => 'We do NOT see clients alone — you must bring a Mahram/Guardian with you, or your appointment will not go ahead.',
                            ])
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Gender <span class="text-brand-crimson">*</span></label>
                                    <select name="guardian_gender" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                        <option value="">Select an option</option>
                                        <option value="male" {{ old('guardian_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('guardian_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Name</label>
                                    <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="Full name"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Relationship</label>
                                    <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship') }}" placeholder="e.g. Father, Brother, Husband..."
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Contact Number</label>
                                    <input type="tel" name="guardian_phone" value="{{ old('guardian_phone') }}" placeholder="Different from above"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Symptoms -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                            @include('Themes.wizard.partials.section-header', [
                                'n' => 4,
                                'title' => 'Symptoms',
                                'hint' => 'Tap all that apply.',
                            ])
                            @php
                                $symptomOptions = [
                                    'Regular nightmares',
                                    'Reaction/aversion to Qur\'an/salah',
                                    'Body pains',
                                    'Headaches',
                                    'Stomach issues',
                                    'Sleep paralysis',
                                    'Insomnia',
                                    'Hear voices/feel presences',
                                    'Self harming/self neglect',
                                    'Anger issues',
                                    'Argumentation',
                                    'Marital/family issues',
                                    'Anxiety/Depression',
                                    'Current or previous use of taweez or similar in family',
                                    'Current or previous substance use',
                                    'Failing to pray 5 times Salah',
                                    'Diagnosed with mental illness or autism',
                                    'Have medical issues',
                                    'Social Services involved in some aspect',
                                ];
                            @endphp
                            <div class="flex flex-wrap gap-2 mb-5">
                                @foreach($symptomOptions as $symptom)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom }}" {{ in_array($symptom, old('symptoms', [])) ? 'checked' : '' }} class="peer sr-only">
                                        <span class="inline-block text-sm px-3.5 py-2 rounded-full border border-slate-200 text-slate-600 peer-checked:bg-brand-teal peer-checked:text-white peer-checked:border-brand-teal transition">{{ $symptom }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Other</label>
                                <textarea name="symptoms_other" rows="3" placeholder="Any other symptoms or details..."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">{{ old('symptoms_other') }}</textarea>
                            </div>
                        </div>

                        <!-- Section 5: How did you find us -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                            @include('Themes.wizard.partials.section-header', [
                                'n' => 5,
                                'title' => 'How Did You Find Us?',
                                'hint' => 'Select at least one.',
                            ])
                            @php
                                $foundViaOptions = [
                                    'Search engine',
                                    'Family/friend',
                                    'East London Mosque',
                                    'Other mosque/Imam',
                                    'Received leaflet',
                                    'Social media',
                                    'Advert on ELM or Islam21C website',
                                    'Another practitioner/professional',
                                    'Returning patient',
                                ];
                            @endphp
                            <div class="flex flex-wrap gap-2">
                                @foreach($foundViaOptions as $option)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="found_via[]" value="{{ $option }}" {{ in_array($option, old('found_via', [])) ? 'checked' : '' }} class="peer sr-only">
                                        <span class="inline-block text-sm px-3.5 py-2 rounded-full border border-slate-200 text-slate-600 peer-checked:bg-brand-teal peer-checked:text-white peer-checked:border-brand-teal transition">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section 6: First Appointment -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                            @include('Themes.wizard.partials.section-header', [
                                'n' => 6,
                                'title' => 'Your Visit',
                                'hint' => 'If this is your first appointment we may move you to another practitioner if we feel it will be more appropriate.',
                            ])
                            @php
                                $visitOptions = [
                                    'Yes',
                                    'Returning patient',
                                    'Self Ruqyah',
                                    'Visited another centre',
                                    'Raaqi visited home',
                                ];
                            @endphp
                            <div class="flex flex-wrap gap-2">
                                @foreach($visitOptions as $option)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="is_first_appointment" value="{{ $option }}" {{ old('is_first_appointment') == $option ? 'checked' : '' }} class="peer sr-only">
                                        <span class="inline-block text-sm px-3.5 py-2 rounded-full border border-slate-200 text-slate-600 peer-checked:bg-brand-gold peer-checked:text-white peer-checked:border-brand-gold transition">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section 7: Inquiry Description -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                            @include('Themes.wizard.partials.section-header', ['n' => 7, 'title' => 'Tell Us More'])
                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Describe Your Inquiry</label>
                                <textarea name="inquiry_description" rows="4" placeholder="Please share any additional information you'd like us to know..."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">{{ old('inquiry_description') }}</textarea>
                            </div>
                        </div>

                        <!-- Section 8: Consent -->
                        <label class="flex items-start gap-3 cursor-pointer px-1">
                            <input type="checkbox" name="consent_updates" value="1" {{ old('consent_updates') ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-teal focus:ring-brand-gold">
                            <span class="text-sm text-slate-600">I consent to receive DK Healing Centre updates and promotions by email, SMS, phone, and WhatsApp.</span>
                        </label>

                        <!-- Submit Button -->
                        <div class="text-center pt-4">
                            @if($service->price_type !== 'FREE' && $service->price_type !== 'RESERVATION' || $donationAddon > 0)
                                <button type="submit" class="w-full sm:w-auto bg-brand-gold hover:bg-brand-goldDark text-white px-12 py-4 rounded-full font-semibold text-sm transition shadow">
                                    Continue to Payment →
                                </button>
                            @elseif($service->price_type === 'RESERVATION')
                                <button type="submit" class="w-full sm:w-auto bg-brand-teal hover:bg-brand-navy text-white px-12 py-4 rounded-full font-semibold text-sm transition shadow">
                                    Request Assessment & Book
                                </button>
                            @else
                                <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-12 py-4 rounded-full font-semibold text-sm transition shadow">
                                    Book Free Consultation
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
