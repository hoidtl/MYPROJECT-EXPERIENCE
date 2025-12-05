-- Thêm cột user_email vào bảng orders nếu chưa có
ALTER TABLE orders ADD COLUMN IF NOT EXISTS user_email VARCHAR(255) NULL AFTER user_id;

-- Cập nhật email cho các đơn hàng cũ từ bảng tbluser
UPDATE orders o
JOIN tbluser u ON o.user_id = u.user_id
SET o.user_email = u.email
WHERE o.user_email IS NULL;
