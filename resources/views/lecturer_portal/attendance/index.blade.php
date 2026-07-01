@extends('layouts.app')

@section('content')
    <div class="mb-10">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-2">{{ __('Absensi') }}</h2>
        <p class="font-body-lg text-body-lg text-secondary">{{ __('Rekap sesi absensi dari matkul yang Anda ampu.') }}</p>
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
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Sesi') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Matkul') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Waktu') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary text-center">{{ __('Rekap') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-label-lg text-on-surface">{{ $session->name }}</p>
                            </td>
                            <td class="py-4 px-6 text-body-md text-secondary">
                                {{ $session->course?->name ?? '-' }}
                                <span class="block text-xs font-mono text-on-surface-variant">{{ $session->course?->code }}</span>
                            </td>
                            <td class="py-4 px-6 text-body-md text-on-surface-variant">
                                <span class="block">{{ $session->open_at?->format('d M Y, H:i') ?? '-' }}</span>
                                @if($session->close_at)
                                    <span class="text-xs text-outline-variant">s/d {{ $session->close_at->format('H:i') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2 text-xs">
                                    <span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant">
                                        {{ $session->pending_count }} pending
                                    </span>
                                    <span class="px-2 py-1 rounded bg-primary-container text-on-primary-container">
                                        {{ $session->diterima_count }} diterima
                                    </span>
                                    <span class="px-2 py-1 rounded bg-error-container text-on-error-container">
                                        {{ $session->ditolak_count }} ditolak
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('lecturer.attendance.records', $session->id) }}"
                                   class="inline-flex items-center gap-1 px-4 py-2 border border-outline-variant rounded text-on-surface font-label-sm text-label-sm uppercase tracking-wider hover:border-primary hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-sm">how_to_reg</span>
                                    {{ __('Lihat Rekap') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-on-surface-variant font-body-lg">
                                {{ __('Belum ada sesi absensi.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-surface-container p-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                {{ $sessions->firstItem() ?? 0 }} - {{ $sessions->lastItem() ?? 0 }} {{ __('of') }} {{ $sessions->total() }}
            </p>
            <div class="flex gap-2">
                @if($sessions->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Previous') }}</button>
                @else
                    <a href="{{ $sessions->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Previous') }}</a>
                @endif
                @if($sessions->hasMorePages())
                    <a href="{{ $sessions->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Next') }}</a>
                @else
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Next') }}</button>
                @endif
            </div>
        </div>
    </div>
@endsection
