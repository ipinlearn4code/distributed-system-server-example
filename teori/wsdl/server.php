<?php
error_reporting(1); // error ditampilkan
require_once('nusoap.php');
require_once('database.php');

// buat objek baru dari class NuSOAP Server
$server = new nusoap_server();

// configure WSDL file
$server->configureWSDL('WSDL Mahasiswa', 'urn:ServerWSDL'); 

$server->register('tampil_semua_data', 	// method name
 array(),  								// no input parameters
 array('output' => 'xsd:Array'),    	// output parameters
 'urn:ServerWSDL',     					// namespace
 'urn:ServerWSDL#tampil_semua_data',  	// soapaction
 'rpc',       							// style
 'encoded',      						// use
 'tampil semua data'      				// documentation
);	

$server->register('tampil_data', 	// method name
 array('input' => 'xsd:String'),   	// input parameters
 array('output' => 'xsd:Array'),    // output parameters
 'urn:ServerWSDL',     				// namespace
 'urn:ServerWSDL#tampil_data',  	// soapaction
 'rpc',       						// style
 'encoded',      					// use
 'tampil data'      				// documentation
);

$server->register('tambah_data', 	// method name
 array('input' => 'xsd:Array'),    	// input parameters
 array(),    						// no output parameters
 'urn:ServerWSDL',     				// namespace
 'urn:ServerWSDL#tambah_data',  	// soapaction
 'rpc',       						// style
 'encoded',      					// use
 'tambah data'      				// documentation
);

$server->register('ubah_data', 		// method name
 array('input' => 'xsd:Array'),    	// input parameters
 array(),    						// no output parameters
 'urn:ServerWSDL',     				// namespace
 'urn:ServerWSDL#ubah_data',  		// soapaction
 'rpc',       						// style
 'encoded',      					// use
 'ubah data'      					// documentation
);

$server->register('hapus_data', 	// method name
 array('input' => 'xsd:String'),    // input parameters
 array(),    						// no output parameters
 'urn:ServerWSDL',     				// namespace
 'urn:ServerWSDL#hapus_data',  		// soapaction
 'rpc',       						// style
 'encoded',      					// use
 'hapus data'      					// documentation
);

// fungsi menghapus selain huruf dan angka
function filter($data)
{	$data = preg_replace('/[^a-zA-Z0-9]/','',$data);
	return $data;
	unset($data);
}

function tampil_semua_data()
{	$abc = new Database();
	$data = $abc->tampil_semua_data();
	return $data; 
	unset($abc,$data);		
}

function tampil_data($nim)
{	$nim = filter($nim);
	$abc = new Database();
	$data = $abc->tampil_data($nim);	
	return $data; 
	unset($nim,$abc,$data);			
}

function tambah_data($data)
{	$abc = new Database();
	$data = $abc->tambah_data($data);	
	unset($abc,$data);	
}

function ubah_data($data)
{	$abc = new Database();
	$data = $abc->ubah_data($data);	
	unset($abc,$data);	
}

function hapus_data($nim)
{	$nim = filter($nim);	
	$abc = new Database();
	$data = $abc->hapus_data($nim);	
	unset($nim,$abc,$data);
}	

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

unset($server);
?>
