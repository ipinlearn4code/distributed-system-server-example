<?php
error_reporting(1);

include_once 'core.php';
include_once 'lib/php-jwt/src/BeforeValidException.php';
include_once 'lib/php-jwt/src/ExpiredException.php';
include_once 'lib/php-jwt/src/SignatureInvalidException.php';
include_once 'lib/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

include_once "database.php";
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 3600'); // cache selama 1 jam
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($data->aksi == 'login') and isset($data->id_pengguna) and isset($data->pin)) {
    $data2['id_pengguna'] = $data->id_pengguna;
    $data2['pin'] = $data->pin;

    // cek login pengguna
    $data3 = $abc->login($data2);
    if ($data3) {
        // generate json web token (jwt)
        $token = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "iss" => $issuer,
            "data" => array(
                "id_pengguna" => $data3['id_pengguna'],
                "nama" => $data3['nama'],
                "no_hp" => $data3['no_hp'],      // tambahkan no_hp
                "alamat" => $data3['alamat']     // tambahkan alamat
            )
        );
        // set response code
        http_response_code(200);
        // generate jwt
        $jwt = JWT::encode($token, $key);
        echo json_encode(
            array(
                "pesan" => "Login sukses",
                "id_pengguna" => $data3['id_pengguna'],
                "nama" => $data3['nama'],
                "no_hp" => $data3['no_hp'],      // tambahkan no_hp
                "alamat" => $data3['alamat'],    // tambahkan alamat
                "jwt" => $jwt
            )
        );
    } else { // login gagal 
        // set response code
        http_response_code(401);
        echo json_encode(array("pesan" => "Login gagal"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jwt = $data->jwt;
    $aksi = $data->aksi;
    $nim = $data->nim;
    $nama = $data->nama;
    $no_hp = $data->no_hp;     // tambahkan no_hp
    $alamat = $data->alamat;   // tambahkan alamat

    // decode jwt
    try {
        JWT::decode($jwt, $key, array('HS256'));

        if ($aksi == 'tambah') {
            $data2 = array(
                'aksi' => $aksi,
                'nim' => $nim,
                'nama' => $nama,
                'no_hp' => $no_hp,      // tambahkan no_hp
                'alamat' => $alamat     // tambahkan alamat
            );
            $abc->tambah_data($data2);
        } elseif ($aksi == 'ubah') {
            $data2 = array(
                'aksi' => $aksi,
                'nim' => $nim,
                'nama' => $nama,
                'no_hp' => $no_hp,      // tambahkan no_hp
                'alamat' => $alamat     // tambahkan alamat
            );
            $abc->ubah_data($data2);
        } elseif ($aksi == 'hapus') {
            $data2 = array(
                'aksi' => $aksi,
                'nim' => $nim
            );
            $abc->hapus_data($nim);
        }

        // set response code
        http_response_code(200);
        echo json_encode($data2);
    } catch (Exception $e) { // jika decode gagal, berarti jwt tidak valid
        http_response_code(401);
        echo json_encode(array(
            "pesan" => "Dilarang akses"
        ));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $jwt = $_GET['jwt'];
    // decode jwt
    try {
        JWT::decode($jwt, $key, array('HS256'));

        if (($_GET['aksi'] == 'tampil') and (isset($_GET['nim']))) {
            $nim = $abc->filter($_GET['nim']);
            $data = $abc->tampil_data($nim);
        } else {  //menampilkan semua data 
            $data = $abc->tampil_semua_data();
        }

        // set response code
        http_response_code(200);
        echo json_encode($data);
    } catch (Exception $e) { // jika decode gagal, berarti jwt tidak valid
        http_response_code(401);
        echo json_encode(array(
            "pesan" => "Dilarang akses"
        ));
    }
} else { // error jika tanpa jwt
    http_response_code(401);
    echo json_encode(array("pesan" => "Dilarang akses"));
}

unset($abc, $postdata, $data, $data2, $data3, $token, $key, $issued_at, $expiration_time, $issuer, $jwt, $nim, $nama, $aksi, $e);
