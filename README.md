# FTP Class

Une classe qui permet de gérer les connexions FTP.
Une meilleur gestion des erreurs.

## Documentation

**$ftp = new FTP(HOST, PORT, USER, PASSWORD, SSL, PASSIVE)**
_Démarre la connexion FTP_

- **HOST** (Required) : String
- **PORT** (Required) (Default : 21) : Int
- **USER** (Required) : String
- **PASSWORD** (Required) : String
- **SSL** (Default : false) : boolean
- **PASSIVE** (Default : false) : boolean

**$ftp->close()**
_Ferme la connexion_

**$ftp->get(REMOTE_FILE, LOCAL_FILE, MODE)**
_Télécharge un fichier_

- **REMOTE_FILE** (Required) : String
- **LOCAL_FILE** (Required) : String
- **MODE** (Default : FTP_BINARY)

**$ftp->put(LOCAL_FILE, REMOTE_FILE, MODE)**
_Envoie un fichier sur le serveur FTP_

- **LOCAL_FILE** (Required) : String
- **REMOTE_FILE** (Required) : String
- **MODE** (Default : FTP_BINARY)

**$ftp->goToFolder(FOLDER)**
_Se déplacer dans un dossier_

- **FOLDER** (Required) : String

**$ftp->getFolder()**
_Retourne le nom du dossier courant_

**$ftp->getFiles()**
_Retourne la liste des dossiers et fichiers_