<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// print_r($_SERVER);
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// die("sdsdsds");
class sendMails {
    private $dbc;
    private $error_message;

    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
	}

    public static function sendMail($subject, $senderName, $senderEmail, $content, $receiverName, $receiverEmail){
        // $receiverEmail = "machoos522@gmail.com";
        // $receiverEmail = "cibil0007@gmail.com";
        
        if($receiverEmail == 'cibil0007@gmail.com') return true;
        
        $vs = "INSERT INTO `mail_log` (`usermane`, `subject` ,`mailID` ) VALUES ('$receiverName','$subject','$receiverEmail')";
        $DBC = mysqli_connect('localhost', 'u775466301_machooscrm', 'Raj.sarath522@123','u775466301_machooscrm');
        $DBC->query($vs);

        $Logolink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] 
                === 'on' ? "https" : "http") . 
                "://" . $_SERVER['HTTP_HOST']."/images/machooseLogo.png";
    // echo $link;
        // die($Logolink);
        
         $newIMGPath = $_SERVER['HTTP_HOST']."/admin/tinymceuploads/" ;

        $content = str_replace("tinymceuploads/",$newIMGPath,$content);
        
       
        $content = str_replace("../signature_album.php","machooosinternational.com/signature_album.php",$content);
        $content = str_replace("../online-album.php","machooosinternational.com/online-album.php",$content);

        $mail = new PHPMailer(true);
        $senderName = "Machooos International";
        try {
			//print_r($mail);

			$mail = new PHPMailer;
			$mail->isSMTP(); 
			$mail->SMTPDebug = false; 
			$mail->Host = "smtp.gmail.com"; 
			$mail->Port = "587"; // typically 587 
			$mail->SMTPSecure = 'tls'; // ssl is depracated
			$mail->SMTPAuth = true;
			$mail->Username = "enquirywebmachoos@gmail.com";
			$mail->Password = "umjkayrwcpphdiup";
			$mail->setFrom($senderEmail, $senderName);
			$mail->addAddress($receiverEmail, $receiverName);
            $mail->addReplyTo($senderEmail, 'Information');
			$mail->Subject = $subject;
            $html = '<!DOCTYPE html>
            <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
                <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->
            
                <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700" rel="stylesheet">
            
                <!-- CSS Reset : BEGIN -->
                <style>
            
                    /* What it does: Remove spaces around the email design added by some email clients. */
                    /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
                    html,
            body {
                margin: 0 auto !important;
                padding: 0 !important;
                height: 100% !important;
                width: 100% !important;
                background: #f1f1f1;
            }
            
            /* What it does: Stops email clients resizing small text. */
            * {
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }
            
            /* What it does: Centers email on Android 4.4 */
            div[style*="margin: 16px 0"] {
                margin: 0 !important;
            }
            
            /* What it does: Stops Outlook from adding extra spacing to tables. */
            table,
            td {
                mso-table-lspace: 0pt !important;
                mso-table-rspace: 0pt !important;
            }
            
            /* What it does: Fixes webkit padding issue. */
            table {
                border-spacing: 0 !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                margin: 0 auto !important;
            }
            
            /* What it does: Uses a better rendering method when resizing images in IE. */
            img {
                -ms-interpolation-mode:bicubic;
            }
            
            /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
            a {
                text-decoration: none;
            }
            
            /* What it does: A work-around for email clients meddling in triggered links. */
            *[x-apple-data-detectors],  /* iOS */
            .unstyle-auto-detected-links *,
            .aBn {
                border-bottom: 0 !important;
                cursor: default !important;
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
            
            /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
            .a6S {
                display: none !important;
                opacity: 0.01 !important;
            }
            
            /* What it does: Prevents Gmail from changing the text color in conversation threads. */
            .im {
                color: inherit !important;
            }
            img.g-img + div {
                display: none !important;
            }
            
            /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
            @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
                u ~ div .email-container {
                    min-width: 320px !important;
                }
            }
            /* iPhone 6, 6S, 7, 8, and X */
            @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
                u ~ div .email-container {
                    min-width: 375px !important;
                }
            }
            /* iPhone 6+, 7+, and 8+ */
            @media only screen and (min-device-width: 414px) {
                u ~ div .email-container {
                    min-width: 414px !important;
                }
            }
            </style>
            
                <!-- CSS Reset : END -->
            
                <!-- Progressive Enhancements : BEGIN -->
            <style>
            
            .primary{
                background: #17bebb;
            }
            .bg_white{
                background: #ffffff;
            }
            .bg_light{
                background: #f7fafa;
            }
            .bg_black{
                background: #000000;
            }
            .bg_dark{
                background: rgba(0,0,0,.8);
            }
            .email-section{
                padding:2.5em;
            }
            
            /*BUTTON*/
            .btn{
                padding: 10px 15px;
                display: inline-block;
            }
            .btn.btn-primary{
                border-radius: 5px;
                background: #17bebb;
                color: #ffffff;
            }
            .btn.btn-white{
                border-radius: 5px;
                background: #ffffff;
                color: #000000;
            }
            .btn.btn-white-outline{
                border-radius: 5px;
                background: transparent;
                border: 1px solid #fff;
                color: #fff;
            }
            .btn.btn-black-outline{
                border-radius: 0px;
                background: transparent;
                border: 2px solid #000;
                color: #000;
                font-weight: 700;
            }
            .btn-custom{
                color: rgba(0,0,0,.3);
                text-decoration: underline;
            }
            
            h1,h2,h3,h4,h5,h6{
                font-family: "Poppins", sans-serif;
                color: #000000;
                margin-top: 0;
                font-weight: 400;
            }
            
            body{
                font-family: "Poppins", sans-serif;
                font-weight: 400;
                font-size: 15px;
                line-height: 1.8;
                color: rgba(0,0,0,.4);
            }
            
            a{
                color: #17bebb;
            }
            table{
            }
            .logo h1{
                margin: 0;
            }
            .logo h1 a{
                color: #17bebb;
                font-size: 24px;
                font-weight: 700;
                font-family: "Poppins", sans-serif;
            }
            .hero{
                position: relative;
                z-index: 0;
            }
            
            .hero .text{
                color: rgba(0,0,0,.3);
            }
            .hero .text h2{
                color: #000;
                font-size: 34px;
                margin-bottom: 0;
                font-weight: 200;
                line-height: 1.4;
            }
            .hero .text h3{
                font-size: 24px;
                font-weight: 300;
            }
            .hero .text h2 span{
                font-weight: 600;
                color: #000;
            }
            .text-author{
                bordeR: 1px solid rgba(0,0,0,.05);
                max-width: 70%;
                margin: 0 auto;
                padding: 2em;
            }
            .text-author img{
                border-radius: 50%;
                padding-bottom: 20px;
            }
            .text-author h3{
                margin-bottom: 0;
            }
            ul.social{
                padding: 0;
            }
            ul.social li{
                display: inline-block;
                margin-right: 10px;
            }
            .footer{
                border-top: 1px solid rgba(0,0,0,.05);
                color: rgba(0,0,0,.5);
            }
            .footer .heading{
                color: #000;
                font-size: 20px;
            }
            .footer ul{
                margin: 0;
                padding: 0;
            }
            .footer ul li{
                list-style: none;
                margin-bottom: 10px;
            }
            .footer ul li a{
                color: rgba(0,0,0,1);
            }
            </style>
            </head>
            <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
                <center style="width: 100%; background-color: #f1f1f1;">
                <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
                  &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                </div>
                <div style="max-width: 800px; margin: 0 auto;" class="email-container">
                    <!-- BEGIN BODY -->
                  <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

                  <!-- <tr>
                  <td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
                      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                              <td class="logo" style="text-align: center;">
                                <img src="'.$Logolink.'" style="width: 100px; max-width: 600px; height: auto; margin: auto; display: block;">
                              </td>
                          </tr>
                      </table>
                  </td>
                  </tr> end tr -->


                        <tr>
                      <td valign="middle" class="hero bg_white" style="padding: 2em 0 4em 0;">'.$content.'</td>
                      </tr><!-- end tr -->
                  <!-- 1 Column Text + Button : END -->
                  </table>
                  <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                    <tr>
                      <td class="bg_light" style="text-align: center;">
                          <p style="padding: 20px;"><a href="https://machooosinternational.com/" style="color: #0d6efd;">machooosinternational.com</a></p>
                      </td>
                    </tr>
                  </table>
                </div>
              </center>
            </body>
            </html>';
            // die($html);
			$mail->msgHTML($html); // remove if you do not want to send HTML email
			$mail->AltBody = 'HTML not supported';
			// $mail->addAttachment('docs/brochure.pdf'); //Attachment, can be skipped

			$mail->send();
			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			// $mail->isSMTP();                                            //Send using SMTP
			// // print_r($mail);
			// die(" I am here !");
			// $mail->Host   = 'smtp.gmail.com';                     //Set the SMTP server to send through
			// $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			// $mail->Username   = 'enquirywebmachoos@gmail.com';                     //SMTP username
			// $mail->Password   = '9895095694';                               //SMTP password
			// $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			// $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			
			// //Recipients
			// $mail->setFrom('vijimonvkattela@gmail.com', 'Mailer');
			// $mail->addAddress('vijimonvkattela@gmail.com', 'Joe User');     //Add a recipient
			// $mail->addAddress('vijimonvkattela@gmail.com');               //Name is optional
			// $mail->addReplyTo('vijimonvkattela@gmail.com', 'Information');
			// $mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');
		
			//Attachments
			// $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
		
			//Content
			// $mail->isHTML(true);                                  //Set email format to HTML
			// $mail->Subject = 'Here is the subject';
			// $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
			// $mail->send();
			// echo 1;
			// die(" I am here !");
		} catch (Exception $e) {
			//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			// die(" I am here !");
		}

    }
}
?>