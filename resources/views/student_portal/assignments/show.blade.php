@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h2 class="text-headline-md font-headline-md text-primary mb-4">{{ $assignment->title }}</h2>

        <div class="mb-6">
            <h3 class="text-label-lg font-label-lg mb-2">{{ __('Question') }}</h3>
            <div class="p-4 bg-surface-container rounded border border-outline-variant">
                <p class="text-body-md">{!! nl2br(e($assignment->question ?? $assignment->description)) !!}</p>
                @if($assignment->question_file)
                    <div class="mt-4 p-3 bg-surface-container-low rounded border border-outline-variant">
                        <a href="{{ route('student.assignments.downloadQuestion', $assignment->id) }}" class="text-primary">{{ __('Download question file') }}</a>
                        <div class="text-xs text-outline-variant mt-2">{{ __('Uploaded') }}: {{ $assignment->updated_at?->format('Y-m-d H:i') }}</div>
                    </div>
                @endif
                @if($assignment->description)
                    <p class="text-body-sm text-on-surface-variant mt-4">{{ __('Details') }}: {{ $assignment->description }}</p>
                @endif
                <div class="text-xs text-outline-variant mt-4">{{ __('Due') }}: {{ $assignment->due_at?->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-label-lg font-label-lg mb-2">{{ __('Your Submission') }}</h3>
            @if($submission)
                <div class="p-4 bg-surface-container rounded border border-outline-variant">
                    <a href="{{ asset('storage/' . $submission->file_path) }}" class="text-primary">{{ __('Download file') }}</a>
                    <div class="text-xs text-outline-variant">{{ $submission->submitted_at?->format('Y-m-d H:i') }}</div>
                    <p class="mt-2 text-body-md text-on-surface-variant">{{ $submission->notes }}</p>
                </div>
            @else
                <div class="p-4 bg-surface-container rounded border border-outline-variant">{{ __('No submission yet.') }}</div>
            @endif
        </div>

        <div>
            <h3 class="text-label-lg font-label-lg mb-2">{{ __('Submit / Resubmit') }}</h3>
            <div class="p-4 bg-surface-container rounded border border-outline-variant">
                <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="mt-0">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-label-sm mb-2">{{ __('File') }}</label>
                        <input type="file" name="file" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-label-sm mb-2">{{ __('Notes') }}</label>
                        <textarea name="notes" rows="4" class="w-full border border-outline-variant rounded p-2">{{ old('notes') }}</textarea>
                    </div>
                    <button class="px-6 py-3 bg-primary text-on-primary rounded">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
