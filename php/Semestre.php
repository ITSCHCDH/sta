<?php
    require_once ('dbconfig.php');
    /**
     *
     */
    class Semestre{

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

        function seleccionarSemestre(){
            $this->_sql="SELECT
                dbo.GruposSemestre.per_Clave

                FROM
                dbo.GruposSemestre
                GROUP BY
                dbo.GruposSemestre.per_Clave";
            $stmt=sqlsrv_query ( $this->_db2, $this->_sql);

            if($stmt!=false){
            	echo '<option selected="selected" disabled="disabled">Elija el Semestre</option>';
            	while($row = sqlsrv_fetch_array($stmt)){
            		if ($row['per_Clave']==1 ) {
            		    echo '<option value="2">2</option>';
                        echo '<option value="4">4</option>';
                        echo '<option value="6">6</option>';
                        echo '<option value="8">8</option>';
            		}else if($row['per_Clave']==2 || $row['per_Clave']==3) {
            		    echo '<option value="1">1</option>';
                        echo '<option value="3">3</option>';
                        echo '<option value="5">5</option>';
                        echo '<option value="7">7</option>';
                        echo '<option value="9">9</option>';
            		}
            	}
            }
        }
    }

    $Sem=new Semestre();
    $Sem->seleccionarSemestre();

?>
