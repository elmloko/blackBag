<div class="container-fluid">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Registros CN35</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">CN35</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="row w-100">
                    <div class="col-md-6 d-flex align-items-center">
                        <button class="btn btn-success" wire:click="abrirModal">
                            <i class="fas fa-plus-circle"></i> Crear Registro
                        </button>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <div class="input-group" style="max-width: 400px;">
                            <input type="text" class="form-control" placeholder="Buscar origen o destino..."
                                wire:model.defer="searchInput">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-flat" wire:click="buscar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if (session()->has('message'))
                    <div class="alert alert-success m-3">
                        {{ session('message') }}
                    </div>
                @endif

                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Receptáculo</th>
                            <th>Identificador</th>
                            <th>Saca</th>
                            <th>Servicio</th>
                            <th>Tipo</th>
                            <th>Paquetes</th>
                            <th>Peso</th>
                            <th>Aduana</th>
                            <th>Manifiesto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registros as $registro)
                            <tr>
                                <td>{{ $registro->receptaculo }}</td>
                                <td>{{ $registro->identificador }}</td>
                                <td>{{ $registro->saca }}</td>
                                <td>{{ $registro->servicio }}</td>
                                <td>{{ $registro->tipo }}</td>
                                <td>{{ $registro->paquetes }}</td>
                                <td>{{ $registro->peso }}</td>
                                <td>{{ $registro->aduana }}</td>
                                <td>{{ $registro->codigo_manifiesto }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" wire:click="editar({{ $registro->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="eliminar({{ $registro->id }})"
                                        onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center">No se encontraron resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {{ $registros->links() }}
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade @if ($modal) show d-block @endif" tabindex="-1"
        style="background: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $cn35_id ? 'Editar Registro' : 'Nuevo Registro' }}</h5>
                    <button type="button" class="close" wire:click="cerrarModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    @foreach ([
        'despacho' => 'number',
        'origen' => 'text',
        'destino' => 'text',
        'saca' => 'number',
        'categoria' => 'text',
        'subclase' => 'text',
        'servicio' => 'text',
        'tipo' => 'text',
        'paquetes' => 'number',
        'peso' => 'number',
        'aduana' => 'text',
        'codigo_manifiesto' => 'text',
        'receptaculo' => 'text',
        'identificador' => 'text',
    ] as $campo => $tipo)
                        <div class="form-group col-md-6">
                            <label for="{{ $campo }}">{{ ucfirst(str_replace('_', ' ', $campo)) }}</label>
                            <input type="{{ $tipo }}" wire:model.defer="{{ $campo }}"
                                class="form-control" style="text-transform: uppercase;">
                            @error($campo)
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button wire:click="guardar" class="btn btn-primary">
                        {{ $cn35_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>
