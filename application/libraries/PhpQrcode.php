
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class PhpQrcode {
    public function __construct() {
        require 'phpqrcode/qrlib.php';
    }
    
    public function png($content) {
        QRcode::png($content);
    }
    
}
  
/* End of file mailer.php */