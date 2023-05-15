<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report</title>
</head>
<style>
    table {
        width: 100%;
        border-spacing: 0px;
    }

    td {
        text-align: left;
        padding: 12px;
    }

    h3 {
        line-height: 200%;
    }

    body {
        font-family: Arial, sans-serif;
    }

    th {
        background: #323e48;
        color: white;
        height: 3%;
        text-align: center;
        font-weight: bold;
    }


</style>

<body>
    <div>
        <table>
            <tr>
                <td><h3>DIRECCIÓN GENERAL DE COMUNICACIONES</h3></td>
                <td><img src="img/logo.jpg" width="200" height="130"></td>
            </tr>
        </table>
    </div>

    @foreach($data as $d)
    <table>
        <tbody>
            <tr>
                <th colspan="2">Información del instrumento</th>
            </tr>
            <tr>
                <td>Nombre del instrumento:</td>
                <td>{{ $d->instrument_name }}</td>
            </tr>
            <tr>
                <td>Tipo de convenio:</td>
                <td>{{ $d->type_instrument_name }}</td>
            </tr>
            <tr>
                <td>Entidad:</td>
                <td>{{ $d->entity_name }}</td>
            </tr>
            <tr>
                <td>Descripción:</td>
                <td>{{ $d->description }}</td>
            </tr>
            <tr>
                <td>Sector:</td>
                <td>{{ $d->sector_name }}</td>
            </tr>
            
        </tbody>
    </table>
    <br>
    @if($d->instrument_name == 'Convenio')
        <table>
            <tbody> 
                <tr>
                    <th colspan="8">Exoneraciones</th>
                </tr>
                <tr >
                    <td><strong>Espacio de servicio</strong></td>
                    <td><strong>Fecha</strong></td>
                    <td><strong>Descripción</strong></td>
                    <td><strong>Monto tarifado/No tarifado</strong></td>
                    <td><strong>Cantidad Horas/Personas</strong></td>
                    <td><strong>Total</strong></td>
                </tr>

                @foreach($d->exonerations as $exo)
                    <tr>
                        <td>{{ $exo->service_place_name }}</td>
                        <td>{{ $exo->date_event }}</td>
                        <td>{{ $exo->exonerated_description }}</td>

                        <?php if($exo->non_tariff_amount == 0) { ?>
                            <td>{{ $exo->tariff_amount }}</td>
                        <?php } else { ?>
                            <td>{{ $exo->non_tariff_amount }}</td>
                        <?php } ?>

                        <?php if($exo->number_hour == NULL) { ?>
                            <td>{{ $exo->number_people }}</td>
                        <?php } else { ?>
                            <td>{{ $exo->number_hour }}</td>
                        <?php } ?>

                        <td>{{ $exo->total_amount }}</td>
                        
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <br><br>
        <br>
        <h4>Total de contribución: ${{ $d->totalAmount }}</h4>
    @else
        <table>
            <tbody> 
                <tr>
                    <th colspan="8">Exoneraciones</th>
                </tr>
                <tr >
                    <td><strong>Fecha</strong></td>
                    <td><strong>Concepto</strong></td>
                    <td><strong>Cantidad</strong></td>
                    <td><strong>Descripción</strong></td>
                    <td><strong>Precio estimado</strong></td>
                    <td><strong>Total</strong></td>
                </tr>

                @foreach($d->exonerations as $exo)
                    <tr>
                        <td>{{ $exo->date_event }}</td>
                        <td>{{ $exo->concept }}</td>
                        <td>{{ $exo->quantity }}</td>
                        <td>{{ $exo->exonerated_description }}</td>
                        <td>{{ $exo->estimated_price }}</td>
                        <td>{{ $exo->total_amount }}</td>
                        
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <br><br>
        <br>
        <h4>Total de contribución: ${{ $d->totalAmount }}</h4>
    @endif
@endforeach
    <div id="footer">

    </div>
</body>

</html>
