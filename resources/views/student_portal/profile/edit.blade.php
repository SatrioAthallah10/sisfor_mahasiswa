@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h2 class="text-headline-md font-headline-md text-primary mb-4">{{ __('Edit Profile') }}</h2>

        <form action="{{ route('student.profile.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-label-sm">{{ __('Name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-outline-variant rounded p-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-label-sm">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-outline-variant rounded p-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-label-sm">{{ __('Password (leave blank to keep)') }}</label>
                <input type="password" name="password" class="w-full border border-outline-variant rounded p-2" />
            </div>
            <div class="mb-4">
                <label class="block text-label-sm">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" class="w-full border border-outline-variant rounded p-2" />
            </div>

            <button class="px-6 py-3 bg-primary text-on-primary rounded">{{ __('Save') }}</button>
        </form>
    </div>
@endsection
