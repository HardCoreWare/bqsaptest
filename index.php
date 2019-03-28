
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>EJEMPLO ENTRECRUZAMIENTOS Y CONSULTAS EN BIGQUERY Y PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>



<div class="container-fluid">
<div class="row">

<?php

require_once 'libs/BigQuery.php' ;

$conceptos=[
"6101000000"=>	  "INTS POR DEP DE EXIGIBILIDAD INM",
"6102000000"=>	  "INTS POR DEPOSITOS A PLAZO",
"6390020300"=>	  "OTRAS COMISIONES BANCARIAS",
"6402020000"=>	  "HONORARIOS PERSONAS MORALES",
"6403020100"=>	  "RENTAS INMUEBLES SOCIEDADES ANONIMAS",
"6404010000"=>	  "MEDIOS MASIVOS RADIO Y TELEVISION",
"6410010102"=>	  "SUELDOS Y SALARIOS",
"6410010103"=>	  "AGUINALDO",
"6410010104"=>	  "BONOS",
"6410010107"=>	  "SEGUROS DE VIDA",
"6410010108"=>	  "SEGUROS DE GASTOS MEDICOS",
"6410010111"=>	  "CUOTAS AL IMSS",
"6410010112"=>	  "APORTACIONES AL INFONAVIT",
"6410010113"=>	  "PRIMA DE VACACIONES",
"6410010115"=>	  "VALES DE DESPENSA",
"6410010125"=>	  "2% SOBRE NOMINAS",
"6491070000"=>	  "SEGUROS",
"6491090000"=>	  "LUZ Y AGUA",
"6491240100"=>	  "ENLACES VOZ, DATOS Y RED CON SUCURSALES",
"6491250000"=>	  "MANT INMUEBLES",
"6491310000"=>	  "VIGILANCIA",
"6491330000"=>	  "OTROS",
];



function testTable($title,$month,$concepts){

    $bigQuery= new BigQuery('informe-211921');
    $dml0 = "SELECT DISTINCT(HKONT) AS CUENTAS FROM `informe-211921.BALANZA.BSEG_2019_".$month."` WHERE ".
    "KOSTL IN('1020100303','5020100303') AND SUBSTR(HKONT,1,1) = '6'";
    $cuentas = $bigQuery->select($dml0);

    //print_r($cuentas);

    $dmlArray=[];

    foreach ($cuentas as $cuenta) {


        $dml1="SELECT ROUND(SUM(CAST(DMBTR AS FLOAT64)), 2) AS MONTO, '".$cuenta['CUENTAS']."' AS CUENTA FROM `informe-211921.BALANZA.BSEG_2019_".$month."` WHERE KOSTL IN('1020100303','5020100303') AND HKONT = '".$cuenta['CUENTAS']."' ";

        $dmlArray[]=$dml1;
    }

    $uniquery = implode(" UNION ALL ", $dmlArray);



    $tablaCuentas =$bigQuery->select($uniquery);


    echo('        
    
    <div class="col-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">'.$title.'</h6>
                </div>
                    <div class="card-body">');

                    echo('<table class="table table-bordered">');
                    echo('<tr><th>CUENTA</th><th>CONCEPTO</th><th>MONTO</th></tr>');

                    $total=0;
                    foreach ($tablaCuentas as $fila) {

                        echo('<tr>');
                        echo('<td>'.$fila['CUENTA'].'</td>');
                        echo('<td>'.$concepts[$fila['CUENTA']].'</td>');
                        echo('<td>'.$fila['MONTO'].'</td>');
                        echo('</tr>');

                        $total+=floatval($fila['MONTO']);

                    }

                    echo('<tr><th>TOTAL</th><th>'.strval($total).'</th></tr>');
                    $total=0;

                    echo('</table>');

    echo('

            </div>
        </div>
    </div>'

    );

$bigQuery=null;

}

testTable('COUNTRY ENE ','1',$conceptos);
testTable('COUNTRY FEB ','2',$conceptos);
testTable('COUNTRY MAR ','3',$conceptos);

?>

</div>
</div>




</body>
</html>

