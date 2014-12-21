<?php
class FTPException extends Exception{}

class FTP {

	private $connexion;
	private $host;
	private $port;
	private $user;
	private $password;
	private $ssl;
	private $passv;

	public function __construct($host = "", $port = 21, $user = "", $password = "", $ssl = false, $passv = false) {
		
		$this->setHost($host);
		$this->setPort($port);
		$this->setUser($user);
		$this->setPassword($password);
		$this->setSsl($ssl);

		$this->connect();
	}

	private function connect() {

		if($this->isSSL()) {
			$this->connexion = ftp_ssl_connect($this->getHost(), $this->getPort());
		} else {
			$this->connexion = ftp_connect($this->getHost(), $this->getPort());
		}

		if($this->connexion === false) {
			throw new FTPException("Erreur lors de l'initialisation de la connexion (Vérifiez votre host et votre port).", 1);
		}

		if(!ftp_login($this->connexion, $this->getUser(), $this->getPassword())) {
			throw new FTPException("Impossible de se connecter au serveur FTP avec vos identifiants", 1);
		}

		if($this->isPassive()) {
			if(!ftp_pasv($this->connexion, true)) {
				throw new FTPException("Problème lors du passage en mode passive", 1);
				
			}
		}

	}

	public function close() {
		ftp_close($this->connexion);
	}

	public function getFiles() {
		
		$files = [];

		$lists_details = ftp_rawlist($this->connexion, ".");
		$lists = ftp_nlist($this->connexion, ".");

		foreach ($lists as $key => $value) {
			$files[] = [
				"name" => $value,
				"details" => $lists_details[$key]
			];
		}

		return $files;
	}

	public function get($remoteFile = "", $localFile = "", $mode = FTP_BINARY) {

		if(empty($remoteFile)) {
			throw new FTPException("Vous devez indiqué un fichier à télécharger", 1);
		}
		if(empty($localFile)) {
			throw new FTPException("Vous devez renseigner l'endroit en ou sauvegarder votre fichier", 1);
		}

		if(!ftp_get($this->connexion, $localFile, $remoteFile, $mode)) {
			throw new FTPException("Erreur impossible de récupérer le fichier ".$remoteFile, 1);
			
		}

		return true;
	}

	public function put($localFile = "", $remoteFile = "", $mode = FTP_BINARY) {
		
		if(empty($remoteFile)) {
			throw new FTPException("Vous devez indiqué un fichier à télécharger", 1);
		}
		if(empty($localFile)) {
			throw new FTPException("Vous devez renseigner l'endroit en ou sauvegarder votre fichier", 1);
		}

		if(!ftp_put($this->connexion, $remoteFile, $localFile, $mode)) {
			throw new FTPException("Erreur d'envoie le fichier ".$localFile, 1);
		}

		return true;
	}


	public function goToFolder($folder = "") {
		if(empty($folder)) {
			throw new FTPException("Vous devez renseigner un dossier", 1);
		}

		if(!ftp_chdir($this->connexion, $folder)) {
			throw new FTPException("Impossible de se déplacer dans le dossier {$folder}", 1);
		}

		return $this;
	}

	public function getFolder() {
		if(!$pwd = ftp_pwd($this->connexion)) {
			throw new FTPException("Impossible de trouver le dossier actuel", 1);
		}

		return $pwd;
	}



	// Setteurs
	private function setHost($value) {
		if(empty($value)) {
			throw new FTPException("Vous devez entrer un serveur", 1);
		}

		$this->host = $value;
	}

	private function setPort($value) {
		if(empty($value)) {
			throw new FTPException("Vous devez entrer un numéro de port valide", 1);
		}

		$this->port = (int)$value;
	}

	private function setUser($value) {
		if(empty($value)) {
			throw new FTPException("Vous devez entrer un nom d'utilisateur", 1);
		}

		$this->user = $value;
	}

	private function setPassword($value) {
		if(empty($value)) {
			throw new FTPException("Vous devez entrer un mot de passe", 1);
		}

		$this->password = $value;
	}

	private function setSsl($value) {
		if(!is_bool($value)) {
			throw new FTPException("Le paramètre SSL doit etre 'true' ou 'false'", 1);
		}

		$this->ssl = $value;
	}

	private function setPassive($value) {
		if(!is_bool($value)) {
			throw new FTPException("Le paramètre Passive doit etre 'true' ou 'false'", 1);
		}

		$this->passv = $value;
	}

	// Getteurs
	private function getHost() {
		return $this->host;
	}

	private function getPort() {
		return $this->port;
	}

	private function getUser() {
		return $this->user;
	}

	private function getPassword() {
		return $this->password;
	}

	private function isSSL() {
		return $this->ssl;
	}

	private function isPassive() {
		return $this->passv;
	}

}

?>