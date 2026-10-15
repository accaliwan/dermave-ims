// DermAve IMS - products page behavior

const searchInput = document.getElementById('productSearch');
const statusFilter = document.getElementById('statusFilter');
const rows = document.querySelectorAll('#productsTable tbody tr');
const noResults = document.getElementById('noResults');

// Show only the rows that match the search text and the chosen status
function applyFilters() {
  const text = searchInput.value.trim().toLowerCase();
  const status = statusFilter.value;
  let visible = 0;

  rows.forEach((row) => {
    const matchesText = row.textContent.toLowerCase().includes(text);
    const matchesStatus = status === 'all' || row.dataset.status === status;
    const show = matchesText && matchesStatus;
    row.hidden = !show;
    if (show) visible++;
  });

  noResults.hidden = visible > 0;
}

searchInput.addEventListener('input', applyFilters);
statusFilter.addEventListener('change', applyFilters);

// TODO: add "Add / Edit / Delete product" actions, and load rows from the database
