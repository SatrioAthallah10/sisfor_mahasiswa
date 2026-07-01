@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto border border-outline-variant bg-surface shadow-2xl relative overflow-hidden flex flex-col rounded">
    <div class="h-1 w-full bg-primary"></div>

    <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-start bg-surface-bright shrink-0">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Buat Tugas Baru') }}</h2>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mt-2">{{ __('Isi form berikut untuk membuat tugas bagi mahasiswa') }}</p>
        </div>
        <a href="{{ route('lecturer.assignments.index') }}" class="text-on-surface-variant hover:text-primary transition-colors mt-1 group">
            <span class="material-symbols-outlined group-hover:rotate-90 transition-transform duration-300">close</span>
        </a>
    </div>

    @if($errors->any())
        <div class="mx-8 mt-8 p-4 bg-error-container text-on-error-container border border-error rounded text-body-md font-body-md">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="px-8 py-8 flex-1">
        <form action="{{ route('lecturer.assignments.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
            @csrf

            {{-- Matkul --}}
            <div class="flex flex-col relative">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="course_id">{{ __('Mata Kuliah') }}</label>
                <select class="appearance-none bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface transition-colors cursor-pointer pr-8"
                        id="course_id" name="course_id" required>
                    <option value="">{{ __('-- Pilih Matkul --') }}</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-0 bottom-2 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
            </div>

            {{-- Judul --}}
            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="title">{{ __('Judul Tugas') }}</label>
                <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors"
                       id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Tugas 1 — Laporan Praktikum" type="text" required />
            </div>

            {{-- Soal / Pertanyaan --}}
            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="question">{{ __('Soal / Pertanyaan') }}</label>
                <textarea class="bg-transparent border border-outline-variant focus:border-on-surface focus:ring-0 px-3 py-2 font-body-md text-on-surface rounded resize-y transition-colors"
                          id="question" name="question" rows="5" placeholder="{{ __('Tulis soal tugas di sini...') }}">{{ old('question') }}</textarea>
            </div>

            {{-- Deskripsi --}}
            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="description">{{ __('Keterangan Tambahan') }}</label>
                <textarea class="bg-transparent border border-outline-variant focus:border-on-surface focus:ring-0 px-3 py-2 font-body-md text-on-surface rounded resize-y transition-colors"
                          id="description" name="description" rows="3" placeholder="{{ __('Instruksi, format pengumpulan, dll. (opsional)') }}">{{ old('description') }}</textarea>
            </div>

            {{-- File soal --}}
            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="question_file">{{ __('File Soal') }} <span class="normal-case text-outline-variant">(opsional)</span></label>
                <input class="text-body-md text-on-surface file:mr-4 file:px-4 file:py-2 file:border-0 file:bg-surface-container file:text-on-surface file:font-label-sm file:uppercase file:tracking-wider file:cursor-pointer"
                       id="question_file" name="question_file" type="file" />
            </div>

            {{-- Waktu --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="available_from">{{ __('Tersedia Mulai') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-md text-on-surface transition-colors"
                           id="available_from" name="available_from" value="{{ old('available_from') }}" type="datetime-local" />
                </div>
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="due_at">{{ __('Deadline') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-md text-on-surface transition-colors"
                           id="due_at" name="due_at" value="{{ old('due_at') }}" type="datetime-local" />
                </div>
            </div>

            <div class="pt-6 border-t border-outline-variant flex justify-end items-center gap-4">
                <a href="{{ route('lecturer.assignments.index') }}" class="px-6 py-2 border border-on-surface text-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-surface-variant transition-colors">
                    {{ __('Batal') }}
                </a>
                <button type="submit" class="px-8 py-2 bg-on-surface text-surface border border-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:border-primary hover:text-on-primary transition-all shadow-[0_4px_14px_0_rgba(37,36,34,0.1)]">
                    {{ __('Buat Tugas') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
