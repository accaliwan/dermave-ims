<?php
/**
 * DermAve IMS - Directory page
 * Unified view of products, machines and supplies.
 * Column headers are clickable for sort / filter options.
 */

$pageTitle  = 'Directory';
$activePage = 'directory';
$pageScript = 'directory.js';

require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';

// Include archived so the Archive button can toggle visibility later
$items = get_directory_items(['include_archived' => true]);

// Distinct values for filter menus (from live data or sample)
$typeOptions     = get_directory_distinct('item_type');
$categoryOptions = get_directory_distinct('category');
$statusOptions   = get_directory_distinct('status');

// Helper to format dates safely
function fmt_date(?string $d): string
{
    if (!$d) return '—';
    $ts = strtotime($d);
    return $ts ? date('M d, Y', $ts) : '—';
}
?>

  <main class="content">
    <h1>Directory</h1>
    <p class="subtitle">All clinic items in one place &mdash; products, machines &amp; supplies</p>

    <div class="toolbar toolbar--directory">
      <input type="search" id="tableSearch" placeholder="Search name, brand, category, batch…">

      <div class="toolbar__actions">
        <button type="button" class="btn btn--primary" id="btnAdd">Add</button>
        <button type="button" class="btn btn--ghost" id="btnEdit" disabled>Edit</button>
        <button type="button" class="btn btn--ghost" id="btnArchive" disabled>Archive</button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="table table--fit js-directory-table" id="directoryTable">
        <thead>
          <tr>
            <th class="col-check"><input type="checkbox" id="checkAll" title="Select all visible"></th>
            <th class="sortable filterable" data-col="item_type" data-type="enum">
              Type <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Sort A → Z</button>
                <button type="button" data-action="sort-desc">Sort Z → A</button>
                <hr>
                <button type="button" data-action="filter-all" class="active">All types</button>
                <?php foreach ($typeOptions as $opt): ?>
                  <button type="button" data-action="filter" data-value="<?= htmlspecialchars($opt) ?>">
                    <?= htmlspecialchars(ucfirst($opt)) ?>
                  </button>
                <?php endforeach; ?>
              </div>
            </th>
            <th class="sortable filterable" data-col="name" data-type="text">
              Item Name <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Sort A → Z</button>
                <button type="button" data-action="sort-desc">Sort Z → A</button>
              </div>
            </th>
            <th class="sortable filterable" data-col="category" data-type="enum">
              Category <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Sort A → Z</button>
                <button type="button" data-action="sort-desc">Sort Z → A</button>
                <hr>
                <button type="button" data-action="filter-all" class="active">All categories</button>
                <?php foreach ($categoryOptions as $opt): ?>
                  <button type="button" data-action="filter" data-value="<?= htmlspecialchars($opt) ?>">
                    <?= htmlspecialchars($opt) ?>
                  </button>
                <?php endforeach; ?>
              </div>
            </th>
            <th class="sortable" data-col="dosage" data-type="text">
              Dosage / Spec <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Sort A → Z</button>
                <button type="button" data-action="sort-desc">Sort Z → A</button>
              </div>
            </th>
            <th class="sortable num" data-col="quantity" data-type="number">
              Qty <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Sort low → high</button>
                <button type="button" data-action="sort-desc">Sort high → low</button>
              </div>
            </th>
            <th class="sortable" data-col="date_key" data-type="date">
              Date <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Soonest first</button>
                <button type="button" data-action="sort-desc">Latest first</button>
              </div>
            </th>
            <th class="sortable filterable" data-col="status" data-type="enum">
              Status <span class="th-icon">▾</span>
              <div class="th-menu" hidden>
                <button type="button" data-action="sort-asc">Sort A → Z</button>
                <button type="button" data-action="sort-desc">Sort Z → A</button>
                <hr>
                <button type="button" data-action="filter-all" class="active">All statuses</button>
                <?php foreach ($statusOptions as $opt): ?>
                  <button type="button" data-action="filter" data-value="<?= htmlspecialchars($opt) ?>">
                    <?= htmlspecialchars(ucfirst($opt)) ?>
                  </button>
                <?php endforeach; ?>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $row): ?>
            <?php
              // Prefer expiry for products, next maintenance for machines
              $dateKey = $row['expiration_date'] ?: $row['next_maintenance'] ?: '';
              $dateLabel = $row['expiration_date']
                ? 'Exp ' . fmt_date($row['expiration_date'])
                : ($row['next_maintenance'] ? 'Maint ' . fmt_date($row['next_maintenance']) : '—');
              $qtyLabel = (int) $row['quantity'] . ($row['unit'] ? ' ' . $row['unit'] : '');
            ?>
            <tr
              data-id="<?= (int) $row['id'] ?>"
              data-item_type="<?= htmlspecialchars($row['item_type']) ?>"
              data-name="<?= htmlspecialchars($row['name']) ?>"
              data-category="<?= htmlspecialchars($row['category'] ?? '') ?>"
              data-dosage="<?= htmlspecialchars($row['dosage'] ?? '') ?>"
              data-quantity="<?= (int) $row['quantity'] ?>"
              data-date_key="<?= htmlspecialchars($dateKey) ?>"
              data-status="<?= htmlspecialchars($row['status']) ?>"
            >
              <td class="col-check"><input type="checkbox" class="row-check"></td>
              <td><span class="type-pill type-pill--<?= htmlspecialchars($row['item_type']) ?>"><?= htmlspecialchars(ucfirst($row['item_type'])) ?></span></td>
              <td>
                <strong><?= htmlspecialchars($row['name']) ?></strong>
                <?php if (!empty($row['batch_number'])): ?>
                  <div class="muted small">Batch <?= htmlspecialchars($row['batch_number']) ?></div>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['category'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['dosage'] ?? '—') ?></td>
              <td class="num"><?= htmlspecialchars($qtyLabel) ?></td>
              <td><?= htmlspecialchars($dateLabel) ?></td>
              <td><span class="badge badge--<?= $row['status'] === 'archived' ? 'expired' : 'ok' ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="empty" id="noResults" hidden>No items match the current filters.</p>
    </div>

    <!-- Simple modal for Add / Edit (UI only – wire to backend later) -->
    <div class="modal" id="itemModal" hidden>
      <div class="modal__backdrop" data-close></div>
      <div class="modal__card">
        <h2 id="modalTitle">Add item</h2>
        <form id="itemForm">
          <input type="hidden" name="id" id="f_id">
          <label>Type
            <select name="item_type" id="f_item_type" required>
              <option value="product">Product</option>
              <option value="machine">Machine</option>
              <option value="supply">Supply</option>
            </select>
          </label>
          <label>Name
            <input type="text" name="name" id="f_name" required>
          </label>
          <label>Category
            <input type="text" name="category" id="f_category">
          </label>
          <label>Dosage / Spec
            <input type="text" name="dosage" id="f_dosage">
          </label>
          <label>Quantity
            <input type="number" name="quantity" id="f_quantity" min="0" value="0">
          </label>
          <label>Unit
            <input type="text" name="unit" id="f_unit" placeholder="pcs, vial, bag…">
          </label>
          <label>Expiration date (products)
            <input type="date" name="expiration_date" id="f_expiration">
          </label>
          <label>Next maintenance (machines)
            <input type="date" name="next_maintenance" id="f_next_maint">
          </label>
          <div class="form-actions">
            <button type="button" class="btn btn--ghost" data-close>Cancel</button>
            <button type="submit" class="btn btn--primary">Save</button>
          </div>
        </form>
        <p class="muted small" style="margin-top:12px">
          Note: Add / Edit currently updates the on-screen table only.
          Connect the form to a PHP endpoint that inserts/updates
          <code>directory_items</code> once the database is live.
        </p>
      </div>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
