<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CN-31</title>
    <style>
        /* Estilos para la tabla */
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

        .first-table th,
        .first-table td {
            border: 1px solid #000;
            padding: 5px;
            line-height: 0.5;
        }

        thead {
            background-color: #f2f2f2;
        }

        /* Estilos para la imagen y el título */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            line-height: 0.5;
        }

        .title {
            text-align: center;
        }

        .firma {
            text-align: center;
            margin-top: 20px;
            line-height: 0;
        }

        .date {
            border: none;
            line-height: 0.5;
        }

        .cn {
            text-align: center;
            float: right;
            /* Alinear a la derecha */
        }

        .special-text {
            text-align: center;
            font-size: 12px;
            border: none;
            font-weight: normal;
            line-height: 0.1;
        }

        .transparent-border {
            border-color: transparent !important;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/images.png') }}" alt="" width="150" height="50">
            <p class="cn">CN 31</p>
        </div>
        <div class="title">
            <h2>Hoja de Aviso</h2>
            <h3>AGENCIA BOLIVIANA DE CORREOS</h3>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <td class="transparent-border">{!! DNS1D::getBarcodeHTML($identificador, 'C128', 1.25, 25) !!}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="transparent-border" colspan="2">{{ $identificador }}</td>
            </tr>
        </tbody>
    </table>
    <table>
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
                <td>{{ $nrodespacho }}</td>
                <td>{{ $created_at }}</td>
            </tr>
        </tbody>
    </table>
    <p><b>1. Cantidad de envases</p>
    <table>
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
                {{-- <td>{{ $data->SACAR }}</td>
                <td>{{ $data->SACAU }}</td> --}}
                <td>Envases en el data</td>
                {{-- <td>{{ $sum }}</td> --}}
                {{-- <td>{{ $sum }}</td> --}}
            </tr>
            <tr>
                <td>Sacas M</td>
                {{-- <td>{{ $data->SACAM }}</td> --}}
                <td>0</td>
                <td>Envases que deben devolverse</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p><b>2. Gastos de tránsito y gastos terminales</p>
    <table>
        <thead>
            <tr>
                <th colspan="3">Correo sujeto al pago de gastos terminales, totales por formato</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Formato</th>
                <th>Cantidad (PAQUETES)</th>
                <th>Peso Kg.</th>
            </tr>
            <tr>
                <td>ORDINARIOS</td>
                <td>{{ $nropaquetesu }}</td>
                <td>{{ $pesou }}</td>
            </tr>
            <tr>
                <td>LC /AO</td>
                <td>{{ $nropaquetesl }}</td>
                <td>{{ $pesol }}</td>
            </tr>
            <tr>
                <td>SACAS M</td>
                <td>{{ $nropaquetesm }}</td>
                <td>{{ $pesom }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>{{ $totalPaquetes }}</td>
                <td>{{ $totalPeso }}</td>
                < </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
    <table>
        <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
            <p class="special-text">__________________________</p>
            <p class="special-text">RECIBIDO POR</p>
            {{-- <p class="special-text">{{ $data->TRASPORTE }}</p> --}}
        </td>
        <td style="border: none; text-align: left; font-weight: normal; line-height: 0.1;">
            <p class="special-text">__________________________ </p>
            <p class="special-text">ENTREGADO POR</p>
            {{-- <p class="special-text">{{ auth()->user()->name }}</p> --}}
        </td>
    </table>
</body>

</html>
