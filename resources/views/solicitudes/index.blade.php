@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('solicitudes._table')
        </div>
    </div>
</div>
@endsection
