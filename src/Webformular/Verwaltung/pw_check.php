<?php


//* Zur überprüfung des Passworts der digitalen ID wird ein Request an dieses Skript geschickt
 
# check password of digital ID
function check()
{
    $id = $_FILES["digital_ID"]["tmp_name"];
    $pw = $_POST['ID_password'];

    if (!$cert_store = file_get_contents($id)) {
        return false;   #  Error: Datei konnte nicht gelesen werden!
    }
    if (openssl_pkcs12_read($cert_store, $cert_info, $pw)) {
        return true;    #  Passwort ist richtig
    } else {
        return false;   #  Error: Passwort ist falsch oder die Datei konnte nicht gelesen werden!
    }
}

 
echo json_encode(check());  # response on recieved request
