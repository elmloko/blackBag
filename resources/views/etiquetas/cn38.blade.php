@extends('adminlte::page')

@section('title', 'GESPA')

@section('template_title')
    Etiquetas CN38
@endsection

@section('content')
    @livewire('cn38')
    @include('footer')
@stop
