<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";

class Usuario{

    protected $_db;
    protected $_sql;

    public function __construct(){
        $this->_db = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
        if ($this->_db ->connect_errno) {
            echo "Fallo al conectar a MySQL: ". $this->_db->connect_error;
            return;
        }
        if (!$this->_db->set_charset("utf8")) {
            printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
        }
    }

    public function setPassWord($tuser,$user,$pass){
        if ($tuser=="Alu") {
            $this->_sql = "SELECT alumnos_caracterizacion.se_no_control, alumnos_caracterizacion.dp_pass_cambio, alumnos_caracterizacion.dp_contrasena FROM alumnos_caracterizacion WHERE  se_no_control = '$user'";
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

            if ($resultado->num_rows >0) {
                $sql="UPDATE sta.alumnos_caracterizacion SET dp_pass_cambio = 1, dp_contrasena = '".$pass."' WHERE se_no_control = '$user'";
                $resultado = $this->_db->query($sql);
                if($this->_db->affected_rows > 0){
                    echo json_encode(array('error' => false));
                    $_SESSION['usuario']['pd']=$pass;
                    $_SESSION['usuario']['passw']='setPass';
                }
                else {
                    echo json_encode(array('error' => true));
                }
            }
            else {
                echo json_encode(array('error' => true));
            }
        }
        else {
            $this->_sql="UPDATE sta.usuario SET u_set_pass = 1, u_Contrasena = '$pass' WHERE u_Clave = $user";
            $resultado = $this->_db->query($this->_sql);
            if($this->_db->affected_rows > 0){
                echo json_encode(array('error' => false));
                $_SESSION['usuario']['passw']='setPass';
                $_SESSION['usuario']['pd']=$pass;
            }
            else {
                echo json_encode(array('error' => true));
            }
        }

    }

