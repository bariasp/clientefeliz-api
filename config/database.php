<?php
declare(strict_types=1);

class Database {
    private string $host = 'localhost';
    private string $dbname = 'cliente_feliz';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $connection = null;

    public function getConnection(): PDO {
        if ($this->connection === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    'error' => 'Error de conexión a la base de datos',
                    'message' => $e->getMessage()
                ]);
                exit;
            }
        }

        return $this->connection;
    }
}