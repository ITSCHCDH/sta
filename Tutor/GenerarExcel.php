<?php
	session_start();

	if (isset($_SESSION['usuario'])) {
		if ($_SESSION['usuario']['Tipo'] != "Tutor") {

			if ($_SESSION['usuario']['Tipo']=="Admin"){
				header('location: '.$_SERVER["DOCUMENT_ROOT"]."/Admin/");
			}elseif ($_SESSION['usuario']['Tipo']=="Profe"){
				header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Profe/');
			}elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
				header('location: '.$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
			}elseif ($_SESSION['usuario']['Tipo']=="Dire"){
				header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Direc/');
			}elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
				header('location: '.$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
			}elseif ($_SESSION['usuario']['Tipo']=="Medic"){
				header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Medico/');
			}elseif ($_SESSION['usuario']['Tipo']=="Alu"){
				header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Alumno/');
			}
		}
	}else{
		header('location:/sta/php/CerrarSesion.php');
	}
	require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";
	$conectar = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	if ($conectar ->connect_errno) {
		echo "Fallo al conectar a MySQL: ". $conectar->connect_error;
		return;
	}
	if (!$conectar->set_charset("utf8")) {
		printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		exit();
	}
?>
<!DOCTYPE html>
<html lang="es-es">
	<head>
		<meta charset="utf-8">
		<title>Contacto</title>
	<head>
	<body>
		<?php
		// Definimos el archivo exportado
		$arquivo = 'msgcontactos.xls';

		// Crear la tabla HTML
		$html = '';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td colspan="5">Plantilla de mensajes de contacto</tr>';
		$html .= '</tr>';


		$html .= '<tr>';
		$html .= '<td><b>Numero de control</b></td>';
		$html .= '<td><b>Nombre</b></td>';
		$html .= '<td><b>E-mail</b></td>';
		$html .= '<td><b>Telefono</b></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '<td></td>';
		$html .= '</tr>';

		//Seleccionar todos los elementos de la tabla
		$result_msg_contatos = "SELECT
                grupos_tutorias.gpo_clave,
                grupos_tutorias_complemento.alu_no_control,
                grupos_tutorias.gpo_sem,
								(SELECT dp_nombre FROM alumnos_caracterizacion WHERE se_no_control = grupos_tutorias_complemento.alu_no_control) as Nombre,
								(SELECT dp_ap_paterno FROM alumnos_caracterizacion WHERE se_no_control = grupos_tutorias_complemento.alu_no_control) as APat,
								(SELECT dp_ap_materno FROM alumnos_caracterizacion WHERE se_no_control = grupos_tutorias_complemento.alu_no_control) as AMat,
								(SELECT dp_email FROM alumnos_caracterizacion WHERE se_no_control = grupos_tutorias_complemento.alu_no_control) as Mail,
								(SELECT dp_tel FROM alumnos_caracterizacion WHERE se_no_control = grupos_tutorias_complemento.alu_no_control) as Tel
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                WHERE
                grupos_tutorias.gpo_nombre = '".$_GET['gpo']."'";
		$resultado_msg_contatos = mysqli_query($conectar , $result_msg_contatos);

		while($row_msg_contatos = mysqli_fetch_assoc($resultado_msg_contatos)){
			$html .= '<tr>';
			$html .= '<td>'.$row_msg_contatos["alu_no_control"].'</td>';
			$html .= '<td>'.$row_msg_contatos["Nombre"].' '.$row_msg_contatos["APat"].' '.$row_msg_contatos["AMat"].'</td>';
			$html .= '<td>'.$row_msg_contatos["Mail"].'</td>';
			$html .= '<td>'.$row_msg_contatos["Tel"].'</td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '<td></td>';
			$html .= '</tr>';
			;
		}
		// Configuración en la cabecera
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generado Data" );
		// Envia contenido al archivo
		echo $html;
		exit; ?>
	</body>
</html>
