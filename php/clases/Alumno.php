<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";

    class Alumno{

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
                printf("Error cargando el conjunto de caracteres utf8: %s\n", $this->_db2->error);
            }

            $connectionInfo = array( "Database"=>MS_DB_NAME, "UID"=>MS_DB_USER, "PWD"=>MS_DB_PASS);
            $this->_db2= sqlsrv_connect( MS_DB_SERVER, $connectionInfo);
            if( !$this->_db2 ){
                echo "Conexión no se pudo establecer.<br />";
                die( print_r( sqlsrv_errors(), true));
            }
        }

        public function AlumnoDatosPersonales(){
            $this->_sql="SELECT se_no_control, CONCAT (dp_nombre,' ', dp_ap_paterno ,' ',dp_ap_materno) AS NombreCompleto, dp_sexo, dp_email, dp_carrera, al_img FROM alumnos_caracterizacion WHERE se_no_control='".$_GET['NoCon']."'";

            if(!$resultado = $this->_db->query($this->_sql)){
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";

                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                /* cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
                exit;*/
            }

            if($resultado->num_rows > 0){
                $row = $resultado->fetch_assoc();
                if($row['dp_sexo']=='M'){
                    $Sexo="Mujer";
                }else{
                    $Sexo="Hombre";
                }
                echo "
                    <fieldset>
                                <img src='/sta/assets/images/".(isset($row['al_img'])?'Alu/'.$row['al_img']:'itsch.png')."' alt='FOTOALUMNO' class='img-responsive'>
                                <b>NO. CONTROL:</b> ".$row['se_no_control']."<br>
                                <br>
                                <b>NOMBRE DEL ALUMNO:</b> ".$row['NombreCompleto']."<br>
                                <br>
                                <b>SEXO:</b> ".$Sexo."<br>
                                <br>
                                <b>CORREO:</b> ".$row['dp_email']."<br>
                                <br>
                                <b>SEMESTRE:</b> ".$row['se_no_control']."<br>
                                <br>
                                <b>CARRERA:</b> ".$row['dp_carrera']."<br>
                                <br>
                                <b>GRUPO:</b> ".(isset($_GET['Grupo'])? $_GET['Grupo']:'')."<br>
                                <br>
                            </fieldset>
                ";
            }
            else{
                $this->_sql="SELECT dbo.Alumnos.alu_NumControl, dbo.Alumnos.alu_Nombre, dbo.Alumnos.alu_ApePaterno, dbo.Alumnos.alu_ApeMaterno, dbo.Alumnos.alu_Sexo, dbo.Alumnos.alu_SemestreAct,
                 (SELECT dbo.Carreras.car_Nombre FROM dbo.Carreras WHERE dbo.Carreras.car_Clave  = dbo.Alumnos.car_Clave) AS Car FROM dbo.Alumnos WHERE dbo.Alumnos.alu_NumControl = '".$_GET['NoCon']."'";
                $stmt = sqlsrv_query ( $this->_db2, $this->_sql)
                    or die('Query failed: '.$this->_sql);
                    $row = sqlsrv_fetch_array($stmt);
                    if($row['alu_Sexo']=='M'){
                        $Sexo="Hombre";
                    }else{
                        $Sexo="Mujer";
                    }
                    echo "
                        <fieldset>
                            <img src='/sta/assets/images/avatar1_small.jpg' alt='FOTOALUMNO' class='img-responsive'>
                            <b>NO. CONTROL:</b> ".$row['alu_NumControl']."<br>
                            <br>
                            <b>NOMBRE DEL ALUMNO:</b> ".$row['alu_Nombre']." ".$row['alu_ApePaterno']." ".$row['alu_ApeMaterno']."<br>
                            <br>
                            <b>SEXO:</b> ".$Sexo."<br>
                            <br>
                            <b>CORREO:</b> <br>
                            <br>
                            <b>SEMESTRE:</b> ".$row['alu_SemestreAct']."<br>
                            <br>
                            <b>CARRERA:</b> ".$row['Car']."<br>
                            <br>
                            <b>GRUPO:</b> ".$_GET['Grupo']."<br>
                            <br>
                        </fieldset>
                    ";
            }
        }

        public function AlumnoCalificaciones(){
            $Materias=array();
            $U1=array(); $U2=array(); $U3=array(); $U4=array(); $U5=array(); $U6=array(); $U7=array(); $U8=array(); $U9=array(); $U10=array(); $U11=array(); $U12=array(); $U13=array();


            $Ide=$this->getIndGrupo();

            $this->_sql="SELECT
                dbo.Reticula.ret_NomCompleto,
                dbo.GruposSemestre.gse_clave,
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
                FROM
                dbo.GruposSemestre
                INNER JOIN dbo.ListasSemestre ON dbo.GruposSemestre.gse_Clave = dbo.ListasSemestre.gse_Clave
                INNER JOIN dbo.Alumnos ON dbo.ListasSemestre.alu_NumControl = dbo.Alumnos.alu_NumControl
                INNER JOIN dbo.Reticula ON dbo.GruposSemestre.ret_Clave = dbo.Reticula.ret_Clave
                WHERE
                dbo.Alumnos.alu_NumControl = '".$_GET['NoCon']."'";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql)
                or die('Query failed: '.$this->_sql);

            $NumMat=0;
            while ($row = sqlsrv_fetch_array($stmt)){
                array_push($Materias, array($row['ret_NomCompleto'], $row['gse_clave']));
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
                $NumMat=$NumMat+1;
            }
            sqlsrv_free_stmt($stmt);
            $Cal = array($U1,$U2,$U3,$U4,$U5,$U6,$U7,$U8,$U9,$U10,$U11,$U12,$U13);
            echo "<thead>
                <tr>";
                for ($a=0; $a < count($Materias); $a++) {
                        echo "<th>".$Materias[$a][0]."</th>";
                }
            echo"</tr>
            </thead>
            <tbody>";
            for ($i=0; $i < 13; $i++) {
                if ($i%2===1) {
                    echo "<tr>";
                    for ($j=0; $j < $NumMat; $j++) {
                        $ban=false;
                        for ($k=0; $k < $NumMat; $k++) {
                            if ( !empty($Cal[$i][$k])) {
                                $ban=true;
                                break;
                            }
                        }
                        if ($ban===true) {
                            if (is_numeric($Cal[$i][$j])) {
                                if ($Cal[$i][$j]<=69) {
                                    $mot=$this->getMotivos($i, $Materias[$j][1], $_GET['NoCon']);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        if (!is_null($mot[1]))
                                            echo "<td class='danger' data-tooltip='".$mot[1]."'>".utf8_encode($Cal[$i][$j])."</td>";
                                        else
                                            echo "<td class='danger' data-tooltip='".$mot[0]."'>".utf8_encode($Cal[$i][$j])."</td>";
                                    }
                                    else {
                                        echo "<td class='danger' data-tooltip='En espera'>".utf8_encode($Cal[$i][$j])."</td>";
                                    }
                                }else {
                                    echo "<td>".utf8_encode($Cal[$i][$j])."</td>";
                                }
                            }else {
                                echo "<td>".utf8_encode($Cal[$i][$j])."</td>";
                            }
                        }else {
                            break 2;
                        }

                    }
                    echo "</tr>";
                }
                else{
                    echo"<tr class='active'>";
                    for ($j=0; $j < $NumMat; $j++) {
                        $ban=false;
                        for ($k=0; $k < $NumMat; $k++) {
                            if ( !empty($Cal[$i][$k])) {
                                $ban=true;
                                break;
                            }
                        }
                        if ($ban===true) {
                            if (is_numeric($Cal[$i][$j])) {
                                if ($Cal[$i][$j]<=69) {
                                    $mot=$this->getMotivos($i, $Materias[$j][1]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        echo "<td class='danger' data-tooltip='".$mot[0]."'>".utf8_encode($Cal[$i][$j])."</td>";
                                    }
                                    else {
                                        echo "<td class='danger' data-tooltip='En espera'>".utf8_encode($Cal[$i][$j])."</td>";
                                    }

                                }else {
                                    echo "<td>".utf8_encode($Cal[$i][$j])."</td>";
                                }
                            }else {
                                echo "<td>".utf8_encode($Cal[$i][$j])."</td>";
                            }

                        }else {
                            break 2;
                        }
                    }
                    echo "</tr>";
                }

            }
            echo "</tbody>";
            /*
            echo var_dump($Cal);
            echo var_dump($Materias);*/

        }

        public function AlumnoSemaforos(){
            $val1='0'; $clas1='danger'; $com1 = "";
            $val2='0'; $clas2='danger'; $com2 = "";
            $val3='0'; $clas3='danger'; $com3 = "";
            $val4='0'; $clas4='danger'; $com4 = "";
            $val5='0'; $clas5='danger'; $com5 = "";

            $this->_sql="SELECT
                semaforos_trayectoria.no_control,
                semaforos_trayectoria.smf_medico,
                semaforos_trayectoria.smf_medico_com,
                semaforos_trayectoria.smf_psicologia,
                semaforos_trayectoria.smf_psicologia_com,
                semaforos_trayectoria.smf_calificacion,
                semaforos_trayectoria.smf_calificacion_com,
                semaforos_trayectoria.smf_srv_social,
                semaforos_trayectoria.smf_srv_social_com,
                semaforos_trayectoria.smf_cult_y_depo,
                semaforos_trayectoria.smf_cult_y_depo_com
                FROM
                semaforos_trayectoria
                WHERE no_control ='".$_GET['NoCon']."'";

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
                $val1 = $row['smf_medico'];         $clas1 = $this->clases($row['smf_medico']);    $com1 = $row['smf_medico_com'];
                $val2 = $row['smf_psicologia'];     $clas2 = $this->clases($row['smf_psicologia']);    $com2 = $row['smf_psicologia_com'];
                $val4 = $row['smf_srv_social'];     $clas4 = $this->clases($row['smf_srv_social']);    $com4 = $row['smf_srv_social_com'];
                $val5 = $row['smf_cult_y_depo'];    $clas5 = $this->clases($row['smf_cult_y_depo']);    $com5 = $row['smf_cult_y_depo_com'];
            }else {
                $com1='Sin Visitas';        $clas1='info';
                $com2='Sin Visitas';        $clas2='info';
                $com4='Aun sin realizar';   $clas4='info';
                $com5='No liverado';        $clas5=$_GET['NoCon']<5?'info':'danger';
            }
            $com3 = $this->calGnr($_GET['NoCon']);   $clas3 = ($val3>=90&&$val3<=100? 'info': ($val3>=80&&$val3<=89? 'success': ($val3>=70&&$val3<=79? 'warning': 'danger' )));

            echo "  <td class='$clas1'>$com1</td>
                    <td class='$clas2'>$com2</td>
                    <td class='$clas3'>$com3</td>
                    <td class='$clas4'>$com4</td>
                    <td class='$clas5'>$com5</td>";

        }

        public function AlumnoSemestre($nu){
            $this->_sql="SELECT dbo.Alumnos.alu_SemestreAct FROM dbo.Alumnos WHERE dbo.Alumnos.alu_NumControl = '$nu'";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            $row = sqlsrv_fetch_array($stmt);
            if ($row['alu_SemestreAct']==1) {
                echo "<table class='table table-condensed table-hover table-striped' style='font-size:20px;'>
                        <thead>
                            <tr>
                                No hay semesestres anteriores
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==2) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==3) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==4) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==5) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==6) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==7) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            <tr>
                            </tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==8) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            <tr>
                            </tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==9) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            <tr>
                            </tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=8'> 8 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==10) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            <tr>
                            </tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=8'> 8 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=9'> 9 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==11) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            </tr>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=8'> 8 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=9'> 9 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=10'> 10 </a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==12) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            <tr>
                            </tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=8'> 8 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=9'> 9 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=10'> 10 </a></th>
                            </tr>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=11'> 11</a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==13) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            </tr>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=8'> 8 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=9'> 9 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=10'> 10 </a></th>
                            </tr>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=11'> 11</a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=12'> 12</a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            else if ($row['alu_SemestreAct']==14) {
                echo "<table class='table' style='font-size:20px;'>
                        <thead>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=1'> 1 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=2'> 2 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=3'> 3 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=4'> 4 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=5'> 5 </a></th>
                            </tr>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=6'> 6 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=7'> 7 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=8'> 8 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=9'> 9 </a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=10'> 10 </a></th>
                            </tr>
                            <tr>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=11'> 11</a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=12'> 12</a></th>
                                <th><a href='Semestre.php?NoCon=".$nu."&Grupo=".(isset($_GET['Grupo'])? $_GET['Grupo']:'Sin Grupo')."&Sem=13'> 13</a></th>
                            </tr>
                        </thead>
                    </table>";
            }
            sqlsrv_free_stmt($stmt);
        }

        public function getIndGrupo(){

            $this->_sql="SELECT
                grupos_tutorias.gpo_identificador
                FROM
                grupos_tutorias
                WHERE
                grupos_tutorias.gpo_nombre = '".(isset($_GET['Grupo'])? $_GET['Grupo']:'')."'";
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

        public function getMotivos($unidad, $gse_clave, $ncon){

            $this->_sql="SELECT
                	(SELECT mot_re_nombre FROM motivos_reprovacion WHERE mot_re_clave = mtv_reprovacion) as Motre,
                	grupo_profesor_calificaciones.gpro_cal_otro

                FROM
                	grupo_profesor_calificaciones
                WHERE
                	grupo_profesor_calificaciones.gpro_cal_parcial = $unidad
                	AND grupo_profesor_calificaciones.gpro_clave = $gse_clave
                	AND se_no_control = '$ncon'";
            if ($resultado=$this->_db->query($this->_sql)) {

                if ($resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    return array($row['mot_re_nombre'], $row['gpro_cal_otro']);
                }
            }
        }

        public function aluFiPerfil($noCon, $caract){
            if ($caract=='no') {
                $this->_sql="SELECT
                    dbo.Alumnos.alu_NumControl,
                    dbo.Alumnos.alu_Nombre,
                    dbo.Alumnos.alu_ApePaterno,
                    dbo.Alumnos.alu_ApeMaterno,
                    dbo.Alumnos.alu_Sexo,
                    (SELECT dbo.Carreras.car_Nombre FROM dbo.Carreras WHERE dbo.Carreras.car_Clave=dbo.Alumnos.car_Clave) AS dp_carrera
                    FROM dbo.Alumnos WHERE dbo.Alumnos.alu_NumControl = '$noCon'";
            }
            else{
                $this->_sql="SELECT
                    alumnos_caracterizacion.se_no_control,
                    alumnos_caracterizacion.dp_nombre,
                    alumnos_caracterizacion.dp_ap_paterno,
                    alumnos_caracterizacion.dp_ap_materno,
                    alumnos_caracterizacion.dp_edad,
                    alumnos_caracterizacion.fm_talla,
                    alumnos_caracterizacion.fm_peso,
                    alumnos_caracterizacion.dp_sexo,
                    alumnos_caracterizacion.dp_carrera,
                    alumnos_caracterizacion.dp_grupo,
                    fiden_perfil.fip_edo_civil,
                    alumnos_caracterizacion.dp_tipo_sangre,
                    fiden_perfil.fip_fecha_nac,
                    fiden_perfil.fip_lugar_nac,
                    fiden_perfil.fip_trabajo,
                    fiden_perfil.fip_domicilio,
                    fiden_perfil.fip_vivienda_tipo,
                    fiden_perfil.fip_vivienda_tipo_otro,
                    alumnos_caracterizacion.dp_tel,
                    alumnos_caracterizacion.dp_email,
                    fiden_perfil.fip_per_vivienda,
                    fiden_perfil.fip_per_parentesco,
                    fiden_perfil.fip_padre,
                    fiden_perfil.fip_madre,
                    fiden_perfil.fip_rel_padres
                    FROM
                    alumnos_caracterizacion
                    left JOIN fiden_perfil ON fiden_perfil.no_control = alumnos_caracterizacion.se_no_control
                    WHERE alumnos_caracterizacion.se_no_control= '$noCon'";
            }

            $se_nocontrol=""; $dp_nombre = "" ; $dp_ap_paterno = "" ; $dp_ap_materno = "" ; $dp_edad = "" ; $fm_talla = "" ;
            $fm_peso = "" ; $dp_sexo = "" ; $dp_carrera = "" ; $dp_grupo = ""; $fip_edo_civil = "" ; $dp_tipo_sangre = "" ;
            $fip_fecha_nac = "" ; $fip_lugar_nac = "" ; $fip_trabajo = "" ; $fip_domicilio = "" ; $fip_vivienda_tipo = "" ;
            $fip_vivienda_tipo_otro = "" ; $dp_tel = "" ; $dp_email = "" ; $fip_per_vivienda = "" ; $fip_per_parentesco = "" ;
            $fip_padre = "" ; $fip_madre = "" ; $fip_rel_padres = "";

            if ($caract == 'no') {
                $stmt = sqlsrv_query($this->_db2, $this->_sql);
                if( $stmt ) {
                    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                    $se_nocontrol=  !is_null($row['alu_NumControl'])? $row['alu_NumControl']:'' ;
                    $dp_nombre =  !is_null($row['alu_Nombre'])? $row['alu_Nombre']:'' ;
                    $dp_ap_paterno =  !is_null($row['alu_ApePaterno'])? $row['alu_ApePaterno']:'' ;
                    $dp_ap_materno =  !is_null($row['alu_ApeMaterno'])? $row['alu_ApeMaterno']:'' ;
                    $dp_edad =  '';
                    $fm_talla =  '';
                    $fm_peso =  '';
                    $dp_sexo =  !is_null($row['alu_Sexo'])? $row['alu_Sexo']:'' ;
                    $dp_carrera =  !is_null($row['dp_carrera'])? $row['dp_carrera']:'' ;
                    $dp_grupo= '';
                    $fip_edo_civil =  '';
                    $dp_tipo_sangre =  '';
                    $fip_fecha_nac =  '';
                    $fip_lugar_nac =  '';
                    $fip_trabajo =  '';
                    $fip_domicilio =  '';
                    $fip_vivienda_tipo =  '';
                    $fip_vivienda_tipo_otro =  '';
                    $dp_tel =  '';
                    $dp_email =  '';
                    $fip_per_vivienda =  '';
                    $fip_per_parentesco =  '';
                    $fip_padre =  '';
                    $fip_madre =  '';
                    $fip_rel_padres =  '';
                }
            }
            else{
                if ($resultado=$this->_db->query($this->_sql)) {
                    if ($resultado->num_rows > 0) {
                        $row = $resultado->fetch_assoc();
                        $se_nocontrol=  !is_null($row['se_no_control'])? $row['se_no_control']:'' ;
                        $dp_nombre =  !is_null($row['dp_nombre'])? $row['dp_nombre']:'' ;
                        $dp_ap_paterno =  !is_null($row['dp_ap_paterno'])? $row['dp_ap_paterno']:'' ;
                        $dp_ap_materno =  !is_null($row['dp_ap_materno'])? $row['dp_ap_materno']:'' ;
                        $dp_edad =  !is_null($row['dp_edad'])? $row['dp_edad']:'' ;
                        $fm_talla =  !is_null($row['fm_talla'])? $row['fm_talla']:'' ;
                        $fm_peso =  !is_null($row['fm_peso'])? $row['fm_peso']:'' ;
                        $dp_sexo =  !is_null($row['dp_sexo'])? $row['dp_sexo']:'' ;
                        $dp_carrera =  !is_null($row['dp_carrera'])? $row['dp_carrera']:'' ;
                        $dp_grupo =  !is_null($row['dp_grupo'])? $row['dp_grupo']:'' ;
                        $fip_edo_civil =  !is_null($row['fip_edo_civil'])? $row['fip_edo_civil']:'' ;
                        $dp_tipo_sangre =  !is_null($row['dp_tipo_sangre'])? $row['dp_tipo_sangre']:'' ;
                        $fip_fecha_nac =  !is_null($row['fip_fecha_nac'])? $row['fip_fecha_nac']:'' ;
                        $fip_lugar_nac =  !is_null($row['fip_lugar_nac'])? $row['fip_lugar_nac']:'' ;
                        $fip_trabajo =  !is_null($row['fip_trabajo'])? $row['fip_trabajo']:'' ;
                        $fip_domicilio =  !is_null($row['fip_domicilio'])? $row['fip_domicilio']:'' ;
                        $fip_vivienda_tipo =  !is_null($row['fip_vivienda_tipo'])? $row['fip_vivienda_tipo']:'' ;
                        $fip_vivienda_tipo_otro =  !is_null($row['fip_vivienda_tipo_otro'])? $row['fip_vivienda_tipo_otro']:'' ;
                        $dp_tel =  !is_null($row['dp_tel'])? $row['dp_tel']:'' ;
                        $dp_email =  !is_null($row['dp_email'])? $row['dp_email']:'' ;
                        $fip_per_vivienda =  !is_null($row['fip_per_vivienda'])? $row['fip_per_vivienda']:'' ;
                        $fip_per_parentesco =  !is_null($row['fip_per_parentesco'])? $row['fip_per_parentesco']:'' ;
                        $fip_padre =  !is_null($row['fip_padre'])? $row['fip_padre']:'' ;
                        $fip_madre =  !is_null($row['fip_madre'])? $row['fip_madre']:'' ;
                        $fip_rel_padres =  !is_null($row['fip_rel_padres'])? $row['fip_rel_padres']:'' ;
                    }
                }
            }
            echo '<h3 class="head text-center">FICHA DE INDENTIFICACIÓN DEL ALUMNO TUTORADO</h3>
                <div class="panel-group" id="personales">
                    <div class="faqHeader">Datos Personales</div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" id="collapseNomA" data-parent="#personales" href="#collapseNom">Nombre Completo</a>
                            </h4>
                        </div>
                        <div id="collapseNom" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_nombre" placeholder="Nombre" value="'.$dp_nombre.'" name="dp_nombre" required readOnly>
                                            <label for="dp_nombre" class="float-label">Nombre(s)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" placeholder="Apellido Paterno" value="'.$dp_ap_paterno.'" name="dp_ap_paterno" id="dp_ap_paterno" required readOnly>
                                            <label for="dp_ap_paterno" class="float-label">Apellido Paterno</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" placeholder="Apellido Materno" value="'.$dp_ap_materno. '" name="dp_ap_materno" id="dp_ap_materno" required readOnly>
                                            <label for="dp_ap_materno" class="float-label">Apellido Materno</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" id="fisicA" data-parent="#personales" href="#fisic">Fisico</a>
                            </h4>
                        </div>
                        <div id="fisic" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="number" class="form-control float-input" id="dp_edad" placeholder="Edad" value="'.$dp_edad. '" name="dp_edad" required>
                                            <label for="dp_edad" class="float-label">Edad</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="number" class="form-control float-input" id="fm_talla" placeholder="Estatura" value="'.$fm_talla. '" name="fm_talla" step="0.01" required>
                                            <label for="fm_talla" class="float-label">Estatura</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="number" class="form-control float-input" id="fm_peso" placeholder="Peso" value="'.$fm_peso. '" name="fm_peso" step="0.01" required>
                                            <label for="fm_peso" class="float-label">Peso</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_sexo" placeholder="Sexo" value="'.$dp_sexo. '" name="dp_sexo" required>
                                            <label for="dp_sexo" class="float-label">Sexo</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <select class="form-control float-input" id="dp_tipo_sangre" name="dp_tipo_sangre" required>
                                                <option disabled>Seleccione un tipo de sangre</option>';
                                                $tipSan= array("A+","A-","B+","B-","AB+","AB-","O+","O-");
                                                foreach ($tipSan as  $value) {
                                                    if (strcasecmp($value, $dp_tipo_sangre) == 0)  {
                                                        echo "<option selected='selected' value='".$value."'>".$value."</option>";
                                                    }else {
                                                        echo "<option value='".$value."'>".$value."</option>";
                                                    }
                                                }
                                        echo'</select>
                                        <label for="dp_tipo_sangre" class="float-label">Tipo de sangre</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" id=grupoA href="#grupo">Grupo</a>
                            </h4>
                        </div>
                        <div id="grupo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_carrera" placeholder="Carrera" value="'.$dp_carrera.'" name="dp_carrera" required >
                                            <label for="dp_carrera" class="float-label">Carrera</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_grupo" placeholder="Grupo" value="'.$dp_grupo.'" name="dp_grupo" required>
                                            <label for="dp_grupo" class="float-label">Grupo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" id="fecnacA" data-parent="#personales" href="#fenac">Fecha de Nacimiento</a>
                            </h4>
                        </div>
                        <div id="fenac" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="date" class="form-control float-input" id="fip_fecha_nac" placeholder="Fecha de Nacimiento" value="'.$fip_fecha_nac. '" name="fip_fecha_nac" required>
                                            <label for="fip_fecha_nac" class="float-label">Fecha de Nacimiemto</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="fip_lugar_nac" placeholder="Lugar de Nacimiento" value="'.$fip_lugar_nac. '" name="fip_lugar_nac" required>
                                            <label for="fip_lugar_nac" class="float-label">Lugar de Nacimiemto</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" id="edciA" data-parent="#personales" href="#edci">Estado Civil</a>
                            </h4>
                        </div>
                        <div id="edci" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <select class="form-control float-input" id="fip_edo_civil" name="fip_edo_civil" required>
                                                <option disabled>Seleccione una opción</option>';
                                    $edoCI=array ("Soltero", "Casado", "Separado", "Divorciado", "Viúdo", "Unión Libre" );
                                    foreach ($edoCI as  $value) {
                                        if (strcasecmp($value, $fip_edo_civil) == 0)  {
                                            echo "<option selected='selected' value='".$value."'>".$value."</option>";
                                        }else {
                                            if (strcasecmp($value, "Seleccione una opción") == 0)  {
                                                echo "<option selected='selected' readonly >".$value."</option>";
                                            } else{
                                                echo "<option value='".$value."'>".$value."</option>";
                                            }
                                        }
                                    }
                                    echo '  </select>
                                            <label for="fip_edo_civil" class="float-label">Estado Civil</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#inputTrabajo">¿ Trabajas ?</a>
                            </h4>
                        </div>
                        <div id="inputTrabajo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <legend>Indica si o no si es que trabajas</legend>
                                        <label class="radio-inline">
                                            <input type="radio" name="trAlu" id="trAlu1" value="1" onclick="$(\'#fip_trabajo\').attr(\'readonly\', false)" ' . ($fip_trabajo!=""? 'checked' : '') . '> Si
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="trAlu" id="trAlu2" value="2" onclick="$(\'#fip_trabajo\').attr(\'readonly\',true).attr(\'value\',\'\')" ' . ($fip_trabajo!=""? '' : 'checked') . ' required> No
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="fip_trabajo" ' . (!empty($fip_trabajo)? 'readonly' : '') . ' id="fip_trabajo" class="form-control float-input" value="'.$fip_trabajo.'"/>
                                            <label for="fip_trabajo" class="float-label">En que lugar trabajas</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#domi">Domicilio Actual</a>
                            </h4>
                        </div>
                        <div id="domi" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="fip_domicilio" id="fip_domicilio" class="form-control float-input" placeholder="Domicilio Actual" value="'.$fip_domicilio.'" required/>
                                            <label for="fip_domicilio" class="float-label">Domicilio Actual</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#casaedo">La casa donde vives es:</a>
                            </h4>
                        </div>
                        <div id="casaedo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">';
                                    echo '<div class="col-sm-6">
                                        <legend>Elige una opcion de acorde a tu casa</legend>';
                                        $tipCasa = array("Rentada", "Prestada", "Propia", "Otro");
                                        foreach ($tipCasa as  $value) {
                                            if (strcasecmp($value, $fip_vivienda_tipo) == 0)  {
                                                if ($value=="Otro") {
                                                    echo '<label class="radio-inline">
                                                        <input type="radio" name="fip_vivienda_tipo" value="'.$value.'" onclick="$(\'#casatipo\').attr(\'readonly\', false)" checked required> '.$value.'
                                                        </label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group bs-float-label">
                                                                <input type="text" name="fip_vivienda_tipo_otro" id="casatipo" class="form-control float-input" value="'.$row['fip_vivienda_tipo_otro'].'"/>
                                                                <label for="casatipo" class="float-label">Define tu casa</label>
                                                            </div>
                                                        </div>';
                                                }
                                                else {
                                                    echo '<label class="radio-inline">
                                                        <input type="radio" name="fip_vivienda_tipo" value="'.$value. '" onclick="$(\'#casatipo\').attr(\'readonly\',true).attr(\'value\',\'\')" checked> '.$value.'
                                                        </label>';
                                                }
                                            }
                                            else {
                                                if ($value=="Otro") {
                                                    echo '<label class="radio-inline">
                                                        <input type="radio" name="fip_vivienda_tipo" value="'.$value.'" onclick="$(\'#casatipo\').attr(\'readonly\', false)" required> '.$value.'
                                                        </label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group bs-float-label">
                                                                <input type="text" name="fip_vivienda_tipo_otro" id="casatipo" class="form-control float-input" value="'.$fip_vivienda_tipo_otro.'" readonly/>
                                                                <label for="casatipo" class="float-label">Define tu casa</label>
                                                            </div>
                                                        </div>';
                                                }
                                                else {
                                                    echo '<label class="radio-inline">
                                                        <input type="radio" name="fip_vivienda_tipo" value="'.$value. '" onclick="$(\'#casatipo\').attr(\'readonly\',true).attr(\'value\',\'\')"> '.$value.'
                                                        </label>';
                                                }
                                            }
                                        }
                        echo'   </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#datai2">Datos de identificacion 2</a>
                            </h4>
                        </div>
                        <div id="datai2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="dp_tel" id="dp_tel" class="form-control float-input" placeholder="Número de Teléfono" value="'.$dp_tel.'" required/>
                                            <label for="dp_tel" class="float-label">Número de telefono</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="email" name="dp_email" id="dp_email" class="form-control float-input" placeholder="Correo Electronico" value="'.$dp_email. '" required/>
                                            <label for="dp_email" class="float-label">Correo Electronio</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#nump">Numero de personas con las que vives</a>
                            </h4>
                        </div>
                        <div id="nump" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="number" name="fip_per_vivienda" id="fip_per_vivienda" class="form-control float-input" placeholder="Número de personas con las que vives" value="'.$fip_per_vivienda.'" required/>
                                            <label for="fip_per_vivienda" class="float-label">Número de personas con las que vives</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="fip_per_parentesco" id="fip_per_parentesco" class="form-control float-input" placeholder="Parentesco" value="'.$fip_per_parentesco.'" required/>
                                            <label for="fip_per_parentesco" class="float-label">Parentesco con las personas con las que vives</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

            $datp= "SELECT * FROM alumnos_datos_padres WHERE id_alumno = '$noCon'";
            $nomp = ''; $app = ''; $amp = ''; $edadp = ''; $telp = ''; $trabp = ''; $profp = ''; $domp = ''; $nomm = ''; $apm = ''; $amm = '';
            $edadm = ''; $telm = ''; $trabm = ''; $profm = ''; $domm = '';

            if ($resultado=$this->_db->query($datp)) {
                if($resultado->num_rows>0){
                    $row = $resultado->fetch_assoc();

                    $nomp= $row['dp_nom_padre'];
                    $app = $row['dp_apep_padre'];
                    $amp = $row['dp_apem_padre'];
                    $edadp = $row['dp_edad_padre'];
                    $telp = $row['dp_tel_padre'];
                    $trabp = $row['dp_trabajo_padre'];
                    $profp = $row['dp_profecion_padre'];
                    $domp = $row['dp_dom_padre'];

                    $nomm = $row['dp_nom_madre'];
                    $apm = $row['dp_apep_madre'];
                    $amm = $row['dp_apem_madre'];
                    $edadm = $row['dp_edad_madre'];
                    $telm = $row['dp_tel_madre'];
                    $trabm = $row['dp_trabajo_madre'];
                    $profm = $row['dp_profecion_madre'];
                    $domm = $row['dp_dom_madre'];
                }
            }

            echo'        <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#datPad">Datos del Padre</a>
                            </h4>
                        </div>
                        <div id="datPad" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" name="nomPa" name="nomPa" placeholder="Nombre" value="'.$nomp.'">
                                            <label for="nomPa" class="float-label">Nombre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" name="apPa" id="apPa" placeholder="Apellido Paterno" value="'.$app.'">
                                            <label for="SoapParam" class="float-label">Apellido Paterno</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" name="amPa" id="amPa" placeholder="Apellido Materno" value="'.$amp.'">
                                            <label for="apMa" class="float-label">Apellido Materno</label>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="number" name="EdadPa" name="EdadPa" class="form-control float-input" placeholder="Edad del Padre" value="'.$edadp.'"/>
                                            <label for="fip_per_parentesco" class="float-label">Edad del Padre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="TelPa" name="TelPa" id="TelPa" class="form-control float-input" placeholder="Telefono del padre" value="'.$telp.'"/>
                                            <label for="TelPa" class="float-label">Telefono del padre</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        ¿Trabaja?
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadioOptions" name="inlineRadio1" value="1" onclick="$(\'#trabPatext\').attr(\'readonly\', false)" checked="'.(!is_null($trabp)? 'checked':'').'"> Si
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadioOptions" name="inlineRadio2" value="2" onclick="$(\'#trabPatext\').attr(\'readonly\',true).attr(\'value\',\'\')" checked="'.(!is_null($trabp)? '':'checked').'"> No
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="trabPtext" id="trabPatext" class="form-control float-input"  placeholder="En que trabaja tu Padre" value="'.$trabp.'" readOnly="'.(!is_null($trabp)? '':'readonly').'"/>
                                        <label for="trabPatext" class="float-label">En que trabaja tu Padre</label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="ProfPa" id="ProfPa" class="form-control float-input" placeholder="Profecion del Padre" value="'.$profp.'"/>
                                            <label for="ProfPa" class="float-label">Profecion del Padre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="DomPa" id="DomPa" class="form-control float-input" placeholder="Domicilio del Padre" value="'.$domp.'"/>
                                            <label for="DomPa" class="float-label">Domicilio del Padre</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#datMad">Datos de la Madre</a>
                            </h4>
                        </div>
                        <div id="datMad" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" class="form-control float-input" name="nomMa" id="nomMa" placeholder="Nombre" value="'.$nomm.'">
                                        <label for="nomMa" class="float-label">Nombre</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group bs-float-label">
                                        <input type="text" class="form-control float-input" name="apMa" id="amPa" placeholder="Apellido Paterno" value="'.$apm.'">
                                        <label for="amPa" class="float-label">Apellido Paterno</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group bs-float-label">
                                        <input type="text" class="form-control float-input" name="amMa" id="amMa" placeholder="Apellido Materno" value="'.$amm.'">
                                        <label for="apMa" class="float-label">Apellido Materno</label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="number" name="EdadMa" name="EdadMa" id="EdadMa" class="form-control float-input" placeholder="Edad de la Madre" value="'.$edadm.'"/>
                                        <label for="EdadMa" class="float-label">Edad de la Madre</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" name="TelPa" id="TelMa" class="form-control float-input" placeholder="Telefono de la Madre" value="'.$telm.'"/>
                                        <label for="TelMa" class="float-label">Telefono de la Madre</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    ¿Trabaja?
                                    <label class="radio-inline">
                                        <input type="radio" name="inlineRadioOptions" name="inid=lineRadio1" value="1" onclick="$(\'#trabMatext\').attr(\'readonly\', false)" checked="'.(!is_null($trabm)? 'checked':'').'"> Si
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="inlineRadioOptions" name="inlineRadio2" value="2" onclick="$(\'#trabMatext\').attr(\'readonly\',true).attr(\'value\',\'\')" checked="'.(!is_null($trabm)? '':'checked').'"> No
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" name="trabMtext" id="trabMatext" class="form-control float-input"  placeholder="En que trabaja tu Madre" value="'.$trabm.'" readOnly="'.(!is_null($trabm)? '':'readonly').'"/>
                                        <label for="trabMtext" class="float-label">En que trabaja tu Madre</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" id="ProfMa" name="ProfMa" class="form-control float-input" placeholder="Profecion de la Madre" value="'.$profm.'"/>
                                        <label for="ProfMa" class="float-label">Profecion de la Madre</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" name="DomMa" id="DomMa" class="form-control float-input" placeholder="Domicilio de la Madre" value="'.$domm.'"/>
                                        <label for="DomMa" class="float-label">Domicilio de la Madre"</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#relPad">¿Comó es la relación de tus padres?</a>
                            </h4>
                        </div>
                        <div id="relPad" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <div class="form-group bs-float-label">
                                                <input type="text" class="form-control float-input" id="fip_rel_padres" name="fip_rel_padres" id="fip_rel_padres" placeholder="Relación" value="'.$fip_rel_padres.'">
                                                <label for="fip_rel_padres" class="float-label">Relación de tus padres</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }

        public function aluFiSalud($noCon){
            $this->_sql="SELECT
                fiden_medico.no_control,
                fiden_medico.fm_diabetes,
                fiden_medico.fm_hipertencion,
                fiden_medico.fm_epilepsia,
                fiden_medico.fm_anorexia,
                fiden_medico.fm_bulimia,
                fiden_medico.fm_trans_sexual,
                fiden_medico.fm_depresion,
                fiden_medico.fm_tristesa,
                fiden_medico.fm_otra,
                fiden_medico.fm_discapacidad,
                fiden_medico.fm_dis_vista,
                fiden_medico.fm_dis_oido,
                fiden_medico.fm_dis_lenguaje,
                fiden_medico.fm_dis_motora,
                fiden_medico.fm_dis_otra,
                fiden_medico.dx_psicologico,
                fiden_medico.dx_psicologico_tm,
                fiden_medico.dx_medico,
                fiden_medico.dx_medico_tm
                FROM fiden_medico WHERE
                fiden_medico.no_control = '$noCon'";

            $fm_diabetes= ""; $fm_hipertencion= ""; $fm_epilepsia= ""; $fm_anorexia= ""; $fm_bulimia= ""; $fm_trans_sexual= ""; $fm_depresion= ""; $fm_tristesa= ""; $fm_otra= ""; $fm_discapacidad= ""; $fm_dis_vista= ""; $fm_dis_oido= ""; $fm_dis_lenguaje= ""; $fm_dis_motora= ""; $fm_dis_otra= ""; $dx_psicologico= ""; $dx_psicologico_tm= ""; $dx_medico= ""; $dx_medico_tm= "";
            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
            }
            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                $fm_diabetes= !is_null($row['fm_diabetes'])? $row['fm_diabetes']:'' ;
                $fm_hipertencion= !is_null($row['fm_hipertencion'])? $row['fm_hipertencion']:'' ;
                $fm_epilepsia= !is_null($row['fm_epilepsia'])? $row['fm_epilepsia']:'' ;
                $fm_anorexia= !is_null($row['fm_anorexia'])? $row['fm_anorexia']:'' ;
                $fm_bulimia= !is_null($row['fm_bulimia'])? $row['fm_bulimia']:'' ;
                $fm_trans_sexual= !is_null($row['fm_trans_sexual'])? $row['fm_trans_sexual']:'' ;
                $fm_depresion= !is_null($row['fm_depresion'])? $row['fm_depresion']:'' ;
                $fm_tristesa= !is_null($row['fm_tristesa'])? $row['fm_tristesa']:'' ;
                $fm_otra= !is_null($row['fm_otra'])? $row['fm_otra']:'' ;
                $fm_discapacidad= !is_null($row['fm_discapacidad'])? $row['fm_discapacidad']:'' ;
                $fm_dis_vista= !is_null($row['fm_dis_vista'])? $row['fm_dis_vista']:'' ;
                $fm_dis_oido= !is_null($row['fm_dis_oido'])? $row['fm_dis_oido']:'' ;
                $fm_dis_lenguaje= !is_null($row['fm_dis_lenguaje'])? $row['fm_dis_lenguaje']:'' ;
                $fm_dis_motora= !is_null($row['fm_dis_motora'])? $row['fm_dis_motora']:'' ;
                $fm_dis_otra= !is_null($row['fm_dis_otra'])? $row['fm_dis_otra']:'' ;
                $dx_psicologico= !is_null($row['dx_psicologico'])? $row['dx_psicologico']:'' ;
                $dx_psicologico_tm= !is_null($row['dx_psicologico_tm'])? $row['dx_psicologico_tm']:'' ;
                $dx_medico= !is_null($row['dx_medico'])? $row['dx_medico']:'' ;
                $dx_medico_tm= !is_null($row['dx_medico_tm'])? $row['dx_medico_tm']:'' ;
            }
            echo '<h3 class="head text-center">FICHA DE INDENTIFICACIÓN DEL ALUMNO TUTORADO</h3>
                    <div class="panel-group" id="salud">
                        <div class="faqHeader">Datos de Salud</div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#enfermedad">Padeces alguna enfermedad:</a>
                                </h4>
                            </div>
                            <div id="enfermedad" class="panel-collapse">
                                <div class="panel-body">
                                    <div class="row form-group bs-float-label">
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox1"> <input type="checkbox" name="fm_diabetes" id="checkbox1" value="1"  '.($fm_diabetes ==1? 'checked':'').'>  Diabetes </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox2"> <input type="checkbox" name="fm_hipertencion" id="checkbox2" value="1" '.($fm_hipertencion ==1? 'checked':'').'>  Hipertension </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox3"> <input type="checkbox" name="fm_epilepsia" id="checkbox3" value="1"  '.($fm_epilepsia ==1? 'checked':'').'>  Epilepsía </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox4"> <input type="checkbox" name="fm_anorexia" id="checkbox4" value="1"  '.($fm_anorexia ==1? 'checked':'').'>  Anorexia </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox5"> <input type="checkbox" name="fm_bulimia" id="checkbox5" value="1" '.($fm_bulimia ==1? 'checked':'').'> Bulimia </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox6"> <input type="checkbox" name="fm_trans_sexual" id="checkbox6" value="1"  '.($fm_trans_sexual ==1? 'checked':'').'> Enfermedad de Transmisión Sexual </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox7"> <input type="checkbox" name="fm_depresion" id="checkbox7" value="1"  '.($fm_depresion ==1? 'checked':'').'> Depresión </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox8"> <input  type="checkbox" name="fm_tristesa" id="checkbox8" value="1"  '.($fm_tristesa ==1? 'checked':'').'> Tristeza Profunda </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox9"> <input type="checkbox" name="otra" id="checkbox9enf" value="otra" onchange="eneableDeseable(\'checkbox9enf\', \'fm_otra\')" '.($fm_otra==1? 'checked':'').'>  Alguna otra </label>
                                            <input class="form-control float-input" type="text" name="fm_otra" id="fm_otra" value="'.$fm_otra. '" ' . ($fm_otra!= 1 ? 'readonly' : '') . '>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#discapacidad">¿ Cuentas con alguna discapacidad fisica ?</a>
                                </h4>
                            </div>
                            <div id="discapacidad" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">';
                                if ($fm_discapacidad==1) {
                                    echo '<div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="fm_discapacidad" id="discRadio1" value="1" checked> Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="fm_discapacidad" id="discRadio2" value="0" required> No
                                            </label>
                                        </div>
                                        <div class="col-sm-9">Indica cual es:
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck1"> <input type="checkbox" name="fm_dis_vista" id="discCheck1" value="1" '.($row['fm_dis_vista']==1? 'checked': '').'>  Vista </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck2"> <input type="checkbox" name="fm_dis_oido" id="discCheck2" value="1" '.($row['fm_dis_oido']==1? 'checked': ''). '>  Oido </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck3"> <input type="checkbox" name="fm_dis_lenguaje" id="discCheck3" value="1" '.($row['fm_dis_lenguaje']==1? 'checked': ''). '>  Lenguaje </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck4"> <input type="checkbox" name="fm_dis_motora" id="discCheck4" value="1" ' . ($row['fm_dis_motora'] == 1 ? 'checked' : '') . '>  Motora </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck4"> <input type="checkbox" name="fm_dis_otra" id="discCheck4" value="1" '.(!is_null($row['fm_dis_otra'])? 'checked': ''). '>  Otro </label>
                                                    <input class="form-control float-input" type="text"  placeholder="Otra discapacidad fisica" name ="fm_dis_otra" '.(is_null($row['fm_otra'])? ("value='".$row['fm_dis_otra']."'"): '').'>
                                                </div>
                                            </div>
                                        </div>';
                                }
                                else {
                                    echo '<div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="fm_discapacidad" id="discRadio1" value="1" onclick="$(\'#trabtext\').attr(\'readonly\', false)" > Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="fm_discapacidad" id="discRadio2" value="0" onclick="$(\'#trabtext\').attr(\'readonly\',true)" checked required> No
                                            </label>
                                        </div>
                                        <div class="col-sm-9">Indica cual es:
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio1"> <input type="checkbox" name="fm_dis_vista" id="inlineRadio1" value="1">  Vista </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio2"> <input type="checkbox" name="fm_dis_oido" id="inlineRadio2" value="1">  Oido </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio3"> <input type="checkbox" name="fm_dis_lenguaje" id="inlineRadio3" value="1">  Lenguaje </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck4"> <input type="checkbox" name="fm_dis_motora" id="discCheck4" value="1" ' . ($fm_dis_motora == 1 ? 'checked' : '') . '>  Motora </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio4"> <input type="checkbox" name="inlineRadioOptions" id="inlineRadio4" value="1">  Otro </label>
                                                    <input class="form-control float-input" type="text" name ="fm_dis_otra" value="" placeholder="Otra discapacidad fisica" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>';
                                }

                                echo   '</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#clinico">¿ Cuentas con algun diagnostico clinico ?</a>
                                </h4>
                            </div>
                            <div id="clinico" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input type="radio" name="dx_psicologico" id="inlineRadio1" value="1" onclick="$(\'#clin\').attr(\'readonly\', false);$(\'#clinT\').attr(\'readonly\', false)" ' . ($dx_psicologico=="" ? '' : 'checked') . '> Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="dx_psicologico" id="inlineRadio2" value="2" onclick="$(\'#clin\').attr(\'readonly\',true);$(\'#clinT\').attr(\'readonly\',true)" ' . ($dx_psicologico=="" ? 'checked' : '') . '> No
                                            </label>
                                        </div>
                                        <div class="col-sm-4">¿Cuál?
                                            <input class="form-control float-input" type="text" name="dx_psicologico" value="'.$dx_psicologico.'" placeholder="¿Cúal?" readonly="readonly" id="clin">
                                        </div>
                                        <div class="col-sm-4">Hace cuanto:
                                            <input class="form-control float-input" type="text" name="dx_psicologico_tm" value="'.$dx_psicologico_tm.'" placeholder="Hace cuanto:" readonly="readonly" id="clinT">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#Medico">¿ Cuentas con algun diagnostico Medico ?</a>
                                </h4>
                            </div>
                            <div id="Medico" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input type="radio" name="medRadio" id="medinlineRad1" value="1" onclick="$(\'#med\').attr(\'readonly\', false);$(\'#medT\').attr(\'readonly\', false)" ' . ($dx_medico=="" ? '' : 'checked') . '> Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="medRadio" id="medinlineRad2" value="2" onclick="$(\'#med\').attr(\'readonly\',true);$(\'#medT\').attr(\'readonly\',true)" ' . ($dx_medico=="" ? 'checked' : '') . '> No
                                            </label>
                                        </div>
                                        <div class="col-sm-4">¿Cuál?
                                            <input class="form-control float-input" type="text" name="dx_medico" value="'.$dx_medico.'" placeholder="Otra discapacidad fisica" readonly="readonly" id="med">
                                        </div>
                                        <div class="col-sm-4">Hace cuanto:
                                            <input class="form-control float-input" type="text" name="dx_medico_tm" value="'.$dx_medico_tm.'" placeholder="Otra discapacidad fisica" readonly="readonly" id="medT">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';


        }

        public function aluFiFamilia($noCon){
            $this->_sql = "SELECT fi_fa_id, no_control, fi_fa_nombre, fi_fa_fecnac, fi_fa_sex, fi_fa_escolaridad, fi_fa_parentesco, fi_fa_actitud FROM fiden_familiares WHERE no_control = '$noCon'";
            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
            }
            if ($resultado->num_rows > 0) {
                echo '
                    <h3 class="head text-center">ÁREAS DE INTEGRACIÓN Y FAMILIAR</h3>
                    <div class="panel-group" id="familia">
                        <div class="faqHeader">DatosFamiliares</div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapse in" data-toggle="collapse" data-parent="#familia" href="#tableFam">
                                        Nombra a los integrantes de tu familia (Mamá, Papá, Hermanos, del mayor al menor incluyendote a ti).
                                    </a>
                                </h4>
                            </div>
                            <div id="tableFam" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        Nombre
                                                    </th>
                                                    <th class="text-center">
                                                        Fecha de Nacimiento
                                                    </th>
                                                    <th class="text-center">
                                                        Sexo
                                                    </th>
                                                    <th class="text-center">
                                                        Escolaridad
                                                    </th>
                                                    <th class="text-center">
                                                        Parentesco
                                                    </th>
                                                    <th class="text-center">
                                                        Actitud con el/ella
                                                    </th>
                                                    <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id=\'addr0\' data-id="0" class="hidden">
                                                    <td data-name="namef">
                                                        <input type="text" name=\'namef[]\' placeholder=\'Nombre completo\' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="nacf">
                                                        <input type="date" name=\'nacf[]\' placeholder=\'\' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="sexf" >
                                                        <select name="sexf[]" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="H">Hombre</option>
                                                            <option value="M">Mujer</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="escf">
                                                        <select name="escf[]" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="1">Sin escolaridad</option>
                                                            <option value="2">Primaria incompleta</option>
                                                            <option value="3">Primaria completa</option>
                                                            <option value="4">Secundaria incompleta</option>
                                                            <option value="5">Secundaria completa</option>
                                                            <option value="6">Media Superior</option>
                                                            <option value="7">Superior</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="parefam">
                                                        <select name="parefam[]" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="11">Yo</option>
                                                            <option value="1">Madre</option>
                                                            <option value="2">Padre</option>
                                                            <option value="3">Abuela</option>
                                                            <option value="4">Abuelo</option>
                                                            <option value="5">Hermana</option>
                                                            <option value="6">Hermano</option>
                                                            <option value="7">Tía</option>
                                                            <option value="8">Tío</option>
                                                            <option value="9">Yo</option>
                                                            <option value="10">Otro</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="actf">
                                                        <input type="text" name=\'actf[]\' placeholder=\'Actitud\' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="del">
                                                        <button name="del[]" class=\'btn btn-danger glyphicon glyphicon-remove row-remove\' type="button" ></button>
                                                    </td>
                                                </tr>';
                                                        $n=1;
                                                        while ($row = $resultado->fetch_assoc()) {
                                                            echo '<tr id="addr'.$n.'" data-id="'.$n. '">
                                                                <td data-name="name">
                                                                    <input name="namef[]" placeholder="Nombre completo" class="form-control float-input" type="text" value="'.$row['fi_fa_nombre'].'">
                                                                </td>
                                                                <td data-name="nac">
                                                                    <input name="nacf[]" placeholder="" class="form-control float-input" type="date" value="'.$row['fi_fa_fecnac'].'">
                                                                </td>
                                                                <td data-name="sex">
                                                                    <select name="sexf[]" class="form-control float-input">';
                                                            $sexfa=array( array('value' => "H", 'text' =>"Hombre"),
                                                                        array('value' => "M", 'text' =>"Mujer")
                                                            );
                                                            foreach ($sexfa as $value) {
                                                                if ($value['value']==$row['fi_fa_sex']) {
                                                                    echo "<option selected value='".$value['value']."'>".$value['text']."</option>";
                                                                }else {
                                                                    echo "<option value='".$value['value']."'>".$value['text']."</option>";
                                                                }
                                                            }
                                                            unset($value);
                                                            echo   '</select>
                                                                </td>
                                                                <td data-name="esc">
                                                                    <select name="escf[]" class="form-control float-input">';
                                                            $edufa=array( array('value' => "1", 'text' =>"Sin escolaridad"),
                                                                        array('value' => "2", 'text' =>"Primaria incompleta"),
                                                                        array('value' => "3", 'text' =>"Primaria completa"),
                                                                        array('value' => "4", 'text' =>"Secundaria incompleta"),
                                                                        array('value' => "5", 'text' =>"Secundaria completa"),
                                                                        array('value' => "6", 'text' =>"Medio Superior"),
                                                                        array('value' => "7", 'text' =>"Superior"),
                                                                        array('value' => "7", 'text' =>"Maestria"),
                                                                        array('value' => "7", 'text' =>"Doctorado"),
                                                            );
                                                            foreach ($edufa as $value) {
                                                                if ($value['value']==$row['fi_fa_escolaridad']) {
                                                                    echo "<option selected value='".$value['value']."'>".$value['text']."</option>";
                                                                }else {
                                                                    echo "<option value='".$value['value']."'>".$value['text']."</option>";
                                                                }
                                                            }
                                                            unset($value);
                                                            echo '</select>
                                                                </td>
                                                                <td data-name="pare">
                                                                    <select name="parefam[]" class="form-control float-input">';
                                                            $edufa=array( array('value' => "11", 'text' =>"Yo"),
                                                                        array('value' => "1", 'text' =>"Madre"),
                                                                        array('value' => "2", 'text' =>"Padre"),
                                                                        array('value' => "3", 'text' =>"Abuela"),
                                                                        array('value' => "4", 'text' =>"Abuelo"),
                                                                        array('value' => "5", 'text' =>"Hermana"),
                                                                        array('value' => "6", 'text' =>"Hermano"),
                                                                        array('value' => "7", 'text' =>"Tía"),
                                                                        array('value' => "8", 'text' =>"Tío"),
                                                                        array('value' => "9", 'text' =>"Otro")
                                                            );
                                                            foreach ($edufa as $value) {
                                                                if ($value['value']==$row['fi_fa_parentesco']) {
                                                                    echo "<option selected value='".$value['value']."'>".$value['text']."</option>";
                                                                }else {
                                                                    echo "<option value='".$value['value']."'>".$value['text']."</option>";
                                                                }
                                                            }
                                                            unset($value);
                                                            echo   '</select>
                                                                </td>
                                                                <td data-name="act">
                                                                    <input name="actf[]" placeholder="Actitud" class="form-control float-input" type="text" value="'.$row['fi_fa_actitud'].'">
                                                                </td>
                                                                <td data-name="del">
                                                                    <button name="del[]" class="btn btn-danger glyphicon glyphicon-remove row-remove" type="button" value="" onClick="$(\'#addr'.$n.'\').remove()"></button>
                                                                </td></tr>';
                                                                $n++;
                                                            }
                                    echo '  </tbody>
                                        </table>
                                    </div>
                                    <a id="add_row" class="btn btn-default pull-right">Añadir otro</a>
                                </div>
                            </div>
                        </div>';
            }
            else{
                echo '
                    <h3 class="head text-center">ÁREAS DE INTEGRACIÓN Y FAMILIAR</h3>
                    <div class="panel-group" id="familia">
                        <div class="faqHeader">DatosFamiliares</div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapse in" data-toggle="collapse" data-parent="#familia" href="#tableFam">
                                        Nombra a los integrantes de tu familia (Mamá, Papá, Hermanos, del mayor al menor incluyendote a ti).
                                    </a>
                                </h4>
                            </div>
                            <div id="tableFam" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        Nombre
                                                    </th>
                                                    <th class="text-center">
                                                        Fecha de Nacimiento
                                                    </th>
                                                    <th class="text-center">
                                                        Sexo
                                                    </th>
                                                    <th class="text-center">
                                                        Escolaridad
                                                    </th>
                                                    <th class="text-center">
                                                        Parentesco
                                                    </th>
                                                    <th class="text-center">
                                                        Actitud con el/ella
                                                    </th>
                                                    <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id=\'addr0\' data-id="0" class="hidden">
                                                    <td data-name="namef">
                                                        <input type="text" name=\'namef0\' placeholder=\'Nombre completo\' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="nacf">
                                                        <input type="date" name=\'nacf0\' placeholder=\'\' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="sexf" >
                                                        <select name="sexf0" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="H">Hombre</option>
                                                            <option value="M">Mujer</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="escf">
                                                        <select name="escf0" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="1">Sin escolaridad</option>
                                                            <option value="2">Primaria incompleta</option>
                                                            <option value="3">Primaria completa</option>
                                                            <option value="4">Secundaria incompleta</option>
                                                            <option value="5">Secundaria completa</option>
                                                            <option value="6">Media Superior</option>
                                                            <option value="7">Superior</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="parefam">
                                                        <select name="parefam0" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="11">Yo</option>
                                                            <option value="1">Madre</option>
                                                            <option value="2">Padre</option>
                                                            <option value="3">Abuela</option>
                                                            <option value="4">Abuelo</option>
                                                            <option value="5">Hermana</option>
                                                            <option value="6">Hermano</option>
                                                            <option value="7">Tía</option>
                                                            <option value="8">Tío</option>
                                                            <option value="9">Otro</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="actf">
                                                        <input type="text" name=\'actf0\' placeholder=\'Actitud\' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="del">
                                                        <button name="del0" class=\'btn btn-danger glyphicon glyphicon-remove row-remove\' type="button"></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a id="add_row" class="btn btn-default pull-right">Añadir otro</a>
                                </div>
                            </div>
                        </div>';
            }
            unset($resultado);
            unset($row);

            $this->_sql="SELECT no_control, fiden_relfa, fiden_dificultades, fiden_actfa, fiden_ligue, fiden_ligue_T, fiden_edu, fiden_influ, fiden_otro_dato FROM fiden_integracion WHERE no_control = '$noCon'";
            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.I
                echo "Lo sentimos, este sitio web está experimentando problemas.";
                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
            }
            $fiden_relfa =""; $fiden_dificultades =""; $fiden_actfa =""; $fiden_ligue =""; $fiden_ligue_T =""; $fiden_edu =""; $fiden_influ =""; $fiden_otro_dato ="";

            if ($resultado->num_rows > 0) {
                $row=$resultado->fetch_assoc();
                $fiden_relfa =!is_null($row['fiden_relfa'])? $row['fiden_relfa']:'' ;
                $fiden_dificultades =!is_null($row['fiden_dificultades'])? $row['fiden_dificultades']:'' ;
                $fiden_actfa =!is_null($row['fiden_actfa'])? $row['fiden_actfa']:'' ;
                $fiden_ligue =!is_null($row['fiden_ligue'])? $row['fiden_ligue']:'' ;
                $fiden_ligue_T =!is_null($row['fiden_ligue_T'])? $row['fiden_ligue_T']:'' ;
                $fiden_edu =!is_null($row['fiden_edu'])? $row['fiden_edu']:'' ;
                $fiden_influ =!is_null($row['fiden_influ'])? $row['fiden_influ']:'' ;
                $fiden_otro_dato =!is_null($row['fiden_otro_dato'])? $row['fiden_otro_dato']:'' ;
            }
            echo '
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam1">¿Comó es la relación con tu familia?</a>
                            </h4>
                        </div>
                        <div id="fam1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea name="fiden_relfa" placeholder="" class="form-control float-input" id="fiden_relfa" required> '.$fiden_relfa.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam2">¿Existen dificultades?</a>
                            </h4>
                        </div>
                        <div id="fam2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input type="radio" name="dif" id="inlineRadio1" value="1" onclick="$(\'#fiden_dificultades\').attr(\'readonly\', false)" '. ($fiden_dificultades =="" ? '' : 'checked') .'> Si
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="dif" id="inlineRadio2" value="2" onclick="$(\'#fiden_dificultades\').attr(\'readonly\',true)" '. ($fiden_dificultades !="" ? '' : 'checked') .' required> No
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="fiden_dificultades">¿De qué tipo?</label>
                                            <textarea name="fiden_dificultades" placeholder="" class="form-control float-input" id="fiden_dificultades" '.($fiden_dificultades!=""? $fiden_dificultades: '').'>'.$fiden_dificultades .'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam3">¿Qué actitud tienes con tu familia?</a>
                            </h4>
                        </div>
                        <div id="fam3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="fiden_actfa" name="fiden_actfa" placeholder="" class="form-control float-input" required>'.$fiden_actfa. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam4">¿Con quien te sientes mas ligado afectivamente</a>
                            </h4>
                        </div>
                        <div id="fam4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <select  id="fiden_ligue" name="fiden_ligue" class="form-control float-input" required>
                                                <option  '.($fiden_ligue==""?'selected':'').' disabled> Selecciona una opcion</option>';
                                                $edufa = array(
                                                    array('value' => "1", 'text' => "Madre"),
                                                    array('value' => "2", 'text' => "Padre"),
                                                    array('value' => "3", 'text' => "Abuela"),
                                                    array('value' => "4", 'text' => "Abuelo"),
                                                    array('value' => "5", 'text' => "Hermana"),
                                                    array('value' => "6", 'text' => "Hermano"),
                                                    array('value' => "7", 'text' => "Tía"),
                                                    array('value' => "8", 'text' => "Tío"),
                                                    array('value' => "9", 'text' => "Otro")
                                                );
                                                foreach ($edufa as $value) {
                                                    if ($value['value'] == $fiden_ligue) {
                                                        echo "<option selected value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                    } else {
                                                        echo "<option value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                    }
                                                }

                            echo '           </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group bs-float-label">
                                                <label for="fiden_ligue_T">Especifica por qué</label>
                                                <textarea id="fiden_ligue_T" name="fiden_ligue_T" placeholder="" class="form-control float-input" required>'.$fiden_ligue_T.'</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam5">¿Quién se ocupa más en tu educación?</a>
                            </h4>
                        </div>
                        <div id="fam5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="fiden_edu" name="fiden_edu" placeholder="" class="form-control float-input" required>'.$fiden_edu. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam6">¿Quién ha influido más en tu decisión para estudiar esta carrera?</a>
                            </h4>
                        </div>
                        <div id="fam6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="fiden_influ" name="fiden_influ" placeholder="" class="form-control float-input" required>'.$fiden_influ. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam7">Consideras importante facilitar algún otro dato sobre tu ambiente familiar</a>
                            </h4>
                        </div>
                        <div id="fam7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea name="fiden_otro_dato" placeholder="" class="form-control float-input">'.$fiden_otro_dato. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        public function aluFiSocial($noCon){
            $this->_sql="SELECT no_control, rel_comp, rel_comp_t, rel_ami, rel_ami_t, alu_par, rel_alu_par, rel_alu_par_t, rel_pro, rel_pro_t, rel_aut_aca, rel_aut_aca_t, alu_tlibre, alu_act_rec, alu_pl_inme, alu_metas, alu_soy, alu_caracter, alu_gusto, alu_aspira, alu_miedo FROM fiden_social WHERE no_control = '$noCon' ";
            if (!$resultado=$this->_db->query($this->_sql)) {
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
                // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
                // cómo obtener información del error
                echo "Error: La ejecución de la consulta falló debido a: \n";
                echo "Query: " . $this->_sql . "\n";
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
            }
            $rel_comp=""; $rel_comp_t=""; $rel_ami=""; $rel_ami_t=""; $alu_par=""; $rel_alu_par=""; $rel_alu_par_t=""; $rel_pro=""; $rel_pro_t=""; $rel_aut_aca=""; $rel_aut_aca_t=""; $alu_tlibre=""; $alu_act_rec=""; $alu_pl_inme=""; $alu_metas=""; $alu_soy=""; $alu_caracter=""; $alu_gusto=""; $alu_aspira=""; $alu_miedo="";
            if ($resultado->num_rows > 0) {
                $row= $resultado->fetch_assoc();
                $rel_comp=!is_null($row['rel_comp'])? $row['rel_comp']:'' ;
                $rel_comp_t=!is_null($row['rel_comp_t'])? $row['rel_comp_t']:'' ;
                $rel_ami=!is_null($row['rel_ami'])? $row['rel_ami']:'' ;
                $rel_ami_t=!is_null($row['rel_ami_t'])? $row['rel_ami_t']:'' ;
                $alu_par=!is_null($row['alu_par'])? $row['alu_par']:'' ;
                $rel_alu_par=!is_null($row['rel_alu_par'])? $row['rel_alu_par']:'' ;
                $rel_alu_par_t=!is_null($row['rel_alu_par_t'])? $row['rel_alu_par_t']:'' ;
                $rel_pro=!is_null($row['rel_pro'])? $row['rel_pro']:'' ;
                $rel_pro_t=!is_null($row['rel_pro_t'])? $row['rel_pro_t']:'' ;
                $rel_aut_aca=!is_null($row['rel_aut_aca'])? $row['rel_aut_aca']:'' ;
                $rel_aut_aca_t=!is_null($row['rel_aut_aca_t'])? $row['rel_aut_aca_t']:'' ;
                $alu_tlibre=!is_null($row['alu_tlibre'])? $row['alu_tlibre']:'' ;
                $alu_act_rec=!is_null($row['alu_act_rec'])? $row['alu_act_rec']:'' ;
                $alu_pl_inme=!is_null($row['alu_pl_inme'])? $row['alu_pl_inme']:'' ;
                $alu_metas=!is_null($row['alu_metas'])? $row['alu_metas']:'' ;
                $alu_soy=!is_null($row['alu_soy'])? $row['alu_soy']:'' ;
                $alu_caracter=!is_null($row['alu_caracter'])? $row['alu_caracter']:'' ;
                $alu_gusto=!is_null($row['alu_gusto'])? $row['alu_gusto']:'' ;
                $alu_aspira=!is_null($row['alu_aspira'])? $row['alu_aspira']:'' ;
                $alu_miedo=!is_null($row['alu_miedo'])? $row['alu_miedo']:'' ;
            }
            echo '<h3 class="head text-center">ÁREAS PERSONAL Y SOCIAL </h3>
                <div class="panel-group" id="social">
                    <div class="faqHeader">Datos sobre tu vida social</div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapse in" data-toggle="collapse" data-parent="#social" href="#soc0">
                                    ¿Cómo es tu relacion con tus compañeros?
                                </a>
                            </h4>
                        </div>
                        <div id="soc0" class="panel-collapse collapsed in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select id="rel_comp" name="rel_comp" class="form-control float-input" required>';
                                            $calRel = array(
                                                array('value' => "", 'text' => "Selecciona una opcion"),
                                                array('value' => "1", 'text' => "Buena"),
                                                array('value' => "2", 'text' => "Regular"),
                                                array('value' => "3", 'text' => "Excelente"),
                                                array('value' => "4", 'text' => "Mala")
                                            );
                                            foreach ($calRel as $value) {
                                                if ($value['value'] == $rel_comp) {
                                                    echo "<option selected value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                }
                                            }
                                unset($value);
                                echo '  </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_com_t">¿Por qué?</label>
                                            <textarea id="rel_comp_t" name="rel_comp_t" placeholder="" class="form-control float-input" >'.$rel_comp_t. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc1">¿Comó es la relación con tus amigos?</a>
                            </h4>
                        </div>
                        <div id="soc1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select id="rel_ami" name="rel_ami" class="form-control float-input">';
                                            $calRel = array(
                                                array('value' => "", 'text' => "Seleccione una opcion"),
                                                array('value' => "1", 'text' => "Buena"),
                                                array('value' => "2", 'text' => "Regular"),
                                                array('value' => "3", 'text' => "Excelente")
                                            );
                                            foreach ($calRel as $value) {
                                                if ($value['value'] == $rel_ami) {
                                                    echo "<option selected value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                }
                                            }
                                unset($value);
                                echo '  </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_ami_t">¿Por qué?</label>
                                            <textarea id="rel_ami_t" name="rel_ami_t" placeholder="" class="form-control float-input">'. $rel_ami_t . '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc2">¿Tienes pareja?</a>
                            </h4>
                        </div>
                        <div id="soc2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="alu_par" id="alu_par1" value="1" onclick="$(\'#rel_alu_par\').attr(\'readonly\', false);$(\'#parejaText\').attr(\'readonly\', false); " '.( $alu_par==1?'':'checked'). '> SI
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="alu_par" id="alu_par2" value="0" onclick="$(\'#rel_alu_par\').attr(\'readonly\',true);$(\'#parejaText\').attr(\'readonly\',true);" ' . (is_null($alu_par) || $alu_par==0 ? 'checked' : '') . '> NO
                                        </label>
                                    </div>
                                    <div class="col-sm-5">
                                        <select name="rel_alu_par" id="rel_alu_par" class="form-control float-input" '.(is_null($alu_par)?'readonly':'').'>';
                                            $calRel = array(
                                                array('value' => "", 'text' => "Selecciona una opcion"),
                                                array('value' => "1", 'text' => "Buena"),
                                                array('value' => "2", 'text' => "Regular"),
                                                array('value' => "3", 'text' => "Excelente")
                                            );
                                            foreach ($calRel as $value) {
                                                if ($value['value'] == $rel_alu_par) {
                                                    echo "<option selected value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                }
                                            }
                                            unset($value);
                                echo '  </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group bs-float-label">
                                            <label for="parejaText">¿Comó es la relación con tu pareja?</label>
                                            <textarea id="parejaText" name="rel_alu_par_t" placeholder="" class="form-control float-input" ' . (is_null($alu_par) ? 'readonly' : '') . '>'.$rel_alu_par_t. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc3">¿Como es la relación con tus profesores?</a>
                            </h4>
                        </div>
                        <div id="soc3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select  id="rel_pro" name="rel_pro" class="form-control float-input" >';
                                            $calRel = array(
                                                array('value' => "", 'text' => "Selecciona una opcion"),
                                                array('value' => "1", 'text' => "Buena"),
                                                array('value' => "2", 'text' => "Regular"),
                                                array('value' => "3", 'text' => "Excelente")
                                            );
                                            foreach ($calRel as $value) {
                                                if ($value['value'] == $rel_pro) {
                                                    echo "<option selected value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                }
                                            }
                                            unset($value);
                                echo '  </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_pro_t">¿Por qué?</label>
                                            <textarea id="rel_pro_t" name="rel_pro_t" placeholder="" class="form-control float-input">'.$rel_pro_t.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc4">¿Comó es tu relación con las autoridades académicas\'</a>
                            </h4>
                        </div>
                        <div id="soc4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select name="rel_aut_aca" class="form-control float-input"  id="rel_aut_aca">';
                                            $calRel = array(
                                                array('value' => "", 'text' => "Selecciona una opcion"),
                                                array('value' => "1", 'text' => "Buena"),
                                                array('value' => "2", 'text' => "Regular"),
                                                array('value' => "3", 'text' => "Excelente")
                                            );
                                            foreach ($calRel as $value) {
                                                if ($value['value'] == $rel_aut_aca) {
                                                    echo "<option selected value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $value['value'] . "'>" . $value['text'] . "</option>";
                                                }
                                            }
                                            unset($value);
                                echo '  </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_aut_aca_t">¿Por qué?</label>
                                            <textarea id="rel_aut_aca_t" name="rel_aut_aca_t" placeholder="" class="form-control float-input">'.$rel_aut_aca_t.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc5">¿Qué haces en tu tiempo libre?</a>
                            </h4>
                        </div>
                        <div id="soc5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_tlibre" name="alu_tlibre" placeholder="" class="form-control float-input" required>'.$alu_tlibre.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc6">¿Cuál es tu actividad recreativa?</a>
                            </h4>
                        </div>
                        <div id="soc6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_act_rec" name="alu_act_rec" placeholder="" class="form-control float-input" required>'.$alu_act_rec.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc7">¿Cuáles son tus planes inmediatos?</a>
                            </h4>
                        </div>
                        <div id="soc7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_pl_inme" name="alu_pl_inme" placeholder="" class="form-control float-input" required>'.$alu_pl_inme. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc8">¿Cuáles son tus metas en la vida?</a>
                            </h4>
                        </div>
                        <div id="soc8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_metas" name="alu_metas" placeholder="" class="form-control float-input" required>' . $alu_metas . '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc9">YO SOY...</a>
                            </h4>
                        </div>
                        <div id="soc9" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_soy" name="alu_soy" placeholder="" class="form-control float-input" required>' . $alu_soy . '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc10">MI CARÁCTER ES...?</a>
                            </h4>
                        </div>
                        <div id="soc10" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_caracter" name="alu_caracter" placeholder="" class="form-control float-input" required>'.$alu_caracter.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc11">A MÍ ME GUSTA QUE..</a>
                            </h4>
                        </div>
                        <div id="soc11" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_gusto" name="alu_gusto" placeholder="" class="form-control float-input" required>'.$alu_gusto. '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc12">YO ASPIRO EN LA VIDA...</a>
                            </h4>
                        </div>
                        <div id="soc12" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_aspira" name="alu_aspira" placeholder="" class="form-control float-input" required>' . $alu_aspira . '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc13">YO TENGO MIEDO QUE...</a>
                            </h4>
                        </div>
                        <div id="soc13" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_miedo" name="alu_miedo" placeholder="" class="form-control float-input" required>' . $alu_miedo . '</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }

        public function saveFichaAlu($noCon){
            //var_dump($_POST);


            $img = $_SESSION['usuario']['img'];
            $pass = $_SESSION['usuario']['pd'];
            $set = isset($pass)? 1: 'null';
            $ret="";

            #Caracterizacion
            $carac = "REPLACE INTO sta.alumnos_caracterizacion(se_no_control, dp_nombre, dp_ap_paterno, dp_ap_materno, dp_sexo, dp_edad, dp_email, dp_carrera, dp_grupo, dp_tel, dp_tipo_sangre, fm_talla, fm_peso, dp_pass_cambio, dp_contrasena, al_img)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $caracterizacion =false;
            $nom = $this->_db->real_escape_string( trim($_POST['dp_nombre'] ) ); $ap = $this->_db->real_escape_string( trim($_POST['dp_ap_paterno'] ) ); $am = $this->_db->real_escape_string( trim($_POST['dp_ap_materno'] ) ); $edad = $this->_db->real_escape_string(trim($_POST['dp_edad'])); $talla = $this->_db->real_escape_string(trim($_POST['fm_talla'])); $peso = $this->_db->real_escape_string(trim($_POST['fm_peso'])); $sex = $this->_db->real_escape_string(trim($_POST['dp_sexo'])); $car = $this->_db->real_escape_string(trim($_POST['dp_carrera'])); $gpo = $this->_db->real_escape_string(trim($_POST['dp_grupo'])); $sangre = $this->_db->real_escape_string(trim($_POST['dp_tipo_sangre'])); $email = $this->_db->real_escape_string(trim($_POST['dp_email'])); $tel = $this->_db->real_escape_string(trim($_POST['dp_tel']));
            if ($caracq = $this->_db->prepare($carac)) {
                $caracq->bind_param('sssssisssisddiss', $noCon, $nom, $ap, $am, $sex, $edad, $email, $car, $gpo, $tel, $sangre, $talla, $peso, $set, $pass, $img );
                $caracq->execute();
                if($this->_db->affected_rows > 0){
                    $caracterizacion = true;
                }else{
                $caracterizacion = true;
                }
            }
            else{
                echo json_encode(['error' => true, 'text' => $this->_db->error . ' ' . $this->_db->errno, 'preparecara' => "REPLACE INTO sta.alumnos_caracterizacion(se_no_control, dp_nombre, dp_ap_paterno, dp_ap_materno, dp_sexo, dp_edad, dp_email, dp_carrera, dp_tel, dp_tipo_sangre, fm_talla, fm_peso)
                VALUES ('$noCon', '$nom', '$ap', '$am', '$sex', '$edad', '$email', '$car', '$sangre', '$tel', '$talla', '$peso')"]);
                exit;
                }

            #padres
            #
            $dp_nom_padre = $this->_db->real_escape_string( trim($_POST['nomPa']));
            $dp_apep_padre = $this->_db->real_escape_string( trim($_POST['apPa']));
            $dp_apem_padre = $this->_db->real_escape_string( trim($_POST['amPa']));
            $dp_edad_padre = $this->_db->real_escape_string( trim($_POST['EdadPa']));
            $dp_tel_padre = $this->_db->real_escape_string( trim($_POST['TelPa']));
            $dp_trabajo_padre = $this->_db->real_escape_string( trim($_POST['trabPtext']));
            $dp_profecion_padre = $this->_db->real_escape_string( trim($_POST['ProfPa']));
            $dp_dom_padre = $this->_db->real_escape_string( trim($_POST['DomPa']));
            $dp_nom_madre = $this->_db->real_escape_string( trim($_POST['nomMa']));
            $dp_apep_madre = $this->_db->real_escape_string( trim($_POST['apMa']));
            $dp_apem_madre = $this->_db->real_escape_string( trim($_POST['amMa']));
            $dp_edad_madre = $this->_db->real_escape_string( trim($_POST['EdadMa']));
            $dp_tel_madre = $this->_db->real_escape_string( trim($_POST['TelPa']));
            $dp_trabajo_madre = $this->_db->real_escape_string( trim($_POST['trabMtext']));
            $dp_profecion_madre = $this->_db->real_escape_string( trim($_POST['ProfMa']));
            $dp_dom_madre = $this->_db->real_escape_string( trim($_POST['DomMa']));
            $padres = "REPLACE INTO alumnos_datos_padres  (id_alumno, dp_nom_padre, dp_apep_padre, dp_apem_padre, dp_edad_padre, dp_tel_padre, dp_trabajo_padre, dp_profecion_padre, dp_dom_padre, dp_nom_madre, dp_apep_madre, dp_apem_madre, dp_edad_madre, dp_tel_madre, dp_trabajo_madre, dp_profecion_madre, dp_dom_madre) VALUES (
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $padr =false;
            if($padresq = $this->_db->prepare($padres)){
                $padresq->bind_param('sssssisssssssisss', $noCon, $dp_nom_padre, $dp_apep_padre, $dp_apem_padre, $dp_edad_padre, $dp_tel_padre, $dp_trabajo_padre, $dp_profecion_padre, $dp_dom_padre, $dp_nom_madre, $dp_apep_madre, $dp_apem_madre, $dp_edad_madre, $dp_tel_madre, $dp_trabajo_madre, $dp_profecion_madre, $dp_dom_madre );
                $padresq->execute();
                if($this->_db->affected_rows > 0){
                    $padr = true;
                }else{
                    echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno."padres".$noCon ]);
                    exit;
                }
            }
            else{
                echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno."padres"]);
                exit;
            }

            #Ficha perfil
            $edc = $this->_db->real_escape_string( trim($_POST['fip_edo_civil'] ) ); $luna = $this->_db->real_escape_string( trim($_POST['fip_lugar_nac'] ) ); $tr = $this->_db->real_escape_string( trim($_POST['fip_trabajo'] ) );
            $dom = $this->_db->real_escape_string( trim($_POST['fip_domicilio'] ) ); $vit = $this->_db->real_escape_string( trim($_POST['fip_vivienda_tipo'] ) ); $vito = $this->_db->real_escape_string( trim($_POST['fip_vivienda_tipo_otro'] ) );
            $perviv= $this->_db->real_escape_string( trim($_POST['fip_per_vivienda'] ) ); $perpa = $this->_db->real_escape_string( trim($_POST['fip_per_parentesco'] ) ); $relpa = $this->_db->real_escape_string( trim($_POST['fip_rel_padres'] ) );

            $perfil = "REPLACE INTO fiden_perfil ( no_control,
                fip_edo_civil,
                fip_fecha_nac,
                fip_lugar_nac,
                fip_trabajo,
                fip_domicilio,
                fip_vivienda_tipo,
                fip_vivienda_tipo_otro,
                fip_per_vivienda,
                fip_per_parentesco,
                fip_rel_padres)
                VALUES ('$noCon',?,'". $_POST['fip_fecha_nac'] ."',?,?,?,?,?,?,?,?);";
            $perl = false;
            if ($perfilq = $this->_db->prepare($perfil)) {
                $perfilq->bind_param('ssssssiss', $edc,  $luna, $tr, $dom, $vit, $vito,$perviv, $perpa, $relpa);
                $perfilq->execute();
                if($this->_db->affected_rows > 0){
                    $perl = true;
                }else{
                    echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno."perfil"

                    , 'cara'=>$perfil]);
                    exit;
                }
            }
            else{
                    echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno."perfil", 'prepareperfil'=>$perfil]);
                    exit;
                }

            #Medico
            $medic = "REPLACE INTO fiden_medico (no_control, fm_diabetes, fm_hipertencion, fm_epilepsia, fm_anorexia, fm_bulimia,
                fm_trans_sexual, fm_depresion, fm_tristesa, fm_otra, fm_discapacidad, fm_dis_vista, fm_dis_oido, fm_dis_lenguaje,
                fm_dis_motora, fm_dis_otra, dx_psicologico, dx_psicologico_tm, dx_medico, dx_medico_tm)VALUES ('$noCon',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,".(is_null($_POST['dx_psicologico_tm'])?"'".$_POST['dx_psicologico_tm']."'":"null").",?,".(is_null($_POST['dx_medico_tm'])?"'".$_POST['dx_medico_tm']."'":"null").")";
            $medico =false;
            $diabetes = $this->_db->real_escape_string( trim(isset($_POST['fm_diabetes'] ) ?$_POST['fm_diabetes']:0));  $hiper = $this->_db->real_escape_string( trim(isset($_POST['fm_hipertencion'] ) ?$_POST['fm_hipertencion']:0));  $epi = $this->_db->real_escape_string( trim(isset($_POST['fm_epilepsia'] ) ?$_POST['fm_epilepsia']:0));
            $anorex = $this->_db->real_escape_string(trim(isset($_POST['fm_anorexia'])?$_POST['fm_anorexia']:0));  $buli = $this->_db->real_escape_string(trim(isset($_POST['fm_bulimia'])?$_POST['fm_bulimia']:0));
            $tran = $this->_db->real_escape_string(trim(isset($_POST['fm_trans_sexual'])?$_POST['fm_trans_sexual']:0));  $dep = $this->_db->real_escape_string(trim(isset($_POST['fm_depresion'])?$_POST['fm_depresion']:0));  $tris = $this->_db->real_escape_string(trim(isset($_POST['fm_tristesa'])?$_POST['fm_tristesa']:0));  $otra = $this->_db->real_escape_string(trim($_POST['fm_otra']));  $disc = $this->_db->real_escape_string(trim(isset($_POST['fm_discapacidad'])?$_POST['fm_discapacidad']:0));  $vista = $this->_db->real_escape_string(trim(isset($_POST['fm_dis_vista'])?$_POST['fm_dis_vista']:0));  $oido = $this->_db->real_escape_string(trim(isset($_POST['fm_dis_oido'])?$_POST['fm_dis_oido']:0));  $leng = $this->_db->real_escape_string(trim(isset($_POST['fm_dis_lenguaje'])?$_POST['fm_dis_lenguaje']:0));  $motora = $this->_db->real_escape_string(trim(isset($_POST['fm_dis_motora'])?$_POST['fm_dis_motora']:0));  $dotra = $this->_db->real_escape_string(trim($_POST['fm_dis_otra']));  $psi =
            $this->_db->real_escape_string(trim($_POST['dx_psicologico']));  $me = $this->_db->real_escape_string(trim($_POST['dx_medico']));
            if ($medicq = $this->_db->prepare($medic)) {
                $medicq->bind_param('iiiiiiiisiiiiisss',  $diabetes, $hiper, $epi, $anorex, $buli, $tran, $dep, $tris, $otra, $disc, $vista, $oido, $leng, $motora, $dotra,$psi, $me);
                $medicq->execute();
                if($this->_db->affected_rows > 0){
                    $medico = true;
                }else{
                echo json_encode(['error' => true, 'text' => $ret . $this->_db->error . ' ' . $this->_db->errno."medic", 'medic' => $medic]);
                exit;
                }
            }
            else{
                echo json_encode(['error' => true, 'text' => $ret . $this->_db->error . ' ' . $this->_db->errno."medic", 'preparemedic' => $medic]);
                exit;
                }

            #Familia
            $famili = "REPLACE INTO fiden_integracion (no_control, fiden_relfa, fiden_dificultades, fiden_actfa, fiden_ligue, fiden_ligue_T, fiden_edu, fiden_influ, fiden_otro_dato)VALUES ('$noCon',?,?,?,?,?,?,?,?)";
            $fam =false;
            $rfa = $this->_db->real_escape_string( trim($_POST['fiden_relfa'] ) ); $dif = $this->_db->real_escape_string( trim($_POST['fiden_dificultades'] ) ); $actf = $this->_db->real_escape_string( trim($_POST['fiden_actfa'] ) ); $ligue = $this->_db->real_escape_string( trim($_POST['fiden_ligue'] ) ); $ligt = $this->_db->real_escape_string( trim($_POST['fiden_ligue_T'] ) ); $fedu = $this->_db->real_escape_string( trim($_POST['fiden_edu'] ) ); $influ= $this->_db->real_escape_string( trim($_POST['fiden_influ'] ) ); $otrod = $this->_db->real_escape_string( trim($_POST['fiden_otro_dato'] ) );
            if ($familiq = $this->_db->prepare($famili)) {
                $familiq->bind_param('ssssssss', $rfa, $dif, $actf, $ligue, $ligt, $fedu, $influ, $otrod);
                $familiq->execute();
                if($this->_db->affected_rows > 0){
                    $fam = true;
                }else{
                    echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno."famili", 'cfamili'=>$famili]);
                    exit;
                }
            } else {
                echo json_encode(['error' => true, 'text' => $ret . $this->_db->error . ' ' . $this->_db->errno."famili", 'preparefamili' => $famili]);
                exit;
            }

            #Social
            $social = "REPLACE INTO fiden_social (no_control, rel_comp, rel_comp_t, rel_ami, rel_ami_t, alu_par, rel_alu_par, rel_alu_par_t, rel_pro, rel_pro_t, rel_aut_aca, rel_aut_aca_t, alu_tlibre, alu_act_rec, alu_pl_inme, alu_metas, alu_soy, alu_caracter, alu_gusto, alu_aspira, alu_miedo)
                VALUES ('$noCon',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $soci =false;
            $rco = $this->_db->real_escape_string( trim($_POST['rel_comp'] ) );
            $rcot = $this->_db->real_escape_string( trim($_POST['rel_comp_t'] ) );
            $ram = $this->_db->real_escape_string( trim($_POST['rel_ami'] ) );
            $ramt = $this->_db->real_escape_string( trim($_POST['rel_ami_t'] ) );
            $apar = $this->_db->real_escape_string( trim($_POST['alu_par'] ) ); $rpa = $this->_db->real_escape_string( trim($_POST['rel_alu_par'] ) ); $rpat = $this->_db->real_escape_string( trim($_POST['rel_alu_par_t'] ) ); $rpro = $this->_db->real_escape_string( trim($_POST['rel_pro'] ) ); $rprot = $this->_db->real_escape_string( trim($_POST['rel_pro_t'] ) ); $rac = $this->_db->real_escape_string( trim($_POST['rel_aut_aca'] ) ); $ract = $this->_db->real_escape_string( trim($_POST['rel_aut_aca_t'] ) ); $tlib = $this->_db->real_escape_string( trim($_POST['alu_tlibre'] ) ); $actr = $this->_db->real_escape_string( trim($_POST['alu_act_rec'] ) ); $pli = $this->_db->real_escape_string( trim($_POST['alu_pl_inme'] ) ); $metas = $this->_db->real_escape_string( trim($_POST['alu_metas'] ) ); $soy = $this->_db->real_escape_string( trim($_POST['alu_soy'] ) ); $caract = $this->_db->real_escape_string( trim($_POST['alu_caracter'] ) ); $gutst = $this->_db->real_escape_string( trim($_POST['alu_gusto'] ) ); $asp = $this->_db->real_escape_string(trim($_POST['alu_aspira'])); $mied = $this->_db->real_escape_string( trim($_POST['alu_miedo'] ) );
            if ($socialq = $this->_db->prepare($social)) {
                $socialq->bind_param('isisiisisissssssssss', $rco, $rcot, $ram, $ramt, $apar, $rpa, $rpat, $rpro, $rprot, $rac, $ract, $tlib, $actr, $pli, $metas, $soy, $caract, $gutst,$asp, $mied );
                $socialq->execute();
                if($this->_db->affected_rows > 0){
                    $soci = true;
                } else {
                    echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno, 'soci'=>$social]);
                    exit;
                }
            } else {
                    echo json_encode(['error' => true , 'text'=>$ret. $this->_db->error . ' ' . $this->_db->errno, 'preparesocial'=>$social]);
                    exit;
                }
            #familia
            $fam2=false;
            $i=0;
            $this->elimFam($noCon);
            $name=""; $sex=""; $esc=0; $pare = 0; $act=''; $naci='0000-00-00';
            $famili2 = "INSERT INTO fiden_familiares (no_control, fi_fa_nombre, fi_fa_fecnac, fi_fa_sex, fi_fa_escolaridad, fi_fa_parentesco, fi_fa_actitud)
            VALUES (?,?,?,?,?,?,?)";
            if (count($_POST['namef'])>=1) {
                if ($Fami2 = $this->_db->prepare($famili2)) {

                    $Fami2->bind_param('ssssiis',$noCon,$name,$naci,$sex,$esc,$pare,$act);
                    $err = true; $er=0;
                    for ($i=0; $i < count($_POST['namef']); $i++)  {
                        $name = $_POST['namef'][$i];
                        $naci = $_POST['nacf'][$i];
                        $sex = $_POST['sexf'][$i];
                        $esc = $_POST['escf'][$i];
                        $pare = $_POST['parefam'][$i];
                        $act = $_POST['actf'][$i];
                        if (!empty($name)) {
                            if (!$Fami2->execute()) {
                                echo $this->_db->error;
                                $er++;
                            } else {
                                $err = false;
                                $fam2 = true;

                            }
                        }
                    }
                    if ($err == true) {
                        echo json_encode(['error' => true, 'er' => "Error en execute familiar" . $this->_db->error]);
                        $fam2 = false;

                    } elseif($er>0) {
                        echo json_encode(['error' => true, 'er' => "Error en prepare" . $this->_db->errno]);
                        $fam2 = false;

                    }else {
                        $fam2 = true;

                    }
                }
            }
            else{
                    echo json_encode(['error' => true, 'er' => "Error en execute familiar" . $this->_db->error]);

            }

            if ($caracterizacion == true && $padr ==true &&$perl == true && $medic == true && $fam == true && $soci == true && $fam2 == true) {
                echo json_encode(['error' => false]);
            } else {
                echo json_encode(['error' => true, 'text' => $ret . $this->_db->error . ' ' . $this->_db->errno, 'cara' => $carac, 'medic' => $medic, 'per' => $perfil, 'famtext' => $famili, 'soc' => $social]);
            }

        }

        public function printpdf($noCon){
            $alu ='';
            $perfil = "SELECT
                alumnos_caracterizacion.se_no_control,
                alumnos_caracterizacion.dp_nombre,
                alumnos_caracterizacion.dp_ap_paterno,
                alumnos_caracterizacion.dp_ap_materno,
                alumnos_caracterizacion.dp_edad,
                alumnos_caracterizacion.fm_talla,
                alumnos_caracterizacion.fm_peso,
                alumnos_caracterizacion.dp_sexo,
                alumnos_caracterizacion.dp_carrera,
                alumnos_caracterizacion.dp_grupo,
                fiden_perfil.fip_edo_civil,
                alumnos_caracterizacion.dp_tipo_sangre,
                fiden_perfil.fip_fecha_nac,
                fiden_perfil.fip_lugar_nac,
                fiden_perfil.fip_trabajo,
                fiden_perfil.fip_domicilio,
                fiden_perfil.fip_vivienda_tipo,
                fiden_perfil.fip_vivienda_tipo_otro,
                alumnos_caracterizacion.dp_tel,
                alumnos_caracterizacion.dp_email,
                alumnos_caracterizacion.al_img,
                fiden_perfil.fip_per_vivienda,
                fiden_perfil.fip_per_parentesco,
                fiden_perfil.fip_padre,
                fiden_perfil.fip_madre,
                fiden_perfil.fip_rel_padres
                FROM
                alumnos_caracterizacion
                INNER JOIN fiden_perfil ON fiden_perfil.no_control = alumnos_caracterizacion.se_no_control
                WHERE alumnos_caracterizacion.se_no_control= '$noCon'";

            $resultado = $this->_db->query($perfil);
            $row = $resultado->fetch_assoc();
            $nombre= $row['dp_nombre'].' '.$row['dp_ap_paterno'].' '.$row['dp_ap_materno'];
            echo'<!DOCTYPE html>
                    <html lang="en">

                    <head>
                        <meta charset="utf-8">
                        <title>Ficha</title>
                        <link rel="stylesheet" href="style.css" media="all" />
                        <link rel="stylesheet" href="bootstrap.min.css" media="all" />

                    </head>

                    <body>
                        <header class="clearfix" style="width:19cm">
                            <div id="logo">
                                <img src="../assets/images/itsch.png" style="height: 70px;">
                            </div>
                            <div id="company">

                            </div>
                            <div id="titulo">
                                <h2>INSTITUTO TECNOLÓGICO SUPERIOR DE CIUDAD HIDALGO</h2>
                            </div>
                        </header>

                            <div class="row" style="width:19cm">
                                <div class="col-sm-8">
                                    <dl>
                                        <dt>
                                            Nombre
                                        </dt>
                                        <dd>
                                            '.$row['dp_nombre'].' '.$row['dp_ap_paterno'].' '.$row['dp_ap_materno'].'
                                        </dd>
                                        <dt>
                                            Carrera
                                        </dt>
                                        <dd>
                                            '.$row['dp_carrera'].'
                                        </dd>
                                        <dt>
                                            Numero de Control
                                        </dt>
                                        <dd>
                                            '.$row['se_no_control'].'
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-4">
                                    <img  src="../assets/images/Alu/'.$row['al_img'].'" style="height: 130px;"/>
                                </div>
                            </div>
                            <hr />
                            <div class="row" style="width:19cm">
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Fecha de nacimiento:
                                        </dt>
                                        <dd>
                                            '.$row['fip_fecha_nac'].'
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Lugar de nacimiento:
                                        </dt>
                                        <dd>
                                            '.$row['fip_lugar_nac'].'
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row" style="width:19cm">
                                <div class="col-sm-3">
                                    <dl>
                                        <dt>
                                            Edad:
                                        </dt>
                                        <dd>
                                            '.$row['dp_edad'].' años
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-3">
                                    <dl>
                                        <dt>
                                            Estatura:
                                        </dt>
                                        <dd>
                                            '.$row['fm_talla'].' m
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-3">
                                    <dl>
                                        <dt>
                                            Peso:
                                        </dt>
                                        <dd>
                                            '.$row['fm_peso'].' kg
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-3">
                                    <dl>
                                        <dt>
                                            Sexo:
                                        </dt>
                                        <dd>
                                            '.$row['dp_sexo'].'
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row" style="width:19cm">
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Estado civil:
                                        </dt>
                                        <dd>
                                            '.$row['fip_edo_civil'].'
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Sangre:
                                        </dt>
                                        <dd>
                                            '.$row['dp_tipo_sangre'].'
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row" style="width:19cm">
                                <div class="col-sm-12">
                                <dl>
                                    <dt>
                                        Trabaja:
                                    </dt>
                                    <dd>
                                        '.$row['fip_trabajo'].'
                                    </dd>
                                </dl>
                                </div>
                            </div>
                            <div class="row" style="width:19cm">
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Teléfono:
                                        </dt>
                                        <dd>
                                            '.$row['dp_tel'].'
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Correo Electronico:
                                        </dt>
                                        <dd>
                                            '.$row['dp_email'].'
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row" style="width:19cm">
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            Número de personas con las que vives:
                                        </dt>
                                        <dd>
                                            '.$row['fip_per_vivienda'].'
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>
                                            La casa donde vives es:
                                        </dt>
                                        <dd>
                                            '.$row['fip_vivienda_tipo'].' '.$row['fip_vivienda_tipo_otro'].'
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <hr />';

            $padres = "SELECT * FROM alumnos_datos_padres WHERE id_alumno = '$noCon'";
            $Pad = $this->_db->query($padres);
            $row = $Pad->fetch_assoc();

            echo'<div class="row" style="width:19cm">
                    <div class="col-sm-12">
                    <dl>
                        <dt>
                            Nombre del padre:
                        </dt>
                        <dd>
                            '.$row['dp_nom_padre'].' '.$row['dp_apep_padre'].' '.$row['dp_apem_padre'].'
                        </dd>
                    </dl>
                    </div>
                </div>
                <div class="row" style="width:19cm">
                    <div class="col-sm-4">
                        <dl>
                            <dt>
                                Edad:
                            </dt>
                            <dd>
                                '.$row['dp_edad_padre'].' años
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-4">
                        <dl>
                            <dt>
                                Teléfono:
                            </dt>
                            <dd>
                                '.$row['dp_tel_padre'].'
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-4">
                        <dl>
                            <dt>
                                Profesión:
                            </dt>
                            <dd>
                                '.$row['dp_profecion_padre'].'
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="row" style="width:19cm">
                    <div class="col-sm-6">
                        <dl>
                            <dt>
                                Trabaja:
                            </dt>
                            <dd>
                                '.$row['dp_trabajo_padre'].'
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-6">
                        <dl>
                            <dt>
                                Domicilio:
                            </dt>
                            <dd>
                                '.$row['dp_dom_padre'].'
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="row" style="width:19cm">
                    <div class="col-sm-12">
                    <dl>
                        <dt>
                            Nombre de la Madre:
                        </dt>
                        <dd>
                            '.$row['dp_nom_madre'].' '.$row['dp_apep_madre'].' '.$row['dp_apem_madre'].'
                        </dd>
                    </dl>
                    </div>
                </div>
                <div class="row" style="width:19cm">
                    <div class="col-sm-4">
                        <dl>
                            <dt>
                                Edad:
                            </dt>
                            <dd>
                                '.$row['dp_edad_madre'].'
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-4">
                        <dl>
                            <dt>
                                Teléfono:
                            </dt>
                            <dd>
                                '.$row['dp_tel_madre'].'
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-4">
                        <dl>
                            <dt>
                                Profesión:
                            </dt>
                            <dd>
                                '.$row['dp_profecion_madre'].'
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="row" style="width:19cm">
                    <div class="col-sm-6">
                        <dl>
                            <dt>
                                Trabaja:
                            </dt>
                            <dd>
                                '.$row['dp_trabajo_madre'].'
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-6">
                        <dl>
                            <dt>
                                Domicilio:
                            </dt>
                            <dd>
                                '.$row['dp_dom_madre'].'
                            </dd>
                        </dl>
                    </div>
                </div>
                <br>
                <hr />';

            $salud = "SELECT
                fiden_medico.no_control,
                fiden_medico.fm_diabetes,
                fiden_medico.fm_hipertencion,
                fiden_medico.fm_epilepsia,
                fiden_medico.fm_anorexia,
                fiden_medico.fm_bulimia,
                fiden_medico.fm_trans_sexual,
                fiden_medico.fm_depresion,
                fiden_medico.fm_tristesa,
                fiden_medico.fm_otra,
                fiden_medico.fm_discapacidad,
                fiden_medico.fm_dis_vista,
                fiden_medico.fm_dis_oido,
                fiden_medico.fm_dis_lenguaje,
                fiden_medico.fm_dis_motora,
                fiden_medico.fm_dis_otra,
                fiden_medico.dx_psicologico,
                fiden_medico.dx_psicologico_tm,
                fiden_medico.dx_medico,
                fiden_medico.dx_medico_tm
                FROM fiden_medico WHERE
                fiden_medico.no_control = '$noCon'";
            $resS = $this->_db->query($salud);
            $row = $resS->fetch_assoc();

            echo '<h5 class="active">Salud</h5>
                <table class="table" style="width:19cm">
                    <tbody>
                        <tr class="table-active">
                            <td>
                                <dl>
                                    <dt>
                                        Enfermedades:
                                    </dt>
                                    <dd>
                                        <ul>'.
                ($row['fm_diabetes']==1?'<li>Diabetes</li>':'').
                ($row['fm_hipertencion']==1?'<li>Hipertensión</li>':'').
                ($row['fm_epilepsia']==1?'<li>Epilepsía</li>':'').
                ($row['fm_anorexia']==1?'<li>Anorexia</li>':'').
                ($row['fm_bulimia']==1?'<li>Bulimia</li>':'').
                ($row['fm_trans_sexual']==1?'<li>ETS</li>':'').
                ($row['fm_depresion']==1?'<li>Depresión</li>':'').
                ($row['fm_tristesa']==1?'<li>Tristeza Profunda</li>':'').
                        '</ul>
                                    </dd>
                                </dl>
                            </td>
                            <td>
                                <dl>
                                    <dt>
                                        Otra enfermedad:
                                    </dt>
                                    <dd>
                                        '.(($row['fm_otra']!="" || $row['fm_otra']!=0)?$row['fm_otra']:'').'
                                    </dd>
                                </dl>
                            </td>
                        </tr>
                        <tr class="table-primary">
                            <td>
                                <dl>
                                    <dt>
                                        Discapacidades:
                                    </dt>
                                    <dd>
                                        <ul>'.
                ($row['fm_dis_vista']==1?'<li>Vista</li>':'').
                ($row['fm_dis_oido']==1?'<li>Oido</li>':'').
                ($row['fm_dis_lenguaje']==1?'<li>Lenguaje</li>':'').
                ($row['fm_dis_motora']==1?'<li>Motora</li>':'').
                                        '</ul>
                                    </dd>
                                </dl>
                            </td>
                            <td>
                                <dl>
                                    <dt>
                                        Otra dicapacidad:
                                    </dt>
                                    <dd>
                                        '.(($row['fm_dis_otra']!="" || $row['fm_dis_otra']!=0)?'<li>'.$row['fm_dis_otra'].'</li>':'').'
                                    </dd>
                                </dl>
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td>
                                <dl>
                                    <dt>
                                        Diagnóstico Clínico:
                                    </dt>
                                    <dd>
                                        '.(!is_null($row['dx_psicologico'])?$row['dx_psicologico']:'NO').'
                                    </dd>
                                </dl>
                            </td>
                            <td>
                                <dl>
                                    <dt>
                                        Fecha del diagnóstico:
                                    </dt>
                                    <dd>
                                        '.(!is_null($row['dx_psicologico'])?$row['dx_psicologico_tm']:'').'
                                    </dd>
                                </dl>
                            </td>
                        </tr>
                        <tr class="table-primary">
                            <td>
                                <dl>
                                    <dt>
                                        Diagnóstico Psicológico:
                                    </dt>
                                    <dd>
                                        '.(!is_null($row['dx_medico'])?$row['dx_medico']:'NO').'
                                    </dd>
                                </dl>
                            </td>
                            <td>
                                <dl>
                                    <dt>
                                        Fecha del diagnóstico:
                                    </dt>
                                    <dd>
                                        '.(!is_null($row['dx_medico'])?$row['dx_medico_tm']:'').'
                                    </dd>
                                </dl>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <hr />';
            $familia ="SELECT
                fiden_familiares.fi_fa_nombre,
                fiden_familiares.fi_fa_fecnac,
                fiden_familiares.fi_fa_sex,
                fiden_familiares.fi_fa_escolaridad,
                fiden_familiares.fi_fa_parentesco,
                fiden_familiares.fi_fa_actitud
                FROM
                fiden_familiares
                WHERE
                fiden_familiares.no_control = '$noCon'";
            if (!$resultado=$this->_db->query($familia)) {
            }
            echo'<h5 class="active">Datos Familiares</h5>
                <table style="width:19cm";>
                    <thead>
                        <tr>
                            <td >Nombre</td>
                            <td >Fecha de Nacimiento</td>
                            <td >Sexo</td>
                            <td >Escolaridad  </td>
                            <td >Parentesco</td>
                            <td >Actitud</td>
                        </tr>
                    </thead>
                    <tbody>';
            if ($resultado->num_rows > 0) {
                $n=1;
                while ($row = $resultado->fetch_assoc()) {
                    echo '<tr>
                        <td >'.$row['fi_fa_nombre'].'</td>
                        <td >'.$row['fi_fa_fecnac'].'</td>
                        <td >'.($row['fi_fa_sex']=='H'?'Hombre':'Mujer').'</td>
                        <td >';
                    $edufa=array( array('value' => "1", 'text' =>"Sin escolaridad"), array('value' => "2", 'text' =>"Primaria incompleta"), array('value' => "3", 'text' =>"Primaria completa"), array('value' => "4", 'text' =>"Secundaria incompleta"), array('value' => "5", 'text' =>"Secundaria completa"), array('value' => "6", 'text' =>"Medio Superior"), array('value' => "7", 'text' =>"Superior") );
                    foreach ($edufa as $value) { if ($value['value']==$row['fi_fa_escolaridad']) { echo $value['text']; } }
                    unset($value);
                    echo   '
                        </td>
                        <td >';
                    $paren=array( array('value' => "1", 'text' =>"Madre"), array('value' => "2", 'text' =>"Padre"), array('value' => "3", 'text' =>"Abuela"), array('value' => "4", 'text' =>"Abuelo"), array('value' => "5", 'text' =>"Hermana"), array('value' => "6", 'text' =>"Hermano"), array('value' => "7", 'text' =>"Tía"), array('value' => "8", 'text' =>"Tío"), array('value' => "9", 'text' =>"Otro") );
                    foreach ($paren as $value) { if ($value['value']==$row['fi_fa_parentesco']) { echo $value['text']; } }
                    unset($value);
                    echo '
                        </td>
                        <td >'.$row['fi_fa_actitud'].'</td>
                    </tr>';$n++;

                }
            }
            echo '      </tbody>
                </table>
                <br>';
            $famili2 = "SELECT no_control,
                fiden_relfa,
                fiden_dificultades,
                fiden_actfa,
                fiden_ligue,
                fiden_ligue_T,
                fiden_edu,
                fiden_influ,
                fiden_otro_dato FROM fiden_integracion WHERE no_control = '$noCon'";
            $resultado = $this->_db->query($famili2);
            $row = $resultado->fetch_assoc();

            echo '<div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>¿Cómo es la relación con tu familia?</dt>
                            <dd>'.$row['fiden_relfa'].'</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>¿Existen dificultades? ¿De qué tipo?</dt>
                            <dd>'.(($row['fiden_dificultades']!="" || $row['fiden_dificultades']!=0)? 'Si': 'No').'<br>'.(($row['fiden_dificultades']!="" || $row['fiden_dificultades']!=0)? $row['fiden_dificultades']: '').'</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>¿Qué actitud tienes con tu familia?</dt>
                            <dd>'.$row['fiden_actfa'].'</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>¿Con quién te sientes más ligado afectivamente?</dt>
                            <dd>';$paren=array( array('value' => "1", 'text' =>"Madre"), array('value' => "2", 'text' =>"Padre"), array('value' => "3", 'text' =>"Abuela"), array('value' => "4", 'text' =>"Abuelo"), array('value' => "5", 'text' =>"Hermana"), array('value' => "6", 'text' =>"Hermano"), array('value' => "7", 'text' =>"Tía"), array('value' => "8", 'text' =>"Tío"), array('value' => "9", 'text' =>"Otro") );
                            foreach ($paren as $value) { if ($value['value']==$row['fiden_ligue']) { echo $value['text']; } }
                            unset($value);echo'</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>¿Quién se ocupa más directamente de tu educación?</dt>
                            <dd>'.$row['fiden_edu'].'</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>¿Quién ha influido más en tu decisión para estudiar esta carrera?</dt>
                            <dd>'.$row['fiden_influ'].'</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <dl>
                            <dt>Consideras importante facilitar algún otro dato sobre tu ambiente familiar</dt>
                            <dd> '.(!is_null($row['fiden_otro_dato'])? 'Si': 'No').' '.(!is_null($row['fiden_otro_dato'])? $row['fiden_otro_dato']: '').'</dd>
                        </dl>
                    </div>
                </div>
                <br />
                <hr />';
            $soci="SELECT no_control, rel_comp, rel_comp_t, rel_ami, rel_ami_t, alu_par, rel_alu_par, rel_alu_par_t, rel_pro, rel_pro_t, rel_aut_aca, rel_aut_aca_t, alu_tlibre, alu_act_rec, alu_pl_inme, alu_metas, alu_soy, alu_caracter, alu_gusto, alu_aspira, alu_miedo FROM fiden_social WHERE no_control = '$noCon' ";

            $resultado = $this->_db->query($soci);
            $row = $resultado->fetch_assoc();

            echo '
                    <h5 class="active">ÁREA SOCIAL</h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Cómo es la relación con los compañeros?</dt>
                                <dd>';$calRel = array( array('value' => "1", 'text' => "Buena"), array('value' => "2", 'text' => "Regular"), array('value' => "3", 'text' => "Excelente") );
                                foreach ($calRel as $value) { if ($value['value'] == $row['rel_comp']) { echo $value['text']; } }
                                echo ' '.$row['rel_comp_t'].' </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Como es la relación con tus amigos?</dt>
                                <dd>';foreach ($calRel as $value) { if ($value['value'] == $row['rel_ami']) { echo $value['text']; } }echo' '.$row['rel_ami_t'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Cómo es la relación con tu pareja? (si tienes)</dt>
                                <dd>';foreach ($calRel as $value) { if ($value['value'] == $row['rel_alu_par']) { echo $value['text']; } }echo' '.$row['rel_alu_par_t'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Cómo es la relación con tus profesores?</dt>
                                <dd>';foreach ($calRel as $value) { if ($value['value'] == $row['rel_pro']) { echo $value['text']; } }echo' '.$row['rel_pro_t'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Como es la relación con las autoridades académicas?</dt>
                                <dd>';foreach ($calRel as $value) { if ($value['value'] == $row['rel_aut_aca']) { echo $value['text']; } }echo' '.$row['rel_aut_aca_t'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Qué haces en tu tiempo libre?</dt>
                                <dd>'.$row['alu_tlibre'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Cuál es tu actividad recreativa?</dt>
                                <dd>'.$row['alu_act_rec'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <br />
                    <hr />
                    <h5 class="active">PLAN DE VIDA</h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Cuáles son tus planes inmediatos?</dt>
                                <dd>'.$row['alu_pl_inme'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>¿Cuáles son tus metas en la vida?</dt>
                                <dd>'.$row['alu_metas'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>Yo soy:</dt>
                                <dd>'.$row['alu_soy'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>Mi carácter es:</dt>
                                <dd>'.$row['alu_caracter'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>A mí me gusta:</dt>
                                <dd>'.$row['alu_gusto'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>Yo aspiro en la vida </dt>
                                <dd>'.$row['alu_aspira'].'</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <dl>
                                <dt>Yo tengo miedo:</dt>
                                <dd>'.$row['alu_miedo']. '</dd>
                            </dl>
                        </div>
                    </div>
                    <hr />
                    <div class="row"><div class="col-sm-4 col-sm-offset-4">

                    </div></div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <table style="width:90%; margin-left: auto; margin-right: auto; border:none !important;" cellspacing="0" border="0">
                        <tr>
                            <td style="width: 100%; text-align: center;" align="center">
                                <div style=" text-align: center;">________________________________________</div>
                                <br>
                                <div style="text-align: center;">       Firma ' . $nombre . '</div>
                            </td>
                        </tr>
                    </table>
                <footer>
                    INSTITUTO TECNOLOGICO SUPERIOR DE CIUDAD HIDALGO
                </footer>
            </body>

            </html>';
            return $alu;

        }

        public function elimFam($nuF){
            $this->_db->query("DELETE FROM fiden_familiares WHERE no_control = $nuF");
            if($this->_db->affected_rows>0){
                return true;
            }else {
                return false;
            }
        }

        public function upPdf($tuser, $user){
            if (isset($_FILES["ficha"])){
                $file = $_FILES["ficha"];
                $nombre = $file["name"];
                $tipo = $file["type"];
                $ruta_provisional = $file["tmp_name"];
                $size = $file["size"];
                $carpeta = $_SERVER["DOCUMENT_ROOT"]."/sta/pdf/";
                $tip=explode("/", $tipo);

                if ($tipo != 'application/pdf' ) {
                  echo "Error, el archivo no es un pdf";
                }
                else {
                    $src = $carpeta.$user.".".$tip[1];
                    $arch=false;
                    $sql=false;
                    if (move_uploaded_file($ruta_provisional, $src)) {
                        $arch=true;
                    }
                    $this->_sql="UPDATE alumnos_caracterizacion SET al_pdf ='".$user.".".$tip[1]."' WHERE se_no_control=$user";
                    $resultado = $this->_db->query($this->_sql);
                    if($this->_db->affected_rows > 0){
                        $sql=true;
                    }

                    if ($arch==true && $sql=true) {
                        echo json_encode( array("error"=>false, "men"=>"Se guardo correctamente la la Ficha de Identificaciòn "));
                    }
                    else{
                        echo json_encode( array("error"=>true, "men"=>"Hubo algun problema al subir la la Ficha de Identificaciòn "));
                    }
                }
            }
        }

        public function showPDF($no){
            $this->_sql="SELECT
                alumnos_caracterizacion.al_pdf
                FROM
                alumnos_caracterizacion
                WHERE
                alumnos_caracterizacion.se_no_control = '$no'";

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
                echo '<div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                		<h2>Puedes ver tu antigua ficha aqui</h2>
                        <a class="btn btn-primary view-pdf" download="'.$no.'.pdf" href="/sta/pdf/'.$row['al_pdf'].'">Ver PDF</a>
            	    </div>
                </div>';
            }else {
                echo '<div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                		<h2>No has subido ninguna ficha aun</h2>
                    </div>
                </div>';
            }

        }

        public function calGnr($Cont){
            $this->_sql="SELECT
                AVG(dbo.ListasSemestre.lse_Calificacion) AS Cal,
                dbo.ListasSemestre.alu_NumControl
                FROM
                dbo.ListasSemestre
                WHERE
                dbo.ListasSemestre.alu_NumControl='$Cont'
                GROUP BY
                dbo.ListasSemestre.alu_NumControl";
            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            if($stmt!=false){
                $row = sqlsrv_fetch_array($stmt);
                return ($row['Cal']);
            }else {
                return '0';
            }

        }

        public  function clases($clas){
            switch ($clas){
                case 0:
                    return ("info");
                    break;
                case 1:
                    return ("success");
                    break;
                case 2:
                    return ("warning");
                    break;
                case 3:
                    return ("danger");
                    break;
            }
        }
    }
?>
