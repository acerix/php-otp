<?php

if (!empty($_FILES['data']) && $_FILES['data']['error']==0) {
	
	$file = $_FILES['data'];

	$otps = dirname(__FILE__).'/otps/';


	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$file['name'].'"');
	header('Content-Length: '.filesize($file['tmp_name']));

	$uploaded = file_get_contents($file['tmp_name']);
	
	$uid = sha1($uploaded);

	if (file_exists($otps.$uid)) {
		$out = $uploaded ^ file_get_contents($otps.$uid);
	}
	else {
		$otp = file_get_contents('/dev/urandom', NULL, NULL, NULL, strlen($uploaded));
		$out = $uploaded ^ $otp;
		$uid = sha1($out);
		file_put_contents($otps.$uid, $otp);
	}

	print($out);
	exit();
}

