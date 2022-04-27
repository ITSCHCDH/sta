<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";
class ps{

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

    public function gruposCar($car){
        $this->_sql="SELECT grupos_tutorias.gpo_nombre FROM grupos_tutorias WHERE grupos_tutorias.car_clave='".$car."'";

        if(!$resultado=$this->_db->query($this->_sql)){
            /*/ ¡Oh, no! La consulta falló.
            echo "Lo sentimos, este sitio web está experimentando problemas.";

            // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
            // cómo obtener información del error
            echo "Error: La ejecución de la consulta falló debido a: \n";
            echo "Query: " . $this->_sql . "\n";
            echo "Errno: " . $this->_db->errno . "\n";
            echo "Error: " . $this->_db->error . "\n";
            exit;*/
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

    public function catedraticos(){
        $this->_sql="SELECT
            catedratico_datos_personales.cat_Clave,
            catedratico_datos_personales.cat_Nombre,
            catedratico_datos_personales.cat_ApePat,
            catedratico_datos_personales.cat_ApeMat,
            catedratico_datos_personales.cat_dep,
            catedratico_datos_personales.cat_Status,
            usuario.u_Usuario,
            usuario.u_tutor_generacional,
            usuario.u_Clave
            FROM
            catedratico_datos_personales
            INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
            WHERE
            u_Tipo not LIKE '%Tutor%'
            ORDER BY cat_dep, cat_ApePat, cat_ApeMat ASC";

        if(!$resultado = $this->_db->query($this->_sql)){

        }
        if ($resultado->num_rows >0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<option label='".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."' value='".$row['u_Clave']."'>";
            }
        }
    }

    public function psicologos(){
        $this->_sql="SELECT
            psicologos_datos.psi_id,
            psicologos_datos.psi_nom,
            psicologos_datos.psi_apePat,
            psicologos_datos.psi_apeMat,
            usuario.u_Usuario,
            usuario.u_tutor_generacional,
            usuario.u_Clave
            FROM
            psicologos_datos
            INNER JOIN usuario ON usuario.psi_clave = psicologos_datos.psi_id
            WHERE
            u_Tipo not LIKE '%-Tutor-%'";
        if(!$resultado = $this->_db->query($this->_sql)){

        }
        if ($resultado->num_rows >0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<option label='".$row['psi_nom']." ".$row['psi_apePat']." ".$row['psi_apeMat']."' value='".$row['u_Clave']."'>";
            }
        }
    }

