<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $bookings */

ob_start();
?>
<h1>Upcoming sessions</h1>
<p class="lede">Confirmed appointments. Guests cancel with the email they booked under.</p>
<?php if ($bookings === []): ?>
    <p>No upcoming sessions. <a href="/?page=book">Book one</a>.</p>
<?php else: ?>
    <ul class="cards">
        <?php foreach ($bookings as $booking): ?>
            <li>
                <strong><?= htmlspecialchars((string) $booking['starts_at'], ENT_QUOTES, 'UTF-8') ?> UTC</strong>
                <span><?= htmlspecialchars((string) $booking['medium'], ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= htmlspecialchars((string) $booking['guest_name'], ENT_QUOTES, 'UTF-8') ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<form class="panel" method="post" action="/?page=cancel">
    <h2>Cancel a booking</h2>
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(\Deskline\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <label>Booking id <input name="id" inputmode="numeric" required></label>
    <label>Email <input name="guest_email" type="email" required></label>
    <button type="submit">Cancel</button>
</form>
<?php
$content = (string) ob_get_clean();
$title = 'Schedule';
require __DIR__ . '/layout.php';
