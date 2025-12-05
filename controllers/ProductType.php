<?php
class ProductType extends Controller {

    // Hiển thị danh sách
    public function show() {
        $obj = $this->model("AdProductTypeModel");
        $data = $obj->all("tblloaisp");

        $this->view("adminPage", [
            "page" => "ProductTypeView",
            "productList" => $data
        ]);
    }

    // Xóa loại sản phẩm theo ID
    public function delete($id) {
        $obj = $this->model("AdProductTypeModel");
        $obj->delete("tblloaisp", $id);

        header("Location:" . APP_URL . "/ProductType/");
        exit();
    }

    // Thêm mới
    public function create() {
        $txt_maloaisp = $_POST["txt_maloaisp"] ?? "";
        $txt_tenloaisp = $_POST["txt_tenloaisp"] ?? "";
        $txt_motaloaisp = $_POST["txt_motaloaisp"] ?? "";

        $obj = $this->model("AdProductTypeModel");
        $obj->insert($txt_maloaisp, $txt_tenloaisp, $txt_motaloaisp);

        header("Location:" . APP_URL . "/ProductType/");
        exit();
    }

    // Sửa: CHỈ nhận ID, không nhận tên
    public function edit($id)
    {
        $obj = $this->model("AdProductTypeModel");

        // Lấy loại theo ID
        $product = $obj->getById($id);

        // Lấy danh sách
        $productList = $obj->all("tblloaisp");

        $this->view("adminPage", [
            "page" => "ProductTypeView",
            "productList" => $productList,
            "editItem" => $product
        ]);
    }

    // Cập nhật: cũng chỉ nhận ID
    public function update($id)
    {
        $tenLoaiSP = $_POST['txt_tenloaisp'] ?? "";
        $moTaLoaiSP = $_POST['txt_motaloaisp'] ?? "";

        $obj = $this->model("AdProductTypeModel");
        $obj->update($id, $tenLoaiSP, $moTaLoaiSP);

        header("Location:" . APP_URL . "/ProductType/");
        exit();
    }
}
