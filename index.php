<?php

require_once 'libs/BigQuery.php' ;

$bigQuery= new BigQuery('informe-211921');

$dml = "SELECT DISTINCT(HKONT) AS CUENTAS FROM `informe-211921.BALANZA.BSEG_2019_2` WHERE KOSTL IN('1020100303') AND SUBSTR(HKONT,1,1) = '6'";

$cuentas = $bigQuery->select($dml);

?>