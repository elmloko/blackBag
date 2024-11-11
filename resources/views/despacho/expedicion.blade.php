@extends('adminlte::page')

@section('title', 'SIRECO')

@section('template_title')
    Despachos Abiertos
@endsection

@section('content')
    @livewire('expedicion')
    @include('footer')
@stop
