-- ============================================================
-- DermAve IMS - Unified Directory table
-- Run this in phpMyAdmin (or mysql CLI) after creating the
-- database "dermave_ims" (or change the USE statement).
-- ============================================================

CREATE DATABASE IF NOT EXISTS dermave_ims
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dermave_ims;

-- ----------------------------------------------------------
-- Main table used by the Directory page.
-- products.php / machines.php / supplies.php filter by item_type.
-- ----------------------------------------------------------
DROP TABLE IF EXISTS directory_items;

CREATE TABLE directory_items (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_type           ENUM('product', 'machine', 'supply') NOT NULL,
  name                VARCHAR(255) NOT NULL,
  brand               VARCHAR(120) DEFAULT NULL,
  category            VARCHAR(150) DEFAULT NULL,   -- e.g. Machines, IV Fluids, Facial Equipment
  subcategory         VARCHAR(150) DEFAULT NULL,   -- finer grouping from the PDF
  description         TEXT DEFAULT NULL,
  dosage              VARCHAR(120) DEFAULT NULL,   -- strength / concentration (products)
  unit                VARCHAR(40)  DEFAULT NULL,   -- pcs, mL, vials, boxes, packs
  quantity            INT          NOT NULL DEFAULT 0,
  min_stock           INT          DEFAULT NULL,   -- threshold for "low" (mainly supplies)
  price               DECIMAL(12,2) DEFAULT NULL,
  batch_number        VARCHAR(80)  DEFAULT NULL,
  manufacturing_date  DATE         DEFAULT NULL,
  expiration_date     DATE         DEFAULT NULL,   -- products / some supplies
  last_maintenance    DATE         DEFAULT NULL,   -- machines
  next_maintenance    DATE         DEFAULT NULL,   -- machines
  status              ENUM('active', 'archived') NOT NULL DEFAULT 'active',
  notes               TEXT DEFAULT NULL,
  created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_item_type (item_type),
  INDEX idx_status (status),
  INDEX idx_name (name),
  INDEX idx_category (category),
  INDEX idx_expiration (expiration_date),
  INDEX idx_next_maint (next_maintenance)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- Sample data (5 machines, 5 products, 5 supplies)
-- Drawn from "DermAve Combined Item List.pdf"
-- ============================================================

-- ----- MACHINES (5) -----
INSERT INTO directory_items
  (item_type, name, brand, category, subcategory, description, unit, quantity, last_maintenance, next_maintenance, status, notes)
VALUES
  ('machine', 'Dermaplaning Handpiece', NULL, 'Machines', 'Facial / Skin', 'Handheld dermaplaning device', 'unit', 1,
   CURDATE() - INTERVAL 20 DAY, CURDATE() + INTERVAL 40 DAY, 'active', 'Routine blade/head check'),
  ('machine', 'Plasma Fibroblast Pen', NULL, 'Machines', 'Facial / Skin', 'Plasma fibroblast treatment pen', 'unit', 1,
   CURDATE() - INTERVAL 45 DAY, CURDATE() - INTERVAL 10 DAY, 'active', 'Overdue for tip inspection'),
  ('machine', 'LED Face Mask / Dome', NULL, 'Machines', 'Facial / Light Therapy', 'LED face mask or dome unit', 'unit', 2,
   CURDATE() - INTERVAL 10 DAY, CURDATE() + INTERVAL 50 DAY, 'active', NULL),
  ('machine', 'HydraFacial Machine', NULL, 'Skin Care & Facial Treatments', 'Facial Machines', 'HydraFacial treatment system', 'unit', 1,
   CURDATE() - INTERVAL 5 DAY, CURDATE() + INTERVAL 25 DAY, 'active', NULL),
  ('machine', 'Cryolipolysis / CoolSculpting Machine', NULL, 'Body Contouring & Fat Reduction', 'Fat Reduction', 'Cryolipolysis body contouring device', 'unit', 1,
   CURDATE() - INTERVAL 30 DAY, CURDATE() + INTERVAL 60 DAY, 'active', 'Applicator seals checked');

-- ----- PRODUCTS (5) – injectables / IV / consumable products -----
INSERT INTO directory_items
  (item_type, name, brand, category, subcategory, description, dosage, unit, quantity, min_stock, price, batch_number, manufacturing_date, expiration_date, status, notes)
VALUES
  ('product', 'Glutathione Vials', NULL, 'IV Drip & Injection Consumables', 'Vitamins / Injectables',
   'Glutathione for IV push/drip (verify indication & authorization)', '600 mg', 'vial', 24, 10, 450.00,
   'GLU-2401', CURDATE() - INTERVAL 90 DAY, CURDATE() + INTERVAL 180 DAY, 'active', 'Also available 1.2 g / 5 g / 8 g'),
  ('product', 'Vitamin C (Ascorbic Acid) Ampoules', NULL, 'IV Drip & Injection Consumables', 'Vitamins / Injectables',
   'Ascorbic acid for IV / mesotherapy / topical', '500 mg', 'ampoule', 40, 15, 85.00,
   'VTC-118', CURDATE() - INTERVAL 60 DAY, CURDATE() + INTERVAL 120 DAY, 'active', 'Also 3 g strength'),
  ('product', 'Normal Saline (0.9% NaCl)', NULL, 'IV Drip & Injection Consumables', 'IV Fluids and Solutions',
   '0.9% sodium chloride IV solution', '500 mL', 'bag', 30, 12, 35.00,
   'NS-500-092', CURDATE() - INTERVAL 30 DAY, CURDATE() + INTERVAL 365 DAY, 'active', 'Also 100/250/1000 mL'),
  ('product', 'Hyaluronic Acid (Skin Booster / Filler)', NULL, 'Topical & Procedural Consumables', 'Injectable / Skin Rejuvenation',
   'HA for filler or skin booster / mesotherapy', '~20 mg/mL', 'syringe 1 mL', 18, 5, 2200.00,
   'HA-1ML-07', CURDATE() - INTERVAL 45 DAY, CURDATE() + INTERVAL 300 DAY, 'active', '0.5 / 1 / 2 mL sizes'),
  ('product', 'Botulinum Toxin Vials', NULL, 'Topical & Procedural Consumables', 'Injectable / Skin Rejuvenation',
   'Botulinum toxin (requires appropriate licensing)', '100 units', 'vial', 8, 3, 8500.00,
   'BTX-100-03', CURDATE() - INTERVAL 20 DAY, CURDATE() + INTERVAL 90 DAY, 'active', 'Also 50 / 200 unit vials');

-- ----- SUPPLIES (5) – procedural / clinic consumables -----
INSERT INTO directory_items
  (item_type, name, brand, category, subcategory, description, unit, quantity, min_stock, price, status, notes)
VALUES
  ('supply', 'Syringes (Assorted Sizes)', NULL, 'IV Administration Equipment', 'IV Admin Supplies',
   'Disposable sterile syringes – specify sizes on order', 'pcs', 120, 40, 8.50, 'active', 'Common sizes 1 mL / 3 mL / 5 mL / 10 mL'),
  ('supply', 'Sterile Needles (Assorted Sizes)', NULL, 'IV Administration Equipment', 'IV Admin Supplies',
   'Disposable sterile needles – specify gauges/lengths', 'pcs', 200, 50, 3.25, 'active', NULL),
  ('supply', 'Alcohol Swabs', NULL, 'IV Administration Equipment', 'IV Admin Supplies',
   'Single-use alcohol prep pads', 'box', 25, 10, 45.00, 'active', NULL),
  ('supply', 'IV Bags (empty / administration sets related)', NULL, 'IV Administration Equipment', 'IV Admin Supplies',
   'IV administration bags / related consumables', 'pcs', 40, 15, 28.00, 'active', 'Distinct from pre-filled fluid bags'),
  ('supply', 'Numbing Cream (Lidocaine 5%)', NULL, 'Anesthetics and Topical Preparations', 'Topical Anesthetics',
   'Topical lidocaine cream 4–5%', 'tube', 12, 4, 180.00, 'active', 'Also gel and EMLA available');


-- Optional: quick verification queries
-- SELECT item_type, COUNT(*) FROM directory_items GROUP BY item_type;
-- SELECT id, item_type, name, quantity, status FROM directory_items ORDER BY item_type, name;
