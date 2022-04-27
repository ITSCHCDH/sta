<?php
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        require_once 'dbconfig.php';

        session_start();

        $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_errno) {
            echo 'Fallo al conectar a MySQL: '.$db->connect_error;

            return;
        }
        if (!$db->set_charset('utf8')) {
            printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
            exit();
        }
        $connectionInfo = array( "Database"=>MS_DB_NAME, "UID"=>MS_DB_USER, "PWD"=>MS_DB_PASS);
        $db2= sqlsrv_connect( MS_DB_SERVER, $connectionInfo);
        if( !$db2 ){
            echo "Conexión no se pudo establecer.<br />";
            die( print_r( sqlsrv_errors(), true));
        }

        $usuario = $db->real_escape_string($_POST['usuario']);

        $sql = "SELECT alumnos_caracterizacion.se_no_control,
            CONCAT(alumnos_caracterizacion.dp_ap_paterno,' ',alumnos_caracterizacion.dp_ap_materno,' ',alumnos_caracterizacion.dp_nombre) AS nombre,
            TRIM(CONCAT(
                TRIM(alumnos_caracterizacion.dp_nombre),
                TRIM(alumnos_caracterizacion.dp_ap_paterno),
                TRIM(alumnos_caracterizacion.dp_ap_materno)
            ) ) AS usuarios, alumnos_caracterizacion.dp_pass_cambio, alumnos_caracterizacion.dp_contrasena,
            alumnos_caracterizacion.al_img
            FROM
            alumnos_caracterizacion
            WHERE  se_no_control = ?";

        if ($nueva_consulta = $db->prepare($sql)) {
            $nueva_consulta->bind_param('s', $usuario);
            $nueva_consulta->execute();
            $resultado = $nueva_consulta->get_result();

            if ($resultado->num_rows == 1) {
                $row = $resultado->fetch_assoc();
                $usePas= strtoupper( str_replace(' ', '', $row['nombre']) );
                if ($row['dp_pass_cambio']==1) {
                    if ($row['dp_contrasena'] == $_POST['password']) {
                        echo json_encode(array('error' => false, 'tipo' => $_POST['tipoUsuario']));
                        $_SESSION['usuario'] = array(
                            'Clave' => $row['se_no_control'],
                            'Nombre' => $row['nombre'],
                            'Tipo' => 'Alu',
                            'passw' => 'setPass',
                            'pd' => $row['dp_contrasena'],
                            'img' => $row['al_img'],
                            'caract' => 'si'
                        );
                    } else {
                        echo json_encode(array('error' => true, 'text' => 'Contraseña incorrecta'));
                    }
                }
                elseif ($usePas == $_POST['password']) {
                    echo json_encode(array('error' => false, 'tipo' => $_POST['tipoUsuario']));
                    $_SESSION['usuario'] = array(
                                                'Clave' => $row['se_no_control'],
                                                'Nombre' => $row['nombre'],
                                                'Tipo' => 'Alu',
                                                'passw' => 'noSet',
                                                'pd' => '',
                                                'img' => $row['al_img'],
                                                'caract' =>'si'
                    );
                } else {
                    echo json_encode(array('error' => true, 'texto' => 'Contraseña incorrecta'));
                }
            }
            else {
                $sql = "SELECT dbo.Alumnos.alu_NumControl,
                    ( RTRIM(Alumnos.alu_ApePaterno) + ' ' + RTRIM(Alumnos.alu_ApeMaterno) + ' ' + RTRIM(Alumnos.alu_Nombre)) AS nombre,
                    ( LTRIM(RTRIM( LTRIM(RTRIM( Alumnos.alu_Nombre)) + LTRIM(RTRIM( Alumnos.alu_ApePaterno)) +  LTRIM(RTRIM(Alumnos.alu_ApeMaterno))))) AS usuarios,
                    dbo.Alumnos.car_Clave FROM dbo.Alumnos WHERE dbo.Alumnos.alu_NumControl = '$usuario'";

                $stmt = sqlsrv_query($db2, $sql);
                if( !$stmt ) {
                    echo json_encode(array('error' => true, 'text' => 'error al consultar datos prepare'));
                }
                $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                $usePas=strtoupper(str_replace(' ', '', $row['nombre']));

                if ($usePas == $_POST['password']) {
                    echo json_encode(array('error' => false, 'tipo' => $_POST['tipoUsuario']));
                    $_SESSION['usuario'] = array(
                                  'Clave' => $row['alu_NumControl'],
                                  'Nombre' => $row['nombre'],
                                  'Tipo' => 'Alu',
                                  'passw' => 'setPass',
                                  'pd' => '',
                                  'img' => '',
                                  'caract' =>'no'
                  );
                }
                else {
                    echo json_encode(array('error' => true, 'text' => 'Datos de ingreso no validos, inténtalo de nuevo'));

                }

            }

        } else {
            echo json_encode(array('error' => true, 'text' => 'Datos de ingreso no validos, inténtalo de nuevo'));
        }
    }
