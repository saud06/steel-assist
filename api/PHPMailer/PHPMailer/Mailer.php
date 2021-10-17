<?php
    use PHPMailer\PHPMailer\PHPMailer;

    require_once("PHPMailer/PHPMailer.php");
    require_once("PHPMailer/Exception.php");
    require_once("PHPMailer/SMTP.php");

    function Mailer($email, $subject, $body){
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
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $body;

        if($mail->send())
            return true;
        else
            return false;
    }
?>
