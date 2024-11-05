<!-- sacas/crear.blade.php -->
@extends('adminlte::page')
@section('title', 'Usuarios')
@section('template_title')
    Paqueteria Postal
@endsection

@section('content')
<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sacas Existentes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Sacas</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="ml-auto">
                                    <a href="{{ route('saca.crear', $id) }}" class="btn btn-success">
                                        Crear Nueva Saca
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Número de Saca</th>
                                        <th>Tipo</th>
                                        <th>Peso</th>
                                        <th>Número de Paquetes</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sacas as $saca)
                                        <tr>
                                            <td>{{ $saca->nrosaca }}</td>
                                            <td>{{ $saca->tipo }}</td>
                                            <td>{{ $saca->peso }}</td>
                                            <td>{{ $saca->nropaquetes }}</td>
                                            <td>{{ $saca->created_at }}</td>
                                            <td>
                                                <form action="{{ route('saca.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="despacho_id" value="{{ $id }}">
                                                    <button type="submit" class="btn btn-primary">Crear Saca</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <!-- Aquí puedes incluir una paginación si es necesario -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('footer')
@endsection