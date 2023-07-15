<?php
require_once dirname(__DIR__, 1) . '/DBConnection.php';

class Keyboard {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::getConnection();
    }

    public function addUser($user_id) {
        try {
            if (! $this->rowRount() || $this->rowRount() == 1) {
                $color = $user_id === 1 ? 'red' : 'yellow';
                $is_active = 1;
                $query = $this->conn->prepare("INSERT INTO keyboard(user, is_active, color) VALUES(:user, :is_active, :color) ");
                $query->bindValue(':user', $user_id, PDO::PARAM_INT);
                $query->bindValue(':is_active', $is_active, PDO::PARAM_INT);
                $query->bindValue(':color', $color, PDO::PARAM_STR);
                $query->execute();
                return $query->rowCount() > 0 ? "User created successfully" : "Unable to create user";
            }
            return "User already exists";
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }

    public function rowRount($id = "") {
        try {
            $sql = !empty($id) ? "SELECT COUNT(*) FROM keyboard where user = $id AND is_active = 1" : "SELECT COUNT(*) FROM keyboard";
            $query = $this->conn->query($sql);
            return $query->fetchColumn();
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }

    public function updateKeboard($data) {
        try {
            $key_name = $data['key_name'] ?? 'key_1';
            $key_value = $data['key_value'] ?? null;
//            $color = $data['color'] ?? null;
            $control = $data['control'] ?? 0;
            $user_id = $data['user_id'];
            $sql = "UPDATE keyboard SET control = ?, ".$key_name."=?" . " WHERE user = ?";
            $query = $this->conn->prepare($sql);
//            $query->bindValue(1, $color, PDO::PARAM_STR || NULL);
            $query->bindValue(1, $control, PDO::PARAM_INT);
            $query->bindValue(2, $key_value, PDO::PARAM_STR);
            $query->bindValue(3, $user_id, PDO::PARAM_INT);
            $query->execute();
            if ($query->rowCount() > 0) {
                $id = $user_id == 1 ? 2 : 1;
//                $control = $control == 0 ? 1 : 0;
                $query = $this->conn->prepare($sql);
//                $query->bindValue(1, $color, PDO::PARAM_STR || NULL);
                $query->bindValue(1, $control, PDO::PARAM_INT);
                $query->bindValue(2, $key_value, PDO::PARAM_STR);
                $query->bindValue(3, $id, PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount() > 0 ? "User updated successfully" : "Unable to update user";
            }
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }

    public function getData($id = null) {
        try {
            $sql = !empty($id) ? "SELECT * FROM keyboard WHERE user=$id" : "SELECT * FROM keyboard";
            $query = $this->conn->query($sql);
            return !empty($id) ? $query->fetchAll(PDO::FETCH_ASSOC)[0] : $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }

    public function truncte() {
        try {
            $query = $this->conn->prepare("TRUNCATE TABLE keyboard");
            $query->execute();
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }

    public function delete(int $user_id) {
        try {
            $query = $this->conn->prepare("DELETE FROM keyboard WHERE user = :user_id");
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
            return $query->rowCount() > 0 ? 'User deleted Successfully' : 'Unable to delete user';
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }

    public function relaseControl() {
        try {
            $query = $this->conn->prepare("UPDATE keyboard SET control = ?");
            $query->bindValue(1, 1, PDO::PARAM_INT);
            $query->execute();
            return $query->rowCount() > 0 ? 'Control released' : 'Unable to release control';
        } catch (PDOException $e) {
            die("Error: ".$e->getMessage());
        }
    }
}

