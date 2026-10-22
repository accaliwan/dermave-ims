/**
 * DermAve IMS - Directory page behaviour
 * - Search
 * - Clickable column headers (sort + filter menus)
 * - Row selection → enable Edit / Archive
 * - Lightweight Add / Edit modal (client-side for demo)
 */

(function () {
  const table = document.getElementById('directoryTable');
  if (!table) return;

  const tbody = table.querySelector('tbody');
  const searchInput = document.getElementById('tableSearch');
  const noResults = document.getElementById('noResults');
  const checkAll = document.getElementById('checkAll');
  const btnEdit = document.getElementById('btnEdit');
  const btnArchive = document.getElementById('btnArchive');
  const btnAdd = document.getElementById('btnAdd');
  const modal = document.getElementById('itemModal');
  const form = document.getElementById('itemForm');
  const modalTitle = document.getElementById('modalTitle');

  // Active filters: { colName: value | null }
  const filters = {};
  // Active sort: { col, dir: 'asc'|'desc' }
  let sort = { col: null, dir: 'asc' };

  /* ---------- helpers ---------- */
  function visibleRows() {
    return Array.from(tbody.querySelectorAll('tr')).filter((r) => !r.hidden);
  }

  function selectedRows() {
    return visibleRows().filter((r) => r.querySelector('.row-check')?.checked);
  }

  function updateActionButtons() {
    const n = selectedRows().length;
    btnEdit.disabled = n !== 1;
    btnArchive.disabled = n === 0;
  }

  function applyAll() {
    const text = (searchInput?.value || '').trim().toLowerCase();
    let visible = 0;

    tbody.querySelectorAll('tr').forEach((row) => {
      // text search across whole row
      const matchesText = !text || row.textContent.toLowerCase().includes(text);

      // column filters
      let matchesFilters = true;
      for (const [col, val] of Object.entries(filters)) {
        if (val == null) continue;
        if ((row.dataset[col] || '') !== val) {
          matchesFilters = false;
          break;
        }
      }

      const show = matchesText && matchesFilters;
      row.hidden = !show;
      if (show) visible++;
    });

    // sort
    if (sort.col) {
      const rows = Array.from(tbody.querySelectorAll('tr'));
      const col = sort.col;
      const dir = sort.dir === 'asc' ? 1 : -1;
      const th = table.querySelector(`th[data-col="${col}"]`);
      const type = th?.dataset.type || 'text';

      rows.sort((a, b) => {
        let va = a.dataset[col] ?? '';
        let vb = b.dataset[col] ?? '';
        if (type === 'number') {
          return (Number(va) - Number(vb)) * dir;
        }
        if (type === 'date') {
          const da = va ? new Date(va).getTime() : 0;
          const db = vb ? new Date(vb).getTime() : 0;
          return (da - db) * dir;
        }
        return String(va).localeCompare(String(vb), undefined, { sensitivity: 'base' }) * dir;
      });
      rows.forEach((r) => tbody.appendChild(r));
    }

    if (noResults) noResults.hidden = visible > 0;
    updateActionButtons();
  }

  /* ---------- search ---------- */
  searchInput?.addEventListener('input', applyAll);

  /* ---------- checkbox select ---------- */
  checkAll?.addEventListener('change', () => {
    visibleRows().forEach((r) => {
      const cb = r.querySelector('.row-check');
      if (cb) cb.checked = checkAll.checked;
    });
    updateActionButtons();
  });

  tbody.addEventListener('change', (e) => {
    if (e.target.classList.contains('row-check')) updateActionButtons();
  });

  /* ---------- column header menus ---------- */
  table.querySelectorAll('th.sortable, th.filterable').forEach((th) => {
    const menu = th.querySelector('.th-menu');
    if (!menu) return;

    th.addEventListener('click', (e) => {
      // ignore clicks inside the open menu itself (handled below)
      if (e.target.closest('.th-menu')) return;

      // close other menus
      table.querySelectorAll('.th-menu').forEach((m) => {
        if (m !== menu) m.hidden = true;
      });
      menu.hidden = !menu.hidden;
    });

    menu.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-action]');
      if (!btn) return;
      e.stopPropagation();

      const action = btn.dataset.action;
      const col = th.dataset.col;

      if (action === 'sort-asc' || action === 'sort-desc') {
        sort = { col, dir: action === 'sort-asc' ? 'asc' : 'desc' };
        // visual active state on sort buttons
        menu.querySelectorAll('[data-action^="sort"]').forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
      } else if (action === 'filter-all') {
        filters[col] = null;
        menu.querySelectorAll('[data-action="filter"], [data-action="filter-all"]').forEach((b) =>
          b.classList.remove('active')
        );
        btn.classList.add('active');
      } else if (action === 'filter') {
        filters[col] = btn.dataset.value;
        menu.querySelectorAll('[data-action="filter"], [data-action="filter-all"]').forEach((b) =>
          b.classList.remove('active')
        );
        btn.classList.add('active');
      }

      menu.hidden = true;
      applyAll();
    });
  });

  // close menus when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('th.sortable, th.filterable')) {
      table.querySelectorAll('.th-menu').forEach((m) => (m.hidden = true));
    }
  });

  /* ---------- modal helpers ---------- */
  function openModal(mode, row) {
    modal.hidden = false;
    modalTitle.textContent = mode === 'edit' ? 'Edit item' : 'Add item';
    form.reset();
    document.getElementById('f_id').value = '';

    if (mode === 'edit' && row) {
      document.getElementById('f_id').value = row.dataset.id || '';
      document.getElementById('f_item_type').value = row.dataset.item_type || 'product';
      document.getElementById('f_name').value = row.dataset.name || '';
      document.getElementById('f_category').value = row.dataset.category || '';
      document.getElementById('f_dosage').value = row.dataset.dosage || '';
      document.getElementById('f_quantity').value = row.dataset.quantity || '0';
      // unit / dates not fully stored in data-* for brevity; left blank or extend as needed
    }
  }

  function closeModal() {
    modal.hidden = true;
  }

  modal?.querySelectorAll('[data-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  btnAdd?.addEventListener('click', () => openModal('add'));

  btnEdit?.addEventListener('click', () => {
    const rows = selectedRows();
    if (rows.length === 1) openModal('edit', rows[0]);
  });

  btnArchive?.addEventListener('click', () => {
    const rows = selectedRows();
    if (!rows.length) return;
    if (!confirm(`Archive ${rows.length} selected item(s)?`)) return;

    rows.forEach((row) => {
      // Client-side demo: mark as archived
      row.dataset.status = 'archived';
      const badge = row.querySelector('.badge');
      if (badge) {
        badge.textContent = 'Archived';
        badge.className = 'badge badge--expired';
      }
      row.querySelector('.row-check').checked = false;
    });
    checkAll.checked = false;
    applyAll();
    // TODO: POST to a PHP endpoint that sets status = 'archived' in directory_items
  });

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    // Demo only – in production POST to save endpoint then reload
    alert('Form ready. Connect this submit handler to a PHP API that writes to directory_items.');
    closeModal();
  });

  // initial
  applyAll();
})();
