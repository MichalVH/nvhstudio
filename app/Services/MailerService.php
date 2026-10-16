<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Service d'élite dédié à l'orchestration et l'envoi de courriels via SMTP sécurisé.
 */
final class MailerService
{
    /**
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Le constructeur reçoit et stocke la configuration injectée par le Container.
     * On définit des valeurs par défaut pour garantir la robustesse.
     * 
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = []) 
    {
        $this->config = array_merge([
            'host'         => 'localhost',
            'port'         => 2525,
            'username'     => null,
            'password'         => null,
            'encryption'   => 'tls',
            'from_address' => 'noreply@nvhstudio.com',
            'from_name'    => 'NVHFAST Core',
        ], $options);
    }

    /**
     * Construit et expédie un e-mail au format HTML premium pour chaque lead reçu.
     */
    public function sendContactEmail(string $name, string $email, string $phone, string $messageContent): bool
    {
        $mail = new PHPMailer(true);

        try {
            // 1. CONFIGURATION DU SERVEUR SMTP
            $mail->isSMTP();
            
            // --- OPTIMISATIONS DE PERFORMANCES ET FIABILITÉ ---
            $mail->Timeout       = 10;          // Timeout max de 10s pour libérer le thread PHP rapidement
            $mail->SMTPKeepAlive = false;       // Ferme immédiatement la connexion TCP après l'envoi
            // --------------------------------------------------
            
            $host = $this->config['host'];
            $mail->Host = is_string($host) ? $host : 'localhost';
            
            $portValue = $this->config['port'];
            $mail->Port = is_numeric($portValue) ? (int)$portValue : 2525;
            
            $username = $this->config['username'];
            $password = $this->config['password'];
            
            if (is_string($username) && $username !== 'null' && $username !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = is_string($password) ? $password : '';
            } else {
                $mail->SMTPAuth = false;
            }

            $encryption = $this->config['encryption'];
            if ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }

            // 2. EXPÉDITEUR & DESTINATAIRE
            $fromAddress = $this->config['from_address'];
            $fromName = $this->config['from_name'];
            
            $mail->setFrom(is_string($fromAddress) ? $fromAddress : 'noreply@nvhstudio.com', is_string($fromName) ? $fromName : 'NVHFAST Core');
            $mail->addAddress(is_string($fromAddress) ? $fromAddress : 'noreply@nvhstudio.com'); 
            
            $mail->addReplyTo($email, $name);
            $mail->CharSet = 'UTF-8';

            // 3. ENCAPSULATION DU CONTENU DANS UN GABARIT HTML PREMIUM SÉCURISÉ (ANTI-XSS)
            $mail->isHTML(true);
            $mail->Subject = "🔥 Nouveau Lead Prospect - NVHFAST";
            
            $safeName    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $safeEmail   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
            $safePhone   = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
            $safeMessage = nl2br(htmlspecialchars($messageContent, ENT_QUOTES, 'UTF-8'));

            $mail->Body = "
            <html>
            <body style='font-family: sans-serif; color: #1e293b; line-height: 1.6; background-color: #fafafa; padding: 20px;'>
                <div style='max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);'>
                    <h2 style='color: #020617; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-top: 0;'>Nouveau message reçu</h2>
                    <p style='margin-bottom: 8px;'><strong>Nom complet :</strong> {$safeName}</p>
                    <p style='margin-bottom: 8px;'><strong>Adresse Email :</strong> <a href='mailto:{$safeEmail}' style='color: #4f46e5;'>{$safeEmail}</a></p>
                    <p style='margin-bottom: 15px;'><strong>Téléphone :</strong> " . ($safePhone !== '' ? $safePhone : 'Non renseigné') . "</p>
                    <div style='background: #f1f5f9; padding: 15px; border-radius: 6px; border-left: 4px solid #4f46e5; margin-top: 20px;'>
                        <strong style='color: #020617; display: block; margin-bottom: 5px;'>Message du prospect :</strong>
                        {$safeMessage}
                    </div>
                </div>
            </body>
            </html>
            ";

            return $mail->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Échec de l'envoi du mail de notification : " . $mail->ErrorInfo);
        }
    }
}
