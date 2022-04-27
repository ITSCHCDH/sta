<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/php/dbconfig.php";
class jfe{

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

    public function Carrera(){
        $this->_sql="SELECT carreras.car_Nombre, carreras.clave_sise FROM carreras WHERE carreras.car_Clave =".$_SESSION['usuario']['Carr'];

        if(!$resultado = $this->_db->query($this->_sql)){
            // ¡Oh, no! La consulta falló.
            echo "Lo sentimos, este sitio web está experimentando problemas.";

            // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
            // cómo obtener información del error
            //echo "Error: La ejecución de la consulta falló debido a: \n";
            //echo "Query: " . $this->_sql . "\n";
            //echo "Errno: " . $this->_db->errno . "\n";
            //echo "Error: " . $this->_db->error . "\n";
            //exit;
        }
        if($resultado->num_rows > 0){
            $row=$resultado->fetch_assoc();
            echo "<center>
                <h1>".$row['car_Nombre']."</h1>
                <input type='hidden' value='".$row['clave_sise']."' /></center>";
                $_SESSION['usuario']['Car']=$row['clave_sise'];
        }
    }

    public function ConsultarGrupos($car){
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

    public function Generaciones($cl){
        $this->_sql = "SELECT clave_sise FROM carreras WHERE car_Clave =". $cl;
        $resultado = $this->_db->query($this->_sql);
        $row=$resultado->fetch_assoc();
        $cla=$row['clave_sise'];
        $this->_sql="SELECT dbo.Alumnos.alu_AnioIngreso FROM dbo.Alumnos WHERE dbo.Alumnos.alu_StatusAct = 'VI' AND dbo.Alumnos.car_Clave = '$cla' GROUP BY dbo.Alumnos.alu_AnioIngreso";
        $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);
        if($stmt!=false) {
            echo '<option selected="selected" disabled="disabled">Generaciones</option>';
            while ($row=sqlsrv_fetch_array($stmt)) {
                echo "<option value='".$row['alu_AnioIngreso']."'>".$row['alu_AnioIngreso']."</option>";
            }
        }
        else {
            echo '<option selected="selected" disabled="disabled">'.sqlsrv_num_rows($stmt).'</option>';
        }
    }

    public function ConsultarCalificaciones($value='')
    {
        // code...
    }

}

?>
