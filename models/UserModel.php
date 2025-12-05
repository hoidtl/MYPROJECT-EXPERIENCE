<?php
class UserModel extends DB {

    private $table = "tbluser";
    public $email;
    public $password;
    public $fullname;
    public $token;

    public function create() {
        $query = "INSERT INTO {$this->table} (fullname, email, password, verification_token) 
                  VALUES (:fullname, :email, :password, :token)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":token", $this->token);
        return $stmt->execute();
    }

    public function verify($token) {
        $query = "SELECT * FROM {$this->table} WHERE verification_token = :token AND is_verified = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt;
    }

    public function setVerified($token) {
        $query = "UPDATE {$this->table} SET is_verified = 1, verification_token = NULL 
                  WHERE verification_token = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function findByEmail($email)
{
    $sql = "SELECT * FROM {$this->table} WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public function updatePassword($email, $newPasswordHash) {
        $query = "UPDATE {$this->table} SET password = :password WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":password", $newPasswordHash);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    public function createUser($fullname, $email, $password) {
        $query = "INSERT INTO {$this->table} (fullname, email, password) 
                  VALUES (:fullname, :email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $fullname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        return $stmt->execute();
    }

    // Lấy tất cả users
    public function getAllUsers() {
        $sql = "SELECT * FROM {$this->table} ORDER BY user_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy user theo ID
    public function getUserById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật role
    public function updateRole($userId, $role) {
        $sql = "UPDATE {$this->table} SET role = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$role, $userId]);
    }

    // Xóa user
    public function deleteUser($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }

    // Đếm users
    public function countUsers() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Đếm admins
    public function countAdmins() {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE role = 'admin'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
