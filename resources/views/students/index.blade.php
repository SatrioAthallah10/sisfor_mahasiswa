{{--
  VARIABLES AVAILABLE:
  $students        — LengthAwarePaginator containing Student models

  ROUTES:
  route('students.index')   — GET, list page with filters
  route('students.create')  — GET, show create form
  route('students.show')    — GET, show student details
  route('students.edit')    — GET, show edit form
  route('students.destroy') — DELETE, soft delete student record
  route('students.export')  — GET, export student list to CSV

  FLASH MESSAGES:
  session('success')        — success string
  session('error')          — error string

  FRONTEND NOTE: use {{ }} for all user data, never {!! !!}
--}}

@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h2 class="font-display-lg text-display-lg text-on-surface mb-2">{{ __('Student Directory') }}</h2>
            <p class="font-body-lg text-body-lg text-secondary">{{ __('Manage enrollment records, academic standing, and profiles.') }}</p>
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <a href="{{ route('students.export') }}" class="flex-grow md:flex-none flex items-center justify-center gap-2 px-6 py-3 border border-on-surface text-on-surface font-label-lg text-label-lg uppercase tracking-wider rounded transition-all hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-sm">download</span>
                {{ __('Download CSV') }}
            </a>
            <a href="{{ route('students.create') }}" class="flex-grow md:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-primary text-on-primary font-label-lg text-label-lg uppercase tracking-wider rounded transition-all hover:bg-surface-tint">
                <span class="material-symbols-outlined text-sm">add</span>
                {{ __('Add New Student') }}
            </a>
        </div>
    </div>

    <!-- Double line divider -->
    <div class="w-full h-1 border-t-2 border-on-surface mb-1 relative"></div>
    <div class="w-full border-t border-outline-variant mb-8"></div>

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

    <!-- Toolbar / Filters -->
    <form action="{{ route('students.index') }}" method="GET" class="flex flex-col lg:flex-row gap-6 mb-8 bg-surface-container-low p-6 rounded border border-outline-variant">
        <!-- Search input -->
        <div class="flex-grow relative">
            <label class="absolute -top-2 left-3 bg-surface-container-low px-1 font-label-sm text-label-sm uppercase tracking-wider text-secondary z-10" for="search">{{ __('Search') }}</label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-secondary pointer-events-none">search</span>
                <input class="w-full bg-transparent border border-outline-variant rounded py-3 pl-12 pr-4 font-body-md text-on-surface placeholder:text-surface-dim focus:border-on-surface focus:ring-0 transition-colors" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by Name or NIM...') }}" type="text"/>
            </div>
        </div>

        <!-- Filters select controls -->
        <div class="flex flex-wrap sm:flex-nowrap gap-4">
            <div class="relative min-w-[200px] flex-grow">
                <label class="absolute -top-2 left-3 bg-surface-container-low px-1 font-label-sm text-label-sm uppercase tracking-wider text-secondary z-10" for="prodi">{{ __('Program Studi') }}</label>
                <select class="w-full bg-transparent bg-none border border-outline-variant rounded py-3 px-4 font-body-md text-on-surface focus:border-on-surface focus:ring-0 appearance-none cursor-pointer pr-10" id="prodi" name="prodi">
                    <option value="">{{ __('All Programs') }}</option>
                    <option value="Teknik Informatika" {{ request('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                    <option value="Sistem Informasi" {{ request('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                    <option value="Desain Komunikasi Visual" {{ request('prodi') == 'Desain Komunikasi Visual' ? 'selected' : '' }}>Desain Komunikasi Visual</option>
                </select>
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">arrow_drop_down</span>
            </div>

            <div class="relative min-w-[160px] flex-grow">
                <label class="absolute -top-2 left-3 bg-surface-container-low px-1 font-label-sm text-label-sm uppercase tracking-wider text-secondary z-10" for="ipk">{{ __('IPK Range') }}</label>
                <select class="w-full bg-transparent bg-none border border-outline-variant rounded py-3 px-4 font-body-md text-on-surface focus:border-on-surface focus:ring-0 appearance-none cursor-pointer pr-10" id="ipk" name="gpa_range">
                    <option value="">{{ __('Any IPK') }}</option>
                    <option value="high" {{ request('gpa_range') == 'high' ? 'selected' : '' }}>3.50 - 4.00</option>
                    <option value="mid" {{ request('gpa_range') == 'mid' ? 'selected' : '' }}>3.00 - 3.49</option>
                    <option value="low" {{ request('gpa_range') == 'low' ? 'selected' : '' }}>< 3.00</option>
                </select>
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">arrow_drop_down</span>
            </div>

            <button type="submit" class="px-6 py-3 bg-on-surface text-surface font-label-lg text-label-lg uppercase tracking-wider rounded transition-colors hover:bg-primary">
                {{ __('Apply') }}
            </button>
            
            @if (request()->hasAny(['search', 'prodi', 'gpa_range']))
                <a href="{{ route('students.index') }}" class="px-6 py-3 border border-outline-variant text-on-surface font-label-lg text-label-lg uppercase tracking-wider rounded flex items-center justify-center transition-colors hover:bg-surface-container">
                    {{ __('Clear') }}
                </a>
            @endif
        </div>
    </form>

    <!-- Data Table -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-16">{{ __('Photo') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-40">{{ __('NIM') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Full Name') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary">{{ __('Program Studi') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-24">{{ __('IPK') }}</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm uppercase tracking-wider text-secondary w-40 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($students as $student)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <div class="w-10 h-10 rounded-full bg-secondary-container overflow-hidden border border-outline flex items-center justify-center">
                                    @if ($student->photo)
                                        <img alt="Portrait photo" class="w-full h-full object-cover grayscale opacity-90 mix-blend-multiply" src="{{ $student->photo_url }}"/>
                                    @else
                                        <span class="material-symbols-outlined text-secondary">person</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 font-body-md text-secondary font-mono text-sm tracking-tight">{{ $student->nim }}</td>
                            <td class="py-4 px-6 font-headline-md text-headline-md text-on-surface text-lg">
                                <a href="{{ route('students.show', $student->id) }}" class="hover:text-primary transition-colors">{{ $student->name }}</a>
                            </td>
                            <td class="py-4 px-6 font-body-md text-secondary">{{ $student->prodi }}</td>
                            <td class="py-4 px-6 font-body-md text-primary font-semibold">{{ number_format($student->gpa, 2) }}</td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-1 opacity-90 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('students.show', $student->id) }}" class="p-2 text-secondary hover:text-primary transition-colors rounded hover:bg-surface-container" title="{{ __('View Record') }}">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('students.edit', $student->id) }}" class="p-2 text-secondary hover:text-primary transition-colors rounded hover:bg-surface-container" title="{{ __('Edit Record') }}">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to soft delete this student record?') }}');" class="inline">
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
                            <td colspan="6" class="py-12 px-6 text-center text-on-surface-variant font-body-lg">
                                {{ __('No student records found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="bg-surface-container p-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">
                {{ __('Showing') }} {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} {{ __('of') }} {{ $students->total() }} {{ __('entries') }}
            </p>
            <div class="flex gap-2">
                @if ($students->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded opacity-50 cursor-not-allowed transition-colors" disabled>
                        {{ __('Previous') }}
                    </button>
                @else
                    <a href="{{ $students->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">
                        {{ __('Previous') }}
                    </a>
                @endif

                @if ($students->hasMorePages())
                    <a href="{{ $students->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant bg-surface text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded hover:border-on-surface transition-colors">
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
