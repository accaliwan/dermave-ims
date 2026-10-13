// DermAve IMS - dashboard behavior

// TODO: fetch live counts from a PHP endpoint (e.g. api/dashboard_stats.php)
// and update the cards, for example:
//
// fetch('api/dashboard_stats.php')
//   .then((res) => res.json())
//   .then((data) => {
//     document.getElementById('lowStock').textContent = data.low_stock;
//     document.getElementById('expiringSoon').textContent = data.expiring_soon;
//     document.getElementById('expired').textContent = data.expired;
//     document.getElementById('maintenanceDue').textContent = data.maintenance_due;
//   });

// Placeholder nav links should not jump to the top of the page
document.querySelectorAll('#mainNav a[href="#"]').forEach((link) => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    alert('This page is not built yet.');
  });
});
