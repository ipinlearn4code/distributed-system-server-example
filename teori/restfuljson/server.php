<?php
error_reporting(1);
include "database.php";
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

// var_dump($abc->tampil_semua_data());
$postdata = file_get_contents("php://input");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode($postdata);
    $nim = $data->nim;
    $nama = $data->nama;
    $no_hp = $data->no_hp;
    $alamat = $data->alamat;
    $aksi = $data->aksi;

    if ($aksi == 'tambah') {
        $data2 = array(
            'nim' => $nim,
            'nama' => $nama,
            'no_hp' => $no_hp,
            'alamat' => $alamat
        );
        $abc->tambah_data($data2);
    } elseif ($aksi == 'ubah') {
        $data2 = array(
            'nim' => $nim,
            'nama' => $nama,
            'no_hp' => $no_hp,
            'alamat' => $alamat
        );
        $abc->ubah_data($data2);
    } elseif ($aksi == 'hapus') {
        $abc->hapus_data($nim);
    }
    unset($postdata, $data, $data2, $nim, $nama, $no_hp, $alamat, $aksi, $abc);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (($_GET['aksi'] == 'tampil') and (isset($_GET['nim']))) {
        $nim = $abc->filter($_GET['nim']);
        $data = $abc->tampil_data($nim);
        echo json_encode($data);
    } else { // Display all data
        $data = $abc->tampil_semua_data();
        echo json_encode($data);
    }
    unset($postdata, $data, $nim, $abc);
}
?>
