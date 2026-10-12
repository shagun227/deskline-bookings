<?php

declare(strict_types=1);

namespace Deskline;

use PDO;

final class Database
{
    public static function connect(string $root): PDO
    {
        $directory = $root . '/data';
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $pdo = new PDO('sqlite:' . $directory . '/bookings.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec((string) file_get_contents($root . '/sql/schema.sql'));

        return $pdo;
    }
}
