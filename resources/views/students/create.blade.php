@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto border border-outline-variant bg-surface shadow-2xl relative overflow-hidden flex flex-col rounded">
    <div class="h-1 w-full bg-primary"></div>
    
    <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-start bg-surface-bright shrink-0">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Add Student Record') }}</h2>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mt-2">{{ __('Enter academic and personal details') }}</p>
        </div>
        <a href="{{ route('students.index') }}" class="text-on-surface-variant hover:text-primary transition-colors mt-1 group">
            <span class="material-symbols-outlined group-hover:rotate-90 transition-transform duration-300">close</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mx-8 mt-8 p-4 bg-error-container text-on-error-container border border-error rounded-sm text-body-md font-body-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="px-8 py-8 flex-1">
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="nim">{{ __('Student ID (NIM)') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors" id="nim" name="nim" value="{{ old('nim') }}" placeholder="e.g. 06.2024.1.07770" type="text" required/>
                </div>
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="name">{{ __('Full Legal Name') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors" id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" type="text" required/>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col relative">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="prodi">{{ __('Study Program (Prodi)') }}</label>
                    <select class="appearance-none bg-transparent bg-none border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface transition-colors cursor-pointer pr-8" id="prodi" name="prodi" required>
                        <option disabled selected value="">{{ __('Select a program') }}</option>
                        <option value="Teknik Informatika" {{ old('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                        <option value="Sistem Informasi" {{ old('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="Desain Komunikasi Visual" {{ old('prodi') == 'Desain Komunikasi Visual' ? 'selected' : '' }}>Desain Komunikasi Visual</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-0 bottom-2 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
                </div>
                <div class="flex flex-col">
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="gpa">{{ __('Current GPA (IPK)') }}</label>
                    <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors font-variant-numeric: tabular-nums;" id="gpa" max="4.00" min="0.00" name="gpa" value="{{ old('gpa') }}" placeholder="e.g. 3.85" step="0.01" type="number" required/>
                </div>
            </div>

            <div class="w-full relative py-4">
                <div class="absolute inset-0 flex items-center justify-center flex-col gap-[2px]">
                    <div class="w-full h-[1px] bg-outline-variant"></div>
                    <div class="w-full h-[3px] bg-outline-variant"></div>
                </div>
            </div>

            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-4">{{ __('Profile Photo Upload') }}</label>
                <div class="border border-dashed border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low transition-colors duration-300 p-8 flex flex-col items-center justify-center text-center cursor-pointer group relative">
                    <input type="file" name="photo" id="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"/>
                    <div class="w-12 h-12 rounded-full bg-surface-variant flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-on-surface-variant">cloud_upload</span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface mb-1">{{ __('Click or drag student portrait here') }}</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('to upload image file') }}</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant mt-4 opacity-70">{{ __('Max size 2MB. Accepted formats: JPG, PNG.') }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-outline-variant bg-surface-bright flex justify-end items-center gap-4 shrink-0">
                <a href="{{ route('students.index') }}" class="px-6 py-2 border border-on-surface text-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-surface-variant transition-colors duration-300">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-8 py-2 bg-on-surface text-surface border border-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:border-primary hover:text-on-primary transition-all duration-300 shadow-[0_4px_14px_0_rgba(37,36,34,0.1)] hover:shadow-[0_6px_20px_rgba(167,52,0,0.2)]">
                    {{ __('Save Data') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
