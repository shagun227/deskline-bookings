<?php

declare(strict_types=1);

/** @var string $title */
/** @var string $content */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> · Deskline</title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
    <header class="top">
        <a class="brand" href="/">Deskline</a>
        <nav>
            <a href="/">Schedule</a>
            <a href="/?page=book">Book</a>
            <a href="/?page=admin">Admin</a>
        </nav>
    </header>
    <main>
        <?php if (!empty($_SESSION['flash'])): ?>
            <p class="flash"><?= htmlspecialchars((string) $_SESSION['flash'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <?= $content ?>
    </main>
</body>
</html>
