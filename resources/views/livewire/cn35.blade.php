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
                            <th>Servicio</th>
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
                                <td>{{ $registro->servicio }}</td>
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

                    <div class="form-group col-md-6">
                        <label for="despacho">Despacho</label>
                        <input type="number" wire:model.defer="despacho" class="form-control"
                            style="text-transform: uppercase;">
                        @error('despacho')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="saca">Saca</label>
                        <input type="number" wire:model.defer="saca" class="form-control"
                            style="text-transform: uppercase;">
                        @error('saca')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="origen">Origen</label>
                        <select class="form-control" id="origen" wire:model="origen">
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
                        @error('origen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="destino">Destino</label>
                        <select class="form-control" id="destino" wire:model="destino">
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
                        @error('destino')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="servicio">Servicio</label>
                        <select wire:model.defer="servicio" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="MIXTO">MIXTO</option>
                            <option value="EMS">EMS</option>
                            <option value="CP">CP</option>
                            <option value="LC/AO">LC/AO</option>
                        </select>
                        @error('servicio')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
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
                            <option value="UV">UV CARTAS - ARTÍCULOS DEVUELTOS QUE NO SE PUEDEN ENTREGAR
                                SUJETOS A REMUNERACIÓN</option>
                            <option value="UX">UX CARTAS - EXPRESO</option>
                            <option value="UY">UY CARTAS - RESERVADO PARA USO MULTILATERAL EN PROYECTOS
                                DESIGNADOS</option>
                            <option value="UZ">UZ CARTAS - RESERVADO PARA USO DE ACUERDOS BILATERALES</option>
                        </select>
                        @error('subclase')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="categoria">Categoría</label>
                        <select class="form-control" id="categoria" wire:model="categoria">
                            <option value="">Seleccione una opción</option>
                            <option value="A">A - Aéreo</option>
                            <option value="B">B - S.A.L.</option>
                            <option value="C">C - Superficie</option>
                            <option value="D">D - Prioritario por superficie</option>
                        </select>
                        @error('categoria')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" wire:click="abrirModalExtra">
                        Siguente
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade @if ($modalExtra) show d-block @endif" tabindex="-1"
        style="background: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Campos adicionales</h5>
                    <button type="button" class="close" wire:click="cerrarModalExtra">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body row">

                    <div class="form-group col-md-6">
                        <label for="paquetes">Paquetes</label>
                        <input type="number" wire:model.defer="paquetes" class="form-control"
                            style="text-transform: uppercase;">
                        @error('paquetes')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="peso">Peso</label>
                        <input type="number" wire:model.defer="peso" class="form-control"
                            style="text-transform: uppercase;">
                        @error('peso')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="aduana">Aduana</label>
                        <select class="form-control" id="aduana" name="aduana" required>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                        @error('aduana')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="codigo_manifiesto">Código Manifiesto</label>
                        <input type="text" wire:model.defer="codigo_manifiesto" class="form-control"
                            style="text-transform: uppercase;">
                        @error('codigo_manifiesto')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button wire:click="guardar" class="btn btn-primary">
                        {{ $cn35_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModalExtra">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>
