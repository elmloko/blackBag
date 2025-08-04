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
                <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                    @foreach ($detalles as $index => $detalle)
                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="mb-3">Saca Nº {{ $index + 1 }}</h5>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Paquetes</label>
                                    <input type="number" min="0" class="form-control"
                                        wire:model.defer="detalles.{{ $index }}.paquetes"
                                        placeholder="Ej. 10">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Peso (kg)</label>
                                    <input type="number" min="0" step="0.01" class="form-control"
                                        wire:model.defer="detalles.{{ $index }}.peso" placeholder="Ej. 2.50">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Aduana</label>
                                    <select class="form-control"
                                        wire:model.defer="detalles.{{ $index }}.aduana">
                                        <option value="">Seleccione</option>
                                        <option value="SI">SI</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Código Manifiesto</label>
                                    <input type="text" class="form-control"
                                        wire:model.defer="detalles.{{ $index }}.codigo_manifiesto"
                                        placeholder="Ej. MANI-00123">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="modal-footer">
                        <button wire:click="guardar" class="btn btn-primary">
                            {{ $cn35_id ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button type="button" class="btn btn-secondary"
                            wire:click="cerrarModalExtra">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const servicioSelect = document.querySelector('select[wire\\:model\\.defer="servicio"]');
        const subclaseSelect = document.getElementById('subclase');

        const opciones = {
            'EMS': ['EA', 'ED', 'EG', 'EH', 'EI', 'EM', 'EN', 'ER', 'ET', 'EU', 'EY', 'EZ'],
            'LC/AO': ['UA', 'UB', 'UC', 'UD', 'UE', 'UF', 'UG', 'UH', 'UI', 'UL', 'UM', 'UN', 'UP', 'UR',
                'UT', 'UU', 'UV', 'UX', 'UY', 'UZ'
            ],
            'CP': ['CA', 'CB', 'CC', 'CD', 'CF', 'CN', 'CR', 'CT', 'CU', 'CV', 'CX', 'CY', 'CZ']
        };

        const descripciones = {
            // EMS
            'EA': 'EA - EMS a descubierto',
            'ED': 'ED - EMS documentos',
            'EG': 'EG - EMS documentos con hora específica',
            'EH': 'EH - EMS mercancía con hora específica',
            'EI': 'EI - EMS mixto con hora específica',
            'EM': 'EM - EMS mercancía',
            'EN': 'EN - EMS mixto',
            'ER': 'ER - EMS devuelto',
            'ET': 'ET - EMS saca vacía',
            'EU': 'EU - EMS otro',
            'EY': 'EY - EMS especial',
            'EZ': 'EZ - EMS reservado',

            // LC/AO
            'UA': 'UA - Cartas AO',
            'UB': 'UB - Cartas masivo',
            'UC': 'UC - Correo directo armonizado',
            'UD': 'UD - Fuera del sistema de gastos terminales',
            'UE': 'UE - Cartas a descubierto',
            'UF': 'UF - Entrada directa LC',
            'UG': 'UG - Entrada directa AO',
            'UH': 'UH - Entrada directa LC/AO',
            'UI': 'UI - CCRI (Centro Clasificación)',
            'UL': 'UL - Cartas LC',
            'UM': 'UM - Sacas M',
            'UN': 'UN - Cartas LC/AO',
            'UP': 'UP - Tarjetas postales',
            'UR': 'UR - Certificado',
            'UT': 'UT - Reservado para acuerdos bilaterales',
            'UU': 'UU - Otros artículos',
            'UV': 'UV - Devueltos no entregables con remuneración',
            'UX': 'UX - Expreso',
            'UY': 'UY - Uso multilateral en proyectos designados',
            'UZ': 'UZ - Reservado para acuerdos bilaterales',

            // CP
            'CA': 'CA - Paquetes a descubierto',
            'CB': 'CB - Paquetes devueltos',
            'CC': 'CC - Comercio electrónico',
            'CD': 'CD - Acceso directo',
            'CF': 'CF - Servicio de consignación',
            'CN': 'CN - Paquete ordinario',
            'CR': 'CR - Mercancía devuelta',
            'CT': 'CT - Sacas vacías',
            'CU': 'CU - Uso bilateral reservado',
            'CV': 'CV - Paquete asegurado',
            'CX': 'CX - Uso multilateral en proyectos designados',
            'CY': 'CY - Multilateral reservado',
            'CZ': 'CZ - Reservado bilateral'
        };

        function actualizarSubclases() {
            const tipo = servicioSelect.value;
            subclaseSelect.innerHTML = '<option value="">Seleccione una opción</option>';
            if (opciones[tipo]) {
                opciones[tipo].forEach(code => {
                    const option = document.createElement('option');
                    option.value = code;
                    option.textContent = descripciones[code] || code;
                    subclaseSelect.appendChild(option);
                });
            }
        }

        servicioSelect.addEventListener('change', actualizarSubclases);

        // Si se cambia programáticamente (por Livewire o recuperación), actualiza
        window.livewire.hook('message.processed', () => {
            actualizarSubclases();
        });
    });
</script>
</div>
