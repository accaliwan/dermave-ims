<?php
/**
 * Shared footer. Optionally set $pageScript (e.g. 'products.js') before including.
 */
?>
  <script src="assets/js/main.js"></script>
<?php if (!empty($pageScript)): ?>
  <script src="assets/js/<?= htmlspecialchars($pageScript) ?>"></script>
<?php endif; ?>
</body>
</html>
