<?php
date_default_timezone_set('Asia/Jakarta');

// Buat RSA Key 1024 bit atau 2048 bit di Linux/FreeBSD 
// $ openssl genrsa 1024
// $ openssl genrsa 2048
$key = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAP0QyNe3d5XZpa15
SsUYGIAKzVertmHEgmtGPuKU4e7fOFuq94P2jcMnydzHKjWpSu5dYXukb+s5yjCB
ZQjVwEE8NrvIZ4XO9lxpr99SNlcUeJZ5JuN5/DtqLABat7y1h1o9jNIo07b8AVvQ
J40EQu/viUNVHVNM7fh/wVVLJj09AgMBAAECgYEAlY5005+WqdWdpz62ZHKQECPS
JQzS1Ua4OMOj6G5Kc8wx12Lbsn5kW0BJeROvK1VodiRztwmAJwjZJ/9ggW8plXcy
rxSF2IhR43QWI+mdQu6nMnA1XXCcKiiVW793ihiMEy+0cGOP9S9va7EOkd5eCuis
0Rmb2yRO9juzRzsiXEECQQD+r0pW4IU6ucq2cVt0j4Yq0WpXg9bgQwbIua2EzScm
pqzfgx3eJYZyHY817vayVpxnQpgAL4hmt/exJtz3iyKRAkEA/l9af/aiC89MmJOn
kP90fWzQl1EydgHeEmBZgYWarhgWiR2hwhUNInACggcgmg/aAHSOI/pQH4XdTwGq
6Gft7QJARwnNyn3Fq6O3Dzx/Lfv6iGbxKofzn4oSklp4M9qlWPqUraN86UG+RoZI
M9r7pLLT3VmN3D9l5IDb7eXRLJr6gQJAPLRUzKT3FwpppR/XpWrRSf1l6jaebDsV
3BzSP26680EcX6yKpd6QO9+vOYip5xpRVDp8kWlzJZK0td4YA06KIQJAFIPsOg9k
AxJsBwSrpyUHFDHjVCRL2OfeKHEwL+iiiYk9k5k0B+QwLM73zNOuKwtXdQP/GMTI
LeU9u4F8cMMy0g==
";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // valid selama 1 jam
$issuer = "RestApiAuthJWT";
