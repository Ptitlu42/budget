@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/history/styles.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('page-scripts')
@endpush
