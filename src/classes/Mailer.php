<?php

namespace Classes;

use Classes\Daten;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__FILE__, 2) . "/vendor/autoload.php";


class Mailer
{
    protected $empfänger = array(); # Kennung der Personen, die eine Email erhalten sollen
    protected $empf_name = array(); # Name der Personen, die eine Email erhalten sollen


    # Kennung & Name der Personen abfragen, die personaladmlevel 4 haben.
    protected function getEmpfaenger()
    {
        $daten = new Daten();
        $vorgesetzte = $daten->getVorgesetzte();
        for ($i = 0; $i < count($vorgesetzte); $i++) {
            $this->empfänger[$i] = $vorgesetzte[$i]['account'];
            $this->empf_name[$i] = $vorgesetzte[$i]['vorname'] . ' ' . $vorgesetzte[$i]['name'];
        }
    }


    protected function send($email_prefix, $empfängername, $betreff, $email_body, $kennung, $absendername, $attachment_path, $filename)
    {
        $mail = new PHPMailer(true);                                  //Create an instance; passing `true` enables exceptions

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                       //Enable verbose debug output (ex. SMTP::DEBUG_SERVER;)
            $mail->isSMTP();                                          //Send using SMTP
            $mail->Host       = SMTP_HOST;                            //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                 //Enable SMTP authentication
            $mail->Username   = SMTP_USERNAME;                        //SMTP username
            $mail->Password   = SMTP_PASSWORD;                         //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       //Enable implicit TLS encryption
            $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('w_alal01@uni-muenster.de', 'Wael Alallan');              // sender 
            //* achtung: auskommentiert da sowas wie mm18@uni-muenster.de existiert nicht (Sender address rejected: not owned by user w_alal01). mm18 sollte auch nicht personallevel 4 heben   
            #   $mail->setFrom($kennung . '@uni-muenster.de', $absendername);             // sender    
            $mail->addAddress($email_prefix . '@uni-muenster.de', $empfängername);     // Add a recipient

            //Attachments
            if ($attachment_path != null  && $filename != null) {
                $mail->addAttachment($attachment_path, $filename);     //Add attachments    
            }

            //Content
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);                                      //Set email format to HTML
            $mail->Subject = $betreff;
            $mail->Body    = $email_body;
            $mail->AltBody = $mail->html2text($email_body);

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }



    public function sendEmail($email_body, $betreff, $kennung, $absendername, $attachment_path = null, $filename = null)
    {
        $this->getEmpfaenger();
        for ($i = 0; $i < count($this->empfänger); $i++) {
            $email_prefix = $this->empfänger[$i];
            $empfängername = $this->empf_name[$i];
            if ($email_prefix != null) {
                $this->send($email_prefix, $empfängername, $betreff, $email_body, $kennung, $absendername, $attachment_path, $filename);
            }
        }
    }
}
