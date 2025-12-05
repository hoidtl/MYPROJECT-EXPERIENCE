<?php
// require_once 'BaseModel.php';
class OrderDetailModel extends BaseModel {
    protected $table = 'order_details';
    
    public function addOrderDetail($orderId, $productId, $quantity, $price, $salePrice, $total, $image, $productName) {
        if (is_null($orderId)) {
            throw new Exception("orderId không được để trống khi thêm chi tiết đơn hàng.");
        }

        $sql = "INSERT INTO {$this->table} 
                (order_id, product_id, quantity, price, sale_price, total, image, product_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->query($sql, [
            $orderId, $productId, $quantity, $price, $salePrice, $total, $image, $productName
        ]);
    }
    
    // Lấy chi tiết đơn hàng theo order_id (join với bảng sản phẩm để lấy tên)
    public function getByOrderId($orderId) {
        $sql = "SELECT od.*, p.tensp as product_name, p.hinhanh as product_image 
                FROM {$this->table} od 
                LEFT JOIN tblsanpham p ON od.product_id = p.masp 
                WHERE od.order_id = ?";
        return $this->select($sql, [$orderId]);
    }
}
