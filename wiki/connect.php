<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private $host = "localhost";
    private $port = "5433";
    private $dbname = "wiki_db";
    private $user = "postgres";
    private $password = "9ovHLcZvDraaq4NH";

    private function __construct()
    {
        try {

            $this->pdo = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}",
                $this->user,
                $this->password
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Erreur DB : " . $e->getMessage());
        }
    }

    // Singleton : 1 seule instance PDO
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}