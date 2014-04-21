<?php

    $mensaje = "";
    $ultimo;

	foreach ($nuevos as $mens){

		/* * * * * Control de HORA/FECHA * * * * * * * * */
		$fechaTotal = explode(" ", $mens['fecha']);
		$fecha = $fechaTotal[0];
		$horaToda = explode(":", $fechaTotal[1]);
		$hora = $horaToda[0].':'.$horaToda[1];

		$mensaje .= '<div id="Conver'.$mens['id_ump'].'" class="mensaje" style="border: 1px solid black; width:300px;">';

		//Imprimir cada mensaje    
		if($fechaHoy == $fecha){

			$mensaje .= '<span class="letrita">[Hoy a las '.$hora.'] </span>';
		}else{
			$mensaje .= '<span class="letrita">['.$fecha.' - '.$hora.'] </span>';
		}

		if($mens['usu_remite'] == $this->session->userdata('id_usuario')){
		//Enviados
			$mensaje .= '<span class="letrita">Yo: </span>';
			$mensaje .= '<p>';
			$mensaje .= $mens['texto'].'</p>';
			$mensaje .= '</div><br/>';
		}else{
		//Recibidos
			$mensaje .= '<span class="letrita">'.$mens['Remite'].' '.$mens['Remite_apellido1'].' '.$mens['Remite_apellido2'].': </span>';
			$mensaje .= '<p>';
			$mensaje .= $mens['texto'].'</p>';
			$mensaje .= '</div><br/>';
		}
		
	$ultimo = $mens['id_ump'];
	echo "<script>";
	echo "$('#ultimoMensaje').val(".$ultimo.");";
	echo "</script>";
	}
	
	echo $mensaje;
    ?>