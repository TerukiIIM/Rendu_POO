<?php
abstract class Database
{
    protected $connection = null;

    protected function __construct()
    {
        if ($this->connection == null) {
            $this->connect();
        }
    }

    private function connect(): void
    {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $_ENV['db_host'] . ';dbname=' . $_ENV['db_name'],
                $_ENV['db_user'],
                $_ENV['db_pwd']
            );

            // Configuration des options de PDO (par exemple, gestion des erreurs)
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
}
