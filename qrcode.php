<?php

include "/var/www/vote/application/libraries/phpqrcode/phpqrcode.php";    // QRcode lib    

$data = $_GET['data']; // data    
$errorCorrectionLevel = "L";
$matrixPointSize = "6";
QRcode::png($data, false, $errorCorrectionLevel, $matrixPointSize);
?>  