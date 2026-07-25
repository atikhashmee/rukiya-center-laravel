@php
    $firstDate = \Carbon\Carbon::parse($dates->first()['date']);
    $lastDate = \Carbon\Carbon::parse($dates->last()['date']);
    $startOfWeek = $firstDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $endOfWeek = $lastDate->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $calendarDates = [];
    $current = $startOfWeek->copy();
    while ($current <= $endOfWeek) {
        $dateStr = $current->toDateString();
        $availableDate = $dates->firstWhere('date', $dateStr);
        $calendarDates[] = [
            'date' => $dateStr,
            'day' => $current->day,
            'month' => $current->month,
            'available' => $availableDate ? true : false,
            'slots' => $availableDate['slots'] ?? [],
            'is_today' => $current->isToday(),
            'is_past' => $current->isPast() && !$current->isToday(),
            'in_range' => $current->between($firstDate, $lastDate) || $availableDate,
        ];
        $current->addDay();
    }
    $weeks = collect($calendarDates)->chunk(7);
@endphp

@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            @if($dates->isEmpty())
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-700 mb-2">No available dates</h3>
                    <p class="text-sm text-slate-500 mb-6">This instructor has no upcoming availability in the next 14 days.</p>
                    <a href="{{ route('wizard.instructor', ['serviceId' => $service->id]) }}" class="inline-block bg-brand-teal hover:bg-brand-navy text-white px-6 py-2.5 rounded font-semibold text-sm transition">Choose Another Instructor</a>
                </div>
            @else
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('wizard.instructor', ['serviceId' => $service->id]) }}"
                       class="text-brand-teal text-sm font-semibold hover:text-brand-gold transition tracking-wide">
                        &lsaquo; SELECT CALENDAR
                    </a>
                    <span class="text-brand-teal font-semibold text-sm tracking-wide">Date &amp; Time</span>
                </div>

                <form method="GET" action="{{ route('wizard.confirm') }}" id="scheduleForm">
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <input type="hidden" name="instructor_id" value="{{ $instructor->id ?? 'any' }}">
                    <input type="hidden" name="any_instructor" value="{{ $anyInstructor ? '1' : '0' }}">
                    <input type="hidden" name="booking_date" id="selectedDate" value="">
                    <input type="hidden" name="booking_time" id="selectedTime" value="">

                    <div class="text-[10px] font-bold text-brand-teal uppercase tracking-[0.15em] mb-3">Appointment</div>
                    <div class="bg-white border border-slate-200 rounded-lg p-6 relative mb-8 shadow-sm">
                        <a href="{{ route('wizard.instructor', ['serviceId' => $service->id]) }}"
                           class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-2xl leading-none transition">&times;</a>
                        <h3 class="text-lg font-semibold text-slate-800">{{ $service->title }}</h3>
                        <div class="text-slate-500 text-sm mb-3">
                            @if($service->price_type === 'FIXED')
                                £{{ number_format($service->price_value, 2) }}
                            @elseif($service->price_type === 'DONATION')
                                Min. £{{ number_format($service->min_donation, 2) }}
                            @elseif($service->price_type === 'RESERVATION')
                                <span class="text-yellow-600 font-semibold">Assessment Required</span>
                            @else
                                Free
                            @endif
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $service->description }}</p>
                    </div>

                    <div class="text-[10px] font-bold text-brand-teal uppercase tracking-[0.15em] mb-3">Date &amp; Time</div>
                    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            {{-- Calendar Grid --}}
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-semibold text-slate-700">{{ $firstDate->format('F Y') }}</span>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                                    <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
                                </div>
                                <div class="grid grid-cols-7 gap-1">
                                    @foreach($calendarDates as $calDate)
                                        @if($calDate['available'])
                                            <button type="button"
                                                class="date-btn aspect-square flex items-center justify-center text-sm font-medium text-slate-700 rounded hover:bg-brand-teal/10 transition cursor-pointer"
                                                data-date="{{ $calDate['date'] }}"
                                                data-slots='{{ json_encode($calDate['slots']) }}'>
                                                {{ $calDate['day'] }}
                                            </button>
                                        @else
                                            <span class="aspect-square flex items-center justify-center text-sm text-slate-300 rounded">
                                                {{ $calDate['day'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Time Slots --}}
                            <div class="border-t md:border-t-0 md:border-l border-slate-200 pt-6 md:pt-0 md:pl-8">
                                <div id="noDateSelected" class="text-center py-12 text-slate-400 text-sm">
                                    Select a date to see available times
                                </div>

                                <div id="timePanel" class="hidden">
                                    <div class="text-base font-semibold text-slate-700 mb-1" id="selectedDateDisplay"></div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-5">Time zone: London (GMT+01:00)</div>

                                    <div id="timeSlotsContainer" class="space-y-2"></div>

                                    <div id="noTimesMsg" class="hidden text-center py-8 text-slate-400 text-sm">
                                        No available time slots for this date.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center hidden" id="continueSection">
                        <button type="submit" class="bg-brand-gold hover:bg-brand-goldDark text-white px-10 py-3 rounded font-semibold text-sm transition shadow tracking-wide">
                            Continue to Confirmation &rarr;
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </main>

    @push('scripts')
    <script>
        const bookedSlots = @json($bookedSlots);
        let selectedDate = null;
        let selectedTime = null;

        document.querySelectorAll('.date-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.date-btn').forEach(b => {
                    b.classList.remove('bg-brand-teal', 'text-white');
                    b.classList.add('text-slate-700');
                });
                this.classList.remove('text-slate-700');
                this.classList.add('bg-brand-teal', 'text-white');

                selectedDate = this.dataset.date;
                document.getElementById('selectedDate').value = selectedDate;

                const slots = JSON.parse(this.dataset.slots || '[]');
                const booked = bookedSlots[selectedDate] || [];
                const container = document.getElementById('timeSlotsContainer');
                const noTimesMsg = document.getElementById('noTimesMsg');
                const timePanel = document.getElementById('timePanel');
                const noDateSelected = document.getElementById('noDateSelected');
                const continueSection = document.getElementById('continueSection');
                const dateDisplay = document.getElementById('selectedDateDisplay');

                const d = new Date(selectedDate + 'T12:00:00');
                const options = { weekday: 'long', month: 'long', day: 'numeric' };
                dateDisplay.textContent = d.toLocaleDateString('en-US', options);

                container.innerHTML = '';
                let hasSlots = false;

                slots.forEach(slot => {
                    const slotTime = slot.start_time.substring(0, 5);
                    if (!booked.includes(slotTime)) {
                        hasSlots = true;
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'time-slot-btn w-full text-left px-4 py-3 rounded border border-slate-200 text-sm font-medium text-slate-700 hover:border-brand-teal hover:text-brand-teal transition bg-white';
                        btn.dataset.time = slot.start_time;
                        btn.textContent = slot.display;
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot-btn').forEach(b => {
                                b.classList.remove('border-brand-teal', 'bg-brand-teal/5', 'text-brand-teal');
                                b.classList.add('border-slate-200');
                            });
                            this.classList.remove('border-slate-200');
                            this.classList.add('border-brand-teal', 'bg-brand-teal/5', 'text-brand-teal');
                            selectedTime = this.dataset.time;
                            document.getElementById('selectedTime').value = selectedTime;
                            continueSection.classList.remove('hidden');
                        });
                        container.appendChild(btn);
                    }
                });

                timePanel.classList.remove('hidden');
                noDateSelected.classList.add('hidden');
                noTimesMsg.classList.toggle('hidden', hasSlots);
                continueSection.classList.add('hidden');
                selectedTime = null;
                document.getElementById('selectedTime').value = '';
            });
        });
    </script>
    @endpush
@endsection
