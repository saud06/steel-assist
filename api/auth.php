<?php
    use Auth\Auth;

    require_once('connection.php');
    require_once('model/Auth.php');
    
    if($_POST['auth_type'] == 'login'){
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $auth = new Auth();

            echo $auth->login(mysqli_real_escape_string($conn, $_POST['email']), mysqli_real_escape_string($conn, $_POST['password']));
        }
    } elseif($_POST['auth_type'] == 'forgot_password'){
        if(!empty($_REQUEST['email'])){
            $auth = new Auth();

            echo $auth->forgot_password(mysqli_real_escape_string($conn, $_POST['email']));
        }
    } elseif($_POST['auth_type'] == 'reset_password'){
        if(!empty($_REQUEST['password']) && !empty($_REQUEST['confirm_password'])){
            $auth = new Auth();

            echo $auth->reset_password(mysqli_real_escape_string($conn, $_POST['password']), mysqli_real_escape_string($conn, $_POST['confirm_password']), mysqli_real_escape_string($conn, $_POST['uid']), mysqli_real_escape_string($conn, $_POST['token']));
        }
    }
?>