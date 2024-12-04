<?php
class Database
{
	private $host = "localhost";
	private $dbname = "serviceserver";
	private $user = "root";
	private $password = "root";
	private $port = "3306";
	private $conn;

	// function yang pertama kali di-load saat class dipanggil
	public function __construct()
	{
		// koneksi database
		try {
			$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
		} catch (PDOException $e) {
			echo "Koneksi gagal";
		}
	}

	// function untuk menghapus selain huruf dan angka
	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	public function login($data)
	{
		$query = $this->conn->prepare("SELECT id_pengguna, nama FROM pengguna WHERE id_pengguna=? AND pin=SHA(?)");
		$query->execute(array($data['id_pengguna'], $data['pin']));
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($data);
	}

	public function tampil_semua_data()
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, alamat FROM mahasiswa ORDER BY nim");
		$query->execute();
		// mengambil banyak data dengan fetchAll	
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		// mengembalikan data	
		return $data;
		// hapus variable dari memory	
		$query->closeCursor();
		unset($data);
	}

	public function tampil_data($nim)
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, alamat FROM mahasiswa WHERE nim=?");
		$query->execute(array($nim));
		// mengambil satu data dengan fetch	
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($nim, $data);
	}

	public function tambah_data($data)
	{
		$query = $this->conn->prepare("INSERT IGNORE INTO mahasiswa (nim, nama, no_hp, alamat) VALUES (?, ?, ?, ?)");
		$query->execute(array($data['nim'], $data['nama'], $data['no_hp'], $data['alamat']));
		$query->closeCursor();
		unset($data);
	}

	public function ubah_data($data)
	{
		$query = $this->conn->prepare("UPDATE mahasiswa SET nama=?, no_hp=?, alamat=? WHERE nim=?");
		$query->execute(array($data['nama'], $data['no_hp'], $data['alamat'], $data['nim']));
		$query->closeCursor();
		unset($data);
	}

	public function hapus_data($nim)
	{
		$query = $this->conn->prepare("DELETE FROM mahasiswa WHERE nim=?");
		$query->execute(array($nim));
		$query->closeCursor();
		unset($nim);
	}
}
