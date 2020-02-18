<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";
    class Grupo{

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

        function listar_alumnos($grup){

            echo $_SESSION['usuario']['Tipo']=='Tutor'?'<div class="row">
                   <div class="col-sm-6 col-md-4 col-sm-offset-6 col-md-offset-8">
                         <div class="btn-group btn-group-justified btn-group-sm pull-right" role="group" <div class="btn-group btn-group-justified btn-group-sm pull-right" role="group" style="margin-bottom: 30px;">
                        <div class="btn-group" role="group">
                            <button type="button" data-toggle="modal" data-target="#aluAlt" class="btn btn-primary center-block">Agregar Alumno</button>
                        </div>
                    </div>
                   </div>
                </div>':'';
            echo '<br>'.
                "<table class='display table' id='mitabla'>
                    <thead>
                        <tr>
                            <th style='width:15%;' >NumControl</th>
                            <th style='' >Nombre</th>
                            <th style='' >Caracterizado</th>
                            <th style='width:10%;' >Semestre</th>
                            <th style='width:25%;' >Indicador</th>
                            <th  style='width:15%;'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>";
            $this->_sql="SELECT
                grupos_tutorias.gpo_clave,
                grupos_tutorias_complemento.alu_no_control,
                grupos_tutorias.gpo_sem
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                WHERE
                grupos_tutorias.gpo_nombre = '$grup'";

            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";

                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;
            }
            if ($resultado->num_rows > 0) {
                $con=1;
                while ($row = $resultado->fetch_assoc() ){
                    if ($mres=$this->_db->query("SELECT alumnos_caracterizacion.dp_nombre,alumnos_caracterizacion.dp_ap_paterno, alumnos_caracterizacion.dp_ap_materno, alumnos_caracterizacion.dp_carrera, alumnos_caracterizacion.tut_seg FROM alumnos_caracterizacion WHERE alumnos_caracterizacion.se_no_control ='". $row['alu_no_control']."'")){
                        if ($mres->num_rows >0) {
                            $mrow = $mres->fetch_assoc();
                            echo"<tr>
                                <td>".$row['alu_no_control']."</td>
                                <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."&Grupo=".$grup."'>".$mrow['dp_ap_paterno']." ".$mrow['dp_ap_materno']." ".$mrow['dp_nombre']."</a></td>
                                <td>Si</td>
                                <td>".$this->aluSem($row['alu_no_control'])."</td>
                                <td>";
                                $Cali=$this->consultarCalificacionGnr($row['alu_no_control']);
                                if ($Cali>=90 && $Cali<=100) {
                                    echo "<div class='progress'>
                                        <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                            $Cali
                                        </div>
                                    </div";
                                }
                                elseif ($Cali>=80 && $Cali<89) {
                                    echo "<div class='progress'>
                                      <div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                        $Cali
                                      </div>
                                    </div";
                                }
                                elseif ($Cali>=70 && $Cali<79) {
                                    echo "<div class='progress'>
                                      <div class='progress-bar progress-bar-warning' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                        $Cali
                                      </div>
                                    </div";
                                }
                                elseif ($Cali<70) {
                                    echo "<div class='progress'>
                                          <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                            $Cali
                                          </div>
                                        </div";
                                }

                                echo"</td>
                                <td>
                                    <div class='btn-group btn-group-justified' role=\"group\" aria-label=\"group button\">
                                        <button style='width: 49%;' type='button' class='btn btn-danger' onClick=\"EliminarAlu('".$row['alu_no_control']."');\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></button>
                                        <button style='width: 49%;' type='button' class='btn btn-success' onclick=\"tutSegi('".$row['alu_no_control']."','".$row['alu_no_control']."')\"><span class='glyphicon glyphicon-eye-open' aria-hidden=\"true\"></span></button>
                                    </div>
                                 </td>
                            </tr>";
                        }
                        else {
                            if($stmt = sqlsrv_query($this->_db2, "SELECT a.alu_NumControl AS NoControl, a.alu_Nombre AS Nombre, a.alu_ApePaterno AS ApePaterno, a.alu_ApeMaterno AS ApeMaterno, a.alu_SemestreAct AS Sem, c.car_Nombre AS Carrera FROM dbo.Alumnos AS a JOIN dbo.Carreras AS c ON a.car_Clave = c.car_Clave WHERE A.alu_NumControl ='" .$row['alu_no_control']."'") ) {
                                $srow = sqlsrv_fetch_array($stmt);
                                echo
                                    "<tr>
                                        <td>".$row['alu_no_control']."</td>
                                        <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."&Grupo=".$grup."'>".$srow['ApePaterno']." ".$srow['ApeMaterno']." ".$srow['Nombre']."</a></td>
                                        <td>No</td>
                                        <td>".$this->aluSem($row['alu_no_control'])."</td>
                                        <td>";
                                        $Cali=$this->consultarCalificacionGnr($row['alu_no_control']);
                                        if ($Cali>=90 && $Cali<=100) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        } elseif ($Cali>=80 && $Cali<89) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        } elseif ($Cali>=70 && $Cali<79) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-warning' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        } elseif ($Cali<70) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        }

                                echo"</td>
                                        <td>
                                        <div class='btn-group btn-group-justified' role=\"group\" aria-label=\"group button\">
                                            <button style='width: 49%;' type='button' class='btn btn-danger' onClick=\"EliminarAlu('".$row['alu_no_control']."');\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></button>
                                            <button style='width: 49%;' type='button' class='btn btn-success' onclick=\"tutSegi('".$row['alu_no_control']."','')\"><span class='glyphicon glyphicon-eye-open' aria-hidden=\"true\"></span></button>
                                        </div>
                                        </td>
                                    </tr>";
                            }
                        }
                    }$con+=1;
                }
            }
            echo"</tbody>
                </table>";
        }

        function listar_alumnosGE($car, $gen){

            echo '<br>'.
                "<table class='display table' id='mitabla'>
                    <thead>
                        <tr>
                            <th style='width:15%;' >NumControl</th>
                            <th style='' >Nombre</th>
                            <th style='' >Caracterizado</th>
                            <th style='width:10%;' >Semestre</th>
                            <th style='width:25%;' >Indicador</th>
                            <th style='width:15%;'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>";

            $this->_sql = "SELECT
                dbo.Alumnos.alu_NumControl
                FROM dbo.Alumnos
                WHERE	dbo.Alumnos.alu_StatusAct = 'VI'
                AND dbo.Alumnos.car_Clave = ".$car."
                AND dbo.Alumnos.alu_AnioIngreso =".$gen;
            $stmts = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

                $alumnos= array();
            if ($stmts != false) {
                $con=1;
                while ($row = sqlsrv_fetch_array($stmts)) {
                    if ($mres=$this->_db->query("SELECT alumnos_caracterizacion.dp_nombre,alumnos_caracterizacion.dp_ap_paterno, alumnos_caracterizacion.dp_ap_materno, alumnos_caracterizacion.dp_carrera, alumnos_caracterizacion.tut_seg FROM alumnos_caracterizacion WHERE alumnos_caracterizacion.se_no_control ='". $row['alu_NumControl']."'")){
                        if ($mres->num_rows >0) {
                            $mrow = $mres->fetch_assoc();
                            echo
                           "<tr>
                                <td>".$row['alu_NumControl']."</td>
                                <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_NumControl']."'>".$mrow['dp_ap_paterno']." ".$mrow['dp_ap_materno']." ".$mrow['dp_nombre']."</a></td>
                                <td>Si</td>
                                <td>".$this->aluSem($row['alu_NumControl'])."</td>
                                <td>";
                                $Cali=$this->consultarCalificacionGnr($row['alu_NumControl']);
                                if ($Cali>=90 && $Cali<=100) {
                                    echo "<div class='progress'>
                                      <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                        $Cali
                                      </div>
                                    </div";
                                }
                                elseif ($Cali>=80 && $Cali<=89) {
                                    echo "<div class='progress'>
                                      <div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                        $Cali
                                      </div>
                                    </div";
                                }
                                elseif ($Cali>=70 && $Cali<=79) {
                                    echo "<div class='progress'>
                                      <div class='progress-bar progress-bar-warning' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                        $Cali
                                      </div>
                                    </div";
                                }
                                elseif ($Cali<70) {
                                    echo "<div class='progress'>
                                          <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".($Cali<10?'100':$Cali)."' aria-valuemin='0' aria-valuemax='100' style='width: ".($Cali<10?'100':$Cali)."%'>
                                            $Cali
                                          </div>
                                        </div";
                                }

                            echo"</td>
                                 <td>".(!is_null($mrow['tut_seg'])?$mrow['tut_seg']:'')."</td>
                                </tr>";
                        }
                        else {
                            if($stmt = sqlsrv_query($this->_db2, "SELECT a.alu_NumControl AS NoControl, a.alu_Nombre AS Nombre, a.alu_ApePaterno AS ApePaterno, a.alu_ApeMaterno AS ApeMaterno, a.alu_SemestreAct AS Sem, c.car_Nombre AS Carrera FROM dbo.Alumnos AS a JOIN dbo.Carreras AS c ON a.car_Clave = c.car_Clave WHERE A.alu_NumControl ='" .$row['alu_NumControl']."'") ) {
                                $srow = sqlsrv_fetch_array($stmt);
                                echo
                                   "<tr>
                                        <td>".$row['alu_NumControl']."</td>
                                        <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_NumControl']."&Grupo='>".$srow['ApePaterno']." ".$srow['ApeMaterno']." ".$srow['Nombre']."</a></td>
                                        <td>No</td>
                                        <td>".$this->aluSem($row['alu_NumControl'])."</td>
                                        <td>";
                                        $Cali=$this->consultarCalificacionGnr($row['alu_NumControl']);
                                        if ($Cali>=90 && $Cali<=100) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        } elseif ($Cali>=80 && $Cali<=89) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        } elseif ($Cali>=70 && $Cali<=79) {
                                            echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-warning' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                                $Cali
                                            </div>
                                            </div";
                                        } elseif ($Cali<70) {
                                            echo "<div class='progress'>
                                                  <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".($Cali<10?'100':$Cali)."' aria-valuemin='0' aria-valuemax='100' style='width: ".($Cali<10?'100':$Cali)."%'>
                                                    $Cali
                                                  </div>
                                                </div";
                                        }

                                echo"</td>
                                        <td></td>
                                      </tr>";
                            }
                        }
                    }$con+=1;
                }
            }
            echo"</tbody>
                </table>";
        }

        function listar_alumnos2(){
            echo "<table class='display table' id='mitabla'>
                    <thead>
                        <tr>
                            <th style='width:15%;' >NumControl</th>
                            <th style='' >Nombre</th>
                            <th style='' >Carrera</th>
                            <th style='width:10%;' >Grupo</th>
                            <th style='width:25%;' >Estado</th>
                        </tr>
                    </thead>
                    <tbody>";
            $this->_sql="SELECT
                grupos_tutorias.gpo_clave,
                grupos_tutorias_complemento.alu_no_control,
                grupos_tutorias.gpo_sem,
                grupos_tutorias.gpo_nombre,
                carreras.car_Nombre
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                INNER JOIN carreras ON grupos_tutorias.car_clave = carreras.car_Clave
                WHERE
                grupos_tutorias.cat_clave = (SELECT usuario.cat_clave FROM usuario WHERE usuario.u_Clave=".$_SESSION['usuario']['Clave'].")";

            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                //echo "Lo sentimos, este sitio web está experimentando problemas.";

                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                //echo "Error: La ejecución de la consulta falló debido a: \n";
                //echo "Query: " . $this->_sql . "\n";
                //echo "Errno: " . $this->_db->errno . "\n";
                //echo "Error: " . $this->_db->error . "\n";
                //exit;
            }
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc() ){

                    if($stmt = sqlsrv_query($this->_db2, "SELECT a.alu_NumControl AS NoControl, a.alu_Nombre AS Nombre, a.alu_ApePaterno AS ApePaterno, a.alu_ApeMaterno AS ApeMaterno, a.alu_SemestreAct AS Sem, c.car_Nombre AS Carrera FROM dbo.Alumnos AS a JOIN dbo.Carreras AS c ON a.car_Clave = c.car_Clave WHERE A.alu_NumControl ='".$row['alu_no_control']."'") ) {
                        $srow = sqlsrv_fetch_array($stmt);
                        $Cali=$this->consultarCalificacionGnr($row['alu_no_control']);
                        if ($Cali<70) {
                            echo "<tr>
                                <td>".$row['alu_no_control']."</td>
                                <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."&Grupo=".$row['gpo_nombre']."'>".$srow['ApePaterno']." ".$srow['ApeMaterno']." ".$srow['Nombre']."</a></td>
                                <td>".$row['car_Nombre']."</td>
                                <td>".$row['gpo_nombre']."</td>
                                <td>
                                    <div class='progress'>
                                        <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$Cali."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Cali."%'>
                                        $Cali
                                        </div>
                                    </div
                                </td>
                            </tr>";
                        }
                    }
                }
            }
            echo"</tbody>
                </table>";
        }

        function listar_alumnosGE2($car, $gen){
            $this->_sql= "SELECT   [dbo].[alumnos].[alu_numcontrol],
                    [dbo].[alumnos].[alu_nombre],
                    [dbo].[alumnos].[alu_apepaterno],
                    [dbo].[alumnos].[alu_apematerno],
                    [dbo].[alumnos].[alu_creditosacum],
                    [dbo].[alumnos].[alu_statusact],
                    [dbo].[alumnos].[alu_anioingreso],
                    (SELECT car_nombre FROM dbo.carreras WHERE car_clave = dbo.alumnos.car_clave) AS Carrera,
                    (SELECT AVG(lse_Calificacion) FROM dbo.ListasSemestre WHERE alu_NumControl= [dbo].[alumnos].[alu_numcontrol] GROUP BY dbo.ListasSemestre.alu_NumControl) AS Prom
                FROM     [dbo].[alumnos]
                WHERE dbo.Alumnos.alu_StatusAct = 'VI'
                    AND dbo.Alumnos.car_Clave = $car
                    AND dbo.Alumnos.alu_AnioIngreso = $gen
                    AND (SELECT AVG(lse_Calificacion) FROM dbo.ListasSemestre WHERE alu_NumControl= [dbo].[alumnos].[alu_numcontrol] GROUP BY dbo.ListasSemestre.alu_NumControl) < 70";

            echo "<table class='display table' id='mitabla'>
                    <thead>
                        <tr>
                            <th style='width:15%;' >NumControl</th>
                            <th style='' >Nombre</th>
                            <th style='' >Creditos acumulados</th>
                            <th style='width:10%;' >Grupo</th>
                            <th style='width:25%;' >Estado</th>
                        </tr>
                    </thead>
                    <tbody>";

            if($stmt = sqlsrv_query( $this->_db2 , $this->_sql)){
                while( $row = sqlsrv_fetch_array($stmt) ) {
                    echo "<tr>
                        <td>".$row['alu_numcontrol']."</td>
                        <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_numcontrol']."&Grupo=".$this->ConsultarGrupo($row['alu_numcontrol'])."'>".$row['alu_apepaterno']." ".$row['alu_apematerno']." ".$row['alu_nombre']."</a></td>
                        <td>".$row['alu_creditosacum']."</td>
                        <td>".$this->ConsultarGrupo($row['alu_numcontrol'])."</td>
                        <td>
                            <div class='progress'>
                                <div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".($row['Prom']<10?'100':$row['Prom'])."' aria-valuemin='0' aria-valuemax='100' style='width: ".($row['Prom']<10?'100':$row['Prom'])."%'>
                                    ".$row['Prom']."
                                </div>
                            </div
                        </td>
                    </tr>";
                }
            }
            echo"</tbody>
                </table>";
        }

        function listar_alumnosFormatos($grup){
            echo
                "<table class='display table' id='mitabla'>
                    <thead>
                        <tr>
                            <th style='width:3%;' >#</th>
                            <th style='width:15%;' >NumControl</th>
                            <th style='' >Nombre</th>
                            <th style='' >Caracterizado</th>
                            <th style='width:20%;' >Ficha PDF</th>
                        </tr>
                    </thead>
                    <tbody>";
            $this->_sql="SELECT
                grupos_tutorias.gpo_clave,
                grupos_tutorias_complemento.alu_no_control,
                grupos_tutorias.gpo_sem
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                WHERE
                grupos_tutorias.gpo_nombre = '$grup'";

            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";

                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;
            }
            if ($resultado->num_rows > 0) {
                $con=1;
                while ($row = $resultado->fetch_assoc() ){
                    if ($mres=$this->_db->query("SELECT alumnos_caracterizacion.dp_nombre,alumnos_caracterizacion.dp_ap_paterno, alumnos_caracterizacion.dp_ap_materno, alumnos_caracterizacion.dp_carrera, alumnos_caracterizacion.al_pdf, alumnos_caracterizacion.al_pdf_valido FROM alumnos_caracterizacion WHERE alumnos_caracterizacion.se_no_control ='". $row['alu_no_control']."'")){
                        if ($mres->num_rows >0) {
                            $mrow = $mres->fetch_assoc();
                            echo
                           "<tr>
                                <td>".$con."</td>
                                <td>".$row['alu_no_control']."</td>
                                <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."&Grupo=".$grup."'>".$mrow['dp_ap_paterno']." ".$mrow['dp_ap_materno']." ".$mrow['dp_nombre']."</a></td>
                                <td>Si</td>
                                <td>".(isset($mrow['al_pdf'])?'<a class="btn btn-primary view-pdf" download="'.$row['alu_no_control'].'.pdf" href="/sta/pdf/'.$mrow['al_pdf'].'">Ver PDF</a>':'')."   </td>

                            </tr>";
                        }
                        else {
                            if($stmt = sqlsrv_query($this->_db2, "SELECT a.alu_NumControl AS NoControl, a.alu_Nombre AS Nombre, a.alu_ApePaterno AS ApePaterno, a.alu_ApeMaterno AS ApeMaterno, a.alu_SemestreAct AS Sem, c.car_Nombre AS Carrera FROM dbo.Alumnos AS a JOIN dbo.Carreras AS c ON a.car_Clave = c.car_Clave WHERE A.alu_NumControl ='" .$row['alu_no_control']."'") ) {
                                $srow = sqlsrv_fetch_array($stmt);
                                echo
                                   "<tr>
                                        <td>".$con."</td>
                                        <td>".$row['alu_no_control']."</td>
                                        <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."&Grupo=".$grup."'>".$srow['ApePaterno']." ".$srow['ApeMaterno']." ".$srow['Nombre']."</a></td>
                                        <td>No</td>
                                        <td></td>
                                    </tr>";
                            }
                        }
                    }$con+=1;
                }
            }
            echo"</tbody>
                </table>";
        }

        function ConsultarGrupos(){
            $this->_sql="SELECT grupos_tutorias.gpo_nombre, grupos_tutorias.gpo_identificador FROM grupos_tutorias WHERE cat_clave=".$_SESSION['usuario']['Clave'];
            #$this->_sql="SELECT grupos_tutorias.gpo_nombre FROM grupos_tutorias WHERE cat_clave=1";

            if(!$resultado=$this->_db->query($this->_sql)){
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";

                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;
            }
            if($resultado->num_rows > 0){
                echo '<option selected="selected" disabled="disabled">Grupos</option>';
                while ($row=$resultado->fetch_assoc()) {
                    echo "<option value='".$row['gpo_nombre']."'>".$row['gpo_nombre']."</option>";
                }
            }
            else {
                echo '<option selected="selected" disabled="disabled">No Hay Grupos</option>';
            }
        }

        public function ConsultarGrupos2($car){
            $this->_sql="SELECT grupos_tutorias.gpo_nombre FROM grupos_tutorias WHERE grupos_tutorias.car_clave='".$car."'";

            if(!$resultado=$this->_db->query($this->_sql)){
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";

                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;
            }
            if($resultado->num_rows > 0){
                echo '<option selected="selected" disabled="disabled">Grupos</option>';
                while ($row=$resultado->fetch_assoc()) {
                    echo "<option value='".$row['gpo_nombre']."'>".$row['gpo_nombre']."</option>";
                }
            }
            else {
                echo '<option selected="selected" disabled="disabled">No Hay Grupos</option>';
            }
        }

        function getIndGrupo($grupo){

            $this->_sql="SELECT
                grupos_tutorias.gpo_identificador
                FROM
                grupos_tutorias
                WHERE
                grupos_tutorias.gpo_nombre = '$grupo'";
            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;
            }
            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                return $row['gpo_identificador'];
            }
        }

        function insertarAlumnos(){
            $grup = mysqli_real_escape_string($this->_db,(strip_tags($_POST['Nom_Grupo'], ENT_QUOTES) ) );

            $this->_sql="SELECT grupos_tutorias.gpo_clave FROM grupos_tutorias WHERE grupos_tutorias.gpo_identificador='".$_POST['Identificador']."' AND grupos_tutorias.cat_clave=".$_SESSION['usuario']['Cat'];
            if(!$resultado = $this->_db->query($this->_sql) ){
                echo json_encode(array('error'=>true,'er'=>"Error en" . $this->_db->errno . $this->_sql ) );
            }
            if ($resultado->num_rows > 0) {
                $row= $resultado->fetch_assoc();
                $clave=$row['gpo_clave'];

                $this->_sql="UPDATE grupos_tutorias SET gpo_nombre = '".$grup."' WHERE grupos_tutorias.gpo_identificador='".$_POST['Identificador']."' AND grupos_tutorias.cat_clave=".$_SESSION['usuario']['Cat'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, no se pudo modificar el nombre de grupo.".$this->_sql ]);
                    exit;
                }
                else{
                    $sql="INSERT INTO grupos_tutorias_complemento(alu_no_control,gpo_clave) values(?,?)";
                    if ($Grupos=$this->_db->prepare($sql)) {
                        $ncon=$_POST['control'];
                        $n="";
                        $Grupos->bind_param('si',$n, $clave);
                        $err=true;
                        for ($i = 0; $i < count($ncon); $i++) {
                            $n=$ncon[$i];
                            if (!$Grupos->execute()) {
                                echo json_encode(['error'=>true,'er'=>"Eror en alumnos Falló la ejecución: (" . $Grupos->errno . ") "]);
                            }
                            else {
                                $err=false;
                            }
                        }
                        if ($err==true) {
                             print_r( sqlsrv_errors(), true);
                            echo json_encode(['error'=>true,'er'=>"Error en prepare"]);
                        }
                        else {
                            echo json_encode(['error'=>false,'res'=>"Se guardo Correctamente el grupo $grup"]);
                        }
                    }
                    else{
                        echo json_encode(['error'=>true,'er'=>"Eror en la sentencia"]);
                    }
                }
            }else{
                print_r($resultado);
                echo json_encode(array('error'=>true,'er'=>"Error en if" . $this->_db->errno. " ". $this->_sql));
            }
        }

        function listar_control($gpo){
            $this->_sql="SELECT
                grupos_tutorias_complemento.alu_no_control
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                WHERE
                grupos_tutorias.gpo_nombre = '$gpo' AND
                grupos_tutorias.cat_clave =".$_SESSION['usuario']['Cat'] ;

            if(!$resultado=$this->_db->query($this->_sql)){
                return "fallo listar control";
            }
            if($resultado->num_rows > 0){
                $Num= '(';
                $co=1;
                while ($row=$resultado->fetch_assoc()) {
                    if ($co>1) {
                        $Num=$Num. ", '".$row['alu_no_control']."'";
                    }else{
                        $Num=$Num. "'".$row['alu_no_control']."'";
                    }
                    $co+=1;
                }
                return $Num=$Num.")";
            }
            else {
                return "( ".$this->_sql.")";
            }
        }

        function consultarCalificacionGnr($Cont){
            $this->_sql="SELECT AVG(dbo.ListasSemestre.lse_Calificacion) AS Cal, dbo.ListasSemestre.alu_NumControl FROM dbo.ListasSemestre WHERE dbo.ListasSemestre.alu_NumControl='$Cont' GROUP BY dbo.ListasSemestre.alu_NumControl";
            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            if($stmt!=false){
                $row = sqlsrv_fetch_array($stmt);
                return ($row['Cal']);
            }else {
                die( print_r( 'Query failed: '.$this->_sql));
            }

        }

        private function consultarGrupo($control){
            $this->_sql = "SELECT   `grupos_tutorias`.`gpo_nombre`
                FROM     `grupos_tutorias_complemento`
                INNER JOIN `grupos_tutorias`  ON `grupos_tutorias_complemento`.`gpo_clave` = `grupos_tutorias`.`gpo_clave`
                WHERE grupos_tutorias_complemento.alu_no_control = ' $control'";

            if (!$resultado=$this->_db->query($this->_sql)) {
                if ($resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    return $row['gpo_nombre'];
                }
                else{
                    return 'Sin tutor';
                }
            }
            else{
                return 'Sin tutor';
            }
        }

        function getCat($id){

            $this->_sql="SELECT catedratico_datos_personales.cat_Clave FROM catedratico_datos_personales INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
            WHERE usuario.u_Clave = '$id'";
            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;
            }
            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                return $row['cat_Clave'].$this->_sql;
            }
        }

        function busAlu($num){
            $this->_sql="SELECT
                grupos_tutorias_complemento.alu_no_control
                FROM
                grupos_tutorias_complemento
                WHERE
                grupos_tutorias_complemento.alu_no_control ='$num'";

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error'=>true, 'men'=>"Tenemos problemas por el momento, intente mas tarde"]);
            }
            if($resultado->num_rows > 0){
                echo json_encode(['error'=>true, 'men'=>"El Alumno ya se encuentra en otro grupo"]);
            }
            else {
                if ($mres=$this->_db->query("SELECT alumnos_caracterizacion.dp_nombre,alumnos_caracterizacion.dp_ap_paterno, alumnos_caracterizacion.dp_ap_materno, alumnos_caracterizacion.dp_carrera FROM alumnos_caracterizacion WHERE alumnos_caracterizacion.se_no_control ='$num'")){
                    if ($mres->num_rows >0) {
                        $mrow = $mres->fetch_assoc();
                        echo json_encode(['error'=>false, 'nom'=>$mrow['dp_nombre'], 'ap'=>$mrow['dp_ap_paterno'], 'am'=>$mrow['dp_ap_materno'], 'car'=>$mrow['dp_carrera'] ]);
                    }
                    else {
                        if($stmt = sqlsrv_query($this->_db2, "SELECT a.alu_NumControl AS NoControl, a.alu_Nombre AS Nombre, a.alu_ApePaterno AS ApePaterno, a.alu_ApeMaterno AS ApeMaterno, a.alu_SemestreAct AS Sem, c.car_Nombre AS Carrera FROM dbo.Alumnos AS a JOIN dbo.Carreras AS c ON a.car_Clave = c.car_Clave WHERE A.alu_NumControl ='" .$num."' AND a.alu_StatusAct = 'VI'") ) {
                            $row_count = sqlsrv_num_rows($stmt);

                            if ($row_count === false) {
                                echo json_encode(['error' => true, 'men' => "El Alumno no se encuentra registrado o esta dado de baja"]);
                            }else {
                                $srow = sqlsrv_fetch_array($stmt);
                                echo json_encode(['error' => false, 'nom' => $srow['Nombre'], 'ap' => $srow['ApePaterno'], 'am' => $srow['ApeMaterno'], 'car' => $srow['Carrera']]);
                            }
                        }
                        else{
                            echo json_encode(['error'=>true, 'men'=>"El Alumno no se encuentra registrado o esta dado de baja"]);
                        }
                    }
                }
                else{
                    echo json_encode(['error'=>true, 'men'=>"El Alumno no se encuentra registrado o esta dado de baja"]);
                }
            }
        }

        function insertarAlumno(){
            $this->_sql="INSERT INTO grupos_tutorias_complemento(alu_no_control,gpo_clave)
                values('".$_POST['control']."',(SELECT grupos_tutorias.gpo_clave FROM grupos_tutorias WHERE grupos_tutorias.gpo_nombre = '".$_POST['gpo']."') )";
            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error'=>true, 'men'=>"Tenemos problemas por el momento, intente mas tarde"]);
            }
            if($this->_db->affected_rows > 0){
                echo json_encode(['error'=>false,'res'=>"Se guardo Correctamente"]);
                }
            else {
                echo json_encode(['error'=>true,'men'=>"Eror en alumnos Falló la ejecución".$this->_sql]);
            }
        }

        function dropAlu($nom, $gpo) {
            $this->_sql="SELECT * FROM grupos_tutorias_complemento WHERE alu_no_control = '$nom' AND gpo_clave = (SELECT grupos_tutorias.gpo_clave FROM grupos_tutorias WHERE grupos_tutorias.gpo_nombre = '$gpo')";

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['err' => true, 'text'=> "error en select".$this->_sql , 'class'=> "alert-danger alert-dismissable" ]);
            }
            if($resultado->num_rows == 0){
                echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
            }else{
                $this->_sql="DELETE FROM grupos_tutorias_complemento WHERE alu_no_control = '$nom' AND gpo_clave = (SELECT grupos_tutorias.gpo_clave FROM grupos_tutorias WHERE grupos_tutorias.gpo_nombre = '$gpo')";
                $delete = mysqli_query(  $this->_db, $this->_sql);
                if($delete){
                   echo json_encode(['err'=> false,'text' => 'El alumno a sido eliminado correctamente.', 'class'=> "alert-success alert-dismissable" ]);
                }else{
                   echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
                }
           }
        }

        function aluSem($nu){
             $this->_sql="SELECT dbo.Alumnos.alu_SemestreAct FROM dbo.Alumnos WHERE dbo.Alumnos.alu_NumControl = '$nu'";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            $row = sqlsrv_fetch_array($stmt);

            return $row['alu_SemestreAct'];
        }
    }
 ?>
