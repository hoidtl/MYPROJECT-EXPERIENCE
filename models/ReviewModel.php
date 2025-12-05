<?php
require_once __DIR__ . '/BaseModel.php';

class ReviewModel extends BaseModel
{
    protected $table = 'reviews';

    // Thêm đánh giá mới
    public function addReview($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, user_name, user_email, product_id, rating, comment, image, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        return $this->query($sql, [
            $data['user_id'],
            $data['user_name'],
            $data['user_email'],
            $data['product_id'],
            $data['rating'],
            $data['comment'],
            $data['image'] ?? null
        ]);
    }

    // Lấy tất cả đánh giá (cho admin)
    public function getAllReviews($status = null)
    {
        $sql = "SELECT r.*, p.tensp, p.hinhanh as product_image 
                FROM {$this->table} r 
                LEFT JOIN tblsanpham p ON r.product_id = p.masp";
        $params = [];

        if ($status) {
            $sql .= " WHERE r.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY r.created_at DESC";
        return $this->select($sql, $params);
    }

    // Lấy đánh giá theo sản phẩm (chỉ approved)
    public function getByProduct($productId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ? AND status = 'approved' ORDER BY created_at DESC";
        return $this->select($sql, [$productId]);
    }

    // Lấy đánh giá theo user
    public function getByUser($userId)
    {
        $sql = "SELECT r.*, p.tensp, p.hinhanh as product_image 
                FROM {$this->table} r 
                LEFT JOIN tblsanpham p ON r.product_id = p.masp 
                WHERE r.user_id = ? 
                ORDER BY r.created_at DESC";
        return $this->select($sql, [$userId]);
    }

    // Cập nhật trạng thái đánh giá
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->query($sql, [$status, $id]);
    }

    // Xóa đánh giá
    public function deleteReview($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    // Lấy thống kê đánh giá theo sản phẩm
    public function getProductStats($productId)
    {
        $sql = "SELECT 
                    COUNT(*) as total_reviews,
                    AVG(rating) as avg_rating,
                    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as star_5,
                    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as star_4,
                    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as star_3,
                    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as star_2,
                    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as star_1
                FROM {$this->table} 
                WHERE product_id = ? AND status = 'approved'";
        $result = $this->select($sql, [$productId]);
        return $result[0] ?? null;
    }

    // Lấy review theo ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result[0] ?? null;
    }
}
