<?php
// QUAN TRỌNG: Start session TRƯỚC KHI output bất kỳ HTML nào
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../app/config.php");
require_once("../app/DB.php");
require_once("./config.php");
require_once("../models/OrderModel.php");
require_once("../models/OrderDetailModel.php");
require_once("../vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;

$vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Xử lý kết quả thanh toán
$paymentSuccess = false;
$message = '';
$orderCode = $_GET['vnp_TxnRef'] ?? '';
$paidAmount = isset($_GET['vnp_Amount']) ? ($_GET['vnp_Amount'] / 100) : 0;

// Lấy thông tin đơn hàng
$orderInfo = null;
$lackAmount = 0;

if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        // Thanh toán thành công - cập nhật đơn hàng
        $orderModel = new OrderModel();
        $orderModel->updateReceivedAmountAndStatus($orderCode, $paidAmount);
        
        // Lấy thông tin đơn hàng sau khi cập nhật
        $orders = $orderModel->select("SELECT * FROM orders WHERE order_code = ?", [$orderCode]);
        if (!empty($orders)) {
            $orderInfo = $orders[0];
            $lackAmount = $orderInfo['lack_amount'] ?? 0;
        }
        
        $paymentSuccess = true;
        $message = 'Thanh toán thành công!';
        
        // Lưu thông báo vào session
        $_SESSION['payment_success'] = true;
        $_SESSION['payment_message'] = 'Thanh toán đơn hàng ' . $orderCode . ' thành công!';
        
        // Gửi email xác nhận đơn hàng
        $userEmail = $orderInfo['user_email'] ?? ($_SESSION['user']['email'] ?? null);
        if ($orderInfo && !empty($userEmail)) {
            $orderDetailModel = new OrderDetailModel();
            $orderDetails = $orderDetailModel->getByOrderId($orderInfo['id']);
            // Thêm email vào orderInfo để dùng trong hàm gửi mail
            $orderInfo['email'] = $userEmail;
            $orderInfo['fullname'] = $orderInfo['receiver'] ?? ($_SESSION['user']['fullname'] ?? 'Quý khách');
            sendOrderConfirmationEmail($orderInfo, $orderDetails, $paidAmount);
        }
        
        // Xóa session thanh toán
        unset($_SESSION['cart']);
        unset($_SESSION['orderCode']);
        unset($_SESSION['totalAmount']);
    } else {
        $message = 'Giao dịch không thành công. Mã lỗi: ' . $_GET['vnp_ResponseCode'];
        $_SESSION['payment_error'] = $message;
    }
} else {
    $message = 'Chữ ký không hợp lệ!';
    $_SESSION['payment_error'] = $message;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kết quả thanh toán</title>
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
        
        /* Result Container */
        .result-container {
            width: 92%;
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            text-align: center;
        }
        
        /* Success/Error Icon */
        .result-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 50px;
        }
        .result-icon.success {
            background: #d4edda;
            color: #2b7a37;
        }
        .result-icon.error {
            background: #f8d7da;
            color: #e74c3c;
        }
        
        h2 {
            margin-bottom: 15px;
            font-size: 24px;
        }
        h2.success { color: #2b7a37; }
        h2.error { color: #e74c3c; }
        
        .result-message {
            color: #666;
            margin-bottom: 25px;
        }
        
        /* Order Details */
        .order-details {
            background: #f0f7ef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #ccc;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #2b7a37;
        }
        
        /* Lack Amount Warning */
        .lack-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            color: #856404;
        }
        .lack-warning strong {
            color: #e74c3c;
        }
        
        /* Buttons */
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 14px 25px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
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
        .btn-warning {
            background: #f0ad4e;
            color: #333;
        }
        .btn-warning:hover {
            background: #ec971f;
        }
        
        /* Countdown */
        .countdown {
            margin-top: 20px;
            color: #999;
            font-size: 14px;
        }
        .countdown span {
            font-weight: bold;
            color: #2b7a37;
        }
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

    <!-- Result Container -->
    <div class="result-container">
        <?php if ($paymentSuccess): ?>
            <div class="result-icon success">✓</div>
            <h2 class="success">Thanh toán thành công!</h2>
            <p class="result-message">Cảm ơn bạn đã thanh toán. Đơn hàng của bạn đã được cập nhật.</p>
            
            <div class="order-details">
                <div class="detail-row">
                    <span>Mã đơn hàng:</span>
                    <strong><?= htmlspecialchars($orderCode) ?></strong>
                </div>
                <div class="detail-row">
                    <span>Số tiền đã thanh toán:</span>
                    <strong><?= number_format($paidAmount, 0, ',', '.') ?> ₫</strong>
                </div>
                <div class="detail-row">
                    <span>Mã giao dịch VNPAY:</span>
                    <strong><?= htmlspecialchars($_GET['vnp_TransactionNo'] ?? '') ?></strong>
                </div>
                <div class="detail-row">
                    <span>Ngân hàng:</span>
                    <strong><?= htmlspecialchars($_GET['vnp_BankCode'] ?? '') ?></strong>
                </div>
            </div>
            
            <?php if ($lackAmount > 0): ?>
            <div class="lack-warning">
                ⚠️ Đơn hàng còn thiếu <strong><?= number_format($lackAmount, 0, ',', '.') ?> ₫</strong>. 
                Bạn có thể thanh toán nốt số tiền còn lại.
            </div>
            
            <div class="btn-group">
                <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-secondary">Xem lịch sử đơn hàng</a>
                <form action="<?= APP_URL ?>/Home/vnpayPay" method="POST" style="display:inline;">
                    <input type="hidden" name="order_code" value="<?= htmlspecialchars($orderCode) ?>">
                    <input type="hidden" name="amount" value="<?= $lackAmount ?>">
                    <button type="submit" class="btn btn-warning">Thanh toán nốt <?= number_format($lackAmount, 0, ',', '.') ?> ₫</button>
                </form>
            </div>
            <?php else: ?>
            <div class="btn-group">
                <a href="<?= APP_URL ?>/Home" class="btn btn-secondary">Về trang chủ</a>
                <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-primary">Xem lịch sử đơn hàng</a>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="result-icon error">✗</div>
            <h2 class="error">Thanh toán thất bại</h2>
            <p class="result-message"><?= htmlspecialchars($message) ?></p>
            
            <div class="btn-group">
                <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-secondary">Xem lịch sử đơn hàng</a>
                <a href="<?= APP_URL ?>/Home" class="btn btn-primary">Về trang chủ</a>
            </div>
        <?php endif; ?>
        
        <p class="countdown">
            Tự động chuyển về lịch sử đơn hàng sau <span id="countdown">5</span> giây...
        </p>
    </div>
    
    <script>
        var seconds = 5;
        var countdown = document.getElementById('countdown');
        var timer = setInterval(function() {
            seconds--;
            countdown.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '<?= APP_URL ?>/Home/orderHistory';
            }
        }, 1000);
    </script>
