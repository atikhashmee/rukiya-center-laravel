@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Book an Appointment</h1>
            <p class="text-slate-300 text-sm sm:text-base">Step 5 — Complete your booking</p>
            <div class="flex items-center justify-center gap-2 pt-4">
                <span class="w-8 h-8 rounded-full bg-white/30 text-white flex items-center justify-center text-sm font-bold">✓</span>
                <span class="w-8 h-0.5 bg-brand-gold"></span>
                <span class="w-8 h-8 rounded-full bg-white/30 text-white flex items-center justify-center text-sm font-bold">✓</span>
                <span class="w-8 h-0.5 bg-brand-gold"></span>
                <span class="w-8 h-8 rounded-full bg-white/30 text-white flex items-center justify-center text-sm font-bold">✓</span>
                <span class="w-8 h-0.5 bg-brand-gold"></span>
                <span class="w-8 h-8 rounded-full bg-white/30 text-white flex items-center justify-center text-sm font-bold">✓</span>
                <span class="w-8 h-0.5 bg-brand-gold"></span>
                <span class="w-8 h-8 rounded-full bg-brand-gold text-white flex items-center justify-center text-sm font-bold">5</span>
            </div>
        </div>
    </section>

    <main class="py-16 bg-white">
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
                    <div class="bg-brand-cream/50 border border-brand-gold/20 p-6 rounded-2xl space-y-4 sticky top-24">
                        <h2 class="text-lg font-serif font-bold text-brand-teal">Your Appointment</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Service:</span>
                                <span class="font-semibold text-brand-teal text-right">{{ $service->title }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Category:</span>
                                <span class="font-semibold text-brand-teal capitalize">{{ $service->category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Instructor:</span>
                                <span class="font-semibold text-brand-teal">{{ $instructor->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Date:</span>
                                <span class="font-semibold text-brand-teal">{{ \Carbon\Carbon::parse(request('booking_date'))->format('l, M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Time:</span>
                                <span class="font-semibold text-brand-teal">{{ \Carbon\Carbon::parse(request('booking_time'))->format('g:i A') }}</span>
                            </div>
                            <div class="border-t border-brand-gold/20 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-slate-600">Total:</span>
                                    @if($service->price_type === 'FIXED')
                                        <span class="text-lg font-bold text-brand-teal">£{{ number_format($service->price_value, 2) }}</span>
                                    @elseif($service->price_type === 'DONATION')
                                        <span class="text-lg font-bold text-brand-teal">Min. £{{ number_format($service->min_donation, 2) }}</span>
                                    @elseif($service->price_type === 'RESERVATION')
                                        <span class="text-sm font-bold text-yellow-600">Assessment Required</span>
                                    @else
                                        <span class="text-lg font-bold text-green-700">Free</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <form method="POST" action="{{ route('wizard.store') }}" class="space-y-8">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">
                        <input type="hidden" name="booking_date" value="{{ request('booking_date') }}">
                        <input type="hidden" name="booking_time" value="{{ request('booking_time') }}">

                        <!-- Section 1: Your Information -->
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <h3 class="text-lg font-serif font-bold text-brand-teal">Your Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">First Name <span class="text-brand-crimson">*</span></label>
                                    <input type="text" name="first_name" required value="{{ old('first_name') }}" placeholder="First name"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                    @error('first_name') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Last Name <span class="text-brand-crimson">*</span></label>
                                    <input type="text" name="last_name" required value="{{ old('last_name') }}" placeholder="Last name"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
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
                                            class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                    </div>
                                    @error('phone_number') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-brand-teal mb-1">Email <span class="text-brand-crimson">*</span></label>
                                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="your@email.com"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                    @error('email') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: More About You -->
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <h3 class="text-lg font-serif font-bold text-brand-teal">More About You</h3>
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
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
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

                        <!-- Section 3: Mahram / Guardian -->
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <div>
                                <h3 class="text-lg font-serif font-bold text-brand-teal">Who Will Accompany You? (Mahram / Guardian)</h3>
                                <p class="text-xs text-slate-500 mt-1">We DO NOT see people alone — you must bring someone with you to the appointment otherwise we will NOT see you.</p>
                            </div>
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
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <div>
                                <h3 class="text-lg font-serif font-bold text-brand-teal">Symptoms</h3>
                                <p class="text-xs text-slate-500 mt-1">Please tick all the relevant symptoms</p>
                            </div>
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
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($symptomOptions as $symptom)
                                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:bg-brand-cream/50 transition cursor-pointer">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom }}" {{ in_array($symptom, old('symptoms', [])) ? 'checked' : '' }}
                                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-teal focus:ring-brand-gold">
                                        <span class="text-sm text-slate-700">{{ $symptom }}</span>
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
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <div>
                                <h3 class="text-lg font-serif font-bold text-brand-teal">How Did You Find Us?</h3>
                                <p class="text-xs text-slate-500 mt-1">Please select at least one</p>
                            </div>
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
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($foundViaOptions as $option)
                                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:bg-brand-cream/50 transition cursor-pointer">
                                        <input type="checkbox" name="found_via[]" value="{{ $option }}" {{ in_array($option, old('found_via', [])) ? 'checked' : '' }}
                                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-teal focus:ring-brand-gold">
                                        <span class="text-sm text-slate-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section 6: First Appointment -->
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <div>
                                <h3 class="text-lg font-serif font-bold text-brand-teal">Your Visit</h3>
                                <p class="text-xs text-slate-500 mt-1">If this is your first appointment we may move your appointment with another practitioner if we feel it will be more appropriate.</p>
                            </div>
                            @php
                                $visitOptions = [
                                    'Yes',
                                    'Returning patient',
                                    'Self Ruqyah',
                                    'Visited another centre',
                                    'Raaqi visited home',
                                ];
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($visitOptions as $option)
                                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:bg-brand-cream/50 transition cursor-pointer">
                                        <input type="radio" name="is_first_appointment" value="{{ $option }}" {{ old('is_first_appointment') == $option ? 'checked' : '' }}
                                            class="mt-0.5 h-4 w-4 border-slate-300 text-brand-teal focus:ring-brand-gold">
                                        <span class="text-sm text-slate-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section 7: Inquiry Description -->
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6 space-y-5">
                            <h3 class="text-lg font-serif font-bold text-brand-teal">Tell Us More</h3>
                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Describe Your Inquiry</label>
                                <textarea name="inquiry_description" rows="4" placeholder="Please share any additional information you'd like us to know..."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">{{ old('inquiry_description') }}</textarea>
                            </div>
                        </div>

                        <!-- Section 8: Consent -->
                        <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-6">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="consent_updates" value="1" {{ old('consent_updates') ? 'checked' : '' }}
                                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-teal focus:ring-brand-gold">
                                <span class="text-sm text-slate-600">I consent to receive DK Healing Centre updates and promotions by email, SMS, phone, and WhatsApp.</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center pt-4">
                            @if($service->price_type !== 'FREE' && $service->price_type !== 'RESERVATION')
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
