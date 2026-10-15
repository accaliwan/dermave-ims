<?php
/**
 * DermAve IMS - placeholder product data + expiry status logic.
 *
 * TODO: replace get_products() with a MySQL query once the database is ready.
 *       product_status() can stay as it is.
 */

/**
 * Work out a product's status from its expiry date.
 *
 * @return array [css class, label, days left]
 *   expired -> already past its expiry date
 *   soon    -> expires within 30 days (label shows the days left)
 *   ok      -> more than 30 days left
 */
function product_status(string $expiry): array
{
    $today = new DateTimeImmutable('today');
    $exp   = new DateTimeImmutable($expiry);
    $days  = (int) $today->diff($exp)->format('%r%a');

    if ($days < 0) {
        return ['expired', 'Expired', $days];
    }
    if ($days <= 30) {
        $label = $days === 0 ? 'Today' : $days . ' day' . ($days === 1 ? '' : 's');
        return ['soon', $label, $days];
    }
    return ['ok', 'OK', $days];
}

/**
 * Placeholder rows based on the Products page sketch.
 * Expiry dates are relative to today so every status can be seen in the demo.
 */
function get_products(): array
{
    return [
        ['item' => 'Vit-C', 'batch' => 'LT-123', 'expiry' => date('Y-m-d', strtotime('-7 days')),  'qty' => 2],
        ['item' => 'IV Bag', 'batch' => 'LT-371', 'expiry' => date('Y-m-d', strtotime('+3 days')),  'qty' => 10],
        ['item' => 'Gluta',  'batch' => 'LT-303', 'expiry' => date('Y-m-d', strtotime('+60 days')), 'qty' => 20],
    ];
}
