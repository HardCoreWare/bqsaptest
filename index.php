
<?php

require_once 'libs/BigQuery.php' ;

$bigQuery= new BigQuery('informe-211921');


class Test{


    public static function testTable($month){

        $dml = "SELECT DISTINCT(HKONT) AS CUENTAS FROM `informe-211921.BALANZA.BSEG_2019_".$month."` WHERE KOSTL IN('1020100303','5020100303') AND SUBSTR(HKONT,1,1) = '6'";

        $cuentas = $bigQuery->select($dml);

        //print_r($cuentas);

        $dmlArray=[];

        foreach ($cuentas as $cuenta) {

            //echo($cuenta['CUENTAS']);

            $dml1="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS MONTO, '".$cuenta['CUENTAS']."' AS CUENTA FROM `informe-211921.BALANZA.BSEG_2019_".$month."` WHERE KOSTL IN('1020100303','5020100303') AND HKONT = '".$cuenta['CUENTAS']."' ";

            $dmlArray[]=$dml1;
        }

        $uniquery = implode(" UNION ALL ", $dmlArray);

        $tablaCuentas =$bigQuery->select($uniquery);

        echo('<table>');
        echo('<tr><th>CUENTA</th><th>MONTO</th></tr>');
        foreach ($tablaCuentas as $fila) {

            echo('<tr>');
            echo('<td>'.$fila['CUENTA'].'</td>');
            echo('<td>'.$fila['MONTO'].'</td>');
            echo('</tr>');

        }

        echo('</table>');

    }

}

Test::testTable('1');

?>








</body>
</html>

