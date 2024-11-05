<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Despachos AGBC</h1>
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
                                        <th>Nro. Envases</th>
                                        <th>Peso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($despachos as $despacho)
                                        <tr>
                                            <td>{{ $despacho->ofdestino }}</td>
                                            <td>{{ $despacho->identificador }}</td>
                                            <td>{{ $despacho->categoria }}</td>
                                            <td>{{ $despacho->subclase }}</td>
                                            <td>{{ $despacho->nroenvase }}</td>
                                            <td>{{ $despacho->peso }}</td>
                                            <td>
                                                <button type="button" class="btn btn-info"
                                                    wire:click="mostrarQueja({{ $despacho->id }})" target="_blank">
                                                    Ver Queja
                                                </button>
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
                                <option value="UA">UA CARTAS - AO</option>
                                <option value="UB">UB CARTAS - MASIVO</option>
                                <option value="UC">UC CARTAS - CORREO DIRECTO ARMONIZADO</option>
                                <option value="UD">UD CARTAS - FUERA DEL SISTEMA DE GASTOS TERMINALES</option>
                                <option value="UE">UE CARTAS - DECOUVERT</option>
                                <option value="UF">UF CARTAS - LC ENTRADA DIRECTA</option>
                                <option value="UG">UG CARTAS - AO ENTRADA DIRECTA</option>
                                <option value="UH">UH CARTAS - LC/AO ENTRADA DIRECTA</option>
                                <option value="UI">UI CARTAS - CCRI</option>
                                <option value="UL">UL CARTAS - LC</option>
                                <option value="UM">UM CARTAS - SACAS M</option>
                                <option value="UN">UN CARTAS - LC/AO</option>
                                <option value="UP">UP CARTAS - TARJETAS POSTALES</option>
                                <option value="UR">UR CARTAS - CERTIFICADO</option>
                                <option value="US">US CARTAS - SACAS VACIAS</option>
                                <option value="UT">UT CARTAS - RESERVADO PARA USO DE ACUERDOS BILATERALES</option>
                                <option value="UV">UV LETTERS - ARTÍCULOS DEVUELTOS QUE NO SE PUEDEN ENTREGAR
                                    SUJETOS A REMUNERACIÓN</option>
                                <option value="UX">UX CARTAS - EXPRESO</option>
                                <option value="UY">UY CARTAS - RESERVADO PARA USO MULTILATERAL EN PROYECTOS
                                    DESIGNADOS</option>
                                <option value="UZ">UZ CARTAS - RESERVADO PARA USO DE ACUERDOS BILATERALES</option>
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
