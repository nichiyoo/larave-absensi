<x-app-layout>
    <x-slot name="title">
        {{ __('Absensi TKNO') }}
    </x-slot>

    @push('meta-tags')
        <meta name="title" content="Absensi TKNO">
        <meta name="description" content="Manajemen Absensi Pegawai TKNO">
        <meta name="keywords" content="phonska,absensi,tkno">
    @endpush

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200">
            {{ __('Absensi') }} {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8" x-data="{ rekap: null }" x-cloak>
            @include('absens.show')

            <!-- download csv button to 'users.download' route -->
            <div class="flex items-center justify-end mb-6 px-3 sm:px-0">
                <!-- Download CSV -->
                <form action="{{ route('rekaps.download') }}" method="POST">
                    @csrf
                    <x-button variant="secondary" type="submit">
                        Download
                    </x-button>
                </form>
            </div>

            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-zinc-800 sm:rounded-lg">
                <div class="border text-zinc-900 border-zinc-200 dark:text-zinc-100 dark:border-zinc-700">
                    <div id="table" class="w-full overflow-x-scroll">
                        <table class="w-full text-sm whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="font-bold text-left text-zinc-600 bg-zinc-100 dark:bg-zinc-700 dark:text-zinc-200">
                                    <th class="px-4 py-3">Action</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">NIK</th>
                                    <th class="px-4 py-3">Shift</th>
                                    <th class="px-4 py-3">Checkin Time</th>
                                    <th class="px-4 py-3">Checkout Location</th>
                                    <th class="px-4 py-3">Checkout Time</th>
                                    <th class="px-4 py-3">Checkout Location</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($rekaps->isEmpty())
                                    <tr>
                                        <td colspan="10" class="px-4 py-20 text-center">
                                            <p class="text-zinc-500 dark:text-zinc-400">
                                                No data found.
                                            </p>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($rekaps as $rekap)
                                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                            <td class="flex justify-center px-4 py-3">
                                                <i x-cloak x-on:click="rekap = {{ $rekap }}" data-lucide="eye"
                                                    class="w-5 h-5 cursor-pointer text-zinc-400 hover:text-secondary-200" />
                                            </td>

                                            <td class="px-4 py-3 min-w-[200px]">
                                                {{ $rekap->tanggal->timeZone('Asia/Jakarta')->format('d F Y') }}
                                            </td>

                                            <td class="px-4 py-3 min-w-[300px]">
                                                {{ $rekap->user->name }}
                                            </td>

                                            <td class="px-4 py-3 min-w-[250px]">
                                                {{ $rekap->user->nik }}
                                            </td>

                                            <td class="px-4 py-3 min-w-[250px]">
                                                {{ $rekap->shift }}
                                            </td>

                                            <td class="px-4 py-3 min-w-[250px]">
                                                {{ $rekap->checkin?->created_at?->timezone('Asia/Jakarta')->format('H:i:s') }}
                                            </td>

                                            <td class="px-4 py-3 min-w-[250px]">
                                                @if ($rekap->checkin)
                                                    {{ sprintf('%s, %s', number_format($rekap->checkin->latitude, 3), number_format($rekap->checkin->longitude, 3)) }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="px-4 py-3 min-w-[250px]">
                                                {{ $rekap->checkout?->created_at?->timezone('Asia/Jakarta')->format('H:i:s') }}
                                            </td>

                                            <td class="px-4 py-3 min-w-[250px]">
                                                @if ($rekap->checkout)
                                                    {{ sprintf('%s, %s', number_format($rekap->checkout->latitude, 3), number_format($rekap->checkout->longitude, 3)) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <div>
                {{ $rekaps->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
