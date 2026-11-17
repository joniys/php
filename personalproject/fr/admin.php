<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_error.php';

// Real admin role required
if (!is_admin()) {
    header('Location: index.php');
    exit;
}

$success = '';
$errors  = [];
$editing = null; // dish being edited (array) or null

$old = [
    'name'              => '',
    'category_id'       => '',
    'short_description' => '',
    'description'       => '',
    'price'             => '',
    'image'             => 'images/dishes/dish-1.svg',
    'featured'          => '0',
];

try {
    $categories = db()->query('SELECT id, name FROM categories ORDER BY sort_order')->fetchAll();
} catch (Throwable $t) {
    db_fail($t);
}

// ---------- LOAD DISH FOR EDIT ----------
$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($editId && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    try {
        $s = db()->prepare('SELECT * FROM dishes WHERE id = ?');
        $s->execute([$editId]);
        $editing = $s->fetch();
        if ($editing) {
            $old = [
                'name'              => $editing['name'],
                'category_id'       => (string)$editing['category_id'],
                'short_description' => $editing['short_description'],
                'description'       => $editing['description'],
                'price'             => $editing['price'],
                'image'             => $editing['image'],
                'featured'          => $editing['featured'] ? '1' : '0',
            ];
        }
    } catch (Throwable $t) {
        db_fail($t);
    }
}

// ---------- DELETE ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $errors['form'] = 'Your session expired. Please try again.';
    } else {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id) {
            try {
                $stmt = db()->prepare('DELETE FROM dishes WHERE id = ?');
                $stmt->execute([$id]);
                if ($stmt->rowCount()) {
                    $success = 'Dish deleted successfully.';
                } else {
                    $errors['form'] = 'Dish not found.';
                }
            } catch (Throwable $t) {
                db_fail($t);
            }
        }
    }
}

// ---------- ADD / UPDATE ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['action'] ?? '', ['add', 'update'], true)) {
    $action = $_POST['action'];
    $editId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    $old['name']              = trim((string)($_POST['name'] ?? ''));
    $old['category_id']       = (string)($_POST['category_id'] ?? '');
    $old['short_description'] = trim((string)($_POST['short_description'] ?? ''));
    $old['description']       = trim((string)($_POST['description'] ?? ''));
    $old['price']             = trim((string)($_POST['price'] ?? ''));
    $old['image']             = trim((string)($_POST['image'] ?? ''));
    $old['featured']          = isset($_POST['featured']) ? '1' : '0';

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $errors['form'] = 'Your session expired. Please try again.';
    }

    if (mb_strlen($old['name']) < 2 || mb_strlen($old['name']) > 120) {
        $errors['name'] = 'Name must be between 2 and 120 characters.';
    }

    $catId = filter_var($old['category_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!$catId) {
        $errors['category_id'] = 'Please choose a category.';
    } else {
        $validCat = false;
        foreach ($categories as $c) {
            if ((int)$c['id'] === $catId) { $validCat = true; break; }
        }
        if (!$validCat) $errors['category_id'] = 'Invalid category.';
    }

    if (mb_strlen($old['short_description']) < 5 || mb_strlen($old['short_description']) > 255) {
        $errors['short_description'] = 'Short description must be 5–255 characters.';
    }

    if (mb_strlen($old['description']) < 10) {
        $errors['description'] = 'Please write a longer description (min. 10 characters).';
    }

    $price = str_replace(',', '.', $old['price']);
    if (!is_numeric($price) || (float)$price < 0 || (float)$price > 9999.99) {
        $errors['price'] = 'Enter a valid price (0 – 9999.99).';
    }

    if ($old['image'] === '' || mb_strlen($old['image']) > 255) {
        $errors['image'] = 'Please provide an image path (e.g. images/dishes/dish-1.svg).';
    }

    if ($action === 'update' && !$editId) {
        $errors['form'] = 'Missing dish ID for update.';
    }

    if (!$errors) {
        try {
            if ($action === 'add') {
                $stmt = db()->prepare(
                    'INSERT INTO dishes (category_id, name, short_description, description, price, image, featured)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $catId,
                    $old['name'],
                    $old['short_description'],
                    $old['description'],
                    number_format((float)$price, 2, '.', ''),
                    $old['image'],
                    (int)$old['featured'],
                ]);
                $success = 'Dish “' . $old['name'] . '” added successfully.';
            } else {
                $stmt = db()->prepare(
                    'UPDATE dishes
                     SET category_id = ?, name = ?, short_description = ?, description = ?, price = ?, image = ?, featured = ?
                     WHERE id = ?'
                );
                $stmt->execute([
                    $catId,
                    $old['name'],
                    $old['short_description'],
                    $old['description'],
                    number_format((float)$price, 2, '.', ''),
                    $old['image'],
                    (int)$old['featured'],
                    $editId,
                ]);
                $success = 'Dish “' . $old['name'] . '” updated successfully.';
            }

            // reset form after successful save
            $old = [
                'name'              => '',
                'category_id'       => '',
                'short_description' => '',
                'description'       => '',
                'price'             => '',
                'image'             => 'images/dishes/dish-1.svg',
                'featured'          => '0',
            ];
            $editing = null;
            if ($action === 'update') {
                header('Location: admin.php?ok=1');
                exit;
            }
        } catch (Throwable $t) {
            db_fail($t);
        }
    } else {
        if ($action === 'update' && $editId) {
            $editing = ['id' => $editId];
        }
    }
}

