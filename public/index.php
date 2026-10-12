<?php

declare(strict_types=1);

use Deskline\BookingService;
use Deskline\Csrf;
use Deskline\Database;
use Deskline\Env;
use Deskline\Validator;

session_start();

$root = dirname(__DIR__);
spl_autoload_register(static function (string $class) use ($root): void {
    $prefix = 'Deskline\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = $root . '/src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

Env::load($root . '/.env');
$service = new BookingService(Database::connect($root));
$page = (string) ($_GET['page'] ?? 'home');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST' && !Csrf::check($_POST['csrf'] ?? null)) {
    http_response_code(419);
    echo 'Invalid form token. Go back and try again.';
    return;
}

if ($page === 'book' && $method === 'POST') {
    $result = Validator::booking($_POST);
    if ($result['errors'] === []) {
        $id = $service->create($result['data']);
        $_SESSION['flash'] = 'Booking ' . $id . ' is confirmed.';
        header('Location: /');
        return;
    }
    $errors = $result['errors'];
    $old = [
        'guest_name' => (string) ($_POST['guest_name'] ?? ''),
        'guest_email' => (string) ($_POST['guest_email'] ?? ''),
        'starts_at' => (string) ($_POST['starts_at'] ?? ''),
        'medium' => (string) ($_POST['medium'] ?? ''),
        'notes' => (string) ($_POST['notes'] ?? ''),
    ];
    require $root . '/views/book.php';
    return;
}

if ($page === 'cancel' && $method === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $email = (string) ($_POST['guest_email'] ?? '');
    $_SESSION['flash'] = $service->cancel($id, $email)
        ? 'Booking cancelled.'
        : 'No confirmed booking matched that id and email.';
    header('Location: /');
    return;
}

if ($page === 'login' && $method === 'POST') {
    $expected = Env::get('DESKLINE_ADMIN_PASSWORD');
    $given = (string) ($_POST['password'] ?? '');
    if ($expected !== '' && hash_equals($expected, $given)) {
        $_SESSION['admin'] = true;
        header('Location: /?page=admin');
        return;
    }
    $_SESSION['flash'] = 'Password did not match.';
    header('Location: /?page=admin');
    return;
}

if ($page === 'logout') {
    unset($_SESSION['admin']);
    header('Location: /?page=admin');
    return;
}

if ($page === 'book') {
    $errors = [];
    $old = [];
    require $root . '/views/book.php';
    return;
}

if ($page === 'admin') {
    $authed = !empty($_SESSION['admin']);
    $bookings = $authed ? $service->all() : [];
    require $root . '/views/admin.php';
    return;
}

$bookings = $service->upcoming();
require $root . '/views/home.php';
