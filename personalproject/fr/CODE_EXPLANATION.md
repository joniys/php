# French Riviera — web photos and code guide

## Web photos added
The seeded menu now uses remotely hosted Unsplash food photographs instead of the local illustrated SVG dish placeholders. The homepage hero background also uses a hosted Unsplash photograph. The image URLs include `auto=format`, crop sizing, and quality parameters so the browser can request optimized images.

**Important:** the photo links need an internet connection. If you want to use local photos instead, download licensed images and change the `image` values in `database.sql` to paths such as `images/dishes/dish-1.jpg`. The existing `images/dishes/*.svg` files remain in the ZIP as backups.

## Apply the changes
1. Extract the ZIP.
2. In phpMyAdmin, import `database.sql` into MySQL. This SQL file creates and resets the project database/tables, so importing it again replaces the seeded menu data.
3. Open the project through your PHP server (for example XAMPP: `http://localhost/french-riviera/`).
4. If you already imported the old database, re-import the updated `database.sql` to replace the old image paths.

## Code map and what each line/statement does

This project contains many files, so a literal explanation of every punctuation mark across all PHP/CSS/SQL would be extremely long. This guide explains the executable statements and markup by file and line-group; blank lines and indentation are only for readability.

### `includes/bootstrap.php` — shared setup
- `const DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`: define the MySQL connection settings.
- `SITE_ADDRESS`, `SITE_PHONE`, `SITE_EMAIL`: central contact details used by the footer/contact page.
- `session_set_cookie_params(...)`: makes the session cookie inaccessible to JavaScript and uses same-site behavior.
- `session_start()`: starts or resumes the user's login session.
- `current_user()`: returns the session's user array, or `null` when logged out.
- `is_admin()`: checks whether the current session user has the admin flag.
- The access guard redirects non-logged-in visitors to `login.php`, except pages that set `$public = true`.
- `db()`: creates one PDO MySQL connection on first use, enables exceptions, fetches rows as associative arrays, and disables emulated prepared statements.
- `e($v)`: HTML-escapes dynamic values to reduce cross-site scripting risk.
- `euro($v)`: formats a number as a European-style price followed by the euro symbol.
- `csrf_token()`: creates and stores a random form token if one does not exist, then returns it.
- `csrf_valid($t)`: safely compares a submitted token with the session token.

### `includes/header.php` — shared page header
- `require_once ... bootstrap.php`: loads shared session/database helpers once.
- `$pageTitle` and `$active`: use values set by the page, or safe defaults.
- `<!DOCTYPE html>` and `<html lang="en">`: declare an HTML5 page and its language.
- `<meta charset>`: selects UTF-8 so French characters render correctly.
- viewport meta: makes the layout scale on mobile.
- `<title>`: displays the current page title and restaurant name.
- description meta: provides a short search-engine description.
- Google Fonts links: load Cormorant Garamond and Montserrat.
- stylesheet link: loads the project's CSS.
- `<body class>`: lets CSS style the active page.
- brand link: returns to the homepage.
- the `current_user()` condition: only shows the navigation when signed in.
- nav toggle: mobile menu button; its three spans form the hamburger icon.
- each `<a>`: navigates to the page named by its `href`; the PHP class expression marks the current page.
- `is_admin()` condition: only admins see the admin link.
- `current_user()['name']`: adds the logged-in name to the logout link tooltip.
- `<main>`: starts the main content region, which `footer.php` closes.

### `includes/footer.php`
- `</main>` closes the main page content.
- footer columns show restaurant name, address, telephone, email, hours, and navigation.
- `e(...)` escapes dynamic contact data before putting it in HTML.
- `tel:` and `mailto:` links open phone/email apps.
- `date('Y')` prints the current year.
- the script tag loads `assets/js/main.js`; the script controls menu filtering, mobile navigation, and reveal animations.

