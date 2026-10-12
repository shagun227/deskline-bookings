<?php

declare(strict_types=1);

namespace Deskline;

final class Validator
{
    /**
     * @param array<string, mixed> $input
     * @return array{data: array<string, string>, errors: array<string, string>}
     */
    public static function booking(array $input): array
    {
        $name = trim((string) ($input['guest_name'] ?? ''));
        $email = strtolower(trim((string) ($input['guest_email'] ?? '')));
        $starts = trim((string) ($input['starts_at'] ?? ''));
        $medium = strtolower(trim((string) ($input['medium'] ?? '')));
        $notes = trim((string) ($input['notes'] ?? ''));
        $errors = [];

        if ($name === '' || strlen($name) > 120) {
            $errors['guest_name'] = 'Enter a name up to 120 characters.';
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['guest_email'] = 'Enter a valid email address.';
        }
        $timestamp = strtotime(str_replace('T', ' ', $starts));
        if ($timestamp === false || $timestamp <= time()) {
            $errors['starts_at'] = 'Choose a date and time in the future.';
        }
        if (!in_array($medium, BookingService::MEDIA, true)) {
            $errors['medium'] = 'Choose Zoom, Google Meet, or Skype.';
        }
        if (strlen($notes) > 500) {
            $errors['notes'] = 'Notes must be 500 characters or fewer.';
        }

        return [
            'data' => [
                'guest_name' => $name,
                'guest_email' => $email,
                'starts_at' => $timestamp === false ? '' : gmdate('Y-m-d H:i:s', $timestamp),
                'medium' => $medium,
                'notes' => $notes,
            ],
            'errors' => $errors,
        ];
    }
}
