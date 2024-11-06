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
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#createSacaModal">
                                            Crear Nueva Saca
                                        </button>
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
                                            <th>Identificador</th>
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
                                                <td>{{ str_pad($saca->nrosaca, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $saca->identificador }}</td>
                                                @php
                                                    $tipos = [
                                                        'BG' => 'BG (Saca)',
                                                        'CG' => 'CG (Rodillo Cesta)',
                                                        'CN' => 'CN (Contenedor)',
                                                        'FW' => 'FW (Carro con bandejas)',
                                                        'GU' => 'GU (Bandeja plana)',
                                                        'IB' => 'IB (Caja en palé IPC)',
                                                        'IL' => 'IL (Bandeja IPC)',
                                                        'IS' => 'IS (Saca de IPC)',
                                                        'PB' => 'PB (Caja en palé)',
                                                        'PU' => 'PU (Bandeja de cartas)',
                                                        'PX' => 'PX (Palé)',
                                                    ];
                                                @endphp
                                                <td>{{ $tipos[$saca->tipo] ?? $saca->tipo }}</td>
                                                <td>{{ $saca->peso }}</td>
                                                <td>{{ $saca->nropaquetes }}</td>
                                                <td>{{ $saca->created_at }}</td>
                                                <td>
                                                    <!-- Botón de Editar que abre el modal -->
                                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#editSacaModal{{ $saca->id }}">
                                                        Editar
                                                    </button>
                                                    <!-- Modal para editar la saca -->
                                                    <div class="modal fade" id="editSacaModal{{ $saca->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="editSacaModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editSacaModalLabel">Editar
                                                                        Saca</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Formulario para editar la saca -->
                                                                    <form action="{{ route('saca.update', $saca->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group">
                                                                            <label for="tipo">Tipo</label>
                                                                            <select class="form-control" id="tipo"
                                                                                name="tipo" required>
                                                                                <option value="BG"
                                                                                    {{ $saca->tipo == 'BG' ? 'selected' : '' }}>
                                                                                    BG (Saca)</option>
                                                                                <option value="CG"
                                                                                    {{ $saca->tipo == 'CG' ? 'selected' : '' }}>
                                                                                    CG (Rodillo) Cesta
                                                                                </option>
                                                                                <option value="CN"
                                                                                    {{ $saca->tipo == 'CN' ? 'selected' : '' }}>
                                                                                    CN Contenedor
                                                                                </option>
                                                                                <!-- Añade las demás opciones aquí -->
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="peso">Peso</label>
                                                                            <input type="text" step="0,001"
                                                                                class="form-control" id="peso"
                                                                                name="peso" value="{{ $saca->peso }}"
                                                                                required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="nropaquetes">Número de
                                                                                Paquetes</label>
                                                                            <input type="number" class="form-control"
                                                                                id="nropaquetes" name="nropaquetes"
                                                                                value="{{ $saca->nropaquetes }}" required>
                                                                        </div>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Guardar Cambios</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Botón de Eliminar -->
                                                    <form action="{{ route('saca.delete', $saca->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta saca?')">Eliminar</button>
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

        <!-- Modal para crear una nueva saca -->
        <div class="modal fade" id="createSacaModal" tabindex="-1" role="dialog" aria-labelledby="createSacaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSacaModalLabel">Crear Nueva Saca</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para crear la nueva saca -->
                        <form action="{{ route('saca.store') }}" method="POST">
                            @csrf
                            <!-- Campo despacho_id oculto con el valor pasado del despacho -->
                            <input type="hidden" name="despacho_id" value="{{ $id }}">

                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select class="form-control" id="tipo" name="tipo" required>
                                    <option value="BG">BG (Saca)</option>
                                    <option value="CG">CG (Rodillo) Cesta</option>
                                    <option value="CN">CN Contenedor</option>
                                    <option value="FW">FW Carro con bandejas (carro, plataforma)</option>
                                    <option value="GU">GU Bandeja plana</option>
                                    <option value="IB">IB Caja en palé IPC</option>
                                    <option value="IL">IL Bandeja IPC</option>
                                    <option value="IS">IS Saca de IPC</option>
                                    <option value="PB">PB Caja en palé</option>
                                    <option value="PU">PU Bandeja de cartas</option>
                                    <option value="PX">PX Palé</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="peso">Peso</label>
                                <input type="text" step="0,001" class="form-control" id="peso" name="peso"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="nropaquetes">Número de Paquetes</label>
                                <input type="number" class="form-control" id="nropaquetes" name="nropaquetes" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear Saca</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('footer')
@endsection
