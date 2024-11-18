<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Admisiones LC/AO</h1>
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
                                <div class="ml-auto d-flex">
                                    <input type="date" wire:model="fechaInicio" class="form-control"
                                        style="margin-right: 10px;">
                                    <input type="date" wire:model="fechaFin" class="form-control"
                                        style="margin-right: 10px;">
                                    <button type="button" class="btn btn-success" wire:click="exportToExcel">Exportar a
                                        Excel</button>
                                    <button type="button" class="btn btn-info" wire:click="showModal">Admitir
                                        Registros</button>
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
                                        <th>Identificador</th>
                                        <th>Oficina Destino</th>
                                        <th>Categoría</th>
                                        <th>Subclase</th>
                                        <th>Nro. Envases</th>
                                        <th>Peso</th>
                                        <th>Estado</th>
                                        <th>Enviado:</th>
                                        {{-- <th>Acciones</th> --}}
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
                                                    'BOORU' => 'BOORU - ORURO',
                                                    'BOTDD' => 'BOTDD - BENI',
                                                    'BOSRE' => 'BOSRE - SUCRE',
                                                    'BOSRZ' => 'BOSRZ - SANTA CRUZ',
                                                ];
                                                $subclases = [
                                                    'UA' => 'UA CARTAS - AO',
                                                    'UB' => 'UB CARTAS - MASIVO',
                                                    'UC' => 'UC CARTAS - CORREO DIRECTO ARMONIZADO',
                                                    'UD' => 'UD CARTAS - FUERA DEL SISTEMA DE GASTOS TERMINALES',
                                                    'UE' => 'UE CARTAS - DECOUVERT',
                                                    'UF' => 'UF CARTAS - LC ENTRADA DIRECTA',
                                                    'UG' => 'UG CARTAS - AO ENTRADA DIRECTA',
                                                    'UH' => 'UH CARTAS - LC/AO ENTRADA DIRECTA',
                                                    'UI' => 'UI CARTAS - CCRI',
                                                    'UL' => 'UL CARTAS - LC',
                                                    'UM' => 'UM CARTAS - SACAS M',
                                                    'UN' => 'UN CARTAS - LC/AO',
                                                    'UP' => 'UP CARTAS - TARJETAS POSTALES',
                                                    'UR' => 'UR CARTAS - CERTIFICADO',
                                                    'US' => 'US CARTAS - SACAS VACIAS',
                                                    'UT' => 'UT CARTAS - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                                    'UV' =>
                                                        'UV LETTERS - ARTÍCULOS DEVUELTOS QUE NO SE PUEDEN ENTREGAR SUJETOS A REMUNERACIÓN',
                                                    'UX' => 'UX CARTAS - EXPRESO',
                                                    'UY' =>
                                                        'UY CARTAS - RESERVADO PARA USO MULTILATERAL EN PROYECTOS DESIGNADOS',
                                                    'UZ' => 'UZ CARTAS - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                                ];
                                                $categorias = [
                                                    'A' => 'A - Aéreo',
                                                    'B' => 'B - S.A.L.',
                                                    'C' => 'C - Superficie',
                                                    'D' => 'D - Prioritario por superficie',
                                                ];
                                            @endphp
                                            <td>{{ $despacho->identificador }}</td>
                                            <td>{{ $oficinas[$despacho->ofdestino] ?? $despacho->ofdestino }}</td>
                                            <td>{{ $categorias[$despacho->categoria] ?? $despacho->categoria }}</td>
                                            <td>{{ $subclases[$despacho->subclase] ?? $despacho->subclase }}</td>
                                            <td>{{ $despacho->nroenvase }}</td>
                                            <td>{{ $despacho->peso }}</td>
                                            <td>{{ $despacho->estado }}</td>
                                            <td>{{ $despacho->updated_at }}</td>
                                            {{-- <td>
                                                
                                            </td> --}}
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
    <div wire:ignore.self class="modal fade" id="admitirModal" tabindex="-1" aria-labelledby="admitirModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="admitirModalLabel">Admitir Registros</h5>
                </div>
                <div class="modal-body">
                    <p>Por favor, ingrese el valor del campo `saca.receptaculo` que desea buscar:</p>
                    <input type="text" wire:model="searchReceptaculo" wire:input="buscarReceptaculo"
                        placeholder="Saca Receptáculo" class="form-control">
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
                <div class="mt-3">
                    @if (count($registrosSeleccionados) > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Receptáculo</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrosSeleccionados as $registro)
                                    <tr>
                                        <td>{{ $registro['receptaculo'] }}</td>
                                        <td>{{ $registro['estado'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                wire:click="quitarRegistro({{ $registro['id'] }})">Quitar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-warning">No se han seleccionado registros aún.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="admitir">Admitir Todos</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('show-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('admitirModal'));
        modal.show();
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('hide-modal', () => {
            console.log('Evento hide-modal recibido'); // Verifica en la consola
            const modalElement = document.getElementById('admitirModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.hide();
        });
    });
</script>
