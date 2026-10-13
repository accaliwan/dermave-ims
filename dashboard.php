<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/config.php';

// TODO: replace these placeholder numbers with real counts from the database.
$stats = [
    'low_stock'       => 3,
    'expiring_soon'   => 5,
    'expiring_30'     => 3,
    'expiring_60'     => 2,
    'expiring_90'     => 0,
    'expired'         => 1,
    'maintenance_due' => 2,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | DermAve IMS</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <header class="topbar">
    <div class="brand brand--small">
      <div class="brand__name">DERM<span>AVE</span> IMS</div>
    </div>

    <!-- TODO: point these links to the real pages once they are built -->
    <nav class="nav" id="mainNav">
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="#">Products</a>
      <a href="#">Machines</a>
      <a href="#">Supplies</a>
      <a href="#">Barcode</a>
    </nav>

    <div class="topbar__user">
      <?= htmlspecialchars($_SESSION['user']) ?>
      <a href="logout.php">Logout</a>
    </div>
  </header>

  <main class="content">
    <h1>Dashboard</h1>
    <p class="subtitle">Inventory overview &mdash; placeholder data</p>

    <section class="cards">
      <article class="card">
        <div class="card__label">Low Stock</div>
        <div class="card__value" id="lowStock"><?= (int)$stats['low_stock'] ?></div>
        <div class="card__note">Items below the minimum level</div>
      </article>

      <article class="card">
        <div class="card__label">Expiring Soon</div>
        <div class="card__value" id="expiringSoon"><?= (int)$stats['expiring_soon'] ?></div>
        <div class="breakdown">
          <span><strong><?= (int)$stats['expiring_30'] ?></strong>30 days</span>
          <span><strong><?= (int)$stats['expiring_60'] ?></strong>60 days</span>
          <span><strong><?= (int)$stats['expiring_90'] ?></strong>90 days</span>
        </div>
      </article>

      <article class="card">
        <div class="card__label">Expired</div>
        <div class="card__value" id="expired"><?= (int)$stats['expired'] ?></div>
        <div class="card__note">Items past their expiry date</div>
      </article>

      <article class="card">
        <div class="card__label">Maintenance Due</div>
        <div class="card__value" id="maintenanceDue"><?= (int)$stats['maintenance_due'] ?></div>
        <div class="card__note">Machines needing servicing</div>
      </article>
    </section>
  </main>

  <script src="assets/js/dashboard.js"></script>
</body>
</html>