    public function tutores(){
        $this->_sql="SELECT
            catedratico_datos_personales.cat_Clave,
            catedratico_datos_personales.cat_Nombre,
            catedratico_datos_personales.cat_ApePat,
            catedratico_datos_personales.cat_ApeMat,
            catedratico_datos_personales.cat_dep,
            catedratico_datos_personales.cat_Status,
            usuario.u_Usuario,
            usuario.u_tutor_generacional
            FROM
            catedratico_datos_personales
            INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
            WHERE
            u_Tipo LIKE '%Tutor' OR u_Tipo LIKE '%-Tutor-%'
            ORDER BY cat_dep, cat_ApePat, cat_ApeMat ASC";

        if(!$resultado = $this->_db->query($this->_sql)){

        }
        if ($resultado->num_rows >0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<option label='".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."' value='".$row['cat_Clave']."'>";
            }
        }
    }

    public function tuto_crud(){
        $this->_sql="SELECT
            catedratico_datos_personales.cat_Clave,
            catedratico_datos_personales.cat_Nombre,
            catedratico_datos_personales.cat_ApePat,
            catedratico_datos_personales.cat_ApeMat,
            catedratico_datos_personales.cat_dep,
            catedratico_datos_personales.cat_Status,
            usuario.u_Usuario,
            usuario.u_tutor_generacional,
            usuario.u_Clave
            FROM
            catedratico_datos_personales
            INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
            WHERE
            u_Tipo LIKE '%Tutor-%'
            ORDER BY cat_dep, cat_ApePat, cat_ApeMat ASC";

        if(!$resultado = $this->_db->query($this->_sql)){

        }
        if ($resultado->num_rows >0) {

            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."</td>
                    <td>".$row['cat_dep']."</td>
                    <td>".$row['u_Usuario']."</td>
                    <td>";
                    echo '<button type="button" class="btn btn-danger btn-sm"
                            onclick="TU_Eliminar('.$row['u_Clave'].', \''.$row["cat_Nombre"]." ".$row["cat_ApePat"]." ".$row["cat_ApeMat"].'\', \'pro\');">
                            Eliminar
                        </button>
                    </td>
                </tr>';
            }
        }

        $this->_sql="SELECT
            psicologos_datos.psi_id,
            psicologos_datos.psi_nom,
            psicologos_datos.psi_apePat,
            psicologos_datos.psi_apeMat,
            usuario.u_Usuario,
            usuario.u_tutor_generacional,
            usuario.u_Clave
            FROM
            psicologos_datos
            INNER JOIN usuario ON usuario.psi_clave = psicologos_datos.psi_id
            WHERE
            u_Tipo LIKE '%Tutor-%'
            ORDER BY psi_apePat, psi_apeMat ASC";

        if(!$resultado = $this->_db->query($this->_sql)){

        }
        if ($resultado->num_rows >0) {

            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>".$row['psi_nom']." ".$row['psi_apePat']." ".$row['psi_apeMat']."</td>
                    <td>Psicologia</td>
                    <td>".$row['u_Usuario']."</td>
                    <td>";
                echo '<button type="button" class="btn btn-danger btn-sm"
                            onclick="TU_Eliminar('.$row['u_Clave'].', \''.$row['psi_nom']." ".$row["psi_apePat"]." ".$row["psi_apeMat"].'\', \'psi\');">
                            Eliminar
                        </button>
                    </td>
                </tr>';
            }
        }
    }

    public function tuto_crud2(){
        $this->_sql="SELECT
            grupos_tutorias.gpo_clave,
            grupos_tutorias.gpo_nombre,
            grupos_tutorias.cat_clave,
            catedratico_datos_personales.cat_Nombre,
            catedratico_datos_personales.cat_ApePat,
            catedratico_datos_personales.cat_ApeMat,
            grupos_tutorias.gpo_sem,
            grupos_tutorias.gpo_identificador,
            carreras.car_Nombre
            FROM
            grupos_tutorias
            INNER JOIN catedratico_datos_personales ON grupos_tutorias.cat_clave = catedratico_datos_personales.cat_Clave
            INNER JOIN carreras ON grupos_tutorias.car_clave = carreras.car_Clave";

        if(!$resultado = $this->_db->query($this->_sql)){

        }
        if ($resultado->num_rows >0) {

            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>".$row['gpo_nombre']."</td>
                    <td>".$row['gpo_sem']."</td>
                    <td>".$row['car_Nombre']."</td>
                    <td>".$row['gpo_identificador']."</td>
                    <td>".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."</td>
                    <td>";
                    echo '<div class="btn-group mr-2" role="group" aria-label="First group">
                            <button type="button" class="btn btn-success btn-sm" onclick="TU_edit_asign(\''.$row['gpo_clave'].'\',\''.$row['gpo_identificador'].'\');" ><span aria-hidden="true" class="glyphicon glyphicon-pencil"></span></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="GPO_Eliminar(\''.$row['gpo_clave'].'\',\''.$row['gpo_identificador'].'\', \''.$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat'].'\');"><span aria-hidden="true" class=" glyphicon glyphicon-remove"></span></button>
                        </div>
                    </td>
                </tr>';
            }
        }
    }

    public function Tutor_mod(){
        $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['user'], ENT_QUOTES)));
        $pass = mysqli_real_escape_string($this->_db,(strip_tags($_POST['pass'], ENT_QUOTES)));
        $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));

        $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.cat_Clave = ".$user;

        if(!$resultado=$this->_db->query($this->_sql)){
            echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
        }

        if($resultado->num_rows > 0){
            $row=$resultado->fetch_assoc();
            $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutor-' WHERE usuario.cat_clave= ".$row['cat_Clave'];
            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
            }else {
                echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);
            }
        }else{
            echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
        }
    }

    public function Tutor_sup(){
        if($_POST['tip']=='pro') {
            $this->_sql = "SELECT * FROM usuario WHERE u_Clave=" . $_POST['id_cat'] . " AND u_Tipo LIKE '%-Tutor-%'";
        }
        elseif ($_POST['tip'=='psi']){
            $this->_sql = "SELECT * FROM usuario WHERE u_Clave=" . $_POST['id_cat'] . " AND u_Tipo LIKE '%-Tutor-%'";
        }
        if(!$resultado=$this->_db->query($this->_sql)){
            #echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
        }
       if($resultado->num_rows == 0){
           echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
       }else{
           $this->_sql="UPDATE usuario SET u_Tipo=REPLACE(u_Tipo, '-Tutor-', '-') WHERE u_Clave = ".$_POST['id_cat'];

           if( $this->_db->query($this->_sql)){
               echo json_encode(['err'=> false,'text' => 'Los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
           }else{
               echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.", 'class'=> "alert-warning alert-dismissable" ]);
           }
       }
    }

    function insertarGrupo(){
        $ClavDoc = ($_POST['clavcat']);
        $Sem = ($_POST['semestre']);
        $Car = ($_POST['carrera']);
        $Iden = $this->_db->real_escape_string($_POST['Grupo']);

        $sql="INSERT INTO grupos_tutorias (cat_clave,gpo_sem,car_clave,gpo_identificador) values(?,?,?,?)";

        if ($Grupos=$this->_db->prepare($sql)) {
            $Grupos->bind_param('siss', $ClavDoc,$Sem,$Car,$Iden);
            if (!$Grupos->execute()) {
                echo json_encode(['error'=>true, 'er'=> "Error en" . $Grupos->errno .$Grupos->error .$sql ]);
            }
            else {
                echo json_encode(['error'=>false, 'res'=> "Se creo el grupo con un tutor" ]);
            }
        }
        else {
            echo json_encode(['error'=>true, 'er'=> "Error en" . $Grupos->error ]);

        }
    }

    function editarGrupo(){
        $ClavDoc = ($_POST['clavcatEdit']);
        $Iden = $_POST['idGrupEdit'];

        $this->_sql="SELECT * FROM grupos_tutorias WHERE grupos_tutorias.gpo_clave = ".$Iden;

        if(!$resultado=$this->_db->query($this->_sql)){
            echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
        }

        $sql="UPDATE grupos_tutorias SET cat_clave = $ClavDoc WHERE gpo_clave= $Iden";

        $resultado=$this->_db->query($sql);
        if($this->_db->affected_rows > 0){
            echo json_encode(['error' => FALSE, 'res'=> "Se ha cambiado el Tutor del grupo seleccionado" ]);
        }else {
            echo json_encode(['error' => TRUE, 'er'=> "error en update".$this->_db->error.$this->_db->errno ]);
        }
    }

    public function GPO_sup($gpo){
        $this->_sql = "DELETE FROM sta.grupos_tutorias WHERE gpo_clave = $gpo";

        if( $this->_db->query($this->_sql)){
            echo json_encode(['err'=> false,'text' => 'Los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
        }else{
            echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.", 'class'=> "alert-warning alert-dismissable" ]);
        }
    }

    public function Tutor_reg(){
        $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['clavcat'], ENT_QUOTES)));

        $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.u_Clave = ".$user;

        if(!$resultado=$this->_db->query($this->_sql)){
            echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
        }

        if($resultado->num_rows > 0){
            $row=$resultado->fetch_assoc();
            $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutor-' WHERE usuario.u_Clave= ".$user;
            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
            }else {
                echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
            }
        }else{
            echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
        }
    }

    public function Tutor_reg2(){
        $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nombredoc'], ENT_QUOTES)));
        $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['APa'], ENT_QUOTES)));
        $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['AMa'], ENT_QUOTES)));
        $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));

        $ran=0;
        while (true){
            $ran = random_int(1, 399);

            $s = "SELECT * FROM catedratico_datos_personales where cat_Clave = 'T$ran'";

            $resultado = $this->_db->query($s);
            if ($resultado->num_rows ==0) {
                break;
            }
        }

        $this->_sql="INSERT INTO catedratico_datos_personales ( cat_Clave,  cat_Nombre,  cat_ApePat,  cat_ApeMat,   cat_Status,  cat_dep, cat_correo)
            values ('T".$ran."','".strtoupper($nombre)."','".strtoupper($apaterno)."','".strtoupper($amaterno)."', 'VI', 'Tutorias', '$correo')";
        $this->_db->query($this->_sql);

        if ($this->_db->affected_rows >0) {
            $this->_db->query("INSERT INTO usuario ( usuario.u_Usuario, usuario.cat_clave, usuario.med_clave, usuario.psi_clave, usuario.u_Contrasena, usuario.u_Tipo )
                values('".substr(strtoupper($nombre),0,3).strtoupper($apaterno)."', 'T$ran',null,null,'TrayectoriaITSCH','-Tutor-')");

            if(!$this->_db->affected_rows > 0 ){
                echo json_encode(['error' => TRUE, 'er'=> "Error, se guardaron los datos, pero no se asigno usuario."]);
            }
            else {
                echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron permisos al usuario como Tutor" ]);
            }
        }
        else {
            echo json_encode(['error' => false, 'res'=> "No hubo modificaciones al sistema".$this->_db->error ]);
        }
    }

    public function Generaciones($cl){
        $this->_sql="SELECT dbo.Alumnos.alu_AnioIngreso FROM dbo.Alumnos WHERE dbo.Alumnos.alu_StatusAct = 'VI' AND dbo.Alumnos.car_Clave = '$cl' GROUP BY dbo.Alumnos.alu_AnioIngreso";
        $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);
        if($stmt!=false) {
            echo '<option selected="selected" disabled="disabled">Generaciones </option>';
            while ($row=sqlsrv_fetch_array($stmt)) {
                echo "<option value='".$row['alu_AnioIngreso']."'>".$row['alu_AnioIngreso']."</option>";
            }
        }
        else {
            echo '<option selected="selected" disabled="disabled">'.sqlsrv_num_rows($stmt).'</option>';
        }
    }

    function listar_alumnosFormatos($car, $gen){
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
            $this->_sql = "SELECT
                dbo.Alumnos.alu_NumControl AS alu_no_control
                FROM dbo.Alumnos
                WHERE	dbo.Alumnos.alu_StatusAct = 'VI'
                AND dbo.Alumnos.car_Clave = ".$car."
                AND dbo.Alumnos.alu_AnioIngreso =".$gen;
                $stmts = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

                $alumnos= array();
            if ($stmts != false) {
                $con=1;
                while ($row = sqlsrv_fetch_array($stmts)) {
                     if ($mres=$this->_db->query("SELECT alumnos_caracterizacion.dp_nombre,alumnos_caracterizacion.dp_ap_paterno, alumnos_caracterizacion.dp_ap_materno, alumnos_caracterizacion.dp_carrera, alumnos_caracterizacion.al_pdf, alumnos_caracterizacion.al_pdf_valido FROM alumnos_caracterizacion WHERE alumnos_caracterizacion.se_no_control ='". $row['alu_no_control']."'")){
                        if ($mres->num_rows >0) {
                            $mrow = $mres->fetch_assoc();
                            echo
                           "<tr>
                                <td>".$con."</td>
                                <td>".$row['alu_no_control']."</td>
                                <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."'>".$mrow['dp_ap_paterno']." ".$mrow['dp_ap_materno']." ".$mrow['dp_nombre']."</a></td>
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
                                        <td><a target='blank' href='PerfilAlumno.php?NoCon=".$row['alu_no_control']."'>".$srow['ApePaterno']." ".$srow['ApeMaterno']." ".$srow['Nombre']."</a></td>
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
}

?>