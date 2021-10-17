<?php
    namespace Auth;

    class Auth{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // LOGIN
        function login($email, $password){
            $user_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_user WHERE user_email = '$email' LIMIT 1");

            if(mysqli_num_rows($user_query) == 1){
                $user_info = mysqli_fetch_array($user_query);
                $password = md5($password);

                if($user_info['user_password'] == $password){
                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $user_info['user_id'];
                    $_SESSION['user_category'] = $user_info['user_category'];

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $user_info['user_fullname']
                    )));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Invalid password!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Invalid email!'
                )));
            }
        }

        // FORGET PASSWORD
        function forgot_password($email){
            require_once('PHPMailer/Mailer.php');

            $user_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_user WHERE user_email = '$email' LIMIT 1");
            
            if(mysqli_num_rows($user_query) == 1){
                $user_info = mysqli_fetch_array($user_query);
                $user_id = $user_info['user_id'];
                $token = md5($email);

                $body = '<p>You have requested for a password reset. Please click on the link below to reset your password.</p><br>' .
                    '<h4><a href="http://inventory.rrmsteel.com.bd/purchase/reset-password?uid=' . $user_id . '&token=' . $token. '">CLICK HERE<a></h4><br><br>';

                if(Mailer($email, 'Forgot Password', $body)){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => "Password reset link has been sent to your email!"
                    )));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Oops! Looks like something went wrong! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Oops! Looks like you are not a registered user yet.'
                )));
            }
        }

        // RESET PASSWORD
        function reset_password($password, $confirm_password, $uid, $token){
            $user_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_user WHERE user_id = '$uid' LIMIT 1");
            
            if(mysqli_num_rows($user_query) == 1){
                $user_info = mysqli_fetch_array($user_query);
                $user_email = $user_info['user_email'];

                if($password == $confirm_password){
                    if(md5($user_email) == $token){
                        $password = md5($password);

                        $upd_user_query = mysqli_query($this->conn, "UPDATE rrmsteel_user SET user_password = '$password' WHERE user_id = '$uid'");
                        
                        if($upd_user_query){
                            exit(json_encode(array(
                                'Type' => 'success',
                                'Reply' => "Your password has been reset successfully!"
                            )));
                        } else{
                            exit(json_encode(array(
                                'Type' => 'error',
                                'Reply' => 'Oops! Looks like something went wrong! Please try again later.'
                            )));
                        }
                    } else {
                        exit(json_encode(array(
                            'Type' => 'error',
                            'Reply' => 'Invalid link!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Passwords does not match!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Oops! Looks like you are not a registered user yet.'
                )));
            }
        }
    }
?>