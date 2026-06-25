<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Client
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function allForUser(int $userId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $total = $this->db->query(
            'SELECT COUNT(*) FROM clients WHERE user_id = uid',
            ['uid' => $userId]
        )->fetchColumn();

        $clients = $this->db->query(
            'SELECT * FROM clients WHERE user_id = :uid ORDER BY name ASC LIMIT :limit OFFSET :offset',
            ['uid' => $userId, 'limit' => $perPage, 'offset' => $offset]
        )->fetchAll();

        return [
            'data' => $clients,
            'total' => (int) $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => max(1, (int) ceil($total / $perPage))
        ];
    }

    public function findByIdAndUser(int $id, int $userId): ?array
    {
        return $this->db->query(
            'SELECT * FROM clients WHERE id = :id AND user_id = :uid',
            ['id' => $id, 'uid' => $userId]
        )->fetch() ?: null;
    }

    public function create(int $userId, array $data): int
    {
        $this->db->execute(
            'INSERT INTO clients (user_id, name, email, address, phone) VALUES (:uid, :name, :email, :address, :phone)',
            [
                'uid' => $userId,
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]
        );
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): void
    {
        $this->db->execute(
            'UPDATE clients SET name = :name, email = :email, address = :address, phone = :phone WHERE id = :id AND user_id = :uid',
            [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
                'id' => $id,
                'uid' => $userId,
            ]
        );
    }

    public function delete(int $id, int $userId): void
    {
        $this->db->execute(
            'DELETE FROM clients WHERE id = :id AND user_id = :uid',
            ['id' => $id, 'uid' => $userId]
        );
    }
}