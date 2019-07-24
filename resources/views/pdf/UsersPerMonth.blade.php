<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Usuarios Por Mes</title>
    <style type="text/css">
		body{
			width: 100%;
			height: 100%;
			margin: 0;
		}

		#main-content{
			width: 100%;
			height: auto;
			margin-top: 150px;
		}

		.logo{
			width: 250px;
			height: 100px;
			display: inline-block;
			left: 0;
			position: absolute;
		}

        .logo img{
            width: 75px;
            height: 75px;
            margin-left: 35px;
            
        }
        .logo h1{
            margin-top: -5px;
        }
		.datos-empresa{
			width: 250px;
			height: 120px;
			display: inline-block;
			right: 0;
			position: absolute;
		}

		.titulo-empresa{
			font-size: 22px;
            text-align: left;
		}

		span{
			display: block;
		}

		#tabla-datos > thead > tr > th {
			border-bottom: 1px solid black;
		}

		#tabla-datos > tbody > tr > td {
			text-align: center;
		}

	</style>
</head>
<?php
    $time = strtotime($begin);
    $begin = date('d-m-Y',$time);
    $time = strtotime($end);
    $end = date('d-m-Y',$time);
?>

<body>
        <div class="logo">
            <img src="img/playic_launcher_app.png" alt="">
            <h1>FancyFun</h1>
		</div>
		<div class="datos-empresa">
			<center>
				<span class="titulo-empresa"> FancyFun </span>
			</center>
			<span>Fecha: {{ date('d M Y') }}</span>
			<span>Hora: {{ date('H:i:s') }}</span>
		</div>
        <div id="main-content">
            <center><h3>Usuarios Registrados durante {{ $begin }} y {{ $end }}</h3></center>
				<table id="tabla-datos" style="width: 100%;">
					<thead>
						<tr>
							<th width="20%">Fecha De Registro</th>
							<th width="5%">ID</th>
							<th width="17%">Nombre</th>
							<th width="12%">Correo</th>
							<th width="4%">Edad</th>
							<th width="10%">Cumpleaños</th>
							<th width="32%">Telefono</th>
						</tr>
					</thead>
					<tbody>
						@foreach($user as $item)
							<tr>
                            <?php
                                $time = strtotime($item["Fecha_De_Registro"]);
                                $item["Fecha_De_Registro"] = date('d-m-Y',$time);
                                $time = strtotime($item["Cumpleaños"]);
                                $item["Cumpleaños"] = date('d-m-Y',$time);
                            ?>
								<td>{!! $item["Fecha_De_Registro"] !!}</td>
								<td>{!! $item["id"] !!}</td>
								<td style="font-size : 12px;">{!! $item["Nombre"]." ".$item["Apellido"] !!}</td>
								<td>{!! $item["Correo"] !!}</td>
								<td>{!! $item["Edad"] !!}</td>
								<td>{!! $item["Cumpleaños"] !!}</td>
								<td style="font-size : 12px;">{!! $item["Telefono"] !!}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
        </div>
</body>
</html>