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
                                <div class="ml-auto d-flex">
                                    <input type="date" wire:model="fechaInicio" class="form-control"
                                        style="margin-right: 10px;">
                                    <input type="date" wire:model="fechaFin" class="form-control"
                                        style="margin-right: 10px;">
                                    <button type="button" class="btn btn-success" wire:click="exportToExcel">Exportar a
                                        Excel</button>
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
                                        <th>Enviado:</th>
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
                                                        'UV CARTAS - ARTÍCULOS DEVUELTOS QUE NO SE PUEDEN ENTREGAR SUJETOS A REMUNERACIÓN',
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
                                            <td>{{ $oficinas[$despacho->ofdestino] ?? $despacho->ofdestino }}</td>
                                            <td>{{ $despacho->identificador }}</td>
                                            <td>{{ $categorias[$despacho->categoria] ?? $despacho->categoria }}</td>
                                            <td>{{ $subclases[$despacho->subclase] ?? $despacho->subclase }}</td>
                                            <td>{{ $despacho->nroenvase }}</td>
                                            <td>{{ $despacho->peso }}</td>
                                            <td>{{ $despacho->estado }}</td>
                                            <td>{{ $despacho->updated_at }}</td>
                                            <td>
                                                @if ($despacho->estado === 'EXPEDICION')
                                                    <button wire:click="reaperturarDespacho({{ $despacho->id }})"
                                                        class="btn btn-warning">Intervenir Saca</button>
                                                    <button wire:click="reimprimirDespacho({{ $despacho->id }})"
                                                        class="btn btn-secondary">Reimprimir PDF</button>
                                                    {{-- <button wire:click="expedicionDespacho({{ $despacho->id }})"
                                                        class="btn btn-info">Expedición</button> --}}
                                                @elseif ($despacho->estado === 'OBSERVADO')
                                                    <a href="{{ route('saca.crear', $despacho->id) }}"
                                                        class="btn btn-primary">Editar Detalles del Despacho</a>
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
</div>
