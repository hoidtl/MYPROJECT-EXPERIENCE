<?php
class Home extends Controller{
        // Hiển thị lịch sử đơn hàng cho người dùng đã đăng nhập
    public function orderHistory() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $orderModel = $this->model('OrderModel');
        $userId = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'] ?? null;
        $orders = [];
        if ($userId !== null) {
            $orders = $orderModel->getOrdersByUser($userId);
        }
        $this->view("Font_end/OrderHistoryView", ["orders" => $orders]);
    }
        // Lưu thông tin giao hàng, hóa đơn và chi tiết hóa đơn

   public function show()
{
    $productModel = $this->model("AdProducModel");

    // ✅ lấy toàn bộ sản phẩm
    $allProducts = $productModel->all("tblsanpham");

    $grouped = [];           // Bánh hoa quả (hiển thị đầu tiên)
    $otherCategories = [];   // Các loại bánh khác (trừ hoa quả và phụ kiện)

    foreach ($allProducts as $sp) {
        $loai = $sp['maLoaiSP'];
        
        // Bỏ qua phụ kiện
        if (stripos($loai, 'phụ kiện') !== false || stripos($loai, 'phu kien') !== false) {
            continue;
        }
        
        // ✅ lấy size
        $sizes = $productModel->select(
            "SELECT * FROM tbl_sanpham_size WHERE masp = ? ORDER BY giaXuat ASC",
            [$sp['masp']]
        );

        if (!$sizes) $sizes = [];

        $minPrice = count($sizes) ? $sizes[0]['giaXuat'] : 0;

        $productData = [
            "masp"     => $sp["masp"],
            "tensp"    => $sp["tensp"],
            "hinhanh"  => $sp["hinhanh"],
            "sizes"    => $sizes,
            "minPrice" => $minPrice,
            "moTa"     => $sp["mota"] ?? ''
        ];

        // Phân loại sản phẩm
        if ($loai === 'Bánh hoa quả') {
            // Group bánh hoa quả riêng (hiển thị đầu tiên)
            if (!isset($grouped[$loai])) {
                $grouped[$loai] = [
                    'title' => $loai,
                    'items' => []
                ];
            }
            $grouped[$loai]['items'][] = $productData;
        } else {
            // Các loại bánh khác -> group theo loại
            if (!isset($otherCategories[$loai])) {
                $otherCategories[$loai] = [
                    'title' => $loai,
                    'items' => []
                ];
            }
            $otherCategories[$loai]['items'][] = $productData;
        }
    }
    
    // Tách riêng bánh Tết (tìm kiếm linh hoạt)
    $tetCategories = [];
    foreach ($otherCategories as $key => $category) {
        // Tìm các loại có chứa "tết" hoặc "tet" trong tên
        if (stripos($key, 'tết') !== false || stripos($key, 'tet') !== false) {
            $tetCategories[$key] = $category;
            unset($otherCategories[$key]);
        }
    }

    // Lấy đánh giá đã duyệt để hiển thị trên trang chủ
    $reviewModel = $this->model('ReviewModel');
    $approvedReviews = $reviewModel->getAllReviews('approved');
    // Lấy tối đa 8 đánh giá mới nhất
    $approvedReviews = array_slice($approvedReviews, 0, 8);
    
    $this->view("homePage", [
        "productData" => $grouped,
        "tetCategories" => $tetCategories,
        "otherCategories" => $otherCategories,
        "reviews" => $approvedReviews
    ]);
}
    public function orderDetail($orderId)
{
    // Chưa đăng nhập thì đá về login
    if (!isset($_SESSION['user'])) {
        header('Location: ' . APP_URL . '/AuthController/ShowLogin');
        exit();
    }

    $orderModel = $this->model("OrderModel");
    $orderDetailModel = $this->model("OrderDetailModel");

    // ✅ LẤY THÔNG TIN ĐƠN HÀNG
    $order = $orderModel->getOrderById($orderId);

    if (!$order) {
        die("Đơn hàng không tồn tại");
    }

    // ✅ CHỈ CHO XEM ĐƠN CỦA CHÍNH MÌNH (check cả user_id và user_email)
    $userId = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'] ?? null;
    $userEmail = $_SESSION['user']['email'] ?? '';
    
    $orderUserId = $order['user_id'] ?? null;
    $orderUserEmail = $order['user_email'] ?? '';
    
    // Cho phép xem nếu trùng user_id HOẶC trùng email
    $canView = ($userId && $orderUserId && $userId == $orderUserId) || 
               ($userEmail && $orderUserEmail && $userEmail === $orderUserEmail);
    
    if (!$canView) {
        die("Bạn không có quyền xem đơn hàng này");
    }

    // ✅ CHI TIẾT ĐƠN HÀNG
    $details = $orderDetailModel->getByOrderId($orderId);

    // ✅ LOAD VIEW RIÊNG
    $this->view("Font_end/OrderDetailView", [
        "orderId" => $orderId,
        "order"   => $order,
        "details" => $details
    ]);
}

 
    public function addtocard($masp) {
    $size = $_GET['size'] ?? '';

    if ($size == '') die("Chưa chọn size bánh");

    $model = $this->model("AdProducModel");

    $row = $model->select(
        "SELECT giaXuat FROM tbl_sanpham_size WHERE masp=? AND size=? LIMIT 1",
        [$masp, $size]
    );

    $price = $row[0]['giaXuat'];

    if (!isset($_SESSION['cart'][$masp][$size])) {
        $_SESSION['cart'][$masp][$size] = [
            'masp' => $masp,
            'size' => $size,
            'price' => $price,
            'qty' => 1
        ];
    } else {
        $_SESSION['cart'][$masp][$size]['qty']++;
    }

    header("Location: " . APP_URL . "/Home/order");
    exit();
}


