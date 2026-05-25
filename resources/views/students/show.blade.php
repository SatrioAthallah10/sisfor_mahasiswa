{{--
  VARIABLES AVAILABLE:
  $student         — Student model instance being viewed

  ROUTES:
  route('students.index')   — GET, back to list page
  route('students.edit')    — GET, edit page
  route('students.destroy') — DELETE, soft delete student record

  FLASH MESSAGES:
  session('success')        — success string
  session('error')          — error string

  FRONTEND NOTE: use {{ }} for all user data, never {!! !!}
--}}

@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto flex flex-col gap-6">
    <!-- Breadcrumbs / Back Link -->
    <div>
        <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-label-lg text-label-lg uppercase tracking-wider">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            {{ __('Back to Directory') }}
        </a>
    </div>

    <!-- Details Card -->
    <div class="border border-outline-variant bg-surface shadow-2xl relative overflow-hidden flex flex-col rounded">
        <!-- Subtle top border accent line -->
        <div class="h-1 w-full bg-primary"></div>
        
        <!-- Header -->
        <div class="px-8 py-6 border-b border-outline-variant flex justify-between items-start bg-surface-bright shrink-0">
            <div>
                <h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Academic Profile') }}</h2>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mt-2">{{ __('Verified Institutional Record') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('students.edit', $student->id) }}" class="px-4 py-2 border border-outline-variant text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded flex items-center gap-2 hover:border-on-surface transition-colors">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    {{ __('Edit Record') }}
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="px-8 py-8 flex flex-col gap-8">
            <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">
                <!-- Profile Portrait -->
                <div class="w-32 h-32 md:w-40 md:h-40 rounded bg-secondary-container overflow-hidden border border-outline flex items-center justify-center shrink-0 shadow-sm">
                    @if ($student->photo)
                        <img alt="Portrait photo" class="w-full h-full object-cover grayscale opacity-90 mix-blend-multiply" src="{{ $student->photo_url }}"/>
                    @else
                        <span class="material-symbols-outlined text-6xl text-secondary">person</span>
                    @endif
                </div>

                <!-- Academic Info Grid -->
                <div class="flex-grow w-full flex flex-col gap-6">
                    <div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Full Legal Name') }}</span>
                        <h3 class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ $student->name }}</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Student ID (NIM)') }}</span>
                            <p class="font-body-lg text-body-lg text-on-surface font-mono tracking-tight mt-1">{{ $student->nim }}</p>
                        </div>
                        <div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Current GPA (IPK)') }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-body-lg text-body-lg text-primary font-bold">{{ number_format($student->gpa, 2) }}</span>
                                <span class="text-xs uppercase px-2 py-0.5 rounded-full font-label-sm tracking-wider font-semibold {{ $student->gpa >= 3.5 ? 'bg-primary-container text-on-primary-container' : 'bg-surface-variant text-on-surface-variant' }}">
                                    {{ $student->gpa >= 3.5 ? __('Honor Standing') : __('Satisfactory') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Study Program (Prodi)') }}</span>
                        <p class="font-body-lg text-body-lg text-on-surface mt-1">{{ $student->prodi }}</p>
                    </div>
                </div>
            </div>

            <!-- Double Line Divider -->
            <div class="w-full relative py-2">
                <div class="absolute inset-0 flex items-center justify-center flex-col gap-[2px]">
                    <div class="w-full h-[1px] bg-outline-variant"></div>
                    <div class="w-full h-[3px] bg-outline-variant"></div>
                </div>
            </div>

            <!-- Metadata Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-on-surface-variant font-label-sm uppercase tracking-wider bg-surface-container-low p-4 rounded border border-outline-variant">
                <div>
                    <span>{{ __('Record Created:') }}</span>
                    <span class="text-on-surface font-mono normal-case block mt-1">{{ $student->created_at->format('M d, Y - H:i A') }}</span>
                </div>
                <div>
                    <span>{{ __('Last Updated:') }}</span>
                    <span class="text-on-surface font-mono normal-case block mt-1">{{ $student->updated_at->format('M d, Y - H:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 border-t border-outline-variant bg-surface-bright flex justify-between items-center shrink-0">
            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to soft delete this student record?') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-error text-error font-label-sm text-label-sm uppercase tracking-wider rounded flex items-center gap-2 hover:bg-error-container transition-colors">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    {{ __('Delete Record') }}
                </button>
            </form>
            <a href="{{ route('students.index') }}" class="px-6 py-2 bg-on-surface text-surface border border-on-surface font-label-lg text-label-lg uppercase tracking-wider hover:bg-primary hover:border-primary hover:text-on-primary transition-all duration-300">
                {{ __('Close') }}
            </a>
        </div>
    </div>
</div>
@endsection
