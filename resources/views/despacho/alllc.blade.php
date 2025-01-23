@extends('adminlte::page')

@section('title', 'GESPA')

@section('template_title')
    Todos los despachos
@endsection

@section('content')
    @livewire('alllc')
    @include('footer')
@stop
