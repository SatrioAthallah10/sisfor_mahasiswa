@extends('layouts.app')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h2 class="font-display-lg text-display-lg text-on-surface mb-2">{{ __('Tugas') }}</h2>
            <p class="font-body-lg text-body-lg text-secondary">{{ __('Semua tugas dari matkul yang Anda ampu.') }}</p>
        </div>
        <a href="{{ route('lecturer.assignments.create') }}"
           class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary font-label-lg text-label-lg uppercase tracking-wider rounded hover:bg-surface-tint transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            {{ __('Buat Tugas') }}
        </a>
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
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Judul Tugas') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Matkul') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Deadline') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary text-center">{{ __('Submission') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($assignments as $a)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <p class="font-label-lg text-on-surface">{{ $a->title }}</p>
                                @if($a->due_at && $a->due_at->isPast())
                                    <span class="text-xs text-error">{{ __('Sudah lewat deadline') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-body-md text-secondary">
                                {{ $a->course?->name ?? '-' }}
                                <span class="block text-xs font-mono text-on-surface-variant">{{ $a->course?->code }}</span>
                            </td>
                            <td class="py-4 px-6 text-body-md text-on-surface-variant">
                                {{ $a->due_at?->format('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2 text-xs">
                                    <span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant">
                                        {{ $a->pending_count }} pending
                                    </span>
                                    <span class="px-2 py-1 rounded bg-primary-container text-on-primary-container">
                                        {{ $a->diterima_count }} diterima
                                    </span>
                                    <span class="px-2 py-1 rounded bg-error-container text-on-error-container">
                                        {{ $a->ditolak_count }} ditolak
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('lecturer.assignments.submissions', $a->id) }}"
                                   class="inline-flex items-center gap-1 px-4 py-2 border border-outline-variant rounded text-on-surface font-label-sm text-label-sm uppercase tracking-wider hover:border-primary hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-sm">folder_open</span>
                                    {{ __('Lihat Submission') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-on-surface-variant font-body-lg">
                                {{ __('Belum ada tugas.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-surface-container p-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                {{ __('Showing') }} {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }}
                {{ __('of') }} {{ $assignments->total() }} {{ __('entries') }}
            </p>
            <div class="flex gap-2">
                @if($assignments->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Previous') }}</button>
                @else
                    <a href="{{ $assignments->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Previous') }}</a>
                @endif

                @if($assignments->hasMorePages())
                    <a href="{{ $assignments->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Next') }}</a>
                @else
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Next') }}</button>
                @endif
            </div>
        </div>
    </div>
@endsection
