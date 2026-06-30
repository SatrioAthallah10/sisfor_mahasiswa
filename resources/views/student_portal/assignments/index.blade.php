@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="max-w-4xl mx-auto">
        <h2 class="text-headline-md font-headline-md text-primary mb-4">{{ __('Assignments') }}</h2>

        <div class="grid gap-4">
            @foreach($assignments as $a)
                <a href="{{ route('student.assignments.show', $a->id) }}" class="p-4 bg-surface-container rounded-lg border border-outline-variant hover:shadow"> 
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-label-lg">{{ $a->title }}</h4>
                            <p class="text-body-md text-on-surface-variant">{{ Str::limit($a->description, 160) }}</p>
                        </div>
                        <div class="text-xs text-outline-variant">{{ $a->due_at?->format('Y-m-d H:i') }}</div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $assignments->links() }}
        </div>
    </div>
@endsection
