<?php
$pageTitle  = 'Products';
$activePage = 'products';
$pageScript = 'products.js';

require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';

$products = get_products();
?>

  <main class="content">
    <h1>Products</h1>
    <p class="subtitle">Items, batches and expiry dates &mdash; placeholder data</p>

    <div class="toolbar">
      <input type="search" id="productSearch" placeholder="Search item or batch...">
      <select id="statusFilter" aria-label="Filter by status">
        <option value="all">All statuses</option>
        <option value="expired">Expired</option>
        <option value="soon">Expiring soon</option>
        <option value="ok">OK</option>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table" id="productsTable">
        <thead>
          <tr>
            <th>Item</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th class="num">Qty</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <?php [$statusClass, $statusLabel] = product_status($p['expiry']); ?>
            <tr data-status="<?= $statusClass ?>">
              <td><?= htmlspecialchars($p['item']) ?></td>
              <td><?= htmlspecialchars($p['batch']) ?></td>
              <td><?= date('M d, Y', strtotime($p['expiry'])) ?></td>
              <td class="num"><?= (int) $p['qty'] ?></td>
              <td><span class="badge badge--<?= $statusClass ?>"><?= $statusLabel ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="empty" id="noResults" hidden>No products found.</p>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
