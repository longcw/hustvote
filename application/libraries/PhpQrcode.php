
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PhpQrcode {

    public function __construct() {
        require 'phpqrcode/phpqrcode.php';
    }

    public function png($content) {
        $errorCorrectionLevel = "L";
        $matrixPointSize = "4";
        QRcode::png($content, false, $errorCorrectionLevel, $matrixPointSize);

    }

}

/* End of file mailer.php */