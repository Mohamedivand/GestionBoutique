<?php
    ob_start();
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\PHPMailer;

    require("../script/connexion_bd.php");
    require("../class/Boutique.php");

    require '../lib/php/PHPMailer/src/Exception.php';
    require '../lib/php/PHPMailer/src/PHPMailer.php';
    require '../lib/php/PHPMailer/src/SMTP.php';
    try {
    
    $date = date('y,m,d', time());
    $query = $bdd->prepare("SELECT * FROM `boutique` WHERE finAbonnement <= ?");
    $query->execute([
        $date
    ]);

    while ($res = $query->fetch()) {
        $boutique = new Boutique($bdd, $res['idBoutique']);

        $dateFin = $boutique->getFinAbonnement();
        $emailBoutique = $boutique->getContact()->getEmail();
        $nom = $boutique->getNomBoutique();
        $message="<h3>Votre abonnement est arrive a terme</h3>";

        if ($dateFin <= $date) {
    
            $mail= new PHPMailer(true);
    
            require '../../script/mail_info.php';

            $mail->SMTPDebug=SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host=$Host;
            $mail->SMTPAuth=$SMTPAuth;
            $mail->SMTPOptions=Array('ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true));
            $mail->Username=$Username;
            $mail->Password=$Password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->$Port=$Port;
            $mail->setFrom($Username, $nom_du_site);
            $mail->addReplyTo($send_email, $nom_du_site);
            $mail->WordWrap   = $WordWrap;
            $mail->isHTML(true);
            $mail->addAddress($emailBoutique);
            $mail->Subject="Abonnement";
            
            $mail->Body="
                <h3>Bonjour $nom Vous avez recue un message depuis votre logiciel EasyManagment.</h3>
                <hr>
                ".nl2br($message);
            
            $mail->send();

            // Header("Location: ./vente.php");
            echo "<h3> envoi reussi </h3>";
            
        }
        else{
            echo "<h3>Echec envoi </h3>";
            // Header("Location: entrepot.php");

        } 
    }
} catch (Exception $e) {
    echo ($e->getMessage());
}
