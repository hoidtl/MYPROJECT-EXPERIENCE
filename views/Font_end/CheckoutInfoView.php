<?php
// NHẬN DATA TỪ CONTROLLER
$listProductOrder = $data['listProductOrder'] ?? [];
$vouchers = $data['vouchers'] ?? [];
$total = (float)($data['total'] ?? 0);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?= APP_URL ?>/public/css/checkOut.css">
    <title>Xác nhận đơn hàng</title>
</head>
<body>
<form action="<?= APP_URL ?>/Home/placeOrder" method="POST">
<header class="site-header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <a href="<?= APP_URL ?>">
                <img src="<?= APP_URL ?>/public/images/logohinhanhquan/LogoWebsite.jpg" alt="Savor Cake">
            </a>
        </div>

        <!-- MENU -->
        <nav class="main-nav">
            <a href="<?= APP_URL ?>">Trang chủ</a>
            <a href="<?= APP_URL ?>/Home/menu">Menu Bánh sinh nhật</a>
            <a href="<?= APP_URL ?>/Home/advice">Tư vấn</a>
            <a href="<?= APP_URL ?>/Home/contact">Liên hệ</a>
        </nav>

        <!-- ICON -->
        <div class="header-right">
            <a href="<?= APP_URL ?>/Home/order" class="cart-link">
                🛒
                <span class="cart-count">
                    <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                </span>
            </a>
        </div>

    </div>
</header>


<!-- ================= CHECKOUT HEADER ================= -->
<div class="checkout-header">
    <div class="checkout-header-inner">
        <div>
            <h1>Xác nhận đơn hàng</h1>
            <p>Vui lòng kiểm tra và điền đầy đủ thông tin trước khi thanh toán</p>
        </div>
    </div>
</div>


<div class="checkout-container">
    
<!-- ================= LEFT ================= -->
<div class="left">
    <h3>Thông tin người đặt</h3>
    <label>Họ và tên</label>
    <input type="text" name="order_name" required>

    <label>Số điện thoại</label>
    <input type="text" name="order_phone" required>

    <h3 class="mt">Thông tin người nhận</h3>
    <label>
        <input type="checkbox" id="same_info"> Giống người đặt hàng
    </label>
    <script>
        document.getElementById("same_info").addEventListener("change", function () {
    const checked = this.checked;

    const orderName = document.querySelector('input[name="order_name"]').value;
    const orderPhone = document.querySelector('input[name="order_phone"]').value;

    const receiverName = document.getElementById("receiver_name");
    const receiverPhone = document.getElementById("receiver_phone");

    if (checked) {
        receiverName.value = orderName;
        receiverPhone.value = orderPhone;

        receiverName.setAttribute("readonly", true);
        receiverPhone.setAttribute("readonly", true);
    } else {
        receiverName.value = "";
        receiverPhone.value = "";

        receiverName.removeAttribute("readonly");
        receiverPhone.removeAttribute("readonly");
    }
});
    </script>
    <label>Họ và tên</label>
    <input type="text" name="receiver_name" id="receiver_name">

    <label>Số điện thoại</label>
    <input type="text" name="receiver_phone" id="receiver_phone">

    <h3 class="mt">Địa chỉ nhận hàng</h3>

    <label>
        <input type="checkbox" id="pickup_store"> Lấy tại cửa hàng
    </label>
    <input type="hidden" id="delivery_method" name="delivery_method" value="home">
    <script>
document.getElementById("pickup_store").addEventListener("change", function () {
    const checked = this.checked;

    const district = document.getElementById("district");
    const ward = document.getElementById("ward");
    const address = document.querySelector('input[name="address"]');
    const deliveryMethod = document.getElementById("delivery_method");

    if (checked) {
        district.value = "";
        ward.value = "";
        address.value = "";

        district.setAttribute("readonly", true);
        ward.setAttribute("readonly", true);
        address.setAttribute("readonly", true);

        deliveryMethod.value = "store";   
    } else {
        district.removeAttribute("readonly");
        ward.removeAttribute("readonly");
        address.removeAttribute("readonly");

        deliveryMethod.value = "home";    
    }
});
</script>

    <label>Quận</label>
    <input type="text" name="district" id="district">

    <label>Phường</label>
    <input type="text" name="ward" id="ward">


    <label>Địa chỉ cụ thể</label>
    <input type="text" name="address">

    <h3 class="mt">Ghi chú khác</h3>
    <textarea name="note" placeholder="Vui lòng ghi rõ nội dung..."></textarea>

    <input type="hidden" name="discount_amount" id="discountInput" value="0">
    <input type="hidden" name="ship_fee" id="shipInput" value="0">
    <input type="hidden" name="final_amount" id="finalInput" value="<?= $total ?>">

</div>

<!-- ================= RIGHT ================= -->
<div class="right">

<h3>Thanh toán</h3>

<div class="order-items">

<?php if (empty($listProductOrder)): ?>
    <p style="color:red;">Không có sản phẩm</p>
<?php else: ?>

    <?php foreach ($listProductOrder as $v): ?>
        <?php $thanhTien = $v['gia'] * $v['qty']; ?>

        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
            <span>
                <?= htmlspecialchars($v['tensp']) ?>
                (<?= htmlspecialchars($v['size']) ?>) x<?= $v['qty'] ?>
            </span>
            <strong>
                <?= number_format($thanhTien, 0, ',', '.') ?>₫
            </strong>
        </div>

    <?php endforeach; ?>

