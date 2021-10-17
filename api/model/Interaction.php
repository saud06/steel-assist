<?php
    namespace Interaction;
    require_once('connection.php');
    require_once('PHPMailer/Mailer.php');

    class Interaction{
        private $conn;
        function __construct(){
            $this->conn = $GLOBALS['conn'];

            session_start();
        }

        // ADD USER
        function addUser($input){
            $now = strtotime('now');
            
            if(mysqli_num_rows(mysqli_query($this->conn, "SELECT * FROM rrmsteel_user WHERE user_email = '".$input['email']."'")) == 0){
                $insert_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_user(user_fullname, user_email, user_mobile, user_designation, user_department, user_password, user_status, user_category, user_created) VALUES('".$input['fullname']."', '".$input['email']."', '".$input['mobile']."', '".$input['designation']."', '".$input['department']."', '".md5($input['password'])."', 1, '".$input['category']."', '$now')");

                if($insert_query){
                    $module = 'User';
                    $action = 'User Added';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'User has been added successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'User can not be added! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Email already exists! Please choose another one.'
                )));
            }
        }

        // UPDATE USER
        function updateUser($input){
            $now = strtotime('now');

            if($input['id'] == 1){
                if($input['password'] === ''){
                    $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_user SET user_fullname = '".$input['fullname']."', user_email = '".$input['email']."' WHERE user_id = '".$input['id']."'");
                } else{
                    $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_user SET user_fullname = '".$input['fullname']."', user_email = '".$input['email']."', user_password = '".md5($input['password'])."' WHERE user_id = '".$input['id']."'");
                }
            } else{
                if($input['password'] === ''){
                    $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_user SET user_fullname = '".$input['fullname']."', user_email = '".$input['email']."', user_mobile = '".$input['mobile']."', user_designation = '".$input['designation']."', user_department = '".$input['department']."', user_category = '".$input['category']."' WHERE user_id = '".$input['id']."'");
                } else{
                    $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_user SET user_fullname = '".$input['fullname']."', user_email = '".$input['email']."', user_mobile = '".$input['mobile']."', user_designation = '".$input['designation']."', user_department = '".$input['department']."', user_password = '".md5($input['password'])."', user_category = '".$input['category']."' WHERE user_id = '".$input['id']."'");
                }
            }

            if($update_query){
                $module = 'User';
                $action = 'User Updated';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'User has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'User can not be updated! Please try again later.'
                )));
            }
        }

        // DELETE USER
        function deleteUser($input){
            $now = strtotime('now');

            $delete_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_user WHERE user_id = '".$input['id']."'");

            if($delete_query){
                $module = 'User';
                $action = 'User Deleted';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'User has been deleted successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'User can not be deleted! Please try again later.'
                )));
            }
        }

        // ADD PARTS
        function addParts($input){
            $user_id = $_SESSION['user_id'];
            $now = strtotime('now');
            $parts_name = preg_replace('/\s\s+/', ' ', $input['parts_name']);

            if($input['type'])
                $type = $input['type'];
            else
                $type = 0;

            if($input['group'])
                $group = $input['group'];
            else
                $group = 0;

            if(mysqli_num_rows(mysqli_query($this->conn, "SELECT * FROM rrmsteel_parts WHERE parts_name = '$parts_name'"))){
                // DELETE IMAGE FROM SERVER
                // LOCAL
                unlink($_SERVER['DOCUMENT_ROOT'] . '/steel-assist/assets/images/uploads/' . $parts_info['parts_image']);

                // SERVER
                // unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/' . $parts_info['parts_image']);

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Parts name exists! Please try a different one.'
                )));
            } else{
                $insert_parts_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_parts(parts_name, parts_nickname, category, subcategory, subcategory_2, type, apply_group, inv_type, unit, alert_qty, parts_image, remarks, parts_created) VALUES('$parts_name', '".$input['parts_nickname']."', '".$input['category']."', '".$input['subcategory']."', '".$input['subcategory_2']."', '$type', '$group', '".$input['inv_type']."', '".$input['unit']."', '".$input['alert_qty']."', '".$input['parts_image']."', '".$input['remarks']."', '$now')");

                if($insert_parts_query){
                    $parts_id = mysqli_insert_id($this->conn);
                    $inventory_created = '1617170400';
                    
                    if($input['opening_qty'] == 0)
                        $parts_rate = 0;
                    else
                        $parts_rate = $input['opening_value'] / $input['opening_qty'];

                    $insert_inventory_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_inv_summary(parts_id, parts_qty, parts_rate, parts_avg_rate, inventory_created) VALUES('$parts_id', '".$input['opening_qty']."', '$parts_rate', '$parts_rate', '$inventory_created')");

                    if($insert_inventory_query){
                        $history_date = '2021-03-31';
                        $inventory_history_created = '1617170400';

                        $insert_inventory_history_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_inv_history(source, required_for, parts_id, opening_qty, opening_value, parts_rate, parts_avg_rate, received_qty, received_value, issued_qty, issued_value, closing_qty, closing_value, user_id, history_date, inventory_history_created) VALUES(0, 0, '$parts_id', '".$input['opening_qty']."', '".$input['opening_value']."', '$parts_rate', '$parts_rate', 0, 0, 0, 0, '".$input['opening_qty']."', '".$input['opening_value']."', '$user_id', '$history_date', '$inventory_history_created')");

                        if($insert_inventory_history_query){
                            $module = 'Parts';
                            $action = 'Parts Added; Inventory Added; Inventory History Added';
                            $user_id = $_SESSION['user_id'];
                            $action_time = $now;
                            $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                            $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                            if($audit_query){
                                exit(json_encode(array(
                                    'Type' => 'success',
                                    'Reply' => 'Parts has been added successfully! In addition to that, the inventory & inventory history has been created for this parts.'
                                )));
                            }
                        }
                    }
                } else{
                    // DELETE IMAGE FROM SERVER
                    // LOCAL
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/steel-assist/assets/images/uploads/' . $parts_info['parts_image']);

                    // SERVER
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/' . $parts_info['parts_image']);

                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => mysqli_error($insert_parts_query)
                    )));
                }
            }
        }

        // UPDATE PARTS
        function updateParts($input){
            $now = strtotime('now');

            if($input['type'])
                $type = $input['type'];
            else
                $type = 0;

            if($input['group'])
                $group = $input['group'];
            else
                $group = 0;

            if($input['parts_image'] !== ''){
                $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_image FROM rrmsteel_parts WHERE parts_id = '".$input['id']."' LIMIT 1"));

                // DELETE IMAGE FROM SERVER
                if(isset($parts_info)){
                    // LOCAL
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/steel-assist/assets/images/uploads/' . $parts_info['parts_image']);

                    // SERVER
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/' . $parts_info['parts_image']);
                }

                $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_parts SET parts_name = '".$input['parts_name']."', parts_nickname = '".$input['parts_nickname']."', category = '".$input['category']."', subcategory = '".$input['subcategory']."', subcategory_2 = '".$input['subcategory_2']."', type = '$type', apply_group = '$group', inv_type = '".$input['inv_type']."', unit = '".$input['unit']."', alert_qty = '".$input['alert_qty']."', parts_image = '".$input['parts_image']."', remarks = '".$input['remarks']."' WHERE parts_id = '".$input['id']."'");
            } else{
                $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_parts SET parts_name = '".$input['parts_name']."', parts_nickname = '".$input['parts_nickname']."', category = '".$input['category']."', subcategory = '".$input['subcategory']."', subcategory_2 = '".$input['subcategory_2']."', type = '$type', apply_group = '$group', inv_type = '".$input['inv_type']."', unit = '".$input['unit']."', alert_qty = '".$input['alert_qty']."', remarks = '".$input['remarks']."' WHERE parts_id = '".$input['id']."'");
            }

            if($update_query){
                $module = 'Parts';
                $action = 'Parts Updated';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Parts has been updated successfully!'
                    )));
                }
            } else{
                if($input['parts_image'] !== ''){
                    $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_image FROM rrmsteel_parts WHERE parts_id = '".$input['id']."' LIMIT 1"));

                    // DELETE IMAGE FROM SERVER
                    if(isset($parts_info)){
                        // LOCAL
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/steel-assist/assets/images/uploads/' . $parts_info['parts_image']);
    
                        // SERVER
                        // unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/' . $parts_info['parts_image']);
                    }
                }

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Parts can not be updated! Please try again later.'
                )));
            }
        }

        // DELETE PARTS
        function deleteParts($input){
            $now = strtotime('now');

            $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_image FROM rrmsteel_parts WHERE parts_id = '".$input['id']."' LIMIT 1"));

            // DELETE IMAGE FROM SERVER
            if(isset($parts_info)){
                // LOCAL
                unlink($_SERVER['DOCUMENT_ROOT'] . '/steel-assist/assets/images/uploads/' . $parts_info['parts_image']);

                // SERVER
                // unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/images/uploads/' . $parts_info['parts_image']);
            }

            $delete_parts_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_parts WHERE parts_id = '".$input['id']."'");

            if($delete_parts_query){
                $delete_inv_summary_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_inv_summary WHERE parts_id = '".$input['id']."'");

                if($delete_inv_summary_query){
                    $delete_inv_history_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_inv_history WHERE parts_id = '".$input['id']."'");

                    if($delete_inv_history_query){
                        $module = 'Parts';
                        $action = 'Parts Deleted';
                        $user_id = $_SESSION['user_id'];
                        $action_time = $now;
                        $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                        $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                        if($audit_query){
                            exit(json_encode(array(
                                'Type' => 'success',
                                'Reply' => 'Parts has been deleted successfully! In addition to that, this parts has been deleted from the inventory & inventory history.'
                            )));
                        }
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Parts can not be deleted! Please try again later.'
                )));
            }
        }

        // ISSUE PARTS
        function issuePartsQty($input, $inventory_history_id = null){
            $now = strtotime('now');

            $source = $input['source'];
            $parts_id = $input['parts_id'];
            $required_for = $input['required_for'];
            $action_date = $input['action_date'];
            $qty = $input['qty'];
            $price = (($inventory_history_id != null) ? $input['price'] : 0);

            $inventory_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary WHERE parts_id = '$parts_id' LIMIT 1"));
            $parts_qty = $inventory_info['parts_qty'];
            $parts_rate = $inventory_info['parts_rate'];
            $parts_avg_rate = $inventory_info['parts_avg_rate'];

            if($inventory_history_id == null){
                $upd_qty = $parts_qty - $qty;
            } else{
                $inventory_history_info0 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT issued_qty FROM rrmsteel_inv_history WHERE inventory_history_id = '$inventory_history_id'"));

                $issued_qty = $inventory_history_info0['issued_qty'];

                if($qty > $issued_qty)
                    $upd_qty = $parts_qty - ($qty - $issued_qty);
                elseif($issued_qty > $qty)
                    $upd_qty = $parts_qty + ($issued_qty - $qty);
                else
                    $upd_qty = $parts_qty;
            }

            $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_inv_summary SET parts_qty = '$upd_qty' WHERE parts_id = '$parts_id'");

            if($update_query){
                $user_id = $_SESSION['user_id'];

                // SELECTED DAY
                $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT opening_qty, opening_value, parts_rate, parts_avg_rate FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' LIMIT 1"));

                // BEFORE SELECTED DAY
                $inventory_history_info1 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_rate, parts_avg_rate, closing_qty, closing_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date < '$action_date' ORDER BY history_date DESC, inventory_history_created DESC LIMIT 1"));

                // IF INSERTED DATE EXISTS IN DB ALREADY
                if(isset($inventory_history_info)){
                    $opening_qty = $inventory_history_info['opening_qty'];
                    $opening_value = $inventory_history_info['opening_value'];
                    $parts_rate2 = $inventory_history_info['parts_rate'];
                    $parts_avg_rate2 = $inventory_history_info['parts_avg_rate'];
                } else{
                    $opening_qty = $inventory_history_info1['closing_qty'];
                    $opening_value = $inventory_history_info1['closing_value'];
                    $parts_rate2 = $inventory_history_info1['parts_rate'];
                    $parts_avg_rate2 = $inventory_history_info1['parts_avg_rate'];
                }

                if($parts_rate2 > 0){
                    $parts_rate = $parts_rate2;
                }

                if($parts_avg_rate2 > 0){
                    $parts_avg_rate = $parts_avg_rate2;
                }

                $issued_value = $parts_avg_rate * $qty;

                if($inventory_history_id == null){
                    $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_id FROM rrmsteel_inv_history WHERE required_for = '$required_for' AND parts_id = '$parts_id' AND source > 1 AND history_date = '$action_date'"));
                    
                    if(isset($inventory_history_info2['inventory_history_id'])){
                        $inventory_history_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' AND inventory_history_id <= '".$inventory_history_info2['inventory_history_id']."'"));
                    } else{
                        $inventory_history_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date'"));
                    }
                } else{
                    $inventory_history_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' AND inventory_history_id < '$inventory_history_id'"));
                }

                $upd_received_qty = $inventory_history_info3['tot_received_qty'];
                $upd_received_val = $inventory_history_info3['tot_received_val'];
                $tot_issued_qty = $inventory_history_info3['tot_issued_qty'] + $qty;
                $tot_issued_val = (($inventory_history_id == null) ? ($inventory_history_info3['tot_issued_val'] + $issued_value) : $price);

                $closing_qty = ($opening_qty + $upd_received_qty) - $tot_issued_qty;
                $closing_value = ($opening_value + $upd_received_val) - $tot_issued_val;

                $inventory_history_info4 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_id, issued_qty, issued_value FROM rrmsteel_inv_history WHERE required_for = '$required_for' AND parts_id = '$parts_id' AND source > 1 AND history_date = '$action_date'"));

                if(isset($inventory_history_info4)){
                    $inventory_history_id2 = $inventory_history_info4['inventory_history_id'];
                    $upd_issued_qty = $inventory_history_info4['issued_qty'] + $qty;
                    $upd_issued_value = (($inventory_history_id == null) ? ($inventory_history_info4['issued_value'] + $issued_value) : $price);

                    if($inventory_history_id == null){
                        $inventory_history_query = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET issued_qty = '$upd_issued_qty', issued_value = '$upd_issued_value', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id2'");
                    } else{
                        $inventory_history_query = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET issued_qty = '$qty', issued_value = '$upd_issued_value', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id2'");
                    }
                } else{
                    $upd_issued_value = (($inventory_history_id == null) ? $issued_value : $price);

                    $inventory_history_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_inv_history(source, required_for, parts_id, opening_qty, opening_value, parts_rate, parts_avg_rate, received_qty, received_value, issued_qty, issued_value, closing_qty, closing_value, user_id, history_date, inventory_history_created) VALUES('$source', '$required_for', '$parts_id', '$opening_qty', '$opening_value', '$parts_rate', '$parts_avg_rate', 0, 0, '$qty', $upd_issued_value, $closing_qty, $closing_value, '$user_id', '$action_date', '$now')");

                    $last_history_id = mysqli_insert_id($this->conn);

                    $inventory_history_info44 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_created FROM rrmsteel_inv_history WHERE inventory_history_id = '$last_history_id'"));
                }

                if($inventory_history_id == null){
                    $inventory_history_info5 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_created FROM rrmsteel_inv_history WHERE required_for = '$required_for' AND parts_id = '$parts_id' AND source > 1 AND history_date = '$action_date' ORDER BY inventory_history_created DESC LIMIT 1"));
                    
                    if(isset($inventory_history_info5['inventory_history_created'])){
                        $inventory_history_query2 = mysqli_query($this->conn, "SELECT inventory_history_id, source, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND ((history_date = '$action_date' AND inventory_history_created > '".$inventory_history_info5['inventory_history_created']."') OR history_date > '$action_date') ORDER BY history_date, inventory_history_created");
                    } else{
                        $inventory_history_query2 = mysqli_query($this->conn, "SELECT inventory_history_id, source, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND ((history_date = '$action_date' AND inventory_history_created > '".$inventory_history_info44['inventory_history_created']."') OR history_date > '$action_date') ORDER BY history_date, inventory_history_created");
                    }
                } else{
                    $inventory_history_info5 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_created FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND inventory_history_id = '$inventory_history_id'"));
                    $inventory_history_created = $inventory_history_info5['inventory_history_created'];

                    $inventory_history_query2 = mysqli_query($this->conn, "SELECT inventory_history_id, source, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND ((history_date = '$action_date' AND inventory_history_created > '$inventory_history_created') OR history_date > '$action_date') ORDER BY history_date, inventory_history_created");
                }

                if(mysqli_num_rows($inventory_history_query2) > 0){
                    while($row = mysqli_fetch_assoc($inventory_history_query2)){
                        $inventory_history_id3 = $row['inventory_history_id'];
                        $source = $row['source'];
                        $history_date = $row['history_date'];

                        // BEFORE SELECTED DAY
                        $inventory_history_info6 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_rate, parts_avg_rate, closing_qty, closing_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date < '$history_date' ORDER BY history_date DESC, inventory_history_created DESC LIMIT 1"));
                        
                        $opening_qty = $inventory_history_info6['closing_qty'];
                        $opening_value = $inventory_history_info6['closing_value'];
                        $parts_rate = $inventory_history_info6['parts_rate'];
                        $parts_avg_rate = $inventory_history_info6['parts_avg_rate'];

                        $inventory_history_info7 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$history_date' AND inventory_history_id <= '$inventory_history_id3'"));

                        $upd_received_qty = $inventory_history_info7['tot_received_qty'];
                        $upd_received_val = $inventory_history_info7['tot_received_val'];
                        $tot_issued_qty = $inventory_history_info7['tot_issued_qty'];
                        $tot_issued_val = $inventory_history_info7['tot_issued_val'];

                        $closing_qty = ($opening_qty + $upd_received_qty) - $tot_issued_qty;
                        $closing_value = ($opening_value + $upd_received_val) - $tot_issued_val;

                        if($source == 1){
                            $upd_avg_rate = (($opening_value + $upd_received_val) / ($opening_qty + $upd_received_qty));

                            $update_query3 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_summary SET parts_avg_rate = '$upd_avg_rate' WHERE parts_id = '$parts_id'");

                            $inventory_history_query3 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET opening_qty = '$opening_qty', opening_value = '$opening_value', parts_avg_rate = '$upd_avg_rate', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id3'");
                        } elseif($source > 1){
                            $inventory_history_query3 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET opening_qty = '$opening_qty', opening_value = '$opening_value', parts_rate = '$parts_rate', parts_avg_rate = '$parts_avg_rate', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id3'");
                        }
                    }
                }

                if($input['source'] < 3){
                    $module = 'Inventory';
                    $action_taken = 'Inventory Updated';
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action_taken', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Inventory has been updated successfully!'
                        )));
                    }
                } else{
                    return true;
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Inventory can not be updated! Please try again later.'
                )));
            }
        }

        // PRE RECEIVE PARTS
        function preReceivePartsQty($input){
            $parts_id = $input['parts_id'];
            $action_date = $input['action_date'];
            $qty = $input['qty'];
            $price = $input['price'];
            $random_numb = rand(1, 2);

            $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT category FROM rrmsteel_parts WHERE parts_id = '$parts_id' LIMIT 1"));

            // BILL & LOAN QUERIES
            if($parts_info['category'] == 2){
                $bill_query = mysqli_query($this->conn, "SELECT bill_id, required_for, qty, received_qty, price FROM rrmsteel_con_bill WHERE parts_id = '$parts_id' AND generate_date <= '$action_date' AND generate_status = 1 GROUP BY bill_id HAVING SUM(received_qty) != SUM(qty)");

                $bill_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(qty) - SUM(received_qty)) AS tot_rem_qty FROM rrmsteel_con_bill WHERE parts_id = '$parts_id' AND generate_date <= '$action_date' AND generate_status = 1 GROUP BY parts_id HAVING SUM(received_qty) != SUM(qty)"));

                $loan_data_query = mysqli_query($this->conn, "SELECT loan_data_id, required_for, parts_qty, received_qty, (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty, price FROM rrmsteel_con_loan_data WHERE parts_id = '$parts_id' AND loan_date <= '$action_date' GROUP BY loan_data_id HAVING SUM(received_qty) != SUM(parts_qty)");

                $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty FROM rrmsteel_con_loan_data WHERE parts_id = '$parts_id' AND loan_date <= '$action_date' GROUP BY parts_id HAVING SUM(received_qty) != SUM(parts_qty)"));
            } else{
                $bill_query = mysqli_query($this->conn, "SELECT bill_id, required_for, qty, received_qty, (SUM(qty) - SUM(received_qty)) AS tot_rem_qty, price FROM rrmsteel_spr_bill WHERE parts_id = '$parts_id' AND generate_date <= '$action_date' AND generate_status = 1 GROUP BY bill_id HAVING SUM(received_qty) != SUM(qty)");

                $bill_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(qty) - SUM(received_qty)) AS tot_rem_qty FROM rrmsteel_spr_bill WHERE parts_id = '$parts_id' AND generate_date <= '$action_date' AND generate_status = 1 GROUP BY parts_id HAVING SUM(received_qty) != SUM(qty)"));

                $loan_data_query = mysqli_query($this->conn, "SELECT loan_data_id, required_for, parts_qty, received_qty, (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty, price FROM rrmsteel_spr_loan_data WHERE parts_id = '$parts_id' AND loan_date <= '$action_date' GROUP BY loan_data_id HAVING SUM(received_qty) != SUM(parts_qty)");

                $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty FROM rrmsteel_spr_loan_data WHERE parts_id = '$parts_id' AND loan_date <= '$action_date' GROUP BY parts_id HAVING SUM(received_qty) != SUM(parts_qty)"));
            }

            $bill_id_arr = [];
            $loan_data_id_arr = [];
            $required_for_arr = [];
            $parts_qty_arr = [];
            $parts_price_arr = [];
            $data = [];

            // GET AVAILABLE PARTS DATA FROM BILL & LOAN RANDOMLY
            if($random_numb == 1){
                $tot_qty = 0;
                $upd_qty_2 = 0;
                $i = 0;

                while($row = mysqli_fetch_assoc($bill_query)){
                    $data[$i]['bill_id'] = $row['bill_id'];
                    $data[$i]['required_for'] = $row['required_for'];

                    $rem_qty = $row['qty'] - $row['received_qty'];
                    $tot_qty += $rem_qty;
                    $tot_rem_qty = $bill_info['tot_rem_qty'];

                    if($qty > $tot_rem_qty){
                        $upd_qty = $tot_rem_qty;
                        $upd_qty_2 = $qty - $tot_rem_qty;
                    } else{
                        $upd_qty = $qty;
                        $upd_qty_2 = 0;
                    }

                    if($upd_qty <= $tot_qty){
                        if($i == 0){
                            $parts_qty = $upd_qty;
                            $parts_price = ($price * $parts_qty) / $upd_qty;
                        } else{
                            if($upd_qty == $tot_qty){
                                $parts_qty = $rem_qty;
                                $parts_price = ($price * $parts_qty) / $upd_qty;
                            } else{
                                $parts_qty = $tot_qty - $upd_qty;
                                $parts_qty = $rem_qty - $parts_qty;
                                $parts_price = ($price * $parts_qty) / $upd_qty;
                            }
                        }

                        $data[$i]['parts_qty'] = $parts_qty;
                        $data[$i]['parts_price'] = $parts_price;

                        break;
                    } else{
                        $parts_qty = $rem_qty;
                        $parts_price = ($price * $parts_qty) / $upd_qty;

                        $data[$i]['parts_qty'] = $parts_qty;
                        $data[$i]['parts_price'] = $parts_price;
                    }

                    $i++;
                }

                $bill_data_count = mysqli_num_rows($bill_query);

                if($bill_data_count == 0 || $upd_qty_2 > 0){
                    $tot_qty = 0;
                    $j = 0;

                    while($row = mysqli_fetch_assoc($loan_data_query)){
                        if($bill_data_count == 0){
                            $index = $j;
                        } else{
                            $index = $i+$j+1;
                        }

                        $data[$index]['loan_data_id'] = $row['loan_data_id'];
                        $data[$index]['required_for'] = $row['required_for'];

                        $rem_qty = $row['parts_qty'] - $row['received_qty'];
                        $tot_qty += $rem_qty;
                        $tot_rem_qty = $loan_data_info['tot_rem_qty'];

                        if(($bill_data_count == 0 && $tot_rem_qty <= $tot_qty) || ($bill_data_count > 0 && $upd_qty_2 <= $tot_qty)){
                            if($j == 0){
                                $parts_qty = (($bill_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                $parts_price = ($price * $parts_qty) / (($bill_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                            } else{
                                if($upd_qty_2 == $tot_qty){
                                    $parts_qty = $rem_qty;
                                    $parts_price = ($price * $parts_qty) / (($bill_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                } else{
                                    $parts_qty = $tot_qty - (($bill_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                    $parts_qty = $rem_qty - $parts_qty;
                                    $parts_price = ($price * $parts_qty) / (($bill_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                }
                            }

                            $data[$index]['parts_qty'] = $parts_qty;
                            $data[$index]['parts_price'] = $parts_price;

                            break;
                        } else{
                            $parts_qty = $rem_qty;
                            $parts_price = ($price * $parts_qty) / (($bill_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);

                            $data[$index]['parts_qty'] = $parts_qty;
                            $data[$index]['parts_price'] = $parts_price;
                        }

                        $j++;
                    }
                }
            } else{
                $tot_qty = 0;
                $upd_qty_2 = 0;
                $i = 0;

                while($row = mysqli_fetch_assoc($loan_data_query)){
                    $data[$i]['loan_data_id'] = $row['loan_data_id'];
                    $data[$i]['required_for'] = $row['required_for'];

                    $rem_qty = $row['parts_qty'] - $row['received_qty'];
                    $tot_qty += $rem_qty;
                    $tot_rem_qty = $loan_data_info['tot_rem_qty'];

                    if($qty > $tot_rem_qty){
                        $upd_qty = $tot_rem_qty;
                        $upd_qty_2 = $qty - $tot_rem_qty;
                    } else{
                        $upd_qty = $qty;
                        $upd_qty_2 = 0;
                    }

                    if($upd_qty <= $tot_qty){
                        if($i == 0){
                            $parts_qty = $upd_qty;
                            $parts_price = ($price * $parts_qty) / $upd_qty;
                        } else{
                            if($upd_qty == $tot_qty){
                                $parts_qty = $rem_qty;
                                $parts_price = ($price * $parts_qty) / $upd_qty;
                            } else{
                                $parts_qty = $tot_qty - $upd_qty;
                                $parts_qty = $rem_qty - $parts_qty;
                                $parts_price = ($price * $parts_qty) / $upd_qty;
                            }
                        }

                        $data[$i]['parts_qty'] = $parts_qty;
                        $data[$i]['parts_price'] = $parts_price;

                        break;
                    } else{
                        $parts_qty = $rem_qty;
                        $parts_price = ($price * $parts_qty) / $upd_qty;

                        $data[$i]['parts_qty'] = $parts_qty;
                        $data[$i]['parts_price'] = $parts_price;
                    }

                    $i++;
                }

                $loan_data_count = mysqli_num_rows($loan_data_query);

                if($loan_data_count == 0 || $upd_qty_2 > 0){
                    $tot_qty = 0;
                    $j = 0;

                    while($row = mysqli_fetch_assoc($bill_query)){
                        if($loan_data_count == 0){
                            $index = $j;
                        } else{
                            $index = $i+$j+1;
                        }

                        $data[$index]['bill_id'] = $row['bill_id'];
                        $data[$index]['required_for'] = $row['required_for'];

                        $rem_qty = $row['qty'] - $row['received_qty'];
                        $tot_qty += $rem_qty;
                        $tot_rem_qty = $bill_info['tot_rem_qty'];

                        if(($loan_data_count == 0 && $tot_rem_qty <= $tot_qty) || ($loan_data_count > 0 && $upd_qty_2 <= $tot_qty)){
                            if($j == 0){
                                $parts_qty = (($loan_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                $parts_price = ($price * $parts_qty) / (($loan_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                            } else{
                                if($upd_qty_2 == $tot_qty){
                                    $parts_qty = $rem_qty;
                                    $parts_price = ($price * $parts_qty) / (($loan_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                } else{
                                    $parts_qty = $tot_qty - (($loan_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                    $parts_qty = $rem_qty - $parts_qty;
                                    $parts_price = ($price * $parts_qty) / (($loan_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);
                                }
                            }

                            $data[$index]['parts_qty'] = $parts_qty;
                            $data[$index]['parts_price'] = $parts_price;

                            break;
                        } else{
                            $parts_qty = $rem_qty;
                            $parts_price = ($price * $parts_qty) / (($loan_data_count > 0) ? $upd_qty_2 : $tot_rem_qty);

                            $data[$index]['parts_qty'] = $parts_qty;
                            $data[$index]['parts_price'] = $parts_price;
                        }

                        $j++;
                    }
                }
            }

            // UPDATE PARTS QTY IN BILL & LOAN
            foreach($data AS $val){
                $bill_id = (isset($val['bill_id']) ? $val['bill_id'] : null);
                $loan_data_id = (isset($val['loan_data_id']) ? $val['loan_data_id'] : null);
                $qty = $val['parts_qty'];

                if($bill_id){
                    if($parts_info['category'] == 2){
                        $upd_bill_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_bill SET received_qty = received_qty + '$qty' WHERE bill_id = '$bill_id'");
                    } else{
                        $upd_bill_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_bill SET received_qty = received_qty + '$qty' WHERE bill_id = '$bill_id'");
                    }
                } else{
                    if($parts_info['category'] == 2){
                        $upd_loan_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan_data SET received_qty = received_qty + '$qty' WHERE loan_data_id = '$loan_data_id'");
                    } else{
                        $upd_loan_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan_data SET received_qty = received_qty + '$qty' WHERE loan_data_id = '$loan_data_id'");
                    }
                }
            }

            // CREATE ARRAY OF UNIQUE PARTS DATA
            $parts_data_arr = [];
            
            foreach($data AS $val){
                if(!isset($parts_data_arr[$val['required_for']])){
                    $parts_data_arr[$val['required_for']] = $val;
                } else{
                    $parts_data_arr[$val['required_for']]['parts_qty'] += $val['parts_qty'];
                }
            }

            $parts_data_arr = array_values($parts_data_arr);

            // RECEIVE PARTS QTY
            $flag = 0;

            foreach($parts_data_arr AS $key => $val){
                $required_for = $val['required_for'];
                $qty = $val['parts_qty'];
                $price = $val['parts_price'];

                $upd_input = [
                    'parts_id' => $input['parts_id'],
                    'required_for' => $required_for,
                    'action_date' => $input['action_date'],
                    'qty' => $qty,
                    'price' => $price,
                    'source' => 1
                ];

                $now = strtotime('now') + $key;

                if($this->receivePartsQty($upd_input, $now)){
                    $flag = 1;
                } else{
                    $flag = 0;
                }
            }

            if($flag == 1){
                exit(json_encode(array(
                    'Type' => 'success',
                    'Reply' => 'Inventory has been updated successfully!'
                )));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Inventory can not be updated! Please try again later.'
                )));
            }
        }

        // RECEIVE PARTS
        function receivePartsQty($input, $timestamp = null, $inventory_history_id = null){
            if($timestamp){
                $now = $timestamp;
            } else{
                $now = strtotime('now');
            }

            $source = $input['source'];
            $parts_id = $input['parts_id'];
            $action_date = $input['action_date'];
            $required_for = $input['required_for'];
            $qty = $input['qty'];
            $price = $input['price'];

            $inventory_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_inv_summary WHERE parts_id = '$parts_id' LIMIT 1"));
            $parts_qty = $inventory_info['parts_qty'];
            
            if($inventory_history_id == null){
                $upd_qty = $parts_qty + $qty;
            } else{
                $inventory_history_info0 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT received_qty FROM rrmsteel_inv_history WHERE inventory_history_id = '$inventory_history_id'"));

                $received_qty = $inventory_history_info0['received_qty'];

                if($qty > $received_qty)
                    $upd_qty = $parts_qty + ($qty - $received_qty);
                elseif($received_qty > $qty)
                    $upd_qty = $parts_qty - ($received_qty - $qty);
                else
                    $upd_qty = $parts_qty;
            }

            $upd_rate = $price / $qty;

            $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_inv_summary SET parts_qty = '$upd_qty', parts_rate = '$upd_rate' WHERE parts_id = '$parts_id'");

            if($update_query){
                $user_id = $_SESSION['user_id'];

                // SELECTED DAY
                $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT opening_qty, opening_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' LIMIT 1"));

                // BEFORE SELECTED DAY
                $inventory_history_info1 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT closing_qty, closing_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date < '$action_date' ORDER BY history_date DESC, inventory_history_created DESC LIMIT 1"));

                // IF INSERTED DATE EXISTS IN DB ALREADY
                if(isset($inventory_history_info)){
                    $opening_qty = $inventory_history_info['opening_qty'];
                    $opening_value = $inventory_history_info['opening_value'];
                } else{
                    $opening_qty = $inventory_history_info1['closing_qty'];
                    $opening_value = $inventory_history_info1['closing_value'];
                }

                if($inventory_history_id == null){
                    $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_id FROM rrmsteel_inv_history WHERE required_for = '$required_for' AND parts_id = '$parts_id' AND source = 1 AND history_date = '$action_date'"));
                    
                    if(isset($inventory_history_info2['inventory_history_id'])){
                        $inventory_history_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' AND inventory_history_id <= '".$inventory_history_info2['inventory_history_id']."'"));
                    } else{
                        $inventory_history_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date'"));
                    }
                } else{
                    $inventory_history_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' AND inventory_history_id < '$inventory_history_id'"));
                }

                $upd_received_qty = $inventory_history_info3['tot_received_qty'] + $qty;
                $upd_received_val = $inventory_history_info3['tot_received_val'] + $price;
                $tot_issued_qty = $inventory_history_info3['tot_issued_qty'];
                $tot_issued_val = $inventory_history_info3['tot_issued_val'];

                $upd_avg_rate = (($opening_value + $upd_received_val) / ($opening_qty + $upd_received_qty));

                $update_query2 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_summary SET parts_avg_rate = '$upd_avg_rate' WHERE parts_id = '$parts_id'");

                $closing_qty = ($opening_qty + $upd_received_qty) - $tot_issued_qty;
                $closing_value = ($opening_value + $upd_received_val) - $tot_issued_val;

                $inventory_history_info4 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_id, received_qty, received_value FROM rrmsteel_inv_history WHERE required_for = '$required_for' AND parts_id = '$parts_id' AND source = 1 AND history_date = '$action_date'"));

                if(isset($inventory_history_info4)){
                    if($inventory_history_id == null){
                        $inventory_history_id2 = $inventory_history_info4['inventory_history_id'];
                        $upd_received_qty2 = $inventory_history_info4['received_qty'] + $qty ;
                        $upd_received_val2 = $inventory_history_info4['received_value'] + $price;

                        $inventory_history_query = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET parts_rate = '$upd_rate', parts_avg_rate = '$upd_avg_rate', received_qty = '$upd_received_qty2', received_value = '$upd_received_val2', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id2'");
                    } else{
                        $inventory_history_query = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET parts_rate = '$upd_rate', parts_avg_rate = '$upd_avg_rate', received_qty = '$qty', received_value = '$price', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id'");
                    }
                } else{
                    $inventory_history_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_inv_history(source, required_for, parts_id, opening_qty, opening_value, parts_rate, parts_avg_rate, received_qty, received_value, issued_qty, issued_value, closing_qty, closing_value, user_id, history_date, inventory_history_created) VALUES('$source', '$required_for', '$parts_id', '$opening_qty', '$opening_value', '$upd_rate', '$upd_avg_rate', '$qty', '$price', 0, 0, $closing_qty, $closing_value, '$user_id', '$action_date', '$now')");

                    $last_history_id = mysqli_insert_id($this->conn);

                    $inventory_history_info44 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_created FROM rrmsteel_inv_history WHERE inventory_history_id = '$last_history_id'"));
                }

                if($inventory_history_id == null){
                    $inventory_history_info5 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_created FROM rrmsteel_inv_history WHERE required_for = '$required_for' AND parts_id = '$parts_id' AND source = 1 AND history_date = '$action_date' ORDER BY inventory_history_created DESC LIMIT 1"));
                    
                    if(isset($inventory_history_info5['inventory_history_created'])){
                        $inventory_history_query2 = mysqli_query($this->conn, "SELECT inventory_history_id, source, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND ((history_date = '$action_date' AND inventory_history_created > '".$inventory_history_info5['inventory_history_created']."') OR history_date > '$action_date') ORDER BY history_date, inventory_history_created");
                    } else{
                        $inventory_history_query2 = mysqli_query($this->conn, "SELECT inventory_history_id, source, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND ((history_date = '$action_date' AND inventory_history_created > '".$inventory_history_info44['inventory_history_created']."') OR history_date > '$action_date') ORDER BY history_date, inventory_history_created");
                    }
                } else{
                    $inventory_history_info5 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT inventory_history_created FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND inventory_history_id = '$inventory_history_id'"));
                    $inventory_history_created = $inventory_history_info5['inventory_history_created'];

                    $inventory_history_query2 = mysqli_query($this->conn, "SELECT inventory_history_id, source, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND ((history_date = '$action_date' AND inventory_history_created > '$inventory_history_created') OR history_date > '$action_date') ORDER BY history_date, inventory_history_created");
                }

                if(mysqli_num_rows($inventory_history_query2) > 0){
                    while($row = mysqli_fetch_assoc($inventory_history_query2)){
                        $inventory_history_id3 = $row['inventory_history_id'];
                        $source = $row['source'];
                        $history_date = $row['history_date'];

                        // BEFORE SELECTED DAY
                        $inventory_history_info6 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_rate, parts_avg_rate, closing_qty, closing_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date < '$history_date' ORDER BY history_date DESC, inventory_history_created DESC LIMIT 1"));
                        
                        $opening_qty = $inventory_history_info6['closing_qty'];
                        $opening_value = $inventory_history_info6['closing_value'];
                        $parts_rate = $inventory_history_info6['parts_rate'];
                        $parts_avg_rate = $inventory_history_info6['parts_avg_rate'];

                        $inventory_history_info7 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS tot_received_qty, SUM(received_value) AS tot_received_val, SUM(issued_qty) AS tot_issued_qty, SUM(issued_value) AS tot_issued_val FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$history_date' AND inventory_history_id <= '$inventory_history_id3'"));

                        $upd_received_qty = $inventory_history_info7['tot_received_qty'];
                        $upd_received_val = $inventory_history_info7['tot_received_val'];
                        $tot_issued_qty = $inventory_history_info7['tot_issued_qty'];
                        $tot_issued_val = $inventory_history_info7['tot_issued_val'];

                        $closing_qty = ($opening_qty + $upd_received_qty) - $tot_issued_qty;
                        $closing_value = ($opening_value + $upd_received_val) - $tot_issued_val;

                        if($source == 1){
                            $upd_avg_rate = (($opening_value + $upd_received_val) / ($opening_qty + $upd_received_qty));

                            $update_query3 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_summary SET parts_avg_rate = '$upd_avg_rate' WHERE parts_id = '$parts_id'");

                            $inventory_history_query3 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET opening_qty = '$opening_qty', opening_value = '$opening_value', parts_avg_rate = '$upd_avg_rate', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id3'");
                        } elseif($source > 1){
                            $inventory_history_query3 = mysqli_query($this->conn, "UPDATE rrmsteel_inv_history SET opening_qty = '$opening_qty', opening_value = '$opening_value', parts_rate = '$parts_rate', parts_avg_rate = '$parts_avg_rate', closing_qty = '$closing_qty', closing_value = '$closing_value' WHERE inventory_history_id = '$inventory_history_id3'");
                        }
                    }
                }

                if($source < 3){
                    $module = 'Inventory';
                    $action_taken = 'Inventory Updated';
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action_taken', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        if($source == 1 && $inventory_history_id == null){
                            return true;
                        } else{
                            exit(json_encode(array(
                                'Type' => 'success',
                                'Reply' => 'Inventory has been updated successfully!'
                            )));
                        }
                    }
                } else{
                    return;
                }
            } else{
                if($source == 1 && $inventory_history_id == null){
                    return false;
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Inventory can not be updated! Please try again later.'
                    )));
                }
            }
        }

        // ADD PARTY
        function addParty($input){
            $now = strtotime('now');

            $insert_party_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_party(party_name, party_mobile, party_address, opening_ledger_balance, party_remarks, party_created) VALUES('".$input['party_name']."', '".$input['mobile']."', '".$input['address']."', '".$input['opening_ledger_balance']."', '".$input['remarks']."', '$now')");

            if($insert_party_query){
                $party_id = mysqli_insert_id($this->conn);

                if($input['opening_ledger_balance'] < 0){
                    $debit = 0;
                    $credit = abs($input['opening_ledger_balance']);
                } else{
                    $debit = $input['opening_ledger_balance'];
                    $credit = 0;
                }

                $insert_ledger_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_party_ledger(party_id, description, debit, credit, ledger_created) VALUES('$party_id', 'Opening', '$debit', '$credit', '$now')");

                if($insert_ledger_query){
                    $module = 'Party';
                    $action = 'Party Added';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Party has been added successfully! In addition to that, a ledger has been created against this party.'
                        )));
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Party can not be added! Please try again later.'
                )));
            }
        }

        // ADD PARTY LEDGER
        function addPartyLedger($input){
            $now = strtotime('now');

            $insert_ledger_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_party_ledger(party_id, description, debit, credit, ledger_created) VALUES('".$input['id']."', 'Payment', 0, '".$input['payment']."', '$now')");

            if($insert_ledger_query){
                $module = 'Party';
                $action = 'Party Ledger Added';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Party ledger has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Party ledger can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE PARTY
        function updateParty($input){
            $now = strtotime('now');
            
            $update_query = mysqli_query($this->conn, "UPDATE rrmsteel_party SET party_name = '".$input['party_name']."', party_mobile = '".$input['mobile']."', party_address = '".$input['address']."', party_remarks = '".$input['remarks']."' WHERE party_id = '".$input['id']."'");

            if($update_query){
                $module = 'Party';
                $action = 'Party Updated';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Party has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Party can not be updated! Please try again later.'
                )));
            }
        }

        // DELETE PARTY
        function deleteParty($input){
            $now = strtotime('now');

            $delete_party_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_party WHERE party_id = '".$input['id']."'");

            if($delete_party_query){
                $delete_ledger_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_party_ledger WHERE party_id = '".$input['id']."'");

                if($delete_ledger_query){
                    $module = 'Party';
                    $action = 'Party Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Party has been deleted successfully! In addition to that, ledger record for this party has been deleted.'
                        )));
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Party can not be deleted! Please try again later.'
                )));
            }
        }

        // ADD CONSUMABLE REQUISITION
        function addConRequisition($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];

            // INSERT REQUISITION
            if($user_category == 1){
                $insert_requisition_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_requisition(requisition_by, s_approval_status, s_approved_by, p_approval_status, p_approved_by, approval_status, approved_by, requisition_created) VALUES(1, 1, '$user_id', 1, '$user_id', 1, '$user_id', '$now')");
            } elseif($user_category == 2){
                $insert_requisition_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_requisition(requisition_by, s_approval_status, s_approved_by, p_approval_status, p_approved_by, approval_status, approved_by, requisition_created) VALUES('$user_id', 0, 0, 0, 0, 0, 0, '$now')");
            }

            if($insert_requisition_query){
                $requisition_id = mysqli_insert_id($this->conn);

                $js_data = json_decode($input);

                foreach($js_data as $key => $value){
                    $required_for = mysqli_real_escape_string($this->conn, $value->required_for);
                    $parts = explode('|', mysqli_real_escape_string($this->conn, $value->parts));
                    $parts_id = $parts[0];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $usage = mysqli_real_escape_string($this->conn, $value->use);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $loan = mysqli_real_escape_string($this->conn, $value->loan);
                    
                    // INSERT REQUISITION DATA
                    $insert_requisition_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_requisition_data(required_for, parts_id, parts_qty, parts_usage, remarks, loan, requisition_id, requisition_data_created) VALUES('$required_for', '$parts_id', '$qty', '$usage', '$remarks', '$loan', '$requisition_id', '$now')");
                }
                
                // AUDIT LOG
                $module = 'Requisition';
                $action = 'Consumable Requisition Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Requisition has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE CONSUMABLE REQUISITION
        function updateConRequisition($input1, $input2, $input3){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];

            if($input2 == 1){
                // UPDATE REQUISITION
                if($user_category == 1){
                    $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET approval_status = '$input2', approved_by = '$user_id' WHERE requisition_id = '$input1'");
                } elseif($user_category == 3){
                    // $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET s_approval_status = '$input2', s_approved_by = '$user_id' WHERE requisition_id = '$input1'");

                    $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET approval_status = '$input2', approved_by = '$user_id', s_approval_status = '$input2', s_approved_by = '$user_id' WHERE requisition_id = '$input1'");
                } elseif($user_category == 4){
                    $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET p_approval_status = '$input2', p_approved_by = '$user_id' WHERE requisition_id = '$input1'");
                }

                if($update_requisition_query){
                    // DELETE REQUISITION DATA
                    $delete_requisition_data_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_requisition_data WHERE requisition_id = '$input1'");
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Requisition can not be updated! Please try again later.'
                    )));
                }
            } else{
                // DELETE REQUISITION DATA
                $delete_requisition_data_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_requisition_data WHERE requisition_id = '$input1'");
            }

            if($delete_requisition_data_query){
                $js_data = json_decode($input3);

                foreach($js_data as $key => $value){
                    $required_for = mysqli_real_escape_string($this->conn, $value->required_for);
                    $parts = explode('|', mysqli_real_escape_string($this->conn, $value->parts));
                    $parts_id = $parts[0];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $usage = mysqli_real_escape_string($this->conn, $value->use);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $loan = mysqli_real_escape_string($this->conn, $value->loan);

                    // INSERT REQUISITION DATA
                    $insert_requisition_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_requisition_data(required_for, parts_id, parts_qty, parts_usage, remarks, loan, requisition_id, requisition_data_created) VALUES('$required_for', '$parts_id', '$qty', '$usage', '$remarks', '$loan', '$input1', '$now')");
                }

                // AUDIT LOG
                $module = 'Requisition';
                $action = 'Consumable Requisition Updated';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Requisition has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be updated! Please try again later.'
                )));
            }
        }

        // APPROVE CONSUMABLE REQUISITION
        function approveConRequisition($input){
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];
            $now = strtotime('now');

            if($user_category == 1){
                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET approval_status = 1, approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            } elseif($user_category == 3){
                // $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET s_approval_status = 1, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");

                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET approval_status = 1, approved_by = '$user_id', s_approval_status = 1, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            } elseif($user_category == 4){
                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET p_approval_status = 1, p_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            }

            if($update_requisition_query){
                $module = 'Requisition';
                $action = 'Consumable Requisition Approved';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    if($user_category == 4)
                        $reply = 'Requisition has been accepted successfully!';
                    else
                        $reply = 'Requisition has been approved successfully!';

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $reply
                    )));
                }
            } else{
                if($user_category == 4)
                    $reply = 'Requisition can not be accepted! Please try again later.';
                else
                    $reply = 'Requisition can not be approved! Please try again later.';

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => $reply
                )));
            }
        }

        // REJECT CONSUMABLE REQUISITION
        function rejectConRequisition($input){
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];
            $now = strtotime('now');

            if($user_category == 1){
                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET approval_status = 2, approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            } elseif($user_category == 3){
                // $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET s_approval_status = 2, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");

                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_requisition SET approval_status = 2, approved_by = '$user_id', s_approval_status = 2, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            }

            if($update_requisition_query){
                $module = 'Requisition';
                $action = 'Consumable Requisition Rejected';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Requisition has been rejected successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be rejected! Please try again later.'
                )));
            }
        }

        // DELETE CONSUMABLE REQUISITION
        function deleteConRequisition($input){
            $now = strtotime('now');

            $delete_requisition_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_requisition WHERE requisition_id = '".$input['id']."'");

            if($delete_requisition_query){
                $delete_requisition_data_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_requisition_data WHERE requisition_id = '".$input['id']."'");

                if($delete_requisition_data_query){
                    $module = 'Requisition';
                    $action = 'Consumable Requisition Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Requisition has been deleted successfully!'
                        )));
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be deleted! Please try again later.'
                )));
            }
        }

        // ADD CONSUMABLE PURCHASE
        function addConPurchase($input, $input2, $input3, $input4){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            // GET PURCHASE DATA
            $purchase_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_purchase WHERE requisition_id = '$input'");

            if(mysqli_num_rows($purchase_query) == 0){
                // INSERT PURCHASE DATA
                $insert_purchase_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_purchase(purchased_by, requisition_id, requisitioned_parts, purchased_parts, purchase_created) VALUES('$user_id', '$input', '$input2', '$input3', '$now')");
            } else{
                // UPDATE PURCHASE DATA
                $purchase_info = mysqli_fetch_assoc($purchase_query);

                $upd_purchase_id = $purchase_info['purchase_id'];
                $upd_purchased_parts = $purchase_info['purchased_parts'] + $input3;

                $upd_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase SET purchased_parts = '$upd_purchased_parts' WHERE purchase_id = '$upd_purchase_id'");
            }

            if(isset($insert_purchase_query)){
                $purchase_id = mysqli_insert_id($this->conn);
            } else{
                $purchase_id = $upd_purchase_id;
            }

            if($purchase_id){
                $js_data = json_decode($input4);

                foreach($js_data as $key => $value){
                    $parts_name = mysqli_real_escape_string($this->conn, $value->parts);

                    $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_id FROM rrmsteel_parts WHERE parts_name = '$parts_name' LIMIT 1"));

                    $required_for = $value->required_for;
                    if($required_for == 'BCP-CCM')
                        $required_for = 1;
                    elseif($required_for == 'BCP-Furnace')
                        $required_for = 2;
                    elseif($required_for == 'Concast-CCM')
                        $required_for = 3;
                    elseif($required_for == 'Concast-Furnace')
                        $required_for = 4;
                    elseif($required_for == 'HRM')
                        $required_for = 5;
                    elseif($required_for == 'HRM Unit-2')
                        $required_for = 6;
                    elseif($required_for == 'Lal Masjid')
                        $required_for = 7;
                    elseif($required_for == 'Sonargaon')
                        $required_for = 8;
                    elseif($required_for == 'General')
                        $required_for = 9;

                    $parts_id = $parts_info['parts_id'];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $req_qty = mysqli_real_escape_string($this->conn, $value->req);
                    $usage = mysqli_real_escape_string($this->conn, $value->use);
                    $price = mysqli_real_escape_string($this->conn, $value->price);
                    $party = explode('|', mysqli_real_escape_string($this->conn, $value->party));
                    $party_id = $party[0];
                    $gate_no =  mysqli_real_escape_string($this->conn, $value->gate_no);
                    $challan_no =  mysqli_real_escape_string($this->conn, $value->challan_no);
                    $challan_photo =  mysqli_real_escape_string($this->conn, $value->challan_photo);
                    $bill_photo =  mysqli_real_escape_string($this->conn, $value->bill_photo);
                    $remarks =  mysqli_real_escape_string($this->conn, $value->remarks);
                    $purchase_date = mysqli_real_escape_string($this->conn, $value->purchase_date);
                    
                    // INSERT PURCHASE DATA
                    $insert_receive_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_purchase_data(required_for, parts_id, parts_qty, req_qty, parts_usage, price, party_id, gate_no, challan_no, challan_photo, bill_photo, remarks, purchase_id, purchase_date, purchase_data_created) VALUES('$required_for', '$parts_id', '$qty', '$req_qty', '$usage', '$price', '$party_id', '$gate_no', '$challan_no', '$challan_photo', '$bill_photo', '$remarks', '$purchase_id', '$purchase_date', '$now')");
                }
                
                // AUDIT LOG
                $module = 'Purchase';
                $action = 'Consumable Purchase Data Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase data has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE CONSUMABLE PURCHASE
        function updateConPurchase($input){
            $now = strtotime('now');

            $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS purchased_qty FROM rrmsteel_con_purchase_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."' AND purchase_data_id != '".$input['id']."'"));
            $purchased_qty = $purchase_data_info['purchased_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($purchased_qty + $input['qty']) >= $received_qty){
                $party = explode('|', $input['party']);
                $party_id = $party[0];
                
                // UPDATE PURCHASE DATA
                $update_purchase_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase_data SET parts_qty = '".$input['qty']."', price = '".$input['price']."', party_id = '$party_id', gate_no = '".$input['gate_no']."', challan_no = '".$input['challan_no']."', challan_photo = '".$input['challan_photo']."', bill_photo = '".$input['bill_photo']."', purchase_date = '".$input['purchase_date']."' WHERE purchase_data_id = '".$input['id']."'");

                if($update_purchase_data_query){
                    // GET PURCHASE
                    $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_con_purchase WHERE purchase_id = '".$input['purchase_id']."'"));
                    $requisition_id = $purchase_info['requisition_id'];

                    // GET PURCHASE DATA
                    $purchase_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_purchase_data WHERE purchase_id = '".$input['purchase_id']."' GROUP BY parts_id");

                    $tot_purchased_parts = 0;

                    while($row = mysqli_fetch_assoc($purchase_data_query)){
                        $parts_id = $row['parts_id'];
                        $purchased_parts = $row['parts_qty'];

                        // GET REQUISITION DATA
                        $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_con_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                        $requisitioned_parts = $requisition_data_info['parts_qty'];

                        if($purchased_parts == $requisitioned_parts)
                            $tot_purchased_parts++;
                    }

                    // UPDATE PURCHASE
                    $update_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase SET purchased_parts = '$tot_purchased_parts' WHERE purchase_id = '".$input['purchase_id']."'");
                    
                    // INSERT AUDIT LOG
                    $module = 'Purchase';
                    $action = 'Consumable Purchase Data Updated';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Purchase data has been updated successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Purchase data can not be updated! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be updated! Inventory against this purchase received already.'
                )));
            }
        }

        // UPATE MULTIPLE PURCHASE DATA
        function updatePurchaseMulti($input){
            $now = strtotime('now');

            foreach($input as $key => $value){
                if($value['type'] == 1){
                    $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS purchased_qty FROM rrmsteel_con_purchase_data WHERE parts_id = '".$value['parts_id']."' AND required_for = '".$value['req_for']."' AND purchase_data_id != '".$value['purchase_data_id']."'"));
                } else{
                   $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS purchased_qty FROM rrmsteel_spr_purchase_data WHERE parts_id = '".$value['parts_id']."' AND required_for = '".$value['req_for']."' AND purchase_data_id != '".$value['purchase_data_id']."'"));
                }

                $purchased_qty = $purchase_data_info['purchased_qty'];

                $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$value['parts_id']."' AND required_for = '".$value['req_for']."'"));
                $received_qty = $inventory_history_info['received_qty'];

                if($received_qty == null || ($purchased_qty + $value['qty']) >= $received_qty){
                    // UPDATE PURCHASE DATA
                    if($value['type'] == 1){
                        $update_purchase_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase_data SET parts_qty = '".$value['qty']."', price = '".$value['price']."', party_id = '".$value['party']."', gate_no = '".$value['gate_no']."', challan_no = '".$value['challan_no']."', purchase_date = '".$value['purchase_date']."' WHERE purchase_data_id = '".$value['purchase_data_id']."'");
                    } else{
                        $update_purchase_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase_data SET parts_qty = '".$value['qty']."', price = '".$value['price']."', party_id = '".$value['party']."', gate_no = '".$value['gate_no']."', challan_no = '".$value['challan_no']."', purchase_date = '".$value['purchase_date']."' WHERE purchase_data_id = '".$value['purchase_data_id']."'");
                    }

                    if($update_purchase_data_query){
                        // GET PURCHASE
                        if($value['type'] == 1){
                            $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_con_purchase WHERE purchase_id = '".$value['purchase_id']."'"));
                        } else{
                            $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_spr_purchase WHERE purchase_id = '".$value['purchase_id']."'"));
                        }

                        $requisition_id = $purchase_info['requisition_id'];

                        // GET PURCHASE DATA
                        if($value['type'] == 1){
                            $purchase_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_purchase_data WHERE purchase_id = '".$value['purchase_id']."' GROUP BY parts_id");
                        } else{
                            $purchase_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_purchase_data WHERE purchase_id = '".$value['purchase_id']."' GROUP BY parts_id");
                        }

                        $tot_purchased_parts = 0;

                        while($row = mysqli_fetch_assoc($purchase_data_query)){
                            $parts_id = $row['parts_id'];
                            $purchased_parts = $row['parts_qty'];

                            // GET REQUISITION DATA
                            if($value['type'] == 1){
                                $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_con_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                            } else{
                                $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_spr_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                            }

                            $requisitioned_parts = $requisition_data_info['parts_qty'];

                            if($purchased_parts == $requisitioned_parts){
                                $tot_purchased_parts++;
                            }
                        }

                        // UPDATE PURCHASE
                        if($value['type'] == 1){
                            $update_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase SET purchased_parts = '$tot_purchased_parts' WHERE purchase_id = '".$value['purchase_id']."'");
                        } else{
                            $update_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase SET purchased_parts = '$tot_purchased_parts' WHERE purchase_id = '".$value['purchase_id']."'");
                        }

                        $flag = 1;
                    } else{
                        $flag = 2;
                    }

                    $flag = 1;
                } else{
                    $flag = 3;
                }
            }

            if($flag == 1){
                // INSERT AUDIT LOG
                $module = 'Purchase';
                $action = 'Multiple Consumable And / Or Spare Purchase Data Updated';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase data has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be updated! Inventory against this purchase received already.'
                )));
            }
        }

        // MARK CONSUMABLE PURCHASE
        function markConPurchase($input){
            $user_id = $_SESSION['user_id'];
            $now = strtotime('now');

            $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisitioned_parts FROM rrmsteel_con_purchase WHERE purchase_id = '".$input['id']."'"));
            $requisitioned_parts = $purchase_info['requisitioned_parts'];

            // UPDATE PURCHASE
            $update_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase SET purchased_parts = '$requisitioned_parts' WHERE purchase_id = '".$input['id']."'");

            if($update_purchase_query){
                $module = 'Purchase';
                $action = 'Consumable Purchase Marked as Done';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    $reply = 'Marked as purchased successfully!';

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $reply
                    )));
                }
            } else{
                $reply = 'Cannot be marked as purchased! Please try again later.';

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => $reply
                )));
            }
        }

        // DELETE CONSUMABLE PURCHASE DATA
        function deleteConPurchase($input){
            $now = strtotime('now');

            $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS purchased_qty FROM rrmsteel_con_purchase_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $purchased_qty = $purchase_data_info['purchased_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($purchased_qty - $input['qty']) >= $received_qty){
                $delete_purchase_data = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_purchase_data WHERE purchase_data_id = '".$input['id']."'");

                if($delete_purchase_data){
                    $purchase_data_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(parts_id) AS tot_parts FROM rrmsteel_con_purchase_data WHERE parts_id = '".$input['parts_id']."' AND purchase_id = '".$input['purchase_id']."'"));

                    if($purchase_data_info2['tot_parts'] == 0){
                        $upd_purchase = mysqli_query($this->conn, "UPDATE rrmsteel_con_purchase SET purchased_parts = purchased_parts - 1 WHERE purchase_id = '".$input['purchase_id']."'");
                    }

                    $purchase_data_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(purchase_id) AS tot_purchase FROM rrmsteel_con_purchase_data WHERE purchase_id = '".$input['purchase_id']."'"));

                    if($purchase_data_info3['tot_purchase'] == 0){
                        $dlt_purchase = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_purchase WHERE purchase_id = '".$input['purchase_id']."'");
                    }

                    $module = 'Purchase';
                    $action = 'Consumable Purchase Data Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Purchase Data has been deleted successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Purchase data can not be deleted! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be deleted! Inventory against this purchase received already.'
                )));
            }
        }

        // ADD CONSUMABLE LOAN
        function addConLoan($input, $input2, $input3, $input4){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            // GET LOAN DATA
            $loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan WHERE requisition_id = '$input'");

            if(mysqli_num_rows($loan_query) == 0){
                // INSERT LOAN DATA
                $insert_loan_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_loan(borrowed_by, requisition_id, requisitioned_parts, borrowed_parts, loan_created) VALUES('$user_id', '$input', '$input2', '$input3', '$now')");
            } else{
                // UPDATE LOAN DATA
                $loan_info = mysqli_fetch_assoc($loan_query);

                $upd_loan_id = $loan_info['loan_id'];
                $upd_borrowed_parts = $loan_info['borrowed_parts'] + $input3;

                $upd_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan SET borrowed_parts = '$upd_borrowed_parts' WHERE loan_id = '$upd_loan_id'");
            }

            if(isset($insert_loan_query)){
                $loan_id = mysqli_insert_id($this->conn);
            } else{
                $loan_id = $upd_loan_id;
            }

            if($loan_id){
                $js_data = json_decode($input4);

                foreach($js_data as $key => $value){
                    $parts_name = mysqli_real_escape_string($this->conn, $value->parts);

                    $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_id FROM rrmsteel_parts WHERE parts_name = '$parts_name' LIMIT 1"));

                    $required_for = $value->required_for;
                    if($required_for == 'BCP-CCM')
                        $required_for = 1;
                    elseif($required_for == 'BCP-Furnace')
                        $required_for = 2;
                    elseif($required_for == 'Concast-CCM')
                        $required_for = 3;
                    elseif($required_for == 'Concast-Furnace')
                        $required_for = 4;
                    elseif($required_for == 'HRM')
                        $required_for = 5;
                    elseif($required_for == 'HRM Unit-2')
                        $required_for = 6;
                    elseif($required_for == 'Lal Masjid')
                        $required_for = 7;
                    elseif($required_for == 'Sonargaon')
                        $required_for = 8;
                    elseif($required_for == 'General')
                        $required_for = 9;

                    $parts_id = $parts_info['parts_id'];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $req_qty = mysqli_real_escape_string($this->conn, $value->req);
                    $usage = mysqli_real_escape_string($this->conn, $value->use);
                    $price = mysqli_real_escape_string($this->conn, $value->price);
                    $party = explode('|', mysqli_real_escape_string($this->conn, $value->party));
                    $party_id = $party[0];
                    $gate_no = mysqli_real_escape_string($this->conn, $value->gate_no);
                    $challan_no = mysqli_real_escape_string($this->conn, $value->challan_no);
                    $challan_photo = mysqli_real_escape_string($this->conn, $value->challan_photo);
                    $bill_photo = mysqli_real_escape_string($this->conn, $value->bill_photo);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $loan_date = mysqli_real_escape_string($this->conn, $value->loan_date);
                    
                    // INSERT LOAN DATA
                    $insert_receive_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_loan_data(required_for, parts_id, parts_qty, req_qty, repay_qty, received_qty, parts_usage, price, party_id, gate_no, challan_no, challan_photo, bill_photo, remarks, loan_id, loan_date, loan_data_created) VALUES('$required_for', '$parts_id', '$qty', '$req_qty', 0, 0, '$usage', '$price', '$party_id', '$gate_no', '$challan_no', '$challan_photo', '$bill_photo', '$remarks', '$loan_id', '$loan_date', '$now')");
                }
                
                // AUDIT LOG
                $module = 'Loan';
                $action = 'Consumable Loan Data Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Loan data has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE CONSUMABLE LOAN
        function updateConLoan($input){
            $now = strtotime('now');

            $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS borrowed_qty FROM rrmsteel_con_loan_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."' AND loan_data_id != '".$input['id']."'"));
            $borrowed_qty = $loan_data_info['borrowed_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($borrowed_qty + $input['qty']) >= $received_qty){
                $party = explode('|', $input['party']);
                $party_id = $party[0];
                
                // UPDATE LOAN DATA
                $update_loan_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan_data SET parts_qty = '".$input['qty']."', price = '".$input['price']."', party_id = '$party_id', gate_no = '".$input['gate_no']."', challan_no = '".$input['challan_no']."', challan_photo = '".$input['challan_photo']."', bill_photo = '".$input['bill_photo']."', loan_date = '".$input['loan_date']."' WHERE loan_data_id = '".$input['id']."'");

                if($update_loan_data_query){
                    // GET LOAN
                    $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_con_loan WHERE loan_id = '".$input['loan_id']."'"));
                    $requisition_id = $loan_info['requisition_id'];

                    // GET LOAN DATA
                    $loan_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_loan_data WHERE loan_id = '".$input['loan_id']."' GROUP BY parts_id");

                    $tot_borrowed_parts = 0;

                    while($row = mysqli_fetch_assoc($loan_data_query)){
                        $parts_id = $row['parts_id'];
                        $borrowed_parts = $row['parts_qty'];

                        // GET REQUISITION DATA
                        $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_con_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                        $requisitioned_parts = $requisition_data_info['parts_qty'];

                        if($borrowed_parts == $requisitioned_parts)
                            $tot_borrowed_parts++;
                    }

                    // UPDATE LOAN
                    $update_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan SET borrowed_parts = '$tot_borrowed_parts' WHERE loan_id = '".$input['loan_id']."'");
                    
                    // INSERT AUDIT LOG
                    $module = 'Loan';
                    $action = 'Consumable Loan Data Updated';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Loan data has been updated successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Loan data can not be updated! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be updated! Inventory against this loan received already.'
                )));
            }
        }

        // UPATE MULTIPLE LOAN DATA
        function updateLoanMulti($input){
            $now = strtotime('now');

            foreach($input as $key => $value){
                if($value['type'] == 1){
                    $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS borrowed_qty FROM rrmsteel_con_loan_data WHERE parts_id = '".$value['parts_id']."' AND required_for = '".$value['req_for']."' AND loan_data_id != '".$value['loan_data_id']."'"));
                } else{
                   $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS borrowed_qty FROM rrmsteel_spr_loan_data WHERE parts_id = '".$value['parts_id']."' AND required_for = '".$value['req_for']."' AND loan_data_id != '".$value['loan_data_id']."'"));
                }

                $borrowed_qty = $loan_data_info['borrowed_qty'];

                $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$value['parts_id']."' AND required_for = '".$value['req_for']."'"));
                $received_qty = $inventory_history_info['received_qty'];

                if($received_qty == null || ($borrowed_qty + $value['qty']) >= $received_qty){
                    // UPDATE LOAN DATA
                    if($value['type'] == 1){
                        $update_loan_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan_data SET parts_qty = '".$value['qty']."', price = '".$value['price']."', party_id = '".$value['party']."', gate_no = '".$value['gate_no']."', challan_no = '".$value['challan_no']."', loan_date = '".$value['loan_date']."' WHERE loan_data_id = '".$value['loan_data_id']."'");
                    } else{
                        $update_loan_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan_data SET parts_qty = '".$value['qty']."', price = '".$value['price']."', party_id = '".$value['party']."', gate_no = '".$value['gate_no']."', challan_no = '".$value['challan_no']."', loan_date = '".$value['loan_date']."' WHERE loan_data_id = '".$value['loan_data_id']."'");
                    }

                    if($update_loan_data_query){
                        // GET LOAN
                        if($value['type'] == 1){
                            $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_con_loan WHERE loan_id = '".$value['loan_id']."'"));
                        } else{
                            $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_spr_loan WHERE loan_id = '".$value['loan_id']."'"));
                        }

                        $requisition_id = $loan_info['requisition_id'];

                        // GET LOAN DATA
                        if($value['type'] == 1){
                            $loan_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_loan_data WHERE loan_id = '".$value['loan_id']."' GROUP BY parts_id");
                        } else{
                            $loan_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_loan_data WHERE loan_id = '".$value['loan_id']."' GROUP BY parts_id");
                        }

                        $tot_borrowed_parts = 0;

                        while($row = mysqli_fetch_assoc($loan_data_query)){
                            $parts_id = $row['parts_id'];
                            $borrowed_parts = $row['parts_qty'];

                            // GET REQUISITION DATA
                            if($value['type'] == 1){
                                $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_con_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                            } else{
                                $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_spr_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                            }

                            $requisitioned_parts = $requisition_data_info['parts_qty'];

                            if($borrowed_parts == $requisitioned_parts){
                                $tot_borrowed_parts++;
                            }
                        }

                        // UPDATE LOAN
                        if($value['type'] == 1){
                            $update_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan SET borrowed_parts = '$tot_borrowed_parts' WHERE loan_id = '".$value['loan_id']."'");
                        } else{
                            $update_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan SET borrowed_parts = '$tot_borrowed_parts' WHERE loan_id = '".$value['loan_id']."'");
                        }

                        $flag = 1;
                    } else{
                        $flag = 2;
                    }

                    $flag = 1;
                } else{
                    $flag = 3;
                }
            }

            if($flag == 1){
                // INSERT AUDIT LOG
                $module = 'Loan';
                $action = 'Multiple Consumable And / Or Spare Loan Data Updated';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Loan data has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be updated! Inventory against this loan received already.'
                )));
            }
        }

        // MARK CONSUMABLE LOAN
        function markConLoan($input){
            $user_id = $_SESSION['user_id'];
            $now = strtotime('now');

            $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisitioned_parts FROM rrmsteel_con_loan WHERE loan_id = '".$input['id']."'"));
            $requisitioned_parts = $loan_info['requisitioned_parts'];

            // UPDATE LOAN
            $update_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan SET borrowed_parts = '$requisitioned_parts' WHERE loan_id = '".$input['id']."'");

            if($update_loan_query){
                $module = 'Loan';
                $action = 'Consumable Loan Marked as Done';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    $reply = 'Marked as borrowed successfully!';

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $reply
                    )));
                }
            } else{
                $reply = 'Cannot be marked as borrowed! Please try again later.';

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => $reply
                )));
            }
        }

        // DELETE CONSUMABLE LOAN DATA
        function deleteConLoan($input){
            $now = strtotime('now');

            $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS borrowed_qty FROM rrmsteel_con_loan_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $borrowed_qty = $loan_data_info['borrowed_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($borrowed_qty - $input['qty']) >= $received_qty){
                $delete_loan_data = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_loan_data WHERE loan_data_id = '".$input['id']."'");

                if($delete_loan_data){
                    $loan_data_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(parts_id) AS tot_parts FROM rrmsteel_con_loan_data WHERE parts_id = '".$input['parts_id']."' AND loan_id = '".$input['loan_id']."'"));

                    if($loan_data_info2['tot_parts'] == 0){
                        $upd_loan = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan SET borrowed_parts = borrowed_parts - 1 WHERE loan_id = '".$input['loan_id']."'");
                    }

                    $loan_data_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(loan_id) AS tot_loan FROM rrmsteel_con_loan_data WHERE loan_id = '".$input['loan_id']."'"));

                    if($loan_data_info3['tot_loan'] == 0){
                        $dlt_loan = mysqli_query($this->conn, "DELETE FROM rrmsteel_con_loan WHERE loan_id = '".$input['loan_id']."'");
                    }

                    $module = 'Loan';
                    $action = 'Consumable Loan Data Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Loan Data has been deleted successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Loan data can not be deleted! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be deleted! Inventory against this loan received already.'
                )));
            }
        }

        // ADD CONSUMABLE LOAN REPAY
        function addConLoanRepay($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            // INSERT LOAN DATA
            $insert_loan_repay_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_loan_repay(parts_id, required_for, party_id, req_qty, borrowed_qty, borrow_date, repaid_qty, repay_date, loan_id, loan_data_id, loan_repay_data_created) VALUES('".$input['parts_id']."', '".$input['req_for']."', '".$input['party_id']."', '".$input['req_qty']."', '".$input['borrow_qty']."', '".$input['borrow_date']."', '".$input['repay_qty']."', '".$input['repay_date']."', '".$input['loan_id']."', '".$input['loan_data_id']."', '$now')");
                
            if($insert_loan_repay_query){
                $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT repay_qty FROM rrmsteel_con_loan_data WHERE loan_data_id = '".$input['loan_data_id']."' AND loan_id = '".$input['loan_id']."' LIMIT 1"));
                $repay_qty = $loan_data_info['repay_qty'];
                $upd_repay_qty = $repay_qty + $input['repay_qty'];

                $upd_loan_data = mysqli_query($this->conn, "UPDATE rrmsteel_con_loan_data SET repay_qty = '$upd_repay_qty' WHERE loan_data_id = '".$input['loan_data_id']."' AND loan_id = '".$input['loan_id']."'");

                $inv_data = [
                    'parts_id' => $input['parts_id'],
                    'required_for' => $input['req_for'],
                    'action_date' => $input['repay_date'],
                    'qty' => $input['repay_qty'],
                    'source' => 3
                ];

                if($this->issuePartsQty($inv_data)){
                    // AUDIT LOG
                    $module = 'Loan';
                    $action = 'Consumable Loan Repay Data Added';
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    // INSERT AUDIT LOG
                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    // AUDIT LOG 2
                    $module_2 = 'Inventory';
                    $action_2 = 'Inventory Updated';

                    // INSERT AUDIT LOG 2
                    $audit_query_2 = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module_2', '$action_2', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query && $audit_query_2){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Loan repay data & inventory has been updated successfully!'
                        )));
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan repay data can not be added! Please try again later.'
                )));
            }
        }

        // ADD CONSUMABLE PURCHASE BILL
        function addConPurchaseBill($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill WHERE parts_id = '".$input['parts_id']."' AND party_id = '".$input['party_id']."' AND required_for = '".$input['required_for']."' AND purchase_id = '".$input['purchase_id']."'");

            if(mysqli_num_rows($bill_query) > 0){
                $bill_info = mysqli_fetch_assoc($bill_query);

                $bill_id = $bill_info['bill_id'];
                $qty = $bill_info['qty'] + $input['qty'];
                $price = $bill_info['price'] + $input['price'];

                // UPDATE BILL
                $insert_bill_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_bill SET qty = '$qty', price = '$price', purchase_data_ids = CONCAT(purchase_data_ids, '".$input['purchase_data_id'].",') WHERE bill_id = '$bill_id'");
            } else{
                // INSERT BILL
                $insert_bill_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_con_bill(required_for, parts_id, qty, received_qty, price, party_id, purchase_data_ids, purchase_id, generate_status, user_id, bill_created) VALUES('".$input['required_for']."', '".$input['parts_id']."', '".$input['qty']."', 0, '".$input['price']."', '".$input['party_id']."', '".$input['purchase_data_id'].",', '".$input['purchase_id']."', 0, '$user_id', '$now')");
            }

            if($insert_bill_query){
                // AUDIT LOG
                $module = 'Purchase';
                $action = 'Consumable Purchase Bill Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase bill has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase bill can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE CONSUMABLE PURCHASE BILL
        function updateConPurchaseBill($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            $bill_id_str = implode(',', $input['bill_id_arr']);

            $update_bill_query = mysqli_query($this->conn, "UPDATE rrmsteel_con_bill SET generate_date = '".$input['bill_date']."', generate_status = 1 WHERE bill_id IN('".$bill_id_str."')");

            if($update_bill_query){
                // AUDIT LOG
                $module = 'Purchase';
                $action = 'Consumable Purchase Bill Updated';
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase bill has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase bill can not be updated! Please try again later.'
                )));
            }
        }

        // ADD SPARE PURCHASE BILL
        function addSprPurchaseBill($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill WHERE parts_id = '".$input['parts_id']."' AND party_id = '".$input['party_id']."' AND required_for = '".$input['required_for']."' AND purchase_id = '".$input['purchase_id']."'");

            if(mysqli_num_rows($bill_query) > 0){
                $bill_info = mysqli_fetch_assoc($bill_query);

                $bill_id = $bill_info['bill_id'];
                $qty = $bill_info['qty'] + $input['qty'];
                $price = $bill_info['price'] + $input['price'];

                // UPDATE BILL
                $insert_bill_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_bill SET qty = '$qty', price = '$price', purchase_data_ids = CONCAT(purchase_data_ids, '".$input['purchase_data_id'].",') WHERE bill_id = '$bill_id'");
            } else{
                // INSERT BILL
                $insert_bill_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_bill(required_for, parts_id, qty, received_qty, price, party_id, purchase_data_ids, purchase_id, generate_status, user_id, bill_created) VALUES('".$input['required_for']."', '".$input['parts_id']."', '".$input['qty']."', 0, '".$input['price']."', '".$input['party_id']."', '".$input['purchase_data_id'].",', '".$input['purchase_id']."', 0, '$user_id', '$now')");
            }

            if($insert_bill_query){
                // AUDIT LOG
                $module = 'Purchase';
                $action = 'Spare Purchase Bill Added';
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase bill has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase bill can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE SPARE PURCHASE BILL
        function updateSprPurchaseBill($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            $bill_id_str = implode(',', $input['bill_id_arr']);

            $update_bill_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_bill SET generate_date = '".$input['bill_date']."', generate_status = 1 WHERE bill_id IN('".$bill_id_str."')");

            if($update_bill_query){
                // AUDIT LOG
                $module = 'Purchase';
                $action = 'Spare Purchase Bill Updated';
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase bill has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase bill can not be updated! Please try again later.'
                )));
            }
        }

        // ADD SPARE REQUISITION
        function addSprRequisition($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];

            // INSERT REQUISITION
            if($user_category == 1){
                $insert_requisition_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_requisition(requisition_by, s_approval_status, s_approved_by, p_approval_status, p_approved_by, approval_status, approved_by, requisition_created) VALUES(1, 1, '$user_id', 1, '$user_id', 1, '$user_id', '$now')");
            } elseif($user_category == 2){
                $insert_requisition_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_requisition(requisition_by, s_approval_status, s_approved_by, p_approval_status, p_approved_by, approval_status, approved_by, requisition_created) VALUES('$user_id', 0, 0, 0, 0, 0, 0, '$now')");
            }

            if($insert_requisition_query){
                $requisition_id = mysqli_insert_id($this->conn);

                $js_data = json_decode($input);

                foreach($js_data as $key => $value){
                    $required_for = mysqli_real_escape_string($this->conn, $value->required_for);
                    $parts = explode('|', mysqli_real_escape_string($this->conn, $value->parts));
                    $parts_id = $parts[0];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $old_spare_details = mysqli_real_escape_string($this->conn, $value->old);
                    $status = mysqli_real_escape_string($this->conn, $value->status);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $loan =  mysqli_real_escape_string($this->conn, $value->loan);
                    
                    // INSERT REQUISITION DATA
                    $insert_requisition_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_requisition_data(required_for, parts_id, parts_qty, old_spare_details, status, remarks, loan, requisition_id, requisition_data_created) VALUES('$required_for', '$parts_id', '$qty', '$old_spare_details', '$status', '$remarks', '$loan', '$requisition_id', '$now')");
                }
                
                // AUDIT LOG
                $module = 'Requisition';
                $action = 'Spare Requisition Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Requisition has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE SPARE REQUISITION
        function updateSprRequisition($input1, $input2, $input3){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];

            if($input2 == 1){
                // UPDATE REQUISITION
                if($user_category == 1){
                    $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET approval_status = '$input2', approved_by = '$user_id' WHERE requisition_id = '$input1'");
                } elseif($user_category == 3){
                    // $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET s_approval_status = '$input2', s_approved_by = '$user_id' WHERE requisition_id = '$input1'");

                    $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET approval_status = '$input2', approved_by = '$user_id', s_approval_status = '$input2', s_approved_by = '$user_id' WHERE requisition_id = '$input1'");
                } elseif($user_category == 4){
                    $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET p_approval_status = '$input2', p_approved_by = '$user_id' WHERE requisition_id = '$input1'");
                }

                if($update_requisition_query){
                    // DELETE REQUISITION DATA
                    $delete_requisition_data_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_requisition_data WHERE requisition_id = '$input1'");
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Requisition can not be updated! Please try again later.'
                    )));
                }
            } else{
                // DELETE REQUISITION DATA
                $delete_requisition_data_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_requisition_data WHERE requisition_id = '$input1'");
            }

            if($delete_requisition_data_query){
                $js_data = json_decode($input3);

                foreach($js_data as $key => $value){
                    $required_for = mysqli_real_escape_string($this->conn, $value->required_for);
                    $parts = explode('|', mysqli_real_escape_string($this->conn, $value->parts));
                    $parts_id = $parts[0];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $old_spare_details = mysqli_real_escape_string($this->conn, $value->old);
                    $status = mysqli_real_escape_string($this->conn, $value->status);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $loan = mysqli_real_escape_string($this->conn, $value->loan);
                    
                    // INSERT REQUISITION DATA
                    $insert_requisition_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_requisition_data(required_for, parts_id, parts_qty, old_spare_details, status, remarks, loan, requisition_id, requisition_data_created) VALUES('$required_for', '$parts_id', '$qty', '$old_spare_details', '$status', '$remarks', '$loan', '$input1', '$now')");
                }

                // AUDIT LOG
                $module = 'Requisition';
                $action = 'Spare Requisition Updated';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Requisition has been updated successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be updated! Please try again later.'
                )));
            }
        }

        // APPROVE SPARE REQUISITION
        function approveSprRequisition($input){
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];
            $now = strtotime('now');

            if($user_category == 1){
                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET approval_status = 1, approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            } elseif($user_category == 3){
                // $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET s_approval_status = 1, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");

                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET approval_status = 1, approved_by = '$user_id', s_approval_status = 1, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            } elseif($user_category == 4){
                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET p_approval_status = 1, p_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            }

            if($update_requisition_query){
                $module = 'Requisition';
                $action = 'Spare Requisition Approved';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    if($user_category == 4)
                        $reply = 'Requisition has been accepted successfully!';
                    else
                        $reply = 'Requisition has been approved successfully!';

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $reply
                    )));
                }
            } else{
                if($user_category == 4)
                    $reply = 'Requisition can not be accepted! Please try again later.';
                else
                    $reply = 'Requisition can not be approved! Please try again later.';

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => $reply
                )));
            }
        }

        // REJECT SPARE REQUISITION
        function rejectSprRequisition($input){
            $user_id = $_SESSION['user_id'];
            $user_category = $_SESSION['user_category'];
            $now = strtotime('now');

            if($user_category == 1){
                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET approval_status = 2, approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            } elseif($user_category == 3){
                // $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET s_approval_status = 2, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");

                $update_requisition_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_requisition SET approval_status = 2, approved_by = '$user_id', s_approval_status = 2, s_approved_by = '$user_id' WHERE requisition_id = '".$input['id']."'");
            }

            if($update_requisition_query){
                $module = 'Requisition';
                $action = 'Spare Requisition Rejected';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Requisition has been rejected successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be rejected! Please try again later.'
                )));
            }
        }

        // DELETE SPARE REQUISITION
        function deleteSprRequisition($input){
            $now = strtotime('now');

            $delete_requisition_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_requisition WHERE requisition_id = '".$input['id']."'");

            if($delete_requisition_query){
                $delete_requisition_data_query = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_requisition_data WHERE requisition_id = '".$input['id']."'");

                if($delete_requisition_data_query){
                    $module = 'Requisition';
                    $action = 'Spare Requisition Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Requisition has been deleted successfully!'
                        )));
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Requisition can not be deleted! Please try again later.'
                )));
            }
        }

        // ADD CONSUMABLE PURCHASE
        function addSprPurchase($input, $input2, $input3, $input4){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            // GET PURCHASE DATA
            $purchase_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_purchase WHERE requisition_id = '$input'");

            if(mysqli_num_rows($purchase_query) == 0){
                // INSERT PURCHASE DATA
                $insert_purchase_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_purchase(purchased_by, requisition_id, requisitioned_parts, purchased_parts, purchase_created) VALUES('$user_id', '$input', '$input2', '$input3', '$now')");
            } else{
                // UPDATE PURCHASE DATA
                $purchase_info = mysqli_fetch_assoc($purchase_query);

                $upd_purchase_id = $purchase_info['purchase_id'];
                $upd_purchased_parts = $purchase_info['purchased_parts'] + $input3;

                $upd_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase SET purchased_parts = '$upd_purchased_parts' WHERE purchase_id = '$upd_purchase_id'");
            }

            if(isset($insert_purchase_query)){
                $purchase_id = mysqli_insert_id($this->conn);
            } else{
                $purchase_id = $upd_purchase_id;
            }

            if($purchase_id){
                $js_data = json_decode($input4);

                foreach($js_data as $key => $value){
                    $parts_name = mysqli_real_escape_string($this->conn, $value->parts);

                    $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_id FROM rrmsteel_parts WHERE parts_name = '$parts_name' LIMIT 1"));

                    $required_for = $value->required_for;
                    if($required_for == 'BCP-CCM')
                        $required_for = 1;
                    elseif($required_for == 'BCP-Furnace')
                        $required_for = 2;
                    elseif($required_for == 'Concast-CCM')
                        $required_for = 3;
                    elseif($required_for == 'Concast-Furnace')
                        $required_for = 4;
                    elseif($required_for == 'HRM')
                        $required_for = 5;
                    elseif($required_for == 'HRM Unit-2')
                        $required_for = 6;
                    elseif($required_for == 'Lal Masjid')
                        $required_for = 7;
                    elseif($required_for == 'Sonargaon')
                        $required_for = 8;
                    elseif($required_for == 'General')
                        $required_for = 9;

                    $parts_id = $parts_info['parts_id'];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $req_qty = mysqli_real_escape_string($this->conn, $value->req);
                    $old_spare_details = mysqli_real_escape_string($this->conn, $value->old);
                    
                    $status = $value->status;
                    if($status == 'Repairable')
                        $status = 1;
                    elseif($status == 'Unusual')
                        $status = 2;

                    $price = mysqli_real_escape_string($this->conn, $value->price);
                    $party = explode('|', mysqli_real_escape_string($this->conn, $value->party));
                    $party_id = $party[0];
                    $gate_no = mysqli_real_escape_string($this->conn, $value->gate_no);
                    $challan_no = mysqli_real_escape_string($this->conn, $value->challan_no);
                    $challan_photo = mysqli_real_escape_string($this->conn, $value->challan_photo);
                    $bill_photo =mysqli_real_escape_string($this->conn, $value->bill_photo);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $purchase_date = mysqli_real_escape_string($this->conn, $value->purchase_date);
                    
                    // INSERT PURCHASE DATA
                    $insert_receive_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_purchase_data(required_for, parts_id, parts_qty, req_qty, old_spare_details, status, price, party_id, gate_no, challan_no, challan_photo, bill_photo, remarks, purchase_id, purchase_date, purchase_data_created) VALUES('$required_for', '$parts_id', '$qty', '$req_qty', '$old_spare_details', '$status', '$price', '$party_id', '$gate_no', '$challan_no', '$challan_photo', '$bill_photo', '$remarks', '$purchase_id', '$purchase_date', '$now')");
                }
                
                // AUDIT LOG
                $module = 'Purchase';
                $action = 'Spare Purchase Data Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Purchase data has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE SPARE PURCHASE
        function updateSprPurchase($input){
            $now = strtotime('now');

            $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS purchased_qty FROM rrmsteel_spr_purchase_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."' AND purchase_data_id != '".$input['id']."'"));
            $purchased_qty = $purchase_data_info['purchased_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($purchased_qty + $input['qty']) >= $received_qty){
                $party = explode('|', $input['party']);
                $party_id = $party[0];
            
                // UPDATE PURCHASE DATA
                $update_purchase_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase_data SET parts_qty = '".$input['qty']."', price = '".$input['price']."', party_id = '$party_id', gate_no = '".$input['gate_no']."', challan_no = '".$input['challan_no']."', challan_photo = '".$input['challan_photo']."', bill_photo = '".$input['bill_photo']."', purchase_date = '".$input['purchase_date']."' WHERE purchase_data_id = '".$input['id']."'");

                if($update_purchase_data_query){
                    // GET PURCHASE
                    $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_spr_purchase WHERE purchase_id = '".$input['purchase_id']."'"));
                    $requisition_id = $purchase_info['requisition_id'];

                    // GET PURCHASE DATA
                    $purchase_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_purchase_data WHERE purchase_id = '".$input['purchase_id']."' GROUP BY parts_id");

                    $tot_purchased_parts = 0;

                    while($row = mysqli_fetch_assoc($purchase_data_query)){
                        $parts_id = $row['parts_id'];
                        $purchased_parts = $row['parts_qty'];

                        // GET REQUISITION DATA
                        $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_spr_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                        $requisitioned_parts = $requisition_data_info['parts_qty'];

                        if($purchased_parts == $requisitioned_parts)
                            $tot_purchased_parts++;
                    }

                    // UPDATE PURCHASE
                    $update_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase SET purchased_parts = '$tot_purchased_parts' WHERE purchase_id = '".$input['purchase_id']."'");
                    
                    // INSERT AUDIT LOG
                    $module = 'Purchase';
                    $action = 'Spare Purchase Data Updated';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Purchase data has been updated successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Purchase data can not be updated! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be updated! Inventory against this purchase received already.'
                )));
            }
        }

        // MARK SPARE PURCHASE
        function markSprPurchase($input){
            $user_id = $_SESSION['user_id'];
            $now = strtotime('now');

            $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisitioned_parts FROM rrmsteel_spr_purchase WHERE purchase_id = '".$input['id']."'"));
            $requisitioned_parts = $purchase_info['requisitioned_parts'];

            // UPDATE PURCHASE
            $update_purchase_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase SET purchased_parts = '$requisitioned_parts' WHERE purchase_id = '".$input['id']."'");

            if($update_purchase_query){
                $module = 'Purchase';
                $action = 'Spare Purchase Marked as Done';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    $reply = 'Marked as purchased successfully!';

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $reply
                    )));
                }
            } else{
                $reply = 'Cannot be marked as purchased! Please try again later.';

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => $reply
                )));
            }
        }

        // DELETE SPARE PURCHASE DATA
        function deleteSprPurchase($input){
            $now = strtotime('now');

            $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS purchased_qty FROM rrmsteel_spr_purchase_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $purchased_qty = $purchase_data_info['purchased_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($purchased_qty - $input['qty']) >= $received_qty){
                $delete_purchase_data = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_purchase_data WHERE purchase_data_id = '".$input['id']."'");

                if($delete_purchase_data){
                    $purchase_data_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(parts_id) AS tot_parts FROM rrmsteel_spr_purchase_data WHERE parts_id = '".$input['parts_id']."' AND purchase_id = '".$input['purchase_id']."'"));

                    if($purchase_data_info2['tot_parts'] == 0){
                        $upd_purchase = mysqli_query($this->conn, "UPDATE rrmsteel_spr_purchase SET purchased_parts = purchased_parts - 1 WHERE purchase_id = '".$input['purchase_id']."'");
                    }

                    $purchase_data_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(purchase_id) AS tot_purchase FROM rrmsteel_spr_purchase_data WHERE purchase_id = '".$input['purchase_id']."'"));

                    if($purchase_data_info3['tot_purchase'] == 0){
                        $dlt_purchase = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_purchase WHERE purchase_id = '".$input['purchase_id']."'");
                    }

                    $module = 'Purchase';
                    $action = 'Spare Purchase Data Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Purchase Data has been deleted successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Purchase data can not be deleted! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Purchase data can not be deleted! Inventory against this purchase received already.'
                )));
            }
        }

        // ADD CONSUMABLE LOAN
        function addSprLoan($input, $input2, $input3, $input4){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            // GET LOAN DATA
            $loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan WHERE requisition_id = '$input'");

            if(mysqli_num_rows($loan_query) == 0){
                // INSERT LOAN DATA
                $insert_loan_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_loan(borrowed_by, requisition_id, requisitioned_parts, borrowed_parts, loan_created) VALUES('$user_id', '$input', '$input2', '$input3', '$now')");
            } else{
                // UPDATE LOAN DATA
                $loan_info = mysqli_fetch_assoc($loan_query);

                $upd_loan_id = $loan_info['loan_id'];
                $upd_borrowed_parts = $loan_info['borrowed_parts'] + $input3;

                $upd_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan SET borrowed_parts = '$upd_borrowed_parts' WHERE loan_id = '$upd_loan_id'");
            }

            if(isset($insert_loan_query)){
                $loan_id = mysqli_insert_id($this->conn);
            } else{
                $loan_id = $upd_loan_id;
            }

            if($loan_id){
                $js_data = json_decode($input4);

                foreach($js_data as $key => $value){
                    $parts_name = mysqli_real_escape_string($this->conn, $value->parts);

                    $parts_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_id FROM rrmsteel_parts WHERE parts_name = '$parts_name' LIMIT 1"));

                    $required_for = $value->required_for;
                    if($required_for == 'BCP-CCM')
                        $required_for = 1;
                    elseif($required_for == 'BCP-Furnace')
                        $required_for = 2;
                    elseif($required_for == 'Concast-CCM')
                        $required_for = 3;
                    elseif($required_for == 'Concast-Furnace')
                        $required_for = 4;
                    elseif($required_for == 'HRM')
                        $required_for = 5;
                    elseif($required_for == 'HRM Unit-2')
                        $required_for = 6;
                    elseif($required_for == 'Lal Masjid')
                        $required_for = 7;
                    elseif($required_for == 'Sonargaon')
                        $required_for = 8;
                    elseif($required_for == 'General')
                        $required_for = 9;

                    $parts_id = $parts_info['parts_id'];
                    $qty = mysqli_real_escape_string($this->conn, $value->qty);
                    $req_qty = mysqli_real_escape_string($this->conn, $value->req);
                    $old_spare_details = mysqli_real_escape_string($this->conn, $value->old);
                    
                    $status = $value->status;
                    if($status == 'Repairable')
                        $status = 1;
                    elseif($status == 'Unusual')
                        $status = 2;

                    $price = mysqli_real_escape_string($this->conn, $value->price);
                    $party = explode('|', mysqli_real_escape_string($this->conn, $value->party));
                    $party_id = $party[0];
                    $gate_no = mysqli_real_escape_string($this->conn, $value->gate_no);
                    $challan_no = mysqli_real_escape_string($this->conn, $value->challan_no);
                    $challan_photo = mysqli_real_escape_string($this->conn, $value->challan_photo);
                    $bill_photo = mysqli_real_escape_string($this->conn, $value->bill_photo);
                    $remarks = mysqli_real_escape_string($this->conn, $value->remarks);
                    $loan_date = mysqli_real_escape_string($this->conn, $value->loan_date);
                    
                    // INSERT LOAN DATA
                    $insert_receive_data_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_loan_data(required_for, parts_id, parts_qty, req_qty, repay_qty, received_qty, old_spare_details, status, price, party_id, gate_no, challan_no, challan_photo, bill_photo, remarks, loan_id, loan_date, loan_data_created) VALUES('$required_for', '$parts_id', '$qty', '$req_qty', 0, 0, '$old_spare_details', '$status', '$price', '$party_id', '$gate_no', '$challan_no', '$challan_photo', '$bill_photo', '$remarks', '$loan_id', '$loan_date', '$now')");
                }
                
                // AUDIT LOG
                $module = 'Loan';
                $action = 'Spare Loan Data Added';
                $user_id = $user_id;
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                // INSERT AUDIT LOG
                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => 'Loan data has been added successfully!'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be added! Please try again later.'
                )));
            }
        }

        // UPDATE SPARE LOAN
        function updateSprLoan($input){
            $now = strtotime('now');

            $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS borrowed_qty FROM rrmsteel_spr_loan_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."' WHERE loan_data_id != '".$input['id']."'"));
            $borrowed_qty = $loan_data_info['borrowed_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($borrowed_qty + $input['qty']) >= $received_qty){
                $party = explode('|', $input['party']);
                $party_id = $party[0];
            
                // UPDATE LOAN DATA
                $update_loan_data_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan_data SET parts_qty = '".$input['qty']."', price = '".$input['price']."', party_id = '$party_id', gate_no = '".$input['gate_no']."', challan_no = '".$input['challan_no']."', challan_photo = '".$input['challan_photo']."', bill_photo = '".$input['bill_photo']."', loan_date = '".$input['loan_date']."' WHERE loan_data_id = '".$input['id']."'");

                if($update_loan_data_query){
                    // GET LOAN
                    $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisition_id FROM rrmsteel_spr_loan WHERE loan_id = '".$input['loan_id']."'"));
                    $requisition_id = $loan_info['requisition_id'];

                    // GET LOAN DATA
                    $loan_data_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_loan_data WHERE loan_id = '".$input['loan_id']."' GROUP BY parts_id");

                    $tot_borrowed_parts = 0;

                    while($row = mysqli_fetch_assoc($loan_data_query)){
                        $parts_id = $row['parts_id'];
                        $borrowed_parts = $row['parts_qty'];

                        // GET REQUISITION DATA
                        $requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_spr_requisition_data WHERE parts_id = '$parts_id' AND requisition_id = '$requisition_id'"));
                        $requisitioned_parts = $requisition_data_info['parts_qty'];

                        if($borrowed_parts == $requisitioned_parts)
                            $tot_borrowed_parts++;
                    }

                    // UPDATE LOAN
                    $update_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan SET borrowed_parts = '$tot_borrowed_parts' WHERE loan_id = '".$input['loan_id']."'");
                    
                    // INSERT AUDIT LOG
                    $module = 'Loan';
                    $action = 'Spare Loan Data Updated';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Loan data has been updated successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Loan data can not be updated! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be deleted! Inventory against this loan received already.'
                )));
            }
        }

        // MARK SPARE LOAN
        function markSprLoan($input){
            $user_id = $_SESSION['user_id'];
            $now = strtotime('now');

            $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT requisitioned_parts FROM rrmsteel_spr_loan WHERE loan_id = '".$input['id']."'"));
            $requisitioned_parts = $loan_info['requisitioned_parts'];

            // UPDATE LOAN
            $update_loan_query = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan SET borrowed_parts = '$requisitioned_parts' WHERE loan_id = '".$input['id']."'");

            if($update_loan_query){
                $module = 'Loan';
                $action = 'Spare Loan Marked as Done';
                $user_id = $_SESSION['user_id'];
                $action_time = $now;
                $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                if($audit_query){
                    $reply = 'Marked as borrowed successfully!';

                    exit(json_encode(array(
                        'Type' => 'success',
                        'Reply' => $reply
                    )));
                }
            } else{
                $reply = 'Cannot be marked as borrowed! Please try again later.';

                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => $reply
                )));
            }
        }

        // DELETE SPARE LOAN DATA
        function deleteSprLoan($input){
            $now = strtotime('now');

            $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS borrowed_qty FROM rrmsteel_spr_loan_data WHERE parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $borrowed_qty = $loan_data_info['borrowed_qty'];

            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty FROM rrmsteel_inv_history WHERE received_qty > 0 AND parts_id = '".$input['parts_id']."' AND required_for = '".$input['req_for']."'"));
            $received_qty = $inventory_history_info['received_qty'];

            if($received_qty == null || ($borrowed_qty - $input['qty']) >= $received_qty){
                $delete_loan_data = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_loan_data WHERE loan_data_id = '".$input['id']."'");

                if($delete_loan_data){
                    $loan_data_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(parts_id) AS tot_parts FROM rrmsteel_spr_loan_data WHERE parts_id = '".$input['parts_id']."' AND loan_id = '".$input['loan_id']."'"));

                    if($loan_data_info2['tot_parts'] == 0){
                        $upd_loan = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan SET borrowed_parts = borrowed_parts - 1 WHERE loan_id = '".$input['loan_id']."'");
                    }

                    $loan_data_info3 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(loan_id) AS tot_loan FROM rrmsteel_spr_loan_data WHERE loan_id = '".$input['loan_id']."'"));

                    if($loan_data_info3['tot_loan'] == 0){
                        $dlt_loan = mysqli_query($this->conn, "DELETE FROM rrmsteel_spr_loan WHERE loan_id = '".$input['loan_id']."'");
                    }

                    $module = 'Loan';
                    $action = 'Spare Loan Data Deleted';
                    $user_id = $_SESSION['user_id'];
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Loan Data has been deleted successfully!'
                        )));
                    }
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Loan data can not be deleted! Please try again later.'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan data can not be deleted! Inventory against this loan received already.'
                )));
            }
        }

        // ADD CONSUMABLE LOAN REPAY
        function addSprLoanRepay($input){
            $now = strtotime('now');
            $user_id = $_SESSION['user_id'];

            // INSERT LOAN DATA
            $insert_loan_repay_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_spr_loan_repay(parts_id, required_for, party_id, req_qty, borrowed_qty, borrow_date, repaid_qty, repay_date, loan_id, loan_data_id, loan_repay_data_created) VALUES('".$input['parts_id']."', '".$input['req_for']."', '".$input['party_id']."', '".$input['req_qty']."', '".$input['borrow_qty']."', '".$input['borrow_date']."', '".$input['repay_qty']."', '".$input['repay_date']."', '".$input['loan_id']."', '".$input['loan_data_id']."', '$now')");
                
            if($insert_loan_repay_query){
                $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT repay_qty FROM rrmsteel_spr_loan_data WHERE loan_data_id = '".$input['loan_data_id']."' AND loan_id = '".$input['loan_id']."' LIMIT 1"));
                $repay_qty = $loan_data_info['repay_qty'];
                $upd_repay_qty = $repay_qty + $input['repay_qty'];

                $upd_loan_data = mysqli_query($this->conn, "UPDATE rrmsteel_spr_loan_data SET repay_qty = '$upd_repay_qty' WHERE loan_data_id = '".$input['loan_data_id']."' AND loan_id = '".$input['loan_id']."'");

                $inv_data = [
                    'parts_id' => $input['parts_id'],
                    'required_for' => $input['req_for'],
                    'action_date' => $input['repay_date'],
                    'qty' => $input['repay_qty'],
                    'source' => 3
                ];

                if($this->issuePartsQty($inv_data)){
                    // AUDIT LOG
                    $module = 'Loan';
                    $action = 'Spare Loan Repay Data Added';
                    $action_time = $now;
                    $ip_address = gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    // INSERT AUDIT LOG
                    $audit_query = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module', '$action', '$user_id', '$ip_address', '$action_time')");

                    // AUDIT LOG 2
                    $module_2 = 'Inventory';
                    $action_2 = 'Inventory Updated';

                    // INSERT AUDIT LOG 2
                    $audit_query_2 = mysqli_query($this->conn, "INSERT INTO rrmsteel_audit_log(module, action, user_id, ip_address, action_time) VALUES('$module_2', '$action_2', '$user_id', '$ip_address', '$action_time')");

                    if($audit_query && $audit_query_2){
                        exit(json_encode(array(
                            'Type' => 'success',
                            'Reply' => 'Loan repay data & inventory has been updated successfully!'
                        )));
                    }
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'Loan repay data can not be added! Please try again later.'
                )));
            }
        }
    }
?>