public function addToCartAjax($masp) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $size = $_GET['size'] ?? '';

    if ($size == '') {
        echo json_encode(['success' => false]);
        exit();
    }

    if (!isset($_SESSION['cart'][$masp][$size])) {
        $_SESSION['cart'][$masp][$size] = [
            'masp' => $masp,
            'size' => $size,
            'qty'  => 1
        ];
    } else {
        $_SESSION['cart'][$masp][$size]['qty']++;
    }

    $totalQty = 0;
    foreach ($_SESSION['cart'] as $sizes) {
        foreach ($sizes as $item) {
            $totalQty += $item['qty'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'totalQty' => $totalQty
    ]);
    exit();
}



    public function delete($masp, $size)
{
    if (isset($_SESSION['cart'][$masp][$size])) {
        unset($_SESSION['cart'][$masp][$size]);

        // nếu masp không còn size nào → xoá luôn masp
        if (empty($_SESSION['cart'][$masp])) {
            unset($_SESSION['cart'][$masp]);
        }
    }

    header("Location: " . APP_URL . "/Home/order");
    exit();
}


    public function update()
{
    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $masp => $sizes) {
            foreach ($sizes as $size => $qty) {
                if (isset($_SESSION['cart'][$masp][$size])) {
                    $_SESSION['cart'][$masp][$size]['qty'] = max(1, (int)$qty);
                }
            }
        }
    }

    if (isset($_POST['addon_qty'])) {
        foreach ($_POST['addon_qty'] as $masp => $qty) {
            $key = 'addon_' . (int)$masp;

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['qty'] = max(1, (int)$qty);
            }
        }
    }

    header("Location: " . APP_URL . "/Home/order");
}


private function getPhuKienForOrder()
{
    $model = $this->model("AdProducModel");

    // Lấy danh sách phụ kiện (dùng đúng giá trị maLoaiSP như trong DB: "Phụ kiện")
    $rows = $model->select(
        "SELECT * FROM tblsanpham WHERE maLoaiSP = ? ORDER BY masp DESC",
        ['Phụ kiện']
    );

    // Nếu bảng size có giá, lấy giá nhỏ nhất từ tbl_sanpham_size
    foreach ($rows as &$r) {
        // ưu tiên trường 'gia' nếu có
        if (!empty($r['gia'])) {
            $r['display_price'] = (float)$r['gia'];
            continue;
        }

        // cố gắng lấy giá từ tbl_sanpham_size (min giaXuat)
        $sizes = $model->select(
            "SELECT MIN(giaXuat) AS minPrice FROM tbl_sanpham_size WHERE masp = ?",
            [$r['masp']]
        );

        $minPrice = 0;
        if (!empty($sizes) && isset($sizes[0]['minPrice'])) {
            $minPrice = (float)$sizes[0]['minPrice'];
        }

        $r['display_price'] = $minPrice;
    }
    unset($r);

    return $rows;
}



    public function order()
{
    // nếu cart rỗng -> render view trống (hoặc redirect)
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        // gọi đúng view — đổi theo đường dẫn view của mày
        $this->view("Font_end/OrderView", [
            "listProductOrder" => [],
            "phuKien" => $this->getPhuKienForOrder()
        ]);
        return;
    }

    $model = $this->model("AdProducModel");
    $listProductOrder = [];

    foreach ($_SESSION['cart'] as $key => $value) {

        // 1) Nếu key là chuỗi bắt đầu bằng 'addon_' -> là phụ kiện
        if (is_string($key) && str_starts_with($key, 'addon_')) {
            // đảm bảo các trường tồn tại trước khi dùng
            $masp    = $value['masp'] ?? null;
            $tensp   = $value['tensp'] ?? ($masp ? ($model->find('tblsanpham', $masp)['tensp'] ?? '') : '');
            $hinhanh = $value['hinhanh'] ?? ($masp ? ($model->find('tblsanpham', $masp)['hinhanh'] ?? '') : '');
            $gia     = isset($value['gia']) ? (float)$value['gia'] : (float)($value['giaXuat'] ?? 0);
            $qty     = isset($value['qty']) ? (int)$value['qty'] : 1;

            $listProductOrder[] = [
                'masp'      => $masp,
                'tensp'     => $tensp,
                'hinhanh'   => $hinhanh,
                'size'      => 'addon',
                'gia'       => $gia,
                'qty'       => $qty,
                'thanhtien' => $gia * $qty,
                'type'      => 'addon'
            ];

            continue;
        }

        // 2) Nếu không phải addon -> phải là product group: $value = [ size => [...], size2 => [...] ]
        if (is_array($value)) {
            foreach ($value as $size => $item) {
                // defensive: item phải là mảng chứa 'qty' hoặc 'price' hoặc 'giaXuat'
                if (!is_array($item)) continue;

                $masp = $item['masp'] ?? $key; // nếu item thiếu masp, fallback key
                // lấy thông tin sản phẩm từ DB nếu thiếu tensp/hinhanh
                $sp = $model->find("tblsanpham", $masp);
                $tensp = $item['tensp'] ?? ($sp['tensp'] ?? '');
                $hinhanh = $item['hinhanh'] ?? ($sp['hinhanh'] ?? '');

                // giá: ưu tiên price (session), giaXuat, fallback sp.gia
                if (isset($item['price'])) {
                    $price = (float)$item['price'];
                } elseif (isset($item['giaXuat'])) {
                    $price = (float)$item['giaXuat'];
                } else {
                    // cố gắng lấy từ bảng size (nếu size là string)
                    $row = $model->select(
                        "SELECT giaXuat FROM tbl_sanpham_size WHERE masp=? AND size=? LIMIT 1",
                        [$masp, $size]
                    );
                    $price = !empty($row) && isset($row[0]['giaXuat']) ? (float)$row[0]['giaXuat'] : (float)($sp['gia'] ?? 0);
                }

                $qty = isset($item['qty']) ? (int)$item['qty'] : 1;

                $listProductOrder[] = [
                    'masp'      => $masp,
                    'tensp'     => $tensp,
                    'hinhanh'   => $hinhanh,
                    'size'      => $size,
                    'gia'       => $price,
                    'qty'       => $qty,
                    'thanhtien' => $price * $qty,
                    'type'      => 'product'
                ];
            }
        }
    }

    // lấy phụ kiện để show ở dưới (nếu cần)
    $phuKien = $this->getPhuKienForOrder();

    // GỌI ĐÚNG VIEW (tên file view của mày là Font_end/OrderView)
    $this->view("Font_end/OrderView", [
        "listProductOrder" => $listProductOrder,
        "phuKien" => $phuKien
    ]);
}

