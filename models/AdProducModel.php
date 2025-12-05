<?php
require_once __DIR__ . "/BaseModel.php";

class AdProducModel extends BaseModel {

    private $table = "tblsanpham";

    /**
     * Đếm sản phẩm
     */
    public function countProducts() {
        try {
            $sql = "SELECT COUNT(*) FROM tblsanpham";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("countProducts error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy toàn bộ sản phẩm (order theo createDate hoặc ngaytao)
     */
    public function all($table) {
        try {
            // cố gắng order theo createDate (nếu cột tồn tại DB sẽ ignore nếu ko)
            // nếu DB ko có createDate thì admin sẽ thay thế bằng ngaytao trong DB schema
            $stmt = $this->db->prepare("SELECT * FROM $table ORDER BY createDate DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdProducModel::all error: " . $e->getMessage());
            // fallback: try order theo ngaytao nếu trên thất bại
            try {
                $stmt = $this->db->prepare("SELECT * FROM $table ORDER BY ngaytao DESC");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                error_log("AdProducModel::all fallback error: " . $e2->getMessage());
                return [];
            }
        }
    }

    /**
     * Lấy 1 sản phẩm theo masp
     */
    public function find($table, $masp) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $table WHERE masp = :masp LIMIT 1");
            $stmt->bindParam(":masp", $masp);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdProducModel::find error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Insert product
     * Trả về true nếu thành công, false nếu lỗi (ví dụ masp trùng)
     */
    public function insert($maLoaiSP, $masp, $tensp, $hinhanh, $soluong, $mota) {

        // kiểm tra trùng mã (dùng BaseModel::check nếu có)
        try {
            if (method_exists($this, 'check')) {
                $col = $this->primaryKeys['tblsanpham'] ?? 'masp';
                if ($this->check('tblsanpham', $col, $masp) > 0) {
                    error_log("AdProducModel::insert - masp tồn tại: $masp");
                    return false;
                }
            }
        } catch (Exception $e) {
            error_log("AdProducModel::insert check error: " . $e->getMessage());
            // tiếp tục thử insert (DB sẽ báo lỗi nếu trùng khóa)
        }

        $ngayTao = date("Y-m-d");

        $sql = "INSERT INTO tblsanpham (maLoaiSP, masp, tensp, hinhanh, soluong, mota, createDate)
                VALUES (:maLoaiSP, :masp, :tensp, :hinhanh, :soluong, :mota, :createDate)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':masp', $masp);
            $stmt->bindParam(':tensp', $tensp);
            $stmt->bindParam(':hinhanh', $hinhanh);
            $stmt->bindParam(':soluong', $soluong);
            $stmt->bindParam(':mota', $mota);
            $stmt->bindParam(':createDate', $ngayTao);
            $res = $stmt->execute();
            if (!$res) {
                $err = $stmt->errorInfo();
                error_log("AdProducModel::insert execute failed: " . json_encode($err));
            } else {
                error_log("AdProducModel::insert OK masp=$masp");
            }
            return $res;
        } catch (PDOException $e) {
            error_log("AdProducModel::insert exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update product
     */
    public function update($maLoaiSP, $masp, $tensp, $hinhanh, $soluong, $mota) {
        try {
            $sql = "UPDATE tblsanpham SET 
                        maLoaiSP = :maLoaiSP,
                        tensp = :tensp,
                        hinhanh = :hinhanh,
                        soluong = :soluong,
                        mota = :mota
                    WHERE masp = :masp";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':masp', $masp);
            $stmt->bindParam(':tensp', $tensp);
            $stmt->bindParam(':hinhanh', $hinhanh);
            $stmt->bindParam(':soluong', $soluong);
            $stmt->bindParam(':mota', $mota);
            $res = $stmt->execute();
            if (!$res) {
                error_log("AdProducModel::update failed: " . json_encode($stmt->errorInfo()));
            }
            return $res;
        } catch (PDOException $e) {
            error_log("AdProducModel::update exception: " . $e->getMessage());
            return false;
        }
    }

    // ==========================
    // SIZE FUNCTIONS (mới)
    // Bảng tbl_sanpham_size cấu trúc: id, masp, size, giaNhap, giaXuat
    // ==========================

    /**
     * Chèn size đầy đủ (size + giaNhap + giaXuat)
     */
    public function insertSizeFull($masp, $size, $giaNhap, $giaXuat) {
        try {
            $sql = "INSERT INTO tbl_sanpham_size (masp, size, giaNhap, giaXuat)
                    VALUES (:masp, :size, :giaNhap, :giaXuat)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':masp', $masp);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':giaNhap', $giaNhap);
            $stmt->bindParam(':giaXuat', $giaXuat);
            $res = $stmt->execute();
            if (!$res) {
                error_log("AdProducModel::insertSizeFull failed: " . json_encode($stmt->errorInfo()));
            }
            return $res;
        } catch (PDOException $e) {
            error_log("AdProducModel::insertSizeFull exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Wrapper tương thích: nếu có code cũ gọi insertSize($masp, $size, $gia)
     * thì ta map $gia -> cả giaNhap và giaXuat bằng cùng giá đó (tương thích ngược).
     */
    public function insertSize($masp, $size, $gia) {
        // Đặt cả giaNhap và giaXuat bằng $gia để giữ tương thích cũ
        return $this->insertSizeFull($masp, $size, $gia, $gia);
    }

    /**
     * Xóa toàn bộ size theo mã sản phẩm
     */
    public function deleteSizeByMasP($masp) {
        try {
            $sql = "DELETE FROM tbl_sanpham_size WHERE masp = :masp";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':masp', $masp);
            $res = $stmt->execute();
            if (!$res) {
                error_log("AdProducModel::deleteSizeByMasP failed: " . json_encode($stmt->errorInfo()));
            }
            return $res;
        } catch (PDOException $e) {
            error_log("AdProducModel::deleteSizeByMasP exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách size theo masp
     */
    public function getSizesByMasP($masp) {
        try {
            $sql = "SELECT * FROM tbl_sanpham_size WHERE masp = :masp ORDER BY id ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':masp', $masp);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            error_log("AdProducModel::getSizesByMasP exception: " . $e->getMessage());
            return [];
        }
    }
    public function getProductsByCategory($maLoaiSP)
{
    $sql = "SELECT sp.*, sz.size, sz.giaNhap, sz.giaXuat 
            FROM tblsanpham sp
            LEFT JOIN tbl_sanpham_size sz ON sp.masp = sz.masp
            WHERE sp.maLoaiSP = :maLoaiSP";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':maLoaiSP', $maLoaiSP);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getCategoryList() {
    $sql = "SELECT * FROM tblloaisp ORDER BY maLoaiSP ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getProducts() {
        $sql = "SELECT sp.*, loai.moTaLoaiSP
                FROM tblsanpham sp
                JOIN tblloaisp loai ON sp.maLoaiSP = loai.maLoaiSP";

        $stmt = $this->db->prepare($sql);  
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getProductByType($typeId) {
        $sql = "SELECT sp.*, loai.moTaLoaiSP
                FROM tblsanpham sp
                JOIN tblloaisp loai ON sp.maLoaiSP = loai.maLoaiSP
                WHERE sp.maLoaiSP = :typeId";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':typeId', $typeId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
  public function getAddonPrice($masp)
{
    $sql = "
        SELECT s.tensp, s.hinhanh, sz.giaXuat
        FROM tblsanpham s
        JOIN tbl_sanpham_size sz ON s.masp = sz.masp
        WHERE s.masp = ?
        LIMIT 1
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$masp]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    

}