</body>
</html>

<?php
// Hàm gửi email xác nhận đơn hàng
function sendOrderConfirmationEmail($order, $orderDetails, $paidAmount) {
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "base64";
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chitogelovehoi@gmail.com';
        $mail->Password = 'mkur ygbo jbyz xtwi';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('chitogelovehoi@gmail.com', 'Bánh Kem Shop');
        $mail->addAddress($order['email'], $order['fullname']);

        $mail->isHTML(true);
        $mail->Subject = "Xác nhận đơn hàng #{$order['order_code']} - Thanh toán thành công";
        
        // Tạo nội dung email
        $itemsHtml = '';
        $pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        foreach ($orderDetails as $item) {
            $productName = $item['product_name'] ?? $item['tensp'] ?? 'Sản phẩm';
            $size = $item['size'] ?? '';
            
            // Lấy giá từ bảng tbl_sanpham_size
            $stmt = $pdo->prepare("SELECT giaXuat FROM tbl_sanpham_size WHERE masp = ? AND size = ?");
            $stmt->execute([$item['product_id'], $size]);
            $sizeInfo = $stmt->fetch();
            $price = $sizeInfo['giaXuat'] ?? $item['price'] ?? 0;
            $subtotal = $price * $item['quantity'];
            
            $itemsHtml .= "<tr>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$productName} ({$size})</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:center;'>{$item['quantity']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:right;'>" . number_format($price, 0, ',', '.') . " ₫</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:right;'>" . number_format($subtotal, 0, ',', '.') . " ₫</td>
            </tr>";
        }
        
        $lackAmount = $order['lack_amount'] ?? 0;
        $lackHtml = '';
        if ($lackAmount > 0) {
            $lackHtml = "<p style='color:#e74c3c; font-weight:bold;'>⚠️ Còn thiếu: " . number_format($lackAmount, 0, ',', '.') . " ₫</p>";
        }
        
        $mail->Body = "
        <div style='font-family:Arial,sans-serif; max-width:600px; margin:0 auto;'>
            <div style='background:#6fa05f; padding:20px; text-align:center;'>
                <h1 style='color:#fff; margin:0;'>🎂 Bánh Kem Shop</h1>
            </div>
            
            <div style='padding:30px; background:#f9f9f9;'>
                <h2 style='color:#2b7a37;'>✅ Đặt hàng thành công!</h2>
                <p>Xin chào <strong>{$order['fullname']}</strong>,</p>
                <p>Cảm ơn bạn đã đặt hàng tại Bánh Kem Shop. Đơn hàng của bạn đã được xác nhận.</p>
                
                <div style='background:#fff; padding:20px; border-radius:8px; margin:20px 0;'>
                    <h3 style='color:#333; margin-top:0;'>📦 Thông tin đơn hàng</h3>
                    <p><strong>Mã đơn hàng:</strong> {$order['order_code']}</p>
                    <p><strong>Ngày đặt:</strong> {$order['created_at']}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> {$order['address']}</p>
                    <p><strong>Số điện thoại:</strong> {$order['phone']}</p>
                </div>
                
                <div style='background:#fff; padding:20px; border-radius:8px; margin:20px 0;'>
                    <h3 style='color:#333; margin-top:0;'>🛒 Chi tiết sản phẩm</h3>
                    <table style='width:100%; border-collapse:collapse;'>
                        <thead>
                            <tr style='background:#f0f7ef;'>
                                <th style='padding:10px; text-align:left;'>Sản phẩm</th>
                                <th style='padding:10px; text-align:center;'>SL</th>
                                <th style='padding:10px; text-align:right;'>Đơn giá</th>
                                <th style='padding:10px; text-align:right;'>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHtml}
                        </tbody>
                    </table>
                </div>
                
                <div style='background:#d4edda; padding:15px; border-radius:8px; margin:20px 0;'>
                    <p style='margin:5px 0;'><strong>Tổng tiền:</strong> " . number_format($order['total_amount'], 0, ',', '.') . " ₫</p>
                    <p style='margin:5px 0;'><strong>Đã thanh toán:</strong> " . number_format($paidAmount, 0, ',', '.') . " ₫</p>
                    {$lackHtml}
                </div>
                
                <p style='color:#666;'>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
                <p style='color:#666;'>Trân trọng,<br><strong>Bánh Kem Shop</strong></p>
            </div>
            
            <div style='background:#333; padding:15px; text-align:center;'>
                <p style='color:#fff; margin:0; font-size:12px;'>© 2025 Bánh Kem Shop - Website Bán Bánh Kem</p>
            </div>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log lỗi nếu cần
        return false;
    }
}
?>
