<?php
error_reporting(E_ALL); // Enable error reporting for development

class Database
{
    private $host = "localhost";
    private $dbname = "toko";
    private $user = "root";
    private $password = "root";
    private $port = "3306";
    private $conn;

    // Constructor for initializing the database connection
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
            echo "Conn Db Granted ";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }

    // Method to get a single record by id
    public function tampil_data($id_barang)
    {
        try {
            $query = $this->conn->prepare("SELECT id_barang, nama_barang FROM barang WHERE id_barang=?");
            $query->execute(array($id_barang));
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $e) {
            echo "Error retrieving data: " . $e->getMessage();
            return null; // Return null if there's an error
        }
    }

    // Method to get all records
    public function tampil_semua_data()
    {
        try {
            $query = $this->conn->prepare("SELECT id_barang, nama_barang FROM barang ORDER BY id_barang");
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $e) {
            echo "Error retrieving data: " . $e->getMessage();
            return []; // Return an empty array if there's an error
        }
    }

    // Method to insert a new record
    public function tambah_data($data)
    {
        try {
            $query = $this->conn->prepare("INSERT INTO barang (id_barang, nama_barang) VALUES (?, ?)");
            $query->execute(array($data['id_barang'], $data['nama_barang']));
        } catch (PDOException $e) {
            echo "Error inserting data: " . $e->getMessage();
        }
    }

    // Method to update a record by id
    public function ubah_data($data)
    {
        try {
            $query = $this->conn->prepare("UPDATE barang SET nama_barang=? WHERE id_barang=?");
            $query->execute(array($data['nama_barang'], $data['id_barang']));
        } catch (PDOException $e) {
            echo "Error updating data: " . $e->getMessage();
        }
    }

    // Method to delete a record by id
    public function hapus_data($id_barang)
    {
        try {
            $query = $this->conn->prepare("DELETE FROM barang WHERE id_barang=?");
            $query->execute(array($id_barang));
        } catch (PDOException $e) {
            echo "Error deleting data: " . $e->getMessage();
        }
    }
}

$ab = new Database();
var_dump($ab->tampil_semua_data());
