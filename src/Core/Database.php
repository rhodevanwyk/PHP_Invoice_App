<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct(array $config)
    {
       $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
       ];
       try {
        $this->pdo = new PDO($config['dsn'], $config['user'], $config['pass'], $options);
       } catch (PDOException $e) {
        throw new PDOException("DATABASE CONNCETION FAILED: " . $e->getMessage());
       }
    }

    // EXECUTE A QUERY AND RETURN THE PDOSTATEMENT...FOR SELECT W/ FETCH/FETCHALL
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // EXECUTE AN INSERT/UPDATE/DELETE AND RETURN THE AFFECTED ROWS
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTrasaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}