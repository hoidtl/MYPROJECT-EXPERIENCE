<?php
class Product extends Controller{
    
    public function show(){
    $obj = $this->model("AdProducModel");

    $sql = "
        SELECT 
            sp.*,

            GROUP_CONCAT(sz.size ORDER BY sz.id SEPARATOR ', ') AS sizes,

            GROUP_CONCAT(FORMAT(sz.giaNhap, 0) ORDER BY sz.id SEPARATOR ' | ') AS giaNhapList,

            GROUP_CONCAT(FORMAT(sz.giaXuat, 0) ORDER BY sz.id SEPARATOR ' | ') AS giaXuatList

        FROM tblsanpham sp
        LEFT JOIN tbl_sanpham_size sz ON sp.masp = sz.masp
        GROUP BY sp.masp
        ORDER BY sp.masp ASC
    ";

    $data = $obj->select($sql);

    $this->view("adminPage",[
        "page"=>"ProductListView",
        "productList"=>$data
    ]);
}


    public function delete($masp){
        $obj = $this->model("AdProducModel");

        // Xóa size trước
        $obj->deleteSizeByMasP($masp);

        // Xóa sản phẩm
        $result = $obj->delete("tblsanpham", $masp);

        if($result) {
            $_SESSION['success'] = "Xóa sản phẩm thành công";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa sản phẩm";
        }

        header("Location:".APP_URL."/Product/");
        exit();
    }

    public function create(){
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        $producttype = $obj2->all("tblloaisp");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $masp = preg_replace('/\s+/', '', $_POST["txt_masp"]);
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $mota = $_POST["txt_mota"];

            // Upload ảnh
            $hinhanh = "default.png";
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $target_dir = "./public/images/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
                move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $target_dir . $hinhanh);
            }

            // INSERT sản phẩm
            $insertResult = $obj->insert(
                $maloaisp,
                $masp,
                $tensp,
                $hinhanh,
                $soluong,
                $mota
            );

            if($insertResult) {

                // INSERT SIZE (S, M, L)
                $sizes = $_POST['size'];          // ['S','M','L']
                $giaNhap = $_POST['giaNhap'];     // nhập của từng size
                $giaXuat = $_POST['giaXuat'];     // xuất của từng size

                for ($i = 0; $i < count($sizes); $i++) {
                    if ($sizes[$i] !== '' && $giaNhap[$i] !== '' && $giaXuat[$i] !== '') {
                        $obj->insertSizeFull($masp, $sizes[$i], $giaNhap[$i], $giaXuat[$i]);
                    }
                }

                $_SESSION['success'] = "Thêm sản phẩm thành công";

            } else {
                $_SESSION['error'] = "Mã sản phẩm đã tồn tại!";
            }

            header("Location: " . APP_URL . "/Product/");
            exit();
        }

        $this->view("adminPage", [
            "page" => "ProductView",
            "producttype" => $producttype
        ]);
    }

    public function edit($masp){
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");

        $producttype = $obj2->all("tblloaisp");
        $product = $obj->find("tblsanpham", $masp);
        $sizes = $obj->getSizesByMasP($masp);

        if (!$product) {
            $_SESSION['error'] = "Không tìm thấy sản phẩm";
            header("Location: " . APP_URL . "/Product/");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $mota = $_POST["txt_mota"];

            // Ảnh
            $hinhanh = $product['hinhanh'];
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                move_uploaded_file($_FILES["uploadfile"]["tmp_name"], "./public/images/" . $hinhanh);
            }

            // UPDATE sản phẩm
            $obj->update(
                $maloaisp,
                $masp,
                $tensp,
                $hinhanh,
                $soluong,
                $mota
            );

            // UPDATE SIZE: xóa hết → thêm lại 3 size
            $obj->deleteSizeByMasP($masp);

            $sizes = $_POST['size'];
            $giaNhap = $_POST['giaNhap'];
            $giaXuat = $_POST['giaXuat'];

            for ($i = 0; $i < count($sizes); $i++) {
                if ($sizes[$i] !== '' && $giaNhap[$i] !== '' && $giaXuat[$i] !== '') {
                    $obj->insertSizeFull($masp, $sizes[$i], $giaNhap[$i], $giaXuat[$i]);
                }
            }

            $_SESSION['success'] = "Cập nhật sản phẩm thành công";

            header("Location: " . APP_URL . "/Product/");
            exit();
        }

        $this->view("adminPage", [
            "page" => "ProductView",
            "producttype" => $producttype,
            "editItem" => $product,
            "sizes" => $sizes
        ]);
    }
}