<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Http\Session;
use App\Services\ContactService;
use App\Services\SecurityService;
use App\Services\MailerService;

/**
 * Contrôleur d'élite dédié à l'interception, la validation et l'aiguillage des soumissions prospects.
 */
final class ContactController
{
    /**
     * Votre Container unifié injecte désormais de manière 100% magique les 5 dépendances requises.
     */
    public function __construct(
        private readonly SecurityService $securityService,
        private readonly Request $request,
        private readonly Session $session,
        private readonly ContactService $contactService,
        private readonly MailerService $mailerService
    ) {}
    
    /**
     * Intercepte la requête POST, orchestre les barrières de validation, la BDD et l'envoi du mail.
     */
    public function submit(): Response
    {
        $response = new Response();
        
        $refererData = $this->request->serverData['HTTP_REFERER'] ?? '/';
        $referer = is_string($refererData) ? $refererData : '/';

        // SÉCURITÉ PHPSTAN LIGNE 42 : Extraction et vérification du type de la chaîne
        $requestedWith = $this->request->serverData['HTTP_X_REQUESTED_WITH'] ?? '';
        $isXmlHttpRequest = is_string($requestedWith) && strtolower($requestedWith) === 'xmlhttprequest';

        // 1. EXTRACTION ET ISOLATION DU JETON CSRF SOUMIS
        $postToken = $this->request->postData['_csrf_token'] ?? null;
        $submittedToken = is_string($postToken) ? $postToken : null;

        // 2. LE BOUCLIER SÉCURITÉ CSRF OWASP
        if (!$this->securityService->validateToken($submittedToken)) {
            if ($isXmlHttpRequest) {
                // SÉCURITÉ LIGNE 53 : Fallback si json_encode échoue
                $jsonError = json_encode(['status' => 'error', 'message' => 'Jeton CSRF invalide ou expiré.']);
                return $response->setStatusCode(403)
                                ->setHeader('Content-Type', 'application/json')
                                ->setContent(is_string($jsonError) ? $jsonError : '{"status":"error"}');
            }
            return $response->setStatusCode(403)
                            ->setContent("<h1>❌ Erreur 403 - Requête invalide ou jeton expiré.</h1>");
        }

        // 3. CAPTURE SÉCURISÉE DES ENTRÉES UTILISATEUR
        $pName = $this->request->postData['name'] ?? '';
        $pEmail = $this->request->postData['email'] ?? '';
        $pPhone = $this->request->postData['phone'] ?? '';
        $pMessage = $this->request->postData['message'] ?? '';

        $name    = is_string($pName) ? $pName : '';
        $email   = is_string($pEmail) ? $pEmail : '';
        $phone   = is_string($pPhone) ? $pPhone : '';
        $message = is_string($pMessage) ? $pMessage : '';

        // CAPTURE DE L'IP DE L'UTILISATEUR
        $sIp = $this->request->serverData['REMOTE_ADDR'] ?? null;
        $ipAddress = is_string($sIp) ? $sIp : null;

        try {
            // 4. REMPART BACK : Transmission de l'IP pour insertion
            $this->contactService->createLead($name, $email, $phone, $message, $ipAddress);


            // 5. SECOND REMPART : Notification par mail immédiate pour le client via PHPMailer SMTP
            $this->mailerService->sendContactEmail($name, $email, $phone, $message);

            $successMessage = "Merci " . $name . ", votre demande a bien été enregistrée et transmise. Nous reprenons contact avec vous sous 24h.";

            // 6. ADAPTATION DE LA RÉPONSE EN FONCTION DU CANAL (AJAX vs CLASSIQUE)
            if ($isXmlHttpRequest) {
                // SÉCURITÉ LIGNE 83 : Fallback si json_encode échoue
                $jsonSuccess = json_encode(['status' => 'success', 'message' => $successMessage]);
                return $response->setStatusCode(200)
                                ->setHeader('Content-Type', 'application/json')
                                ->setContent(is_string($jsonSuccess) ? $jsonSuccess : '{"status":"success"}');
            }

            // Injection classique du Flash de succès si JavaScript inactif
            $this->session->setFlash('success', $successMessage);

        } catch (\InvalidArgumentException $e) {
            if ($isXmlHttpRequest) {
                // SÉCURITÉ LIGNE 96 : Fallback si json_encode échoue
                $jsonInputError = json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                return $response->setStatusCode(400)
                                ->setHeader('Content-Type', 'application/json')
                                ->setContent(is_string($jsonInputError) ? $jsonInputError : '{"status":"error"}');
            }
            $this->session->setFlash('error', $e->getMessage());
        } catch (\Throwable $e) {
            $errorMessage = "Une erreur technique est survenue lors de l'envoi. Veuillez réessayer ultérieurement.";
            if ($isXmlHttpRequest) {
                // SÉCURITÉ LIGNE 107 : Fallback si json_encode échoue
                $jsonTechError = json_encode(['status' => 'error', 'message' => $errorMessage]);
                return $response->setStatusCode(500)
                                ->setHeader('Content-Type', 'application/json')
                                ->setContent(is_string($jsonTechError) ? $jsonTechError : '{"status":"error"}');
            }
            $this->session->setFlash('error', $errorMessage);
        }

        // 7. REDIRECTION SÉCURISÉE SANS DOUBLE ENVOI (Pattern PRG - Avec Ancre pour le fallback)
        return $response->setStatusCode(302)
                        ->setHeader('Location', $referer . '#contact');
    }
}
