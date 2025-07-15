@extends('adminlte::page')

@section('title', 'GESPA')

@section('template_title')
    Todos los despachos
@endsection

@section('content')
    @livewire('allmx')
    @include('footer')
@stop
