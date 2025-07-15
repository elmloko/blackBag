<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Despachos MX</h1>
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
                                            <td>{{ $despacho->updated_at }}</td>
                                            <td>
                                                @if ($despacho->estado === 'EXPEDICION')
                                                    <button wire:click="reaperturarDespacho({{ $despacho->id }})"
                                                        class="btn btn-warning">Intervenir Saca</button>
                                                    <button wire:click="expedicionDespacho({{ $despacho->id }})"
                                                        class="btn btn-info">REIMPRIMIR CN</button>
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
