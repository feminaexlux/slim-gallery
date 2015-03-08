<?php
class DAO
{
    public $conn = null;

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=192.168.0.108;dbname=gallery", 'gallery', 'gallery');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection could not be established: $e->getMessage()";
        }
    }

    function __destruct() {
        $this->conn = null;
    }

    public function getAllImages() {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM image ORDER BY created DESC, "name" ASC');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            return json_encode($stmt->fetchAll());
        } catch(PDOException $e) {
            echo "Error getting image: $e->getMessage()";
        }
    }

    public function getLatestImages() {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM image ORDER BY created DESC, "name" ASC LIMIT 10');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            return json_encode($stmt->fetchAll());
        } catch(PDOException $e) {
            echo "Error getting image: $e->getMessage()";
        }
    }

    public function getImage($filename) {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM image WHERE filename = :filename');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('filename' => $filename));
            return json_encode($stmt->fetch());
        } catch(PDOException $e) {
            echo "Error getting image: $e->getMessage()";
        }
    }
}