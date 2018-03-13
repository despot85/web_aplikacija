<?php
require_once('db_mysqli.php');

function htmlHeader($title)
{?>  
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">    
    <title><?= htmlentities($title)?></title>
</head>
<body>
    <nav class="navbar-inverse navbar" role="navigation">
        <div class="container">
            <div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w0-collapse"><span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button><a class="navbar-brand" href="/">cteban-85</a>
            </div>

            <div class="collapse navbar-collapse" id="navbar1">
                <ul class="navbar-nav navbar-right nav">
                    <?php if( ! getIdLogovani()): ?>
                        <li><a href="register.php">Registracija</a></li>
                        <li><a href="login.php">Login</a></li>
                    <?php else:?>
                        <li><a href="logout.php">Odjava</a></li>
                    <?php endif;?>
                </ul>
            </div>       
        </div>
    </nav>    
    <div class="container">
<?php 
}

function htmlFooter()
{?>
    </div>
</body>
</html>       
<?php 
}

function prikaziVest($row)
{
    $idVest = $row['id'];
    $naslov = htmlentities($row['naslov']);
    $tekst = htmlentities($row['tekst']);
    $epoch = strtotime($row['vreme_objave']);
    $vreme = date('d.m.Y. H:i', $epoch);
    $komentari = getKomentariHtml($idVest);
    echo <<<HTML
    <div class="row">
        <div class="col-md-12">            
            <article>
                <h3>$naslov</h3>
                <p><time>$vreme</time></p>
                <section class="">$tekst</section>
                
                <section class="komentari">
                    <h4>Komentari</h4>
                    <form method="POST">
                        <textarea class="form-control" name="komentar"></textarea>
                        <input type="hidden" name="idVest" value="$idVest"/>
                        <button class="btn btn-success">Objavite komentar</button>
                    </form>
                    $komentari
                </section>
            
            </article>     
        </div>
    </div>
HTML;
}

function getIdLogovani()
{
    return empty($_SESSION['usr_id']) ? false : $_SESSION['usr_id'];
}

function getKomentariHtml($idVest)
{
    global $con;
    $komentari = '';
    $sql = "SELECT komentari.*, korisnik.username
            FROM komentari 
            INNER JOIN korisnik ON korisnik.id=komentari.username_id
            WHERE komentari.vest_id=$idVest ORDER BY komentari.id DESC";
    $result = mysqli_query($con, $sql);
    
    while ($row = mysqli_fetch_array($result)) {
        $komentar = htmlentities($row['tekst']);
        $idKomentar = $row['id'];
        $linkBrisanje = '';
        $korisnik = htmlentities($row['username']);
        if (getIdLogovani() == $row['username_id']) {
            $linkBrisanje = <<<BRISANJE
                <form method="POST">
                    <input type="hidden" name="brisanjeKomentara" value="$idKomentar"/>
                    <button class="btn btn-xs btn-danger">Brisanje</button>
                </form>
BRISANJE;
        }
        $komentari .= <<<HTML
            <div class="komentar">
                <small><strong>$korisnik kaže:</strong></small>
                <p>$komentar</p>
                $linkBrisanje
                <hr>
            </div>            
HTML;
    }
    return $komentari;
}

function izbrisiKomentar($id)
{
    global $con;
    $sql = "SELECT username_id FROM komentari WHERE id=$id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    if ($row['username_id'] != getIdLogovani()) {
        die ('Možete brisati samo svoje komentare!');
    }
    $delete = "DELETE FROM komentari WHERE id=$id";
    return mysqli_query($con, $delete);
}

function upisiKomentar($idVest, $komentar)
{    
    global $con;
    $idKorisnik = getIdLogovani();
    $sql = "INSERT INTO komentari(username_id, vest_id, tekst) VALUES($idKorisnik, $idVest, ?)";
    $stm = mysqli_prepare($con, $sql);
    if (mysqli_stmt_bind_param($stm, 's', $komentar)) {            
        return mysqli_stmt_execute($stm);
    }
    return false;
}