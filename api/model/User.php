<?php
    namespace User;

    class User{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // FETCH A USER
        function fetch($user_id){
            $user_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_user WHERE user_id = '$user_id' LIMIT 1");

            if(mysqli_num_rows($user_query) > 0){
            	while($row = mysqli_fetch_assoc($user_query)){
	                $data[] = [
	                	'user_id' => $row['user_id'],
	                    'user_fullname' => $row['user_fullname'],
	                    'user_email' => $row['user_email'],
	                    'user_mobile' => $row['user_mobile'],
	                    'user_designation' => $row['user_designation'],
	                    'user_department' => $row['user_department'],
	                    'user_status' => $row['user_status'],
	                    'user_category' => $row['user_category']
	                ];
	            }

                $reply = array(
	                'Type' => 'success',
	                'Reply' => $data
	            );

	            exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No user found !'
                )));
            }
        }

        // FETCH ALL USERS
        function fetch_all(){
            $user_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_user WHERE user_id > 1");

            if(mysqli_num_rows($user_query) > 0){
                $i = 0;

                while($row = mysqli_fetch_assoc($user_query)){
                	$user_id = $row['user_id'];

                	if($row['user_category'] == 1)
                        $user_category = 'Admin';
                    elseif($row['user_category'] == 2)
                        $user_category = 'Requisite Person';
                    elseif($row['user_category'] == 3)
                        $user_category = 'Store In-charge';
                    elseif($row['user_category'] == 4)
                        $user_category = 'Purchase In-charge';
                    elseif($row['user_category'] == 5)
                        $user_category = 'Electricity In-charge';

                    if($row['user_status'] == 0)
                        $user_status = 'Inactive';
                    elseif($row['user_status'] == 1)
                        $user_status = 'Active';

                    $action = '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".bs-example-modal-lg2" data-id="'.$user_id.'" onclick="update_user(' . $user_id . ')"><i class="mdi mdi-pencil"></i></a> ';
                    $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger" data-id="'.$user_id.'" onclick="delete_user(' . $user_id . ')"><i class="mdi mdi-delete"></i></a>';

	                $data[] = [
	                	'sl' => ++$i,
	                	'user_id' => $row['user_id'],
	                    'user_fullname' => $row['user_fullname'],
	                    'user_email' => $row['user_email'],
	                    'user_mobile' => $row['user_mobile'],
	                    'user_designation' => $row['user_designation'],
	                    'user_department' => $row['user_department'],
	                    'user_status' => $user_status,
	                    'user_category' => $user_category,
	                    'action' => $action
	                ];
	            }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No parts found !'
                )));
            }
        }
    }
?>