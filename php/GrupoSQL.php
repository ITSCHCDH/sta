<?php
    require_once ('dbconfig.php');
    /**
     *
     */
    class Grupo{

        protected $_db2;
        protected $_sql;

        function __construct(){
            $connectionInfo = array( "Database"=>MS_DB_NAME, "UID"=>MS_DB_USER, "PWD"=>MS_DB_PASS);
            $this->_db2= sqlsrv_connect( MS_DB_SERVER, $connectionInfo);
            if( !$this->_db2 ){
                echo "Conexión no se pudo establecer.<br />";
                die( print_r( sqlsrv_errors(), true));
            }
        }

        function agregarGrupo($Car,$Sem){
            $this->_sql="SELECT
                dbo.GruposSemestre.gse_Observaciones
                FROM
                dbo.GruposSemestre
                WHERE
                dbo.GruposSemestre.gse_Observaciones LIKE '".$Car.$Sem."%'
                GROUP BY
                dbo.GruposSemestre.gse_Observaciones";
            $stmt=sqlsrv_query ( $this->_db2, $this->_sql);

            if($stmt!=false){
            	echo '<option selected="selected" disabled="disabled">Elija su Grupo</option>';
            	while($row = sqlsrv_fetch_array($stmt)){
            		echo '<option value="'.$row['gse_Observaciones'].'">'.$row['gse_Observaciones'].'</option>';
            	}
            }
        }

    }
    $Grupo=new Grupo();
    $Grupo->agregarGrupo($_POST['Car'],$_POST['Sem']);

?>