public function addAddon()
{
    if (!isset($_POST['masp'])) {
        echo 'missing masp';
        return;
    }

    $masp = (int)$_POST['masp'];
    $model = $this->model('AdProducModel');

    $p = $model->getAddonPrice($masp);
    if (!$p) {
        echo 'addon not found';
        return;
    }

    $key = 'addon_' . $masp;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // ✅ nếu đã có → tăng
    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['qty']++;
    } else {
        $_SESSION['cart'][$key] = [
            'masp'    => $masp,
            'tensp'   => $p['tensp'],
            'hinhanh' => $p['hinhanh'],
            'gia'     => (int)$p['giaXuat'], // ✅ GIÁ ĐÚNG
            'qty'     => 1,
            'type'    => 'addon'
        ];
    }

    echo 'ok';
}



public function updateAddon()
{
    if (!isset($_POST['addon_qty'])) return;

    foreach ($_POST['addon_qty'] as $masp => $qty) {
        $key = 'addon_' . (int)$masp;

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty'] = max(1, (int)$qty);
        }
    }

    header("Location: " . APP_URL . "/Home/order");
}

public function removeAddon($masp)
{
    $key = 'addon_' . (int)$masp;

    unset($_SESSION['cart'][$key]);

    header("Location: " . APP_URL . "/Home/order");
}


    public function checkout() {

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: " . APP_URL . "/Home/order");
        exit();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: " . APP_URL . "/AuthController/ShowLogin");
        exit();
    }

    $cartSession = $_SESSION['cart'];
    $productModel = $this->model("ProductModel");

    $cart = [];
    $total = 0;

    foreach ($cartSession as $item) {

        // LẤY LẠI GIÁ & TÊN TỪ DB
        $product = $productModel->getById($item['masp']);

        if (!$product) continue;

        $price = (float)$product['gia'];
        $qty   = (int)$item['qty'];
        $lineTotal = $price * $qty;

        $total += $lineTotal;

        $cart[] = [
            'masp'  => $item['masp'],
            'tensp'=> $product['tensp'],
            'gia'   => $price,
            'qty'   => $qty
        ];
    }

    $promotionModel = $this->model("PromotionModel");
        $this->view("homePage", [
        "page" => "CheckoutInfoView",
        "listProductOrder" => $cart,
        "total" => $total
    ]);
}


    public function checkoutSave() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/Show');
            exit();
        }
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }
        $receiver = isset($_POST['receiver']) ? trim($_POST['receiver']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        if ($receiver === '' || $phone === '' || $address === '') {
            echo '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin giao hàng!</div>';
            $this->view("homePage", ["page" => "CheckoutInfoView"]);
            return;
        }
    $orderModel = $this->model("OrderModel");
    $promotionModel = $this->model("PromotionModel");
        $orderDetailModel =$this->model("OrderDetailModel");
        $user = $_SESSION['user'];
        $orderCode = 'HD' . time();
        $transaction_info="chothanhtoan";
        $created_at = date('Y-m-d H:i:s');
        $totalAmount = 0;
        foreach ($cart as $item) {
            $thanhtien = ($item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100)) * $item['qty'];
            $totalAmount += $thanhtien;
        }
        // Check for coupon code
        $coupon_code = isset($_POST['coupon_code']) ? trim($_POST['coupon_code']) : null;
        $discount_amount = 0;
        if ($coupon_code) {
            $validation = $promotionModel->validateCode($coupon_code, $totalAmount);
            if (!$validation['success']) {
                // show checkout page again with message
                $this->view("homePage", ["page" => "CheckoutInfoView", 'coupon_message' => $validation['message']]);
                return;
            }
            $discount_amount = $validation['discount_amount'];
            // Optionally increment usage now (or after successful payment). We'll increment after creating order to reserve it.
        }
        // Lưu đơn hàng, bổ sung thông tin giao hàng
    $orderId = $orderModel->createOrderWithShipping($orderCode, $totalAmount,$user['email'], $receiver, $phone, $address,$created_at,$transaction_info, $coupon_code, $discount_amount);
       //  $tongtien=0;
        foreach ($cart as $item) {
            $thanhtien = ($item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100)) * $item['qty'];
          //  $tongtien += $thanhtien;
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100),
                $thanhtien,
                $item['hinhanh'],
                //  '', // loại sp nếu có
                $item['tensp']
            );
        }
    $_SESSION['orderCode'] = $orderCode; //mã hóa đơn
    $_SESSION['totalAmount']= $totalAmount - $discount_amount; //tổng tiền thanh toán của cả đơn hàng (sau giảm)
         // Xóa giỏ hàng sau khi đặt hàng thành công
        $_SESSION['cart'] = [];
        $payment_method=$_POST['payment_method'];
        if($payment_method=='vnpay'){
            header('Location: ' . APP_URL . '/vnpay_php/vnpay_pay.php');
            exit();
        }
        elseif($payment_method=='cod'){
            $this->view("Font_end/OrderView", [
            "listProductOrder" => [],
            "success" => "Đặt hàng thành công! Mã hóa đơn: $orderCode"
        ]);
        }
    }  

        // Xử lý khi VNPAY redirect về
        public function vnpayReturn() {
            // Lấy tất cả params VNPAY trả về
            $data = $_GET;
            //$vnp_HashSecret = defined('VNP_HASH_SECRET') ? VNP_HASH_SECRET : '';
            $vnp_HashSecret = "QK4ZU6CQVZ4BLPP9ZJMDJFY9I59F9TXK";
            if (isset($data['vnp_SecureHash'])) {
                $secureHash = $data['vnp_SecureHash'];
                unset($data['vnp_SecureHash']);
                unset($data['vnp_SecureHashType']);
                ksort($data);
                $hashData = '';
                foreach ($data as $key => $value) {
                    if (($key !== 'vnp_SecureHash') && ($key !== 'vnp_SecureHashType')) {
                        $hashData .= $key . '=' . $value . '&';
                    }
                }
                $hashData = rtrim($hashData, '&');
                $calculatedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

                if ($calculatedHash === $secureHash) {
                    // signature ok -> kiểm tra mã trả về
                    $vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
                    $vnp_TxnRef = isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '';

                    if ($vnp_ResponseCode === '00') {
                        // Thanh toán thành công
                        // Update received amount and status
                        $paidAmount = isset($_GET['vnp_Amount']) ? ($_GET['vnp_Amount'] / 100) : 0;
                        $orderModel = new OrderModel();
                        $orderModel->updateReceivedAmountAndStatus($vnp_TxnRef, $paidAmount);

                        // If order had a coupon_code, increment promotion usage
                        $order = $orderModel->select("SELECT * FROM orders WHERE order_code = ?", [$vnp_TxnRef]);
                        if (!empty($order) && !empty($order[0]['coupon_code'])) {
                            $promoModel = $this->model('PromotionModel');
                            $promo = $promoModel->getByCode($order[0]['coupon_code']);
                            if ($promo && !empty($promo['id'])) {
                                $promoModel->incrementUsage($promo['id']);
                            }
                        }

                        $message = "Thanh toán VNPAY thành công. Mã đơn: $vnp_TxnRef";
                    } else {
                        $message = "Thanh toán VNPAY không thành công. Mã trả về: " . htmlspecialchars($vnp_ResponseCode);
                    }
                } else {
                    $message = 'Chu ky khong hop le.';
                }
            } else {
                $message = 'Tham so chua duoc truyen.';
            }

            $this->view("Font_end/OrderView", [
            "listProductOrder" => [],
            "success" => $message
        ]);

        }

        // Hiển thị form nhập thông tin giao hàng sau khi đăng ký hoặc đăng nhập
        public function checkoutInfo()
{
    if (!isset($_SESSION['user'])) {
        header('Location: ' . APP_URL . '/AuthController/ShowLogin');
        exit();
    }

    if (empty($_SESSION['cart'])) {
        header("Location: " . APP_URL . "/Home/order");
        exit();
    }

    $model = $this->model("AdProducModel"); // kiểm tra tên model đúng với file bạn có
    $listProductOrder = [];
    $total = 0;

    foreach ($_SESSION['cart'] as $k => $entry) {

        // nếu entry không phải mảng => skip
        if (!is_array($entry)) continue;

        // --------- CASE A: entry là 1 item (associative item with 'masp' or 'size') ----------
        // ví dụ: $_SESSION['cart'][] = ['masp'=>..., 'size'=>..., 'price'=>..., 'qty'=>...]
        if (isset($entry['masp']) || isset($entry['size'])) {
            $masp = $entry['masp'] ?? ($entry['product_id'] ?? $k);
            $size = $entry['size'] ?? ($entry['size_name'] ?? '');
            $price = $this->getPriceFromItemOrDb($model, $masp, $size, $entry);
            $qty = isset($entry['qty']) ? (int)$entry['qty'] : 1;
            $thanhTien = $price * $qty;
            $total += $thanhTien;

            $listProductOrder[] = [
                'masp'     => $masp,
                'tensp'    => $entry['tensp'] ?? ($model->find('tblsanpham', $masp)['tensp'] ?? ''),
                'hinhanh'  => $entry['hinhanh'] ?? ($model->find('tblsanpham', $masp)['hinhanh'] ?? ''),
                'size'     => $size,
                'gia'      => $price,
                'qty'      => $qty,
                'thanhtien'=> $thanhTien
            ];
            continue;
        }

        // --------- CASE B: entry là nhóm sizes cho 1 masp ----------
        // ví dụ: $_SESSION['cart'][$masp] = [ '13x6cm' => item, '17x7.5cm' => item, ... ]
        foreach ($entry as $maybeSize => $maybeItem) {
            if (!is_array($maybeItem)) continue;

            $masp = $maybeItem['masp'] ?? $k; // fallback: key $k là masp
            $size = $maybeItem['size'] ?? $maybeSize;
            $price = $this->getPriceFromItemOrDb($model, $masp, $size, $maybeItem);
            $qty = isset($maybeItem['qty']) ? (int)$maybeItem['qty'] : 1;
            $thanhTien = $price * $qty;
            $total += $thanhTien;

            $listProductOrder[] = [
                'masp'     => $masp,
                'tensp'    => $maybeItem['tensp'] ?? ($model->find('tblsanpham', $masp)['tensp'] ?? ''),
                'hinhanh'  => $maybeItem['hinhanh'] ?? ($model->find('tblsanpham', $masp)['hinhanh'] ?? ''),
                'size'     => $size,
                'gia'      => $price,
                'qty'      => $qty,
                'thanhtien'=> $thanhTien
            ];
        }
    }

    // Trả về view (CheckoutInfoView nằm trong layout checkoutLayout)
    $this->view("checkoutLayout", [
        "page" => "CheckoutInfoView",
        "listProductOrder" => $listProductOrder,
        "total" => $total,
        "vouchers" => $this->model('PromotionModel')->getAllActive()
    ]);
}


