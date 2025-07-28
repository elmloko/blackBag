@extends('adminlte::page')

@section('title', 'GESPA')

@section('template_title')
    Etiquetas CN35
@endsection

@section('content')
    @livewire('cn35')
    @include('footer')
@stop
