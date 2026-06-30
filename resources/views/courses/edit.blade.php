@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto border border-outline-variant bg-surface shadow-2xl relative overflow-hidden flex flex-col rounded">
    <div class="h-1 w-full bg-primary"></div>
    
    <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-start bg-surface-bright shrink-0">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Edit Course') }}</h2>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mt-2">{{ __('Modify course details and update assigned lecturer') }}</p>
        </div>
        <a href="{{ route('courses.index') }}" class="text-on-surface-variant hover:text-primary transition-colors mt-1 group">
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
        <form action="{{ route('courses.update', $course->id) }}" method="POST" class="flex flex-col gap-8">
            @csrf
            @method('PUT')

            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="code">{{ __('Course Code') }}</label>
                <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors font-mono uppercase" id="code" name="code" value="{{ old('code', $course->code) }}" placeholder="e.g. IF-301" type="text" required/>
            </div>

            <div class="flex flex-col">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="name">{{ __('Course Title') }}</label>
                <input class="bg-transparent border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/40 transition-colors" id="name" name="name" value="{{ old('name', $course->name) }}" placeholder="e.g. Pemrograman Web" type="text" required/>
            </div>

            <div class="flex flex-col relative">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2" for="lecturer_id">{{ __('Assigned Lecturer') }}</label>
                <select class="appearance-none bg-transparent bg-none border-0 border-b border-outline-variant focus:border-on-surface focus:ring-0 px-0 py-2 font-body-lg text-body-lg text-on-surface transition-colors cursor-pointer pr-8" id="lecturer_id" name="lecturer_id">
                    <option value="">{{ __('Unassigned') }}</option>
                    @foreach ($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}" {{ old('lecturer_id', $course->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
                            {{ $lecturer->name }} ({{ $lecturer->email }})
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-0 bottom-2 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
            </div>

            <div class="pt-6 border-t border-outline-variant bg-surface-bright flex justify-end items-center gap-4 shrink-0">
                <a href="{{ route('courses.index') }}" class="px-6 py-2 border border-on-surface text-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-surface-variant transition-colors duration-300">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-8 py-2 bg-on-surface text-surface border border-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:border-primary hover:text-on-primary transition-all duration-300 shadow-[0_4px_14px_0_rgba(37,36,34,0.1)] hover:shadow-[0_6px_20px_rgba(167,52,0,0.2)]">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
