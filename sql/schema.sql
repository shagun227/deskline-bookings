CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    guest_name TEXT NOT NULL,
    guest_email TEXT NOT NULL,
    starts_at TEXT NOT NULL,
    medium TEXT NOT NULL,
    notes TEXT NOT NULL DEFAULT '',
    status TEXT NOT NULL DEFAULT 'confirmed',
    created_at TEXT NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_bookings_starts_at ON bookings (starts_at);
CREATE INDEX IF NOT EXISTS idx_bookings_status ON bookings (status);
