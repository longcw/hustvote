
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
  
class Mailer {
  
    var $mail;
  
    public function __construct()
    {
        require_once('phpmailer/class.phpmailer.php');
        // the true param means it will throw exceptions on errors, which we need to catch
        $this->mail = new PHPMailer(true);
  
        $this->mail->IsSMTP(); // telling the class to use SMTP
  
        $this->mail->CharSet = "utf-8";                  // 一定要設定 CharSet 才能正確處理中文
      //  $this->mail->SMTPDebug  = 0;                     // enables SMTP debug information
        $this->mail->SMTPAuth   = true;                  // enable SMTP authentication
        $this->mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $this->mail->Host       = "smtp.163.com";      // sets 163 as the SMTP server
        $this->mail->Port       = 465;                   // set the SMTP port for the 163 server
        $this->mail->Username   = "guesstest@163.com";// 163 username
        $this->mail->Password   = "hustyy23";       // 163 password
       /// $this->mail->AddReplyTo('@163.com', 'YOUR_NAME');   //回复地址(可填可不填)
        //$this->mail->SetFrom('YOUR_GAMIL@163.com', 'YOUR_NAME'); 
    }
    
    /**
     * 发送邮件
     * @param array $to $key是姓名 $value是地址
     * @param string $subject
     * @param string $body
     * @return boolean
     */
    public function sendmail($to, $subject, $body){
        try{
            $this->mail->From = 'guesstest@163.com';
            $this->mail->FromName = 'Canmou';
            foreach ($to as $name => $address) {
                $this->mail->AddAddress($address, $name);
            }
            
             
            $this->mail->WordWrap = 50;                                 // Set word wrap to 50 characters
            $this->mail->IsHTML(true);                         // 使用html格式
 
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $state = $this->mail->Send();

            return $state;
            
        } catch (phpmailerException $e) {
            
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
            return false;
        } catch (Exception $e) {
            
            echo $e->getMessage(); //Boring error messages from anything else!
            return false;
        }
    }
}
  
/* End of file mailer.php */