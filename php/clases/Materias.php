<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/php/dbconfig.php";

    class Materias {

        protected $_db;
        protected $_db2;
        protected $_sql;

        function __construct(){
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

        function listarMaterias($indi){
            $alumnos = $this->listaAlumnos($_POST['Grup']);
            $nocon="";
            for ($i=0; $i < count($alumnos); $i++) {
                $nocon.=" '".$alumnos[$i]."',";
            }
            $nocon = trim($nocon, ',');
            /*$this->_sql="SELECT
            dbo.Reticula.ret_NomCompleto,
            dbo.GruposSemestre.gse_Clave FROM dbo.GruposSemestre INNER JOIN dbo.Reticula ON dbo.GruposSemestre.ret_Clave = dbo.Reticula.ret_Clave
            WHERE dbo.GruposSemestre.gse_Observaciones = '$indi'";*/

            $this->_sql= "SELECT
                	dbo.GruposSemestre.gse_Clave,
                	(SELECT Reticula.ret_NomCompleto  FROM Reticula INNER JOIN GruposSemestre ON GruposSemestre.ret_Clave = Reticula.ret_Clave
                		WHERE GruposSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave) AS ret_NomCompleto
                FROM
                	dbo.Alumnos
                	INNER JOIN dbo.ListasSemestre ON dbo.ListasSemestre.alu_NumControl = dbo.Alumnos.alu_NumControl
                	INNER JOIN dbo.GruposSemestre ON dbo.ListasSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave
                	AND dbo.ListasSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave
                	AND dbo.ListasSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave
                	INNER JOIN dbo.Reticula ON dbo.GruposSemestre.ret_Clave = dbo.Reticula.ret_Clave
                WHERE
                	dbo.ListasSemestre.alu_NumControl IN ($nocon)
                GROUP BY
                dbo.GruposSemestre.gse_Clave";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            if($stmt==false){
                echo '<option selected="selected" disabled="disabled">No Hay Materias</option>';
            }
            echo '<option selected="selected" disabled="disabled">Materias</option>';
            while ($row= sqlsrv_fetch_array($stmt)) {
                echo "<option value='".$row['gse_Clave']."'>".utf8_encode($row['ret_NomCompleto'])."</option>";
            }
            echo '<option value="todas">Todas</option>';

        }

        function listarMaterias2($alu){
            $this->_sql="SELECT
                dbo.GruposSemestre.gse_Clave
                FROM
                dbo.Alumnos
                INNER JOIN dbo.ListasSemestre ON dbo.ListasSemestre.alu_NumControl = dbo.Alumnos.alu_NumControl
                INNER JOIN dbo.GruposSemestre ON dbo.ListasSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave AND dbo.ListasSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave AND dbo.ListasSemestre.gse_Clave = dbo.GruposSemestre.gse_Clave
                INNER JOIN dbo.Reticula ON dbo.GruposSemestre.ret_Clave = dbo.Reticula.ret_Clave
                WHERE
                dbo.ListasSemestre.alu_NumControl IN ($alu)
                GROUP BY
                dbo.GruposSemestre.gse_Clave";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('<h3>No hay Alumnos por el momento</h3>');
            $mat = array();

            if($stmt!=false){
                while ($row= sqlsrv_fetch_array($stmt)) {
                    array_push($mat,$row['gse_Clave']);
                }
            }

            return $mat;

        }

        function consCalMaterias($Mat){
            $Materia=""; $Control=array(); $Alumnos=array(); $U1=array(); $U2=array(); $U3=array(); $U4=array(); $U5=array(); $U6=array(); $U7=array(); $U8=array(); $U9=array(); $U10=array(); $U11=array(); $U12=array(); $U13=array();

            $this->_sql="SELECT
                grupos_tutorias.gpo_clave,
                grupos_tutorias_complemento.alu_no_control,
                grupos_tutorias.gpo_sem
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                WHERE
                grupos_tutorias.gpo_nombre = '".$_POST['Gpo']."'";

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
            $nocon= "";
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc() ){
                    $nocon.=" '".$row['alu_no_control']."',";
                }
            }
            $nocon = trim($nocon, ',');

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
                AND Alumnos.alu_NumControl IN (".$nocon.")
                        ORDER BY Alumnos.alu_ApePaterno, Alumnos.alu_ApeMaterno, Alumnos.alu_Nombre ASC";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

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
                if ($Cal[$i][0]!==null) {
                    $con=$con+1;
                }elseif ($Cal[$i+1][0]!==null) {
                    $con=$con+1;
                }else {
                    $Can=$i;
                    break;
                }
            }

            echo "<table class='display' id='mitabla'>
                    <thead style='background: white;'>
                        <tr>
                            <th>NumControl</th>
                            <th>Nombre</th>";
            for ($i=1; $i <=$con ; $i++) {
                echo "<th>U$i</th>";
            }
            echo" </tr>
                </thead>
                <tbody>";
            for ($i=0; $i <count($Control) ; $i++){
                echo "<tr>";
                for ($j=0; $j <$Can ; $j++) {
                    if (is_numeric($Cal[$j][$i])) {
                        if ($Cal[$j][$i]<=69) {
                            $mot=$this->getMotivos($j+1, $Mat, $Control[$i]);
                            if (!is_null($mot) && !is_null($mot[0]) ) {
                                if (!is_null($mot[1]))
                                    echo "<td class='danger' data-tooltip='".$mot[1]."'>".$Cal[$j][$i]."</td>";
                                else
                                    echo "<td class='danger' data-tooltip='".$mot[0]."'>".$Cal[$j][$i]."</td>";
                            }
                            else {
                                echo "<td class='danger' data-tooltip='En espera'>".$Cal[$j][$i]."</td>";
                            }
                        }else {
                            echo "<td>".utf8_encode($Cal[$j][$i])."</td>";
                        }
                    }else {
                        echo "<td>".$Cal[$j][$i]."</td>";
                    }
                }
                echo "</tr>";
            }

            echo "</tbody>
            </table>";
        }

        function consCalMaterias2($Mat,$con){
            $Materia=""; $Control=array(); $Alumnos=array(); $U1=array(); $U2=array(); $U3=array(); $U4=array(); $U5=array(); $U6=array(); $U7=array(); $U8=array(); $U9=array(); $U10=array(); $U11=array(); $U12=array(); $U13=array();

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
                AND Alumnos.alu_NumControl IN ($con)
                ORDER BY Alumnos.alu_ApePaterno, Alumnos.alu_ApeMaterno, Alumnos.alu_Nombre ASC";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            if($stmt==false){ }
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
                if ($Cal[$i][0]!==null) {
                    $con=$con+1;
                }elseif ($Cal[$i+1][0]!==null) {
                    $con=$con+1;
                }else {
                    $Can=$i;
                    break;
                }
            }
            return( array_slice($Cal, 0, $Can));
        }

        function listaAlumnos($gpo) {
            $this->_sql="SELECT
                grupos_tutorias.gpo_clave,
                grupos_tutorias_complemento.alu_no_control,
                grupos_tutorias.gpo_sem
                FROM
                grupos_tutorias
                INNER JOIN grupos_tutorias_complemento ON grupos_tutorias_complemento.gpo_clave = grupos_tutorias.gpo_clave
                WHERE
                grupos_tutorias.gpo_nombre = '$gpo'";

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
            $nocon= array();
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc() ){
                    array_push($nocon,$row['alu_no_control']);
                }
            }
            return $nocon;
        }

        function materiasAll(){
            $alumnos = $this->listaAlumnos($_POST['Gpo']);
            $nocon="";
            for ($i=0; $i < count($alumnos); $i++) {
                $nocon.=" '".$alumnos[$i]."',";
            }
            $nocon = trim($nocon, ',');
            #print_r($nocon);

            $materias = $this->listarMaterias2($nocon);
            #var_dump($materias);
            $cal= array();
            $unidad= array();
            foreach ($materias as $vmat) {
                $aux = $this->consCalMaterias2($vmat, $nocon);
                array_push($cal, $aux);
                array_push($unidad, count($aux)-2);
            }

            unset($vmat);
            echo "<table class='table display' id='mitabla' style='width: 30em; overflow-x: auto; white-space: nowrap; '>
                <thead style=\"background: white;\">
                        <tr>
                            <th></th>
                            <th></th>";
            for ($i=0; $i < count($materias); $i++) {
                echo "<th colspan='".$unidad[$i]."'>".$this->NomMateria($materias[$i])."</th>";
            }
            echo "</tr>
                <tr>
                    <th>NumControl</th>
                    <th>Nombre</th>";
            for ($i=0; $i < count($unidad); $i++) {
                for ($j=0; $j < $unidad[$i]; $j++) {
                    if ($j===0) {
                        echo "<th style='border-left: 1px solid;'>U".($j+1)."</th>";
                    } elseif ($j===($unidad[$i]-1)) {
                        echo "<th style='border-right: 1px solid;'>U".($j+1)."</th>";
                    } else {
                        echo "<th>U".($j+1)."</th>";
                    }

                }
            }
            echo "</tr>
            </thead>
            <tbody>";
            for ($i=0; $i < count($alumnos); $i++) {
                echo "<tr>
                        <td>".$alumnos[$i]."</td>
                        <td>".$this->NomAlumno($alumnos[$i])."</td>";
                for ($j=0; $j < count($materias); $j++) {
                    $existe=array_search($alumnos[$i], $cal[$j][0]);
                    for ($k=2; $k < count($cal[$j]); $k++) {
                        if (/*($existe != false || $existe >=0 || $existe != null) && */ is_int($existe)) {
                            if ($k===2) {
                                if ($cal[$j][$k][$existe] < 70) {
                                    $mot=$this->getMotivos($k-1, $materias[$j], $alumnos[$i]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        if (!is_null($mot[1]))
                                            echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='".$mot[1]."'>".$cal[$j][$k][$existe]."</td>";
                                        else
                                            echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='".$mot[0]."'>".$cal[$j][$k][$existe]."</td>";
                                    }
                                    else {
                                        echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='En espera'>".$cal[$j][$k][$existe]."</td>";
                                    }

                                }else {
                                    echo "<td style='border-left: 1px solid;'>".$cal[$j][$k][$existe]."</td>";
                                }
                            } elseif ($k===(count($cal[$j])-1)) {
                                if ($cal[$j][$k][$existe] < 70) {
                                    $mot=$this->getMotivos($k-1, $materias[$j], $alumnos[$i]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        if (!is_null($mot[1]))
                                            echo "<td style='border-right: 1px solid;' class='danger' data-tooltip='".$mot[1]."'>".$cal[$j][$k][$existe]."</td>";
                                        else
                                            echo "<td style='border-right: 1px solid;' class='danger' data-tooltip='".$mot[0]."'>".$cal[$j][$k][$existe]."</td>";
                                    }
                                    else {
                                        echo "<td style='border-right: 1px solid;' class='danger' data-tooltip='En espera'>".$cal[$j][$k][$existe]."</td>";
                                    }

                                }else {
                                    echo "<td style='border-right: 1px solid;'>".$cal[$j][$k][$existe]."</td>";
                                }
                            } else {
                                if ($cal[$j][$k][$existe] < 70) {
                                    $mot=$this->getMotivos($k-1, $materias[$j], $alumnos[$i]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        if (!is_null($mot[1]))
                                            echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='".$mot[1]."'>".$cal[$j][$k][$existe]."</td>";
                                        else
                                            echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='".$mot[0]."'>".$cal[$j][$k][$existe]."</td>";
                                    }
                                    else {
                                        echo "<td class='danger' data-tooltip='En espera'>".$cal[$j][$k][$existe]."</td>";
                                    }

                                }else {
                                    echo "<td>".$cal[$j][$k][$existe]."</td>";
                                }
                            }
                        }
                        else {
                            if ($k===2) {
                                echo "<td style='border-left: 1px solid;'> </td>";
                            } elseif ($k===(count($cal[$j])-1)) {
                                echo "<td style='border-right: 1px solid;'></td>";
                            } else {
                                echo "<td></td>";
                            }
                        }
                    }
                }
                echo "</tr>";
            }
        }

        function materiasAll2(){
            $this->_sql = "SELECT
                dbo.Alumnos.alu_NumControl
                FROM dbo.Alumnos
                WHERE	dbo.Alumnos.alu_StatusAct = 'VI'
                AND dbo.Alumnos.car_Clave = ".$_SESSION['usuario']['Car']."
                AND dbo.Alumnos.alu_AnioIngreso =".$_POST['Gen'];
            $stmts = sqlsrv_query ( $this->_db2, $this->_sql) or die('<h3>No hay Alumnos por el momento</h3>');

            $alumnos= array();
            if ($stmts != false) {
                while ($row = sqlsrv_fetch_array($stmts)) {
                    array_push($alumnos,$row['alu_NumControl']);
                }
            }
            else {
                echo "sa";
                exit;
            }
            $nocon="";
            for ($i=0; $i < count($alumnos); $i++) {
                $nocon.=" '".$alumnos[$i]."',";
            }
            $nocon = trim($nocon, ',');

            $materias = $this->listarMaterias2($nocon);

            $cal= array();
            $unidad= array();
            foreach ($materias as $vmat) {
                $aux = $this->consCalMaterias2($vmat, $nocon);
                array_push($cal, $aux);
                array_push($unidad, count($aux)-2);
            }
            unset($vmat);
            echo "<table class='table display' id='mitabla' style='width: 30em; overflow-x: auto; white-space: nowrap; '>
                <thead style=\"background: white;\">
                        <tr>
                            <th></th>
                            <th></th>";
            for ($i=0; $i < count($materias); $i++) {
                echo "<th colspan='".$unidad[$i]."'>".$this->NomMateria($materias[$i])."</th>";
            }
            echo "</tr>
                <tr>
                    <th>NumControl</th>
                    <th>Nombre</th>";
            for ($i=0; $i < count($unidad); $i++) {
                for ($j=0; $j < $unidad[$i]; $j++) {
                    if ($j===0) {
                        echo "<th style='border-left: 1px solid;'>U".($j+1)."</th>";
                    } elseif ($j===($unidad[$i]-1)) {
                        echo "<th style='border-right: 1px solid;'>U".($j+1)."</th>";
                    } else {
                        echo "<th>U".($j+1)."</th>";
                    }

                }
            }
            echo "</tr>
            </thead>
            <tbody>";

            for ($i=0; $i < count($alumnos); $i++) {
                echo "<tr>
                        <td>".$alumnos[$i]."</td>
                        <td>".$this->NomAlumno($alumnos[$i])."</td>";
                for ($j=0; $j < count($materias); $j++) {
                    $existe=array_search($alumnos[$i], $cal[$j][0]);
                    for ($k=2; $k < count($cal[$j]); $k++) {
                        if (/*($existe != false || $existe >=0 || $existe != null) && */ is_int($existe)) {
                            if ($k===2) {
                                if ($cal[$j][$k][$existe] < 70) {
                                    $mot=$this->getMotivos($k-1, $materias[$j], $alumnos[$i]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        if (!is_null($mot[1]))
                                            echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='".$mot[1]."'>".$cal[$j][$k][$existe]."</td>";
                                        else
                                            echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='".$mot[0]."'>".$cal[$j][$k][$existe]."</td>";
                                    }
                                    else {
                                        echo "<td style='border-left: 1px solid;' class='danger' data-tooltip='En espera'>".$cal[$j][$k][$existe]."</td>";
                                    }

                                }else {
                                    echo "<td style='border-left: 1px solid;'>".$cal[$j][$k][$existe]."</td>";
                                }
                            } elseif ($k===(count($cal[$j])-1)) {
                                if ($cal[$j][$k][$existe] < 70) {
                                    $mot=$this->getMotivos($k-1, $materias[$j], $alumnos[$i]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {
                                        if (!is_null($mot[1]))
                                            echo "<td style='border-riht: 1px solid;' class='danger' data-tooltip='".$mot[1]."'>".$cal[$j][$k][$existe]."</td>";
                                        else
                                            echo "<td style='border-riht: 1px solid;' class='danger' data-tooltip='".$mot[0]."'>".$cal[$j][$k][$existe]."</td>";
                                    }
                                    else {
                                        echo "<td style='border-right: 1px solid;' class='danger' data-tooltip='En espera'>".$cal[$j][$k][$existe]."</td>";
                                    }

                                }else {
                                    echo "<td style='border-right: 1px solid;'>".$cal[$j][$k][$existe]."</td>";
                                }
                            } else {
                                if ($cal[$j][$k][$existe] < 70) {
                                    $mot=$this->getMotivos($k-1, $materias[$j], $alumnos[$i]);
                                    if (!is_null($mot) && !is_null($mot[0]) ) {if (!is_null($mot[1]))
                                        echo "<td class='danger' data-tooltip='".$mot[1]."'>".$cal[$j][$k][$existe]."</td>";
                                    else
                                        echo "<td class='danger' data-tooltip='".$mot[0]."'>".$cal[$j][$k][$existe]."</td>";

                                    }
                                    else {
                                        echo "<td class='danger' data-tooltip='En espera'>".$cal[$j][$k][$existe]."</td>";
                                    }

                                }else {
                                    echo "<td>".$cal[$j][$k][$existe]."</td>";
                                }
                            }
                        }
                        else {
                            if ($k===2) {
                                echo "<td style='border-left: 1px solid;'> </td>";
                            } elseif ($k===(count($cal[$j])-1)) {
                                echo "<td style='border-right: 1px solid;'></td>";
                            } else {
                                echo "<td></td>";
                            }
                        }
                    }
                }
                echo "</tr>";
            }
        }

        function NomMateria($idMat){
            $this->_sql="SELECT
                dbo.Reticula.ret_NomCompleto
                FROM dbo.GruposSemestre INNER JOIN dbo.Reticula ON dbo.GruposSemestre.ret_Clave = dbo.Reticula.ret_Clave
                WHERE dbo.GruposSemestre.gse_Clave  = '$idMat'";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            if($stmt==true){
                $row= sqlsrv_fetch_array($stmt);
                return $row['ret_NomCompleto'];
            }
            else {
                return "";
            }

        }

        function NomAlumno($idAl){
            $this->_sql="SELECT (RTRIM(Alumnos.alu_ApePaterno) + ' ' + RTRIM(Alumnos.alu_ApeMaterno) + ' ' + RTRIM(Alumnos.alu_Nombre)) AS Alumno FROM dbo.Alumnos WHERE dbo.Alumnos.alu_NumControl IN ('$idAl')";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            if($stmt==true){
                $row= sqlsrv_fetch_array($stmt);
                return $row['Alumno'];
            }
            else {
                return "";
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
                    return array(isset($row['mot_re_nombre'])?$row['mot_re_nombre']:'En espera', isset($row['gpro_cal_otro'])?$row['gpro_cal_otro']:'');
                }
                else{
                    return array("En espera", '');
                }
            }
        }
    }

 ?>
