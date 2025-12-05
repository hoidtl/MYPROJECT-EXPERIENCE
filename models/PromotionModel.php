        <?php
        require_once 'BaseModel.php';
        class PromotionModel extends BaseModel {
            protected $table = 'promotions';

            // Get promotion by code
            public function getByCode($code) {
                $sql = "SELECT * FROM $this->table WHERE code = :code LIMIT 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':code' => $code]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Validate promotion code against order amount and usage
            // Returns array: ['success'=>bool,'message'=>string,'discount_amount'=>float,'promotion'=>array]
            public function validateCode($code, $orderAmount) {
                $promo = $this->getByCode($code);
                if (!$promo) {
                    return ['success' => false, 'message' => 'Mã khuyến mãi không tồn tại', 'discount_amount' => 0, 'promotion' => null];
                }

                $now = date('Y-m-d H:i:s');
                if (!empty($promo['start_date']) && $now < $promo['start_date']) {
                    return ['success' => false, 'message' => 'Mã còn chưa hiệu lực', 'discount_amount' => 0, 'promotion' => $promo];
                }
                if (!empty($promo['end_date']) && $now > $promo['end_date']) {
                    return ['success' => false, 'message' => 'Mã đã hết hạn', 'discount_amount' => 0, 'promotion' => $promo];
                }

                if (isset($promo['status']) && $promo['status'] != 'active') {
                    return ['success' => false, 'message' => 'Mã không khả dụng', 'discount_amount' => 0, 'promotion' => $promo];
                }

                if (!empty($promo['usage_limit']) && $promo['usage_limit'] > 0) {
                    $usageCount = (int)($promo['usage_count'] ?? 0);
                    if ($usageCount >= $promo['usage_limit']) {
                        return ['success' => false, 'message' => 'Mã đã đạt giới hạn sử dụng', 'discount_amount' => 0, 'promotion' => $promo];
                    }
                }

                if (!empty($promo['min_order_amount']) && $orderAmount < $promo['min_order_amount']) {
                    return ['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã', 'discount_amount' => 0, 'promotion' => $promo];
                }

                // Calculate discount
                $discount = 0;
                if ($promo['type'] == 'percent') {
                    $discount = round($orderAmount * (float)$promo['value'] / 100, 0);
                } else { // fixed
                    $discount = (float)$promo['value'];
                }

                // Don't allow discount greater than order amount
                if ($discount > $orderAmount) $discount = $orderAmount;

                return ['success' => true, 'message' => 'Mã hợp lệ', 'discount_amount' => $discount, 'promotion' => $promo];
            }

            // Increase usage count (simple global usage)
            public function incrementUsage($id) {
                $sql = "UPDATE $this->table SET usage_count = COALESCE(usage_count,0) + 1 WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([':id' => $id]);
            }

            // trong class PromotionModel
            public function getAllActive() {
            $sql = "SELECT * FROM promotions WHERE status = 'active'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        }
