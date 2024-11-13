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
                        <h1>Sacas de Despacho: {{ $identificadorDespacho }}</h1>
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
                                <div class="d-flex justify-content-between">
                                    <a href="/iniciar" class="btn btn-secondary">Atrás</a>
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#createSacaModal">
                                        Crear Nueva Saca
                                    </button>
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
                                            <th>Etiqueta</th>
                                            <th>Peso</th>
                                            <th>Número de Paquetes</th>
                                            <th>Fecha de Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sacas as $saca)
                                            @php
                                                // Verificar si ya existe contenido asociado a esta saca
                                                $contenido = $saca->contenido()->first(); // Ajusta esta consulta según tu relación entre 'saca' y 'contenido'
                                            @endphp
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
                                                    $etiqueta = [
                                                        'RO' => 'RO - Roja',
                                                        'BL' => 'BL - Blanca',
                                                    ];
                                                @endphp
                                                <td>{{ $tipos[$saca->tipo] ?? $saca->tipo }}</td>
                                                <td>{{ $etiqueta[$saca->etiqueta] ?? $saca->etiqueta }}</td>
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
                                                                            <label for="etiqueta">Etiqueta</label>
                                                                            <select class="form-control" id="etiqueta" name="etiqueta" required>
                                                                                <option value="RO" {{ $saca->etiqueta == 'RO' ? 'selected' : '' }}>RO - Roja</option>
                                                                                <option value="BL" {{ $saca->etiqueta == 'BL' ? 'selected' : '' }}>BL Blanca</option>
                                                                            </select>
                                                                        </div>                                                                        
                                                                        {{-- <div class="form-group">
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
                                                                        </div> --}}
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
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#createContenidoModal{{ $saca->id }}">
                                                        Declarar Contenido
                                                    </button>
                                                    <div class="modal fade" id="createContenidoModal{{ $saca->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="createContenidoModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="createContenidoModalLabel">
                                                                        {{ $contenido ? 'Editar' : 'Declarar' }} Contenido
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form
                                                                        action="{{ $contenido ? route('contenido.update', $contenido->id) : route('contenido.store') }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @if ($contenido)
                                                                            @method('PUT')
                                                                        @endif

                                                                        <input type="hidden" name="saca_id"
                                                                            value="{{ $saca->id }}">

                                                                        <!-- Campo de descripción -->
                                                                        <div class="form-group">
                                                                            <label for="descripcion">Descripción</label>
                                                                            <select class="form-control" id="descripcion"
                                                                                name="descripcion" required>
                                                                                <option value="MINL"
                                                                                    {{ optional($contenido)->descripcion == 'MINL' ? 'selected' : '' }}>
                                                                                    MINL - Normal</option>
                                                                                <option value="MIFW"
                                                                                    {{ optional($contenido)->descripcion == 'MIFW' ? 'selected' : '' }}>
                                                                                    MIFW - Reexpedición al extranjero en
                                                                                    curso</option>
                                                                                <option value="MIRT"
                                                                                    {{ optional($contenido)->descripcion == 'MIRT' ? 'selected' : '' }}>
                                                                                    MIRT - Devolución en curso</option>
                                                                                <option value="MIRD"
                                                                                    {{ optional($contenido)->descripcion == 'MIRD' ? 'selected' : '' }}>
                                                                                    MIRD - Devolución/reexpedición en
                                                                                    tránsito aquí en curso</option>
                                                                                <option value="MIAT"
                                                                                    {{ optional($contenido)->descripcion == 'MIAT' ? 'selected' : '' }}>
                                                                                    MIAT - En tránsito aquí</option>
                                                                                <option value="MIMS"
                                                                                    {{ optional($contenido)->descripcion == 'MIMS' ? 'selected' : '' }}>
                                                                                    MIMS - Fuera de curso</option>
                                                                                <option value="MIAD"
                                                                                    {{ optional($contenido)->descripcion == 'MIAD' ? 'selected' : '' }}>
                                                                                    MIAD - Enviado al descubierto</option>
                                                                                <option value="MISP"
                                                                                    {{ optional($contenido)->descripcion == 'MISP' ? 'selected' : '' }}>
                                                                                    MISP - Enviado al descubierto: especial
                                                                                    (sin tasas ni gastos pagaderos)
                                                                                </option>
                                                                            </select>
                                                                        </div>

                                                                        <!-- Tabla para Peso, Número de Paquetes y Tipo -->
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Tipo</th>
                                                                                    <th>Peso</th>
                                                                                    <th>Número de Paquetes</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span
                                                                                            class="form-control-plaintext">M</span>
                                                                                        <input type="hidden"
                                                                                            name="tipom" value="M">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="pesom" name="pesom"
                                                                                            step="0.001"
                                                                                            value="{{ optional($contenido)->pesom }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="nropaquetesm"
                                                                                            name="nropaquetesm"
                                                                                            value="{{ optional($contenido)->nropaquetesm }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span
                                                                                            class="form-control-plaintext">LC/AO</span>
                                                                                        <input type="hidden"
                                                                                            name="tipol" value="L">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="pesol" name="pesol"
                                                                                            step="0.001"
                                                                                            value="{{ optional($contenido)->pesol }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="nropaquetesl"
                                                                                            name="nropaquetesl"
                                                                                            value="{{ optional($contenido)->nropaquetesl }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span
                                                                                            class="form-control-plaintext">U</span>
                                                                                        <input type="hidden"
                                                                                            name="tipou" value="U">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="pesou" name="pesou"
                                                                                            step="0.001"
                                                                                            value="{{ optional($contenido)->pesou }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            id="nropaquetesu"
                                                                                            name="nropaquetesu"
                                                                                            value="{{ optional($contenido)->nropaquetesu }}">
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{ $contenido ? 'Actualizar' : 'Guardar' }}
                                                                            Contenido</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-right">
                                <form action="{{ route('despacho.cerrar', $id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de que deseas cerrar este despacho y todas sus sacas?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Cerrar Despacho</button>
                                </form>
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
        <div class="modal fade" id="createSacaModal" tabindex="-1" role="dialog"
            aria-labelledby="createSacaModalLabel" aria-hidden="true">
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
                                <label for="etiqueta">Etiqueta</label>
                                <select class="form-control" id="etiqueta" name="etiqueta" required>
                                    <option value="RO  ">RO - Roja</option>
                                    <option value="BL">BL Blanca</option>
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