/**
 * Helper: lấy giá (ưu tiên từ item), nếu không có -> query DB theo masp+size
 */
private function getPriceFromItemOrDb($productModel, $masp, $size, $item)
{
    // Kiểm tra các key thường gặp
    if (!empty($item['price'])) return (float)$item['price'];
    if (!empty($item['gia'])) return (float)$item['gia'];
    if (!empty($item['giaxuat'])) return (float)$item['giaxuat'];

    // Nếu không có giá trong session -> lấy từ bảng size (nếu có size)
    if (!empty($size)) {
        $r = $productModel->select("SELECT giaXuat FROM tbl_sanpham_size WHERE masp=? AND size=? LIMIT 1", [$masp, $size]);
        if (!empty($r) && isset($r[0]['giaXuat'])) return (float)$r[0]['giaXuat'];
    }

    // Fallback: lấy giá mặc định của sản phẩm (nếu có)
    $sp = $productModel->find("tblsanpham", $masp);
    if (!empty($sp) && isset($sp['gia'])) return (float)$sp['gia'];

    return 0.0;
}

/**
 * Helper: kiểm tra xem mảng có phải associative hay là list numeric-index
 */
private function is_assoc(array $arr)
{
    if ([] === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}



        public function vnpayPay() {
            if (!isset($_POST['order_code']) || !isset($_POST['amount'])) {
                header('Location: ' . APP_URL . '/Home');
                exit();
            }

            $orderCode = $_POST['order_code'];
            $amount = $_POST['amount'];

            // Store in session for vnpay processing
            $_SESSION['orderCode'] = $orderCode;
            $_SESSION['totalAmount'] = $amount;

            // Redirect to VNPAY payment page
            header('Location: ' . APP_URL . '/vnpay_php/vnpay_pay.php');
            exit();
        }
    public function index() {
    $this->show();
}
    public function placeOrder()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . APP_URL);
        exit;
    }

    // Nếu không cần bắt đăng nhập: bỏ phần này. Nhưng hiện bạn bắt đăng nhập:
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vui lòng đăng nhập để đặt hàng";
        header("Location: " . APP_URL . "/AuthController/ShowLogin");
        exit;
    }

    // Lấy user_id an toàn: ưu tiên session, fallback lookup bằng email
    $userId = $_SESSION['user']['user_id'] 
           ?? $_SESSION['user']['id'] 
           ?? null;

    if ($userId === null && !empty($_SESSION['user']['email'])) {
        // lookup trong DB để lấy user_id (dự phòng)
        $userModel = $this->model('UserModel');
        $row = $userModel->findByEmail($_SESSION['user']['email']);
        if ($row) {
            $userId = $row['user_id'] ?? $row['id'] ?? null;
            // cập nhật session để lần sau khỏi lookup
            $_SESSION['user']['user_id'] = $userId;
        }
    }

    // nếu vẫn null tuỳ bạn: cho phép NULL (guest order) hoặc bắt login.
    if ($userId === null) {
        // Option A: ép bắt login
        $_SESSION['error'] = "Không xác định được user. Vui lòng đăng nhập lại.";
        header("Location: " . APP_URL . "/AuthController/ShowLogin");
        exit;

        // Option B (nếu muốn cho guest order): comment đoạn trên và set $userId = null; 
        // Nhưng DB hiện user_id NOT NULL → cần phải thay cấu trúc DB để allow NULL.
    }

    if (empty($_SESSION['cart'])) {
        $_SESSION['error'] = "Giỏ hàng trống";
        header("Location: " . APP_URL . "/Home/order");
        exit;
    }

    // Lấy dữ liệu từ form
    $orderName     = trim($_POST['order_name'] ?? '');
    $orderPhone    = trim($_POST['order_phone'] ?? '');
    $receiver      = trim($_POST['receiver_name'] ?: $orderName);
    $receiverPhone = trim($_POST['receiver_phone'] ?: $orderPhone);
    $payment       = $_POST['payment'] ?? 'cod';
    $voucherCode   = $_POST['voucher_code'] ?? null;

    $discount = (float)($_POST['discount_amount'] ?? 0);
    $shipFee  = (float)($_POST['ship_fee'] ?? 0);
    $final    = (float)($_POST['final_amount'] ?? 0);

    $addressParts = [];
    if (!empty($_POST['address'])) $addressParts[] = trim($_POST['address']);
    if (!empty($_POST['ward']))    $addressParts[] = trim($_POST['ward']);
    if (!empty($_POST['district']))$addressParts[] = trim($_POST['district']);
    $address = implode(', ', $addressParts);

    $orderCode = 'HD' . time();
    
    // Xác định trạng thái thanh toán dựa trên phương thức
    // bank_before: chờ thanh toán (sẽ redirect VNPay)
    // bank_after: chờ thanh toán (thanh toán sau)
    // cod: chờ thanh toán (tiền mặt)
    $transaction = 'chothanhtoan';

    // LẤY PHƯƠNG THỨC GIAO HÀNG
    $deliveryMethod = $_POST['delivery_method'] ?? 'home';

    // LẤY PHƯƠNG THỨC THANH TOÁN
    $paymentMethod = $_POST['payment'] ?? 'cod';

    $orderModel = $this->model('OrderModel');

    $orderData = [
    'user_id' => $userId,
    'user_email' => $_SESSION['user']['email'] ?? null,
    'order_code' => $orderCode,
    'receiver' => $receiver,
    'phone' => $receiverPhone,
    'address' => $address,
    'delivery_method' => $deliveryMethod,   
    'payment_method' => $paymentMethod,     
    'total_amount' => $final,
    'discount_amount' => $discount,
    'coupon_code' => $voucherCode,
    'transaction_info' => $transaction,
    'note' => $_POST['note'] ?? null
];

    $orderId = $orderModel->createOrder($orderData);

    if (!$orderId) {
        $_SESSION['error'] = "Không thể tạo đơn hàng! Thử lại.";
        header("Location: " . APP_URL . "/Home/order");
        exit;
    }

    // Lưu chi tiết đơn hàng và trừ kho
    $productModel = $this->model('AdProducModel');
    
    foreach ($_SESSION['cart'] as $productId => $sizes) {
        foreach ($sizes as $size => $item) {
            $qty = (int)($item['qty'] ?? 0);
            
            // Lấy giá từ bảng tbl_sanpham_size
            $sizeInfo = $productModel->select(
                "SELECT giaXuat FROM tbl_sanpham_size WHERE masp = ? AND size = ?",
                [$productId, $size]
            );
            $price = $sizeInfo[0]['giaXuat'] ?? $item['price'] ?? $item['gia'] ?? 0;
            
            // Lưu chi tiết đơn hàng
            $orderModel->insertOrderDetail([
                'order_id' => $orderId,
                'product_id' => $productId,
                'size' => $size,
                'quantity' => $qty,
                'price' => (float)$price
            ]);
            
            // Trừ số lượng tồn kho trong bảng tblsanpham
            $productModel->query(
                "UPDATE tblsanpham SET soluong = GREATEST(0, soluong - ?) WHERE masp = ?",
                [$qty, $productId]
            );
        }
    }

    // Gửi email xác nhận đơn hàng
    $userEmail = $_SESSION['user']['email'] ?? null;
    if ($userEmail) {
        $orderDetails = $orderModel->getOrderDetailsByOrderId($orderId);
        $orderInfo = [
            'order_code' => $orderCode,
            'receiver' => $receiver,
            'phone' => $receiverPhone,
            'address' => $address,
            'total_amount' => $final,
            'discount_amount' => $discount,
            'payment_method' => $paymentMethod,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->sendOrderEmail($userEmail, $orderInfo, $orderDetails);
    }

    unset($_SESSION['cart']);
    
    // Nếu chọn "chuyển khoản trước" -> redirect sang VNPay ngay
    if ($paymentMethod === 'bank_before') {
        $_SESSION['orderCode'] = $orderCode;
        $_SESSION['totalAmount'] = $final;
        header('Location: ' . APP_URL . '/vnpay_php/vnpay_pay.php');
        exit;
    }
    
    // Các phương thức khác -> về trang lịch sử đơn hàng
    $_SESSION['success'] = "Đặt hàng thành công! Mã đơn: $orderCode";
    header("Location: " . APP_URL . "/Home/orderHistory");
    exit;
}

    // ================= ĐÁNH GIÁ SẢN PHẨM =================
    
    // Hiển thị danh sách sản phẩm để đánh giá
    public function reviewList() {
        $productModel = $this->model('AdProducModel');
        $reviewModel = $this->model('ReviewModel');
        
        // Lấy tất cả sản phẩm
        $products = $productModel->select("SELECT * FROM tblsanpham ORDER BY tensp");
        
        // Thêm thống kê đánh giá cho mỗi sản phẩm
        foreach ($products as &$product) {
            $stats = $reviewModel->getProductStats($product['masp']);
            $product['avg_rating'] = $stats['avg_rating'] ?? 0;
            $product['total_reviews'] = $stats['total_reviews'] ?? 0;
        }
        
        $this->view('Font_end/ReviewProductListView', ['products' => $products]);
    }
    
    // Hiển thị form đánh giá sản phẩm
    public function reviewProduct($masp) {
        $productModel = $this->model('AdProducModel');
        $reviewModel = $this->model('ReviewModel');
        
        // Lấy thông tin sản phẩm
        $product = $productModel->find('tblsanpham', $masp);
        if (!$product) {
            header('Location: ' . APP_URL . '/Home/reviewList');
            exit();
        }
        
        // Lấy đánh giá đã duyệt
        $reviews = $reviewModel->getByProduct($masp);
        
        // Lấy thống kê
        $stats = $reviewModel->getProductStats($masp);
        
        $this->view('Font_end/ReviewFormView', [
            'product' => $product,
            'reviews' => $reviews,
            'stats' => $stats
        ]);
    }
    
    // Xử lý gửi đánh giá
    public function submitReview() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Home/reviewList');
            exit();
        }
        
        if (!isset($_SESSION['user'])) {
            $_SESSION['review_error'] = 'Vui lòng đăng nhập để gửi đánh giá';
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        
        $productId = $_POST['product_id'] ?? '';
        $rating = (int)($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');
        
        // Validate
        if (empty($productId) || $rating < 1 || $rating > 5) {
            $_SESSION['review_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . APP_URL . '/Home/reviewProduct/' . $productId);
            exit();
        }
        
        // Upload ảnh nếu có
        $imageName = null;
        if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/images/reviews/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $ext = pathinfo($_FILES['review_image']['name'], PATHINFO_EXTENSION);
            $imageName = 'review_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['review_image']['tmp_name'], $uploadDir . $imageName);
        }
        
        // Lưu đánh giá
        $reviewModel = $this->model('ReviewModel');
        $reviewModel->addReview([
            'user_id' => $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'],
            'user_name' => $_SESSION['user']['fullname'],
            'user_email' => $_SESSION['user']['email'],
            'product_id' => $productId,
            'rating' => $rating,
            'comment' => $comment,
            'image' => $imageName
        ]);
        
        $_SESSION['review_success'] = 'Cảm ơn bạn đã gửi đánh giá! Đánh giá sẽ được hiển thị sau khi được duyệt.';
        header('Location: ' . APP_URL . '/Home/reviewProduct/' . $productId);
        exit();
    }
    
    // ================= GỬI EMAIL XÁC NHẬN ĐƠN HÀNG =================
    private function sendOrderEmail($toEmail, $orderInfo, $orderDetails) {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
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
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = "Xác nhận đơn hàng #{$orderInfo['order_code']} - Bánh Kem Shop";
            
            // Tạo danh sách sản phẩm
            $itemsHtml = '';
            $productModel = $this->model('AdProducModel');
            foreach ($orderDetails as $item) {
                // Lấy tên sản phẩm
                $product = $productModel->select("SELECT tensp FROM tblsanpham WHERE masp = ?", [$item['product_id']]);
                $productName = $product[0]['tensp'] ?? 'Sản phẩm';
                
                // Lấy giá từ bảng tbl_sanpham_size
                $sizeInfo = $productModel->select(
                    "SELECT giaXuat FROM tbl_sanpham_size WHERE masp = ? AND size = ?", 
                    [$item['product_id'], $item['size']]
                );
                $price = $sizeInfo[0]['giaXuat'] ?? $item['price'] ?? 0;
                $subtotal = $price * $item['quantity'];
                
                $itemsHtml .= "<tr>
                    <td style='padding:12px; border-bottom:1px solid #eee;'>{$productName} ({$item['size']})</td>
                    <td style='padding:12px; border-bottom:1px solid #eee; text-align:center;'>{$item['quantity']}</td>
                    <td style='padding:12px; border-bottom:1px solid #eee; text-align:right;'>" . number_format($price, 0, ',', '.') . " ₫</td>
                    <td style='padding:12px; border-bottom:1px solid #eee; text-align:right;'>" . number_format($subtotal, 0, ',', '.') . " ₫</td>
                </tr>";
            }
            
            // Phương thức thanh toán
            $paymentText = match($orderInfo['payment_method'] ?? 'cod') {
                'bank_before' => 'Chuyển khoản trước (VNPay)',
                'bank_after' => 'Chuyển khoản sau khi nhận hàng',
                default => 'Thanh toán tiền mặt khi nhận hàng'
            };
            
            $mail->Body = "
            <div style='font-family:Arial,sans-serif; max-width:600px; margin:0 auto; background:#fff;'>
                <div style='background:linear-gradient(135deg, #6fa05f 0%, #4a8c3a 100%); padding:25px; text-align:center;'>
                    <h1 style='color:#fff; margin:0; font-size:28px;'>🎂 Bánh Kem Shop</h1>
                </div>
                
                <div style='padding:30px;'>
                    <h2 style='color:#2b7a37; margin-top:0;'>✅ Đặt hàng thành công!</h2>
                    <p style='color:#555; font-size:15px;'>Xin chào <strong>{$orderInfo['receiver']}</strong>,</p>
                    <p style='color:#555; font-size:15px;'>Cảm ơn bạn đã đặt hàng tại Bánh Kem Shop. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
                    
                    <div style='background:#f8f9fa; border-radius:10px; padding:20px; margin:25px 0;'>
                        <h3 style='color:#333; margin-top:0; border-bottom:2px solid #2b7a37; padding-bottom:10px;'>📦 Thông tin đơn hàng</h3>
                        <table style='width:100%; font-size:14px;'>
                            <tr><td style='padding:8px 0; color:#666;'>Mã đơn hàng:</td><td style='padding:8px 0;'><strong style='color:#2b7a37;'>{$orderInfo['order_code']}</strong></td></tr>
                            <tr><td style='padding:8px 0; color:#666;'>Ngày đặt:</td><td style='padding:8px 0;'>{$orderInfo['created_at']}</td></tr>
                            <tr><td style='padding:8px 0; color:#666;'>Người nhận:</td><td style='padding:8px 0;'>{$orderInfo['receiver']}</td></tr>
                            <tr><td style='padding:8px 0; color:#666;'>Số điện thoại:</td><td style='padding:8px 0;'>{$orderInfo['phone']}</td></tr>
                            <tr><td style='padding:8px 0; color:#666;'>Địa chỉ:</td><td style='padding:8px 0;'>{$orderInfo['address']}</td></tr>
                            <tr><td style='padding:8px 0; color:#666;'>Thanh toán:</td><td style='padding:8px 0;'>{$paymentText}</td></tr>
                        </table>
                    </div>
                    
                    <div style='margin:25px 0;'>
                        <h3 style='color:#333; border-bottom:2px solid #2b7a37; padding-bottom:10px;'>🛒 Chi tiết sản phẩm</h3>
                        <table style='width:100%; border-collapse:collapse; font-size:14px;'>
                            <thead>
                                <tr style='background:#f0f7ef;'>
                                    <th style='padding:12px; text-align:left;'>Sản phẩm</th>
                                    <th style='padding:12px; text-align:center;'>SL</th>
                                    <th style='padding:12px; text-align:right;'>Đơn giá</th>
                                    <th style='padding:12px; text-align:right;'>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>{$itemsHtml}</tbody>
                        </table>
                    </div>
                    
                    <div style='background:#d4edda; border-radius:10px; padding:20px; margin:25px 0;'>
                        <table style='width:100%; font-size:15px;'>
                            <tr><td style='padding:5px 0;'>Giảm giá:</td><td style='text-align:right;'>-" . number_format($orderInfo['discount_amount'], 0, ',', '.') . " ₫</td></tr>
                            <tr><td style='padding:10px 0; font-size:18px;'><strong>Tổng thanh toán:</strong></td><td style='text-align:right; font-size:20px;'><strong style='color:#e74c3c;'>" . number_format($orderInfo['total_amount'], 0, ',', '.') . " ₫</strong></td></tr>
                        </table>
                    </div>
                    
                    <p style='color:#666; font-size:14px;'>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
                    <p style='color:#666; font-size:14px;'>Trân trọng,<br><strong style='color:#2b7a37;'>Bánh Kem Shop</strong></p>
                </div>
                
                <div style='background:#333; padding:20px; text-align:center;'>
                    <p style='color:#fff; margin:0; font-size:13px;'>© 2025 Bánh Kem Shop - Website Bán Bánh Kem</p>
                </div>
            </div>";

            $mail->send();
            return true;
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            error_log("Send email error: " . $e->getMessage());
            return false;
        }
    }
}

