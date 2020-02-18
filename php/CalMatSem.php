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

        function calificacionesxsemestre(){
            $this->_sql="SELECT
                dbo.Reticula.ret_NomCompleto,
                dbo.Cardex.cdx_Calif,
                dbo.Cardex.cdx_SemXPrime,
                dbo.Cardex.cdx_AnioXPrime,
                dbo.Cardex.cdx_SemXSeg,
                dbo.Cardex.cdx_AnioXSeg,
                dbo.Cardex.cdx_SemXTer,
                dbo.Cardex.cdx_AnioXTer,
                dbo.Cardex.cdx_ultopcacred
                FROM
                dbo.Cardex
                INNER JOIN dbo.Alumnos ON dbo.Cardex.alu_NumControl = dbo.Alumnos.alu_NumControl
                INNER JOIN dbo.Reticula ON dbo.Cardex.ret_Clave = dbo.Reticula.ret_Clave
                WHERE
                dbo.Cardex.alu_NumControl = '".$_GET['NoCon']."' AND ( dbo.Cardex.cdx_SemXPrime =".$_GET['Sem']." OR dbo.Cardex.cdx_SemXSeg=".$_GET['Sem']." or dbo.Cardex.cdx_SemXTer=".$_GET['Sem'].")
                ";

            $stmt=sqlsrv_query ( $this->_db2, $this->_sql)
                or die('Query failed: '.$this->_sql);

            if($stmt!=false){
            	while($row = sqlsrv_fetch_array($stmt)){
                    if($row['cdx_Calif']>70){
                        echo "<tr>
                            <th>".$row['ret_NomCompleto']."</th>
                            <th>".$row['cdx_Calif']."</th>";
                        switch ($row['cdx_ultopcacred']) {
                            case 1:
                                echo "<th>Ordinario</th>";
                                break;
                            case 2:
                                echo "<th class='success'>Ordinario con nivelación</th>";
                                break;
                            case 3:
                                echo "<th class='warning2'>Repetición</th>";
                                break;
                            case 4:
                                echo "<th class='warning1'>Repetición con nivelación</th>";
                                break;
                            case 5:
                                echo "<th class='danger'>Especial</th>";
                                break;
                            case 6:
                                echo "<th class='danger'>Especial con nivelación</th>";
                                break;
                            default:
                                echo "<th class='danger'>Reprobada</th>";
                                break;
                        }
                        echo"</tr>";
                    }
                    else {
                        echo "<tr class='danger'>
                            <th class='danger'>".$row['ret_NomCompleto']."</th>
                            <th class='danger'>".$row['cdx_Calif']."</th>
                            <th class='danger'>Reprobada</th>
                        </tr>";
                    }

            	}
            }
        }
    }


?>
