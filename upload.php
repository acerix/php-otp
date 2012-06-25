<?php

if (!empty($_FILES['data']) && $_FILES['data']['size']) {

	$otp_dir = dirname(__FILE__).'/otps/';

	$file = $_FILES['data'];

	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$file['name'].'"');
	header('Content-Length: '.filesize($file['tmp_name']));

	$uploaded = file_get_contents($file['tmp_name']);

	$uid = sha1($uploaded);

	if (file_exists($otp_dir.$uid))
		print( $uploaded ^ file_get_contents($otp_dir.$uid) );

	else {
		$otp = file_get_contents('/dev/urandom', NULL, NULL, NULL, strlen($uploaded));
		$out = $uploaded ^ $otp;
		$uid = sha1($out);
		if (file_put_contents($otp_dir.$uid, $otp))
			print($out);
	}

}
