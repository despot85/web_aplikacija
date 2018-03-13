<?php
session_start();
require_once ('functions.php');

if (isset($_SESSION['login'])) {
   header("Location: index.php");
}

include_once 'db_mysqli.php';

//check if form is submitted
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $sql = "SELECT * FROM korisnik WHERE username = '" . $username . "' and password = '" . md5($password) . "'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);
    if ($row) {
        $_SESSION['usr_id'] = $row['id'];
        $_SESSION['usr_name'] = $row['username'];
        $_SESSION['login'] = true; //ako se na pocetku fajla proverava ova promenljiva, ovo je pravo mesto da se setuje.
        header("Location: index.php");
    } else {
        $errormsg = "Incorrect Email or Password!!!";
    }
}


?>

<?=htmlHeader('Registracija')?>
<div class="col-md-4 col-md-offset-4 well">

    <legend>Login</legend>
    <form method="POST" action="">
        Username:<br />
        <input class="form-control" type="text" name="username" value ="" /> <br />
        Password:<br />
        <input class="form-control" type ="password" name="password" value ="" /><br /> <!-- typo-->
        <input class="btn btn-primary" type ="submit" name="login" value="Login" /> <!-- bitno je da name bude login-->
    </form>
    <br /> 
    [<a href="password.php">Zaboravljena lozinka </a>]
</div>
<?=htmlFooter()?>
