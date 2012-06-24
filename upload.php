<?php
function rearrange( $arr ) {
        foreach( $arr as $key => $all ) {
                foreach( $all as $i => $val ) {
                        $new[$i][$key] = $val;
                }
        }
        return $new;
}
$otps = dirname(__FILE__).'/otps/';
$random = fopen('/dev/urandom','rb');

if (!empty($_FILES['data']))
foreach(rearrange($_FILES['data']) as $file) {
	if ($file['error']!=0) continue;

	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$file['name'].'"');
	header('Content-Length: '.filesize($file['tmp_name']));

	$uid = sha1_file($file['tmp_name']);
	$orig = file_get_contents($file['tmp_name']);

	if (file_exists($otps.$uid)) {
		$out = $orig ^ file_get_contents($otps.$uid);
	}
	else {
		$otp = fread($random,strlen($orig));
		$out = $orig ^ $otp;
		$uid = sha1($out);
		file_put_contents($otps.$uid,$otp);
	}
	print($out);
	exit();
}

