<?php
require_once('db_mysqli.php');
require_once('functions.php');

if ( ! empty($_POST['brisanjeKomentara'])) {
    if (izbrisiKomentar($_POST['brisanjeKomentara'])) {
        header('Location: index.php');
        exit();
    } 
    die('Greška prilikom brisanja komentara');
} elseif ( ! empty($_POST['komentar'])) {
    if (upisiKomentar($_POST['idVest'], $_POST['komentar'])) {
        header('Location: index.php');
        exit();        
    } 
    die('Greška prilikom upisa');
}

htmlHeader('Vesti');
?>
<h1>Vesti</h1>
<?php

$sql = "SELECT vesti.* FROM vesti ORDER BY id DESC";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
    prikaziVest($row);
}
htmlFooter();


/*
Test podaci:
 
insert into vesti(naslov, tekst, vreme_objave) values
('Vest 2', 'Glavni tekst vesti 2', now()),
('Vest 3', 'Glavni tekst vesti 3', now());
('PHP kao sistemski jezik', 'Prvi operativni sistem kompletno programiran na programskom jeziku PHP kreiran je u laboratoriji u Papua Novoj Gvineji...', now()),
*/
