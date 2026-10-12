<?php

declare(strict_types=1);

namespace Deskline;

use PDO;

final class BookingService
{
    public const MEDIA = ['zoom', 'meet', 'skype'];

    public function __construct(private PDO $pdo)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function upcoming(): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, guest_name, starts_at, medium, status
             FROM bookings
             WHERE status = :status AND starts_at >= :now
             ORDER BY starts_at ASC'
        );
        $statement->execute([
            'status' => 'confirmed',
            'now' => gmdate('Y-m-d H:i:s'),
        ]);
        return $statement->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        return $this->pdo
            ->query('SELECT * FROM bookings ORDER BY starts_at DESC')
            ->fetchAll();
    }

    /** @param array<string, string> $input */
    public function create(array $input): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO bookings
                (guest_name, guest_email, starts_at, medium, notes, status, created_at)
             VALUES
                (:guest_name, :guest_email, :starts_at, :medium, :notes, :status, :created_at)'
        );
        $statement->execute([
            'guest_name' => $input['guest_name'],
            'guest_email' => $input['guest_email'],
            'starts_at' => $input['starts_at'],
            'medium' => $input['medium'],
            'notes' => $input['notes'],
            'status' => 'confirmed',
            'created_at' => gmdate('Y-m-d H:i:s'),
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function cancel(int $id, string $email): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE bookings
             SET status = :status
             WHERE id = :id AND lower(guest_email) = :email AND status = :current'
        );
        $statement->execute([
            'status' => 'cancelled',
            'id' => $id,
            'email' => strtolower($email),
            'current' => 'confirmed',
        ]);
        return $statement->rowCount() === 1;
    }
}
