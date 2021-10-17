<?php
    session_start();

    if (isset($_SESSION['discard_after']) && time() > $_SESSION['discard_after']){
        session_unset();
        session_destroy();
        session_start();
    }

    $_SESSION['discard_after'] = time() + 3600;

    require_once('connection.php');

    if(empty($_SESSION['email'])){
        // header('location: http://' . $_SERVER['HTTP_HOST'] . '/purchase');
        header('location: http://' . $_SERVER['HTTP_HOST'] . '/rrm_purchase/purchase');
        exit();
    } else{
        date_default_timezone_set('Asia/Dhaka');
        
        $email = $_SESSION['email'];
        $user_category = $_SESSION['user_category'];

        $user_query = mysqli_query($conn, "SELECT * FROM rrmsteel_user WHERE user_email = '$email'");

        if(mysqli_num_rows($user_query) == 1){
            $user_info = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rrmsteel_user WHERE user_email = '$email'"));

            $user_id = $user_info['user_id'];
            $user_fullname = $user_info['user_fullname'];
        } else{
            header('location: ../logout');
            exit();
        }
    }
?>