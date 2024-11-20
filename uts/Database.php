<?php
error_reporting(E_ALL);
class Database
{
    private $host = "localhost";
    private $dbname = "buku";
    private $user = "root";
    private $password = "root";
    private $port = "3306";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
        } catch (PDOException $e) {
            print('gagal');
            echo "Koneksi gagal $e";
        }
    }

    public function tampil_data($no_buku)
    {
        $query = $this->conn->prepare("select no_buku, judul, pengarang, tahun_terbit, penerbit, kategori from daftar_buku where no_buku=?");
        $query->execute(array($no_buku));

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data;

        $query->closeCursor();
        unset($no_buku, $data);
    }

    public function tampil_semua_data()
    {
        $query = $this->conn->prepare("select no_buku, judul, pengarang, tahun_terbit, penerbit, kategori from daftar_buku");
        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);
       
        return $data;

        $query->closeCursor();
        unset($data);
    }

    public function tambah_data($data)
    {
        $query = $this->conn->prepare("insert ignore into daftar_buku (no_buku, judul, pengarang, tahun_terbit, penerbit, kategori) values (?,?,?,?,?,?)");
        $query->execute(array($data['no_buku'], $data['judul'], $data['pengarang'], $data['tahun_terbit'], $data['penerbit'], $data['kategori']));
        $query->closeCursor();
        unset($data);
    }
    public function ubah_data($data)
    {
        $query = $this->conn->prepare("UPDATE daftar_buku SET judul = ?, pengarang = ?, tahun_terbit = ?, penerbit = ?, kategori = ? WHERE no_buku = ?");
        $query->execute(array($data['judul'], $data['pengarang'], $data['tahun_terbit'], $data['penerbit'], $data['kategori'], $data['no_buku']));
        $query->closeCursor();
        unset($data);
    }
    public function hapus_data($no_buku)
    {
        $query = $this->conn->prepare("delete from daftar_buku where no_buku=?");
        $query->execute(array($no_buku));
        $query->closeCursor();
        unset($no_buku);
    }
}
