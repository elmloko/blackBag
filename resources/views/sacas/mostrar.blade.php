@extends('adminlte::page')

@section('title', 'SIRECO')

@section('template_title')
    Mostrar Sacas
@endsection

@section('content')
    @livewire('mostrar')
    @include('footer')
@stop
