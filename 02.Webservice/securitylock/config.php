<?php

// DB info
define('_DBHOST_', "localhost");
define('_DBUSER_', "root");
define('_DBPASS_', "");
define('_DBNAME_', "securitylock");
// AES key
$iv = array(0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0C, 0x0B, 0x0D, 0x0E, 0x0F, 0xAA);
$key = array(0x2B, 0x7E, 0x15, 0x16, 0x28, 0xAE, 0xD2, 0xA6, 0xAB, 0xF7, 0x15, 0x88, 0x09, 0xCF, 0x4F, 0x3C);

?>