-- Run this once in phpMyAdmin if you already imported an older database.sql
USE french_riviera;

-- 1) Add the is_admin column (ignore the error if the column already exists)
ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER password_hash;

-- 2) Create / promote the default admin account
--    Login: admin@frenchriviera.example
--    Password: admin1234
INSERT INTO users (name, email, password_hash, is_admin)
VALUES ('Admin', 'admin@frenchriviera.example', '$2y$10$L/wHgkeU2fu2Zh6foteLg.Rw4JNz4mE3jsPq7d0Y2hAiPsnC6nUC.', 1)
ON DUPLICATE KEY UPDATE is_admin = 1, password_hash = VALUES(password_hash);

-- Optional: promote your own account instead (replace the email)
-- UPDATE users SET is_admin = 1 WHERE email = 'your@email.com';