<?php endif; ?>

</div>

<hr>

<!-- ================= SUMMARY ================= -->
<div class="summary">

    <div class="row">
        <span>Tổng tiền sản phẩm:</span>
        <strong id="sum-total">
            <?= number_format($total, 0, ',', '.') ?>₫
        </strong>
    </div>

    <div class="row">
        <span>Chọn voucher:</span>
        <select id="voucherSelect" name="voucher_code" style="width:180px;">
            <option value="">-- Không dùng --</option>

            <?php foreach ($vouchers as $v): ?>
                <option
                    value="<?= $v['code'] ?>"
                    data-type="<?= $v['type'] ?>"
                    data-value="<?= $v['value'] ?>"
                    data-min="<?= $v['min_order_amount'] ?>"
                >
                    <?= $v['code'] ?>
                    (<?= $v['type'] === "percent"
                        ? $v['value'] . "%"
                        : number_format($v['value']) . "đ" ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row">
        <span>Giảm giá:</span>
        <strong id="discount-amount">0₫</strong>
    </div>

    <div class="row">
        <span>Phí ship:</span>
        <strong id="ship-fee">0₫</strong>
    </div>
</div>

<div class="total">
    <span>Tổng đơn:</span>
    <strong id="total-pay">
        <?= number_format($total, 0, ',', '.') ?>₫
    </strong>
</div>

</div>
</div>

<!-- ================= JS ================= -->
<script>
const baseTotal = <?= $total ?>;

const voucherSelect = document.getElementById('voucherSelect');
const discountLabel = document.getElementById('discount-amount');
const totalPayLabel = document.getElementById('total-pay');
const shipFeeLabel = document.getElementById('ship-fee');

function updateTotal() {
    const opt = voucherSelect.options[voucherSelect.selectedIndex];

    let discount = 0;

    if (opt.value) {
        const type = opt.dataset.type;
        const value = parseFloat(opt.dataset.value);
        const min = parseFloat(opt.dataset.min) || 0;

        if (baseTotal >= min) {
            discount = (type === 'percent')
                ? Math.round(baseTotal * value / 100)
                : value;

            if (discount > baseTotal) discount = baseTotal;
        } else {
            alert("Đơn hàng chưa đủ điều kiện dùng voucher!");
            voucherSelect.value = "";
        }
    }

    const totalAfterDiscount = baseTotal - discount;
    let shipFee = totalAfterDiscount < 350000 ? 30000 : 0;
    let finalTotal = totalAfterDiscount + shipFee;

    discountLabel.innerText = discount.toLocaleString() + "₫";
    shipFeeLabel.innerText = shipFee.toLocaleString() + "₫";
    totalPayLabel.innerText = finalTotal.toLocaleString() + "₫";

    document.getElementById('discountInput').value = discount;
    document.getElementById('shipInput').value = shipFee;
    document.getElementById('finalInput').value = finalTotal;
}

// Lắng nghe sự kiện
voucherSelect.addEventListener("change", updateTotal);

// Chạy lần đầu
updateTotal();

</script>

<div class="checkout-box">

    <div class="checkout-section">

        <h2 class="title">Phí ship</h2>

        <div class="ship-block">
            <p class="ship-title">1. Ship 12 quận nội thành Hà Nội:</p>
            <ul>
                <li>Đơn dưới 350k: 30k</li>
                <li>Đơn từ 350k: Free ship</li>
            </ul>

            <p class="ship-title">2. Có 2 hình thức thanh toán</p>
            <ul>
                <li>Tiền mặt : Thì khi nhận hàng kiểm hàng mới phải thanh toán</li>
                <li>Chuyển khoản : Chuyển khoản khi nhận hàng hoặc chuyển khoản trước</li>
            </ul>
        </div>

        <h2 class="title mt-20">Phương thức thanh toán</h2>

        <div class="payment-options">
            <label class="payment-option">
                <input type="radio" name="payment" value="bank_before" checked>
                <span class="circle"></span>
                <span class="payment-text">Chuyển khoản trước (qua VNPay)</span>
                <img src="<?= APP_URL ?>/public/images/vnpay-logo.png" class="icon" alt="VNPay" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2331/2331949.png'">
            </label>

            <label class="payment-option">
                <input type="radio" name="payment" value="bank_after">
                <span class="circle"></span>
                <span class="payment-text">Chuyển khoản sau khi nhận hàng</span>
                <img src="https://cdn-icons-png.flaticon.com/512/2830/2830284.png" class="icon" alt="Bank">
            </label>

            <label class="payment-option">
                <input type="radio" name="payment" value="cod">
                <span class="circle"></span>
                <span class="payment-text">Thanh toán tiền mặt khi nhận hàng</span>
                <img src="https://cdn-icons-png.flaticon.com/512/2489/2489756.png" class="icon" alt="COD">
            </label>
        </div>

        <p class="note">
            Cửa hàng khuyến khích quý khách chuyển khoản trước toàn bộ để được tự động xác nhận đơn hàng nhanh hơn ạ.
        </p>

    </div>

</div>

<div class="order-btn-wrap">
    <button class="order-btn" type="submit">
        Đặt hàng
    </button>
</div>
</form>
</body>
</html>
