@extends('layouts.app')

@section('content')
    <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h2 class="font-display-lg text-display-lg text-on-surface mb-2">{{ __('Lecturer Directory') }}</h2>
            <p class="font-body-lg text-body-lg text-secondary">{{ __('Manage lecturer credentials, accounts, and profile info.') }}</p>
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <a href="{{ route('lecturers.create') }}" class="flex-grow md:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-primary text-on-primary font-label-lg text-label-lg uppercase tracking-wider rounded transition-all hover:bg-surface-tint">
                <span class="material-symbols-outlined text-sm">add</span>
                {{ __('Add New Lecturer') }}
            </a>
        </div>
    </div>

    <div class="w-full h-1 border-t-2 border-on-surface mb-1 relative"></div>
    <div class="w-full border-t border-outline-variant mb-8"></div>

    <form action="{{ route('lecturers.index') }}" method="GET" class="flex flex-col lg:flex-row gap-6 mb-8 bg-surface-container-low p-6 rounded border border-outline-variant">
        <div class="flex-grow relative">
            <label class="absolute -top-2 left-3 bg-surface-container-low px-1 font-label-sm text-label-sm uppercase tracking-wider text-secondary z-10" for="search">{{ __('Search') }}</label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-secondary pointer-events-none">search</span>
                <input class="w-full bg-transparent border border-outline-variant rounded py-3 pl-12 pr-4 font-body-md text-on-surface placeholder:text-surface-dim focus:border-on-surface focus:ring-0 transition-colors" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by Name or Email...') }}" type="text"/>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-on-surface text-surface font-label-lg text-label-lg uppercase tracking-wider rounded transition-colors hover:bg-primary">
                {{ __('Apply') }}
            </button>
            
            @if (request()->has('search'))
                <a href="{{ route('lecturers.index') }}" class="px-6 py-3 border border-outline-variant text-on-surface font-label-lg text-label-lg uppercase tracking-wider rounded flex items-center justify-center transition-colors hover:bg-surface-container">
                    {{ __('Clear') }}
                </a>
            @endif
        </div>
    </form>

    <div class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-16">{{ __('Initial') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Full Name') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Email Address') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-32">{{ __('Courses Taught') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-40 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($lecturers as $lecturer)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <span class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-sm uppercase tracking-wide border border-outline-variant">
                                    {{ substr($lecturer->name, 0, 2) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-headline-md text-headline-md text-on-surface text-lg font-bold">
                                {{ $lecturer->name }}
                            </td>
                            <td class="py-4 px-6 font-body-md text-secondary font-mono text-sm tracking-tight">{{ $lecturer->email }}</td>
                            <td class="py-4 px-6 font-body-md text-secondary">{{ $lecturer->courses()->count() }} {{ __('Courses') }}</td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-1 opacity-90 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('lecturers.edit', $lecturer->id) }}" class="p-2 text-secondary hover:text-primary transition-colors rounded hover:bg-surface-container" title="{{ __('Edit Record') }}">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('lecturers.destroy', $lecturer->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this lecturer?') }}');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-secondary hover:text-error transition-colors rounded hover:bg-error-container" title="{{ __('Delete Record') }}">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-on-surface-variant font-body-lg">
                                {{ __('No lecturer records found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-surface-container p-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                {{ __('Showing') }} {{ $lecturers->firstItem() ?? 0 }} - {{ $lecturers->lastItem() ?? 0 }} {{ __('of') }} {{ $lecturers->total() }} {{ __('entries') }}
            </p>
            <div class="flex gap-2">
                @if ($lecturers->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed transition-colors" disabled>
                        {{ __('Previous') }}
                    </button>
                @else
                    <a href="{{ $lecturers->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">
                        {{ __('Previous') }}
                    </a>
                @endif

                @if ($lecturers->hasMorePages())
                    <a href="{{ $lecturers->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">
                        {{ __('Next') }}
                    </a>
                @else
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed transition-colors" disabled>
                        {{ __('Next') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection
