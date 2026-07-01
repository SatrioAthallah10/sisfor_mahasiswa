@extends('layouts.app')

@section('content')
    <div class="mb-10">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-2">{{ __('Selamat datang') }}, {{ auth()->user()->name }}</h2>
        <p class="font-body-lg text-body-lg text-secondary">{{ __('Portal Dosen — ringkasan aktivitas matkul Anda.') }}</p>
    </div>

    <div class="w-full h-1 border-t-2 border-on-surface mb-1"></div>
    <div class="w-full border-t border-outline-variant mb-8"></div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <div class="p-6 bg-surface-container rounded border border-outline-variant flex items-center gap-4">
            <span class="material-symbols-outlined text-primary text-4xl">menu_book</span>
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">{{ __('Mata Kuliah') }}</p>
                <p class="font-display-sm text-display-sm text-on-surface">{{ $totalCourses }}</p>
            </div>
        </div>

        <a href="{{ route('lecturer.assignments.index') }}" class="p-6 bg-surface-container rounded border border-outline-variant flex items-center gap-4 hover:shadow transition-shadow">
            <span class="material-symbols-outlined text-4xl {{ $pendingSubmissions > 0 ? 'text-error' : 'text-secondary' }}">assignment</span>
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">{{ __('Submission Pending') }}</p>
                <p class="font-display-sm text-display-sm text-on-surface">{{ $pendingSubmissions }}</p>
            </div>
        </a>

        <a href="{{ route('lecturer.attendance.index') }}" class="p-6 bg-surface-container rounded border border-outline-variant flex items-center gap-4 hover:shadow transition-shadow">
            <span class="material-symbols-outlined text-4xl {{ $pendingAttendance > 0 ? 'text-error' : 'text-secondary' }}">how_to_reg</span>
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">{{ __('Absensi Pending') }}</p>
                <p class="font-display-sm text-display-sm text-on-surface">{{ $pendingAttendance }}</p>
            </div>
        </a>
    </div>

    {{-- Quick links --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('lecturer.assignments.create') }}" class="flex items-center gap-3 p-5 bg-primary text-on-primary rounded hover:bg-surface-tint transition-colors">
            <span class="material-symbols-outlined">add_circle</span>
            <span class="font-label-lg text-label-lg uppercase tracking-wider">{{ __('Buat Tugas Baru') }}</span>
        </a>
        <a href="{{ route('lecturer.courses.index') }}" class="flex items-center gap-3 p-5 bg-surface-container rounded border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">list_alt</span>
            <span class="font-label-lg text-label-lg uppercase tracking-wider">{{ __('Lihat Matkul Saya') }}</span>
        </a>
    </div>
@endsection
