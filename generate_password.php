<?php
include_once 'application/libraries/Crypto.php';
include_once 'application/libraries/Hash.php';

if ($argv[1] == 'user')
{
	$hash = Hash::encode_hash(Hash::generate_password(8,2));
} else {
	$hash = Hash::encode_hash(Hash::generate_password(32,8));
}
$pw = Hash::decode_hash($hash);
?>
