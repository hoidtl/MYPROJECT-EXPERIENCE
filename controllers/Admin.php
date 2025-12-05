<?php
class Admin extends Controller{
    function show(){
        $obj=$this->model("AdProductTypeModel");
        $data=$obj->all("tblloaisp");
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data]);
    }

    // Promotions CRUD
    public function promotionList() {
        $promotionModel = $this->model('PromotionModel');
        $promotions = $promotionModel->select("SELECT * FROM promotions ORDER BY created_at DESC");
        $this->view('adminPage', ['page' => 'PromotionList', 'promotions' => $promotions]);
    }

    public function promotionCreate() {
        $this->view('adminPage', ['page' => 'PromotionForm', 'method' => 'create']);
    }

    public function promotionStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/promotionList');
            exit();
        }
        $code = trim($_POST['code'] ?? '');
        $type = $_POST['type'] ?? 'fixed';
        $value = $_POST['value'] ?? 0;
        $min_order_amount = $_POST['min_order_amount'] ?: 0;
        $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $status = $_POST['status'] ?? 'active';
        $usage_limit = $_POST['usage_limit'] ?: null;

        $promotionModel = $this->model('PromotionModel');
        $sql = "INSERT INTO promotions (code,type,value,min_order_amount,start_date,end_date,status,usage_limit,created_at) VALUES (:code,:type,:value,:min_order_amount,:start_date,:end_date,:status,:usage_limit,NOW())";
        $promotionModel->query($sql, [
            ':code' => $code,
            ':type' => $type,
            ':value' => $value,
            ':min_order_amount' => $min_order_amount,
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':status' => $status,
            ':usage_limit' => $usage_limit
        ]);
        header('Location: ' . APP_URL . '/Admin/promotionList');
        exit();
    }

    public function promotionEdit($id) {
        $promotionModel = $this->model('PromotionModel');
        $promo = $promotionModel->select("SELECT * FROM promotions WHERE id = ? LIMIT 1", [$id]);
        if (empty($promo)) {
            header('Location: ' . APP_URL . '/Admin/promotionList');
            exit();
        }
        $this->view('adminPage', ['page' => 'PromotionForm', 'method' => 'edit', 'promotion' => $promo[0]]);
    }

    public function promotionUpdate($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/promotionList');
            exit();
        }
        $sql = "UPDATE promotions SET code=:code,type=:type,value=:value,min_order_amount=:min_order_amount,start_date=:start_date,end_date=:end_date,status=:status,usage_limit=:usage_limit,updated_at=NOW() WHERE id=:id";
        $this->model('PromotionModel')->query($sql, [
            ':code' => $_POST['code'],
            ':type' => $_POST['type'],
            ':value' => $_POST['value'],
            ':min_order_amount' => $_POST['min_order_amount'] ?: 0,
            ':start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            ':end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            ':status' => $_POST['status'] ?? 'active',
            ':usage_limit' => $_POST['usage_limit'] ?: null,
            ':id' => $id
        ]);
        header('Location: ' . APP_URL . '/Admin/promotionList');
        exit();
    }

    public function promotionDelete($id) {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $this->model('PromotionModel')->query("DELETE FROM promotions WHERE id = ?", [$id]);
        header('Location: ' . APP_URL . '/Admin/promotionList');
        exit();
    }
    
    // ================= QUẢN LÝ ĐƠN HÀNG =================
    public function orderList() {
        $orderModel = $this->model('OrderModel');
        
        // Build query with filters
        $where = "1=1";
        $params = [];
        $statsWhere = "1=1";
        $statsParams = [];
        
        if (!empty($_GET['status'])) {
            $where .= " AND transaction_info = ?";
            $params[] = $_GET['status'];
        }
        
        if (!empty($_GET['from_date'])) {
            $where .= " AND DATE(created_at) >= ?";
            $params[] = $_GET['from_date'];
            $statsWhere .= " AND DATE(created_at) >= ?";
            $statsParams[] = $_GET['from_date'];
        }
        
        if (!empty($_GET['to_date'])) {
            $where .= " AND DATE(created_at) <= ?";
            $params[] = $_GET['to_date'];
            $statsWhere .= " AND DATE(created_at) <= ?";
            $statsParams[] = $_GET['to_date'];
        }
        
        $orders = $orderModel->select("SELECT * FROM orders WHERE $where ORDER BY created_at DESC", $params);
        
        // Calculate stats - dựa trên filter ngày
        $filteredOrders = $orderModel->select("SELECT * FROM orders WHERE $statsWhere", $statsParams);
        $stats = [
            'total' => count($filteredOrders),
            'pending' => 0,
            'completed' => 0,
            'revenue' => 0
        ];
        
        foreach ($filteredOrders as $o) {
            // Tính doanh thu = tổng received_amount (số tiền đã nhận được)
            $stats['revenue'] += (float)($o['received_amount'] ?? 0);
            
            if ($o['transaction_info'] == 'dathanhtoan') {
                $stats['completed']++;
            } elseif ($o['transaction_info'] == 'chothanhtoan') {
                $stats['pending']++;
            }
        }
        
        $this->view('adminPage', [
            'page' => 'OrderListView',
            'orders' => $orders,
            'stats' => $stats
        ]);
    }
    
    public function orderDetail($id) {
        $orderModel = $this->model('OrderModel');
        $orderDetailModel = $this->model('OrderDetailModel');
        
        $order = $orderModel->getOrderById($id);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . APP_URL . '/Admin/orderList');
            exit();
        }
        
        $details = $orderDetailModel->getByOrderId($id);
        
        $this->view('adminPage', [
            'page' => 'OrderDetailAdminView',
            'order' => $order,
            'details' => $details
        ]);
    }
    
    public function orderUpdateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            $orderModel = $this->model('OrderModel');
            $orderModel->query("UPDATE orders SET transaction_info = ? WHERE id = ?", [$status, $id]);
            $_SESSION['success'] = 'Cập nhật trạng thái thành công';
            header('Location: ' . APP_URL . '/Admin/orderList');
            exit();
        }
        
        // Show form
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);
        
        $this->view('adminPage', [
            'page' => 'OrderStatusForm',
            'order' => $order
        ]);
    }
    
    // ================= QUẢN LÝ KHO HÀNG =================
    public function inventory() {
        $productModel = $this->model('AdProducModel');
        $categoryModel = $this->model('AdProductTypeModel');
        
        // Get all products from tblsanpham
        $where = "1=1";
        $params = [];
        
        if (!empty($_GET['category'])) {
            $where .= " AND maLoaiSP = ?";
            $params[] = $_GET['category'];
        }
        
        if (!empty($_GET['search'])) {
            $where .= " AND tensp LIKE ?";
            $params[] = '%' . $_GET['search'] . '%';
        }
        
        $sql = "SELECT * FROM tblsanpham WHERE $where ORDER BY masp";
        $products = $productModel->select($sql, $params);
        
        // Filter by stock status
        if (!empty($_GET['stock_status'])) {
            $products = array_filter($products, function($p) {
                $stock = (int)($p['soluong'] ?? 0);
                switch ($_GET['stock_status']) {
                    case 'out_of_stock': return $stock <= 0;
                    case 'low_stock': return $stock > 0 && $stock <= 10;
                    case 'in_stock': return $stock > 10;
                    default: return true;
                }
            });
            $products = array_values($products); // Reset keys
        }
        
        // Calculate stats
        $allProducts = $productModel->select("SELECT soluong FROM tblsanpham");
        $stats = [
            'total_products' => count($allProducts),
            'in_stock' => 0,
            'low_stock' => 0,
            'out_of_stock' => 0
        ];
        
        foreach ($allProducts as $p) {
            $stock = (int)($p['soluong'] ?? 0);
            if ($stock <= 0) $stats['out_of_stock']++;
            elseif ($stock <= 10) $stats['low_stock']++;
            else $stats['in_stock']++;
        }
        
        $categories = $categoryModel->all('tblloaisp');
        
        $this->view('adminPage', [
            'page' => 'InventoryView',
            'products' => $products,
            'categories' => $categories,
            'stats' => $stats
        ]);
    }
    
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Admin/inventory');
            exit();
        }
        
        $masp = $_POST['masp'] ?? '';
        $action = $_POST['action'] ?? 'set';
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        $productModel = $this->model('AdProducModel');
        
        // Get current stock from tblsanpham
        $current = $productModel->select(
            "SELECT soluong FROM tblsanpham WHERE masp = ?",
            [$masp]
        );
        
        $currentStock = (int)($current[0]['soluong'] ?? 0);
        
        // Calculate new stock
        switch ($action) {
            case 'add':
                $newStock = $currentStock + $quantity;
                break;
            case 'subtract':
                $newStock = max(0, $currentStock - $quantity);
                break;
            default:
                $newStock = $quantity;
        }
        
        // Update stock in tblsanpham
        $productModel->query(
            "UPDATE tblsanpham SET soluong = ? WHERE masp = ?",
            [$newStock, $masp]
        );
        
        $_SESSION['success'] = "Cập nhật tồn kho thành công: $masp = $newStock";
        header('Location: ' . APP_URL . '/Admin/inventory');
        exit();
    }
    
    // ================= QUẢN LÝ ĐÁNH GIÁ =================
    public function reviewList() {
        $reviewModel = $this->model('ReviewModel');
        
        // Filter by status
        $status = $_GET['status'] ?? null;
        $reviews = $reviewModel->getAllReviews($status);
        
        // Filter by rating
        if (!empty($_GET['rating'])) {
            $rating = (int)$_GET['rating'];
            $reviews = array_filter($reviews, function($r) use ($rating) {
                return $r['rating'] == $rating;
            });
        }
        
        // Calculate stats
        $allReviews = $reviewModel->getAllReviews();
        $stats = [
            'total' => count($allReviews),
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0
        ];
        
        foreach ($allReviews as $r) {
            if ($r['status'] == 'pending') $stats['pending']++;
            elseif ($r['status'] == 'approved') $stats['approved']++;
            elseif ($r['status'] == 'rejected') $stats['rejected']++;
        }
        
        $this->view('adminPage', [
            'page' => 'ReviewListView',
            'reviews' => $reviews,
            'stats' => $stats
        ]);
    }
    
    public function reviewApprove($id) {
        $reviewModel = $this->model('ReviewModel');
        $reviewModel->updateStatus($id, 'approved');
        $_SESSION['success'] = 'Đã duyệt đánh giá';
        header('Location: ' . APP_URL . '/Admin/reviewList');
        exit();
    }
    
    public function reviewReject($id) {
        $reviewModel = $this->model('ReviewModel');
        $reviewModel->updateStatus($id, 'rejected');
        $_SESSION['success'] = 'Đã từ chối đánh giá';
        header('Location: ' . APP_URL . '/Admin/reviewList');
        exit();
    }
    
    public function reviewDelete($id) {
        $reviewModel = $this->model('ReviewModel');
        
        // Xóa ảnh nếu có
        $review = $reviewModel->getById($id);
        if ($review && $review['image']) {
            $imagePath = 'public/images/reviews/' . $review['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $reviewModel->deleteReview($id);
        $_SESSION['success'] = 'Đã xóa đánh giá';
        header('Location: ' . APP_URL . '/Admin/reviewList');
        exit();
    }
    
    // ================= QUẢN LÝ NGƯỜI DÙNG =================
    public function userList() {
        $userModel = $this->model('UserModel');
        $users = $userModel->getAllUsers();
        
        $stats = [
            'total' => count($users),
            'admins' => 0,
            'users' => 0
        ];
        
        foreach ($users as $u) {
            if (($u['role'] ?? '') === 'admin') {
                $stats['admins']++;
            } else {
                $stats['users']++;
            }
        }
        
        $this->view('adminPage', [
            'page' => 'UserListView',
            'users' => $users,
            'stats' => $stats
        ]);
    }
    
    public function userSetAdmin($userId) {
        $userModel = $this->model('UserModel');
        $userModel->updateRole($userId, 'admin');
        $_SESSION['success'] = 'Đã cấp quyền Admin cho người dùng';
        header('Location: ' . APP_URL . '/Admin/userList');
        exit();
    }
    
    public function userRemoveAdmin($userId) {
        $userModel = $this->model('UserModel');
        $userModel->updateRole($userId, 'user');
        $_SESSION['success'] = 'Đã thu hồi quyền Admin';
        header('Location: ' . APP_URL . '/Admin/userList');
        exit();
    }
    
    public function userDelete($userId) {
        // Không cho xóa chính mình
        if ($userId == ($_SESSION['user']['user_id'] ?? 0)) {
            $_SESSION['error'] = 'Không thể xóa tài khoản của chính mình';
            header('Location: ' . APP_URL . '/Admin/userList');
            exit();
        }
        
        $userModel = $this->model('UserModel');
        $userModel->deleteUser($userId);
        $_SESSION['success'] = 'Đã xóa người dùng';
        header('Location: ' . APP_URL . '/Admin/userList');
        exit();
    }
}
