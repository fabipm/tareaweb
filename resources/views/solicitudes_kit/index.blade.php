@php
    // Si el usuario es estudiante, redirigir al dashboard principal
    if(Auth::user() && Auth::user()->rol === 'Estudiante') {
        header('Location: ' . route('dashboard.estudiante'));
        exit;
    }
@endphp

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @include('solicitudes_kit._table')
        </div>
    </div>
</div>
@endsection
