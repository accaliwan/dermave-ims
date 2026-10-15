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
        ['item' => 'Vitamin C Ampoules', 'batch' => 'LT-123', 'expiry' => date('Y-m-d', strtotime('+6 days')),  'qty' => 67],
        ['item' => 'B-Complex Injection Vials', 'batch' => 'LT-371', 'expiry' => date('Y-m-d', strtotime('+10 days')),  'qty' => 21],
        ['item' => 'Methylcobalamin',  'batch' => 'BT-223', 'expiry' => date('Y-m-d', strtotime('+80 days')), 'qty' => 20],
        ['item' => 'Cyanocobalamin', 'batch' => 'BT-4123', 'expiry' => date('Y-m-d', strtotime('+1 days')),  'qty' => 12],
        ['item' => 'EDTA Chelation Agent', 'batch' => 'AG-37111', 'expiry' => date('Y-m-d', strtotime('-2 days')),  'qty' => 29],
        ['item' => 'Zinc Additives',  'batch' => 'AD-30331', 'expiry' => date('Y-m-d', strtotime('+60 days')), 'qty' => 19],
        ['item' => 'Selenium Additives', 'batch' => 'AD-12123', 'expiry' => date('Y-m-d', strtotime('-7 days')),  'qty' => 10],
    ];
}

/**
 * Machine maintenance status. Same rules as product_status(), but a past
 * date is "Overdue" instead of "Expired".
 *
 * @return array [css class, label, days left]
 */
function machine_status(string $date): array
{
    [$class, $label, $days] = product_status($date);
    return $class === 'expired' ? ['overdue', 'Overdue', $days] : [$class, $label, $days];
}

/**
 * Placeholder rows based on the Machines page sketch.
 * 'date' is the next maintenance date, relative to today for the demo.
 */
function get_machines(): array
{
    return [
        ['name' => 'LED Face Mask',          'date' => date('Y-m-d', strtotime('+3 days'))],
        ['name' => 'Plasma Fibroblast Pen',  'date' => date('Y-m-d', strtotime('-14 days'))],
        ['name' => 'Diode Laser',            'date' => date('Y-m-d', strtotime('+45 days'))],
    ];
}

/**
 * Stock level for a supply.
 *
 * @return string 'out' (0 left), 'low' (at or below the minimum) or 'ok'
 */
function supply_status(int $stock, int $min): string
{
    if ($stock <= 0) {
        return 'out';
    }
    return $stock <= $min ? 'low' : 'ok';
}

/**
 * Placeholder rows based on the Supplies page sketch.
 * 'min' is the minimum level before an item counts as low stock.
 */
function get_supplies(): array
{
    return [
        ['item' => 'Hand Sanitizer',    'stock' => 5,  'unit' => 'pcs',   'min' => 10],
        ['item' => 'Face Masks',        'stock' => 3,  'unit' => 'packs', 'min' => 5],
        ['item' => 'Disposable Gloves', 'stock' => 0,  'unit' => 'boxes', 'min' => 5],
        ['item' => 'Cotton Pads',       'stock' => 40, 'unit' => 'packs', 'min' => 10],
        ['item' => 'Alcohol Swabs',     'stock' => 25, 'unit' => 'boxes', 'min' => 10],
    ];
}
