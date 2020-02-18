<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";
    class Carrera{

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
                printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
                exit();
            }

            $connectionInfo = array( "Database"=>MS_DB_NAME, "UID"=>MS_DB_USER, "PWD"=>MS_DB_PASS);
            $this->_db2= sqlsrv_connect( MS_DB_SERVER, $connectionInfo);
            if( !$this->_db2 ){
                echo "Conexión no se pudo establecer.<br />";
                die( print_r( sqlsrv_errors(), true));
            }
        }

        function ConsultarCarrera(){
            $this->_sql="SELECT car_Clave, car_Nombre FROM carreras";
            if(!$resultado=$this->_db->query($this->_sql)){
                echo '<option selected="selected" disabled="disabled">Hay problemas con las carreras</option>';
            }
            if($resultado->num_rows > 0){
                echo '<option selected="selected" disabled="disabled">Carreras</option>';
                while ($row=$resultado->fetch_assoc()) {
                    echo "<option value='".$row['car_Clave']."'>".$row['car_Nombre']."</option>";
                }
            }
            else {
                echo '<option selected="selected" disabled="disabled">No Hay Grupos</option>';
            }
        }

    }
 ?>
