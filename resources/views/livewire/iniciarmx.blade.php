<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Despachos MIXTOS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Registros</li>
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
                                <div class="float-left d-flex align-items-center">
                                    <input type="text" wire:model="searchTerm" placeholder="Buscar..."
                                        class="form-control" style="margin-right: 10px;">
                                    <button type="button" class="btn btn-primary" wire:click="$refresh">Buscar</button>
                                </div>
                                <div class="ml-auto">
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#createDespachoModal">Nuevo Despacho</button>
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
                                        <th>Oficina Destino</th>
                                        <th>Identificador</th>
                                        <th>Categoría</th>
                                        <th>Subclase</th>
                                        <th>Nro. Paquetes</th>
                                        <th>Peso(Kg.)</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($despachos as $despacho)
                                        <tr>
                                            @php
                                                $oficinas = [
                                                    'BOLPZ' => 'BOLPZ - LA PAZ',
                                                    'BOTJA' => 'BOTJA - TARIJA',
                                                    'BOPOI' => 'BOPOI - POTOSI',
                                                    'BOCIJ' => 'BOCIJ - PANDO',
                                                    'BOCBB' => 'BOCBB - COCHABAMBA',
                                                    'BOORU' => 'BOORU - ORURO',
                                                    'BOTDD' => 'BOTDD - BENI',
                                                    'BOSRE' => 'BOSRE - SUCRE',
                                                    'BOSRZ' => 'BOSRZ - SANTA CRUZ',
                                                ];
                                                $subclases = [
                                                    'MA' => 'MA MIXTO - AL DESCUBIERTO',
                                                    'MD' => 'MD MIXTO - DOCUMENTOS',
                                                    'MG' => 'MG MIXTO - PLAZO GARANTIZADO: DOCUMENTOS',
                                                    'MH' => 'MH MIXTO - PLAZO GARANTIZADO: MERCANCIA',
                                                    'MI' => 'MI MIXTO - PLAZO GARANTIZADO: MIXTO',
                                                    'MM' => 'MM MIXTO - MERCADERIA',
                                                    'MN' => 'MN MIXTO - MIXTO',
                                                    'MR' => 'MR MIXTO - MERCANCIA DEVUELTA',
                                                    'MT' => 'MT MIXTO - SACAS VACIAS',
                                                    'MU' => 'MU MIXTO - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                                    'MY' =>
                                                        'MY MIXTO - RESERVADO PARA USO MULTILATERAL EN PROYECTOS DESIGNADOS',
                                                    'MZ' => 'MZ MIXTO - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                                ];
                                                $categorias = [
                                                    'A' => 'A - Aéreo',
                                                    'B' => 'B - S.A.L.',
                                                    'C' => 'C - Superficie',
                                                    'D' => 'D - Prioritario por superficie',
                                                ];
                                            @endphp
                                            <td>{{ $oficinas[$despacho->ofdestino] ?? $despacho->ofdestino }}</td>
                                            <td>{{ $despacho->identificador }}</td>
                                            <td>{{ $categorias[$despacho->categoria] ?? $despacho->categoria }}</td>
                                            <td>{{ $subclases[$despacho->subclase] ?? $despacho->subclase }}</td>
                                            <td>{{ $despacho->nroenvase }}</td>
                                            <td>{{ $despacho->peso }}</td>
                                            <td>{{ $despacho->estado }}</td>
                                            <td>
                                                @if ($despacho->estado === 'CERRADO')
                                                    <button wire:click="reaperturarDespacho({{ $despacho->id }})"
                                                        class="btn btn-warning">Reaperturar Saca</button>
                                                    <button wire:click="expedicionDespacho({{ $despacho->id }})"
                                                        class="btn btn-info">Expedición</button>
                                                @elseif ($despacho->estado === 'APERTURA' || $despacho->estado === 'REAPERTURA')
                                                    <a href="{{ route('saca.crear', $despacho->id) }}"
                                                        class="btn btn-primary">Ver Detalles de Saca</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $despachos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para Crear Despacho -->
    <div wire:ignore.self class="modal fade" id="createDespachoModal" tabindex="-1" role="dialog"
        aria-labelledby="createDespachoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDespachoModalLabel">Nuevo Despacho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="crearDespacho">
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select class="form-control" id="categoria" wire:model="categoria">
                                <option value="">Seleccione una opción</option>
                                <option value="A">A - Aéreo</option>
                                <option value="B">B - S.A.L.</option>
                                <option value="C">C - Superficie</option>
                                <option value="D">D - Prioritario por superficie</option>
                            </select>
                            @error('categoria')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ofdestino">Oficina Destino</label>
                            <select class="form-control" id="ofdestino" wire:model="ofdestino">
                                <option value="">Seleccione una opción</option>
                                <option value="BOLPZ">BOLPZ - LA PAZ</option>
                                <option value="BOTJA">BOTJA - TARIJA</option>
                                <option value="BOPOI">BOPOI - POTOSI</option>
                                <option value="BOCBB">BOCBB - COCHABAMBA</option>
                                <option value="BOCIJ">BOCIJ - PANDO</option>
                                <option value="BOORU">BOORU - ORURO</option>
                                <option value="BOTDD">BOTDD - BENI</option>
                                <option value="BOSRE">BOSRE - SUCRE</option>
                                <option value="BOSRZ">BOSRZ - SANTA CRUZ</option>
                            </select>
                            @error('ofdestino')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subclase">Subclase</label>
                            <select class="form-control" id="subclase" wire:model="subclase">
                                <option value="">Seleccione una opción</option>
                                <option value="MN">MN MIXTO - MIXTO</option>
                                <option value="MA">MA MIXTO - AL DESCUBIERTO</option>
                                <option value="MD">MD MIXTO - DOCUMENTOS</option>
                                <option value="MG">MG MIXTO - PLAZO GARANTIZADO: DOCUMENTOS</option>
                                <option value="MH">MH MIXTO - PLAZO GARANTIZADO: MERCANCIA</option>
                                <option value="MI">MI MIXTO - PLAZO GARANTIZADO: MIXTO</option>
                                <option value="MM">MM MIXTO - MERCADERIA</option>
                                <option value="MR">MR MIXTO - MERCANCIA DEVUELTA</option>
                                <option value="MT">MT MIXTO - SACAS VACIAS</option>
                                <option value="MU">MU MIXTO - RESERVADO PARA USO DE ACUERDOS BILATERALES</option>
                                <option value="MY">MY MIXTO - RESERVADO PARA USO MULTILATERAL EN PROYECTOS DESIGNADOS
                                </option>
                                <option value="MZ">MZ MIXTO - RESERVADO PARA USO DE ACUERDOS BILATERALES</option>
                            </select>
                            @error('subclase')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="button" class="btn btn-primary"
                            wire:click.prevent="crearDespacho">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Confirmar Guardado de Despacho -->
    <div wire:ignore.self class="modal fade" id="confirmDespachoModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDespachoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDespachoModalLabel">Confirmar Despacho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Número de Despacho:</strong> {{ $nrodespacho }}</p>
                    <p><strong>Fecha y Hora Actual:</strong> {{ $fechaHoraActual }}</p>
                    <p>¿Deseas confirmar y guardar este despacho?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="confirmarGuardarDespacho">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('closeCreateDespachoModal', () => {
            $('#createDespachoModal').modal('hide');
        });

        window.addEventListener('openConfirmModal', () => {
            $('#confirmDespachoModal').modal('show');
        });

        window.addEventListener('closeConfirmModal', () => {
            $('#confirmDespachoModal').modal('hide');
        });
    });
</script>
