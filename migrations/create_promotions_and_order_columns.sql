-- Create promotions table
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(100) NOT NULL UNIQUE,
  `type` VARCHAR(20) NOT NULL COMMENT 'percent|fixed',
  `value` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `min_order_amount` DECIMAL(12,2) DEFAULT 0,
  `start_date` DATETIME NULL,
  `end_date` DATETIME NULL,
  `status` VARCHAR(20) DEFAULT 'active',
  `usage_limit` INT DEFAULT NULL,
  `usage_count` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add columns to orders table (adjust names if your table differs)
ALTER TABLE `orders`
  ADD COLUMN IF NOT EXISTS `coupon_code` VARCHAR(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `discount_amount` DECIMAL(12,2) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `received_amount` DECIMAL(12,2) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `lack_amount` DECIMAL(12,2) DEFAULT 0;

-- Note: Some MySQL versions do not support IF NOT EXISTS for ADD COLUMN; if your server errors, run ALTER statements individually and check existing schema.
