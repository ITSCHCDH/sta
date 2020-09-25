<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";

    class AdminUser{

        protected $_db;
        protected $_db2;
        protected $_db3;
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

            $this->_db3 = new mysqli(DB_SERVER2,DB_USER2,DB_PASS2,DB_NAME2);
            if ($this->_db3 ->connect_errno) {
                echo "Fallo al conectar a MySQL: ". $this->_db3->connect_error;
                return;
            }
            if (!$this->_db3->set_charset("utf8")) {
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

        public function sice(){
            $err=0;
            $pas=0;
            $noact=0;
            $this->_sql="SELECT
                dbo.Catedraticos.cat_Nombre,
                dbo.Catedraticos.cat_ApePat,
                dbo.Catedraticos.cat_ApeMat,
                dbo.Catedraticos.cat_Clave,
                dbo.Departamentos.dep_Nombre,
                dbo.Catedraticos.cat_Status
                FROM
                dbo.Catedraticos
                INNER JOIN dbo.Departamentos ON dbo.Catedraticos.dep_Clave = dbo.Departamentos.dep_Clave
                WHERE
                dbo.Catedraticos.cat_Status = 'VI'";

            $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if (!$resul = $this->_db->query("INSERT INTO catedratico_datos_personales ( cat_Clave,  cat_Nombre,  cat_ApePat,  cat_ApeMat,   cat_Status,  cat_dep)
                    SELECT * FROM (SELECT '".$row['cat_Clave']."', '".$row['cat_Nombre']."',  '".$row['cat_ApePat']."',  '".$row['cat_ApeMat']."',   '".$row['cat_Status']."',  '".$row['dep_Nombre']."') AS tmp
                    WHERE NOT EXISTS ( SELECT cat_Clave FROM catedratico_datos_personales	WHERE cat_Clave = '".$row['cat_Clave']."' ) LIMIT 1;")) {

                }
                if ($this->_db->affected_rows ==1) {
                    if (!$passs=$this->_db->query("INSERT INTO usuario ( usuario.u_Usuario, usuario.cat_clave, usuario.med_clave, usuario.psi_clave, usuario.u_Contrasena, usuario.u_Tipo )
                        SELECT * FROM (SELECT '".substr($row['cat_Nombre'],0,3).$row['cat_ApePat']."' AS Col1, ".$row['cat_Clave']." AS Col2, null AS Col3, null AS Col4, 'TrayectoriaITSCH' AS Col5, 'Profe' AS Col6) AS tmp
                        WHERE NOT EXISTS ( SELECT cat_Clave FROM usuario WHERE cat_Clave = ".$row['cat_Clave'].") LIMIT 1;")){
                            echo $this->_db->errno .$this->_db->error;
                        }

                    if(!$this->_db->affected_rows ==1 ){
                        $err+=1;
                    }
                    else {
                        $pas+=1;
                    }
                }
                else{
                    $noact++;
                }
            }
            sqlsrv_free_stmt($stmt);
            if ($pas>0) {
                echo json_encode(['error' => false, 'res'=> "Se agregaron ".$pas." Docentes y ".$err." errores." ]);
            }else if($noact>0){
                echo json_encode(['error' => false, 'res'=> "No hubo modificaciones al sistema" ]);
            }else {
                echo json_encode(['error' => true, 'er'=> "error en replace".$pas." ".$err." ".$noact ]);
            }
        }

        public function catedraticos(){
            $this->_sql="SELECT
                catedratico_datos_personales.cat_Clave,
                catedratico_datos_personales.cat_Nombre,
                catedratico_datos_personales.cat_ApePat,
                catedratico_datos_personales.cat_ApeMat,
                catedratico_datos_personales.cat_dep,
                catedratico_datos_personales.cat_Status
                FROM
                catedratico_datos_personales
                WHERE
                cat_Status = 'VI'
                AND
                cat_Clave NOT IN (SELECT usuario.u_Clave FROM usuario WHERE usuario.u_Tipo LIKE '%Tutoria%')
                ORDER BY cat_dep, cat_ApePat, cat_ApeMat ASC";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<option label='".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."' value='".$row['cat_Clave']."'>";
                }
            }
        }

        public function atuto_crud(){
            $this->_sql="SELECT
                catedratico_datos_personales.cat_Clave,
                catedratico_datos_personales.cat_Nombre,
                catedratico_datos_personales.cat_ApePat,
                catedratico_datos_personales.cat_ApeMat,
                catedratico_datos_personales.cat_dep,
                catedratico_datos_personales.cat_Status,
                usuario.u_Tipo,
                usuario.u_Usuario
                FROM
                catedratico_datos_personales
                INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
                WHERE u_Tipo LIKE '%Tutoria%'
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
                                onclick="AT_Eliminar('.$row['cat_Clave'].', \''.$row["cat_Nombre"]." ".$row["cat_ApePat"]." ".$row["cat_ApeMat"].'\');">
                                <i class="icon_close_alt2"></i>  Eliminar
                            </button>
                        </td>
                    </tr>';
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
                u_Tipo LIKE '%Tutor' OR u_Tipo LIKE '%Tutor-%'
                ORDER BY cat_dep, cat_ApePat, cat_ApeMat ASC";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {

                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                        <td>".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."</td>
                        <td>".$row['cat_dep']."</td>
                        <td>".$row['u_Usuario']."</td>";
                        /*<td>";
                        echo '<button type="button" class="btn btn-danger btn-sm"
                                onclick="TU_Eliminar('.$row['u_Clave'].', \''.$row["cat_Nombre"]." ".$row["cat_ApePat"]." ".$row["cat_ApeMat"].'\');">
                                <i class="icon_close_alt2"></i>  Eliminar
                            </button>
                        </td>*/
                    echo '</tr>';
                }
            }
        }

        public function doc_crud(){
            $this->_sql="SELECT
                catedratico_datos_personales.cat_Clave,
                catedratico_datos_personales.cat_Nombre,
                catedratico_datos_personales.cat_ApePat,
                catedratico_datos_personales.cat_ApeMat,
                catedratico_datos_personales.cat_dep,
                catedratico_datos_personales.cat_Status,
                usuario.u_Tipo,
                usuario.u_Usuario
                FROM
                catedratico_datos_personales
                INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
                WHERE u_Tipo LIKE '%Profe%'
                AND cat_Status = 'VI'
                ORDER BY cat_dep, cat_ApePat, cat_ApeMat, cat_Nombre ASC";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                        <td>".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."</td>
                        <td>".$row['cat_dep']."</td>
                        <td>".$row['u_Usuario']."</td>
                        <td>";
                        echo '<button type="button" class="btn btn-warning btn-sm"
                                onclick="DOC_Edit('
                                .$row['cat_Clave'].'
                                , \''.$row["cat_Nombre"].'\',\''.
                                $row["cat_ApePat"].'\', \''.
                                $row["cat_ApeMat"].'\');">
                                <i class="icon_pencil-edit"></i>  Editar
                            </button>
                        </td>
                    </tr>';
                }
            }
        }

        public function jefes_crud(){
            $this->_sql="SELECT
                catedratico_datos_personales.cat_Clave,
                catedratico_datos_personales.cat_Nombre,
                catedratico_datos_personales.cat_ApePat,
                catedratico_datos_personales.cat_ApeMat,
                catedratico_datos_personales.cat_dep,
                catedratico_datos_personales.cat_Status,
                usuario.u_jefe_carrera,
                usuario.u_Tipo,
                usuario.u_Usuario,
                usuario.u_Clave
                FROM
                catedratico_datos_personales
                INNER JOIN usuario ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
                WHERE u_Tipo LIKE '%Jefe%'
                AND cat_Status = 'VI'
                ORDER BY cat_dep, cat_ApePat, cat_ApeMat, cat_Nombre ASC";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                        <td>".$row['cat_Nombre']." ".$row['cat_ApePat']." ".$row['cat_ApeMat']."</td>
                        <td>".$this->Carrera($row['u_jefe_carrera'])."</td>
                        <td>".$row['u_Usuario']."</td>
                        <td>";
                        $profe=strpos($row['u_Tipo'], 'Profe');

                        /*if ($profe=== false && !is_numeric($profe)) {
                            echo '<button type="button" class="btn btn-warning btn-sm"
                                    onclick="JEF_Edit(\''
                                    .$row['cat_Clave'].'\'
                                    , \''.$row["cat_Nombre"].'\',\''.
                                    $row["cat_ApePat"].'\', \''.
                                    $row["cat_ApeMat"].'\');">
                                    <i class="icon_pencil-edit"></i>  Editar
                                </button>';
                        }*/
                        echo '
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="JFE_Eliminar('.$row['u_Clave'].", '".$row['u_jefe_carrera'].'\', \''.$row["cat_Nombre"]." ".$row["cat_ApePat"]." ".$row["cat_ApeMat"].'\');">
                                <i class="icon_close_alt2"></i>  Eliminar
                            </button>
                        </td>
                    </tr>';
                }
            }
        }

        public function medic_crud(){
            $this->_sql="SELECT
                datos_doctor.dd_id_doctor,
                usuario.med_clave,
                datos_doctor.dd_nombre,
                datos_doctor.dd_apellido_paterno,
                datos_doctor.dd_apellido_materno,
                datos_doctor.dd_cedula_prof,
                usuario.u_Usuario
                FROM
                datos_doctor
                INNER JOIN usuario ON usuario.med_clave = datos_doctor.dd_id_doctor";

            if(!$resultado = $this->_db->query($this->_sql)){
                echo "Errno: " . $this->_db->errno . "\n";
                echo "Error: " . $this->_db->error . "\n";
            }
            if ($resultado->num_rows >0) {

                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                        <td>".$row['dd_nombre']." ".$row['dd_apellido_paterno']." ".$row['dd_apellido_materno']."</td>
                        <td>".$row['dd_cedula_prof']."</td>
                        <td>".$row['u_Usuario']."</td>
                        <td>";
                    echo '  <button type="button" class="btn btn-warning btn-sm"
                                onclick="MED_Edit('
                                .$row['med_clave'].'
                                , \''.$row["dd_nombre"].'\',\''.
                                $row["dd_apellido_paterno"].'\', \''.
                                $row["dd_apellido_materno"].'\', '.
                                $row['dd_cedula_prof'].');">
                                <i class="icon_pencil-edit"></i>  Editar
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="MED_Eliminar('.$row['med_clave'].", ".$row['dd_cedula_prof'].', \''.$row["dd_nombre"]." ".$row["dd_apellido_paterno"]." ".$row["dd_apellido_materno"].'\');">
                                <i class="icon_close_alt2"></i>  Eliminar
                            </button>
                        </td>
                    </tr>';
                }
            }
        }

        public function psic_crud(){
            $this->_sql="SELECT
                psicologos_datos.psi_id,
                usuario.psi_clave,
                psicologos_datos.psi_nom,
                psicologos_datos.psi_apePat,
                psicologos_datos.psi_apeMat,
                psicologos_datos.psi_cedula_prof,
                usuario.u_Usuario
                FROM
                psicologos_datos
                INNER JOIN usuario ON usuario.psi_clave = psicologos_datos.psi_id";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {

                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                        <td>".$row['psi_nom']." ".$row['psi_apePat']." ".$row['psi_apeMat']."</td>
                        <td>".$row['psi_cedula_prof']."</td>
                        <td>".$row['u_Usuario']."</td>
                        <td>";
                    echo '  <button type="button" class="btn btn-warning btn-sm"
                                onclick="PSI_Edit('
                                .$row['psi_clave'].'
                                , \''.$row["psi_nom"].'\',\''.
                                $row["psi_apePat"].'\', \''.
                                $row["psi_apeMat"].'\', '.
                                $row['psi_cedula_prof'].');">
                                <i class="icon_pencil-edit"></i>  Editar
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="PSI_Eliminar('.$row['psi_clave'].", ".$row['psi_cedula_prof'].', \''.$row["psi_nom"]." ".$row["psi_apePat"]." ".$row["psi_apeMat"].'\');">
                                <i class="icon_close_alt2"></i>  Eliminar
                            </button>
                        </td>
                    </tr>';
                }
            }
        }

        public function ATutor_reg(){
            $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['user'], ENT_QUOTES)));
            $pass = mysqli_real_escape_string($this->_db,(strip_tags($_POST['pass'], ENT_QUOTES)));
            $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));

            $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.cat_Clave = ".$user;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutoria' WHERE usuario.cat_clave= ".$row['cat_Clave'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
            }
        }

        public function Tutor_reg(){
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
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
            }
        }

        public function Jefe_reg(){
            $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nombredoc'], ENT_QUOTES)));
            $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['APa'], ENT_QUOTES)));
            $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['AMa'], ENT_QUOTES)));
            $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));
            $car = mysqli_real_escape_string($this->_db,(strip_tags($_POST['DirCarrera'], ENT_QUOTES)));
            $carre = $this->Carrera($car);


            $this->_sql="INSERT INTO catedratico_datos_personales ( cat_Clave,  cat_Nombre,  cat_ApePat,  cat_ApeMat,   cat_Status,  cat_dep, cat_correo)
                values ('J".$car."','".strtoupper($nombre)."','".strtoupper($apaterno)."','".strtoupper($amaterno)."', 'VI', '$carre', '$correo')";
            $this->_db->query($this->_sql);

            if ($this->_db->affected_rows >0) {
                $this->_db->query("INSERT INTO usuario ( usuario.u_Usuario, usuario.cat_clave, usuario.med_clave, usuario.psi_clave, usuario.u_Contrasena, usuario.u_Tipo, usuario.u_jefe_carrera )
                    values('".substr(strtoupper($nombre),0,3).strtoupper($apaterno)."', '$car',null,null,'TrayectoriaITSCH','-Jefe-','$car')");

                if(!$this->_db->affected_rows > 0 ){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, se guardaron los datos, pero no se asigno usuario."]);
                }
                else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron permisos al usuario como Jefe de carrera" ]);
                }
            }
            else {
                echo json_encode(['error' => false, 'res'=> "No hubo modificaciones al sistema ".$this->_db->error ]);
            }
        }

        public function Jefe_reg2(){
            $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['clavcat'], ENT_QUOTES)));
            $carr = mysqli_real_escape_string($this->_db,(strip_tags($_POST['DirCarrera'], ENT_QUOTES)));

            $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.cat_Clave = ".$user;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['err' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Jefe-', u_jefe_carrera='".$_POST['DirCarrera']."' WHERE usuario.cat_clave= ".$row['cat_Clave'];
                mysqli_query( $this->_db, $this->_sql);
                if($this->_db->affected_rows > 0){
                    echo json_encode(['err' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }else{
                    echo json_encode(['err'=> true,'er' => "Error, no se pudo agregar los nuevos datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
                }
            }else{
                echo json_encode(['err' => TRUE, 'er'=> "error en resul".$this->_sql ]);
            }
        }

        public function psic_reg(){
            $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nom'], ENT_QUOTES)));
            $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ap'], ENT_QUOTES)));
            $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['am'], ENT_QUOTES)));
            $cedula = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ced'], ENT_QUOTES)));

            $this->_sql="SELECT * FROM psicologos_datos WHERE psicologos_datos.psi_cedula_prof =".$cedula;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows == 0){
                $this->_sql="INSERT INTO psicologos_datos (psi_cedula_prof, psi_nom, psi_apePat, psi_apeMat)
                    values ('".$cedula."','".$nombre."','".$apaterno."','".$amaterno."')";
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, no se pudo agregar los datos." ]);
                }else {
                    $this->_sql="INSERT INTO usuario ( u_Usuario, cat_clave, med_clave, psi_clave, u_Contrasena, u_Tipo)  VALUES ('". substr($nombre,0,3).$apaterno. "', null, null, (SELECT psi_id FROM psicologos_datos WHERE psi_cedula_prof='".$cedula."'), 'PsicoITSCH', '-Psic' )";
                    if(!$resultado=$this->_db->query($this->_sql)){
                        echo json_encode(['error' => TRUE, 'er'=> "Error, se guardaron los datos, pero no se asigno usuario.".$this->_sql ]);
                    }
                    else {
                        echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron permisos al usuario como Medico" ]);
                    }

                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "Error, ya existe la cedula dentro del sistema." ]);
            }
        }

        public function medic_reg(){
            $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nom'], ENT_QUOTES)));
            $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ap'], ENT_QUOTES)));
            $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['am'], ENT_QUOTES)));
            $cedula = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ced'], ENT_QUOTES)));

            $this->_sql="SELECT * FROM datos_doctor WHERE datos_doctor.dd_cedula_prof =".$cedula;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows == 0){
                $this->_sql="INSERT INTO datos_doctor (dd_cedula_prof, dd_nombre, dd_apellido_paterno, dd_apellido_materno)
                    values ('".$cedula."','".$nombre."','".$apaterno."','".$amaterno."')";
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, no se pudo agregar los datos." ]);
                }else {
                    $this->_sql="INSERT INTO usuario ( u_Usuario, cat_clave, psi_clave, med_clave, u_Contrasena, u_Tipo)
                        VALUES ('".substr($nombre,0,3).$apaterno."', null, null, (SELECT dd_id_doctor FROM datos_doctor WHERE dd_cedula_prof='".$cedula."'), 'MedicoITSCH', '-Medic' )";
                    if(!$resultado=$this->_db->query($this->_sql)){
                        echo json_encode(['error' => TRUE, 'er'=> "Error, se guardaron los datos, pero no se asigno usuario." ]);
                    }
                    else {
                        echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron permisos al usuario como Medico" ]);
                    }

                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "Error, ya existe la cedula dentro del sistema." ]);
            }
        }

        public function ATutor_mod(){
            $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['user'], ENT_QUOTES)));
            $pass = mysqli_real_escape_string($this->_db,(strip_tags($_POST['pass'], ENT_QUOTES)));
            $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));

            $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.cat_Clave = ".$user;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutoria' WHERE usuario.cat_clave= ".$row['cat_Clave'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
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
                $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutor' WHERE usuario.cat_clave= ".$row['cat_Clave'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
            }
        }

        public function medic_mod(){
            $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['user'], ENT_QUOTES)));
            $pass = mysqli_real_escape_string($this->_db,(strip_tags($_POST['pass'], ENT_QUOTES)));
            $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));

            $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.cat_Clave = ".$user;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutoria' WHERE usuario.cat_clave= ".$row['cat_Clave'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
            }
        }

        public function psic_mod(){
            $user = mysqli_real_escape_string($this->_db,(strip_tags($_POST['user'], ENT_QUOTES)));
            $pass = mysqli_real_escape_string($this->_db,(strip_tags($_POST['pass'], ENT_QUOTES)));
            $correo = mysqli_real_escape_string($this->_db,(strip_tags($_POST['correo'], ENT_QUOTES)));

            $this->_sql="SELECT usuario.cat_Clave, usuario.u_Tipo FROM usuario WHERE usuario.cat_Clave = ".$user;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                $this->_sql="UPDATE usuario SET usuario.u_Tipo ='".$row['u_Tipo']."-Tutoria' WHERE usuario.cat_clave= ".$row['cat_Clave'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "error en update" ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se otorgaron nuevos permisos al usuario" ]);;
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "error en resul".$this->_sql ]);
            }
        }

        public function ATutor_sup(){
             $this->_sql="SELECT * FROM usuario WHERE cat_clave=".$_POST['id_cat'];

             if(!$resultado=$this->_db->query($this->_sql)){
                 #echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
             }
            if($resultado->num_rows == 0){
                echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
            }else{
                $this->_sql="UPDATE usuario SET u_Tipo=REPLACE(u_Tipo, '-Tutoria', '') WHERE cat_clave = ".$_POST['id_cat'];
                $delete = mysqli_query($this->_db, $this->_sql);
                if($delete){
                    echo json_encode(['err'=> false,'text' => 'Bien hecho, los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
                }else{
                    echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
                }
            }
        }

        public function Tutor_sup(){
            $this->_sql="SELECT * FROM usuario WHERE cat_clave=".$_POST['id_cat']." AND u_Tipo LIKE '%-Tutor-%'";

            if(!$resultado=$this->_db->query($this->_sql)){
                #echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }
           if($resultado->num_rows == 0){
               echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
           }else{
               $this->_sql="UPDATE usuario SET u_Tipo=REPLACE(u_Tipo, '-Tutor-', '-') WHERE cat_clave = ".$_POST['id_cat'];
               $delete = mysqli_query(  $this->_db, $this->_sql);
               if($delete){
                   echo json_encode(['err'=> false,'text' => 'Bien hecho, los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
               }else{
                   echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
               }
           }
        }

        public function medic_sup(){
            $this->_sql="SELECT * FROM usuario WHERE med_clave=".$_POST['id_cat']." AND u_Tipo LIKE '%-Medic%'";

            if(!$resultado=$this->_db->query($this->_sql)){
                #echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }
           if($resultado->num_rows == 0){
               echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
           }else{
               $this->_sql="DELETE FROM `datos_doctor` WHERE (`dd_id_doctor`='".$_POST['id_cat']."') AND (`dd_cedula_prof`='".$_POST['ced']."')";
               $delete = mysqli_query( $this->_db, $this->_sql);
               if($delete){
                   echo json_encode(['err'=> false,'text' => 'Bien hecho, los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
               }else{
                   echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
               }
           }
        }

        public function psic_sup(){
            $this->_sql="SELECT * FROM usuario WHERE psi_clave=".$_POST['id_cat']." AND u_Tipo LIKE '%Psic%'";

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos qq.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
                #echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }
            elseif($resultado->num_rows == 0){
               echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
           }else{
               $this->_sql="DELETE FROM `psicologos_datos` WHERE (`psi_id`='".$_POST['id_cat']."') AND (`psi_cedula_prof`='".$_POST['ced']."')";
               $delete = mysqli_query( $this->_db, $this->_sql);
               if($delete){
                   echo json_encode(['err'=> false,'text' => 'Bien hecho, los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
               }else{
                   echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
               }
           }
        }

        public function jfe_sup(){
            $this->_sql="SELECT * FROM usuario WHERE u_Clave=".$_POST['id_cat']." AND u_Tipo LIKE '%-Jefe%'";

            if(!$resultado=$this->_db->query($this->_sql)){
                #echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }
           if($resultado->num_rows == 0){
               echo json_encode(['err'=> true,'text' => 'No se encontraron datos.', 'class'=> "alert-danger alert-dismissable" ]);
           }else{
               $this->_sql="UPDATE usuario SET u_Tipo=REPLACE(u_Tipo, 'Jefe', '-'), u_jefe_carrera= '00' WHERE u_Clave = ".$_POST['id_cat'];

               $delete = mysqli_query( $this->_db, $this->_sql);
               if($this->_db->affected_rows > 0){
                   echo json_encode(['err'=> false,'text' => 'Bien hecho, los datos han sido eliminados correctamente.', 'class'=> "alert-success alert-dismissable" ]);
               }else{
                   echo json_encode(['err'=> true,'text' => "Error, no se pudo eliminar los datos.".$this->_sql, 'class'=> "alert-warning alert-dismissable" ]);
               }
           }
        }

        public function medic_edit(){
            $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nom'], ENT_QUOTES)));
            $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ap'], ENT_QUOTES)));
            $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['am'], ENT_QUOTES)));
            $cedula = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ced'], ENT_QUOTES)));
            $cedula2 = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ced2'], ENT_QUOTES)));

            $this->_sql="SELECT * FROM datos_doctor WHERE datos_doctor.dd_cedula_prof =".$cedula2;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $this->_sql="UPDATE datos_doctor set dd_nombre = '".$nombre."', dd_apellido_paterno = '".$apaterno."', dd_apellido_materno = '".$amaterno."', dd_cedula_prof = ".$cedula."  WHERE dd_cedula_prof =".$cedula2;
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, no se pudo modificar los datos.".$this->_sql ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se modificaron los datos." ]);
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "Error, No existe la cedula dentro del sistema." ]);
            }
        }

        public function psic_edit(){
            $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nom'], ENT_QUOTES)));
            $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ap'], ENT_QUOTES)));
            $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['am'], ENT_QUOTES)));
            $cedula = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ced'], ENT_QUOTES)));
            $cedula2 = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ced2'], ENT_QUOTES)));

            $this->_sql="SELECT * FROM psicologos_datos WHERE psicologos_datos.psi_cedula_prof =".$cedula2;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $this->_sql="UPDATE psicologos_datos set psi_nom = '".$_POST['nom']."', psi_apePat = '".$_POST['ap']."', psi_apeMat = '".$_POST['am']."', psi_cedula_prof = ".$_POST['ced']."  WHERE psi_id =".$_POST['id'];
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, no se pudo modificar los datos.".$this->_sql ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se modificaron los datos." ]);
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "Error, No existe la cedula dentro del sistema." ]);
            }
        }

        public function doc_edit(){
            $ide = mysqli_real_escape_string($this->_db,(strip_tags($_POST['id'], ENT_QUOTES)));
            $nombre = mysqli_real_escape_string($this->_db,(strip_tags($_POST['nom'], ENT_QUOTES)));
            $apaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['ap'], ENT_QUOTES)));
            $amaterno = mysqli_real_escape_string($this->_db,(strip_tags($_POST['am'], ENT_QUOTES)));

            $this->_sql="SELECT * FROM catedratico_datos_personales WHERE cat_Clave =".$ide;

            if(!$resultado=$this->_db->query($this->_sql)){
                echo json_encode(['error' => TRUE, 'er'=> "error en select".$this->_sql ]);
            }

            if($resultado->num_rows > 0){
                $this->_sql="UPDATE catedratico_datos_personales SET cat_Nombre = '".$nombre."',  cat_ApePat = '".$apaterno."', cat_ApeMat = '".$amaterno."' WHERE cat_Clave =".$ide;
                if(!$resultado=$this->_db->query($this->_sql)){
                    echo json_encode(['error' => TRUE, 'er'=> "Error, no se pudo modificar los datos.".$this->_sql ]);
                }else {
                    echo json_encode(['error' => FALSE, 'res'=> "Se modificaron los datos." ]);
                }
            }else{
                echo json_encode(['error' => TRUE, 'er'=> "Error, No existe la cedula dentro del sistema." ]);
            }
        }

        public function caracterizacion(){
            $nocon= array();
            $err=0;
            $pas=0;
            $nCon = "SELECT
                    dbo.Alumnos.alu_NumControl,
                    dbo.alumnos.alu_nombre,
                    dbo.alumnos.alu_apepaterno,
                    dbo.alumnos.alu_apematerno
                    FROM dbo.Alumnos
                    WHERE	dbo.Alumnos.alu_StatusAct = 'VI'
                    AND dbo.Alumnos.alu_AnioIngreso =".date("Y");
            if ($stmt = sqlsrv_query ( $this->_db2, $nCon) ){
                while ($rowS = sqlsrv_fetch_array($stmt)){
                    $this->_sql="SELECT
                        alumnos_caracterizacion.se_no_control,
                        alumnos_datos_personales.dp_nombre,
                        alumnos_datos_personales.dp_ap_paterno,
                        alumnos_datos_personales.dp_ap_materno,
                        alumnos_datos_personales.dp_sexo,
                        alumnos_datos_personales.dp_email,
                        alumnos_datos_personales.dp_tipo_sangre,
                        alumnos_datos_personales.dp_carrera,
                        alumnos_caracterizacion.se_fecha_ficha,
                        alumnos_caracterizacion.se_procedencia_foraneo,
                        alumnos_caracterizacion.se_esc_proced,
                        alumnos_caracterizacion.se_esc_proced_mun,
                        alumnos_caracterizacion.se_esc_proced_edo,
                        alumnos_caracterizacion.se_bachillerato_prom,
                        alumnos_caracterizacion.se_bachillerato_tipo,
                        alumnos_caracterizacion.sa_corrimiento_listas,
                        alumnos_caracterizacion.sa_grupo_gen,
                        alumnos_caracterizacion.sa_exam_adm_res,
                        alumnos_caracterizacion.tut_probpsico_depresion,
                        alumnos_caracterizacion.tut_probpsico_autoestima,
                        alumnos_caracterizacion.tut_probpsico_machover,
                        alumnos_caracterizacion.tut_probpsico_diagnostico,
                        alumnos_caracterizacion.tut_probmed_peso,
                        alumnos_caracterizacion.tut_probmed_enf,
                        alumnos_caracterizacion.tut_probmed_ad,
                        alumnos_caracterizacion.tut_probmed_alergias,
                        alumnos_caracterizacion.tut_probmed_alergmed,
                        alumnos_caracterizacion.tut_probmed_enf_hered,
                        alumnos_caracterizacion.tut_probfam_res,
                        alumnos_caracterizacion.tut_hab_estudio,
                        alumnos_caracterizacion.tut_orient_vocacional,
                        alumnos_caracterizacion.tut_opc_esc,
                        alumnos_caracterizacion.tut_carrera_trunca,
                        alumnos_caracterizacion.doc_curso_algebra_res,
                        alumnos_caracterizacion.doc_curso_regul_res,
                        alumnos_caracterizacion.est_se_ingresos_todos,
                        alumnos_caracterizacion.se_ocasiones_ingreso,
                        alumnos_caracterizacion.tut_becas,
                        alumnos_caracterizacion.tut_becas_detalles,
                        alumnos_caracterizacion.tut_vivir_con,
                        alumnos_caracterizacion.tut_trabajo_lugar,
                        alumnos_caracterizacion.tut_trabajo_horario,
                        alumnos_ficha_medica.fm_peso,
                        alumnos_ficha_medica.fm_talla,
                        alumnos_ficha_medica.fm_imc,
                        alumnos_ficha_medica.fm_peso_diagnostico,
                        alumnos_ficha_medica.fm_edad
                        FROM
                        alumnos_caracterizacion
                        INNER JOIN alumnos_datos_personales ON alumnos_caracterizacion.se_no_control = alumnos_datos_personales.se_no_control
                        INNER JOIN alumnos_ficha_medica ON alumnos_ficha_medica.no_control = alumnos_datos_personales.se_no_control
                        WHERE alumnos_datos_personales.dp_nombre = '". utf8_encode($rowS['alu_nombre'])."' AND
                        alumnos_datos_personales.dp_ap_paterno= '".utf8_encode($rowS['alu_apepaterno'])."' AND
                        alumnos_datos_personales.dp_ap_materno= '".utf8_encode($rowS['alu_apematerno'])."' ";

                    if($res=$this->_db3->query($this->_sql)){
                        if ($res->num_rows > 0) {
                            $row = $res->fetch_array();
                            $se_no_control= "'".$rowS['alu_NumControl']."'";
                            $dp_nombre= isset($row['dp_nombre'])?"'".$row['dp_nombre']."'":"null";
                            $dp_ap_paterno= isset($row['dp_ap_paterno'])?"'".$row['dp_ap_paterno']."'":"null";
                            $dp_ap_materno= isset($row['dp_ap_materno'])?"'".$row['dp_ap_materno']."'":"null";
                            $dp_sexo= isset($row['dp_sexo'])?"'".$row['dp_sexo']."'":"null";
                            $dp_email= isset($row['dp_email'])?"'".$row['dp_email']."'":"null";
                            $dp_tipo_sangre= isset($row['dp_tipo_sangre'])?"'".$row['dp_tipo_sangre']."'":"null";
                            $dp_carrera= isset($row['dp_carrera'])?"'".$row['dp_carrera']."'":"null";
                            $se_fecha_ficha= isset($row['se_fecha_ficha'])?"'".$row['se_fecha_ficha']."'":"null";
                            $se_procedencia_foraneo= isset($row['se_procedencia'])?"'".$row['se_procedencia']."'":"0";
                            $se_esc_proced= isset($row['se_esc_proced'])?"'".$row['se_esc_proced']."'":"null";
                            $se_esc_proced_mun= isset($row['se_esc_proced_mun'])?"'".$row['se_esc_proced_mun']."'":"null";
                            $se_esc_proced_edo= isset($row['se_esc_proced_edo'])?"'".$row['se_esc_proced_edo']."'":"null";
                            $se_bachillerato_prom= isset($row['se_bachillerato_prom'])?"'".$row['se_bachillerato_prom']."'":"null";
                            $se_bachillerato_tipo= isset($row['se_bachillerato_tipo'])?"'".$row['se_bachillerato_tipo']."'":"null";
                            $sa_corrimiento_listas= isset($row['sa_corrimiento_listas'])?"'".$row['sa_corrimiento_listas']."'":"null";
                            $sa_grupo_gen= isset($row['sa_grupo_gen'])?"'".$row['sa_grupo_gen']."'":"null";
                            $sa_exam_adm_res= isset($row['sa_exam_adm_res'])?"'".$row['sa_exam_adm_res']."'":"null";
                            $tut_probpsico_depresion= isset($row['tut_probpsico_depresion'])?"'".$row['tut_probpsico_depresion']."'":"null";
                            $tut_probpsico_autoestima= isset($row['tut_probpsico_autoestima'])?"'".$row['tut_probpsico_autoestima']."'":"null";
                            $tut_probpsico_machover= isset($row['tut_probpsico_machover'])?"'".$row['tut_probpsico_machover']."'":"null";
                            $tut_probpsico_diagnostico= isset($row['tut_probpsico_diagnostico'])?"'".$row['tut_probpsico_diagnostico']."'":"null";
                            $tut_probmed_peso= isset($row['tut_probmed_peso'])?"'".$row['tut_probmed_peso']."'":"null";
                            $tut_probmed_enf= isset($row['tut_probmed_enf'])?"'".$row['tut_probmed_enf']."'":"null";
                            $tut_probmed_ad= isset($row['tut_probmed_ad'])?"'".$row['tut_probmed_ad']."'":"null";
                            $tut_probmed_alergias= isset($row['tut_probmed_alergias'])?"'".$row['tut_probmed_alergias']."'":"null";
                            $tut_probmed_alergmed= isset($row['tut_probmed_alergmed'])?"'".$row['tut_probmed_alergmed']."'":"null";
                            $tut_probmed_enf_hered= isset($row['tut_probmed_enf_hered'])?"'".$row['tut_probmed_enf_hered']."'":"null";
                            $tut_probfam_res= isset($row['tut_probfam_res'])?"'".$row['tut_probfam_res']."'":"null";
                            $tut_hab_estudio= isset($row['tut_hab_estudio'])?"'".$row['tut_hab_estudio']."'":"null";
                            $tut_orient_vocacional= isset($row['tut_orient_vocacional'])?"'".$row['tut_orient_vocacional']."'":"null";
                            $tut_opc_esc= isset($row['tut_opc_esc'])?"'".$row['tut_opc_esc']."'":"null";
                            $tut_carrera_trunca= isset($row['tut_carrera_trunca'])?"'".$row['tut_carrera_trunca']."'":"null";
                            $doc_curso_algebra_res= isset($row['doc_curso_algebra_res'])?"'".$row['doc_curso_algebra_res']."'":"null";
                            $doc_curso_regul_res= isset($row['doc_curso_regul_res'])?"'".$row['doc_curso_regul_res']."'":"null";
                            $est_se_ingresos_todos= isset($row['est_se_ingresos_todos'])?"'".$row['est_se_ingresos_todos']."'":"null";
                            $se_ocasiones_ingreso= isset($row['se_ocasiones_ingreso'])?"'".$row['se_ocasiones_ingreso']."'":"null";
                            $tut_becas= isset($row['tut_becas'])?$row['tut_becas']:"0";
                            $tut_becas_detalles= isset($row['tut_becas_detalles'])?"'".$row['tut_becas_detalles']."'":"null";
                            $tut_vivir_con= isset($row['tut_vivir_con'])?"'".$row['tut_vivir_con']."'":"null";
                            $tut_trabajo_lugar= isset($row['tut_trabajo_lugar'])?"'".$row['tut_trabajo_lugar']."'":"null";
                            $tut_trabajo_horario= isset($row['tut_trabajo_horario'])?"'".$row['tut_trabajo_horario']."'":"null";
                            $fm_peso= isset($row['fm_peso'])?"'".$row['fm_peso']."'":"null";
                            $fm_talla= isset($row['fm_talla'])?"'".$row['fm_talla']."'":"null";
                            $fm_imc= isset($row['fm_imc'])?"'".$row['fm_imc']."'":"null";
                            $fm_peso_diagnostico= isset($row['fm_peso_diagnostico'])?"'".$row['fm_peso_diagnostico']."'":"null";
                            $fm_edad= isset($row['fm_edad'])?"'".$row['fm_edad']."'":"null";

                            $sql2="REPLACE INTO alumnos_caracterizacion
                                (se_no_control, dp_nombre, dp_ap_paterno, dp_ap_materno, dp_sexo, dp_edad, dp_email, dp_carrera, dp_tipo_sangre, se_fecha_ficha, se_procedencia_foraneo, se_esc_proced, se_esc_proced_mun, se_esc_proced_edo, se_bachillerato_prom, se_bachillerato_tipo, sa_corrimiento_listas, sa_grupo_gen,
                                sa_exam_adm_res, tut_probpsico_depresion, tut_probpsico_autoestima, tut_probpsico_machover, tut_probpsico_diagnostico, tut_probmed_peso, tut_probmed_enf, tut_probmed_ad, tut_probmed_alergias, tut_probmed_alergmed, tut_probmed_enf_hered, tut_probfam_res, tut_hab_estudio, tut_orient_vocacional, tut_opc_esc,
                                tut_carrera_trunca, doc_curso_algebra_res, doc_curso_regul_res, est_se_ingresos_todos, se_ocasiones_ingreso, tut_becas, tut_becas_detalles, tut_vivir_con, tut_trabajo_lugar,  tut_trabajo_horario)
                                VALUES
                                ($se_no_control, $dp_nombre, $dp_ap_paterno, $dp_ap_materno, $dp_sexo, $fm_edad, $dp_email, $dp_carrera, $dp_tipo_sangre, $se_fecha_ficha, $se_procedencia_foraneo, $se_esc_proced, $se_esc_proced_mun, $se_esc_proced_edo, $se_bachillerato_prom, $se_bachillerato_tipo, $sa_corrimiento_listas, $sa_grupo_gen,
                                $sa_exam_adm_res, $tut_probpsico_depresion, $tut_probpsico_autoestima, $tut_probpsico_machover, $tut_probpsico_diagnostico, $tut_probmed_peso, $tut_probmed_enf, $tut_probmed_ad, $tut_probmed_alergias, $tut_probmed_alergmed, $tut_probmed_enf_hered, $tut_probfam_res, $tut_hab_estudio, $tut_orient_vocacional, $tut_opc_esc,
                                $tut_carrera_trunca, $doc_curso_algebra_res, $doc_curso_regul_res, $est_se_ingresos_todos, $se_ocasiones_ingreso, $tut_becas, $tut_becas_detalles, $tut_vivir_con, $tut_trabajo_lugar, $tut_trabajo_horario)";

                            if(!$res2=$this->_db->query($sql2)){

                                $err+=1;
                            }
                            else {
                                $pas+=1;
                            }
                        }
                        else {
                            $err+=1;
                            array_push($nocon, $rowS['alu_NumControl']." ".utf8_encode($rowS['alu_nombre']). " ".utf8_encode($rowS['alu_apepaterno'])." ".utf8_encode($rowS['alu_apematerno']).$res->num_rows );
                        }
                    }
                    else{
                        $err++;
                    }

                }

                if ($pas>0) {
                    echo json_encode(['error' => false, 'res'=> "Se agregaron ".$pas." Alumnos y ".$err." alumnos de nuevo ingreso no cuentan con la caracterizacion." ]);
                }
                else {
                    echo json_encode(['error' => true, 'er'=> "error en replace ".$err ]);
                }
            }else {
                echo json_encode(['error' => true, 'er' =>'Problemas con los alumnos '.$err]);
            }

        }

        public function contTutor(){
            $this->_sql="SELECT Count(usuario.u_Clave) AS Us FROM usuario WHERE usuario.u_Tipo LIKE '%Tutor' OR usuario.u_Tipo LIKE '%Tutor-%'";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                $row = $resultado->fetch_assoc();
                echo $row['Us'];
            }
        }

        public function contProf(){
            $this->_sql="SELECT Count(usuario.u_Clave) AS Us FROM usuario WHERE usuario.u_Tipo LIKE 'Profe%'";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                $row = $resultado->fetch_assoc();
                echo $row['Us'];
            }
        }

        public function contDire(){
            $this->_sql="SELECT Count(usuario.u_Clave) AS Us FROM usuario WHERE usuario.u_Tipo LIKE '%Dire%'";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                $row = $resultado->fetch_assoc();
                echo $row['Us'];
            }
        }

        public function contTutoria(){
            $this->_sql="SELECT Count(usuario.u_Clave) AS Us FROM usuario WHERE usuario.u_Tipo LIKE '%Tutoria%'";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                $row = $resultado->fetch_assoc();
                echo $row['Us'];
            }
        }

        public function contMedic(){
            $this->_sql="SELECT Count(usuario.u_Clave) AS Us FROM usuario WHERE usuario.u_Tipo LIKE '%Medic%'";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                $row = $resultado->fetch_assoc();
                echo $row['Us'];
            }
        }

        public function contPsic(){
            $this->_sql="SELECT Count(usuario.u_Clave) AS Us FROM usuario WHERE usuario.u_Tipo LIKE '%Psic%'";

            if(!$resultado = $this->_db->query($this->_sql)){

            }
            if ($resultado->num_rows >0) {
                $row = $resultado->fetch_assoc();
                echo $row['Us'];
            }
        }

        public function Carrera($id){
            $this->_sql="SELECT carreras.car_Nombre, carreras.clave_sise FROM carreras WHERE carreras.car_Clave ='$id'";

            if(!$resultado = $this->_db->query($this->_sql)){
                return '';
            }
            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                return $row['car_Nombre'];
            }
        }

        public function ciclo(){
            $fecha = date("Ymd-His"); //Obtenemos la fecha y hora para identificar el respaldo

            // Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
            $salida_sql = DB_NAME.'_'.$fecha.'.sql';

            //Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
            $dump = "mysqldump --h".DB_HOST." -u".DB_USER." -p".DB_PASS." --opt ".DB_NAME." > $salida_sql";
            system($dump, $output); //Ejecutamos el comando para respaldo

            $zip = new ZipArchive(); //Objeto de Libreria ZipArchive

            //Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
            $salida_zip = DB_NAME.'_'.$fecha.'.zip';

            if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
                $zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
                $zip->close(); //Cerramos el ZIP
                unlink($salida_sql); //Eliminamos el archivo temporal SQL
                header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
                echo json_encode(['error' => false, 'res'=> "Se a cerrado el ciclo escolar " ]);
            }
            else {
                echo json_encode(['error' => true, 'er'=> "error en replace " ]);
            }
        }
    }
?>
