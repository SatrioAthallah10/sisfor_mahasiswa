@extends('layouts.app')

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-body-sm text-on-surface-variant mb-6">
        <a href="{{ route('lecturer.attendance.index') }}" class="hover:text-primary transition-colors">{{ __('Absensi') }}</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-on-surface">{{ $session->name }}</span>
    </div>

    <div class="mb-8">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-1">{{ $session->name }}</h2>
        <p class="font-body-lg text-body-lg text-secondary">
            {{ $session->course?->name }} ({{ $session->course?->code }})
            @if($session->open_at)
                &nbsp;·&nbsp; {{ $session->open_at->format('d M Y, H:i') }}
                @if($session->close_at) — {{ $session->close_at->format('H:i') }} @endif
            @endif
        </p>
    </div>

    <div class="w-full h-1 border-t-2 border-on-surface mb-1"></div>
    <div class="w-full border-t border-outline-variant mb-8"></div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-primary-container text-on-primary-container rounded border border-primary">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Mahasiswa') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Waktu Absen') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Status') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($records as $record)
                        @php
                            $badgeClass = match($record->status) {
                                'diterima' => 'bg-primary-container text-on-primary-container',
                                'ditolak'  => 'bg-error-container text-on-error-container',
                                default    => 'bg-surface-container-high text-on-surface-variant',
                            };
                        @endphp
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-label-lg text-on-surface">{{ $record->user?->name }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $record->user?->email }}</p>
                            </td>
                            <td class="py-4 px-6 text-body-md text-on-surface-variant">
                                {{ $record->present_at?->format('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded text-label-sm font-label-sm {{ $badgeClass }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    @if($record->status !== 'diterima')
                                        <form action="{{ route('lecturer.attendance.approve', $record->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-2 bg-primary-container text-on-primary-container rounded font-label-sm text-label-sm uppercase tracking-wider hover:opacity-80 transition-opacity"
                                                    onclick="return confirm('Terima absensi ini?')">
                                                <span class="material-symbols-outlined text-sm align-middle">check_circle</span>
                                                Terima
                                            </button>
                                        </form>
                                    @endif

                                    @if($record->status !== 'ditolak')
                                        <form action="{{ route('lecturer.attendance.reject', $record->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-2 bg-error-container text-on-error-container rounded font-label-sm text-label-sm uppercase tracking-wider hover:opacity-80 transition-opacity"
                                                    onclick="return confirm('Tolak absensi ini?')">
                                                <span class="material-symbols-outlined text-sm align-middle">cancel</span>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-on-surface-variant font-body-lg">
                                {{ __('Belum ada mahasiswa yang absen di sesi ini.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-surface-container p-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                {{ $records->firstItem() ?? 0 }} - {{ $records->lastItem() ?? 0 }} {{ __('of') }} {{ $records->total() }}
            </p>
            <div class="flex gap-2">
                @if($records->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Previous') }}</button>
                @else
                    <a href="{{ $records->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Previous') }}</a>
                @endif
                @if($records->hasMorePages())
                    <a href="{{ $records->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Next') }}</a>
                @else
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Next') }}</button>
                @endif
            </div>
        </div>
    </div>
@endsection
