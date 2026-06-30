@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="max-w-4xl mx-auto">
        <h2 class="text-headline-md font-headline-md text-primary mb-4">{{ __('Student Portal') }}</h2>

        <section class="mb-8">
            <h3 class="text-label-lg font-label-lg">{{ __('Recent Assignments') }}</h3>
            <div class="mt-4 grid gap-4">
                @forelse($assignments as $a)
                    <a href="{{ route('student.assignments.show', $a->id) }}" class="p-4 bg-surface-container rounded-lg border border-outline-variant hover:shadow"> 
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-label-lg">{{ $a->title }}</h4>
                                <p class="text-body-md text-on-surface-variant">{{ Str::limit($a->description, 120) }}</p>
                            </div>
                            <div class="text-xs text-outline-variant">{{ $a->due_at?->format('Y-m-d H:i') }}</div>
                        </div>
                    </a>
                @empty
                    <div class="p-4 bg-surface-container rounded">{{ __('No assignments yet.') }}</div>
                @endforelse
            </div>
        </section>

        <section>
            <h3 class="text-label-lg font-label-lg">{{ __('Open Attendance Sessions') }}</h3>
            <div class="mt-4 grid gap-4">
                @forelse($openSessions as $s)
                    @php $record = $attendanceRecords[$s->id] ?? null; @endphp
                    <div class="p-4 bg-surface-container rounded-lg border border-outline-variant">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <div>
                                <h4 class="font-label-lg">{{ $s->name }}</h4>
                                <p class="text-body-md text-on-surface-variant">{{ $s->open_at?->format('Y-m-d H:i') }} - {{ $s->close_at?->format('Y-m-d H:i') }}</p>
                                @if($record)
                                    <p class="text-xs text-primary mt-2">{{ __('You marked present at') }}: {{ $record->present_at?->format('Y-m-d H:i') }}</p>
                                @endif
                            </div>
                            @if($record)
                                <span class="px-4 py-2 bg-surface-container text-on-surface-variant rounded border border-outline-variant">{{ __('Already Marked') }}</span>
                            @else
                                <form action="{{ route('student.attendance.mark', $s->id) }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-2 bg-primary text-on-primary rounded">{{ __('Mark Present') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-surface-container rounded">{{ __('No open attendance sessions.') }}</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
