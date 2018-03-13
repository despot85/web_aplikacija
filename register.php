<?php
require_once('functions.php');

if(isset($_SESSION['login'])) {
    header("Location: index.php");
}

include_once 'db_mysqli.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    
    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z ]+$/",$username)) {
        $error = true;
        $username_error = "username must contain only alphabets and space $username";
    }
    if(strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    
    if (!$error) {
        if(mysqli_query($con, "INSERT INTO korisnik(username,password, status) VALUES('" . $username . "',  '" . md5($password) . "', '1')")) {
            $successmsg = "Successfully Registered! <a href='login.php'>Click here to Login</a>";
        } else {
            $errormsg = "Error in registering...Please try again later!";
        }
    }
}
?>

<?=htmlHeader("Registracija")?>
    
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                    <legend>Sign Up</legend>
                    <div class="form-group">
                        <label for="name">username</label>
                        <input type="text" name="username" placeholder="Odaberite nadimak" required value="<?php if($error) echo $username; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($username_error)) echo $username_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="Password" required class="form-control" />
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>

<?=htmlFooter()?>
