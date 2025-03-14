<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CN-35, CN-31 y CN-38</title>
    <style>
        /* Estilos generales */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        thead {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Estilos para CN-35 */
        .cn35-table {
            width: 64%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .cn35-table th,
        .cn35-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .transparent-left-border {
            border-left-color: transparent !important;
        }

        .transparent-right-border {
            border-right-color: transparent !important;
        }

        .transparent-top-border {
            border-top-color: transparent !important;
        }

        .transparent-bottom-border {
            border-bottom-color: transparent !important;
        }

        /* Estilos para CN-31 */
        .cn31-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .cn38-section table,
        .cn38-section th,
        .cn38-section td {
            border: none !important;
            /* Quita el borde */
        }


        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            line-height: 0.5;
        }

        .title {
            text-align: center;
        }

        .barcode-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .barcode-content {
            display: inline-block;
            margin-bottom: 10px;
        }

        .firma {
            text-align: center;
            margin-top: 20px;
            line-height: 0;
        }

        .cn {
            text-align: center;
            float: right;
        }

        .special-text {
            text-align: center;
            font-size: 12px;
            border: none;
            font-weight: normal;
            line-height: 0.1;
        }

        /* Footer positioning */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
@if ($despacho->service === 'LC')

    <body>
        <!-- CN-31 Content -->
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('images/images.png') }}" alt="" width="150" height="50">
                <p class="cn">CN 31</p>
            </div>
            <div class="title">
                <h2>Hoja de Aviso</h2>
                <h3>AGENCIA BOLIVIANA DE CORREOS</h3>
            </div>
            <div class="barcode-container">
                <div class="barcode-content">
                    {!! DNS1D::getBarcodeHTML($identificador, 'C128', 1.25, 25) !!}
                    <br>
                    <div>{{ $identificador }}</div>
                </div>
            </div>
        </div>
        <table class="cn31-table">
            <thead>
                <tr>
                    <th rowspan="2">Operadores</th>
                    <th>Origen</th>
                    <td colspan="5">{{ $ciudadOrigen }} - AGENCIA BOLIVIANA DE CORREOS</td>
                </tr>
                <tr>
                    <th>Destino</th>
                    <td colspan="5">{{ $ciudadDestino }} - AGENCIA BOLIVIANA DE CORREOS</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Origen OE</th>
                    <th>Destino OE</th>
                    <th>Categoria</th>
                    <th>Sub-Clase</th>
                    <th>Año</th>
                    <th>Nro. de Despacho</th>
                    <th>Fecha</th>
                </tr>
                <tr>
                    <td>{{ $siglaOrigen }}</td>
                    <td>{{ $ofdestino }}</td>
                    <td>{{ $categoria }}</td>
                    <td>{{ $subclase }}</td>
                    <td>{{ $ano }}</td>
                    <td>{{ str_pad($despacho->nrodespacho, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $created_at }}</td>
                </tr>
            </tbody>
        </table>
        <p><b>1. Cantidad de sacas</b></p>
        <table class="cn31-table">
            <thead>
                <tr>
                    <th>Etiquetas de Envase</th>
                    <th>Etiquetas Rojas</th>
                    <th>Etiquetas Blancas/Azules</th>
                    <th>Tipos de envases</th>
                    <th>Sacas</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Prioritario/no prioritario LC/AO</td>
                    <td>{{ $totalContenidoR }}</td>
                    <td>{{ $totalContenidoB }}</td>
                    <td>Envases en el despacho</td>
                    <td>{{ $totalContenido }}</td>
                    <td>{{ $totalContenido }}</td>
                </tr>
                <tr>
                    <td>Sacas M</td>
                    <td>{{ $sacasm }}</td>
                    <td>----</td>
                    <td>CN 33</td>
                    <td>{{ $listas }}</td>
                    <td>----</td>
                </tr>
            </tbody>
        </table>
        <p><b>2. Gastos de tránsito y gastos terminales</b></p>
        <table class="cn31-table">
            <thead>
                <tr>
                    <th colspan="3">Correo sujeto al pago de gastos terminales, totales por formato</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Formato</th>
                    <th>Cantidad (PAQUETES)</th>
                    <th>Peso (Kg.)</th>
                </tr>

                @if ($despacho->service === 'LC')
                    <tr>
                        <td>ORDINARIOS</td>
                        <td>{{ $nropaquetesbl ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>CERTIFICADOS</td>
                        <td>{{ $nropaquetesro ?? '0' }}</td>
                        <td></td>
                    </tr>
                @elseif ($despacho->service === 'EMS')
                    <tr>
                        <td>CONTRATOS</td>
                        <td>{{ $nropaquetesco ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ENCOMIENDAS</td>
                        <td>{{ $nropaquetescp ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>EMS</td>
                        <td>{{ $nropaquetesems ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>SUPER EXPRESS</td>
                        <td>{{ $nropaquetessu ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ENVIO OFICIAL</td>
                        <td>{{ $nropaquetesof ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ENVIO INTERNACIONAL</td>
                        <td>{{ $nropaquetesii ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ENVIO TRANSITO</td>
                        <td>{{ $nropaqueteset ?? '0' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ENVIO SIN DESPACHO</td>
                        <td>{{ $nropaquetessn ?? '0' }}</td>
                        <td></td>
                    </tr>
                @endif
                <tr>
                    <td>Total</td>
                    <td>{{ $totalPaquetes }}</td>
                    <td>{{ $peso }}</td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <table>
                <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                    <p class="special-text">__________________________</p>
                    <p class="special-text">RECIBIDO POR</p>
                </td>
                <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                    <p class="special-text">__________________________ </p>
                    <p class="special-text">ENTREGADO POR</p>
                    <p class="special-text">{{ auth()->user()->name }}</p>
                </td>
            </table>
        </div>
    </body>
@elseif ($despacho->service === 'EMS')
@endif

<body>
    <!-- CN-38 Content -->
    <div class="cn38-section">
        <div class="content">
            <div class="header">
                <div class="logo">
                    <img src="{{ public_path('images/images.png') }}" alt="" width="150" height="50">
                    <p class="cn">CN 38</p>
                </div>
                <div class="title">
                    <h2>Factura de Entrega</h2>
                    <h3>AGENCIA BOLIVIANA DE CORREOS</h3>
                </div>
            </div>
            <table>
                <tbody>
                    <tr>
                        <th style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                            Oficina de Cambio: {{ $ciudadOrigen }} - {{ $siglaOrigen }}
                        </th>
                        <th style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                            Oficina de Destino:{{ $ciudadDestino }} - {{ $ofdestino }}
                        </th>
                        <th style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                            @php
                                $subclaseTranslation = [
                                    'A' => 'Aéreo',
                                    'B' => 'S.A.L.',
                                    'C' => 'Superficie',
                                    'D' => 'Prioritario por superficie',
                                ];
                            @endphp
                            Medio de Transporte: {{ $subclaseTranslation[$categoria] ?? $categoria }}
                        </th>
                    </tr>
                    <tr>
                        <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                            <p>Fecha: {{ $despacho->created_at->format('Y-m-d') }}</p>
                        </td>
                        <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                            @php
                                $claseTranslation = [
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
                                    'UY' => 'UY CARTAS - RESERVADO PARA USO MULTILATERAL EN PROYECTOS DESIGNADOS',
                                    'UZ' => 'UZ CARTAS - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                    'EA' => 'EA EMS - AL DESCUBIERTO',
                                    'ED' => 'ED EMS - DOCUMENTOS',
                                    'EG' => 'EG EMS - PLAZO GARANTIZADO: DOCUMENTOS',
                                    'EH' => 'EH EMS - PLAZO GARANTIZADO: MERCANCIA',
                                    'EI' => 'EI EMS - PLAZO GARANTIZADO: MIXTO',
                                    'EM' => 'EM EMS - MERCADERIA',
                                    'EN' => 'EN EMS - MIXTO',
                                    'ER' => 'ER EMS - MERCANCIA DEVUELTA',
                                    'ET' => 'ET EMS - SACAS VACIAS',
                                    'EU' => 'EU EMS - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                    'EY' => 'EY EMS - RESERVADO PARA USO MULTILATERAL EN PROYECTOS DESIGNADOS',
                                    'EZ' => 'EZ EMS - RESERVADO PARA USO DE ACUERDOS BILATERALES',
                                ];
                            @endphp
                            Clase: {{ $claseTranslation[$subclase] ?? $subclase }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="first-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>DESPACHO</th>
                        <th>ORIGEN</th>
                        <th>DESTINO</th>
                        <th>LC-BOLSAS DE CORREO</th>
                        <th>CP-BOLSAS DE CORREO</th>
                        <th>EMS-BOLSAS DE CORREO</th>
                        <th>PESO (Kg.)</th>
                        <th>OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($sacas as $index => $saca)
                        @if ($i > 20)
                            @break
                        @endif
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ str_pad($despacho->nrodespacho, 3, '0', STR_PAD_LEFT) }} /
                                {{ str_pad($saca->nrosaca, 3, '0', STR_PAD_LEFT) }}
                                @if ($index === count($sacas) - 1)
                                    <strong>F</strong>
                                @endif
                            </td>
                            @php
                                // Mapeo de siglas
                                $siglaIATA = [
                                    'BOLPZ' => 'LPZ',
                                    'BOTJA' => 'TJA',
                                    'BOPOI' => 'POI',
                                    'BOCIJ' => 'CIJ',
                                    'BOORU' => 'ORU',
                                    'BOTDD' => 'TDD',
                                    'BOSRE' => 'SRE',
                                    'BOSRZ' => 'SRZ',
                                ];
                            @endphp
                            <td>{{ $siglaIATA[$siglaOrigen] ?? $siglaOrigen }}</td>
                            <td>{{ $siglaIATA[$ofdestino] ?? $ofdestino }}</td>
                            @if ($despacho->service === 'LC')
                                <td>1</td>
                            @else
                                <td></td>
                            @endif
                            <td></td>
                            @if ($despacho->service === 'EMS')
                                <td>1</td>
                            @else
                                <td></td>
                            @endif
                            <td>{{ $saca->peso }}Kg.</td>
                            <td>
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
                                {{ $tipos[$saca->tipo] ?? $saca->tipo }}/{{ $etiqueta[$saca->etiqueta] ?? $saca->etiqueta }}
                            </td>
                        </tr>
                        @php $i++; @endphp
                    @endforeach

                    <!-- Filas vacías si el total de $sacas es menor que 30 -->
                    @for ($j = $i; $j <= 20; $j++)
                        <tr>
                            <td>{{ $j }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            <table>
                <thead>
                    <tr>
                        <td>SACAS TOTAL</td>
                        <th>{{ $saca->nrosaca }}</th>
                        <td>PESO TOTAL</td>
                        <th>{{ $peso }} Kg.</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="footer">
            <table>
                <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                    <p class="special-text">__________________________</p>
                    <p class="special-text">RECIBIDO POR</p>
                    <p class="special-text"></p>
                </td>
                <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
                    <p class="special-text">__________________________ </p>
                    <p class="special-text">ENTREGADO POR</p>
                    <p class="special-text">{{ auth()->user()->name }}</p>
                </td>
            </table>
        </div>
    </div>
</body>

<body>
    <!-- CN-35 Content -->
    @foreach ($sacas as $index => $saca)
        <table class="cn35-table">
            <tr>
                <td colspan="2" class="transparent-bottom-border">PARA :</td>
                <td class="transparent-bottom-border text-center">GESPA <strong>{{ $despacho->service }}</strong></td>
                <td class="transparent-bottom-border text-center">CN 35</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">{{ $ciudadDestino }}</td>
                <td class="text-center">
                    @if ($despacho->service === 'LC')
                        <br><strong style="font-size: 20px;">TRADICIONAL</strong>
                    @elseif ($despacho->service === 'EMS')
                        <br><strong style="font-size: 20px;">PRIORITARIO</strong>
                    @endif
                </td>
                @if ($index === count($sacas) - 1)
                    <td class="text-center" style="font-size: 20px;"><strong>F</strong></td>
                @endif
            </tr>
            <tr>
                <td>Cat: {{ $categoria }}</td>
                <td>SubC: {{ $subclase }}</td>
                <td class="transparent-right-border text-right">{{ $siglaOrigen }} (BOA)</td>
                <td>{{ $ciudadOrigen }} {{ $despacho->service }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">{{ $despacho->created_at->format('Y-m-d') }}</td>
                <td colspan="2" class="transparent-bottom-border text-center">AGENCIA BOLIVIANA DE CORREOS</td>
            </tr>
            <tr>
                <td>DespNo: {{ str_pad($despacho->nrodespacho, 3, '0', STR_PAD_LEFT) }}</td>
                <td>RecNo: {{ str_pad($saca->nrosaca, 3, '0', STR_PAD_LEFT) }}</td>
                <td colspan="2" class="text-center">(AGBC)</td>
            </tr>
            <tr>
                <td>Peso: {{ $saca->peso }} Kg.</td>
                <td>NoPaq: {{ $saca->nropaquetes }}</td>
                <td colspan="2" class="transparent-bottom-border text-center">{{ $saca->receptaculo }}</td>
            </tr>
            <tr>
                @php
                    $subclaseTranslation = [
                        'A' => 'Aéreo',
                        'B' => 'S.A.L.',
                        'C' => 'Superficie',
                        'D' => 'Prioritario por superficie',
                    ];
                @endphp
                <td>Via: {{ $subclaseTranslation[$categoria] ?? $categoria }}</td>
                <td></td>
                <td colspan="2">
                    <p>{!! DNS1D::getBarcodeHTML($saca->receptaculo, 'C128', 1.08, 40) !!}</p>
                </td>
            </tr>
        </table>
        <br>
    @endforeach
</body>

</html>
