@extends('adminlte::page')

@section('title', 'GESPA')

@section('template_title')
    Despachos Abiertos
@endsection

@section('content')
    @livewire('admitir')
    @include('footer')
@stop
