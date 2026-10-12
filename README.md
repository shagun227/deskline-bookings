# Deskline Bookings

Portfolio project by Shagun Sharma. A plain PHP appointment desk: guests book a Zoom, Google Meet, or Skype session, and an admin can review the list. Original sample code, not a client site.

## Stack

PHP 8.2, PDO, SQLite, server-rendered pages. No framework and no Composer packages.

## Run

```bash
cp .env.example .env
php -S 127.0.0.1:8080 -t public public/router.php
```

Open `http://127.0.0.1:8080`. The admin password in `.env.example` is `deskline-demo`. Change it before you use this anywhere else.

## What it does

- Creates `data/bookings.sqlite` from `sql/schema.sql` on first request
- Validates name, email, a future start time, and the meeting medium
- Stores a CSRF token on every form
- Cancels a booking only when the id and guest email both match
- Admin sign-in compares the password with `hash_equals`

## Layout

```
public/index.php       front controller
public/router.php      used by the PHP built-in server
src/BookingService.php SQL for create, list, cancel
src/Validator.php      input rules
views/                 schedule, book, admin
sql/schema.sql         table and indexes
```
