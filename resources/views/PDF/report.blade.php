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
        background: #323e48;
        color: white;
        height: 3%;
        text-align: center;
        font-weight: bold;
    }

    h3 {
        line-height: 200%;
    }

    body {
        font-family: Arial, sans-serif;
        padding-top: 30px;
    }

    th {
        text-align: left;
        padding: 12px;
    }

    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 3cm;
    }

    body {
        margin: 3cm 1cm;
    }

</style>

@if($national_direction_name == NULL || $national_direction_name == '')
<header>
     <table>
        <tr>
            <th><h3>DIRECCIÓN GENERAL DE RELACIONES INTERNACIONALES Y COOPERACIÓN</h3></td>
            <th><img src="img/logo.jpg" width="200" height="130"></td>
        </tr>
    </table>
</header>

<table>
    <tr>
        <th>
            <h4 align="center">REPORTE DE PROYECTOS POR DIRECCIÓN</h4>
            @foreach($data as $d)
            <h4 align="center">{{ $d->national_direction_name }}</h4>
            @endforeach
        </th>
    </tr>
</table>

<body>
    <div>
        <table>
            <thead>
                <tr>
                    <td scope="col"><strong>Servicio</strong></td>
                    <td scope="col"><strong>Sector</strong></td>
                    <td scope="col"><strong>Fecha</strong></td>
                    <td scope="col"><strong>Horas</strong></td>
                    <td scope="col"><strong>Descripción</strong></td>
                    <td scope="col"><strong>Cantidad de personas</strong></td>
                    <td scope="col"><strong>Monto Percibido en Taquilla</strong></td>
                    <td scope="col"><strong>Monto Exonerado</strong></td>
                    <td scope="col"><strong>Total Exonerado</strong></td>
                </tr>
            </thead>
            
            @foreach($data as $d)
            <tbody> 
                @foreach($d->exonerations as $exo)
                    <tr>
                        <?php if($exo->service_place_name == NULL || $exo->service_place_name == '') { ?>
                            <th>{{ $exo->concept }}</th>
                        <?php } else { ?>
                            <th>{{ $exo->service_place_name }}</th>
                        <?php } ?>

                        <th>{{ $d->sector_name }}</th>
                        <th>{{ $exo->date_event }}</th>
                        <th>{{ $exo->number_hour }}</th>
                        <th>{{ $exo->exonerated_description }}</th>

                        <th>{{ $exo->number_people }}</th>

                       <?php if($exo->number_people == NULL) { ?>
                            <th></th>
                            <?php if($exo->non_tariff_amount == 0) { ?>
                                <th>${{ number_format($exo->tariff_amount, 2) }}</th>
                            <?php } else { ?>
                                <th>${{ number_format($exo->non_tariff_amount, 2) }}</th>
                            <?php } ?>
                        <?php } ?>

                        <?php if($exo->number_hour == NULL && $exo->estimated_price == NULL) { ?>
                            <?php if($exo->non_tariff_amount == 0) { ?>
                                <th>${{ number_format($exo->tariff_amount, 2) }}</th>
                            <?php } else { ?>
                                <th>${{ number_format($exo->non_tariff_amount, 2) }}</th>
                            <?php } ?>
                            <th></th>
                        <?php } ?>
                            
                        <th>${{ number_format($exo->total_amount, 2) }}</th>
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <br>
        <?php if($d->total == NULL || $d->total == ''){ ?>
            <h4>Total de contribución: $</h4>
        <?php } else { ?>
            <h4>Total de contribución: $ {{ number_format( $d->total, 2) }}</h4>
        <?php } ?>
    </div>
    @endforeach
    <div id="footer">

    </div>
</body>

@else 
<header>
     <table>
        <tr>
            <th><h3>DIRECCIÓN GENERAL DE RELACIONES INTERNACIONALES Y COOPERACIÓN</h3></td>
            <th><img src="img/logo.jpg" width="200" height="130"></td>
        </tr>
    </table>
</header>

<table>
    <tr>
        <th>
            <h4 align="center">REPORTE GENERAL PROYECTOS</h4>
        </th>
    </tr>
</table>

<body>
    <div>
        <table>
            <thead>
                <tr>
                    <td scope="col"><strong>Servicio</strong></td>
                    <td scope="col"><strong>Sector</strong></td>
                    <td scope="col"><strong>Dirección Nacional</strong></td>
                    <td scope="col"><strong>Fecha</strong></td>
                    <td scope="col"><strong>Horas</strong></td>
                    <td scope="col"><strong>Descripción</strong></td>
                    <td scope="col"><strong>Cantidad de personas</strong></td>
                    <td scope="col"><strong>Monto Percibido en Taquilla</strong></td>
                    <td scope="col"><strong>Monto Exonerado</strong></td>
                    <td scope="col"><strong>Total Exonerado</strong></td>
                </tr>
            </thead>
            
            @foreach($data as $d)
            <tbody> 
                @foreach($d->exonerations as $exo)
                    <tr>
                        <?php if($exo->service_place_name == NULL || $exo->service_place_name == '') { ?>
                            <th>{{ $exo->concept }}</th>
                        <?php } else { ?>
                            <th>{{ $exo->service_place_name }}</th>
                        <?php } ?>

                        <th>{{ $d->sector_name }}</th>
                        <th>{{ $national_direction_name }}</th>
                        <th>{{ $exo->date_event }}</th>
                        <th>{{ $exo->number_hour }}</th>
                        <th>{{ $exo->exonerated_description }}</th>

                        <th>{{ $exo->number_people }}</th>

                        <?php if($exo->number_people == NULL) { ?>
                            <th></th>
                            <?php if($exo->non_tariff_amount == 0) { ?>
                                <th>${{ number_format($exo->tariff_amount, 2) }}</th>
                            <?php } else { ?>
                                <th>${{ number_format($exo->non_tariff_amount, 2) }}</th>
                            <?php } ?>
                        <?php } ?>

                        <?php if($exo->number_hour == NULL && $exo->estimated_price == NULL) { ?>
                            <?php if($exo->non_tariff_amount == 0) { ?>
                                <th>${{ number_format($exo->tariff_amount, 2) }}</th>
                            <?php } else { ?>
                                <th>${{ number_format($exo->non_tariff_amount, 2) }}</th>
                            <?php } ?>
                            <th></th>
                        <?php } ?>
                            
                        <th>${{ number_format($exo->total_amount, 2) }}</th>
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <br>
        <?php if($d->total == NULL || $d->total == ''){ ?>
            <h4>Total de contribución: $</h4>
        <?php } else { ?>
            <h4>Total de contribución: $ {{ number_format( $d->total, 2) }}</h4>
        <?php } ?>
    </div>
    @endforeach
    <div id="footer">
    </div>
</body>
@endif
</html>