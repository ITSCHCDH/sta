<?php
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
        require_once "dbconfig.php";

        session_start();

        $db = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
        if ($db ->connect_errno) {
            echo "Fallo al conectar a MySQL: ". $db->connect_error;
            return;
        }
        if (!$db->set_charset("utf8")) {
            printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
            exit();
        }

        $usuario= $db->real_escape_string($_POST['usuario']);
        $pas=$db->real_escape_string($_POST['password']);

        $sql="SELECT * FROM usuario WHERE u_Usuario = ? AND u_Contrasena = ? ";

        if ($nueva_consulta=$db->prepare($sql)) {
            $nueva_consulta->bind_param('ss', $usuario, $pas);
            $nueva_consulta->execute();
            $resultado=$nueva_consulta->get_result();

            if ($resultado->num_rows==1) {
                # code...
                $row = $resultado->fetch_assoc();
                $Cadena=$row['u_Tipo'];
                $posicion=strpos($Cadena,$_POST['tipoUsuario']);
                if ($posicion===false) {
                    echo json_encode(array('error'=> false,'tipo'=>"NoUs"));
                }else {
                    if(($row['u_Contrasena']=='TrayectoriaITSCH') || $row['u_Contrasena'] == 'MedicoITSCH' || $row['u_Contrasena'] == 'PsicologiaITSCH'){
                        $pas='noset';
                    }
                    else{
                        $pas = 'set';
                    }
                    echo json_encode(array('error'=> false,'tipo'=>$_POST['tipoUsuario'], 'pas'=>$pas));
                    $_SESSION['usuario']=array(
                                                "Clave"=>$row['u_Clave'],
                                                "Nombre"=>$row['u_Usuario'],
                                                "Pass"=>$row['u_Contrasena'],
                                                "Tipo"=>$_POST['tipoUsuario'],
                                                "Carr"=>$row['u_jefe_carrera'],
                                                "Cat"=>isset($row['cat_clave'])?$row['cat_clave']:'',
                                                "Psi"=>isset($row['psi_clave'])?$row['psi_clave']:'',
                                                "Med"=>isset($row['psi_clave'])?$row['med_clave']:'',
                                                "passw" => ($row['u_set_pass']!=1?'noSet':'setPass'),
                                                "img" => $row['u_img']
                    );
                }
            } else {
                echo json_encode(array('error'=>true));
            }

            $nueva_consulta->close();

        }
        else {
          echo json_encode(array('error'=>true));
        }
    }
 ?>
