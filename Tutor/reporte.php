<?
include('conexion.php');//CONEXION A LA BD

$fecha1=$_POST['fecha1'];
$fecha2=$_POST['fecha2'];

if(isset($_POST['generar_reporte']))
{
	// NOMBRE DEL ARCHIVO Y CHARSET
	header('Content-Type:text/csv; charset=latin1');
	header('Content-Disposition: attachment; filename="Reporte_Fechas_Ingreso.csv"');

	// SALIDA DEL ARCHIVO
	$salida=fopen('php://output', 'w');
	// ENCABEZADOS
	fputcsv($salida, array('Id Alumno', 'Nombre', 'Carrera', 'Grupo', 'Fecha de Ingreso'));
	// QUERY PARA CREAR EL REPORTE
	$reporteCsv=$conexion->query("SELECT *  FROM alumnos where fecha_ingreso BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_alumno");
	while($filaR= $reporteCsv->fetch_assoc())
		fputcsv($salida, array($filaR['id_alumno'], 
								$filaR['nombre'],
								$filaR['carrera'],
								$filaR['grupo'],
								$filaR['fecha_ingreso']));

}

?>