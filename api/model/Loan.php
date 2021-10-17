<?php
    namespace Loan;

    class Loan{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // FETCH ALL REQUISITION
        function fetch_all($user_id, $user_category){
            $flag = 0;
            $flag2 = 0;
            $flag3 = 0;
            $flag4 = 0;
            $flag5 = 0;

            // CONSUMABLE LOAN
            $con_requisition_query = mysqli_query($this->conn, "SELECT *, u1.user_fullname AS requisitioned_by, u2.user_fullname AS accepted_by FROM rrmsteel_con_requisition r INNER JOIN rrmsteel_user u1 ON u1.user_id = r.requisition_by INNER JOIN rrmsteel_user u2 ON u2.user_id = r.p_approved_by WHERE r.p_approval_status = 1 ORDER BY r.requisition_id DESC");
            
            if(mysqli_num_rows($con_requisition_query) > 0){
                $i = 0;

                while($row = mysqli_fetch_assoc($con_requisition_query)){
                    $requisition_id = $row['requisition_id'];

                    $con_requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(*) AS tot_rec FROM rrmsteel_con_requisition_data WHERE requisition_id = '$requisition_id' AND loan = 1"));

                    if($con_requisition_data_info['tot_rec'] > 0){
                        $requisition_created = $row['requisition_created'];
                        $approval_status = $row['approval_status'];
                        $p_approval_status = $row['p_approval_status'];

                        $reference = 'RRM\CONSUMABLE-REQUISITION\\' . date('Y', $requisition_created) . '-' . $requisition_id;

                        // STATUS
                        $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan WHERE requisition_id = '$requisition_id' LIMIT 1"));

                        if(isset($loan_info)){
                            $requisitioned_parts = $loan_info['requisitioned_parts'];
                            $borrowed_parts = $loan_info['borrowed_parts'];

                            if($borrowed_parts >= $requisitioned_parts){
                                $con_loan_status = 1;

                                $loan_status = '<span class="text-success font-weight-bold">Borrowed</span>';
                            } else{
                                $con_loan_status = 0;

                                $loan_status = '<div class="progress mb-2 progress-sm w-100">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: '.(($borrowed_parts / $requisitioned_parts) * 100).'%;" aria-valuenow="'.(($borrowed_parts / $requisitioned_parts) * 100).'" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>';

                                $loan_status .= '<span class="text-warning font-weight-bold">' . ($requisitioned_parts - $borrowed_parts) . ' of ' . $requisitioned_parts . ' requisitioned parts are pending</span>';
                            }
                        } else{
                            $con_loan_status = 0;
                            $loan_status = '<span class="text-warning font-weight-bold">Pending</span>';
                        }

                        // ACTION
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan WHERE requisition_id = '$requisition_id'");

                        $con_loan_status = 0;

                        if(mysqli_num_rows($con_loan_query) > 0){
                            $loan_info = mysqli_fetch_assoc($con_loan_query);

                            $loan_id = $loan_info['loan_id'];
                            $requisitioned_parts = $loan_info['requisitioned_parts'];
                            $borrowed_parts = $loan_info['borrowed_parts'];

                            if($requisitioned_parts == $borrowed_parts)
                                $con_loan_status = 1;
                        }

                        $action = '';

                        if((($user_category == 1 && $approval_status == 1) || ($user_category == 3 && $p_approval_status == 1) || ($user_category == 4 && $p_approval_status == 1)) && (mysqli_num_rows($con_loan_query) == 0 || $con_loan_status == 0)){
                            if(isset($loan_id)){
                                $action .= '<a title="Mark as Borrowed" href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="mark_loan('. $loan_id .')"><i class="mdi mdi-check"></i></a>';
                            }

                            $action .= ' <a title="Loan" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".full-width-modal" data-id="'.$requisition_id.'" onclick="loan_con('. $requisition_id .')"><i class="mdi mdi-cart"></i></a>';
                        }

                        if(mysqli_num_rows($con_loan_query) > 0){
                            $action .= ' <a title="View" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-target=".full-width-modal-2" data-id="'.$requisition_id.'" onclick="view_loan_con('. $requisition_id .', this)"><i class="mdi mdi-eye"></i></a>';
                        }

                        $data[] = [
                            'sl' => ++$i,
                            'reference' => $reference,
                            'requisitioned_by' => $row['requisitioned_by'],
                            'accepted_by' => $row['accepted_by'],
                            'requisition_created' => date('d M, Y', $requisition_created),
                            'loan_status' => $loan_status,
                            'action' => $action
                        ];

                        $flag = 1;
                    }
                }
            } else{
                $flag = 0;
            }

            // SPARE LOAN
            $spr_requisition_query = mysqli_query($this->conn, "SELECT *, u1.user_fullname AS requisitioned_by, u2.user_fullname AS accepted_by FROM rrmsteel_spr_requisition r INNER JOIN rrmsteel_user u1 ON u1.user_id = r.requisition_by INNER JOIN rrmsteel_user u2 ON u2.user_id = r.p_approved_by WHERE r.p_approval_status = 1 ORDER BY r.requisition_id DESC");

            if(mysqli_num_rows($spr_requisition_query) > 0){
                $j = 0;

                while($row = mysqli_fetch_assoc($spr_requisition_query)){
                    $requisition_id = $row['requisition_id'];

                    $spr_requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(*) AS tot_rec FROM rrmsteel_spr_requisition_data WHERE requisition_id = '$requisition_id' AND loan = 1"));

                    if($spr_requisition_data_info['tot_rec'] > 0){
                        $requisition_created = $row['requisition_created'];
                        $approval_status = $row['approval_status'];
                        $p_approval_status = $row['p_approval_status'];

                        $reference = 'RRM\SPARE-REQUISITION\\' . date('Y', $requisition_created) . '-' . $requisition_id;

                        // STATUS
                        $loan_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan WHERE requisition_id = '$requisition_id' LIMIT 1"));

                        if(isset($loan_info)){
                            $requisitioned_parts = $loan_info['requisitioned_parts'];
                            $borrowed_parts = $loan_info['borrowed_parts'];

                            if($borrowed_parts >= $requisitioned_parts){
                                $spr_loan_status = 1;

                                $loan_status = '<span class="text-success font-weight-bold">Borrowed</span>';
                            } else{
                                $spr_loan_status = 0;

                                $loan_status = '<div class="progress mb-2 progress-sm w-100">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: '.(($borrowed_parts / $requisitioned_parts) * 100).'%;" aria-valuenow="'.(($borrowed_parts / $requisitioned_parts) * 100).'" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>';

                                $loan_status .= '<span class="text-warning font-weight-bold">' . ($requisitioned_parts - $borrowed_parts) . ' of ' . $requisitioned_parts . ' requisitioned parts are pending</span>';
                            }
                        } else{
                            $spr_loan_status = 0;
                            $loan_status = '<span class="text-warning font-weight-bold">Pending</span>';
                        }

                        // ACTION
                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan WHERE requisition_id = '$requisition_id'");

                        $spr_loan_status = 0;

                        if(mysqli_num_rows($spr_loan_query) > 0){
                            $loan_info = mysqli_fetch_assoc($spr_loan_query);

                            $loan_id = $loan_info['loan_id'];
                            $requisitioned_parts = $loan_info['requisitioned_parts'];
                            $borrowed_parts = $loan_info['borrowed_parts'];

                            if($requisitioned_parts == $borrowed_parts)
                                $spr_loan_status = 1;
                        }

                        $action = '';

                        if((($user_category == 1 && $approval_status == 1) || ($user_category == 3 && $p_approval_status == 1) || ($user_category == 4 && $p_approval_status == 1)) && (mysqli_num_rows($spr_loan_query) == 0 || $spr_loan_status == 0)){
                            if(isset($loan_id)){
                                $action .= '<a title="Mark as Borrowed" href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="mark_loan('. $loan_id .')"><i class="mdi mdi-check"></i></a>';
                            }

                            $action .= ' <a title="Loan" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".full-width-modal" data-id="'.$requisition_id.'" onclick="loan_spr('. $requisition_id .')"><i class="mdi mdi-cart"></i></a>';
                        }

                        if(mysqli_num_rows($spr_loan_query) > 0){
                            $action .= ' <a title="View" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-target=".full-width-modal-2" data-id="'.$requisition_id.'" onclick="view_loan_spr('. $requisition_id .', this)"><i class="mdi mdi-eye"></i></a>';
                        }

                        $data2[] = [
                            'sl' => ++$j,
                            'reference' => $reference,
                            'requisitioned_by' => $row['requisitioned_by'],
                            'accepted_by' => $row['accepted_by'],
                            'requisition_created' => date('d M, Y', $requisition_created),
                            'loan_status' => $loan_status,
                            'action' => $action
                        ];

                        $flag2 = 1;
                    }
                }
            } else{
                $flag2 = 0;
            }

            // FILTERED LOAN
            $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 1 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

            $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 1 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

            $k = 0;

            if(mysqli_num_rows($con_requisition_data_query) > 0){
                while($row = mysqli_fetch_assoc($con_requisition_data_query)){
                    $required_for = $row['required_for'];

                    if($required_for == 1)
                        $required_for = 'BCP-CCM';
                    elseif($required_for == 2)
                        $required_for = 'BCP-Furnace';
                    elseif($required_for == 3)
                        $required_for = 'Concast-CCM';
                    elseif($required_for == 4)
                        $required_for = 'Concast-Furnace';
                    elseif($required_for == 5)
                        $required_for = 'HRM';
                    elseif($required_for == 6)
                        $required_for = 'HRM Unit-2';
                    elseif($required_for == 7)
                        $required_for = 'Lal Masjid';
                    elseif($required_for == 8)
                        $required_for = 'Sonargaon';
                    elseif($required_for == 9)
                        $required_for = 'General';

                    $parts_unit = $row['unit'];

                    if($row['unit'] == 1)
                        $parts_unit = 'Bag';
                    elseif($row['unit'] == 2)
                        $parts_unit = 'Box';
                    elseif($row['unit'] == 3)
                        $parts_unit = 'Box/Pcs';
                    elseif($row['unit'] == 4)
                        $parts_unit = 'Bun';
                    elseif($row['unit'] == 5)
                        $parts_unit = 'Bundle';
                    elseif($row['unit'] == 6)
                        $parts_unit = 'Can';
                    elseif($row['unit'] == 7)
                        $parts_unit = 'Cartoon';
                    elseif($row['unit'] == 8)
                        $parts_unit = 'Challan';
                    elseif($row['unit'] == 9)
                        $parts_unit = 'Coil';
                    elseif($row['unit'] == 10)
                        $parts_unit = 'Drum';
                    elseif($row['unit'] == 11)
                        $parts_unit = 'Feet';
                    elseif($row['unit'] == 12)
                        $parts_unit = 'Gallon';
                    elseif($row['unit'] == 13)
                        $parts_unit = 'Item';
                    elseif($row['unit'] == 14)
                        $parts_unit = 'Job';
                    elseif($row['unit'] == 15)
                        $parts_unit = 'Kg';
                    elseif($row['unit'] == 16)
                        $parts_unit = 'Kg/Bundle';
                    elseif($row['unit'] == 17)
                        $parts_unit = 'Kv';
                    elseif($row['unit'] == 18)
                        $parts_unit = 'Lbs';
                    elseif($row['unit'] == 19)
                        $parts_unit = 'Ltr';
                    elseif($row['unit'] == 20)
                        $parts_unit = 'Mtr';
                    elseif($row['unit'] == 21)
                        $parts_unit = 'Pack';
                    elseif($row['unit'] == 22)
                        $parts_unit = 'Pack/Pcs';
                    elseif($row['unit'] == 23)
                        $parts_unit = 'Pair';
                    elseif($row['unit'] == 24)
                        $parts_unit = 'Pcs';
                    elseif($row['unit'] == 25)
                        $parts_unit = 'Pound';
                    elseif($row['unit'] == 26)
                        $parts_unit = 'Qty';
                    elseif($row['unit'] == 27)
                        $parts_unit = 'Roll';
                    elseif($row['unit'] == 28)
                        $parts_unit = 'Set';
                    elseif($row['unit'] == 29)
                        $parts_unit = 'Truck';
                    elseif($row['unit'] == 30)
                        $parts_unit = 'Unit';
                    elseif($row['unit'] == 31)
                        $parts_unit = 'Yeard';
                    elseif($row['unit'] == 32)
                        $parts_unit = '(Unit Unknown)';
                    elseif($row['unit'] == 33)
                        $parts_unit = 'SFT';
                    elseif($row['unit'] == 34)
                        $parts_unit = 'RFT';
                    elseif($row['unit'] == 35)
                        $parts_unit = 'CFT';

                    $data3[] = [
                        'sl' => ++$k,
                        'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                        'required_for' => $required_for,
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_qty' => $row['parts_qty'] . ' ' . $parts_unit,
                        'empty_data' => '',
                        'date' => date('Y-m-d', $row['requisition_data_created'])
                    ];
                }

                $flag3 = 1;
            } else{
                $flag3 = 0;
            }

            if(mysqli_num_rows($spr_requisition_data_query) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_data_query)){
                    $required_for = $row['required_for'];

                    if($required_for == 1)
                        $required_for = 'BCP-CCM';
                    elseif($required_for == 2)
                        $required_for = 'BCP-Furnace';
                    elseif($required_for == 3)
                        $required_for = 'Concast-CCM';
                    elseif($required_for == 4)
                        $required_for = 'Concast-Furnace';
                    elseif($required_for == 5)
                        $required_for = 'HRM';
                    elseif($required_for == 6)
                        $required_for = 'HRM Unit-2';
                    elseif($required_for == 7)
                        $required_for = 'Lal Masjid';
                    elseif($required_for == 8)
                        $required_for = 'Sonargaon';
                    elseif($required_for == 9)
                        $required_for = 'General';

                    $parts_unit = $row['unit'];

                    if($row['unit'] == 1)
                        $parts_unit = 'Bag';
                    elseif($row['unit'] == 2)
                        $parts_unit = 'Box';
                    elseif($row['unit'] == 3)
                        $parts_unit = 'Box/Pcs';
                    elseif($row['unit'] == 4)
                        $parts_unit = 'Bun';
                    elseif($row['unit'] == 5)
                        $parts_unit = 'Bundle';
                    elseif($row['unit'] == 6)
                        $parts_unit = 'Can';
                    elseif($row['unit'] == 7)
                        $parts_unit = 'Cartoon';
                    elseif($row['unit'] == 8)
                        $parts_unit = 'Challan';
                    elseif($row['unit'] == 9)
                        $parts_unit = 'Coil';
                    elseif($row['unit'] == 10)
                        $parts_unit = 'Drum';
                    elseif($row['unit'] == 11)
                        $parts_unit = 'Feet';
                    elseif($row['unit'] == 12)
                        $parts_unit = 'Gallon';
                    elseif($row['unit'] == 13)
                        $parts_unit = 'Item';
                    elseif($row['unit'] == 14)
                        $parts_unit = 'Job';
                    elseif($row['unit'] == 15)
                        $parts_unit = 'Kg';
                    elseif($row['unit'] == 16)
                        $parts_unit = 'Kg/Bundle';
                    elseif($row['unit'] == 17)
                        $parts_unit = 'Kv';
                    elseif($row['unit'] == 18)
                        $parts_unit = 'Lbs';
                    elseif($row['unit'] == 19)
                        $parts_unit = 'Ltr';
                    elseif($row['unit'] == 20)
                        $parts_unit = 'Mtr';
                    elseif($row['unit'] == 21)
                        $parts_unit = 'Pack';
                    elseif($row['unit'] == 22)
                        $parts_unit = 'Pack/Pcs';
                    elseif($row['unit'] == 23)
                        $parts_unit = 'Pair';
                    elseif($row['unit'] == 24)
                        $parts_unit = 'Pcs';
                    elseif($row['unit'] == 25)
                        $parts_unit = 'Pound';
                    elseif($row['unit'] == 26)
                        $parts_unit = 'Qty';
                    elseif($row['unit'] == 27)
                        $parts_unit = 'Roll';
                    elseif($row['unit'] == 28)
                        $parts_unit = 'Set';
                    elseif($row['unit'] == 29)
                        $parts_unit = 'Truck';
                    elseif($row['unit'] == 30)
                        $parts_unit = 'Unit';
                    elseif($row['unit'] == 31)
                        $parts_unit = 'Yeard';
                    elseif($row['unit'] == 32)
                        $parts_unit = '(Unit Unknown)';
                    elseif($row['unit'] == 33)
                        $parts_unit = 'SFT';
                    elseif($row['unit'] == 34)
                        $parts_unit = 'RFT';
                    elseif($row['unit'] == 35)
                        $parts_unit = 'CFT';

                    $data4[] = [
                        'sl' => ++$k,
                        'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                        'required_for' => $required_for,
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_qty' => $row['parts_qty'] . ' ' . $parts_unit,
                        'empty_data' => '',
                        'date' => date('Y-m-d', $row['requisition_data_created'])
                    ];
                }

                $flag4 = 1;
            } else{
                $flag4 = 0;
            }

            $con_arr = []; $spr_arr = [];

            // REPAY LOAN
            $con_requisition_data_query_2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS borrowed_qty, SUM(repay_qty) AS repaid_qty FROM rrmsteel_con_loan_data GROUP BY parts_id");

            if(mysqli_num_rows($con_requisition_data_query_2) > 0){
                while($row = mysqli_fetch_assoc($con_requisition_data_query_2)){
                    array_push(
                        $con_arr,
                        (object) [
                            'parts_id' => $row['parts_id'],
                            'borrowed_qty' => $row['borrowed_qty'],
                            'repaid_qty' => $row['repaid_qty']
                        ]
                    );
                }
            }

            $spr_requisition_data_query_2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS borrowed_qty, SUM(repay_qty) AS repaid_qty FROM rrmsteel_spr_loan_data GROUP BY parts_id");

            if(mysqli_num_rows($spr_requisition_data_query_2) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_data_query_2)){
                    array_push(
                        $spr_arr,
                        (object) [
                            'parts_id' => $row['parts_id'],
                            'borrowed_qty' => $row['borrowed_qty'],
                            'repaid_qty' => $row['repaid_qty']
                        ]
                    );
                }
            }

            $parts_data = array_column(array_merge($con_arr, $spr_arr), NULL, 'parts_id');
            ksort($parts_data);
            $parts_data = array_values($parts_data);

            if(count($parts_data) > 0){
                $parts_id_str = implode(',', array_map(function($p){
                    return $p->parts_id;
                }, $parts_data));

                $inv_summary_query = mysqli_query($this->conn, "SELECT i.*, p.parts_name, p.unit, p.category FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.parts_id IN (".$parts_id_str.")");

                if(mysqli_num_rows($inv_summary_query) > 0){
                    $l = 0;

                    while($row = mysqli_fetch_assoc($inv_summary_query)){
                        $parts_id = $row['parts_id'];
                        $parts_category = $row['category'];

                        if($row['unit'] == 1) $parts_unit = 'Bag';
                        elseif($row['unit'] == 2) $parts_unit = 'Box';
                        elseif($row['unit'] == 3) $parts_unit = 'Box/Pcs';
                        elseif($row['unit'] == 4) $parts_unit = 'Bun';
                        elseif($row['unit'] == 5) $parts_unit = 'Bundle';
                        elseif($row['unit'] == 6) $parts_unit = 'Can';
                        elseif($row['unit'] == 7) $parts_unit = 'Cartoon';
                        elseif($row['unit'] == 8) $parts_unit = 'Challan';
                        elseif($row['unit'] == 9) $parts_unit = 'Coil';
                        elseif($row['unit'] == 10) $parts_unit = 'Drum';
                        elseif($row['unit'] == 11) $parts_unit = 'Feet';
                        elseif($row['unit'] == 12) $parts_unit = 'Gallon';
                        elseif($row['unit'] == 13) $parts_unit = 'Item';
                        elseif($row['unit'] == 14) $parts_unit = 'Job';
                        elseif($row['unit'] == 15) $parts_unit = 'Kg';
                        elseif($row['unit'] == 16) $parts_unit = 'Kg/Bundle';
                        elseif($row['unit'] == 17) $parts_unit = 'Kv';
                        elseif($row['unit'] == 18) $parts_unit = 'Lbs';
                        elseif($row['unit'] == 19) $parts_unit = 'Ltr';
                        elseif($row['unit'] == 20) $parts_unit = 'Mtr';
                        elseif($row['unit'] == 21) $parts_unit = 'Pack';
                        elseif($row['unit'] == 22) $parts_unit = 'Pack/Pcs';
                        elseif($row['unit'] == 23) $parts_unit = 'Pair';
                        elseif($row['unit'] == 24) $parts_unit = 'Pcs';
                        elseif($row['unit'] == 25) $parts_unit = 'Pound';
                        elseif($row['unit'] == 26) $parts_unit = 'Qty';
                        elseif($row['unit'] == 27) $parts_unit = 'Roll';
                        elseif($row['unit'] == 28) $parts_unit = 'Set';
                        elseif($row['unit'] == 29) $parts_unit = 'Truck';
                        elseif($row['unit'] == 30) $parts_unit = 'Unit';
                        elseif($row['unit'] == 31) $parts_unit = 'Yeard';
                        elseif($row['unit'] == 32) $parts_unit = '(Unit Unknown)';
                        elseif($row['unit'] == 33) $parts_unit = 'SFT';
                        elseif($row['unit'] == 34) $parts_unit = 'RFT';
                        elseif($row['unit'] == 35) $parts_unit = 'CFT';

                        // ACTION
                        $action = '';

                        if($parts_data[$l]->repaid_qty < $parts_data[$l]->borrowed_qty){
                            $action .= '<a title="Parts Loan" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".bs-example-modal-lg3" data-id="'.$parts_id.'" onclick="parts_loan('. $parts_id .', '. $parts_category .', this)"><i class="mdi mdi-swap-vertical-variant"></i></a> ';
                        }

                        $action .= '<a title="Loan Repay History" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-target=".bs-example-modal-lg4" data-id="'.$parts_id.'" onclick="loan_repay_history('. $parts_id .', '. $parts_category .', this)"><i class="mdi mdi-format-list-bulleted-type"></i></a>';

                        $data5[] = [
                            'sl' => ($l+1),
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'parts_qty' => $row['parts_qty'],
                            'borrowed_qty' => $parts_data[$l]->borrowed_qty,
                            'repaid_qty' => $parts_data[$l]->repaid_qty,
                            'parts_avg_rate' => $row['parts_avg_rate'],
                            'action' => $action
                        ];

                        $l++;
                    }

                    $flag5 = 1;
                } else{
                    $flag5 = 0;
                }
            } else{
                $flag5 = 0;
            }

            if($flag3 = 1 && $flag4 == 1){
                $merged = array_merge($data3, $data4);
            } elseif($flag3 = 1 && $flag4 == 0){
                $merged = $data3;
            } elseif($flag3 = 0 && $flag4 == 1){
                $merged = $data4;
            } else{
                $merged = '';
            }

            if($flag == 0 && $flag2 == 0 && !$merged && $flag5 == 0){
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No Loan data found !'
                )));
            } else{
                $reply = array(
                    'Type' => 'success',
                    'Reply' => ($flag == 1) ? $data : '',
                    'Reply2' => ($flag2 == 1) ? $data2 : '',
                    'Reply3' => $merged ? $merged : '',
                    'Reply4' => ($flag5 == 1) ? $data5 : '',
                );

                exit(json_encode($reply));
            }
        }

        // FETCH A CONSUMABLE REQUISITION
        function fetch_requisition_con($requisition_id){
            $requisition_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_requisition WHERE requisition_id = '$requisition_id'");

            if(mysqli_num_rows($requisition_query) > 0){
                $requisition_info = mysqli_fetch_assoc($requisition_query);

                $requisition_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks, r.parts_qty AS r_qty, i.parts_qty AS i_qty, i.parts_rate AS price FROM rrmsteel_con_requisition_data r INNER JOIN rrmsteel_parts p ON p.parts_id = r.parts_id INNER JOIN rrmsteel_inv_summary i ON i.parts_id = r.parts_id WHERE r.requisition_id = '$requisition_id' AND r.loan = 1");

                if(mysqli_num_rows($requisition_data_query) > 0){
                    while($row = mysqli_fetch_assoc($requisition_data_query)){
                        $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT rd.parts_id AS borrowed_parts_id, SUM(rd.parts_qty) AS borrowed_parts_qty FROM rrmsteel_con_loan_data rd INNER JOIN rrmsteel_con_loan r ON r.loan_id = rd.loan_id WHERE r.requisition_id = '$requisition_id' AND rd.parts_id = '".$row['parts_id']."' LIMIT 1"));

                        if(isset($loan_data_info)){
                            $borrowed_parts_id = $loan_data_info['borrowed_parts_id'] == null ? 0 : $loan_data_info['borrowed_parts_id'];
                            $borrowed_parts_qty = $loan_data_info['borrowed_parts_qty'] == null ? 0 : $loan_data_info['borrowed_parts_qty'];
                        } else{
                            $borrowed_parts_id = 0;
                            $borrowed_parts_qty = 0;
                        }

                        $data[] = [
                            'p_approval_status' => $requisition_info['p_approval_status'],
                            'approval_status' => $requisition_info['approval_status'],
                            'required_for' => $row['required_for'],
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'r_qty' => $row['r_qty'],
                            'parts_unit' => $row['unit'],
                            'i_qty' => $row['i_qty'],
                            'price' => $row['price'],
                            'parts_usage' => $row['parts_usage'],
                            'remarks' => $row['r_remarks'],
                            'requisition_id' => $row['requisition_id'],
                            'borrowed_parts_id' => $borrowed_parts_id,
                            'borrowed_parts_qty' => $borrowed_parts_qty
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
                        'Reply' => 'No loan data found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No loan data found !'
                )));
            }
        }

        // FETCH A SPARE REQUISITION
        function fetch_requisition_spr($requisition_id){
            $requisition_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_requisition WHERE requisition_id = '$requisition_id'");

            if(mysqli_num_rows($requisition_query) > 0){
                $requisition_info = mysqli_fetch_assoc($requisition_query);

                $requisition_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks, r.parts_qty AS r_qty, i.parts_qty AS i_qty, i.parts_rate AS price FROM rrmsteel_spr_requisition_data r INNER JOIN rrmsteel_parts p ON p.parts_id = r.parts_id INNER JOIN rrmsteel_inv_summary i ON i.parts_id = r.parts_id WHERE r.requisition_id = '$requisition_id' AND r.loan = 1");

                if(mysqli_num_rows($requisition_data_query) > 0){
                    while($row = mysqli_fetch_assoc($requisition_data_query)){
                        $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT rd.parts_id AS borrowed_parts_id, SUM(rd.parts_qty) AS borrowed_parts_qty FROM rrmsteel_spr_loan_data rd INNER JOIN rrmsteel_spr_loan r ON r.loan_id = rd.loan_id WHERE r.requisition_id = '$requisition_id' AND rd.parts_id = '".$row['parts_id']."' LIMIT 1"));

                        if(isset($loan_data_info)){
                            $borrowed_parts_id = $loan_data_info['borrowed_parts_id'] == null ? 0 : $loan_data_info['borrowed_parts_id'];
                            $borrowed_parts_qty = $loan_data_info['borrowed_parts_qty'] == null ? 0 : $loan_data_info['borrowed_parts_qty'];
                        } else{
                            $borrowed_parts_id = 0;
                            $borrowed_parts_qty = 0;
                        }

                        $data[] = [
                            'p_approval_status' => $requisition_info['p_approval_status'],
                            'approval_status' => $requisition_info['approval_status'],
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
                            'requisition_id' => $row['requisition_id'],
                            'borrowed_parts_id' => $borrowed_parts_id,
                            'borrowed_parts_qty' => $borrowed_parts_qty
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
                        'Reply' => 'No loan data found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No loan data found !'
                )));
            }
        }

        // FETCH A CONSUMABLE LOAN
        function fetch_loan_con($requisition_id){
            $loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan WHERE requisition_id = '$requisition_id' LIMIT 1");

            if(mysqli_num_rows($loan_query) > 0){
                $loan_info = mysqli_fetch_assoc($loan_query);
                $loan_id = $loan_info['loan_id'];

                $loan_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks FROM rrmsteel_con_loan_data r INNER JOIN rrmsteel_parts pr ON pr.parts_id = r.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = r.party_id WHERE loan_id = '$loan_id' ORDER BY r.parts_id");

                while($row = mysqli_fetch_assoc($loan_data_query)){
                    $parts_id = $row['parts_id'];

                    $requisition_data_query = mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_con_requisition_data WHERE requisition_id = '$requisition_id' AND parts_id = '$parts_id' AND loan = 1 LIMIT 1");
                    $requisition_info = mysqli_fetch_assoc($requisition_data_query);
                    $requisitioned_parts_qty = $requisition_info['parts_qty'];

                    $loan_data_query_2 = mysqli_query($this->conn, "SELECT COUNT(*) AS loan_count, SUM(parts_qty) AS borrowed_parts_qty FROM rrmsteel_con_loan_data r WHERE loan_id = '$loan_id' AND parts_id = '$parts_id'");
                    $loan_data_info = mysqli_fetch_assoc($loan_data_query_2);
                    $borrowed_parts_qty = $loan_data_info['borrowed_parts_qty'];
                    $loan_count = $loan_data_info['loan_count'];

                    $data1[] = [
                        'loan_id' => $loan_id,
                        'required_for' => $row['required_for'],
                        'parts_id' => $parts_id,
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'requisitioned_parts_qty' => $requisitioned_parts_qty,
                        'borrowed_parts_qty' => $borrowed_parts_qty,
                        'loan_indx_f' => 1,
                        'loan_indx_l' => (int)$loan_count,
                        'parts_usage' => $row['parts_usage'],
                        'remarks' => $row['r_remarks']
                    ];

                    $data2[] = [
                        'loan_data_id' => $row['loan_data_id'],
                        'parts_id' => $parts_id,
                        'parts_qty' => $row['parts_qty'],
                        'price' => $row['price'],
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'gate_no' => $row['gate_no'],
                        'challan_no' => $row['challan_no'],
                        'challan_photo' => $row['challan_photo'],
                        'bill_photo' => $row['bill_photo'],
                        'loan_date' => $row['loan_date']
                    ];
                }

                $data1 = array_values(array_unique($data1, SORT_REGULAR));

                foreach($data1 as $key => $value){
                    if($key == 0){
                        $loan_indx_f = $value['loan_indx_f'];
                        $loan_indx_l = $value['loan_indx_l'];
                    } else{
                        $loan_indx_f = $loan_indx_l + 2;
                        $loan_indx_l = $loan_indx_f + $value['loan_indx_l'] - 1;
                    }

                    $data1[$key]['loan_indx_f'] = $loan_indx_f;
                    $data1[$key]['loan_indx_l'] = $loan_indx_l;
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply1' => $data1,
                    'Reply2' => $data2
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No loan data found !'
                )));
            }
        }

        // FETCH A SPARE LOAN
        function fetch_loan_spr($requisition_id){
            $loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan WHERE requisition_id = '$requisition_id' LIMIT 1");

            if(mysqli_num_rows($loan_query) > 0){
                $loan_info = mysqli_fetch_assoc($loan_query);
                $loan_id = $loan_info['loan_id'];

                $loan_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks FROM rrmsteel_spr_loan_data r INNER JOIN rrmsteel_parts pr ON pr.parts_id = r.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = r.party_id WHERE loan_id = '$loan_id' ORDER BY r.parts_id");

                while($row = mysqli_fetch_assoc($loan_data_query)){
                    $parts_id = $row['parts_id'];

                    $requisition_data_query = mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_spr_requisition_data WHERE requisition_id = '$requisition_id' AND parts_id = '$parts_id' AND loan = 1 LIMIT 1");
                    $requisition_info = mysqli_fetch_assoc($requisition_data_query);
                    $requisitioned_parts_qty = $requisition_info['parts_qty'];

                    $loan_data_query_2 = mysqli_query($this->conn, "SELECT COUNT(*) AS loan_count, SUM(parts_qty) AS borrowed_parts_qty FROM rrmsteel_spr_loan_data r WHERE loan_id = '$loan_id' AND parts_id = '$parts_id'");
                    $loan_data_info = mysqli_fetch_assoc($loan_data_query_2);
                    $borrowed_parts_qty = $loan_data_info['borrowed_parts_qty'];
                    $loan_count = $loan_data_info['loan_count'];

                    $data1[] = [
                        'loan_id' => $loan_id,
                        'required_for' => $row['required_for'],
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'requisitioned_parts_qty' => $requisitioned_parts_qty,
                        'borrowed_parts_qty' => $borrowed_parts_qty,
                        'loan_indx_f' => 1,
                        'loan_indx_l' => (int)$loan_count,
                        'old_spare_details' => $row['old_spare_details'],
                        'status' => (($row['status'] == 1) ? 'Repairable' : 'Unusual'),
                        'remarks' => $row['r_remarks']
                    ];

                    $data2[] = [
                        'loan_data_id' => $row['loan_data_id'],
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty'],
                        'price' => $row['price'],
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'gate_no' => $row['gate_no'],
                        'challan_no' => $row['challan_no'],
                        'challan_photo' => $row['challan_photo'],
                        'bill_photo' => $row['bill_photo'],
                        'loan_date' => $row['loan_date']
                    ];
                }

                $data1 = array_values(array_unique($data1, SORT_REGULAR));

                foreach($data1 as $key => $value){
                    if($key == 0){
                        $loan_indx_f = $value['loan_indx_f'];
                        $loan_indx_l = $value['loan_indx_l'];
                    } else{
                        $loan_indx_f = $loan_indx_l + 2;
                        $loan_indx_l = $loan_indx_f + $value['loan_indx_l'] - 1;
                    }

                    $data1[$key]['loan_indx_f'] = $loan_indx_f;
                    $data1[$key]['loan_indx_l'] = $loan_indx_l;
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply1' => $data1,
                    'Reply2' => $data2
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No loan data found !'
                )));
            }
        }

        // FETCH FILTERED LOAN
        function fetch_filtered_loan($type, $party_id, $parts_id, $parts_nickname, $date_range = null){
            $data1 = [];
            $data2 = [];
            $data3 = [];
            $data4 = [];

            $flag = 0;
            $flag2 = 0;
            $flag3 = 0;
            $flag4 = 0;

            if($date_range){
                $date_range = explode(' to ', $date_range);
                $start_date = $date_range[0];
                $end_date = $date_range[1];
            }

            if($type == 1){
                if(!$party_id && !$parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id");
                    } else{
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // LOAN DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // loan DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && $parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_id = '$parts_id'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_id = '$parts_id'");
                    } else{
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_id = '$parts_id' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_id = '$parts_id' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // LOAN DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // loan DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && !$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_nickname = '$parts_nickname'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_nickname = '$parts_nickname'");
                    } else{
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_nickname = '$parts_nickname' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.parts_nickname = '$parts_nickname' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // LOAN DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // loan DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id'");
                    } else{
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");

                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // LOAN DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // loan DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && $parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_id = '$parts_id'");
                        
                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_id = '$parts_id'");
                    } else{
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_id = '$parts_id' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                        
                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_id = '$parts_id' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // LOAN DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // loan DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_nickname = '$parts_nickname'");
                        
                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_nickname = '$parts_nickname'");
                    } else{
                        $con_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data pr INNER JOIN rrmsteel_con_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_nickname = '$parts_nickname' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                        
                        $spr_loan_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data pr INNER JOIN rrmsteel_spr_loan p ON p.loan_id = pr.loan_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.parts_nickname = '$parts_nickname' AND pr.loan_date >= '$start_date' AND pr.loan_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // LOAN DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_loan_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for_txt = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for_txt = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for_txt = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for_txt = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for_txt = 'HRM';
                            elseif($required_for == 6)
                                $required_for_txt = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for_txt = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for_txt = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for_txt = 'General';

                            $parts_id = $row['parts_id'];
                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            // PARTS QTY
                            $parts_qty_txt = '<span class="data-span">' . $parts_qty . ' ' . $parts_unit . '</span>';

                            $parts_qty_txt .= '<div class="input-group data-input d-none">';
                                $parts_qty_txt .= '<input type="number" class="form-control" placeholder="Insert" value="'.$parts_qty.'" oninput="qty_3(this, ' . $parts_qty . ')">';
                                $parts_qty_txt .= '<div class="input-group-prepend">';
                                    $parts_qty_txt .= '<div class="input-group-text">' . $parts_unit . '</div>';
                                $parts_qty_txt .= '</div>';
                            $parts_qty_txt .= '</div>';

                            // PARTS PRICE
                            $parts_price = $row['price'];

                            $parts_price_txt = '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' . $parts_price . '</span>';

                            $parts_price_txt .= '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'.$parts_price.'" oninput="price_2(this, ' . $parts_price . ')">';

                            // PARTY
                            $party_id = $row['party_id'];
                            $party_name = $row['party_name'];

                            $party_name_txt = '<span class="data-span">' . $party_name . '</span>';

                            $party_name_txt .= '<div class="data-input d-none">';
                                $party_name_txt .= '<select class="select-b-party" onchange="party_name_2(this)">';
                                    $party_name_txt .= '<option value="'.$party_id.'" selected>' . $party_name . '</option>';
                                $party_name_txt .= '</select>';
                                $party_name_txt .= '<span class="float-left mt-1 text-primary remarks"></span>';
                            $party_name_txt .= '</div>';

                            // GATE NO
                            $gate_no = $row['gate_no'];

                            $gate_no_txt = '<span class="data-span">' . $gate_no . '</span>';

                            $gate_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$gate_no.'">';

                            // CHALLAN NO
                            $challan_no = $row['challan_no'];

                            $challan_no_txt = '<span class="data-span">' . $challan_no . '</span>';

                            $challan_no_txt .= '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'.$challan_no.'">';

                            // loan DATE
                            $loan_date = $row['loan_date'];

                            $loan_date_txt = '<span class="data-span">' . $loan_date . '</span>';

                            $loan_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$loan_date.'">';

                            // ACTION
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $loan_data_id . ', ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr_2(' . $loan_data_id . ', this, ' . $loan_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con_2(' . $loan_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $loan_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'loan_data_id' => $loan_data_id,
                                'loan_id' => $loan_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $loan_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                }

                if($flag = 1 && $flag2 == 1){
                    $merged = array_merge($data1, $data2);
                } elseif($flag = 1 && $flag2 == 0){
                    $merged = $data1;
                } elseif($flag = 0 && $flag2 == 1){
                    $merged = $data2;
                } else{
                    $merged = '';
                }
            } else{
                if($date_range){
                    $start_date_timestamp = strtotime($start_date);
                    $end_date_timestamp = strtotime($end_date) + 86399;
                }

                if($parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 1 AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_con_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $con_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_id = '$parts_id' GROUP BY prd.loan_id, prd.parts_id");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 1 AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_spr_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $spr_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_id = '$parts_id' GROUP BY prd.loan_id, prd.parts_id");
                    } else{
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 1 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_con_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $con_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_id = '$parts_id' AND prd.loan_data_created >= '$start_date_timestamp' AND prd.loan_data_created <= '$end_date_timestamp' GROUP BY prd.loan_id, prd.parts_id");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 1 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_spr_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $spr_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_id = '$parts_id' AND prd.loan_data_created >= '$start_date_timestamp' AND prd.loan_data_created <= '$end_date_timestamp' GROUP BY prd.loan_id, prd.parts_id");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_requisition_data_query) > 0){
                        while($row = mysqli_fetch_assoc($con_requisition_data_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for = 'HRM';
                            elseif($required_for == 6)
                                $required_for = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for = 'General';

                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            $parts_qty_txt = $parts_qty . ' ' . $parts_unit;

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'empty_data' => '',
                                'date' => date('Y-m-d', $row['requisition_data_created'])
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_requisition_data_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_requisition_data_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for = 'HRM';
                            elseif($required_for == 6)
                                $required_for = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for = 'General';

                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            $parts_qty_txt = $parts_qty . ' ' . $parts_unit;

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'empty_data' => '',
                                'date' => date('Y-m-d', $row['requisition_data_created'])
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }

                    if(mysqli_num_rows($con_loan_data_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_data_query)){
                            $con_loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (req_qty - SUM(parts_qty)) AS rem_qty FROM rrmsteel_con_loan_data WHERE loan_id = '".$row['loan_id']."' GROUP BY parts_id"));
                            $rem_qty = $con_loan_data_info['rem_qty'];

                            if($rem_qty > 0){
                                $required_for = $row['required_for'];

                                if($required_for == 1)
                                    $required_for = 'BCP-CCM';
                                elseif($required_for == 2)
                                    $required_for = 'BCP-Furnace';
                                elseif($required_for == 3)
                                    $required_for = 'Concast-CCM';
                                elseif($required_for == 4)
                                    $required_for = 'Concast-Furnace';
                                elseif($required_for == 5)
                                    $required_for = 'HRM';
                                elseif($required_for == 6)
                                    $required_for = 'HRM Unit-2';
                                elseif($required_for == 7)
                                    $required_for = 'Lal Masjid';
                                elseif($required_for == 8)
                                    $required_for = 'Sonargaon';
                                elseif($required_for == 9)
                                    $required_for = 'General';

                                $parts_unit = $row['unit'];

                                if($row['unit'] == 1)
                                    $parts_unit = 'Bag';
                                elseif($row['unit'] == 2)
                                    $parts_unit = 'Box';
                                elseif($row['unit'] == 3)
                                    $parts_unit = 'Box/Pcs';
                                elseif($row['unit'] == 4)
                                    $parts_unit = 'Bun';
                                elseif($row['unit'] == 5)
                                    $parts_unit = 'Bundle';
                                elseif($row['unit'] == 6)
                                    $parts_unit = 'Can';
                                elseif($row['unit'] == 7)
                                    $parts_unit = 'Cartoon';
                                elseif($row['unit'] == 8)
                                    $parts_unit = 'Challan';
                                elseif($row['unit'] == 9)
                                    $parts_unit = 'Coil';
                                elseif($row['unit'] == 10)
                                    $parts_unit = 'Drum';
                                elseif($row['unit'] == 11)
                                    $parts_unit = 'Feet';
                                elseif($row['unit'] == 12)
                                    $parts_unit = 'Gallon';
                                elseif($row['unit'] == 13)
                                    $parts_unit = 'Item';
                                elseif($row['unit'] == 14)
                                    $parts_unit = 'Job';
                                elseif($row['unit'] == 15)
                                    $parts_unit = 'Kg';
                                elseif($row['unit'] == 16)
                                    $parts_unit = 'Kg/Bundle';
                                elseif($row['unit'] == 17)
                                    $parts_unit = 'Kv';
                                elseif($row['unit'] == 18)
                                    $parts_unit = 'Lbs';
                                elseif($row['unit'] == 19)
                                    $parts_unit = 'Ltr';
                                elseif($row['unit'] == 20)
                                    $parts_unit = 'Mtr';
                                elseif($row['unit'] == 21)
                                    $parts_unit = 'Pack';
                                elseif($row['unit'] == 22)
                                    $parts_unit = 'Pack/Pcs';
                                elseif($row['unit'] == 23)
                                    $parts_unit = 'Pair';
                                elseif($row['unit'] == 24)
                                    $parts_unit = 'Pcs';
                                elseif($row['unit'] == 25)
                                    $parts_unit = 'Pound';
                                elseif($row['unit'] == 26)
                                    $parts_unit = 'Qty';
                                elseif($row['unit'] == 27)
                                    $parts_unit = 'Roll';
                                elseif($row['unit'] == 28)
                                    $parts_unit = 'Set';
                                elseif($row['unit'] == 29)
                                    $parts_unit = 'Truck';
                                elseif($row['unit'] == 30)
                                    $parts_unit = 'Unit';
                                elseif($row['unit'] == 31)
                                    $parts_unit = 'Yeard';
                                elseif($row['unit'] == 32)
                                    $parts_unit = '(Unit Unknown)';
                                elseif($row['unit'] == 33)
                                    $parts_unit = 'SFT';
                                elseif($row['unit'] == 34)
                                    $parts_unit = 'RFT';
                                elseif($row['unit'] == 35)
                                    $parts_unit = 'CFT';

                                $parts_qty_txt = $rem_qty . ' ' . $parts_unit;

                                $data3[] = [
                                    'sl' => ++$i,
                                    'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                    'required_for' => $required_for,
                                    'parts_id' => $row['parts_id'],
                                    'parts_name' => $row['parts_name'],
                                    'parts_qty' => $parts_qty_txt,
                                    'empty_data' => '',
                                    'date' => $row['loan_date']
                                ];

                                $flag3 = 1;
                            }
                        }
                    } else{
                        $flag3 = 0;
                    }

                    if(mysqli_num_rows($spr_loan_data_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_data_query)){
                            $spr_loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (req_qty - SUM(parts_qty)) AS rem_qty FROM rrmsteel_spr_loan_data WHERE loan_id = '".$row['loan_id']."' GROUP BY parts_id"));
                            $rem_qty = $spr_loan_data_info['rem_qty'];

                            if($rem_qty > 0){
                                $required_for = $row['required_for'];

                                if($required_for == 1)
                                    $required_for = 'BCP-CCM';
                                elseif($required_for == 2)
                                    $required_for = 'BCP-Furnace';
                                elseif($required_for == 3)
                                    $required_for = 'Concast-CCM';
                                elseif($required_for == 4)
                                    $required_for = 'Concast-Furnace';
                                elseif($required_for == 5)
                                    $required_for = 'HRM';
                                elseif($required_for == 6)
                                    $required_for = 'HRM Unit-2';
                                elseif($required_for == 7)
                                    $required_for = 'Lal Masjid';
                                elseif($required_for == 8)
                                    $required_for = 'Sonargaon';
                                elseif($required_for == 9)
                                    $required_for = 'General';

                                $parts_unit = $row['unit'];

                                if($row['unit'] == 1)
                                    $parts_unit = 'Bag';
                                elseif($row['unit'] == 2)
                                    $parts_unit = 'Box';
                                elseif($row['unit'] == 3)
                                    $parts_unit = 'Box/Pcs';
                                elseif($row['unit'] == 4)
                                    $parts_unit = 'Bun';
                                elseif($row['unit'] == 5)
                                    $parts_unit = 'Bundle';
                                elseif($row['unit'] == 6)
                                    $parts_unit = 'Can';
                                elseif($row['unit'] == 7)
                                    $parts_unit = 'Cartoon';
                                elseif($row['unit'] == 8)
                                    $parts_unit = 'Challan';
                                elseif($row['unit'] == 9)
                                    $parts_unit = 'Coil';
                                elseif($row['unit'] == 10)
                                    $parts_unit = 'Drum';
                                elseif($row['unit'] == 11)
                                    $parts_unit = 'Feet';
                                elseif($row['unit'] == 12)
                                    $parts_unit = 'Gallon';
                                elseif($row['unit'] == 13)
                                    $parts_unit = 'Item';
                                elseif($row['unit'] == 14)
                                    $parts_unit = 'Job';
                                elseif($row['unit'] == 15)
                                    $parts_unit = 'Kg';
                                elseif($row['unit'] == 16)
                                    $parts_unit = 'Kg/Bundle';
                                elseif($row['unit'] == 17)
                                    $parts_unit = 'Kv';
                                elseif($row['unit'] == 18)
                                    $parts_unit = 'Lbs';
                                elseif($row['unit'] == 19)
                                    $parts_unit = 'Ltr';
                                elseif($row['unit'] == 20)
                                    $parts_unit = 'Mtr';
                                elseif($row['unit'] == 21)
                                    $parts_unit = 'Pack';
                                elseif($row['unit'] == 22)
                                    $parts_unit = 'Pack/Pcs';
                                elseif($row['unit'] == 23)
                                    $parts_unit = 'Pair';
                                elseif($row['unit'] == 24)
                                    $parts_unit = 'Pcs';
                                elseif($row['unit'] == 25)
                                    $parts_unit = 'Pound';
                                elseif($row['unit'] == 26)
                                    $parts_unit = 'Qty';
                                elseif($row['unit'] == 27)
                                    $parts_unit = 'Roll';
                                elseif($row['unit'] == 28)
                                    $parts_unit = 'Set';
                                elseif($row['unit'] == 29)
                                    $parts_unit = 'Truck';
                                elseif($row['unit'] == 30)
                                    $parts_unit = 'Unit';
                                elseif($row['unit'] == 31)
                                    $parts_unit = 'Yeard';
                                elseif($row['unit'] == 32)
                                    $parts_unit = '(Unit Unknown)';
                                elseif($row['unit'] == 33)
                                    $parts_unit = 'SFT';
                                elseif($row['unit'] == 34)
                                    $parts_unit = 'RFT';
                                elseif($row['unit'] == 35)
                                    $parts_unit = 'CFT';

                                $parts_qty_txt = $rem_qty . ' ' . $parts_unit;

                                $data4[] = [
                                    'sl' => ++$i,
                                    'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                    'required_for' => $required_for,
                                    'parts_id' => $row['parts_id'],
                                    'parts_name' => $row['parts_name'],
                                    'parts_qty' => $parts_qty_txt,
                                    'empty_data' => '',
                                    'date' => $row['loan_date']
                                ];

                                $flag4 = 1;
                            }
                        }
                    } else{
                        $flag4 = 0;
                    }
                } elseif(!$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_nickname = '$parts_nickname' AND rd.loan = 1 AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_con_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $con_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_nickname = '$parts_nickname' GROUP BY prd.loan_id, prd.parts_id");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_nickname = '$parts_nickname' AND rd.loan = 1 AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_spr_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $spr_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_nickname = '$parts_nickname' GROUP BY prd.loan_id, prd.parts_id");
                    } else{
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_nickname = '$parts_nickname' AND rd.loan = 1 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_con_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $con_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_nickname = '$parts_nickname' AND prd.loan_data_created >= '$start_date_timestamp' AND prd.loan_data_created <= '$end_date_timestamp' GROUP BY prd.loan_id, prd.parts_id");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_nickname = '$parts_nickname' AND rd.loan = 1 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_spr_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $spr_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id WHERE prd.parts_nickname = '$parts_nickname' AND prd.loan_data_created >= '$start_date_timestamp' AND prd.loan_data_created <= '$end_date_timestamp' GROUP BY prd.loan_id, prd.parts_id");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_requisition_data_query) > 0){
                        while($row = mysqli_fetch_assoc($con_requisition_data_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for = 'HRM';
                            elseif($required_for == 6)
                                $required_for = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for = 'General';

                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            $parts_qty_txt = $parts_qty . ' ' . $parts_unit;

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'empty_data' => '',
                                'date' => date('Y-m-d', $row['requisition_data_created'])
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_requisition_data_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_requisition_data_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for = 'HRM';
                            elseif($required_for == 6)
                                $required_for = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for = 'General';

                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            $parts_qty_txt = $parts_qty . ' ' . $parts_unit;

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'empty_data' => '',
                                'date' => date('Y-m-d', $row['requisition_data_created'])
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }

                    if(mysqli_num_rows($con_loan_data_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_data_query)){
                            $con_loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (req_qty - SUM(parts_qty)) AS rem_qty FROM rrmsteel_con_loan_data WHERE loan_id = '".$row['loan_id']."' GROUP BY parts_id"));
                            $rem_qty = $con_loan_data_info['rem_qty'];

                            if($rem_qty > 0){
                                $required_for = $row['required_for'];

                                if($required_for == 1)
                                    $required_for = 'BCP-CCM';
                                elseif($required_for == 2)
                                    $required_for = 'BCP-Furnace';
                                elseif($required_for == 3)
                                    $required_for = 'Concast-CCM';
                                elseif($required_for == 4)
                                    $required_for = 'Concast-Furnace';
                                elseif($required_for == 5)
                                    $required_for = 'HRM';
                                elseif($required_for == 6)
                                    $required_for = 'HRM Unit-2';
                                elseif($required_for == 7)
                                    $required_for = 'Lal Masjid';
                                elseif($required_for == 8)
                                    $required_for = 'Sonargaon';
                                elseif($required_for == 9)
                                    $required_for = 'General';

                                $parts_unit = $row['unit'];

                                if($row['unit'] == 1)
                                    $parts_unit = 'Bag';
                                elseif($row['unit'] == 2)
                                    $parts_unit = 'Box';
                                elseif($row['unit'] == 3)
                                    $parts_unit = 'Box/Pcs';
                                elseif($row['unit'] == 4)
                                    $parts_unit = 'Bun';
                                elseif($row['unit'] == 5)
                                    $parts_unit = 'Bundle';
                                elseif($row['unit'] == 6)
                                    $parts_unit = 'Can';
                                elseif($row['unit'] == 7)
                                    $parts_unit = 'Cartoon';
                                elseif($row['unit'] == 8)
                                    $parts_unit = 'Challan';
                                elseif($row['unit'] == 9)
                                    $parts_unit = 'Coil';
                                elseif($row['unit'] == 10)
                                    $parts_unit = 'Drum';
                                elseif($row['unit'] == 11)
                                    $parts_unit = 'Feet';
                                elseif($row['unit'] == 12)
                                    $parts_unit = 'Gallon';
                                elseif($row['unit'] == 13)
                                    $parts_unit = 'Item';
                                elseif($row['unit'] == 14)
                                    $parts_unit = 'Job';
                                elseif($row['unit'] == 15)
                                    $parts_unit = 'Kg';
                                elseif($row['unit'] == 16)
                                    $parts_unit = 'Kg/Bundle';
                                elseif($row['unit'] == 17)
                                    $parts_unit = 'Kv';
                                elseif($row['unit'] == 18)
                                    $parts_unit = 'Lbs';
                                elseif($row['unit'] == 19)
                                    $parts_unit = 'Ltr';
                                elseif($row['unit'] == 20)
                                    $parts_unit = 'Mtr';
                                elseif($row['unit'] == 21)
                                    $parts_unit = 'Pack';
                                elseif($row['unit'] == 22)
                                    $parts_unit = 'Pack/Pcs';
                                elseif($row['unit'] == 23)
                                    $parts_unit = 'Pair';
                                elseif($row['unit'] == 24)
                                    $parts_unit = 'Pcs';
                                elseif($row['unit'] == 25)
                                    $parts_unit = 'Pound';
                                elseif($row['unit'] == 26)
                                    $parts_unit = 'Qty';
                                elseif($row['unit'] == 27)
                                    $parts_unit = 'Roll';
                                elseif($row['unit'] == 28)
                                    $parts_unit = 'Set';
                                elseif($row['unit'] == 29)
                                    $parts_unit = 'Truck';
                                elseif($row['unit'] == 30)
                                    $parts_unit = 'Unit';
                                elseif($row['unit'] == 31)
                                    $parts_unit = 'Yeard';
                                elseif($row['unit'] == 32)
                                    $parts_unit = '(Unit Unknown)';
                                elseif($row['unit'] == 33)
                                    $parts_unit = 'SFT';
                                elseif($row['unit'] == 34)
                                    $parts_unit = 'RFT';
                                elseif($row['unit'] == 35)
                                    $parts_unit = 'CFT';

                                $parts_qty_txt = $rem_qty . ' ' . $parts_unit;

                                $data3[] = [
                                    'sl' => ++$i,
                                    'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                    'required_for' => $required_for,
                                    'parts_id' => $row['parts_id'],
                                    'parts_name' => $row['parts_name'],
                                    'parts_qty' => $parts_qty_txt,
                                    'empty_data' => '',
                                    'date' => $row['loan_date']
                                ];

                                $flag3 = 1;
                            }
                        }
                    } else{
                        $flag3 = 0;
                    }

                    if(mysqli_num_rows($spr_loan_data_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_data_query)){
                            $spr_loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (req_qty - SUM(parts_qty)) AS rem_qty FROM rrmsteel_spr_loan_data WHERE loan_id = '".$row['loan_id']."' GROUP BY parts_id"));
                            $rem_qty = $spr_loan_data_info['rem_qty'];

                            if($rem_qty > 0){
                                $required_for = $row['required_for'];

                                if($required_for == 1)
                                    $required_for = 'BCP-CCM';
                                elseif($required_for == 2)
                                    $required_for = 'BCP-Furnace';
                                elseif($required_for == 3)
                                    $required_for = 'Concast-CCM';
                                elseif($required_for == 4)
                                    $required_for = 'Concast-Furnace';
                                elseif($required_for == 5)
                                    $required_for = 'HRM';
                                elseif($required_for == 6)
                                    $required_for = 'HRM Unit-2';
                                elseif($required_for == 7)
                                    $required_for = 'Lal Masjid';
                                elseif($required_for == 8)
                                    $required_for = 'Sonargaon';
                                elseif($required_for == 9)
                                    $required_for = 'General';

                                $parts_unit = $row['unit'];

                                if($row['unit'] == 1)
                                    $parts_unit = 'Bag';
                                elseif($row['unit'] == 2)
                                    $parts_unit = 'Box';
                                elseif($row['unit'] == 3)
                                    $parts_unit = 'Box/Pcs';
                                elseif($row['unit'] == 4)
                                    $parts_unit = 'Bun';
                                elseif($row['unit'] == 5)
                                    $parts_unit = 'Bundle';
                                elseif($row['unit'] == 6)
                                    $parts_unit = 'Can';
                                elseif($row['unit'] == 7)
                                    $parts_unit = 'Cartoon';
                                elseif($row['unit'] == 8)
                                    $parts_unit = 'Challan';
                                elseif($row['unit'] == 9)
                                    $parts_unit = 'Coil';
                                elseif($row['unit'] == 10)
                                    $parts_unit = 'Drum';
                                elseif($row['unit'] == 11)
                                    $parts_unit = 'Feet';
                                elseif($row['unit'] == 12)
                                    $parts_unit = 'Gallon';
                                elseif($row['unit'] == 13)
                                    $parts_unit = 'Item';
                                elseif($row['unit'] == 14)
                                    $parts_unit = 'Job';
                                elseif($row['unit'] == 15)
                                    $parts_unit = 'Kg';
                                elseif($row['unit'] == 16)
                                    $parts_unit = 'Kg/Bundle';
                                elseif($row['unit'] == 17)
                                    $parts_unit = 'Kv';
                                elseif($row['unit'] == 18)
                                    $parts_unit = 'Lbs';
                                elseif($row['unit'] == 19)
                                    $parts_unit = 'Ltr';
                                elseif($row['unit'] == 20)
                                    $parts_unit = 'Mtr';
                                elseif($row['unit'] == 21)
                                    $parts_unit = 'Pack';
                                elseif($row['unit'] == 22)
                                    $parts_unit = 'Pack/Pcs';
                                elseif($row['unit'] == 23)
                                    $parts_unit = 'Pair';
                                elseif($row['unit'] == 24)
                                    $parts_unit = 'Pcs';
                                elseif($row['unit'] == 25)
                                    $parts_unit = 'Pound';
                                elseif($row['unit'] == 26)
                                    $parts_unit = 'Qty';
                                elseif($row['unit'] == 27)
                                    $parts_unit = 'Roll';
                                elseif($row['unit'] == 28)
                                    $parts_unit = 'Set';
                                elseif($row['unit'] == 29)
                                    $parts_unit = 'Truck';
                                elseif($row['unit'] == 30)
                                    $parts_unit = 'Unit';
                                elseif($row['unit'] == 31)
                                    $parts_unit = 'Yeard';
                                elseif($row['unit'] == 32)
                                    $parts_unit = '(Unit Unknown)';
                                elseif($row['unit'] == 33)
                                    $parts_unit = 'SFT';
                                elseif($row['unit'] == 34)
                                    $parts_unit = 'RFT';
                                elseif($row['unit'] == 35)
                                    $parts_unit = 'CFT';

                                $parts_qty_txt = $rem_qty . ' ' . $parts_unit;

                                $data4[] = [
                                    'sl' => ++$i,
                                    'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                    'required_for' => $required_for,
                                    'parts_id' => $row['parts_id'],
                                    'parts_name' => $row['parts_name'],
                                    'parts_qty' => $parts_qty_txt,
                                    'empty_data' => '',
                                    'date' => $row['loan_date']
                                ];

                                $flag4 = 1;
                            }
                        }
                    } else{
                        $flag4 = 0;
                    }
                } else{
                    if($date_range == null){
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 1 AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_con_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $con_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id GROUP BY prd.loan_id, prd.parts_id");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 1 AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_spr_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $spr_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id GROUP BY prd.loan_id, prd.parts_id");
                    } else{
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 1 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_con_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $con_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_con_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_con_loan pr ON pr.loan_id = prd.loan_id WHERE prd.loan_data_created >= '$start_date_timestamp' AND prd.loan_data_created <= '$end_date_timestamp' GROUP BY prd.loan_id, prd.parts_id");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 1 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND NOT EXISTS (SELECT pr.requisition_id FROM rrmsteel_spr_loan pr WHERE pr.requisition_id = rd.requisition_id)");

                        $spr_loan_data_query = mysqli_query($this->conn, "SELECT prd.*, p.parts_name, p.unit, pr.requisition_id FROM rrmsteel_spr_loan_data prd INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_spr_loan pr ON pr.loan_id = prd.loan_id WHERE prd.loan_data_created >= '$start_date_timestamp' AND prd.loan_data_created <= '$end_date_timestamp' GROUP BY prd.loan_id, prd.parts_id");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_requisition_data_query) > 0){
                        while($row = mysqli_fetch_assoc($con_requisition_data_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for = 'HRM';
                            elseif($required_for == 6)
                                $required_for = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for = 'General';

                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            $parts_qty_txt = $parts_qty . ' ' . $parts_unit;

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'empty_data' => '',
                                'date' => date('Y-m-d', $row['requisition_data_created'])
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_requisition_data_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_requisition_data_query)){
                            $required_for = $row['required_for'];

                            if($required_for == 1)
                                $required_for = 'BCP-CCM';
                            elseif($required_for == 2)
                                $required_for = 'BCP-Furnace';
                            elseif($required_for == 3)
                                $required_for = 'Concast-CCM';
                            elseif($required_for == 4)
                                $required_for = 'Concast-Furnace';
                            elseif($required_for == 5)
                                $required_for = 'HRM';
                            elseif($required_for == 6)
                                $required_for = 'HRM Unit-2';
                            elseif($required_for == 7)
                                $required_for = 'Lal Masjid';
                            elseif($required_for == 8)
                                $required_for = 'Sonargaon';
                            elseif($required_for == 9)
                                $required_for = 'General';

                            $parts_qty = $row['parts_qty'];
                            $parts_unit = $row['unit'];

                            if($row['unit'] == 1)
                                $parts_unit = 'Bag';
                            elseif($row['unit'] == 2)
                                $parts_unit = 'Box';
                            elseif($row['unit'] == 3)
                                $parts_unit = 'Box/Pcs';
                            elseif($row['unit'] == 4)
                                $parts_unit = 'Bun';
                            elseif($row['unit'] == 5)
                                $parts_unit = 'Bundle';
                            elseif($row['unit'] == 6)
                                $parts_unit = 'Can';
                            elseif($row['unit'] == 7)
                                $parts_unit = 'Cartoon';
                            elseif($row['unit'] == 8)
                                $parts_unit = 'Challan';
                            elseif($row['unit'] == 9)
                                $parts_unit = 'Coil';
                            elseif($row['unit'] == 10)
                                $parts_unit = 'Drum';
                            elseif($row['unit'] == 11)
                                $parts_unit = 'Feet';
                            elseif($row['unit'] == 12)
                                $parts_unit = 'Gallon';
                            elseif($row['unit'] == 13)
                                $parts_unit = 'Item';
                            elseif($row['unit'] == 14)
                                $parts_unit = 'Job';
                            elseif($row['unit'] == 15)
                                $parts_unit = 'Kg';
                            elseif($row['unit'] == 16)
                                $parts_unit = 'Kg/Bundle';
                            elseif($row['unit'] == 17)
                                $parts_unit = 'Kv';
                            elseif($row['unit'] == 18)
                                $parts_unit = 'Lbs';
                            elseif($row['unit'] == 19)
                                $parts_unit = 'Ltr';
                            elseif($row['unit'] == 20)
                                $parts_unit = 'Mtr';
                            elseif($row['unit'] == 21)
                                $parts_unit = 'Pack';
                            elseif($row['unit'] == 22)
                                $parts_unit = 'Pack/Pcs';
                            elseif($row['unit'] == 23)
                                $parts_unit = 'Pair';
                            elseif($row['unit'] == 24)
                                $parts_unit = 'Pcs';
                            elseif($row['unit'] == 25)
                                $parts_unit = 'Pound';
                            elseif($row['unit'] == 26)
                                $parts_unit = 'Qty';
                            elseif($row['unit'] == 27)
                                $parts_unit = 'Roll';
                            elseif($row['unit'] == 28)
                                $parts_unit = 'Set';
                            elseif($row['unit'] == 29)
                                $parts_unit = 'Truck';
                            elseif($row['unit'] == 30)
                                $parts_unit = 'Unit';
                            elseif($row['unit'] == 31)
                                $parts_unit = 'Yeard';
                            elseif($row['unit'] == 32)
                                $parts_unit = '(Unit Unknown)';
                            elseif($row['unit'] == 33)
                                $parts_unit = 'SFT';
                            elseif($row['unit'] == 34)
                                $parts_unit = 'RFT';
                            elseif($row['unit'] == 35)
                                $parts_unit = 'CFT';

                            $parts_qty_txt = $parts_qty . ' ' . $parts_unit;

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'empty_data' => '',
                                'date' => date('Y-m-d', $row['requisition_data_created'])
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }

                    if(mysqli_num_rows($con_loan_data_query) > 0){
                        while($row = mysqli_fetch_assoc($con_loan_data_query)){
                            $con_loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (req_qty - SUM(parts_qty)) AS rem_qty FROM rrmsteel_con_loan_data WHERE loan_id = '".$row['loan_id']."' GROUP BY parts_id"));
                            $rem_qty = $con_loan_data_info['rem_qty'];

                            if($rem_qty > 0){
                                $required_for = $row['required_for'];

                                if($required_for == 1)
                                    $required_for = 'BCP-CCM';
                                elseif($required_for == 2)
                                    $required_for = 'BCP-Furnace';
                                elseif($required_for == 3)
                                    $required_for = 'Concast-CCM';
                                elseif($required_for == 4)
                                    $required_for = 'Concast-Furnace';
                                elseif($required_for == 5)
                                    $required_for = 'HRM';
                                elseif($required_for == 6)
                                    $required_for = 'HRM Unit-2';
                                elseif($required_for == 7)
                                    $required_for = 'Lal Masjid';
                                elseif($required_for == 8)
                                    $required_for = 'Sonargaon';
                                elseif($required_for == 9)
                                    $required_for = 'General';

                                $parts_unit = $row['unit'];

                                if($row['unit'] == 1)
                                    $parts_unit = 'Bag';
                                elseif($row['unit'] == 2)
                                    $parts_unit = 'Box';
                                elseif($row['unit'] == 3)
                                    $parts_unit = 'Box/Pcs';
                                elseif($row['unit'] == 4)
                                    $parts_unit = 'Bun';
                                elseif($row['unit'] == 5)
                                    $parts_unit = 'Bundle';
                                elseif($row['unit'] == 6)
                                    $parts_unit = 'Can';
                                elseif($row['unit'] == 7)
                                    $parts_unit = 'Cartoon';
                                elseif($row['unit'] == 8)
                                    $parts_unit = 'Challan';
                                elseif($row['unit'] == 9)
                                    $parts_unit = 'Coil';
                                elseif($row['unit'] == 10)
                                    $parts_unit = 'Drum';
                                elseif($row['unit'] == 11)
                                    $parts_unit = 'Feet';
                                elseif($row['unit'] == 12)
                                    $parts_unit = 'Gallon';
                                elseif($row['unit'] == 13)
                                    $parts_unit = 'Item';
                                elseif($row['unit'] == 14)
                                    $parts_unit = 'Job';
                                elseif($row['unit'] == 15)
                                    $parts_unit = 'Kg';
                                elseif($row['unit'] == 16)
                                    $parts_unit = 'Kg/Bundle';
                                elseif($row['unit'] == 17)
                                    $parts_unit = 'Kv';
                                elseif($row['unit'] == 18)
                                    $parts_unit = 'Lbs';
                                elseif($row['unit'] == 19)
                                    $parts_unit = 'Ltr';
                                elseif($row['unit'] == 20)
                                    $parts_unit = 'Mtr';
                                elseif($row['unit'] == 21)
                                    $parts_unit = 'Pack';
                                elseif($row['unit'] == 22)
                                    $parts_unit = 'Pack/Pcs';
                                elseif($row['unit'] == 23)
                                    $parts_unit = 'Pair';
                                elseif($row['unit'] == 24)
                                    $parts_unit = 'Pcs';
                                elseif($row['unit'] == 25)
                                    $parts_unit = 'Pound';
                                elseif($row['unit'] == 26)
                                    $parts_unit = 'Qty';
                                elseif($row['unit'] == 27)
                                    $parts_unit = 'Roll';
                                elseif($row['unit'] == 28)
                                    $parts_unit = 'Set';
                                elseif($row['unit'] == 29)
                                    $parts_unit = 'Truck';
                                elseif($row['unit'] == 30)
                                    $parts_unit = 'Unit';
                                elseif($row['unit'] == 31)
                                    $parts_unit = 'Yeard';
                                elseif($row['unit'] == 32)
                                    $parts_unit = '(Unit Unknown)';
                                elseif($row['unit'] == 33)
                                    $parts_unit = 'SFT';
                                elseif($row['unit'] == 34)
                                    $parts_unit = 'RFT';
                                elseif($row['unit'] == 35)
                                    $parts_unit = 'CFT';

                                $parts_qty_txt = $rem_qty . ' ' . $parts_unit;

                                $data3[] = [
                                    'sl' => ++$i,
                                    'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                    'required_for' => $required_for,
                                    'parts_id' => $row['parts_id'],
                                    'parts_name' => $row['parts_name'],
                                    'parts_qty' => $parts_qty_txt,
                                    'empty_data' => '',
                                    'date' => $row['loan_date']
                                ];

                                $flag3 = 1;
                            }
                        }
                    } else{
                        $flag3 = 0;
                    }

                    if(mysqli_num_rows($spr_loan_data_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_loan_data_query)){
                            $spr_loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (req_qty - SUM(parts_qty)) AS rem_qty FROM rrmsteel_spr_loan_data WHERE loan_id = '".$row['loan_id']."' GROUP BY parts_id"));
                            $rem_qty = $spr_loan_data_info['rem_qty'];

                            if($rem_qty > 0){
                                $required_for = $row['required_for'];

                                if($required_for == 1)
                                    $required_for = 'BCP-CCM';
                                elseif($required_for == 2)
                                    $required_for = 'BCP-Furnace';
                                elseif($required_for == 3)
                                    $required_for = 'Concast-CCM';
                                elseif($required_for == 4)
                                    $required_for = 'Concast-Furnace';
                                elseif($required_for == 5)
                                    $required_for = 'HRM';
                                elseif($required_for == 6)
                                    $required_for = 'HRM Unit-2';
                                elseif($required_for == 7)
                                    $required_for = 'Lal Masjid';
                                elseif($required_for == 8)
                                    $required_for = 'Sonargaon';
                                elseif($required_for == 9)
                                    $required_for = 'General';

                                $parts_unit = $row['unit'];

                                if($row['unit'] == 1)
                                    $parts_unit = 'Bag';
                                elseif($row['unit'] == 2)
                                    $parts_unit = 'Box';
                                elseif($row['unit'] == 3)
                                    $parts_unit = 'Box/Pcs';
                                elseif($row['unit'] == 4)
                                    $parts_unit = 'Bun';
                                elseif($row['unit'] == 5)
                                    $parts_unit = 'Bundle';
                                elseif($row['unit'] == 6)
                                    $parts_unit = 'Can';
                                elseif($row['unit'] == 7)
                                    $parts_unit = 'Cartoon';
                                elseif($row['unit'] == 8)
                                    $parts_unit = 'Challan';
                                elseif($row['unit'] == 9)
                                    $parts_unit = 'Coil';
                                elseif($row['unit'] == 10)
                                    $parts_unit = 'Drum';
                                elseif($row['unit'] == 11)
                                    $parts_unit = 'Feet';
                                elseif($row['unit'] == 12)
                                    $parts_unit = 'Gallon';
                                elseif($row['unit'] == 13)
                                    $parts_unit = 'Item';
                                elseif($row['unit'] == 14)
                                    $parts_unit = 'Job';
                                elseif($row['unit'] == 15)
                                    $parts_unit = 'Kg';
                                elseif($row['unit'] == 16)
                                    $parts_unit = 'Kg/Bundle';
                                elseif($row['unit'] == 17)
                                    $parts_unit = 'Kv';
                                elseif($row['unit'] == 18)
                                    $parts_unit = 'Lbs';
                                elseif($row['unit'] == 19)
                                    $parts_unit = 'Ltr';
                                elseif($row['unit'] == 20)
                                    $parts_unit = 'Mtr';
                                elseif($row['unit'] == 21)
                                    $parts_unit = 'Pack';
                                elseif($row['unit'] == 22)
                                    $parts_unit = 'Pack/Pcs';
                                elseif($row['unit'] == 23)
                                    $parts_unit = 'Pair';
                                elseif($row['unit'] == 24)
                                    $parts_unit = 'Pcs';
                                elseif($row['unit'] == 25)
                                    $parts_unit = 'Pound';
                                elseif($row['unit'] == 26)
                                    $parts_unit = 'Qty';
                                elseif($row['unit'] == 27)
                                    $parts_unit = 'Roll';
                                elseif($row['unit'] == 28)
                                    $parts_unit = 'Set';
                                elseif($row['unit'] == 29)
                                    $parts_unit = 'Truck';
                                elseif($row['unit'] == 30)
                                    $parts_unit = 'Unit';
                                elseif($row['unit'] == 31)
                                    $parts_unit = 'Yeard';
                                elseif($row['unit'] == 32)
                                    $parts_unit = '(Unit Unknown)';
                                elseif($row['unit'] == 33)
                                    $parts_unit = 'SFT';
                                elseif($row['unit'] == 34)
                                    $parts_unit = 'RFT';
                                elseif($row['unit'] == 35)
                                    $parts_unit = 'CFT';

                                $parts_qty_txt = $rem_qty . ' ' . $parts_unit;

                                $data4[] = [
                                    'sl' => ++$i,
                                    'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                    'required_for' => $required_for,
                                    'parts_id' => $row['parts_id'],
                                    'parts_name' => $row['parts_name'],
                                    'parts_qty' => $parts_qty_txt,
                                    'empty_data' => '',
                                    'date' => $row['loan_date']
                                ];

                                $flag4 = 1;
                            }
                        }
                    } else{
                        $flag4 = 0;
                    }
                }

                if($flag = 1 && $flag2 == 1 && $flag3 = 1 && $flag4 == 1){
                    $merged = array_merge($data1, $data2, $data3, $data4);
                } elseif($flag = 1 && $flag2 == 0 && $flag3 = 0 && $flag4 == 0){
                    $merged = $data1;
                } elseif($flag = 0 && $flag2 == 1 && $flag3 = 0 && $flag4 == 0){
                    $merged = $data2;
                } elseif($flag = 0 && $flag2 == 0 && $flag3 = 1 && $flag4 == 0){
                    $merged = $data3;
                } elseif($flag = 0 && $flag2 == 0 && $flag3 = 0 && $flag4 == 1){
                    $merged = $data4;
                } elseif($flag = 1 && $flag2 == 1 && $flag3 = 0 && $flag4 == 0){
                    $merged = array_merge($data1, $data2);
                } elseif($flag = 1 && $flag2 == 0 && $flag3 = 1 && $flag4 == 0){
                    $merged = array_merge($data1, $data3);
                } elseif($flag = 1 && $flag2 == 0 && $flag3 = 0 && $flag4 == 1){
                    $merged = array_merge($data1, $data4);
                } elseif($flag = 0 && $flag2 == 1 && $flag3 = 1 && $flag4 == 0){
                    $merged = array_merge($data2, $data3);
                } elseif($flag = 0 && $flag2 == 0 && $flag3 = 1 && $flag4 == 1){
                    $merged = array_merge($data3, $data4);
                } elseif($flag = 0 && $flag2 == 1 && $flag3 = 0 && $flag4 == 1){
                    $merged = array_merge($data2, $data4);
                } elseif($flag = 1 && $flag2 == 1 && $flag3 = 1 && $flag4 == 0){
                    $merged = array_merge($data1, $data2, $data3);
                } elseif($flag = 1 && $flag2 == 1 && $flag3 = 0 && $flag4 == 1){
                    $merged = array_merge($data1, $data2, $data4);
                } elseif($flag = 0 && $flag2 == 1 && $flag3 = 1 && $flag4 == 1){
                    $merged = array_merge($data2, $data3, $data4);
                } elseif($flag = 1 && $flag2 == 0 && $flag3 = 1 && $flag4 == 1){
                    $merged = array_merge($data1, $data3, $data4);
                } else{
                    $merged = '';
                }
            }

           if($merged){
                $reply = array(
                    'Type' => 'success',
                    'Reply' => $merged
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No Loan data found !'
                )));
            }
        }

        // FETCH PARTS LOAN DATA
        function fetch_parts_loan_data($parts_id, $parts_cat){
            if($parts_cat == 1){
                $spr_loan_data_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_data ln INNER JOIN rrmsteel_parts p ON p.parts_id = ln.parts_id INNER JOIN rrmsteel_party pr ON pr.party_id = ln.party_id WHERE ln.parts_id = '$parts_id' ORDER BY ln.loan_data_created DESC");

                $flag = 0;

                if(mysqli_num_rows($spr_loan_data_query) > 0){
                    $i = 0;

                    while($row = mysqli_fetch_assoc($spr_loan_data_query)){
                        $parts_id = $row['parts_id'];
                        $required_for = $row['required_for'];

                        if($required_for == 1)
                            $required_for_txt = 'BCP-CCM';
                        elseif($required_for == 2)
                            $required_for_txt = 'BCP-Furnace';
                        elseif($required_for == 3)
                            $required_for_txt = 'Concast-CCM';
                        elseif($required_for == 4)
                            $required_for_txt = 'Concast-Furnace';
                        elseif($required_for == 5)
                            $required_for_txt = 'HRM';
                        elseif($required_for == 6)
                            $required_for_txt = 'HRM Unit-2';
                        elseif($required_for == 7)
                            $required_for_txt = 'Lal Masjid';
                        elseif($required_for == 8)
                            $required_for_txt = 'Sonargaon';
                        elseif($required_for == 9)
                            $required_for_txt = 'General';

                        $parts_unit = $row['unit'];

                        if($row['unit'] == 1)
                            $parts_unit = 'Bag';
                        elseif($row['unit'] == 2)
                            $parts_unit = 'Box';
                        elseif($row['unit'] == 3)
                            $parts_unit = 'Box/Pcs';
                        elseif($row['unit'] == 4)
                            $parts_unit = 'Bun';
                        elseif($row['unit'] == 5)
                            $parts_unit = 'Bundle';
                        elseif($row['unit'] == 6)
                            $parts_unit = 'Can';
                        elseif($row['unit'] == 7)
                            $parts_unit = 'Cartoon';
                        elseif($row['unit'] == 8)
                            $parts_unit = 'Challan';
                        elseif($row['unit'] == 9)
                            $parts_unit = 'Coil';
                        elseif($row['unit'] == 10)
                            $parts_unit = 'Drum';
                        elseif($row['unit'] == 11)
                            $parts_unit = 'Feet';
                        elseif($row['unit'] == 12)
                            $parts_unit = 'Gallon';
                        elseif($row['unit'] == 13)
                            $parts_unit = 'Item';
                        elseif($row['unit'] == 14)
                            $parts_unit = 'Job';
                        elseif($row['unit'] == 15)
                            $parts_unit = 'Kg';
                        elseif($row['unit'] == 16)
                            $parts_unit = 'Kg/Bundle';
                        elseif($row['unit'] == 17)
                            $parts_unit = 'Kv';
                        elseif($row['unit'] == 18)
                            $parts_unit = 'Lbs';
                        elseif($row['unit'] == 19)
                            $parts_unit = 'Ltr';
                        elseif($row['unit'] == 20)
                            $parts_unit = 'Mtr';
                        elseif($row['unit'] == 21)
                            $parts_unit = 'Pack';
                        elseif($row['unit'] == 22)
                            $parts_unit = 'Pack/Pcs';
                        elseif($row['unit'] == 23)
                            $parts_unit = 'Pair';
                        elseif($row['unit'] == 24)
                            $parts_unit = 'Pcs';
                        elseif($row['unit'] == 25)
                            $parts_unit = 'Pound';
                        elseif($row['unit'] == 26)
                            $parts_unit = 'Qty';
                        elseif($row['unit'] == 27)
                            $parts_unit = 'Roll';
                        elseif($row['unit'] == 28)
                            $parts_unit = 'Set';
                        elseif($row['unit'] == 29)
                            $parts_unit = 'Truck';
                        elseif($row['unit'] == 30)
                            $parts_unit = 'Unit';
                        elseif($row['unit'] == 31)
                            $parts_unit = 'Yeard';
                        elseif($row['unit'] == 32)
                            $parts_unit = '(Unit Unknown)';
                        elseif($row['unit'] == 33)
                            $parts_unit = 'SFT';
                        elseif($row['unit'] == 34)
                            $parts_unit = 'RFT';
                        elseif($row['unit'] == 35)
                            $parts_unit = 'CFT';

                        $parts_qty = $row['parts_qty'];
                        $repay_qty = $row['repay_qty'];

                        $disabled = ($parts_qty == $repay_qty) ? 'disabled' : '';

                        // ACTION DATE
                        $action_date_txt = '<input type="text" class="form-control loan-repay-date" oninput="loan_repay_date(this)" onchange="action_date(this, '. $parts_id .')" data-id="'.$parts_id.'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" ' . $disabled . '>';

                        // REPAY QTY.
                        $avail_repay_qty = (($parts_qty - $repay_qty) < $parts_qty) ? ($parts_qty - $repay_qty) : $parts_qty;

                        $repay_qty_txt = '<div class="input-group">
                                            <input type="number" class="form-control" placeholder="Insert" oninput="qty_4(this, ' . $avail_repay_qty . ')" ' . $disabled . ' readonly>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">' . $parts_unit . '</div>
                                            </div>
                                        </div>
                                        
                                        <span class="float-left w-100 mt-1 text-primary">Repaid Qty.: <strong>' . $repay_qty . '</strong> ' . $parts_unit . '.</span>';

                        // ACTION
                        $action = '';

                        if($parts_qty != $repay_qty){
                            $party_id = $row['party_id'];
                            $loan_date = $row['loan_date'];
                            $borrow_date = "'" . $loan_date . "'";
                            $req_qty = $row['req_qty'];
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];

                            $action .= '<a title="Repay" href="javascript:void(0)" class="btn btn-xs btn-success disabled" onclick="loan_repay2(' . $parts_id . ', ' . $required_for . ', this, ' . $party_id . ', ' . $borrow_date . ', ' . $req_qty . ', ' . $parts_qty . ', ' . $loan_id . ', ' . $loan_data_id . ');"><i class="mdi mdi-undo"></i></a>';
                        }

                        $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                        $data[] = [
                            'sl' => ++$i,
                            'required_for' => $required_for_txt,
                            'parts_qty' => $parts_qty . ' ' . $parts_unit,
                            'req_qty' => $row['req_qty'] . ' ' . $parts_unit,
                            'action_date' => $action_date_txt,
                            'repay_qty' => $repay_qty_txt,
                            'price' => $parts_price_txt,
                            'party_id' => $party_id,
                            'party_name' => $row['party_name'],
                            'loan_date' => $loan_date,
                            'action' => $action
                        ];
                    }

                    $flag = 1;
                } else{
                    $flag = 0;
                }
            } else{
                $con_loan_data_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_data ln INNER JOIN rrmsteel_parts p ON p.parts_id = ln.parts_id INNER JOIN rrmsteel_party pr ON pr.party_id = ln.party_id WHERE ln.parts_id = '$parts_id' ORDER BY ln.loan_data_created DESC");

                $flag = 0;

                if(mysqli_num_rows($con_loan_data_query) > 0){
                    $i = 0;

                    while($row = mysqli_fetch_assoc($con_loan_data_query)){
                        $parts_id = $row['parts_id'];
                        $required_for = $row['required_for'];

                        if($required_for == 1)
                            $required_for_txt = 'BCP-CCM';
                        elseif($required_for == 2)
                            $required_for_txt = 'BCP-Furnace';
                        elseif($required_for == 3)
                            $required_for_txt = 'Concast-CCM';
                        elseif($required_for == 4)
                            $required_for_txt = 'Concast-Furnace';
                        elseif($required_for == 5)
                            $required_for_txt = 'HRM';
                        elseif($required_for == 6)
                            $required_for_txt = 'HRM Unit-2';
                        elseif($required_for == 7)
                            $required_for_txt = 'Lal Masjid';
                        elseif($required_for == 8)
                            $required_for_txt = 'Sonargaon';
                        elseif($required_for == 9)
                            $required_for_txt = 'General';

                        $parts_unit = $row['unit'];

                        if($row['unit'] == 1)
                            $parts_unit = 'Bag';
                        elseif($row['unit'] == 2)
                            $parts_unit = 'Box';
                        elseif($row['unit'] == 3)
                            $parts_unit = 'Box/Pcs';
                        elseif($row['unit'] == 4)
                            $parts_unit = 'Bun';
                        elseif($row['unit'] == 5)
                            $parts_unit = 'Bundle';
                        elseif($row['unit'] == 6)
                            $parts_unit = 'Can';
                        elseif($row['unit'] == 7)
                            $parts_unit = 'Cartoon';
                        elseif($row['unit'] == 8)
                            $parts_unit = 'Challan';
                        elseif($row['unit'] == 9)
                            $parts_unit = 'Coil';
                        elseif($row['unit'] == 10)
                            $parts_unit = 'Drum';
                        elseif($row['unit'] == 11)
                            $parts_unit = 'Feet';
                        elseif($row['unit'] == 12)
                            $parts_unit = 'Gallon';
                        elseif($row['unit'] == 13)
                            $parts_unit = 'Item';
                        elseif($row['unit'] == 14)
                            $parts_unit = 'Job';
                        elseif($row['unit'] == 15)
                            $parts_unit = 'Kg';
                        elseif($row['unit'] == 16)
                            $parts_unit = 'Kg/Bundle';
                        elseif($row['unit'] == 17)
                            $parts_unit = 'Kv';
                        elseif($row['unit'] == 18)
                            $parts_unit = 'Lbs';
                        elseif($row['unit'] == 19)
                            $parts_unit = 'Ltr';
                        elseif($row['unit'] == 20)
                            $parts_unit = 'Mtr';
                        elseif($row['unit'] == 21)
                            $parts_unit = 'Pack';
                        elseif($row['unit'] == 22)
                            $parts_unit = 'Pack/Pcs';
                        elseif($row['unit'] == 23)
                            $parts_unit = 'Pair';
                        elseif($row['unit'] == 24)
                            $parts_unit = 'Pcs';
                        elseif($row['unit'] == 25)
                            $parts_unit = 'Pound';
                        elseif($row['unit'] == 26)
                            $parts_unit = 'Qty';
                        elseif($row['unit'] == 27)
                            $parts_unit = 'Roll';
                        elseif($row['unit'] == 28)
                            $parts_unit = 'Set';
                        elseif($row['unit'] == 29)
                            $parts_unit = 'Truck';
                        elseif($row['unit'] == 30)
                            $parts_unit = 'Unit';
                        elseif($row['unit'] == 31)
                            $parts_unit = 'Yeard';
                        elseif($row['unit'] == 32)
                            $parts_unit = '(Unit Unknown)';
                        elseif($row['unit'] == 33)
                            $parts_unit = 'SFT';
                        elseif($row['unit'] == 34)
                            $parts_unit = 'RFT';
                        elseif($row['unit'] == 35)
                            $parts_unit = 'CFT';

                        $parts_qty = $row['parts_qty'];
                        $repay_qty = $row['repay_qty'];

                        $disabled = ($parts_qty == $repay_qty) ? 'disabled' : '';

                        // ACTION DATE
                        $action_date_txt = '<input type="text" class="form-control loan-repay-date" oninput="loan_repay_date(this)" onchange="action_date(this, '. $parts_id .')" data-id="'.$parts_id.'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" ' . $disabled . '>';

                        // REPAY QTY.
                        $avail_repay_qty = (($parts_qty - $repay_qty) < $parts_qty) ? ($parts_qty - $repay_qty) : $parts_qty;

                        $repay_qty_txt = '<div class="input-group">
                                            <input type="number" class="form-control" placeholder="Insert" oninput="qty_4(this, ' . $avail_repay_qty . ')" ' . $disabled . ' readonly>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">' . $parts_unit . '</div>
                                            </div>
                                        </div>
                                        
                                        <span class="float-left w-100 mt-1 text-primary">Repaid Qty.: <strong>' . $repay_qty . '</strong> ' . $parts_unit . '.</span>';

                        // ACTION
                        $action = '';

                        if($parts_qty != $repay_qty){
                            $party_id = $row['party_id'];
                            $loan_date = $row['loan_date'];
                            $borrow_date = "'" . $loan_date . "'";
                            $req_qty = $row['req_qty'];
                            $loan_id = $row['loan_id'];
                            $loan_data_id = $row['loan_data_id'];
                            
                            $action .= '<a title="Repay" href="javascript:void(0)" class="btn btn-xs btn-success disabled" onclick="loan_repay(' . $parts_id . ', ' . $required_for . ', this, ' . $party_id . ', ' . $borrow_date . ', ' . $req_qty . ', ' . $parts_qty . ', ' . $loan_id . ', ' . $loan_data_id . ');"><i class="mdi mdi-undo"></i></a>';
                        }

                        $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                        $data[] = [
                            'sl' => ++$i,
                            'required_for' => $required_for_txt,
                            'parts_qty' => $parts_qty . ' ' . $parts_unit,
                            'req_qty' => $row['req_qty'] . ' ' . $parts_unit,
                            'action_date' => $action_date_txt,
                            'repay_qty' => $repay_qty_txt,
                            'price' => $parts_price_txt,
                            'party_id' => $party_id,
                            'party_name' => $row['party_name'],
                            'loan_date' => $loan_date,
                            'action' => $action
                        ];
                    }

                    $flag = 1;
                } else{
                    $flag = 0;
                }
            }

            if($flag == 0){
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No loan repay data found !'
                )));
            } else{
                $reply = array(
                    'Type' => 'success',
                    'Reply' => ($flag == 1) ? $data : ''
                );

                exit(json_encode($reply));
            }
        }

        // FETCH PARTS LOAN REPAY HISTORY DATA
        function fetch_parts_loan_repay_history_data($parts_id, $parts_cat){
            if($parts_cat == 1){
                $spr_loan_repay_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_loan_repay ln INNER JOIN rrmsteel_parts p ON p.parts_id = ln.parts_id INNER JOIN rrmsteel_party pr ON pr.party_id = ln.party_id WHERE ln.parts_id = '$parts_id' ORDER BY ln.loan_repay_data_created DESC");

                $flag = 0;

                if(mysqli_num_rows($spr_loan_repay_query) > 0){
                    $i = 0;

                    while($row = mysqli_fetch_assoc($spr_loan_repay_query)){
                        $parts_unit = $row['unit'];

                        if($row['unit'] == 1)
                            $parts_unit = 'Bag';
                        elseif($row['unit'] == 2)
                            $parts_unit = 'Box';
                        elseif($row['unit'] == 3)
                            $parts_unit = 'Box/Pcs';
                        elseif($row['unit'] == 4)
                            $parts_unit = 'Bun';
                        elseif($row['unit'] == 5)
                            $parts_unit = 'Bundle';
                        elseif($row['unit'] == 6)
                            $parts_unit = 'Can';
                        elseif($row['unit'] == 7)
                            $parts_unit = 'Cartoon';
                        elseif($row['unit'] == 8)
                            $parts_unit = 'Challan';
                        elseif($row['unit'] == 9)
                            $parts_unit = 'Coil';
                        elseif($row['unit'] == 10)
                            $parts_unit = 'Drum';
                        elseif($row['unit'] == 11)
                            $parts_unit = 'Feet';
                        elseif($row['unit'] == 12)
                            $parts_unit = 'Gallon';
                        elseif($row['unit'] == 13)
                            $parts_unit = 'Item';
                        elseif($row['unit'] == 14)
                            $parts_unit = 'Job';
                        elseif($row['unit'] == 15)
                            $parts_unit = 'Kg';
                        elseif($row['unit'] == 16)
                            $parts_unit = 'Kg/Bundle';
                        elseif($row['unit'] == 17)
                            $parts_unit = 'Kv';
                        elseif($row['unit'] == 18)
                            $parts_unit = 'Lbs';
                        elseif($row['unit'] == 19)
                            $parts_unit = 'Ltr';
                        elseif($row['unit'] == 20)
                            $parts_unit = 'Mtr';
                        elseif($row['unit'] == 21)
                            $parts_unit = 'Pack';
                        elseif($row['unit'] == 22)
                            $parts_unit = 'Pack/Pcs';
                        elseif($row['unit'] == 23)
                            $parts_unit = 'Pair';
                        elseif($row['unit'] == 24)
                            $parts_unit = 'Pcs';
                        elseif($row['unit'] == 25)
                            $parts_unit = 'Pound';
                        elseif($row['unit'] == 26)
                            $parts_unit = 'Qty';
                        elseif($row['unit'] == 27)
                            $parts_unit = 'Roll';
                        elseif($row['unit'] == 28)
                            $parts_unit = 'Set';
                        elseif($row['unit'] == 29)
                            $parts_unit = 'Truck';
                        elseif($row['unit'] == 30)
                            $parts_unit = 'Unit';
                        elseif($row['unit'] == 31)
                            $parts_unit = 'Yeard';
                        elseif($row['unit'] == 32)
                            $parts_unit = '(Unit Unknown)';
                        elseif($row['unit'] == 33)
                            $parts_unit = 'SFT';
                        elseif($row['unit'] == 34)
                            $parts_unit = 'RFT';
                        elseif($row['unit'] == 35)
                            $parts_unit = 'CFT';

                        $data[] = [
                            'sl' => ++$i,
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'repaid_qty' => $row['repaid_qty'],
                            'repay_date' => $row['repay_date'],
                            'party_id' => $row['party_id'],
                            'party_name' => $row['party_name']
                        ];
                    }

                    $flag = 1;
                } else{
                    $flag = 0;
                }
            } else{
                $con_loan_repay_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_loan_repay ln INNER JOIN rrmsteel_parts p ON p.parts_id = ln.parts_id INNER JOIN rrmsteel_party pr ON pr.party_id = ln.party_id WHERE ln.parts_id = '$parts_id' ORDER BY ln.loan_repay_data_created DESC");

                $flag = 0;

                if(mysqli_num_rows($con_loan_repay_query) > 0){
                    $i = 0;

                    while($row = mysqli_fetch_assoc($con_loan_repay_query)){
                        $parts_unit = $row['unit'];

                        if($row['unit'] == 1)
                            $parts_unit = 'Bag';
                        elseif($row['unit'] == 2)
                            $parts_unit = 'Box';
                        elseif($row['unit'] == 3)
                            $parts_unit = 'Box/Pcs';
                        elseif($row['unit'] == 4)
                            $parts_unit = 'Bun';
                        elseif($row['unit'] == 5)
                            $parts_unit = 'Bundle';
                        elseif($row['unit'] == 6)
                            $parts_unit = 'Can';
                        elseif($row['unit'] == 7)
                            $parts_unit = 'Cartoon';
                        elseif($row['unit'] == 8)
                            $parts_unit = 'Challan';
                        elseif($row['unit'] == 9)
                            $parts_unit = 'Coil';
                        elseif($row['unit'] == 10)
                            $parts_unit = 'Drum';
                        elseif($row['unit'] == 11)
                            $parts_unit = 'Feet';
                        elseif($row['unit'] == 12)
                            $parts_unit = 'Gallon';
                        elseif($row['unit'] == 13)
                            $parts_unit = 'Item';
                        elseif($row['unit'] == 14)
                            $parts_unit = 'Job';
                        elseif($row['unit'] == 15)
                            $parts_unit = 'Kg';
                        elseif($row['unit'] == 16)
                            $parts_unit = 'Kg/Bundle';
                        elseif($row['unit'] == 17)
                            $parts_unit = 'Kv';
                        elseif($row['unit'] == 18)
                            $parts_unit = 'Lbs';
                        elseif($row['unit'] == 19)
                            $parts_unit = 'Ltr';
                        elseif($row['unit'] == 20)
                            $parts_unit = 'Mtr';
                        elseif($row['unit'] == 21)
                            $parts_unit = 'Pack';
                        elseif($row['unit'] == 22)
                            $parts_unit = 'Pack/Pcs';
                        elseif($row['unit'] == 23)
                            $parts_unit = 'Pair';
                        elseif($row['unit'] == 24)
                            $parts_unit = 'Pcs';
                        elseif($row['unit'] == 25)
                            $parts_unit = 'Pound';
                        elseif($row['unit'] == 26)
                            $parts_unit = 'Qty';
                        elseif($row['unit'] == 27)
                            $parts_unit = 'Roll';
                        elseif($row['unit'] == 28)
                            $parts_unit = 'Set';
                        elseif($row['unit'] == 29)
                            $parts_unit = 'Truck';
                        elseif($row['unit'] == 30)
                            $parts_unit = 'Unit';
                        elseif($row['unit'] == 31)
                            $parts_unit = 'Yeard';
                        elseif($row['unit'] == 32)
                            $parts_unit = '(Unit Unknown)';
                        elseif($row['unit'] == 33)
                            $parts_unit = 'SFT';
                        elseif($row['unit'] == 34)
                            $parts_unit = 'RFT';
                        elseif($row['unit'] == 35)
                            $parts_unit = 'CFT';

                        $data[] = [
                            'sl' => ++$i,
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'repaid_qty' => $row['repaid_qty'],
                            'repay_date' => $row['repay_date'],
                            'party_id' => $row['party_id'],
                            'party_name' => $row['party_name']
                        ];
                    }

                    $flag = 1;
                } else{
                    $flag = 0;
                }
            }
            
            if($flag == 0){
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No loan repay history data found !'
                )));
            } else{
                $reply = array(
                    'Type' => 'success',
                    'Reply' => ($flag == 1) ? $data : ''
                );

                exit(json_encode($reply));
            }
        }
    }
?>