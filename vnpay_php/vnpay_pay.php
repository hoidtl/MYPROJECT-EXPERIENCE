<?php 
require_once("./config.php");
require_once("../app/config.php");
require_once("../app/DB.php");
require_once("../models/OrderModel.php");

$amount = isset($_SESSION['totalAmount']) ? $_SESSION['totalAmount'] : 0;
$orderCode = isset($_SESSION['orderCode']) ? $_SESSION['orderCode'] : '';

// Lấy thông tin đơn hàng từ DB nếu có
$orderInfo = null;
$lackAmount = 0;
$receivedAmount = 0;
$totalAmount = 0;

if ($orderCode) {
    $orderModel = new OrderModel();
    $orders = $orderModel->select("SELECT * FROM orders WHERE order_code = ?", [$orderCode]);
    if (!empty($orders)) {
        $orderInfo = $orders[0];
        $totalAmount = $orderInfo['total_amount'] ?? 0;
        $receivedAmount = $orderInfo['received_amount'] ?? 0;
        $lackAmount = $orderInfo['lack_amount'] ?? ($totalAmount - $receivedAmount);
        if ($lackAmount < 0) $lackAmount = 0;
        
        // Nếu amount từ session = 0, dùng lack_amount
        if ($amount <= 0) {
            $amount = $lackAmount;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán đơn hàng - VNPay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f8f4;
            min-height: 100vh;
        }
        
        /* Header */
        .site-header {
            background: #6fa05f;
            height: 70px;
            display: flex;
            align-items: center;
        }
        .header-container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo img {
            height: 60px;
            width: 60px;
        }
        .main-nav {
            display: flex;
            gap: 30px;
        }
        .main-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }
        .main-nav a:hover {
            text-decoration: underline;
        }
        
        /* Container */
        .payment-container {
            width: 92%;
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        
        h2 {
            color: #2b7a37;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .order-code {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 15px;
        }
        
        /* Order Summary */
        .order-summary {
            background: #f0f7ef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .order-summary h3 {
            color: #2b7a37;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            color: #2b7a37;
            border-top: 2px solid #2b7a37;
            margin-top: 10px;
            padding-top: 15px;
        }
        .text-success { color: #2b7a37; }
        .text-danger { color: #e74c3c; }
        
        /* Form */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #2b7a37;
        }
        .form-control:read-only {
            background: #f5f5f5;
        }
        
        /* Payment Methods */
        .payment-methods {
            margin-bottom: 25px;
        }
        .payment-methods h3 {
            color: #2b7a37;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .payment-option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-option:hover {
            border-color: #2b7a37;
            background: #f0f7ef;
        }
        .payment-option input[type="radio"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            accent-color: #2b7a37;
        }
        .payment-option img {
            width: 30px;
            height: 30px;
            margin-left: auto;
        }
        
        /* Buttons */
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            border: none;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #2b7a37;
            color: white;
        }
        .btn-primary:hover {
            background: #236b2e;
        }
        .btn-secondary {
            background: #e5e5e5;
            color: #333;
        }
        .btn-secondary:hover {
            background: #d5d5d5;
        }
        
        /* Note */
        .note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #856404;
        }
        
        /* Amount input helper */
        .amount-helper {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .amount-btn {
            padding: 8px 15px;
            background: #f0f7ef;
            border: 1px solid #2b7a37;
            border-radius: 6px;
            color: #2b7a37;
            cursor: pointer;
            font-size: 13px;
        }
        .amount-btn:hover {
            background: #2b7a37;
            color: white;
        }
        
        /* Hidden language */
        .hidden { display: none; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <a href="<?= APP_URL ?>">
                    <img src="<?= APP_URL ?>/public/images/logohinhanhquan/LogoWebsite.jpg" alt="Logo">
                </a>
            </div>
            <nav class="main-nav">
                <a href="<?= APP_URL ?>">Trang chủ</a>
                <a href="<?= APP_URL ?>/Home/menu">Menu Bánh sinh nhật</a>
                <a href="<?= APP_URL ?>/Home/orderHistory">Lịch sử đơn hàng</a>
            </nav>
        </div>
    </header>

    <!-- Payment Container -->
    <div class="payment-container">
        <h2>💳 Thanh toán đơn hàng</h2>
        <p class="order-code">Mã đơn hàng: <strong><?= htmlspecialchars($orderCode) ?></strong></p>
        
        <?php if ($orderInfo): ?>
        <!-- Order Summary -->
        <div class="order-summary">
            <h3>📋 Thông tin đơn hàng</h3>
            <div class="summary-row">
                <span>Tổng tiền đơn hàng:</span>
                <span><?= number_format($totalAmount, 0, ',', '.') ?> ₫</span>
            </div>
            <?php if ($receivedAmount > 0): ?>
            <div class="summary-row">
                <span>Đã thanh toán:</span>
                <span class="text-success"><?= number_format($receivedAmount, 0, ',', '.') ?> ₫</span>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span>Còn phải thanh toán:</span>
                <span class="text-danger"><?= number_format($lackAmount, 0, ',', '.') ?> ₫</span>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($lackAmount > 0 && $lackAmount != $amount): ?>
        <div class="note">
            💡 <strong>Lưu ý:</strong> Bạn có thể thanh toán một phần hoặc toàn bộ số tiền còn thiếu. 
            Số tiền còn lại sẽ được ghi nhận và bạn có thể thanh toán nốt sau.
        </div>
        <?php endif; ?>
        
        <form action="vnpay_create_payment.php" method="post">
            <div class="form-group">
                <label for="amount">Số tiền thanh toán (VNĐ)</label>
                <input class="form-control" id="amount" name="amount" type="number" 
                       min="10000" max="<?= $lackAmount > 0 ? $lackAmount : 100000000 ?>" 
                       value="<?= $amount ?>" required>
                
                <?php if ($lackAmount > 0): ?>
                <div class="amount-helper">
                    <button type="button" class="amount-btn" onclick="setAmount(<?= $lackAmount ?>)">
                        Thanh toán toàn bộ (<?= number_format($lackAmount, 0, ',', '.') ?>₫)
                    </button>
                    <?php if ($lackAmount >= 100000): ?>
                    <button type="button" class="amount-btn" onclick="setAmount(<?= floor($lackAmount/2) ?>)">
                        Thanh toán 50%
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="payment-methods">
                <h3>Chọn phương thức thanh toán</h3>
                
                <label class="payment-option">
                    <input type="radio" name="bankCode" value="" checked>
                    <span>Cổng thanh toán VNPAY (QR Code, Thẻ, Ví điện tử)</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/6963/6963703.png" alt="">
                </label>
                
                <label class="payment-option">
                    <input type="radio" name="bankCode" value="VNPAYQR">
                    <span>Quét mã VNPAY QR</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/3388/3388930.png" alt="">
                </label>
                
                <label class="payment-option">
                    <input type="radio" name="bankCode" value="VNBANK">
                    <span>Thẻ ATM / Tài khoản ngân hàng nội địa</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/2331/2331949.png" alt="">
                </label>
                
                <label class="payment-option">
                    <input type="radio" name="bankCode" value="INTCARD">
                    <span>Thẻ thanh toán quốc tế (Visa, Master, JCB)</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/349/349221.png" alt="">
                </label>
            </div>
            
            <!-- Hidden language field -->
            <input type="hidden" name="language" value="vn">
            
            <div class="btn-group">
                <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-secondary">← Quay lại</a>
                <button type="submit" class="btn btn-primary">Thanh toán ngay →</button>
            </div>
        </form>
    </div>
    
    <script>
        function setAmount(value) {
            document.getElementById('amount').value = value;
        }
    </script>
</body>
</html>
