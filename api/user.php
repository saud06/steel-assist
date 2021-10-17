<?php
    use User\User;

    require_once('connection.php');
    require_once('model/User.php');
    
    if($_POST['user_data_type'] == 'fetch'){
        if(!empty($_POST['user_id'])){
            $user = new User();
            echo $user->fetch(mysqli_real_escape_string($conn, $_POST['user_id']));
        }
    } elseif($_POST['user_data_type'] == 'fetch_all'){
        $user = new User();
        echo $user->fetch_all();
    }
?>