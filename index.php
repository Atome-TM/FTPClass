<?php
spl_autoload_register(function ($class) {
    require_once( 'classes/' . $class . '.class.php');
});

try {
	$myFtp = new FTP("mon.ftp.com", "21", "myuser", "password");
	$myFtp->goToFolder("www");

	$listeFiles = $myFtp->getFiles();

	var_dump($listeFiles);

} catch(FTPException $e) {
	echo $e->getMessage();
}
?>