<?php
class BaseModel extends DB {

    // Danh sách bảng và khóa chính
    protected $primaryKeys = [
        'tblsanpham' => 'masp',
        'tblloaisp'  => 'maLoaiSP',
        'tbl_sanpham_size' => 'id'
    ];

    public function __construct()
    {
        parent::__construct();  // GỌI CONSTRUCTOR LỚP DB ĐỂ GÁN $this->db

        // BẮT LỖI PDO RÕ RÀNG
        if ($this->db instanceof PDO) {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            error_log("❌ BaseModel: \$this->db KHÔNG PHẢI PDO - LỖI KẾT NỐI");
        }
    }

    // =============== CÁC HÀM SẴN CÓ ===============

    public function all($table) {
        try {
            $sql = "SELECT * FROM $table";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();       
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi BaseModel::all(): " . $e->getMessage());
            return [];
        }
    }

    public function find($table, $id) {
        try {
            if (!array_key_exists($table, $this->primaryKeys)) {
                throw new Exception("Bảng không hợp lệ.");
            }
            $column = $this->primaryKeys[$table];
            $sql = "SELECT * FROM $table WHERE $column = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi BaseModel::find(): " . $e->getMessage());
            return null;
        }
    }

    public function check($table, $column, $id) {
        try {
            $sql = "SELECT COUNT(*) FROM $table WHERE $column = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Lỗi BaseModel::check(): " . $e->getMessage());
            return 0;
        }
    }

    public function delete($table,$id){
        try {
            if (!array_key_exists($table, $this->primaryKeys)) {
                throw new Exception("Bảng không hợp lệ.");
            }
            $column = $this->primaryKeys[$table];

            if($this->check($table, $column, $id)>0){
                $sql="DELETE FROM $table WHERE $column=:id"; 
                $stmt=$this->db->prepare($sql);
                $stmt->bindParam(":id",$id);
                return $stmt->execute();   
            }
            return false;

        } catch (PDOException $e) {
            error_log("Lỗi BaseModel::delete(): " . $e->getMessage());
            return false;
        }
    }   

    public function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Lỗi BaseModel::query(): " . $e->getMessage());
            return false;
        }
    }

    public function select($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi BaseModel::select(): " . $e->getMessage());
            return [];
        }
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    public function testConnection() {
        try {
            $this->db->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi kết nối database: " . $e->getMessage());
            return false;
        }
    }
}
