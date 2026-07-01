@extends('layouts.app')

@section('content')
    <div class="mb-10">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-2">{{ __('Mata Kuliah Saya') }}</h2>
        <p class="font-body-lg text-body-lg text-secondary">{{ __('Daftar matkul yang Anda ampu.') }}</p>
    </div>

    <div class="w-full h-1 border-t-2 border-on-surface mb-1"></div>
    <div class="w-full border-t border-outline-variant mb-8"></div>

    @if($courses->isEmpty())
        <div class="py-16 text-center text-on-surface-variant font-body-lg">
            <span class="material-symbols-outlined text-5xl mb-4 block">school</span>
            {{ __('Belum ada matkul yang ditugaskan ke Anda.') }}
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="p-6 bg-surface-container rounded border border-outline-variant flex flex-col gap-3">
                    <div>
                        <p class="font-mono text-sm text-secondary tracking-widest uppercase mb-1">{{ $course->code }}</p>
                        <h3 class="font-headline-md text-headline-md text-on-surface">{{ $course->name }}</h3>
                    </div>
                    <div class="flex gap-4 text-body-sm text-on-surface-variant mt-auto pt-3 border-t border-outline-variant">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">assignment</span>
                            {{ $course->assignments_count ?? 0 }} {{ __('Tugas') }}
                        </span>
                    </div>
                    <div class="flex gap-2 mt-1">
                        <a href="{{ route('lecturer.assignments.index', ['course_id' => $course->id]) }}"
                           class="flex-1 text-center px-4 py-2 bg-primary text-on-primary rounded font-label-sm text-label-sm uppercase tracking-wider hover:bg-surface-tint transition-colors">
                            {{ __('Tugas') }}
                        </a>
                        <a href="{{ route('lecturer.attendance.index', ['course_id' => $course->id]) }}"
                           class="flex-1 text-center px-4 py-2 border border-outline-variant text-on-surface rounded font-label-sm text-label-sm uppercase tracking-wider hover:bg-surface-container-high transition-colors">
                            {{ __('Absensi') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
