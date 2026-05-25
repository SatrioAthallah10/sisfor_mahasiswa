{{--
  VARIABLES AVAILABLE:
  None

  ROUTES:
  route('login.post')       — POST, submit login credentials
  route('login')            — GET, show login page

  FLASH MESSAGES:
  session('success')        — success string
  session('error')          — error string

  FRONTEND NOTE: use {{ }} for all user data, never {!! !!}
--}}

@extends('layouts.app')

@section('content')
<div class="w-full max-w-[500px] border border-outline-variant bg-surface p-10 md:p-16 relative">
    
    <!-- Academic Crest / Identity -->
    <div class="flex flex-col items-center text-center mb-12">
        <span class="material-symbols-outlined text-[48px] text-primary mb-4" style="font-variation-settings: 'FILL' 0;">school</span>
        <div class="w-12 h-px bg-outline-variant my-4 mx-auto"></div>
        <h2 class="text-headline-md font-headline-md text-on-surface-variant">{{ __('Academic Portal') }}</h2>
    </div>

    {{-- Error Notifications --}}
    @if ($errors->any())
        <div class="mb-8 p-4 bg-error-container text-on-error-container border border-error rounded-sm text-body-md font-body-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success and Error Flash Notifications --}}
    @if (session('success'))
        <div class="mb-8 p-4 bg-primary-container text-on-primary-container border border-primary rounded-sm text-body-md font-body-md">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-8 p-4 bg-error-container text-on-error-container border border-error rounded-sm text-body-md font-body-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login.post') }}" class="flex flex-col gap-8" method="POST">
        @csrf

        <div class="flex flex-col gap-2">
            <label class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-widest" for="email">{{ __('Email Address') }}</label>
            <input class="border-0 border-b border-outline-variant bg-transparent px-0 py-2 text-body-lg font-body-lg text-on-surface focus:ring-0 focus:border-on-surface transition-colors placeholder:text-surface-dim" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" type="email" required/>
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-widest" for="password">{{ __('Password') }}</label>
            <input class="border-0 border-b border-outline-variant bg-transparent px-0 py-2 text-body-lg font-body-lg text-on-surface focus:ring-0 focus:border-on-surface transition-colors placeholder:text-surface-dim" id="password" name="password" placeholder="••••••••" type="password" required/>
        </div>

        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center">
                <input class="h-4 w-4 rounded-none border-outline-variant text-primary focus:ring-primary bg-transparent cursor-pointer" id="remember-me" name="remember-me" type="checkbox"/>
                <label class="ml-2 block text-body-md font-body-md text-on-surface-variant cursor-pointer" for="remember-me">
                    {{ __('Remember me') }}
                </label>
            </div>
            <div class="text-body-md font-body-md">
                <a class="text-on-surface-variant hover:text-primary transition-colors underline decoration-outline-variant underline-offset-4" href="#">{{ __('Forgot password?') }}</a>
            </div>
        </div>

        <div class="mt-8">
            <button class="w-full flex justify-center py-4 px-4 border border-transparent text-label-lg font-label-lg uppercase tracking-widest text-on-primary bg-primary hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors" type="submit">
                {{ __('Login') }}
            </button>
        </div>
    </form>

    <div class="mt-12 text-center">
        <p class="text-label-sm font-label-sm text-on-surface-variant">
            {{ __('By logging in, you agree to the') }} <a class="underline decoration-outline-variant underline-offset-4 hover:text-primary transition-colors" href="#">{{ __('Institutional Access Policy') }}</a>.
        </p>
    </div>
</div>
@endsection
