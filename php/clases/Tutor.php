<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/php/dbconfig.php";

class tutor{

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

    public function gpoAsign($cat){
        $this->_sql = "SELECT
            grupos_tutorias.gpo_clave,
            grupos_tutorias.gpo_identificador
            FROM
            grupos_tutorias
            WHERE grupos_tutorias.gpo_clave NOT IN (SELECT grupos_tutorias_complemento.gpo_clave FROM grupos_tutorias_complemento GROUP BY grupos_tutorias_complemento.gpo_clave)
            AND
            grupos_tutorias.cat_clave =(SELECT usuario.cat_clave FROM usuario WHERE usuario.u_Clave=".$cat.")";

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
                echo '<option selected="selected" disabled="disabled">No Hay Grupos </option>';
            }
            if($resultado->num_rows > 0){
                echo '<option selected="selected" disabled="disabled">Grupos</option>';
                while ($row=$resultado->fetch_assoc()) {
                    echo "<option value='".$row['gpo_identificador']."'>".$row['gpo_identificador']."</option>";
                }
            }
            else {
                echo '<option selected="selected" disabled="disabled">No Hay Grupos</option>';
            }
    }

    public function ConsultarGrupos($ban){
        $this->_sql="SELECT grupos_tutorias.gpo_nombre FROM grupos_tutorias
            WHERE cat_clave= (SELECT usuario.cat_clave FROM usuario WHERE usuario.u_Clave=".$_SESSION['usuario']['Clave'].") AND
            grupos_tutorias.gpo_nombre IS NOT NULL";

        if(!$resultado=$this->_db->query($this->_sql)){
            // ¡Oh, no! La consulta falló.
            //echo "Lo sentimos, este sitio web está experimentando problemas.";

            // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
            // cómo obtener información del error
            //echo "Error: La ejecución de la consulta falló debido a: \n";
            //echo "Query: " . $this->_sql . "\n";
            //echo "Errno: " . $this->_db->errno . "\n";
            //echo "Error: " . $this->_db->error . "\n";
            //exit;
            echo '<option selected="selected" disabled="disabled">No Hay Grupos</option>';
        }
        if($resultado->num_rows > 0){
            echo '<option selected="selected" disabled="disabled">Grupos</option>';
            while ($row=$resultado->fetch_assoc()) {
                echo "<option value='".$row['gpo_nombre']."' >".$row['gpo_nombre']."</option>";
            }
        }
        else {
            echo '<option selected="selected" disabled="disabled">No Hay Grupos</option>';
        }
    }

    function llenarAlumnos($grupo){
        $Clave=$this->claveGrupo($grupo);
        $this->_sql="SELECT
                L.alu_NumControl AS 'NoControl',
                a.alu_Nombre AS 'Nombre',
                a.alu_ApePaterno AS 'ApePaterno',
                a.alu_Apematerno AS 'ApeMaterno',
                a.alu_SemestreAct AS 'Sem',
                c.car_Nombre AS 'Carrera'
            FROM
                ListasSemestre L
            JOIN Alumnos a ON L.alu_NumControl = a.alu_NumControl
            JOIN Carreras c ON a.car_Clave = c.car_Clave
            WHERE
                gse_Clave =$Clave
            ORDER BY L.alu_NumControl";

        $stmt = sqlsrv_query ( $this->_db2, $this->_sql) or die('Query failed: '.$this->_sql);
        echo "<table class='display table table-striped table-border>' id='AlumnosTabla'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>NumControl</th>
                    <th>Nombre</th>
                    <th>Semestre</th>
                    <th>Carrera</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody>";
        $con=1;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
            echo
               "<tr>
                    <td>".$con."</td>
                    <td>".$row['NoControl']."</td>
                    <td>".utf8_encode($row['ApePaterno'])." ".utf8_encode($row['ApeMaterno'])." ".utf8_encode($row['Nombre'])."</td>
                    <td>".$row['Sem']."</td>
                    <td>".utf8_encode($row['Carrera'])."</td>
                    <td><input class='NumCon' type='checkbox' name='control[]' value='".$row['NoControl']."' checked='true'</td>
                </tr>";
            $con=$con+1;
        }
        echo "</tbody>
            </table>";
    }

    function claveGrupo($Grupo){
        $this->_sql="SELECT gse_Clave
        FROM GruposSemestre WHERE gse_Observaciones = '".$Grupo."'
        AND gse_CantAlu = ( SELECT MAX ( dbo.GruposSemestre.gse_CantAlu ) FROM
                dbo.GruposSemestre WHERE dbo.GruposSemestre.gse_Observaciones = '".$Grupo."' )";

        $stmt = sqlsrv_query ( $this->_db2, $this->_sql);
        if($stmt==false){
            die( print_r( sqlsrv_errors(), true) );
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        #$field=sqlsrv_fetch_field($stmt,0);
        $Cgrupo=$row['gse_Clave'];
        return $Cgrupo;
    }

    function newGrupos($Doc){
        $this->_sql="SELECT
            COUNT(grupos_tutorias.gpo_identificador) As nugrup
            FROM grupos_tutorias
            WHERE grupos_tutorias.gpo_clave NOT IN (SELECT grupos_tutorias_complemento.gpo_clave FROM grupos_tutorias_complemento GROUP BY grupos_tutorias_complemento.gpo_clave)
            AND grupos_tutorias.cat_clave =(SELECT usuario.cat_clave FROM usuario WHERE usuario.u_Clave=$Doc)";

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
                return 0;
            }
            if($resultado->num_rows > 0){
                $row=$resultado->fetch_assoc();
                return $row['nugrup'];
            }
            else {
                return 0;
            }
    }

    public function valid_format($nocon,$val)
    {
        $this->_sql = "SELECT * FROM alumnos_caracterizacion WHERE  se_no_control = '$nocon'";
        if(!$resultado = $this->_db->query($this->_sql)){
            // ¡Oh, no! La consulta falló.
            echo "Lo sentimos, este sitio web está experimentando problemas.";
        }

        if ($resultado->num_rows >0) {
            $sql="UPDATE sta.alumnos_caracterizacion SET al_pdf_valido = '".$val."' WHERE se_no_control = '$nocon'";
            $this->_db->query($sql);
            if($this->_db->affected_rows > 0){
                if ($val == 1)
                    echo json_encode(array('error' => false, 'mensaje'=>'Se aprobo la ficha del alumno'));
                else
                    echo json_encode(array('error' => false, 'mensaje'=>'Se desaprobo la ficha del alumno'));
            }
            else {
                echo json_encode(array('error' => true, 'men'=>$this->_db->error.$sql));
            }
        }
        else {
            echo json_encode(array('error' => true, 'mensaje' => 'No se encuentra registrado el alumno'));
        }
    }

    function AluSeg(){
        $this->_sql = "SELECT * FROM alumnos_caracterizacion WHERE  se_no_control = '".$_POST['alu']."'";
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
            $sql="UPDATE sta.alumnos_caracterizacion SET tut_seg = '".$_POST['seg']."' WHERE se_no_control = '".$_POST['alu']."'";
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

}
