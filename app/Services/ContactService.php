<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * Service métier dédié à la validation et à la persistance des leads prospects.
 */
final class ContactService
{
    /**
     * L'autowiring du Container va injecter automatiquement le composant Database.
     */
    public function __construct(
        private readonly Database $database
    ) {}

    /**
     * Valide les entrées et insère proprement le lead en base de données.
     * 
     * @throws \InvalidArgumentException Si une validation métier échoue.
     */
    public function createLead(string $name, string $email, string $phone, string $message, ?string $ipAddress): bool
    {
        // 1. NETTOYAGE CHIRURGICAL ET INTERDICTION DES ENTRÉES VIDES
        $name = trim($name);
        $email = trim($email);
        $phone = trim($phone);
        $message = trim($message);

        if ($name === '' || $email === '' || $message === '') {
            throw new \InvalidArgumentException("Veuillez remplir tous les champs obligatoires (Nom, Email, Message).");
        }

        // 2. VALIDATION STRICTE DU FORMAT DE L'EMAIL (Norme ISO/RFC)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("L'adresse e-mail saisie n'est pas valide.");
        }

// 2.1 VALIDATION NUMÉRO DE TÉLÉPHONE (OPTIONNEL MAIS STRICT)
        if ($phone !== '') {
    $result = preg_replace('/[^0-9]/', '', $phone);
    $cleanedPhone = is_string($result) ? $result : '';


            if (!preg_match('/^[0-9]{10}$/', $cleanedPhone)) {
                throw new \InvalidArgumentException("Le numéro de téléphone doit comporter exactement 10 chiffres.");
            }
            $phone = $cleanedPhone; 
        }

        // 3. PRÉPARATION DE LA REQUÊTE NATIVE PARAMÉTRÉE
        $db = $this->database->getConnection();
        $sql = "INSERT INTO `contacts` (`name`, `email`, `phone`, `message`, `ip_address`) 
                VALUES (:name, :email, :phone, :message, :ip_address)";
                
        $stmt = $db->prepare($sql);

        $dbPhone = $phone !== '' ? $phone : null;

        // 4. EXÉCUTION DU PARSEUR AVEC PARAMÈTRES SÉCURISÉS
        return $stmt->execute([
            ':name'       => $name,
            ':email'      => $email,
            ':phone'      => $dbPhone,
            ':message'    => $message,
            ':ip_address' => $ipAddress
        ]);
    }
}