### `index.php` — homepage
- `require_once` loads session/database functions and database error handling.
- `db()->query(...)->fetchAll()` retrieves the three featured dishes from MySQL.
- `try/catch` catches database failures; `db_fail()` displays a helpful local error page.
- `$pageTitle` and `$active` set the page title and navigation state.
- `require header.php` prints the common HTML head and navigation.
- `.hero` is the full-screen hero section; its text and buttons introduce the restaurant.
- `.island-wrap` positions the floating feature card over the hero.
- `.island` links to the menu; image, heading, paragraph, and button make up the card.
- `.intro` presents the restaurant's cuisine description.
- `foreach ($featured as $f)` creates one card per database result.
- `food.php?id=...` opens the selected dish detail page; `(int)` converts its ID to an integer.
- `e(...)` escapes dish text; `euro(...)` formats its price.
- `.cta` gives the visitor a reservation link.
- `require footer.php` adds the shared footer and JavaScript.

### `menu.php` — menu listing and categories
- loads shared helpers and error handling.
- the first query reads menu categories ordered by `sort_order`.
- `prepare(...)` creates a reusable parameterized query for dishes in one category.
- the page title and active navigation are set before loading the header.
- category buttons carry `data-filter` values that JavaScript uses to show/hide sections.
- the outer `foreach` executes the dish query for each category.
- `fetchAll()` collects that category's dishes.
- `data-cat` and `id` identify the section for filtering and anchor navigation.
- the inner `foreach` renders each dish as a clickable card.
- the image `src` is read from the database; `loading="lazy"` delays off-screen image loading.
- the card shows name, short description, price, and detail link.
- footer closes the page.

### `food.php` — dish detail
- `filter_input(...FILTER_VALIDATE_INT...)` reads a positive dish ID from the URL.
- `$dish` and `$related` start empty so missing/invalid IDs are handled.
- the prepared SQL joins dishes to categories and uses `?` for the ID parameter.
- `execute([$id])` supplies the ID without concatenating it into SQL.
- `fetch()` reads the matching dish.
- if a dish exists, a second prepared query gets up to three other dishes in its category.
- exceptions route to `db_fail()`.
- missing dishes return HTTP 404.
- page title and active navigation are set.
- the conditional displays a not-found message or the dish detail.
- `e()` escapes dish fields; `nl2br()` preserves line breaks in the description.
- related dishes use a loop to render linked cards.
- footer is included in both cases.

### `login.php` — authentication
- `$public = true` allows visitors to reach the login page without a session.
- shared helpers and database error handling are loaded.
- already logged-in users are redirected home.
- `$error` and `$email` store validation feedback and the entered email.
- POST means the login form was submitted.
- email is trimmed and lowercased; password is read as a string.
- CSRF validation rejects expired/forged submissions.
- empty email/password are rejected.
- prepared SQL looks up the account by email.
- `password_verify()` compares the submitted password with the saved password hash.
- successful login regenerates the session ID to reduce session-fixation risk.
- the session stores only the user identity and admin status needed by the site.
- redirect sends the user to the homepage.
- failed credentials display a generic message.
- the HTML form posts to this page; hidden CSRF token protects the submission.
- `type=email` and `type=password` enable browser input behavior; `required` prevents empty submission.
- footer loads shared scripts.

### `signup.php` — account creation
- `$public = true` makes registration public.
- signed-in users are redirected home.
- `$errors` stores per-field messages; `$old` stores safe values to refill the form.
- POST handling trims name/email and reads password/confirmation.
- CSRF, name length, email format, password length, and matching confirmation are checked.
- only if there are no errors does the code prepare an INSERT statement.
- `password_hash()` stores a one-way password hash, not the original password.
- `execute()` inserts the account; `lastInsertId()` gets its new ID.
- session regeneration and session assignment sign the user in.
- duplicate email SQL error code `23000` is shown as a field error.
- `$f` is a helper closure that prints one form field with label, input, and error message.
- the form calls `$f(...)` for name, email, password, and confirmation.
- footer loads shared scripts.

