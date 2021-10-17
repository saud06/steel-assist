<?php
    namespace Requisition;

    class Requisition{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // FETCH ALL REQUISITION
        function fetch_all($user_id, $user_category){
            if($user_category == 2){
                $con_requisition_query = mysqli_query($this->conn, "SELECT *, u.user_fullname FROM rrmsteel_con_requisition r INNER JOIN rrmsteel_user u ON u.user_id = r.requisition_by WHERE r.requisition_by = '$user_id' ORDER BY r.requisition_id DESC");
                $spr_requisition_query = mysqli_query($this->conn, "SELECT *, u.user_fullname FROM rrmsteel_spr_requisition r INNER JOIN rrmsteel_user u ON u.user_id = r.requisition_by WHERE r.requisition_by = '$user_id' ORDER BY r.requisition_id DESC");
            } else{
                $con_requisition_query = mysqli_query($this->conn, "SELECT *, u.user_fullname FROM rrmsteel_con_requisition r INNER JOIN rrmsteel_user u ON u.user_id = r.requisition_by ORDER BY r.requisition_id DESC");
                $spr_requisition_query = mysqli_query($this->conn, "SELECT *, u.user_fullname FROM rrmsteel_spr_requisition r INNER JOIN rrmsteel_user u ON u.user_id = r.requisition_by ORDER BY r.requisition_id DESC");
            }

            $flag = 0;
            $flag2 = 0;

            // CONSUMABLE REQUISITION
            if(mysqli_num_rows($con_requisition_query) > 0){
                $i = 0;

                while($row = mysqli_fetch_assoc($con_requisition_query)){
                    $requisition_id = $row['requisition_id'];
                    $requisition_created = $row['requisition_created'];
                    $s_approval_status = $row['s_approval_status'];
                    $approval_status = $row['approval_status'];
                    $p_approval_status = $row['p_approval_status'];

                    $reference = 'RRM\CONSUMABLE-REQUISITION\\' . date('Y', $requisition_created) . '-' . $requisition_id;

                    if($s_approval_status == 0){
                        $s_approval_status_txt = '<span class="text-warning font-weight-bold">Pending</span>';
                    } elseif($s_approval_status == 1){
                        $s_approval_status_txt = '<span class="text-success font-weight-bold">Approved</span>';
                    } elseif($s_approval_status == 2){
                        $s_approval_status_txt = '<span class="text-danger font-weight-bold">Rejected</span>';
                    }

                    if($s_approval_status == 2){
                        $approval_status = '-';
                    } else{
                        if($approval_status == 0){
                            $approval_status_txt = '<span class="text-warning font-weight-bold">Pending</span>';
                        } elseif($approval_status == 1){
                            $approval_status_txt = '<span class="text-success font-weight-bold">Approved</span>';
                        } elseif($approval_status == 2){
                            $approval_status_txt = '<span class="text-danger font-weight-bold">Rejected</span>';
                        }
                    }

                    if($s_approval_status == 2 || $approval_status == 2){
                        $p_approval_status_txt = '-';
                    } else{
                        if($p_approval_status == 0){
                            $p_approval_status_txt = '<span class="text-warning font-weight-bold">Pending</span>';
                        } elseif($p_approval_status == 1){
                            $p_approval_status_txt = '<span class="text-success font-weight-bold">Accepted</span>';
                        }
                    }

                    $action = '';

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 4 && $s_approval_status <= 1 && $p_approval_status == 0 && $approval_status == 1)){
                        $action .= '<a title="'.(($user_category == 4) ? 'Accept' : 'Approve').'" href="javascript:void(0)" class="btn btn-xs btn-primary" data-id="'.$requisition_id.'" onclick="approve_requisition(' . $requisition_id . ', ' . $user_category . ')"><i class="mdi mdi-check"></i></a>';
                    }

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0)){
                        $action .= ' <a title="Reject" href="javascript:void(0)" class="btn btn-xs btn-warning" data-id="'.$requisition_id.'" onclick="reject_requisition('.$requisition_id.')"><i class="mdi mdi-close"></i></a>';
                    }

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 2 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 4 && $s_approval_status <= 1 && $p_approval_status == 0 && $approval_status == 1)){
                        $action .= ' <a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".full-width-modal" data-id="'.$requisition_id.'" onclick="update_requisition('.$requisition_id.')"><i class="mdi mdi-pencil"></i></a>';
                    }

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 2 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0)){
                        $action .= ' <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger" data-id="'.$requisition_id.'" onclick="delete_requisition('.$requisition_id.')"><i class="mdi mdi-delete"></i></a>';
                    }

                    if($s_approval_status == 1 || $approval_status == 1){
                        $action .= ' <a title="View / Print" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-id="'.$requisition_id.'" data-target=".full-width-modal-2" onclick="view_con_requisition('.$requisition_id.')"><i class="mdi mdi-printer"></i></a>';
                    }

                    $data[] = [
                        'sl' => ++$i,
                        'reference' => $reference,
                        'requisition_by' => $row['user_fullname'],
                        's_approval_status' => $s_approval_status_txt,
                        'p_approval_status' => $p_approval_status_txt,
                        'approval_status' => $approval_status_txt,
                        'requisition_created' => date('d M, Y', $requisition_created),
                        'action' => $action
                    ];
                }

                $flag = 1;
            } else{
                $flag = 0;
            }

            // SPARE REQUISITION
            if(mysqli_num_rows($spr_requisition_query) > 0){
                $j = 0;

                while($row = mysqli_fetch_assoc($spr_requisition_query)){
                    $requisition_id = $row['requisition_id'];
                    $requisition_created = $row['requisition_created'];
                    $s_approval_status = $row['s_approval_status'];
                    $approval_status = $row['approval_status'];
                    $p_approval_status = $row['p_approval_status'];

                    $reference = 'RRM\SPARE-REQUISITION\\' . date('Y', $requisition_created) . '-' . $requisition_id;

                    if($s_approval_status == 0){
                        $s_approval_status_txt = '<span class="text-warning font-weight-bold">Pending</span>';
                    } elseif($s_approval_status == 1){
                        $s_approval_status_txt = '<span class="text-success font-weight-bold">Approved</span>';
                    } elseif($s_approval_status == 2){
                        $s_approval_status_txt = '<span class="text-danger font-weight-bold">Rejected</span>';
                    }

                    if($s_approval_status == 2){
                        $approval_status = '-';
                    } else{
                        if($approval_status == 0){
                            $approval_status_txt = '<span class="text-warning font-weight-bold">Pending</span>';
                        } elseif($approval_status == 1){
                            $approval_status_txt = '<span class="text-success font-weight-bold">Approved</span>';
                        } elseif($approval_status == 2){
                            $approval_status_txt = '<span class="text-danger font-weight-bold">Rejected</span>';
                        }
                    }

                    if($s_approval_status == 2 || $approval_status == 2){
                        $p_approval_status_txt = '-';
                    } else{
                        if($p_approval_status == 0){
                            $p_approval_status_txt = '<span class="text-warning font-weight-bold">Pending</span>';
                        } elseif($p_approval_status == 1){
                            $p_approval_status_txt = '<span class="text-success font-weight-bold">Accepted</span>';
                        }
                    }

                    $action = '';

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 4 && $s_approval_status <= 1 && $p_approval_status == 0 && $approval_status == 1)){
                        $action .= '<a title="'.(($user_category == 4) ? 'Accept' : 'Approve').'" href="javascript:void(0)" class="btn btn-xs btn-primary" data-id="'.$requisition_id.'" onclick="approve_requisition2(' . $requisition_id . ', ' . $user_category . ')"><i class="mdi mdi-check"></i></a>';
                    }

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0)){
                        $action .= ' <a title="Reject" href="javascript:void(0)" class="btn btn-xs btn-warning" data-id="'.$requisition_id.'" onclick="reject_requisition2('.$requisition_id.')"><i class="mdi mdi-close"></i></a>';
                    }

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 2 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 4 && $s_approval_status <= 1 && $p_approval_status == 0 && $approval_status == 1)){
                        $action .= ' <a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".full-width-modal" data-id="'.$requisition_id.'" onclick="update_requisition2('.$requisition_id.')"><i class="mdi mdi-pencil"></i></a>';
                    }

                    if(($user_category == 1 && $s_approval_status <= 1 && $approval_status == 0) || ($user_category == 2 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0) || ($user_category == 3 && $s_approval_status == 0 && $p_approval_status == 0 && $approval_status == 0)){
                        $action .= ' <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger" data-id="'.$requisition_id.'" onclick="delete_requisition2('.$requisition_id.')"><i class="mdi mdi-delete"></i></a>';
                    }

                    if($s_approval_status == 1 || $approval_status == 1){
                        $action .= ' <a title="View / Print" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-id="'.$requisition_id.'" data-target=".full-width-modal-2" onclick="view_spr_requisition('.$requisition_id.')"><i class="mdi mdi-printer"></i></a>';
                    }

                    $data2[] = [
                        'sl' => ++$j,
                        'reference' => $reference,
                        'requisition_by' => $row['user_fullname'],
                        's_approval_status' => $s_approval_status_txt,
                        'p_approval_status' => $p_approval_status_txt,
                        'approval_status' => $approval_status_txt,
                        'requisition_created' => date('d M, Y', $requisition_created),
                        'action' => $action
                    ];
                }

                $flag2 = 1;
            } else{
                $flag2 = 0;
            }

            if($flag == 0 && $flag2 == 0){
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No requisition data found !'
                )));
            } else{
                $reply = array(
                    'Type' => 'success',
                    'Reply' => ($flag == 1) ? $data : '',
                    'Reply2' => ($flag2 == 1) ? $data2 : ''
                );

                exit(json_encode($reply));
            }
        }

        // FETCH A CONSUMABLE REQUISITION
        function fetch_consumable($requisition_id){
            $requisition_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_requisition WHERE requisition_id = '$requisition_id'");

            if(mysqli_num_rows($requisition_query) > 0){
                $requisition_info = mysqli_fetch_assoc($requisition_query);

                $user_query = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['requisition_by']."' LIMIT 1");
                $user_info = mysqli_fetch_assoc($user_query);

                $user_query2 = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['approved_by']."' LIMIT 1");
                $user_info2 = mysqli_fetch_assoc($user_query2);
                if(isset($user_info2)){
                    $approved_by = $user_info2['user_fullname'];
                } else{
                    $approved_by = 'None';
                }

                $user_query3 = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['s_approved_by']."' LIMIT 1");
                $user_info3 = mysqli_fetch_assoc($user_query3);
                if(isset($user_info3)){
                    $s_approved_by = $user_info3['user_fullname'];
                } else{
                    $s_approved_by = 'None';
                }

                $user_query4 = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['p_approved_by']."' LIMIT 1");
                $user_info4 = mysqli_fetch_assoc($user_query4);
                if(isset($user_info4)){
                    $p_approved_by = $user_info4['user_fullname'];
                } else{
                    $p_approved_by = 'None';
                }

                $requisition_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks, r.parts_qty AS r_qty, i.parts_qty AS i_qty, i.parts_rate AS price FROM rrmsteel_con_requisition_data r INNER JOIN rrmsteel_parts p ON p.parts_id = r.parts_id INNER JOIN rrmsteel_inv_summary i ON i.parts_id = r.parts_id WHERE requisition_id = '$requisition_id'");

                while($row = mysqli_fetch_assoc($requisition_data_query)){
                    $data[] = [
                        'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y', $requisition_info['requisition_created']) . '-',
                        'requisition_by' => $user_info['user_fullname'],
                        's_approval_status' => $requisition_info['s_approval_status'],
                        's_approved_by' => $s_approved_by,
                        'p_approval_status' => $requisition_info['p_approval_status'],
                        'p_approved_by' => $p_approved_by,
                        'approval_status' => $requisition_info['approval_status'],
                        'approved_by' => $approved_by,
                        'requisition_created' => date('d M, Y', $requisition_info['requisition_created']),
                        'requisition_data_id' => $row['requisition_data_id'],
                        'required_for' => $row['required_for'],
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'r_qty' => $row['r_qty'],
                        'parts_unit' => $row['unit'],
                        'i_qty' => $row['i_qty'],
                        'price' => $row['price'],
                        'parts_usage' => $row['parts_usage'],
                        'remarks' => $row['r_remarks'],
                        'loan' => $row['loan'],
                        'requisition_id' => $row['requisition_id']
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
                    'Reply' => 'No requisition data found !'
                )));
            }
        }

        // FETCH A SPARE REQUISITION
        function fetch_spare($requisition_id){
            $requisition_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_requisition WHERE requisition_id = '$requisition_id'");

            if(mysqli_num_rows($requisition_query) > 0){
                $requisition_info = mysqli_fetch_assoc($requisition_query);

                $user_query = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['requisition_by']."' LIMIT 1");
                $user_info = mysqli_fetch_assoc($user_query);

                $user_query2 = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['approved_by']."' LIMIT 1");
                $user_info2 = mysqli_fetch_assoc($user_query2);
                if(isset($user_info2)){
                    $approved_by = $user_info2['user_fullname'];
                } else{
                    $approved_by = 'None';
                }

                $user_query3 = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['s_approved_by']."' LIMIT 1");
                $user_info3 = mysqli_fetch_assoc($user_query3);
                if(isset($user_info3)){
                    $s_approved_by = $user_info3['user_fullname'];
                } else{
                    $s_approved_by = 'None';
                }

                $user_query4 = mysqli_query($this->conn, "SELECT user_fullname FROM rrmsteel_user WHERE user_id = '".$requisition_info['p_approved_by']."' LIMIT 1");
                $user_info4 = mysqli_fetch_assoc($user_query4);
                if(isset($user_info4)){
                    $p_approved_by = $user_info4['user_fullname'];
                } else{
                    $p_approved_by = 'None';
                }

                $requisition_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks, r.parts_qty AS r_qty, i.parts_qty AS i_qty, i.parts_rate AS price FROM rrmsteel_spr_requisition_data r INNER JOIN rrmsteel_parts p ON p.parts_id = r.parts_id INNER JOIN rrmsteel_inv_summary i ON i.parts_id = r.parts_id WHERE requisition_id = '$requisition_id'");

                while($row = mysqli_fetch_assoc($requisition_data_query)){
                    $data[] = [
                        'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y', $requisition_info['requisition_created']) . '-',
                        'requisition_by' => $user_info['user_fullname'],
                        's_approval_status' => $requisition_info['s_approval_status'],
                        's_approved_by' => $s_approved_by,
                        'p_approval_status' => $requisition_info['p_approval_status'],
                        'p_approved_by' => $p_approved_by,
                        'approval_status' => $requisition_info['approval_status'],
                        'approved_by' => $approved_by,
                        'requisition_created' => date('d M, Y', $requisition_info['requisition_created']),
                        'requisition_data_id' => $row['requisition_data_id'],
                        'required_for' => $row['required_for'],
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'r_qty' => $row['r_qty'],
                        'parts_unit' => $row['unit'],
                        'i_qty' => $row['i_qty'],
                        'price' => $row['price'],
                        'old_spare_details' => $row['old_spare_details'],
                        'status' => $row['status'],
                        'remarks' => $row['r_remarks'],
                        'loan' => $row['loan'],
                        'requisition_id' => $row['requisition_id']
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
                    'Reply' => 'No requisition data found !'
                )));
            }
        }

        // FETCH REQUISITION NUMBER
        function fetch_requisition_num(){
            // CURRENT MONTH
            $requisition_info_1 = mysqli_fetch_array(mysqli_query($this->conn, "SELECT SUM(CASE WHEN approval_status = 0 THEN 1 Else 0 End) tot_pending, SUM(CASE WHEN approval_status = 1 THEN 1 Else 0 End) tot_approved, SUM(CASE WHEN approval_status = 2 THEN 1 Else 0 End) tot_rejected FROM rrmsteel_con_requisition WHERE MONTH(FROM_UNIXTIME(requisition_created)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(requisition_created)) = YEAR(CURDATE())"));

            $requisition_info_2 = mysqli_fetch_array(mysqli_query($this->conn, "SELECT SUM(CASE WHEN approval_status = 0 THEN 1 Else 0 End) tot_pending, SUM(CASE WHEN approval_status = 1 THEN 1 Else 0 End) tot_approved, SUM(CASE WHEN approval_status = 2 THEN 1 Else 0 End) tot_rejected FROM rrmsteel_spr_requisition WHERE MONTH(FROM_UNIXTIME(requisition_created)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(requisition_created)) = YEAR(CURDATE())"));

            $tot_pending = 0; $tot_approved = 0; $tot_rejected = 0;

            if(isset($requisition_info_1['tot_pending']))
                $tot_pending += $requisition_info_1['tot_pending'];
            if(isset($requisition_info_1['tot_approved']))
                $tot_approved += $requisition_info_1['tot_approved'];
            if(isset($requisition_info_1['tot_rejected']))
                $tot_rejected += $requisition_info_1['tot_rejected'];

            if(isset($requisition_info_2['tot_pending']))
                $tot_pending += $requisition_info_2['tot_pending'];
            if(isset($requisition_info_2['tot_approved']))
                $tot_approved += $requisition_info_2['tot_approved'];
            if(isset($requisition_info_2['tot_rejected']))
                $tot_rejected += $requisition_info_2['tot_rejected'];

            // PREVIOUS MONTH
            $requisition_info_3 = mysqli_fetch_array(mysqli_query($this->conn, "SELECT SUM(CASE WHEN approval_status = 0 THEN 1 Else 0 End) tot_pending, SUM(CASE WHEN approval_status = 1 THEN 1 Else 0 End) tot_approved, SUM(CASE WHEN approval_status = 2 THEN 1 Else 0 End) tot_rejected FROM rrmsteel_con_requisition WHERE MONTH(FROM_UNIXTIME(requisition_created)) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(FROM_UNIXTIME(requisition_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH)"));

            $requisition_info_4 = mysqli_fetch_array(mysqli_query($this->conn, "SELECT SUM(CASE WHEN approval_status = 0 THEN 1 Else 0 End) tot_pending, SUM(CASE WHEN approval_status = 1 THEN 1 Else 0 End) tot_approved, SUM(CASE WHEN approval_status = 2 THEN 1 Else 0 End) tot_rejected FROM rrmsteel_spr_requisition WHERE MONTH(FROM_UNIXTIME(requisition_created)) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(FROM_UNIXTIME(requisition_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH)"));

            $tot_pending2 = 0; $tot_approved2 = 0; $tot_rejected2 = 0;

            if(isset($requisition_info_3['tot_pending']))
                $tot_pending2 += $requisition_info_3['tot_pending'];
            if(isset($requisition_info_3['tot_approved']))
                $tot_approved2 += $requisition_info_3['tot_approved'];
            if(isset($requisition_info_3['tot_rejected']))
                $tot_rejected2 += $requisition_info_3['tot_rejected'];

            if(isset($requisition_info_4['tot_pending']))
                $tot_pending2 += $requisition_info_4['tot_pending'];
            if(isset($requisition_info_4['tot_approved']))
                $tot_approved2 += $requisition_info_4['tot_approved'];
            if(isset($requisition_info_4['tot_rejected']))
                $tot_rejected2 += $requisition_info_4['tot_rejected'];

            // PREVIOUS TO PREVIOUS MONTH
            $requisition_info_5 = mysqli_fetch_array(mysqli_query($this->conn, "SELECT SUM(CASE WHEN approval_status = 0 THEN 1 Else 0 End) tot_pending, SUM(CASE WHEN approval_status = 1 THEN 1 Else 0 End) tot_approved, SUM(CASE WHEN approval_status = 2 THEN 1 Else 0 End) tot_rejected FROM rrmsteel_con_requisition WHERE MONTH(FROM_UNIXTIME(requisition_created)) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(FROM_UNIXTIME(requisition_created)) = YEAR(CURDATE() - INTERVAL 2 MONTH)"));

            $requisition_info_6 = mysqli_fetch_array(mysqli_query($this->conn, "SELECT SUM(CASE WHEN approval_status = 0 THEN 1 Else 0 End) tot_pending, SUM(CASE WHEN approval_status = 1 THEN 1 Else 0 End) tot_approved, SUM(CASE WHEN approval_status = 2 THEN 1 Else 0 End) tot_rejected FROM rrmsteel_spr_requisition WHERE MONTH(FROM_UNIXTIME(requisition_created)) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(FROM_UNIXTIME(requisition_created)) = YEAR(CURDATE() - INTERVAL 2 MONTH)"));

            $tot_pending3 = 0; $tot_approved3 = 0; $tot_rejected3 = 0;

            if(isset($requisition_info_5['tot_pending']))
                $tot_pending3 += $requisition_info_5['tot_pending'];
            if(isset($requisition_info_5['tot_approved']))
                $tot_approved3 += $requisition_info_5['tot_approved'];
            if(isset($requisition_info_5['tot_rejected']))
                $tot_rejected3 += $requisition_info_5['tot_rejected'];

            if(isset($requisition_info_6['tot_pending']))
                $tot_pending3 += $requisition_info_6['tot_pending'];
            if(isset($requisition_info_6['tot_approved']))
                $tot_approved3 += $requisition_info_6['tot_approved'];
            if(isset($requisition_info_6['tot_rejected']))
                $tot_rejected3 += $requisition_info_6['tot_rejected'];

            $data[] = [
                'tot_pending' => $tot_pending,
                'tot_approved' => $tot_approved,
                'tot_rejected' => $tot_rejected,
                'tot_pending2' => $tot_pending2,
                'tot_approved2' => $tot_approved2,
                'tot_rejected2' => $tot_rejected2,
                'tot_pending3' => $tot_pending3,
                'tot_approved3' => $tot_approved3,
                'tot_rejected3' => $tot_rejected3
            ];

            $reply = array(
                'Type' => 'success',
                'Reply' => $data
            );

            exit(json_encode($reply));
        }
    }
?>