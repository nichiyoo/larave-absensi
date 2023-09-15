@php
    use Illuminate\Support\Carbon;
    
    $checkin_target = Carbon::parse($rekap->tanggal)
        ->timezone('Asia/Jakarta')
        ->setHour($rekap->shift == 'pagi' ? 7 : ($rekap->shift == 'siang' ? 15 : 23))
        ->setMinute(0)
        ->setSecond(0);
    
    $checkout_target = Carbon::parse($rekap->shift == 'malam' ? $rekap->tanggal->addDay() : $rekap->tanggal)
        ->timezone('Asia/Jakarta')
        ->setHour($rekap->shift == 'pagi' ? 15 : ($rekap->shift == 'siang' ? 23 : 7))
        ->setMinute(0)
        ->setSecond(0);
    
    if ($checkin == null) {
        $checkin_status = 'Not Checked In';
        $checkin_status_class = 'bg-gray-300 text-zinc-800';
    } else {
        $checkin_time = Carbon::parse($checkin?->created_at);
    
        // check late
        if ($checkin_time->gt($checkin_target)) {
            $checkin_status = 'Late';
            $checkin_status_class = 'bg-red-300 text-zinc-800';
        }
    
        // check on time
        elseif ($checkin_time->diffInMinutes($checkin_target) <= 60) {
            $checkin_status = 'On Time';
            $checkin_status_class = 'bg-secondary-200 text-zinc-800';
        }
    
        // check early
        else {
            $checkin_status = 'Early';
            $checkin_status_class = 'bg-blue-300 text-zinc-800';
        }
    }
    
    if ($checkout == null) {
        $checkout_status = 'Not Checked Out';
        $checkout_status_class = 'bg-gray-300 text-zinc-800';
    } else {
        $checkout_time = Carbon::parse($checkout?->created_at);
    
        // check late
        if ($checkout_time->gt($checkout_target)) {
            $checkout_status = 'Late';
            $checkout_status_class = 'bg-blue-300 text-zinc-800';
        }
    
        // check on time
        elseif ($checkout_time->diffInMinutes($checkout_target) <= 60) {
            $checkout_status = 'On Time';
            $checkout_status_class = 'bg-secondary-200 text-zinc-800';
        }
    
        // check early
        else {
            $checkout_status = 'Early';
            $checkout_status_class = 'bg-red-300 text-zinc-800';
        }
    }
@endphp

<x-app-layout>
    <x-slot name="title">
        {{ __('Absensi TKNO') }}
    </x-slot>

    @push('meta-tags')
        <meta name="title" content="Absensi TKNP">
        <meta name="description" content="Absensi untuk pekerja TKNO">
        <meta name="keywords" content="phonska,absensi,tkno">
    @endpush

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200">
            {{ __('Absensi TKNO') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6 px-3 sm:px-0">
                @include('absens.create')

                <!-- Session Status -->
                @if (session('status'))
                    <p x-data="{ flash: true }" x-show="flash" x-transition x-init="setTimeout(() => flash = false, 5000)"
                        class="text-sm text-phonska-800 dark:text-zinc-200">
                        {{ session('status') }}
                    </p>
                @endif
            </div>


            <!-- download csv button to 'rekanans.download' route -->
            <div class="flex items-end justify-between mb-6 px-3 sm:px-0">

                <!-- Change Shift -->
                <form method="post" action="{{ route('rekap-absens.update', ['rekap_absen' => $rekap]) }}">
                    @csrf
                    @method('patch')

                    <x-select-menu name="shift" id="shift" class="w-[200px]" onchange="this.form.submit()">
                        <option value="pagi" @if ($rekap->shift == 'pagi') selected @endif>Pagi</option>
                        <option value="siang" @if ($rekap->shift == 'siang') selected @endif>Siang</option>
                        <option value="malam" @if ($rekap->shift == 'malam') selected @endif>Malam</option>
                    </x-select-menu>
                    <x-input-error class="mt-2" :messages="$errors->get('shift')" />
                </form>

                <!-- Today's Date -->
                <p class="text-sm text-zinc-500 dark:text-zinc-400 text-end whitespace-nowrap">
                    {{ Carbon::parse($rekap->tanggal)->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Checkin Details -->
                <div
                    class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-5 border border-zinc-200 dark:border-zinc-700">
                    <div class="relative w-full aspect-[4/3] mb-4 rounded-lg overflow-hidden">
                        <img src="{{ $checkin?->photo }}" alt="checkin photo"
                            class="w-full h-full object-cover object-center bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center
                            text-zinc-500 dark:text-zinc-400">
                        <span
                            class="absolute bottom-0 right-0 {{ $checkin_status_class }} text-xs rounded-full px-2 py-1 m-4 font-semibold tracking-wide">
                            {{ $checkin_status }}
                        </span>
                    </div>

                    <div class="px-2">
                        <h5 class="font-semibold text-zinc-800 dark:text-zinc-100 mb-4">Checkin Details</h5>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Nama</p>
                            <p class="text-zinc-800 dark:text-zinc-100">{{ Auth::user()->name }}</p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Time</p>
                            <p class="text-zinc-800 dark:text-zinc-100">
                                {{ $checkin?->created_at->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM Y - HH:mm') }}
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Shift Starts</p>
                            <p class="text-zinc-800 dark:text-zinc-100">
                                {{ $checkin_target->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM Y - HH:mm') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Longitude</p>
                            <p class="text-zinc-800 dark:text-zinc-100">{{ $checkin?->longitude }}</p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Latitude</p>
                            <p class="text-zinc-800 dark:text-zinc-100">{{ $checkin?->latitude }}</p>
                        </div>
                    </div>
                </div>

                <!-- Checkout Details -->
                <div
                    class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-5 border border-zinc-200 dark:border-zinc-700">
                    <div class="relative w-full aspect-[4/3] mb-4 rounded-lg overflow-hidden">
                        <img src="{{ $checkout?->photo }}" alt="checkin photo"
                            class="w-full h-full object-cover object-center bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center
                            text-zinc-500 dark:text-zinc-400">
                        <span
                            class="absolute bottom-0 right-0 {{ $checkout_status_class }} text-xs rounded-full px-2 py-1 m-4 font-semibold tracking-wide">
                            {{ $checkout_status }}
                        </span>
                    </div>

                    <div class="px-2">
                        <h5 class="font-semibold text-zinc-800 dark:text-zinc-100 mb-4">Checkout Details</h5>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Nama</p>
                            <p class="text-zinc-800 dark:text-zinc-100">{{ Auth::user()->name }}</p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Time</p>
                            <p class="text-zinc-800 dark:text-zinc-100">
                                {{ $checkout?->created_at->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM Y - HH:mm') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Shift Ends</p>
                            <p class="text-zinc-800 dark:text-zinc-100">
                                {{ $checkout_target->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM Y - HH:mm') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Longitude</p>
                            <p class="text-zinc-800 dark:text-zinc-100">{{ $checkout?->longitude }}</p>
                        </div>

                        <div class="flex items-center space-x-4 text-sm mb-2">
                            <p class="text-zinc-500 dark:text-zinc-400 w-[100px]">Latitude</p>
                            <p class="text-zinc-800 dark:text-zinc-100">{{ $checkout?->latitude }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
