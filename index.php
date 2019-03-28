
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TITLE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<?php

require_once 'libs/BigQuery.php' ;

function testTable($title,$month){

    $bigQuery= new BigQuery('informe-211921');

    $dml0 = "SELECT DISTINCT(HKONT) AS CUENTAS FROM `informe-211921.BALANZA.BSEG_2019_".$month."` WHERE KOSTL IN('1020100303','5020100303') AND SUBSTR(HKONT,1,1) = '6'";

    $cuentas = $bigQuery->select($dml0);

    //print_r($cuentas);

    $dmlArray=[];

    foreach ($cuentas as $cuenta) {

        //echo($cuenta['CUENTAS']);

        $dml1="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS MONTO, '".$cuenta['CUENTAS']."' AS CUENTA FROM `informe-211921.BALANZA.BSEG_2019_".$month."` WHERE KOSTL IN('1020100303','5020100303') AND HKONT = '".$cuenta['CUENTAS']."' ";

        $dmlArray[]=$dml1;
    }

    $uniquery = implode(" UNION ALL ", $dmlArray);

    $tablaCuentas =$bigQuery->select($uniquery);


    echo('        <div class="col-4">
    <!-- Area Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">'.$title.'</h6>
        </div>
        <div class="card-body">');

    echo('<table class="table">');
    echo('<tr><th>CUENTA</th><th>MONTO</th></tr>');
    foreach ($tablaCuentas as $fila) {

        echo('<tr>');
        echo('<td>'.$fila['CUENTA'].'</td>');
        echo('<td>'.$fila['MONTO'].'</td>');
        echo('</tr>');

    }

    echo('</table>');


    echo('                </div>
    </div>
</div>');


}

echo('<div class="container-fluid">
<div class="row">');


testTable('ok','1');
testTable('ok','2');
testTable('ok','3');


echo('</div>
</div>');

?>








</body>
</html>

