# Architecture

`public/index.php` is the only entry point. It starts the session, loads `.env`, opens SQLite, and dispatches on `page`.

Views never write SQL. `BookingService` is the only class that touches the database, and every statement is prepared. `Validator` returns cleaned data plus field errors so the book page can redisplay the form.

Times are stored as UTC `Y-m-d H:i:s` strings. The cancel action is a conditional update: it changes a row only when the email matches and the status is still `confirmed`.

The built-in PHP server does not route extension-less URLs to `index.php` by itself, so `public/router.php` sends those requests there and lets real files such as CSS pass through.
