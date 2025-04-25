<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

// Remplace par ton adresse Gmail
$receiving_email_address = 'zanajaona2404@gmail.com';
$gmail_username = 'zanajaona2404@gmail.com'; // Ton adresse Gmail complète
$gmail_app_password = 'cmnu gobq snae ojya'; // Le mot de passe d’application (PAS ton mot de passe Gmail)


// Vérifie si la requête vient bien du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sécurité & validation
    $name = htmlspecialchars(strip_tags(trim($_POST["name"] ?? "")));
    $email = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(strip_tags(trim($_POST["subject"] ?? "")));
    $message = htmlspecialchars(strip_tags(trim($_POST["message"] ?? "")));

    if (!$name || !$email || !$subject || !$message) {
        http_response_code(400);
        echo "Veuillez remplir tous les champs correctement.";
        exit;
    }

    // PHPMailer config
    $mail = new PHPMailer(true);

    try {
        // Config SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $gmail_username;
        $mail->Password   = $gmail_app_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Infos email
        $mail->setFrom($email, $name);
        $mail->addAddress($receiving_email_address);
        $mail->Subject = "📩 Nouveau message de $name : $subject";
        $mail->Body    = "Vous avez reçu un message via votre portfolio :\n\n"
                       . "Nom: $name\n"
                       . "Email: $email\n"
                       . "Sujet: $subject\n"
                       . "Message:\n$message\n";

        $mail->send();
        echo "Votre message a bien été envoyé ✅";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Erreur PHPMailer : {$mail->ErrorInfo}";
    }
} else {
    http_response_code(403);
    echo "Méthode non autorisée.";
}
?>