### `contact.php` — contact and reservations
- shared bootstrap and database error helpers are loaded.
- the code prepares date/time/guest defaults and validates the submitted fields.
- POST requests are checked for CSRF and the hidden honeypot field.
- date, time, guest count, contact details, and notes are validated before saving.
- the prepared INSERT stores a reservation safely.
- the session stores a one-time confirmation summary; redirect prevents form resubmission.
- the confirmation is read and removed from the session after redirect.
- contact details and hours are printed from shared constants.
- the reservation form includes labels, required inputs, dropdowns, optional notes, and field-level error text.
- `e()` escapes all displayed user-provided values.
- footer closes the page.

### `admin.php` — administrator menu management
- shared setup is loaded; `is_admin()` blocks non-admin users.
- `$success`, `$errors`, `$editing`, and `$old` store feedback, validation errors, selected dish, and form values.
- category query fills the category dropdown.
- GET `edit` loads an existing dish into the form.
- POST `action=delete` checks CSRF and deletes the requested dish by prepared ID.
- POST `action=add` or `update` reads form values and validates all fields.
- category IDs are checked against the actual category list.
- price is normalized from comma to dot and checked for numeric range.
- prepared INSERT/UPDATE queries save dish data without SQL string concatenation.
- successful saves reset the form; updates redirect to avoid duplicate submission.
- the listing query joins categories and sorts the menu.
- the HTML form sends hidden CSRF/action/ID fields.
- the dish table shows image, name, category, price, featured status, and edit/delete actions.
- delete confirmation asks the user before sending the delete request.
- the final style block adjusts the admin table and form controls.

### `assets/js/main.js` — browser interactions
- the script selects the header, navigation toggle, and menu using DOM selectors.
- scroll handling adds the compact header class after scrolling.
- the toggle click changes `aria-expanded` and opens/closes the mobile navigation.
- category filter buttons read their `data-filter` value and hide nonmatching menu sections.
- reveal elements are observed as they enter the viewport and receive the visible class.
- the script checks whether browser features exist before using them.

### `assets/css/style.css` — visual design
- `:root` declares reusable colors, font families, and shadow values.
- `*` resets margin/padding and uses border-box sizing.
- `html` enables smooth anchor scrolling.
- `body` sets typography, colors, background, and line spacing.
- `img` prevents images from overflowing their containers.
- heading, paragraph, container, section, and eyebrow rules establish shared typography/layout.
- header rules make the navigation fixed, translucent, and responsive.
- button rules define gold/navy/outline variants and hover transitions.
- `.hero` sets the full-screen background photo, overlay gradient, and cover sizing.
- `.island-wrap` overlaps the hero; `.island` creates the floating card grid and shadow.
- `.island:hover` pauses the floating animation and lifts the card.
- `.island-img img` crops the photo and zooms on hover.
- remaining selectors style menu cards, details, forms, alerts, footer, responsive layouts, and animations.

### `database.sql` — database structure and starter content
- `CREATE DATABASE` creates the UTF-8 database if missing; `USE` selects it.
- `DROP TABLE` clears old starter tables before rebuilding (importing again resets data).
- `CREATE TABLE categories` stores menu categories and ordering.
- `CREATE TABLE dishes` stores dish text, price, image URL/path, featured flag, and category relationship.
- `CREATE TABLE reservations` stores customer reservation information and timestamp.
- `CREATE TABLE users` stores account names/emails/password hashes/admin flags.
- starter admin and category INSERT statements create initial records.
- the dishes INSERT statements create the starter menu; each `image` field now points to a web photo URL.

## Image attribution / licensing
Photos are loaded from Unsplash image CDN URLs. Please verify the specific photo's license and attribution requirements before publishing a commercial restaurant website. This project uses image URLs, not downloaded copies.
