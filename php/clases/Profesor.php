<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";

class Profesor{

    protected $_db;
    protected $_db2;
    protected $_sql;

    public function __construct(){
        $this->_db = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
        if ($this->_db ->connect_errno) {
            echo "Fallo al conectar a MySQL: ". $this->_db->connect_error;
            return;
        }
        if (!$this->_db->set_charset("utf8")) {
            printf("Error cargando el conjunto de caracteres utf8: %s\n", $this->_db->error);
            exit();
        }

        $connectionInfo = array( "Database"=>MS_DB_NAME, "UID"=>MS_DB_USER, "PWD"=>MS_DB_PASS);
        $this->_db2= sqlsrv_connect( MS_DB_SERVER, $connectionInfo);
        if( !$this->_db2 ){
            echo "Conexión no se pudo establecer.<br />";
            die( print_r( sqlsrv_errors(), true));
        }
    }

    function consCalMaterias($Mat,$Pro,$NomMat){
        $Materia="";
        $Control=array();
        $Alumnos=array();
        $U1=array();
        $U2=array();
        $U3=array();
        $U4=array();
        $U5=array();
        $U6=array();
        $U7=array();
        $U8=array();
        $U9=array();
        $U10=array();
        $U11=array();
        $U12=array();
        $U13=array();

        $this->_sql="SELECT
                Alumnos.alu_NumControl as 'Control',
            (RTRIM(Alumnos.alu_ApePaterno) + ' ' + RTRIM(Alumnos.alu_ApeMaterno) + ' ' + RTRIM(Alumnos.alu_Nombre)) as 'Alumno',
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=1) AS U1,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=2) AS U2,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=3) AS U3,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=4) AS U4,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=5) AS U5,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=6) AS U6,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=7) AS U7,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=8) AS U8,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=9) AS U9,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=10) AS U10,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=11) AS U11,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=12) AS U12,
            (select lsc_Calificacion from ListasSemestreCom where lse_clave=ListasSemestre.lse_Clave and lsc_NumUnidad=13) AS U13
            FROM GruposSemestre INNER JOIN ListasSemestre ON GruposSemestre.gse_Clave = ListasSemestre.gse_Clave
                    INNER JOIN Alumnos ON ListasSemestre.alu_NumControl COLLATE SQL_Latin1_General_CP1_CI_AS = Alumnos.alu_NumControl
            WHERE GruposSemestre.gse_Clave = $Mat
                    ORDER BY Alumnos.alu_ApePaterno, Alumnos.alu_ApeMaterno, Alumnos.alu_Nombre ASC";

        $stmt = sqlsrv_query ( $this->_db2, $this->_sql)
          or die('Query failed: '.$this->_sql);

        if($stmt==false){
            echo '<option selected="selected" disabled="disabled">No Hay Materias</option>';
        }
        while ($row = sqlsrv_fetch_array($stmt)){
            array_push($Control,$row['Control']);
            array_push($Alumnos,$row['Alumno']);
            array_push($U1,$row['U1']);
            array_push($U2,$row['U2']);
            array_push($U3,$row['U3']);
            array_push($U4,$row['U4']);
            array_push($U5,$row['U5']);
            array_push($U6,$row['U6']);
            array_push($U7,$row['U7']);
            array_push($U8,$row['U8']);
            array_push($U9,$row['U9']);
            array_push($U10,$row['U10']);
            array_push($U11,$row['U11']);
            array_push($U12,$row['U12']);
            array_push($U13,$row['U13']);
        }
        $Cal = array($Control,$Alumnos,$U1,$U2,$U3,$U4,$U5,$U6,$U7,$U8,$U9,$U9,$U10,$U11,$U12,$U13);
        $con=0;
        $Can=0;
        for ($i=2; $i <count($Cal) ; $i++) {
            if ($Cal[$i][1]!==null) {
                $con=$con+1;
            }elseif ($Cal[$i+1][1]!==null) {
                $con=$con+1;
            }else {
                $Can=$i+1;
                break;
            }
        }
        #guardar la Materia
        $ClaveMat=$this->guardar_materia($Mat,$Pro,$NomMat);
        #Aqui empieza la tabs de parciales
        echo "<ul class='nav nav-tabs nav-justified'>";
        for ($i=1; $i <=$con ; $i++) {
            if ($i>1) {
                echo "<li><a href='#Par$i' data-toggle='tab'>Parcial $i</a></li>";
            }
            else{
                echo "<li class='active'><a href='#Par$i' data-toggle='tab'>Parcial $i</a></li>";
            }
        }
        echo "</ul>
            <div class='tab-content'>";
        #aqui empieza elllenado de tabs-panels
        for ($i=1; $i <=$con ; $i++){
            if ($i>1) {echo "<div class='tab-pane fade' id='Par$i'>";}
            else {echo "<div class='tab-pane fade in active' id='Par$i'>";}
            echo "  <div class='content-box-large'>
                <div class='panel-heading'>
                    <div class='panel-title'><h3></h3></div>
                </div>
                <div class='panel-body'>
                    <div class='panel panel-primary'>
                        <div class='panel-heading' style='height:30px;'>
                            <h3 class='panel-title'>Calificaciones Alumnos</h3>
                        </div>
                        <div class='panel-body'>
                            <table class='display mitabla' id='mitabla'>
                                <thead>
                                    <tr>
                                        <th style='width:10%'>NumControl</th>
                                        <th style='width:30%'>Nombre</th>
                                        <th style='width:10%'>Parcial $i</th>
                                        <th style='width:20%'>Motivos de reprobacion</th>
                                        <th style='width:30%'>Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>";
            for ($j=0; $j <count($Control) ; $j++) {
                if ($j%2===1) {echo "<tr>";}
                else {echo"<tr class='active'>";}
                echo "<td style='width:10%'>".utf8_encode($Cal[0][$j]). "<input id='con' name='con' type='hidden' value='".$Cal[0][$j]."' /><input id='parsial' name='parsial' type='hidden' value='".$i."' /></td>";
                echo "<td style='width:30%'>".utf8_encode($Cal[1][$j])."</td>";
                if (is_numeric($Cal[$i+1][$j])) {
                    if ($Cal[$i+1][$j]<=69) {
                        $this->insertMot($ClaveMat,utf8_encode($Cal[0][$j]),$Mat,$i,$Cal[$i+1][$j]);
                    }
                    else {
                        echo "<td style='width:10%'>".utf8_encode($Cal[$i+1][$j])."</td>
                              <td style='width:20%'>
                                <select class='motRep' name='selectRepro".$i."' style='visibility:hidden'>
                                    <option selected='selected' disabled='disabled'>Motivos de reprobacion</option>
                                    <option value='value1'>Responsabilidad Alumno</option>
                                    <option value='value2'>Inasistencia</option>
                                    <option value='value3'>Complejidad Materia</option>
                                    <option value='value4'>Otro</option>
                                </select>
                            </td>
                            <td style='width:30%'><input type='text' name='Comentarios".$i."' style='visibility:hidden'></td>";
                    }
                }
                else {
                    echo "<td>".utf8_encode($Cal[$i+1][$j])."</td>";
                }
                echo "</tr>";
            }
            echo "      </tr>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
        }
        echo "</div>
            <input type='hidden' name='numpar' value='$con'>";
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $(\'.mitabla\').DataTable({
                "order": [[1, "asc"]],
                "language":{
                    "lengthMenu": "Mostrar _MENU_ registros por pagina",
                    "info": "Mostrando pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros disponibles",
                        "infoFiltered": "(filtrada de _MAX_ registros)",
                        "loadingRecords": "Cargando...",
                        "processing":     "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords":    "No se encontraron registros coincidentes",
                        "paginate": {
                            "next":       "Siguiente",
                            "previous":   "Anterior"
                        },
                    },
                paging: false,
            });
            });
        </script>';
    }

    function seleccionarMateria(){
        $this->_sql="SELECT
            dbo.GruposSemestre.gse_Clave,
            dbo.Reticula.ret_NomCompleto,
            dbo.GruposSemestre.gse_Observaciones

            FROM
            dbo.GruposSemestre
            INNER JOIN dbo.Catedraticos ON dbo.GruposSemestre.cat_Clave = dbo.Catedraticos.cat_Clave
            INNER JOIN dbo.Reticula ON dbo.GruposSemestre.ret_Clave = dbo.Reticula.ret_Clave
            WHERE
            dbo.Catedraticos.cat_Clave = '".$_SESSION['usuario']['Cat']."'";
        $stmt=sqlsrv_query ( $this->_db2, $this->_sql);

        if($stmt!=false){
            echo '<option selected="selected" disabled="disabled">Elija Su Materia</option>';
            while($row = sqlsrv_fetch_array($stmt)){
                echo "<option value='".$row['gse_Clave']."'>".$row['ret_NomCompleto']."_".$row['gse_Observaciones']."</option>";
            }
        }
        else{
            echo '<option selected="selected" disabled="disabled">No Hay Materias</option>';
        }
    }

    function guardar_materia($Mat,$Pro,$NomMat){
        $mate = explode("_", $NomMat);
        $qres2="SELECT grupo_profesor.gpro_clave FROM grupo_profesor WHERE grupo_profesor.cat_clave = $Pro AND grupo_profesor.gpro_id_materia  = $Mat";
        if(!$resqm = $this->_db->query($qres2)){
            echo "Error: La ejecución de la consulta falló debido a: \n";
            echo "Query: " . $qres2 . "\n";
            echo "Errno: " . $this->_db->errno . "\n";
            echo "Error: " . $this->_db->error . "\n";
            exit;
        }
        if ($resqm->num_rows > 0) {
            $row = $resqm->fetch_assoc();
            return $row['gpro_clave'];
        }else {
            $qres3 = "INSERT INTO grupo_profesor (cat_clave,gpro_id_materia,gpro_Nombre_Mat,gpro_Grupo)
                VALUES ($Pro, $Mat, '$mate[0]', '$mate[1]' )";
            if(!$this->_db->query($qres3) ){
                return"Error: La ejecución de la consulta falló debido a: \n".
                    "Query: " . $qres3 . "\n".
                    "Errno: " . $this->_db->errno . "\n".
                    "Error: " . $this->_db->error . "\n";
            }
            else {
                $this->guardar_materia($Mat,$Pro,$NomMat);
            }
        }
    }

    function insertMot($Cla,$NCon,$Mat,$NPar,$Cali) {
        $NCon=trim($NCon," ");
        $q1="SELECT * FROM grupo_profesor_calificaciones WHERE grupo_profesor_calificaciones.se_no_control = '$NCon' AND grupo_profesor_calificaciones.gpro_cal_parcial= $NPar AND grupo_profesor_calificaciones.gpro_clave = $Mat AND grupo_profesor_calificaciones.gpro_cal_calificacion =$Cali";
        if(!$resqc = $this->_db->query($q1)){
            echo "Query: " . $q1. "\n".
            "Errno: " . $this->_db->errno . "\n".
            "Error: " . $this->_db->error . "\n";
            exit;
        }
        if ($resqc->num_rows > 0) {
            $row = $resqc->fetch_assoc();
            echo "<td style='width:10%' class='warning'>".$Cali."</td>
                  <td style='width:20%' class='warning'>";
            echo '<select class="motRep" name="selectRepro'.$NPar.'" onchange="mtvRepChange(\''.$NCon.'\','.$NPar.',this.value,$(\'#motcom'.$NCon.$NPar.'\').val());">';
            echo "       <option disabled='disabled' selected>Motivos de reprobacion</option>";
                    $rep = array('valor1' => array('value' => "1", 'text' =>"Responsabilidad Alumno"),
                                 'valor2' => array('value' => "2", 'text' =>"Inasistencia"),
                                 'valor3' => array('value' => "3", 'text' =>"Complejidad Materia"),
                                 'valor4' => array('value' => "4", 'text' =>"Otro")
                    );
                    foreach ($rep as $value) {
                        if ($value['value']==$row['mtv_reprovacion']) {
                            echo "<option selected='selected' value='".$value['value']."'>".$value['text']."</option>";
                        }else {
                            echo "<option value='".$value['value']."'>".$value['text']."</option>";
                        }
                    }
                    echo "</select>
                </td>
                <td style='width:30%' class='warning'><input style='width: 100%;' readonly id='motcom".$NCon.$NPar."' type='text' name='Comentarios".$NPar."' value='".$row['gpro_cal_otro']."'></th>";
        }
        else {
            $qi1 = "INSERT INTO grupo_profesor_calificaciones (se_no_control, gpro_clave, gpro_cal_parcial, gpro_cal_calificacion, mtv_reprovacion, gpro_cal_otro)
                    VALUES ('$NCon', '$Mat', '$NPar', '$Cali', null, '')";
            if(!$this->_db->query($qi1) ){
                echo "Query: " . $qi1 . "\n".
                    "Errno: " . $this->_db->errno . "\n".
                    "Error: " . $this->_db->error . "\n";
                exit;
            }
            else {
                echo "<td style='width: 10%;' class='warning'>".$Cali."</td>";
                echo "<td style='width: 20%;' class='warning'>
                        <select class='motRep' name='selectRepro".$NPar."[]' onchange=\"mtvRepChange('".$NCon."',".$NPar.",this.value,$('#motcom".$NCon."').val());\">
                            <option selected='selected' disabled='disabled'>Motivos de reprobacion</option>
                            <option value='1'>Responsabilidad Alumno</option>
                            <option value='2'>Inasistencia</option>
                            <option value='3'>Complejidad Materia</option>
                            <option value='4'>Otro</option>
                        </select>
                    </td>
                    <td style='width: 30%;' class='warning'><input style='width: 100%;' readonly type='text' name='Comentarios".$NPar."[]'></td>";
            }
        }
    }

    function changeMotRep($mat,$par,$con,$motrep,$motcom){
        $this->_sql="UPDATE `sta`.`grupo_profesor_calificaciones` SET `mtv_reprovacion` = $motrep, `gpro_cal_otro` = '$motcom'
        WHERE `gpro_clave` = $mat AND `se_no_control` = '$con' AND `gpro_cal_parcial` = '$par' ";

        if(!$resultado=$this->_db->query($this->_sql)){
            echo json_encode(['error' => TRUE, 'mensaje'=> "Hubo un problema al actualizar el motivo de reprovación" ]);
        }else {
            echo json_encode(['error' => FALSE, 'mensaje'=> "Se cambio el motivo de reprovacion" ]);;
        }
    }
}
?>
