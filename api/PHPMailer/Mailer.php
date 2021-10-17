<?php
    use PHPMailer\PHPMailer\PHPMailer;

    require_once("PHPMailer/PHPMailer.php");
    require_once("PHPMailer/Exception.php");
    require_once("PHPMailer/SMTP.php");

    function Mailer($email, $subject, $body) {
        $mail = new PHPMailer();

        //Server settings
        $mail->SMTPDebug = 2;
        $mail->Host = 'mail.inventory.rrmsteel.com.bd';
        $mail->Username = 'admin@inventory.rrmsteel.com.bd';
        $mail->Password = 'Jksa(@#*9JIDNm893h';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('admin@inventory.rrmsteel.com.bd', 'RRM Steel Support');
        
        if(strpos($email, ',') !== false){
            // handling upto 5 emails
            $addresses = explode(', ', $email);
            if(count($addresses) == 2) $mail->addAddress($addresses[0]);
            if(count($addresses) == 3) $mail->addAddress($addresses[1]);
            if(count($addresses) == 4) $mail->addAddress($addresses[2]);
            if(count($addresses) == 5) $mail->addAddress($addresses[3]);
            if(count($addresses) == 6) $mail->addAddress($addresses[4]);
        } else{
            $mail->addAddress($email);
        }

        //Content
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $body;
        $mail->IsHTML(true);

        if($mail->send())
            return true;
        else
            return false;
    }
?>
