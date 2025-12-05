    
<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Controller.php';

use PHPMailer\PHPMailer\PHPMailer;

//session_start();

class AuthController extends Controller {
    // Hiển thị form đăng ký
    //http://localhost/MVC3/AuthController/Show
    public function Show() {
        $this->view("homePage",["page"=>"RegisterView"]);
    }
    // Xử lý đăng ký, gửi OTP
    
    // Hiển thị form đăng ký
    public function ShowRegister() {
        $this->view("Font_end/RegisterView");
    }

    // Xử lý đăng ký + gửi OTP
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if ($fullname === '' || $email === '' || $password === '') {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            header('Location: ' . APP_URL . '/AuthController/ShowRegister');
            exit();
        }

        // Kiểm tra email
        $user = $this->model('UserModel');
        if ($user->emailExists($email)) {
            $_SESSION['error'] = "Email đã tồn tại!";
            header('Location: ' . APP_URL . '/AuthController/ShowRegister');
            exit();
        }

        // Tạo OTP & lưu tạm vào session
        $otp = rand(100000, 999999);
        $_SESSION['register'] = [
            'fullname' => $fullname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'otp' => $otp,
            'otp_time' => time()
        ];

        // Gửi OTP
        $this->sendOtpEmail($email, $otp);

        // Chuyển sang form OTP
        header('Location: ' . APP_URL . '/AuthController/ShowverifyOtp');
        exit();
    }

    // Hiển thị form nhập OTP
    public function ShowverifyOtp() {
        $this->view("Font_end/OtpView");
    }

    // Xử lý xác thực OTP
    public function verifyOtp() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    if (!isset($_SESSION['register'])) {
        die("Không tìm thấy dữ liệu đăng ký!");
    }

    $input = $_POST['otp'];
    $data = $_SESSION['register'];

    // Check time 3 phút
    if (time() - $data['otp_time'] > 180) {
        unset($_SESSION['register']);
        die("OTP đã hết hạn. Vui lòng đăng ký lại.");
    }

    if ($input != $data['otp']) {
        $_SESSION['error'] = "Mã OTP không chính xác!";
        header('Location: ' . APP_URL . '/AuthController/ShowverifyOtp');
        exit();
    }

    // --- LƯU USER VÀO DB ---
    $user = $this->model('UserModel');
    $user->fullname = $data['fullname'];
    $user->email = $data['email'];
    $user->password = $data['password'];
    $user->token = null;

    $user->createUser($data['fullname'], $data['email'], $data['password']);


    unset($_SESSION['register']);

    header("Location: " . APP_URL . "/AuthController/ShowLogin");
    exit();
}


    // Gửi email OTP
    private function sendOtpEmail($email, $otp) {
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

            $mail->setFrom('chitogelovehoi@gmail.com', 'Your App');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Mã OTP xác thực đăng ký";
            $mail->Body = "Mã OTP của bạn là: <b>$otp</b> (Hiệu lực 3 phút)";

            $mail->send();
        } catch (Exception $e) {
            echo "Gửi email lỗi: {$mail->ErrorInfo}";
        }
    }

    public function dangnhap(){
        $this->view("homePage",["page"=>"LoginView"]);
    }
    // Hiển thị form đăng nhập
    public function ShowLogin() {
    // Nếu đã đăng nhập thì chuyển hướng thẳng về trang chủ hoặc đâu bạn muốn
    if (isset($_SESSION['user'])) {
        header("Location: " . APP_URL . "/Home");
        exit();
    }

    // Hiển thị trang LoginView TRỰC TIẾP (không nhúng vào homePage)
    $this->view("Font_end/LoginView");
}


        // Xử lý đăng nhập
    public function login() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . APP_URL . "/AuthController/ShowLogin");
        exit();
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $userModel = $this->model('UserModel');
    $user = $userModel->findByEmail($email);

    if (!$user) {
        $_SESSION['error'] = "Email không tồn tại!";
        header("Location: " . APP_URL . "/AuthController/ShowLogin");
        exit();
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Mật khẩu không đúng!";
        header("Location: " . APP_URL . "/AuthController/ShowLogin");
        exit();
    }

    // Lưu session CHÍNH XÁC CHO BẢNG tbluser
    $_SESSION['user'] = [
        'user_id'  => $user['user_id'],
        'email'    => $user['email'],
        'fullname' => $user['fullname'],
        'role'     => $user['role'] ?? 'user'
    ];

    // Nếu là admin thì redirect đến trang quản trị
    if (($user['role'] ?? '') === 'admin') {
        header("Location: " . APP_URL . "/Product");
        exit();
    }

    header("Location: " . APP_URL . "/Home");
    exit();
}



    // Đăng xuất
    public function logout() {
        session_destroy();
        header('Location: ' . APP_URL . '/Home');
        exit();
    }

    // Hiển thị form quên mật khẩu
    public function forgotPassword() {
        //$this->view("Font_end/ForgotPasswordView");
        $this->view("homePage",["page"=>"ForgotPasswordView"]);
    }
    public function ShowForgotPassword() {
        // Load trang đăng ký riêng biệt, không dùng layout homePage
        $this->view("Font_end/ForgotPasswordView");
    }

    // Xử lý gửi lại mật khẩu mới qua email
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $userModel = $this->model('UserModel');
            $user = $userModel->findByEmail($email);
            if ($user) {
                $newPass = substr(bin2hex(random_bytes(4)), 0, 8);
                $userModel->updatePassword($email, password_hash($newPass, PASSWORD_DEFAULT));
                $this->sendNewPasswordEmail($email, $newPass);
                echo '<div class="container mt-5"><div class="alert alert-success">Mật khẩu mới đã được gửi về email của bạn!</div></div>';
            } else {
                echo '<div class="container mt-5"><div class="alert alert-danger">Email không tồn tại!</div></div>';
            }
            //$this->view("Font_end/ForgotPasswordView");
             $this->view("homePage",["page"=>"ForgotPasswordView"]);
            
        }
    }

    // Gửi mật khẩu mới qua email
    private function sendNewPasswordEmail($email, $newPass) {
        $mail = new PHPMailer(true);
        try {
            // Cấu hình để gửi tiếng Việt không lỗi
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "base64";
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'chitogelovehoi@gmail.com';
            $mail->Password = 'mkur ygbo jbyz xtwi';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('chitogelovehoi@gmail.com', 'Your App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Mật khẩu mới cho tài khoản của bạn";
            $mail->Body = "Mật khẩu mới của bạn là: <b>$newPass</b>";
            $mail->send();
        } catch (Exception $e) {
            // Không echo lỗi ra ngoài
        }
    }

}