if (isset($_GET['ok'])) {
    $success = $success ?: 'Dish updated successfully.';
}

// ---------- LIST ----------
try {
    $dishes = db()->query(
        'SELECT d.id, d.name, d.price, d.image, d.featured, d.short_description, c.name AS category
         FROM dishes d
         JOIN categories c ON c.id = d.category_id
         ORDER BY c.sort_order, d.id'
    )->fetchAll();
} catch (Throwable $t) {
    db_fail($t);
}

$pageTitle = 'Admin – Manage Dishes';
$active    = 'admin';
require __DIR__ . '/includes/header.php';

$isEdit = (bool)$editing;
?>

<section class="page-hero">
  <p class="eyebrow">Back Office</p>
  <h1>Manage Dishes</h1>
  <p>Add, edit or remove dishes from the menu. Only administrators can access this page.</p>
</section>

<div class="container admin-page" style="padding: 40px 24px 80px;">

  <?php if ($success): ?>
    <div class="alert ok" role="status"><?= e($success) ?></div>
  <?php endif; ?>
  <?php if (isset($errors['form'])): ?>
    <div class="alert err" role="alert"><?= e($errors['form']) ?></div>
  <?php endif; ?>

  <!-- ========== ADD / EDIT FORM ========== -->
  <div class="form-card" style="margin-bottom: 50px;" id="dish-form">
    <h2 style="margin-bottom: 1.2rem;">
      <?= $isEdit ? 'Edit dish' : 'Add a new dish' ?>
      <?php if ($isEdit): ?>
        <a href="admin.php" class="btn btn-sm btn-navy" style="float:right;margin-top:-4px;">Cancel edit</a>
      <?php endif; ?>
    </h2>

    <form method="post" action="admin.php<?= $isEdit ? '?edit=' . (int)$editing['id'] : '' ?>" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add' ?>">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
      <?php endif; ?>

      <div class="row2">
        <div class="field<?= isset($errors['name']) ? ' bad' : '' ?>">
          <label for="name">Dish name</label>
          <input id="name" name="name" type="text" value="<?= e($old['name']) ?>" required maxlength="120">
          <small class="msg"><?= e($errors['name'] ?? '') ?></small>
        </div>
        <div class="field<?= isset($errors['category_id']) ? ' bad' : '' ?>">
          <label for="category_id">Category</label>
          <select id="category_id" name="category_id" required>
            <option value="">— Choose —</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= (int)$c['id'] ?>"<?= $old['category_id'] == $c['id'] ? ' selected' : '' ?>>
                <?= e($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="msg"><?= e($errors['category_id'] ?? '') ?></small>
        </div>
      </div>

      <div class="field<?= isset($errors['short_description']) ? ' bad' : '' ?>">
        <label for="short_description">Short description (shown on menu cards)</label>
        <input id="short_description" name="short_description" type="text" value="<?= e($old['short_description']) ?>" required maxlength="255">
        <small class="msg"><?= e($errors['short_description'] ?? '') ?></small>
      </div>

      <div class="field<?= isset($errors['description']) ? ' bad' : '' ?>">
        <label for="description">Full description</label>
        <textarea id="description" name="description" rows="4" required><?= e($old['description']) ?></textarea>
        <small class="msg"><?= e($errors['description'] ?? '') ?></small>
      </div>

      <div class="row2">
        <div class="field<?= isset($errors['price']) ? ' bad' : '' ?>">
          <label for="price">Price (€)</label>
          <input id="price" name="price" type="text" value="<?= e($old['price']) ?>" placeholder="12.50" required>
          <small class="msg"><?= e($errors['price'] ?? '') ?></small>
        </div>
        <div class="field<?= isset($errors['image']) ? ' bad' : '' ?>">
          <label for="image">Image path</label>
          <input id="image" name="image" type="text" value="<?= e($old['image']) ?>" placeholder="images/dishes/dish-1.svg" required>
          <small class="msg"><?= e($errors['image'] ?? '') ?></small>
        </div>
      </div>

      <div class="field" style="margin-top: 8px;">
        <label style="display:flex;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:.95rem;">
          <input type="checkbox" name="featured" value="1"<?= $old['featured'] === '1' ? ' checked' : '' ?>>
          Featured dish (shown on the homepage)
        </label>
      </div>

      <button class="btn btn-gold" type="submit" style="margin-top: 18px;">
        <?= $isEdit ? 'Save changes' : 'Add dish' ?>
      </button>
    </form>
  </div>

  <!-- ========== DISHES LIST ========== -->
  <h2 style="margin-bottom: 1.2rem;">Current menu (<?= count($dishes) ?> dishes)</h2>

  <?php if (!$dishes): ?>
    <p>No dishes yet. Add the first one above.</p>
  <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dishes as $d): ?>
            <tr>
              <td><?= (int)$d['id'] ?></td>
              <td>
                <img src="<?= e($d['image']) ?>" alt="" width="56" height="42" style="object-fit:cover;border-radius:4px;background:#eee;">
              </td>
              <td>
                <strong><?= e($d['name']) ?></strong><br>
                <small style="opacity:.7;"><?= e(mb_strimwidth($d['short_description'], 0, 60, '…')) ?></small>
              </td>
              <td><?= e($d['category']) ?></td>
              <td><?= euro($d['price']) ?></td>
              <td><?= $d['featured'] ? '★ Yes' : '—' ?></td>
              <td style="white-space:nowrap;">
                <a href="admin.php?edit=<?= (int)$d['id'] ?>#dish-form" class="btn btn-sm btn-navy">Edit</a>
                <form method="post" action="admin.php" onsubmit="return confirm('Delete “<?= e(addslashes($d['name'])) ?>”? This cannot be undone.');" style="display:inline;">
                  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</div>

<style>
.admin-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: var(--shadow);
  font-size: 0.92rem;
}
.admin-table th,
.admin-table td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #e8e0c8;
  vertical-align: middle;
}
.admin-table th {
  background: var(--navy);
  color: #f3ecd9;
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  font-weight: 500;
}
.admin-table tr:last-child td { border-bottom: 0; }
.admin-table tr:hover td { background: #faf6ec; }
.btn-danger {
  background: #b3392e;
  color: #fff;
  border: 1px solid #b3392e;
  padding: 8px 14px;
  font-size: 0.7rem;
}
.btn-danger:hover {
  background: #8c2a22;
  border-color: #8c2a22;
  color: #fff;
  transform: none;
}
.admin-page .form-card textarea,
.admin-page .form-card select {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #d6cdb4;
  background: var(--cream);
  font-family: var(--sans);
  font-size: 0.95rem;
  border-radius: 3px;
}
.admin-page .form-card textarea { resize: vertical; }
</style>

<?php require __DIR__ . '/includes/footer.php'; ?>