    public function perfil($tuser, $user){
        if ($tuser=="Alu") {
            $this->_sql= "SELECT
                alumnos_caracterizacion.se_no_control, alumnos_caracterizacion.dp_nombre,
                alumnos_caracterizacion.dp_email,
                alumnos_caracterizacion.dp_ap_paterno, alumnos_caracterizacion.dp_ap_materno,
                alumnos_caracterizacion.dp_contrasena, alumnos_caracterizacion.dp_sexo,
                alumnos_caracterizacion.dp_carrera, alumnos_caracterizacion.dp_tel, alumnos_caracterizacion.al_img,
                fiden_perfil.fip_fecha_nac
                FROM alumnos_caracterizacion left JOIN fiden_perfil
                ON fiden_perfil.no_control = alumnos_caracterizacion.se_no_control
                WHERE alumnos_caracterizacion.se_no_control = '$user'";
            if(!$resultado = $this->_db->query($this->_sql)){
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
            }

            if ($resultado->num_rows >0) {
                $row=$resultado->fetch_assoc();
                //$f = explode("-",$row['fip_fecha_nac']);
                //$miFecha= gmmktime(12,0,0,$f[1],$f[2],$f[0]);
                echo '<div id="perfil" class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">'.$row['dp_nombre'].' '.$row['dp_ap_paterno'].' '.$row['dp_ap_materno'].'</h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-md-3 col-lg-3 " align="center">
                                                <img alt="User Pic" src="/sta/assets/images/'.($row['al_img']!=""?'Alu/'.$row['al_img']:'avatar1_small.jpg').'" class="img-circle img-responsive">
                                            </div>
                                            <div class=" col-md-9 col-lg-9 ">
                                                <table class="table table-user-information">
                                                    <tbody>
                                                        <tr>
                                                            <td>Carrera:
                                                                <td>'.$row['dp_carrera'].'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Fecha de nacimiento</td>
                                                            <td>'.utf8_encode(/*strftime("%A, %d de %B de %Y", $miFecha)*/$row['fip_fecha_nac']).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sexo</td>
                                                            <td>'.(trim($row['dp_sexo'])=='H'?'Hombre':'Mujer').'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Correo:</td>
                                                            <td><a href="#">'.$row['dp_email'].'</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Numero de teléfono</td>
                                                            <td>'.$row['dp_tel'].'</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer center">
                                        <a href="#" data-original-title="Editar Usuario" data-toggle="tooltip" type="button" class="btn btn-warning" onclick="$(\'#perfil\').hide(\'slow\'); $(\'#Editar_img\').show(\'slow\');">Editar foto de perfil y ficha</a>
                                        <a href="#" data-original-title="Editar Usuario" data-toggle="tooltip" type="button" class="btn btn-primary" onclick="$(\'#perfil\').hide(\'slow\'); $(\'#Editar\').show(\'slow\');">Editar datos perosnales</a>
                                        <a href="#" data-original-title="Editar Usuario" data-toggle="modal" type="button"  class="btn btn-primary" data-target="#PassChange" style="background: #dd0707e6;">Editar contraseña</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="Editar" class="container" style="display:none;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Editar perfil</h3>
                                    </div>
                                    <div class="panel-body">
                                        <form id="form_editUser" class="form-horizontal" enctype="multipart/form-data">
                                            <fieldset>
                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="firstname">Nombre(s)</label>
                                                    <div class="col-md-9">
                                                        <input id="firstname" name="firstname" type="text" placeholder="Nombre(s)" class="form-control input-md" value="'.$row['dp_nombre'].'" required>
                                                        <span class="help-block">Introduce tu nombre</span>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="lastname">Apellido Paterno</label>
                                                    <div class="col-md-9">
                                                        <input id="lastname" name="lastname" type="text" placeholder="Apellido Paterno" class="form-control input-md" value="'.$row['dp_ap_paterno'].'" required>
                                                        <span class="help-block">Introduce tu Apellido</span>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="lastname2">Apellido Materno</label>
                                                    <div class="col-md-9">
                                                        <input id="lastname2" name="lastname2" type="text" placeholder="Apellido Materno" class="form-control input-md" value="'.$row['dp_ap_materno'].'" >
                                                        <span class="help-block">Introduce tu Apellido</span>
                                                    </div>
                                                </div>

                                                <!-- Multiple Radios -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="radios">Sexo</label>
                                                    <div class="col-md-9">
                                                        <div class="radio">
                                                            <label for="radios-0"> <input type="radio" name="radios" id="radios-0" value="H" '.($row['dp_sexo']=='H' ? 'checked':'').'> Hombre </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label for="radios-1"> <input type="radio" name="radios" id="radios-1" value="M" '.($row['dp_sexo']=='M' ? 'checked':'').' required> Mujer </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="mobno">Número de teléfono</label>
                                                    <div class="col-md-9">
                                                        <input id="mobno" name="mobno" type="text" placeholder="Número telefonico" class="form-control input-md" value="'.$row['dp_tel'].'" required>
                                                        <span class="help-block">Introduce tu número de teléfono</span>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="emailid">Correo electrónico</label>
                                                    <div class="col-md-9">
                                                        <input id="emailid" name="emailid" type="text" placeholder="Correo electrónico" class="form-control input-md" value="'.$row['dp_email'].'" required>
                                                        <span class="help-block">Introduce tu correo</span>
                                                    </div>
                                                </div>


                                                <!-- Button -->
                                                <div class="form-group pull-right">
                                                    <button type="button" id="cl" class="btn btn-primary" onclick="$(\'#perfil\').show(\'slow\'); $(\'#Editar\').hide(\'slow\');">Cancelar</button>
                                                    <button type="submit" id="otp" class="btn btn-primary">Enviar</button>
                                                </div>

                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
        else{
            $this->_sql="SELECT
                usuario.u_Clave,
                catedratico_datos_personales.cat_Nombre,
                catedratico_datos_personales.cat_ApePat,
                catedratico_datos_personales.cat_ApeMat,
                usuario.u_img,
                catedratico_datos_personales.cat_correo
                FROM
                usuario
                INNER JOIN catedratico_datos_personales ON usuario.cat_clave = catedratico_datos_personales.cat_Clave
                WHERE
                usuario.u_Clave = $user";
            if(!$resultado = $this->_db->query($this->_sql)){
                // ¡Oh, no! La consulta falló.
                echo "Lo sentimos, este sitio web está experimentando problemas.";
            }

            if ($resultado->num_rows >0) {
                $row=$resultado->fetch_assoc();
                echo '<div id="perfil" class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">'.$row['cat_Nombre'].' '.$row['cat_ApePat'].' '.$row['cat_ApeMat'].'</h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-md-3 col-lg-3 " align="center">
                                                <img alt="User Picture" src="/sta/assets/images/'.($row['u_img']!=""?('Users/'.$row['u_img']):'avatar1_small.jpg').'" class="img-circle img-responsive">
                                            </div>
                                            <div class=" col-md-9 col-lg-9 ">
                                                <table class="table table-user-information">
                                                    <tbody>
                                                        <tr>
                                                            <td>Correo:</td>
                                                            <td><a href="#">'.$row['cat_correo'].'</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer center">
                                        <a href="#" data-original-title="Editar Usuario" data-toggle="tooltip" type="button" class="btn btn-warning" onclick="$(\'#perfil\').hide(\'slow\'); $(\'#Editar_img\').show(\'slow\');">Editar foto de perfil</a>
                                        <a href="#" data-original-title="Editar Usuario" data-toggle="tooltip" type="button" class="btn btn-primary" onclick="$(\'#perfil\').hide(\'slow\'); $(\'#Editar\').show(\'slow\');">Editar datos perosnales</a>
                                        <a href="#" data-original-title="Editar Usuario" data-toggle="modal" type="button"  class="btn btn-primary" data-target="#PassChange" style="background: #dd0707e6;">Editar contraseña</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="Editar" class="container" style="display:none;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Editar perfil</h3>
                                    </div>
                                    <div class="panel-body">
                                        <form id="form_editUser" class="form-horizontal" enctype="multipart/form-data">
                                            <fieldset>
                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="firstname">Nombre(s)</label>
                                                    <div class="col-md-9">
                                                        <input id="firstname" name="firstname" type="text" placeholder="Nombre(s)" class="form-control input-md" value="'.$row['cat_Nombre'].'" required>
                                                        <span class="help-block">Introduce tu nombre</span>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="lastname">Apellido Paterno</label>
                                                    <div class="col-md-9">
                                                        <input id="lastname" name="lastname" type="text" placeholder="Apellido Paterno" class="form-control input-md" value="'.$row['cat_ApePat'].'" required>
                                                        <span class="help-block">Introduce tu Apellido</span>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="lastname2">Apellido Materno</label>
                                                    <div class="col-md-9">
                                                        <input id="lastname2" name="lastname2" type="text" placeholder="Apellido Materno" class="form-control input-md" value="'.$row['cat_ApeMat'].'" >
                                                        <span class="help-block">Introduce tu Apellido</span>
                                                    </div>
                                                </div>

                                                <!-- Text input-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="emailid">Correo electrónico</label>
                                                    <div class="col-md-9">
                                                        <input id="emailid" name="emailid" type="text" placeholder="Correo electrónico" class="form-control input-md" value="'.$row['cat_correo'].'" required>
                                                        <span class="help-block">Introduce tu correo</span>
                                                    </div>
                                                </div>

                                                <!-- Button -->
                                                <div class="form-group pull-right">
                                                    <button type="button" id="cl" class="btn btn-primary" onclick="$(\'#perfil\').show(\'slow\'); $(\'#Editar\').hide(\'slow\');">Cancelar</button>
                                                    <button type="submit" id="otp" class="btn btn-primary">Enviar</button>
                                                </div>

                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
    }

    public function editPerfil_img($tuser,$user){
        if (isset($_FILES["perfil"])){
            $file = $_FILES["perfil"];
            $nombre = $file["name"];
            $tipo = $file["type"];
            $ruta_provisional = $file["tmp_name"];
            $size = $file["size"];
            $dimensiones = getimagesize($ruta_provisional);
            $width = $dimensiones[0];
            $height = $dimensiones[1];
             $carpeta = $_SERVER["DOCUMENT_ROOT"]."/sta/assets/images/". ($tuser=="Alu" ? "Alu/" : "Users/");
            $tip=explode("/", $tipo);

            if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
              echo json_encode( array("error"=>true, "men"=> "Error, el archivo no es una imagen"));
            }
            else if ($size > 1024*1024) {
              echo json_encode( array("error"=>true, "men"=> "Error, el tamaño máximo permitido es un 1MB"));
            }
            else if ($width > 500 || $height > 500) {
                echo json_encode( array("error"=>true, "men"=> "Error la anchura y la altura máxima permitida es 500px"));
            }
            else if($width < 60 || $height < 60)  {
                echo json_encode( array("error"=>true, "men"=> "Error la anchura y la altura mínima permitida es 60px"));
            }
            else {
                $src = $carpeta.$user.".".$tip[1];
                $arch=false;
                $sql=false;
                if (move_uploaded_file($ruta_provisional, $src)) {
                    $arch=true;
                }
                $this->_sql=$tuser=="Alu" ? "UPDATE alumnos_caracterizacion SET al_img ='".$user.".".$tip[1]."' WHERE se_no_control='$user'" : "UPDATE sta.usuario SET u_img = '".$user.".".$tip[1]."' WHERE u_Clave = $user";
                $resultado = $this->_db->query($this->_sql);
                if($this->_db->affected_rows > 0){
                    $sql=true;
                }

                if ($arch==true && $sql=true) {
                    echo json_encode( array("error"=>false, "men"=>"Se guardo correctamente la imagen"));
                    $_SESSION['usuario']['img']=$user.".".$tip[1];
                }
                else{
                    echo json_encode( array("error"=>true, "men"=>"Hubo algún problema al subir la imagen"));
                }
            }
        }

    }

    public function editPerfil($tuser,$user){
        if ($tuser=="Alu") {
            $this->_sql = "SELECT alumnos_caracterizacion.se_no_control, alumnos_caracterizacion.dp_pass_cambio, alumnos_caracterizacion.dp_contrasena FROM alumnos_caracterizacion WHERE  se_no_control = '$user'";
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

            if ($resultado->num_rows >0) {
                $sql="UPDATE sta.alumnos_caracterizacion SET dp_nombre = '".$_POST['firstname']."', dp_ap_paterno = '".$_POST['lastname']."',
                dp_ap_materno = '".$_POST['lastname2']."', dp_sexo = '".$_POST['radios']."', dp_email = '".$_POST['emailid']."', dp_tel = '".$_POST['mobno']."' WHERE se_no_control = '$user'";
                $resultado = $this->_db->query($sql);
                if($this->_db->affected_rows > 0){
                    echo json_encode(array('error' => false));
                }
                else {
                    echo json_encode(array('error' => true, 'men'=>$this->_db->error.$sql));
                }
            }
            else {
                echo json_encode(array('error' => true));
            }
        }
        else {
            $this->_sql = "SELECT usuario.cat_clave, usuario.med_clave, usuario.psi_clave FROM usuario WHERE usuario.u_Clave = $user";
            if(!$resultado = $this->_db->query($this->_sql)){
                echo "Lo sentimos, este sitio web está experimentando problemas.".$this->_sql;
            }

            if ($resultado->num_rows >0) {
                if (!isset($row['cat_clave'])) {
                    $sql="UPDATE sta.catedratico_datos_personales SET cat_Nombre = '".$_POST['firstname']."', cat_ApePat = '".$_POST['lastname']."', cat_ApeMat = '".$_POST['lastname2']."', cat_correo = '".$_POST['emailid']."' WHERE cat_Clave = '$user'";
                }elseif (!isset($row['med_clave'])) {
                    $sql="UPDATE sta.datos_doctor SET dd_nombre = '".$_POST['firstname']."', dd_apellido_paterno = '".$_POST['lastname']."', dd_apellido_materno = '".$_POST['lastname2']."', dd_mail = '".$_POST['emailid']."' WHERE dd_id_doctor = '$user'";
                }elseif (!isset($row['psi_clave'])) {
                    $sql="UPDATE sta.datos_doctor SET psi_nombre = '".$_POST['firstname']."', psi_apellido_paterno = '".$_POST['lastname']."', psi_apellido_materno = '".$_POST['lastname2']."', psi_mail = '".$_POST['emailid']."' WHERE dd_id_doctor = '$user'";
                }
                $resultado = $this->_db->query($sql);
                if($this->_db->affected_rows > 0){
                    echo json_encode(array('error' => false));
                }
                else {
                    echo json_encode(array('error' => true, 'men'=>$this->_db->error.$sql));
                }
            }
            else {
                echo json_encode(array('error' => true));
            }
        }
    }

}

?>