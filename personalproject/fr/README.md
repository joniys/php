# French Riviera – Restaurant Website (PHP + MySQL)

## Install with XAMPP (Windows)
1. Install XAMPP (https://www.apachefriends.org) and start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Copy the `french-riviera` folder into `C:\xampp\htdocs\`.
3. Open http://localhost/phpmyadmin → **Import** → choose `database.sql` → **Go**. This creates the `french_riviera` database, tables and all 18 dishes.
4. Open http://localhost/french-riviera/ in your browser.

If your MySQL has a root password or another port, edit the settings at the top of `includes/bootstrap.php`.

## Login and sign-up
The site opens on `login.php`. Visitors must log in (or create an account on `signup.php`) before they can see any other page. Passwords are stored hashed (`password_hash`). If you already imported an earlier `database.sql`, import `users.sql` once to add the `users` table (or re-import `database.sql`, which resets all data).

## Structure
- `index.php` home with hero and floating island (links to the menu)
- `menu.php` categorised menu · `food.php?id=N` one detail page per dish (data from MySQL)
- `about.php`, `contact.php` (reservation form: CSRF token, honeypot, server and client validation, saved to `reservations`)
- `admin.php` – add / edit / delete dishes (only visible to administrators)
- `includes/` shared header, footer, PDO connection · `assets/` CSS and JS · `images/` artwork · `database.sql`

## Admin – Manage dishes (real admin role)

Only users with `is_admin = 1` can open the admin page.

**Default admin account** (created by `database.sql` / `admin.sql`):
- Email: `admin@frenchriviera.example`
- Password: `admin1234`

After logging in as admin you will see **Admin** in the top menu  
(or go to `http://localhost/french-riviera/admin.php`).

You can:
- **Add** a new dish
- **Edit** any existing dish (click Edit)
- **Delete** any dish (with confirmation)

Image path example: `images/dishes/dish-1.svg` (or any file you put in `images/dishes/`).

### If you already imported the old database
Import `admin.sql` once in phpMyAdmin. It adds the `is_admin` column and creates the admin account above.

To make your own account an admin:
```sql
UPDATE users SET is_admin = 1 WHERE email = 'your@email.com';
```

## Security
PDO prepared statements everywhere, output escaped with `htmlspecialchars`, CSRF token on every form, strict server-side validation.

## Replacing the images with photographs
The bundled images are stylised illustrations (no internet access was available when generating this project). To use real photos:
1. Put your photos in `images/dishes/` (e.g. `bouillabaisse.jpg`).
2. In phpMyAdmin, open table `dishes` and change the `image` column (e.g. `images/dishes/bouillabaisse.jpg`).
3. Replace `images/hero.svg` and `images/about.svg` with your own photos (and update the two references to `hero.svg` in `assets/css/style.css` and `about.svg` in `about.php`). Free sources: unsplash.com, pexels.com.

## Viewing reservations
phpMyAdmin → `french_riviera` → `reservations`.



Log in as admin:
Email: admin@frenchriviera.example
Password: admin1234