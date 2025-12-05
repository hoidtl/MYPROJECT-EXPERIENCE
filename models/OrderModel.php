<?php
require_once __DIR__ . '/BaseModel.php';

class OrderModel extends BaseModel {
    // Lấy chi tiết đơn hàng theo order_id
    public function getOrderDetailsByOrderId($orderId) {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }
      // Lưu đơn hàng kèm thông tin giao hàng
    protected $table = 'orders';
    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM $this->table WHERE user_id = ? ORDER BY created_at DESC";
        return $this->select($sql, [$userId]);
    }
    public function updateOrderStatus($orderCode, $status) {
        $sql = "UPDATE orders SET transaction_info = :transaction_info WHERE order_code = :orderCode";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':transaction_info' => $status,
            ':orderCode' => $orderCode
        ]);
    }
       public function createOrderWithShipping($orderCode, $totalAmount, $userEmail, $receiver, $phone, $address,$created_at,$transaction_info, $coupon_code = null, $discount_amount = 0) {
        // Calculate initial lack_amount and received_amount
        $received_amount = 0;
        $final_amount = $totalAmount - $discount_amount;
        if ($final_amount < 0) $final_amount = 0;
        $lack_amount = $final_amount - $received_amount;
        if ($lack_amount < 0) $lack_amount = 0;

        $sql = "INSERT INTO $this->table (order_code, total_amount, user_email, receiver, phone, address, created_at, transaction_info, coupon_code, discount_amount, received_amount, lack_amount) VALUES (";
        $sql .= ":order_code,:total_amount,:user_email, :receiver, :phone, :address, :created_at,:transaction_info, :coupon_code, :discount_amount, :received_amount, :lack_amount)";
        $stm=$this->db->prepare($sql);
        $getLastInsertId = $stm->execute([
            'order_code' => $orderCode,
            'total_amount' => $final_amount,
            'user_email' => $userEmail,
            'receiver' => $receiver,
            'phone' => $phone,
            'address' => $address,
            'created_at' => $created_at,
            'transaction_info' => $transaction_info,
            'coupon_code' => $coupon_code,
            'discount_amount' => $discount_amount,
            'received_amount' => $received_amount,
            'lack_amount' => $lack_amount
        ]);
       return $this->getLastInsertId();
    }

      // Lấy lịch sử đơn hàng theo email
    public function getOrdersByEmail($email) {
        $sql = "SELECT *, 
                total_amount,
                COALESCE(received_amount, 0) as received_amount,
                COALESCE(discount_amount, 0) as discount_amount,
                coupon_code,
                CASE 
                    WHEN received_amount IS NULL THEN total_amount
                    WHEN received_amount < total_amount THEN total_amount - received_amount
                    ELSE 0
                END as lack_amount,
                CASE 
                    WHEN transaction_info IN ('chothanhtoan', '') OR transaction_info IS NULL 
                    THEN 1 
                    ELSE 0 
                END as needs_payment 
                FROM $this->table 
                WHERE user_email = ? 
                ORDER BY needs_payment DESC, created_at DESC";
        return $this->select($sql, [$email]);
    }

    public function updateReceivedAmountAndStatus($orderCode, $receivedAmount) {
        // First get the total amount for this order
        $sql = "SELECT total_amount, received_amount FROM orders WHERE order_code = :orderCode";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':orderCode' => $orderCode]);
        $order = $stmt->fetch();

        // Calculate new received_amount
        $newReceivedAmount = ($order['received_amount'] ?? 0) + $receivedAmount;
        
        // Calculate lack_amount and determine status
        $lackAmount = $order['total_amount'] - $newReceivedAmount;
        $status = 'chothanhtoan';
        
        if ($lackAmount <= 0) {
            $status = 'dathanhtoan';
            $lackAmount = 0;
        } else {
            $status = 'thanhtoanthieu';
        }

        // Update received_amount, lack_amount and transaction_info
        $sql = "UPDATE orders SET 
                received_amount = :received_amount,
                lack_amount = :lack_amount,
                transaction_info = :transaction_info 
                WHERE order_code = :orderCode";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':received_amount' => $newReceivedAmount,
            ':lack_amount' => $lackAmount,
            ':transaction_info' => $status,
            ':orderCode' => $orderCode
        ]);
    }
    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $result = $this->select($sql, [$orderId]);
        return $result ? $result[0] : null;
    }
    public function getByOrderId($orderId)
    {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        return $this->select($sql, [$orderId]);
    }


    public function createOrder($data)
{
    // Kiểm tra xem cột user_email có tồn tại không
    try {
        $checkCol = $this->db->query("SHOW COLUMNS FROM orders LIKE 'user_email'");
        $hasEmailCol = $checkCol->rowCount() > 0;
    } catch (Exception $e) {
        $hasEmailCol = false;
    }

    if ($hasEmailCol) {
        $sql = "INSERT INTO orders 
        (user_id, user_email, order_code, receiver, phone, address, total_amount,
         discount_amount, coupon_code, transaction_info, note, delivery_method, payment_method)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['user_email'] ?? null,
            $data['order_code'],
            $data['receiver'],
            $data['phone'],
            $data['address'],
            $data['total_amount'],
            $data['discount_amount'],
            $data['coupon_code'],
            $data['transaction_info'],
            $data['note'] ?? null,
            $data['delivery_method'],
            $data['payment_method']
        ]);
    } else {
        $sql = "INSERT INTO orders 
        (user_id, order_code, receiver, phone, address, total_amount,
         discount_amount, coupon_code, transaction_info, note, delivery_method, payment_method)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['order_code'],
            $data['receiver'],
            $data['phone'],
            $data['address'],
            $data['total_amount'],
            $data['discount_amount'],
            $data['coupon_code'],
            $data['transaction_info'],
            $data['note'] ?? null,
            $data['delivery_method'],
            $data['payment_method']
        ]);
    }

    return $this->db->lastInsertId();
}


public function insertOrderDetail($d)
{
    $sql = "INSERT INTO order_details (order_id, product_id, size, quantity, price)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        $d['order_id'],
        $d['product_id'],
        $d['size'],
        $d['quantity'],
        $d['price']
    ]);
}


}
