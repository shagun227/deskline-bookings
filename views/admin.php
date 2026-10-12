<?php

declare(strict_types=1);

/** @var bool $authed */
/** @var array<int, array<string, mixed>> $bookings */

ob_start();
?>
<h1>Admin</h1>
<?php if (!$authed): ?>
    <form class="panel" method="post" action="/?page=login">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(\Deskline\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
        <label>Password <input name="password" type="password" required></label>
        <button type="submit">Sign in</button>
    </form>
<?php else: ?>
    <p><a href="/?page=logout">Sign out</a></p>
    <table>
        <thead>
            <tr><th>Id</th><th>When (UTC)</th><th>Guest</th><th>Email</th><th>Medium</th><th>Status</th></tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= (int) $booking['id'] ?></td>
                    <td><?= htmlspecialchars((string) $booking['starts_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $booking['guest_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $booking['guest_email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $booking['medium'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $booking['status'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php
$content = (string) ob_get_clean();
$title = 'Admin';
require __DIR__ . '/layout.php';
