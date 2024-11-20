<?php
error_reporting(1);

include_once 'core.php';
include_once 'lib/php-jwt/src/BeforeValidException.php';
include_once 'lib/php-jwt/src/ExpiredException.php';
include_once 'lib/php-jwt/src/SignatureInvalidException.php';
include_once 'lib/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

include_once 'Database.php';
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 3600');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
exit(0);
}
$postdata = file_get_contents("php://input");
var_dump($postdata);
$data = json_decode($postdata);

function filter($data)
{
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    var_dump($data);
    echo"sego pecel";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data->id_pengguna) && isset($data->pin)) {
    $data2['id_pengguna'] = $data->id_pengguna;
    $data2['pin'] = $data->pin;
    // Call the login function and check if user data is returned
    $userData = $abc->login($data2);
    // var_dump($data2);
    if ($userData) {
        // Prepare the JWT token with user details
        $token = array(
            'iat' => $issued_at,
            'exp' => $expiration_time,
            'iss' => $issuer,
            'data' => array(
                'id_pengguna' => $userData['id_pengguna'],  // Use the id_pengguna from $userData
                'pin' => $data2['pin']  // Avoid storing the pin in the JWT; you might want to remove it for security
                )
            );
            
            // Set response code to 200 OK
            http_response_code(200);
            
            // Encode the JWT and return it in the response
            $jwt = JWT::encode($token, $key);
            echo json_encode(
                array(
                    'message' => 'Login Success',
                    'jwt' => $jwt
                    )
        );
    } else {
        // Set response code to 401 Unauthorized
        http_response_code(401);
        echo json_encode(
            array(
                'message' => 'Login Gagal'
                )
            );
        }
        
        // if ($abc->login($data2)) {
            //     $token = array(
                //         'iat' => $issued_at,
                //         'exp' => $expiration_time,
                //         'iss' => $issuer,
                //         'data' => array(
                    //             'id_pengguna' => $data2['id_pengguna'],
                    //             'pin' => $data2['pin']
                    //         )
                    //     );
                    
                    //     http_response_code(200);
                    
                    //     $jwt = JWT::encode($token, $key);
                    //     echo json_encode(
                        //         array(
                            //             'message' => 'Login Success',
                            //             'jwt' => $jwt
                            //         )
                            //     );
                            // } else {
                                //     http_response_code(401);
                                //     echo json_encode(
                                    //         array(
                                        
                                    //             'message' => 'Login Gagal'
                                    //         )
                                    //     );
                                    // }
                                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data->jwt) && isset($data->aksi)) {
                                    $jwt = $data->jwt;
    $aksi = $data->aksi;
    $id_barang = isset($data->id_barang) ? $data->id_barang : null;
    $nama_barang = isset($data->nama_barang) ? $data->nama_barang : null;
    
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        
        if ($aksi == 'tambah') {
            $data2 = array(
                'aksi' => $aksi,
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang
            );
            $abc->tambah_data($data2);
        } elseif ($aksi == 'ubah') {
            $data2 = array(
                'aksi' => $aksi,
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang
            );
            $abc->ubah_data($data2);
        } elseif ($aksi == 'hapus') {
            $abc->hapus_data($id_barang);
        }
        
        http_response_code(200);
        echo json_encode($data2);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('message' => 'Access Denied'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $jwt = isset($_GET['jwt']) ? $_GET['jwt'] : null;
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        
        if (isset($_GET['aksi']) && $_GET['aksi'] == 'tampil' && isset($_GET['id_barang'])) {
            $id_barang = filter($_GET['id_barang']);
            $data = $abc->tampil_data($id_barang);
        } else {
            $data = $abc->tampil_semua_data();
        }
        http_response_code(200);
        echo json_encode($data);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('message' => 'Access denied 2'));
    }
} else {
    http_response_code(401);
    echo json_encode(array('message' => 'Access denied 3'));
}

unset($abc, $postdata, $data, $data2, $token, $key, $issued_at, $expiration_time, $issuer, $jwt, $decoded, $id_barang, $nama_barang, $aksi);
