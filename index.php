<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="main.js"></script>
</head>
<body>
    
<?php

require_once 'libs/BigQuery.php' ;

$bigQuery= new BigQuery('informe-211921');

$dml = "SELECT DISTINCT(HKONT) AS CUENTAS FROM `informe-211921.BALANZA.BSEG_2019_2` WHERE KOSTL IN('1020100303','5020100303') AND SUBSTR(HKONT,1,1) = '6'";

$cuentas = $bigQuery->select($dml);

//print_r($cuentas);

$dmlArray=[];

foreach ($cuentas as $cuenta) {

    //echo($cuenta['CUENTAS']);

    $dml1="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS MONTO, '".$cuenta['CUENTAS']."' AS CUENTA FROM `informe-211921.BALANZA.BSEG_2019_2` WHERE KOSTL IN('1020100303','5020100303') AND HKONT = '".$cuenta['CUENTAS']."' ";

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


?>

</body>
</html>

