@extends('layouts.app')

@section('content')
    <div class="mb-12">
        <h2 class="text-display-lg-mobile md:text-display-lg font-display-lg-mobile md:font-display-lg text-on-background mb-2">{{ __('Executive Summary') }}</h2>
        <p class="text-body-lg font-body-lg text-on-surface-variant max-w-2xl">{{ __('Overview of institutional metrics and student academic performance for the current academic term.') }}</p>
        <div class="h-px w-full bg-outline-variant mt-8 relative">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-24 h-0.5 bg-primary"></div>
        </div>
    </div>

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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-16">
        <div class="border border-outline-variant p-8 bg-surface-container-lowest relative group hover:border-primary transition-colors duration-300 cursor-default">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl" style="font-variation-settings: 'FILL' 1;">groups</span>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface-variant mb-4 relative z-10">{{ __('Total Enrollment') }}</h3>
            <div class="text-display-lg font-display-lg text-primary relative z-10">{{ number_format($totalStudents) }}</div>
            <div class="mt-4 flex items-center gap-2 text-label-sm font-label-sm text-on-surface-variant relative z-10">
                <span class="material-symbols-outlined text-sm text-primary">arrow_upward</span>
                <span>{{ __('Active Student Records') }}</span>
            </div>
        </div>

        <div class="border border-outline-variant p-8 bg-surface-container-lowest relative group hover:border-primary transition-colors duration-300 cursor-default">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-6xl" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface-variant mb-4 relative z-10">{{ __('Average GPA / IPK') }}</h3>
            <div class="text-display-lg font-display-lg text-on-background relative z-10">{{ number_format($averageGpa, 2) }}</div>
            <div class="mt-4 flex items-center gap-2 text-label-sm font-label-sm text-on-surface-variant relative z-10">
                <span class="material-symbols-outlined text-sm text-on-surface-variant">grade</span>
                <span>{{ __('Scale bounds: 0.00 to 4.00') }}</span>
            </div>
        </div>

        <div class="border border-outline-variant p-8 bg-primary-container text-on-primary-container relative group hover:opacity-95 transition-opacity duration-300 cursor-default">
            <h3 class="text-headline-md font-headline-md mb-4 text-on-primary-container">{{ __('Quick Actions') }}</h3>
            <div class="text-display-lg font-display-lg text-white">{{ __('SIS Portal') }}</div>
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-2 text-label-sm font-label-sm opacity-90">
                    <span class="material-symbols-outlined text-sm">manage_accounts</span>
                    <span>{{ __('Manage all entries') }}</span>
                </div>
                <a href="{{ route('students.index') }}" class="text-label-sm font-label-sm uppercase tracking-widest border-b border-on-primary-container pb-0.5 hover:opacity-75 transition-opacity">{{ __('Go to Directory') }}</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
        <div class="lg:col-span-1 border border-outline-variant bg-surface-container-lowest p-8 flex flex-col">
            <h3 class="text-headline-md font-headline-md text-on-background mb-8 pb-4 border-b border-outline-variant">{{ __('Program Distribution') }}</h3>
            
            <div class="flex-1 flex flex-col justify-center min-h-[250px]">
                <canvas id="prodiChart"></canvas>
            </div>
            
            <div class="mt-6 pt-6 border-t border-outline-variant flex justify-between text-label-sm font-label-sm text-on-surface-variant">
                <span>{{ __('Database Sync') }}</span>
                <a class="text-primary hover:underline" href="{{ route('students.index') }}">{{ __('Detailed Directory') }}</a>
            </div>
        </div>

        <div class="lg:col-span-2 border border-outline-variant bg-surface-container-lowest flex flex-col">
            <div class="p-8 pb-4 border-b border-outline-variant flex justify-between items-end">
                <h3 class="text-headline-md font-headline-md text-on-background">{{ __('Study Program Enrollment') }}</h3>
                <a href="{{ route('students.export') }}" class="text-label-sm font-label-sm text-primary uppercase tracking-widest hover:text-surface-tint transition-colors">{{ __('Export CSV') }}</a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-label-sm font-label-sm text-on-surface-variant border-b border-outline-variant uppercase tracking-widest bg-surface-container-low">
                            <th class="py-4 px-8 font-normal">{{ __('No') }}</th>
                            <th class="py-4 px-8 font-normal">{{ __('Study Program') }}</th>
                            <th class="py-4 px-8 font-normal text-right">{{ __('Student Count') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md text-on-background">
                        @forelse ($studentsByProdi as $index => $row)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-8 text-on-surface-variant">{{ $index + 1 }}</td>
                                <td class="py-4 px-8 font-semibold">{{ $row->prodi }}</td>
                                <td class="py-4 px-8 text-right font-mono">{{ number_format($row->total) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 px-8 text-center text-on-surface-variant">{{ __('No study programs registered.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script id="prodi-data" type="application/json">@json($studentsByProdi)</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataTag = document.getElementById('prodi-data');
            if (dataTag) {
                const rawData = JSON.parse(dataTag.textContent);
                const labels = rawData.map(item => item.prodi);
                const values = rawData.map(item => item.total);

                const ctx = document.getElementById('prodiChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: '#a73400',
                                borderColors: '#a73400',
                                borderWidth: 0,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#e6e2de'
                                    },
                                    ticks: {
                                        color: '#594139',
                                        precision: 0
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#594139'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection
