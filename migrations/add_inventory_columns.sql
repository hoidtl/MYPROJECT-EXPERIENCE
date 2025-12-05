-- Thêm cột quản lý kho hàng vào bảng tbl_sanpham_size
-- Chạy SQL này trong phpMyAdmin

ALTER TABLE `tbl_sanpham_size`
  ADD COLUMN IF NOT EXISTS `stock_quantity` INT DEFAULT 100 COMMENT 'Số lượng tồn kho',
  ADD COLUMN IF NOT EXISTS `sold_quantity` INT DEFAULT 0 COMMENT 'Số lượng đã bán';

-- Nếu MySQL báo lỗi "IF NOT EXISTS", chạy từng lệnh riêng:
-- ALTER TABLE `tbl_sanpham_size` ADD COLUMN `stock_quantity` INT DEFAULT 100;
-- ALTER TABLE `tbl_sanpham_size` ADD COLUMN `sold_quantity` INT DEFAULT 0;
