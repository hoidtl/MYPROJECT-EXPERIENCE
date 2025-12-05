-- Tạo bảng reviews để lưu đánh giá sản phẩm
-- Chạy SQL này trong phpMyAdmin

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `user_name` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `product_id` VARCHAR(50) NOT NULL COMMENT 'masp từ tblsanpham',
  `rating` INT NOT NULL DEFAULT 5 COMMENT 'Số sao từ 1-5',
  `comment` TEXT NULL COMMENT 'Nội dung bình luận',
  `image` VARCHAR(255) NULL COMMENT 'Ảnh đánh giá',
  `status` VARCHAR(20) DEFAULT 'pending' COMMENT 'pending, approved, rejected',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  INDEX `idx_product` (`product_id`),
  INDEX `idx_user` (`user_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
