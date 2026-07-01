@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-body-sm text-on-surface-variant mb-6">
        <a href="{{ route('lecturer.assignments.index') }}" class="hover:text-primary transition-colors">{{ __('Tugas') }}</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-on-surface">{{ $assignment->title }}</span>
    </div>

    <div class="mb-8">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-1">{{ $assignment->title }}</h2>
        <p class="font-body-lg text-body-lg text-secondary">
            {{ $assignment->course?->name }} ({{ $assignment->course?->code }})
            @if($assignment->due_at)
                &nbsp;·&nbsp; Deadline: {{ $assignment->due_at->format('d M Y, H:i') }}
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
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Mahasiswa') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Dikumpulkan') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Catatan') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Status') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($submissions as $sub)
                        @php
                            $badgeClass = match($sub->status) {
                                'diterima' => 'bg-primary-container text-on-primary-container',
                                'ditolak'  => 'bg-error-container text-on-error-container',
                                default    => 'bg-surface-container-high text-on-surface-variant',
                            };
                        @endphp
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-label-lg text-on-surface">{{ $sub->user?->name }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $sub->user?->email }}</p>
                            </td>
                            <td class="py-4 px-6 text-body-md text-on-surface-variant">
                                {{ $sub->submitted_at?->format('d M Y, H:i') ?? '-' }}
                                @if($sub->file_path)
                                    <a href="{{ asset('storage/' . $sub->file_path) }}"
                                       class="block mt-1 text-xs text-primary hover:underline"
                                       target="_blank">
                                        <span class="material-symbols-outlined text-xs align-middle">download</span> {{ __('Unduh File') }}
                                    </a>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-body-sm text-on-surface-variant max-w-xs">
                                {{ Str::limit($sub->notes, 80) ?: '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded text-label-sm font-label-sm {{ $badgeClass }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                                @if($sub->status === 'ditolak' && $sub->feedback)
                                    <p class="text-xs text-on-surface-variant mt-1 italic">{{ Str::limit($sub->feedback, 60) }}</p>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    {{-- Approve --}}
                                    @if($sub->status !== 'diterima')
                                        <form action="{{ route('lecturer.submissions.approve', $sub->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-2 bg-primary-container text-on-primary-container rounded font-label-sm text-label-sm uppercase tracking-wider hover:opacity-80 transition-opacity"
                                                    onclick="return confirm('Terima submission ini?')">
                                                <span class="material-symbols-outlined text-sm align-middle">check_circle</span>
                                                Terima
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Reject with feedback --}}
                                    @if($sub->status !== 'ditolak')
                                        <button type="button"
                                                onclick="document.getElementById('reject-modal-{{ $sub->id }}').classList.remove('hidden')"
                                                class="px-3 py-2 bg-error-container text-on-error-container rounded font-label-sm text-label-sm uppercase tracking-wider hover:opacity-80 transition-opacity">
                                            <span class="material-symbols-outlined text-sm align-middle">cancel</span>
                                            Tolak
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Reject Modal (inline, per row) --}}
                        <tr id="reject-modal-{{ $sub->id }}" class="hidden bg-surface-container-low">
                            <td colspan="5" class="px-6 py-4">
                                <form action="{{ route('lecturer.submissions.reject', $sub->id) }}" method="POST" class="flex gap-4 items-end">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2 block">
                                            {{ __('Feedback untuk') }} {{ $sub->user?->name }} <span class="normal-case text-outline-variant">(opsional)</span>
                                        </label>
                                        <textarea name="feedback" rows="2"
                                                  class="w-full border border-outline-variant rounded px-3 py-2 font-body-md text-on-surface bg-surface"
                                                  placeholder="{{ __('Tulis alasan penolakan...') }}"></textarea>
                                    </div>
                                    <div class="flex gap-2 shrink-0">
                                        <button type="submit"
                                                class="px-4 py-2 bg-error text-on-error rounded font-label-sm text-label-sm uppercase tracking-wider">
                                            {{ __('Konfirmasi Tolak') }}
                                        </button>
                                        <button type="button"
                                                onclick="document.getElementById('reject-modal-{{ $sub->id }}').classList.add('hidden')"
                                                class="px-4 py-2 border border-outline-variant rounded text-on-surface font-label-sm text-label-sm uppercase tracking-wider">
                                            {{ __('Batal') }}
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-on-surface-variant font-body-lg">
                                {{ __('Belum ada submission untuk tugas ini.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-surface-container p-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                {{ $submissions->firstItem() ?? 0 }} - {{ $submissions->lastItem() ?? 0 }} {{ __('of') }} {{ $submissions->total() }}
            </p>
            <div class="flex gap-2">
                @if($submissions->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Previous') }}</button>
                @else
                    <a href="{{ $submissions->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Previous') }}</a>
                @endif
                @if($submissions->hasMorePages())
                    <a href="{{ $submissions->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">{{ __('Next') }}</a>
                @else
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed" disabled>{{ __('Next') }}</button>
                @endif
            </div>
        </div>
    </div>
@endsection
