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
        text-align: center;
        padding: 10px;
    }

    body {
        font-family: Arial, sans-serif;
        padding-top: 30px;
    }

    th {
        background: #323e48;
        color: white;
        height: 3%;
        text-align: center;
        font-weight: bold;
    }

    header {
        position: fixed;
        top: 0cm;
        left: 0.8cm;
        right: 0cm;
        height: 3cm;
    }

    body {
        margin: 3cm 1cm;
    }

    td.instrument-info {
        text-align: left;
    }

    .new-page {
        page-break-before: always;
    }

</style>


    @if(($national_direction_name == NULL || $national_direction_name == '') && ($kind_of_report != 'General'))
        <header>
            <table>
                <tr>
                    <td><h3>DIRECCIÓN GENERAL DE RELACIONES INTERNACIONALES Y COOPERACIÓN</h3></td>
                    <td><img src="img/logo.jpg" width="200" height="130"></td>
                </tr>
            </table>
        </header>
        @foreach($data as $d)
        <body>
            <table>
                <tbody>
                    <tr>
                        <th colspan="2">Información del instrumento</th>
                    </tr>
                    <tr>
                        <td class="instrument-info">Nombre del instrumento:</td>
                        <td class="instrument-info">{{ $d->instrument_name }}</td>
                    </tr>
                    <tr>
                        <td class="instrument-info">Tipo de convenio:</td>
                        <td class="instrument-info">{{ $d->type_instrument_name }}</td>
                    </tr>
                    <tr>
                        <td class="instrument-info">Entidad:</td>
                        <td class="instrument-info">{{ $d->entity_name }}</td>
                    </tr>
                    <tr>
                        <td class="instrument-info">Descripción:</td>
                        <td class="instrument-info">{{ $d->description }}</td>
                    </tr>
                    <tr>
                        <td class="instrument-info">Sector:</td>
                        <td class="instrument-info">{{ $d->sector_name }}</td>
                    </tr>
                    
                </tbody>
            </table>
            <br>
        
            @if($d->type_instrument_name == 'Convenio')
                <table>
                    <tr>
                        <th colspan="6">Exoneraciones</th>
                    </tr>
                    <br>

                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Cantidad Horas</strong></th>
                            <th><strong>Descripción</strong></th>
                            <th><strong>Cantidad Personas</strong></th>
                            <th><strong>Monto percibido en taquilla</strong></th>
                            <th><strong>Monto exonerado</strong></th>
                        </tr>
                    </thead>

                    @foreach($d->exonerations as $exo)
                        <tr>
                            <td>{{ $exo->date_event }}</td>

                            <td>{{ $exo->number_hour }}</td>

                            <td>{{ $exo->exonerated_description }}</td>
                            
                            <td>{{ $exo->number_people }}</td>

                            <?php if($exo->non_tariff_amount == 0) { ?>
                                <td>${{ number_format($exo->tariff_amount, 2) }}</td>
                            <?php } else { ?>
                                <td>${{ number_format($exo->non_tariff_amount, 2) }}</td>
                            <?php } ?>

                            <td>${{ number_format($exo->total_amount, 2) }}</td>
                            
                        </tr>    
                    @endforeach
                </table>
                <br><br>
                <br>
                <?php if($d->totalAmount == NULL || $d->totalAmount == ''){ ?>
                    <h4>Total de contribución: $</h4>
                <?php } else { ?>
                    <h4>Total de contribución: ${{ number_format($d->totalAmount, 2) }}</h4>
                <?php } ?>
            @else
                <table>
                    <tr>
                        <th colspan="6">Exoneraciones</th>
                    </tr>
                    <br>

                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Concepto</strong></th>
                            <th><strong>Cantidad</strong></th>
                            <th><strong>Descripción</strong></th>
                            <th><strong>Precio estimado</strong></th>
                            <th><strong>Total</strong></th>
                        </tr>
                    </thead>

                    @foreach($d->exonerations as $exo)
                        <tr>
                            <td>{{ $exo->date_event }}</td>
                            <td>{{ $exo->concept }}</td>
                            <td>{{ $exo->quantity }}</td>
                            <td>{{ $exo->exonerated_description }}</td>
                            <td>${{ number_format($exo->estimated_price, 2) }}</td>
                            <td>${{ number_format($exo->total_amount, 2) }}</td>      
                        </tr>    
                    @endforeach
                </table>
                <br><br><br>
                <?php if($d->totalAmount == NULL || $d->totalAmount == ''){ ?>
                    <h4>Total de contribución: $</h4>
                <?php } else { ?>
                    <h4>Total de contribución: ${{ number_format($d->totalAmount, 2) }}</h4>
                <?php } ?>
            </body>
            @endif
        @endforeach

    @elseif($kind_of_report == NULL || $kind_of_report == '')
        <header>
            <table>
                <tr>
                    <td><h3>DIRECCIÓN GENERAL DE RELACIONES INTERNACIONALES Y COOPERACIÓN</h3></td>
                    <td><img src="img/logo.jpg" width="200" height="130"></td>
                </tr>
            </table>
        </header>
        <table>
            <tr>
                <td>
                    <h4 align="center">REPORTE DE PROYECTOS POR DIRECCIÓN NACIONAL</h4>
                    <h4 align="center">{{ $national_direction_name }}</h4>
                </td>
            </tr>
        </table>
        <body>
            <table>
                <thead>
                    <tr>
                        <th scope="col"><strong>Instrumento</strong></th>
                        <th scope="col"><strong>Servicio</strong></th>
                        <th scope="col"><strong>Sector</strong></th>
                        <th scope="col"><strong>Fecha</strong></th>
                        <th scope="col"><strong>Horas</strong></th>
                        <th scope="col"><strong>Descripción</strong></th>
                        <th scope="col"><strong>Cantidad / Cantidad de personas</strong></th>
                        <th scope="col"><strong>Monto Percibido en Taquilla</strong></th>
                        <th scope="col"><strong>Monto Exonerado</strong></th>
                        <th scope="col"><strong>Total Exonerado</strong></th>
                    </tr>
                </thead>

                @foreach($data as $d)
                    <br>
                        <tr>
                            <th colspan="10">{{ $d->entity_name }}</th>
                        </tr>
                    <br>
                    <tbody>
                        @foreach($d->exonerations as $exo)
                            <tr>
                                <td>{{ $d->instrument_name }}</td>
                                <?php if($exo->service_place_name == NULL || $exo->service_place_name == '') { ?>
                                    <td>{{ $exo->concept }}</td>
                                <?php } else { ?>
                                    <td>{{ $exo->service_place_name }}</td>
                                <?php } ?>

                                <td>{{ $d->sector_name }}</td>
                                <td>{{ $exo->date_event }}</td>
                                <td>{{ $exo->number_hour }}</td>
                                <td>{{ $exo->exonerated_description }}</td>

                                 <?php if($exo->number_people == NULL || $exo->number_people == '') { ?>
                                    <td>{{ $exo->quantity }}</td>
                                <?php } else { ?>
                                    <td>{{ $exo->number_people }}</td>
                                <?php } ?>  

                                <?php if($exo->number_people == NULL) { ?>
                                    <td></td>
                                    <?php if($exo->non_tariff_amount == 0 && $exo->estimated_price == NULL) { ?>
                                        <td>${{ number_format($exo->tariff_amount, 2) }}</td>
                                    <?php } else if($exo->tariff_amount == 0 && $exo->estimated_price == NULL){ ?>
                                        <td>${{ number_format($exo->non_tariff_amount, 2) }}</td>
                                    <?php } ?>
                                <?php } ?>

                                <?php if($exo->number_hour == NULL && $exo->estimated_price == NULL && $exo->quantity == NULL) { ?>
                                    <?php if($exo->non_tariff_amount == 0 && $exo->non_tariff_amount == NULL) { ?>
                                        <td>${{ number_format($exo->tariff_amount, 2) }}</td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>${{ number_format($exo->non_tariff_amount, 2) }}</td>
                                        <td></td>
                                    <?php } ?>
                                <?php } else if($exo->number_hour == NULL && $exo->non_tariff_amount == NULL && $exo->tariff_amount == NULL) {?>
                                    <td>${{ number_format($exo->estimated_price, 2) }}</td>
                                <?php } ?>
                                    
                                <td>${{ number_format($exo->total_amount, 2) }}</td>
                            </tr>    
                        @endforeach
                    </tbody>
                    <br>
                    <tr>
                        <?php if($d->totalAmount == NULL || $d->totalAmount == ''){ ?>
                            <h4>Total de contribución: $</h4>
                        <?php } else { ?>
                            <td colspan="10"><h4>Total de contribución: ${{ number_format($d->totalAmount, 2) }}</h4></td>
                        <?php } ?>  
                    </tr>
                @endforeach
            </table>
        </body>
    @elseif($kind_of_report == 'General')
        <header>
            <table>
                <tr>
                    <td><h3>DIRECCIÓN GENERAL DE RELACIONES INTERNACIONALES Y COOPERACIÓN</h3></td>
                    <td><img src="img/logo.jpg" width="200" height="130"></td>
                </tr>
            </table>
        </header>
        <table>
            <tr>
                <td>
                    <h4 align="center">REPORTE GENERAL DE PROYECTOS</h4>
                </td>
            </tr>
        </table>
        <body>
            <table>
                <thead>
                    <tr>
                        <th scope="col"><strong>Servicio</strong></th>
                        <th scope="col"><strong>Sector</strong></th>
                        <th scope="col"><strong>Fecha</strong></th>
                        <th scope="col"><strong>Horas</strong></th>
                        <th scope="col"><strong>Descripción</strong></th>
                        <th scope="col"><strong>Cantidad / Cantidad de personas</strong></th>
                        <th scope="col"><strong>Monto Percibido en Taquilla</strong></th>
                        <th scope="col"><strong>Monto Exonerado</strong></th>
                        <th scope="col"><strong>Total Exonerado</strong></th>
                    </tr>
                </thead>

                <?php $previousDirection = null; ?>
                @foreach($data as $d)
                    <?php if ($d->national_direction_name !== $previousDirection) { ?>
                        <br>
                            <tr>
                                <th colspan="10">{{ $d->national_direction_name }}</th>
                            </tr>
                    <?php $previousDirection = $d->national_direction_name; } ?>
                        <br>
                        <tr>
                            <td colspan="10" class="instrument-info"><strong>Entidad: {{ $d->entity_name }}</strong></td>
                        </tr>
                    <tbody>
                        @foreach($d->exonerations as $exo)
                            <tr>
                                <?php if($exo->service_place_name == NULL || $exo->service_place_name == '') { ?>
                                    <td>{{ $exo->concept }}</td>
                                <?php } else { ?>
                                    <td>{{ $exo->service_place_name }}</td>
                                <?php } ?>

                                <td>{{ $d->sector_name }}</td>
                                <td>{{ $exo->date_event }}</td>
                                <td>{{ $exo->number_hour }}</td>
                                <td>{{ $exo->exonerated_description }}</td>

                                <?php if($exo->number_people == NULL || $exo->number_people == '') { ?>
                                    <td>{{ $exo->quantity }}</td>
                                <?php } else { ?>
                                    <td>{{ $exo->number_people }}</td>
                                <?php } ?>  

                                <?php if($exo->number_people == NULL) { ?>
                                    <td></td>
                                    <?php if($exo->non_tariff_amount == 0 && $exo->estimated_price == NULL) { ?>
                                        <td>${{ number_format($exo->tariff_amount, 2) }}</td>
                                    <?php } else if($exo->tariff_amount == 0 && $exo->estimated_price == NULL){ ?>
                                        <td>${{ number_format($exo->non_tariff_amount, 2) }}</td>
                                    <?php } ?>
                                <?php } ?>

                                <?php if($exo->number_hour == NULL && $exo->estimated_price == NULL && $exo->quantity == NULL) { ?>
                                    <?php if($exo->non_tariff_amount == 0 && $exo->non_tariff_amount == NULL) { ?>
                                        <td>${{ number_format($exo->tariff_amount, 2) }}</td>
                                        <td></td>
                                    <?php } else { ?>
                                        <td>${{ number_format($exo->non_tariff_amount, 2) }}</td>
                                        <td></td>
                                    <?php } ?>
                                <?php } else if($exo->number_hour == NULL && $exo->non_tariff_amount == NULL && $exo->tariff_amount == NULL) {?>
                                    <td>${{ number_format($exo->estimated_price, 2) }}</td>
                                <?php } ?>
                                
                                <td>${{ number_format($exo->total_amount, 2) }}</td>
                            </tr>    
                        @endforeach
                    </tbody>
                    <br>
                    <tr>
                        <?php if($d->totalAmount == NULL || $d->totalAmount == ''){ ?>
                            <td colspan="10" class="instrument-info"><h4>Total de contribución: $</h4></td>
                        <?php } else { ?>
                            <td colspan="10" class="instrument-info"><h4>Total de contribución: ${{ number_format($d->totalAmount, 2) }}</h4></td>
                        <?php } ?>  
                    </tr> 
                @endforeach
            </table>
        </body>
    @endif
    <div id="footer">

    </div>
</html>