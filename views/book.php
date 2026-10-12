<?php

declare(strict_types=1);

/** @var array<string, string> $errors */
/** @var array<string, string> $old */

$errors ??= [];
$old ??= [];
$value = static function (string $key) use ($old): string {
    return htmlspecialchars($old[$key] ?? '', ENT_QUOTES, 'UTF-8');
};

ob_start();
?>
<h1>Book a session</h1>
<form class="panel" method="post" action="/?page=book">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(\Deskline\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <label>Name <input name="guest_name" value="<?= $value('guest_name') ?>" required></label>
    <?php if (isset($errors['guest_name'])): ?><p class="error"><?= htmlspecialchars($errors['guest_name'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>Email <input name="guest_email" type="email" value="<?= $value('guest_email') ?>" required></label>
    <?php if (isset($errors['guest_email'])): ?><p class="error"><?= htmlspecialchars($errors['guest_email'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>Starts <input name="starts_at" type="datetime-local" value="<?= $value('starts_at') ?>" required></label>
    <?php if (isset($errors['starts_at'])): ?><p class="error"><?= htmlspecialchars($errors['starts_at'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>Meeting
        <select name="medium">
            <?php foreach (\Deskline\BookingService::MEDIA as $medium): ?>
                <option value="<?= $medium ?>" <?= ($old['medium'] ?? '') === $medium ? 'selected' : '' ?>><?= $medium ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (isset($errors['medium'])): ?><p class="error"><?= htmlspecialchars($errors['medium'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>Notes <textarea name="notes" rows="3"><?= $value('notes') ?></textarea></label>
    <button type="submit">Confirm booking</button>
</form>
<?php
$content = (string) ob_get_clean();
$title = 'Book';
require __DIR__ . '/layout.php';
