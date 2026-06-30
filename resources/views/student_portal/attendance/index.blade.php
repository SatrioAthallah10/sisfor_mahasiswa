@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h2 class="text-headline-md font-headline-md text-primary mb-4">{{ __('Attendance') }}</h2>

        <div class="grid gap-4">
            @foreach($sessions as $s)
                @php
                    $record = isset($records[$s->id]) ? $records[$s->id] : null;
                    $now = now();
                    $isOpen = (is_null($s->open_at) || $s->open_at <= $now) && (is_null($s->close_at) || $s->close_at >= $now);
                @endphp
                <div class="p-4 bg-surface-container rounded border border-outline-variant flex justify-between items-center">
                    <div>
                        <h4 class="font-label-lg">{{ $s->name }}</h4>
                        <div class="text-xs text-outline-variant">{{ $s->open_at?->format('Y-m-d H:i') }} - {{ $s->close_at?->format('Y-m-d H:i') }}</div>
                        @if($record)
                            <div class="text-xs text-primary mt-2">{{ __('You marked present at') }}: {{ $record->present_at?->format('Y-m-d H:i') }}</div>
                        @endif
                    </div>
                    @if($record)
                        <button disabled class="px-4 py-2 bg-surface-container text-on-surface-variant rounded border border-outline-variant">{{ __('Already Marked') }}</button>
                    @else
                        @if($isOpen)
                            <form action="{{ route('student.attendance.mark', $s->id) }}" method="POST">
                                @csrf
                                <button class="px-4 py-2 bg-primary text-on-primary rounded">{{ __('Mark Present') }}</button>
                            </form>
                        @else
                            @if($s->open_at && $s->open_at->gt($now))
                                <div class="text-xs text-on-surface-variant">{{ __('Attendance not open yet.') }}</div>
                            @elseif($s->close_at && $s->close_at->lt($now))
                                <div class="text-xs text-on-surface-variant">{{ __('Attendance closed.') }}</div>
                            @else
                                <div class="text-xs text-on-surface-variant">{{ __('Attendance not open yet.') }}</div>
                            @endif
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
    </div>
@endsection
