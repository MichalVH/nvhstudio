<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Composant d'infrastructure central encapsulant l'instance native PDO.
 * Configuration blindée contre les injections SQL et optimisée pour la performance.
 */
final class Database
{
    private ?PDO $pdo = null;

    /**
     * Le constructeur initialise la connexion PDO de manière sécurisée.
     */
    public function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password,
        string $charset = 'utf8mb4'
    ) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset);

        $options = [
            // 1. LE BOUCLIER ANTI-SILENCE : Force PDO à lever des exceptions en cas d'erreur SQL
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            
            // 2. OPTIMISATION UX : Retourne les résultats sous forme de tableaux associatifs par défaut
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            // 3. SECURITE ABSOLUE : Désactive l'émulation pour forcer de vraies requêtes préparées natives
            // (Pare-feu mathématique imparable contre les failles d'Injections SQL)
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new \RuntimeException("Échec critique de la connexion à la base de données : " . $e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Donne un accès direct à l'instance PDO pour exécuter des requêtes.
     */
    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            throw new \LogicException("Impossible de récupérer la connexion : PDO n'a pas été initialisé.");
        }
        
        return $this->pdo;
    }
}
