<?php
    namespace Purchase;

    class Purchase{
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

            // CONSUMABLE PURCHASE
            $con_requisition_query = mysqli_query($this->conn, "SELECT *, u1.user_fullname AS requisitioned_by, u2.user_fullname AS accepted_by FROM rrmsteel_con_requisition r INNER JOIN rrmsteel_user u1 ON u1.user_id = r.requisition_by INNER JOIN rrmsteel_user u2 ON u2.user_id = r.p_approved_by WHERE r.p_approval_status = 1 ORDER BY r.requisition_id DESC");
            
            if(mysqli_num_rows($con_requisition_query) > 0){
                $i = 0;

                while($row = mysqli_fetch_assoc($con_requisition_query)){
                    $requisition_id = $row['requisition_id'];

                    $con_requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(*) AS tot_rec FROM rrmsteel_con_requisition_data WHERE requisition_id = '$requisition_id' AND loan = 0"));

                    if($con_requisition_data_info['tot_rec'] > 0){
                        $requisition_created = $row['requisition_created'];
                        $approval_status = $row['approval_status'];
                        $p_approval_status = $row['p_approval_status'];

                        $reference = 'RRM\CONSUMABLE-REQUISITION\\' . date('Y', $requisition_created) . '-' . $requisition_id;

                        // STATUS
                        $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_purchase WHERE requisition_id = '$requisition_id' LIMIT 1"));

                        if(isset($purchase_info)){
                            $requisitioned_parts = $purchase_info['requisitioned_parts'];
                            $purchased_parts = $purchase_info['purchased_parts'];

                            if($purchased_parts >= $requisitioned_parts){
                                $con_purchase_status = 1;

                                $purchase_status = '<span class="text-success font-weight-bold">Purchased</span>';
                            } else{
                                $con_purchase_status = 0;

                                $purchase_status = '<div class="progress mb-2 progress-sm w-100">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: '.(($purchased_parts / $requisitioned_parts) * 100).'%;" aria-valuenow="'.(($purchased_parts / $requisitioned_parts) * 100).'" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>';

                                $purchase_status .= '<span class="text-warning font-weight-bold">' . ($requisitioned_parts - $purchased_parts) . ' of ' . $requisitioned_parts . ' requisitioned parts are pending</span>';
                            }
                        } else{
                            $con_purchase_status = 0;
                            $purchase_status = '<span class="text-warning font-weight-bold">Pending</span>';
                        }

                        // ACTION
                        $con_purchase_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_purchase WHERE requisition_id = '$requisition_id'");

                        $con_purchase_status = 0;

                        if(mysqli_num_rows($con_purchase_query) > 0){
                            $purchase_info = mysqli_fetch_assoc($con_purchase_query);

                            $purchase_id = $purchase_info['purchase_id'];
                            $requisitioned_parts = $purchase_info['requisitioned_parts'];
                            $purchased_parts = $purchase_info['purchased_parts'];

                            if($requisitioned_parts == $purchased_parts)
                                $con_purchase_status = 1;
                        }

                        $action = '';

                        if((($user_category == 1 && $approval_status == 1) || ($user_category == 3 && $p_approval_status == 1) || ($user_category == 4 && $p_approval_status == 1)) && (mysqli_num_rows($con_purchase_query) == 0 || $con_purchase_status == 0)){
                            if(isset($purchase_id)){
                                $action .= '<a title="Mark as Purchased" href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="mark_purchase('. $purchase_id .')"><i class="mdi mdi-check"></i></a>';
                            }

                            $action .= ' <a title="Purchase" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".full-width-modal" data-id="'.$requisition_id.'" onclick="purchase_con('. $requisition_id .')"><i class="mdi mdi-cart"></i></a>';
                        }

                        if(mysqli_num_rows($con_purchase_query) > 0){
                            $action .= ' <a title="View" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-target=".full-width-modal-2" data-id="'.$requisition_id.'" onclick="view_purchase_con('. $requisition_id .', this)"><i class="mdi mdi-eye"></i></a>';
                    
                            $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill WHERE purchase_id = '$purchase_id'");

                            if(mysqli_num_rows($con_bill_query) > 0){
                                $action .= ' <a title="Generate Bill" href="javascript:void(0)" class="btn btn-xs btn-secondary generate-bill" data-toggle="modal" data-id="'.$purchase_id.'" data-target=".full-width-modal-4" onclick="generate_bill('. $purchase_id .', 1)"><i class="mdi mdi-format-line-weight"></i></a>';
                            }
                        }

                        $data[] = [
                            'sl' => ++$i,
                            'reference' => $reference,
                            'requisitioned_by' => $row['requisitioned_by'],
                            'accepted_by' => $row['accepted_by'],
                            'requisition_created' => date('d M, Y', $requisition_created),
                            'purchase_status' => $purchase_status,
                            'action' => $action
                        ];

                        $flag = 1;
                    }
                }
            } else{
                $flag = 0;
            }

            // SPARE PURCHASE
            $spr_requisition_query = mysqli_query($this->conn, "SELECT *, u1.user_fullname AS requisitioned_by, u2.user_fullname AS accepted_by FROM rrmsteel_spr_requisition r INNER JOIN rrmsteel_user u1 ON u1.user_id = r.requisition_by INNER JOIN rrmsteel_user u2 ON u2.user_id = r.p_approved_by WHERE r.p_approval_status = 1 ORDER BY r.requisition_id DESC");

            if(mysqli_num_rows($spr_requisition_query) > 0){
                $j = 0;

                while($row = mysqli_fetch_assoc($spr_requisition_query)){
                    $requisition_id = $row['requisition_id'];

                    $spr_requisition_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT COUNT(*) AS tot_rec FROM rrmsteel_spr_requisition_data WHERE requisition_id = '$requisition_id' AND loan = 0"));

                    if($spr_requisition_data_info['tot_rec'] > 0){
                        $requisition_created = $row['requisition_created'];
                        $approval_status = $row['approval_status'];
                        $p_approval_status = $row['p_approval_status'];

                        $reference = 'RRM\SPARE-REQUISITION\\' . date('Y', $requisition_created) . '-' . $requisition_id;

                        // STATUS
                        $purchase_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_purchase WHERE requisition_id = '$requisition_id' LIMIT 1"));

                        if(isset($purchase_info)){
                            $requisitioned_parts = $purchase_info['requisitioned_parts'];
                            $purchased_parts = $purchase_info['purchased_parts'];

                            if($purchased_parts >= $requisitioned_parts){
                                $spr_purchase_status = 1;

                                $purchase_status = '<span class="text-success font-weight-bold">Purchased</span>';
                            } else{
                                $spr_purchase_status = 0;

                                $purchase_status = '<div class="progress mb-2 progress-sm w-100">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: '.(($purchased_parts / $requisitioned_parts) * 100).'%;" aria-valuenow="'.(($purchased_parts / $requisitioned_parts) * 100).'" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>';

                                $purchase_status .= '<span class="text-warning font-weight-bold">' . ($requisitioned_parts - $purchased_parts) . ' of ' . $requisitioned_parts . ' requisitioned parts are pending</span>';
                            }
                        } else{
                            $spr_purchase_status = 0;
                            $purchase_status = '<span class="text-warning font-weight-bold">Pending</span>';
                        }

                        // ACTION
                        $spr_purchase_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_purchase WHERE requisition_id = '$requisition_id'");

                        $spr_purchase_status = 0;

                        if(mysqli_num_rows($spr_purchase_query) > 0){
                            $purchase_info = mysqli_fetch_assoc($spr_purchase_query);

                            $purchase_id = $purchase_info['purchase_id'];
                            $requisitioned_parts = $purchase_info['requisitioned_parts'];
                            $purchased_parts = $purchase_info['purchased_parts'];

                            if($requisitioned_parts == $purchased_parts)
                                $spr_purchase_status = 1;
                        }

                        $action = '';

                        if((($user_category == 1 && $approval_status == 1) || ($user_category == 3 && $p_approval_status == 1) || ($user_category == 4 && $p_approval_status == 1)) && (mysqli_num_rows($spr_purchase_query) == 0 || $spr_purchase_status == 0)){
                            if(isset($purchase_id)){
                                $action .= '<a title="Mark as Purchased" href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="mark_purchase('. $purchase_id .')"><i class="mdi mdi-check"></i></a>';
                            }

                            $action .= ' <a title="Purchase" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".full-width-modal" data-id="'.$requisition_id.'" onclick="purchase_spr('. $requisition_id .')"><i class="mdi mdi-cart"></i></a>';
                        }

                        if(mysqli_num_rows($spr_purchase_query) > 0){
                            $action .= ' <a title="View" href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-target=".full-width-modal-2" data-id="'.$requisition_id.'" onclick="view_purchase_spr('. $requisition_id .', this)"><i class="mdi mdi-eye"></i></a>';
                    
                            $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill WHERE purchase_id = '$purchase_id'");

                            if(mysqli_num_rows($spr_bill_query) > 0){
                                $action .= ' <a title="Generate Bill" href="javascript:void(0)" class="btn btn-xs btn-secondary generate-bill" data-toggle="modal" data-id="'.$purchase_id.'" data-target=".full-width-modal-4" onclick="generate_bill('. $purchase_id .', 1)"><i class="mdi mdi-format-line-weight"></i></a>';
                            }
                        }

                        $data2[] = [
                            'sl' => ++$j,
                            'reference' => $reference,
                            'requisitioned_by' => $row['requisitioned_by'],
                            'accepted_by' => $row['accepted_by'],
                            'requisition_created' => date('d M, Y', $requisition_created),
                            'purchase_status' => $purchase_status,
                            'action' => $action
                        ];

                        $flag2 = 1;
                    }
                }
            } else{
                $flag2 = 0;
            }

            // FILTERED PURCHASE
            $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

            $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

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

            if($flag3 = 1 && $flag4 == 1){
                $merged = array_merge($data3, $data4);
            } elseif($flag3 = 1 && $flag4 == 0){
                $merged = $data3;
            } elseif($flag3 = 0 && $flag4 == 1){
                $merged = $data4;
            } else{
                $merged = '';
            }

            if($flag == 0 && $flag2 == 0 && !$merged){
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No purchase data found !'
                )));
            } else{
                $reply = array(
                    'Type' => 'success',
                    'Reply' => ($flag == 1) ? $data : '',
                    'Reply2' => ($flag2 == 1) ? $data2 : '',
                    'Reply3' => $merged ? $merged : ''
                );

                exit(json_encode($reply));
            }
        }

        // FETCH A CONSUMABLE REQUISITION
        function fetch_requisition_con($requisition_id){
            $requisition_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_requisition WHERE requisition_id = '$requisition_id'");

            if(mysqli_num_rows($requisition_query) > 0){
                $requisition_info = mysqli_fetch_assoc($requisition_query);

                $requisition_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks, r.parts_qty AS r_qty, i.parts_qty AS i_qty, i.parts_rate AS price FROM rrmsteel_con_requisition_data r INNER JOIN rrmsteel_parts p ON p.parts_id = r.parts_id INNER JOIN rrmsteel_inv_summary i ON i.parts_id = r.parts_id WHERE r.requisition_id = '$requisition_id' AND r.loan = 0");

                if(mysqli_num_rows($requisition_data_query) > 0){
                    while($row = mysqli_fetch_assoc($requisition_data_query)){
                        $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT rd.parts_id AS purchased_parts_id, SUM(rd.parts_qty) AS purchased_parts_qty FROM rrmsteel_con_purchase_data rd INNER JOIN rrmsteel_con_purchase r ON r.purchase_id = rd.purchase_id WHERE r.requisition_id = '$requisition_id' AND rd.parts_id = '".$row['parts_id']."' LIMIT 1"));

                        if(isset($purchase_data_info)){
                            $purchased_parts_id = $purchase_data_info['purchased_parts_id'] == null ? 0 : $purchase_data_info['purchased_parts_id'];
                            $purchased_parts_qty = $purchase_data_info['purchased_parts_qty'] == null ? 0 : $purchase_data_info['purchased_parts_qty'];
                        } else{
                            $purchased_parts_id = 0;
                            $purchased_parts_qty = 0;
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
                            'purchased_parts_id' => $purchased_parts_id,
                            'purchased_parts_qty' => $purchased_parts_qty
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
                        'Reply' => 'No purchase data found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No purchase data found !'
                )));
            }
        }

        // FETCH A SPARE REQUISITION
        function fetch_requisition_spr($requisition_id){
            $requisition_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_requisition WHERE requisition_id = '$requisition_id'");

            if(mysqli_num_rows($requisition_query) > 0){
                $requisition_info = mysqli_fetch_assoc($requisition_query);

                $requisition_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks, r.parts_qty AS r_qty, i.parts_qty AS i_qty, i.parts_rate AS price FROM rrmsteel_spr_requisition_data r INNER JOIN rrmsteel_parts p ON p.parts_id = r.parts_id INNER JOIN rrmsteel_inv_summary i ON i.parts_id = r.parts_id WHERE r.requisition_id = '$requisition_id' AND r.loan = 0");

                if(mysqli_num_rows($requisition_data_query) > 0){
                    while($row = mysqli_fetch_assoc($requisition_data_query)){
                        $purchase_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT rd.parts_id AS purchased_parts_id, SUM(rd.parts_qty) AS purchased_parts_qty FROM rrmsteel_spr_purchase_data rd INNER JOIN rrmsteel_spr_purchase r ON r.purchase_id = rd.purchase_id WHERE r.requisition_id = '$requisition_id' AND rd.parts_id = '".$row['parts_id']."' LIMIT 1"));

                        if(isset($purchase_data_info)){
                            $purchased_parts_id = $purchase_data_info['purchased_parts_id'] == null ? 0 : $purchase_data_info['purchased_parts_id'];
                            $purchased_parts_qty = $purchase_data_info['purchased_parts_qty'] == null ? 0 : $purchase_data_info['purchased_parts_qty'];
                        } else{
                            $purchased_parts_id = 0;
                            $purchased_parts_qty = 0;
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
                            'purchased_parts_id' => $purchased_parts_id,
                            'purchased_parts_qty' => $purchased_parts_qty
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
                        'Reply' => 'No purchase data found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No purchase data found !'
                )));
            }
        }

        // FETCH A CONSUMABLE PURCHASE
        function fetch_purchase_con($requisition_id, $party_id = null){
            $purchase_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_purchase WHERE requisition_id = '$requisition_id' LIMIT 1");

            if(mysqli_num_rows($purchase_query) > 0){
                $purchase_info = mysqli_fetch_assoc($purchase_query);
                $purchase_id = $purchase_info['purchase_id'];

                if(!$party_id || $party_id == 0){
                    $purchase_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks FROM rrmsteel_con_purchase_data r INNER JOIN rrmsteel_parts pr ON pr.parts_id = r.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = r.party_id WHERE purchase_id = '$purchase_id' ORDER BY r.parts_id");
                } else{
                    $purchase_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks FROM rrmsteel_con_purchase_data r INNER JOIN rrmsteel_parts pr ON pr.parts_id = r.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = r.party_id WHERE purchase_id = '$purchase_id' AND r.party_id = '$party_id' ORDER BY r.parts_id");
                }

                $flag = 0;

                while($row = mysqli_fetch_assoc($purchase_data_query)){
                    $parts_id = $row['parts_id'];
                    $flag = 1;

                    $requisition_data_query = mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_con_requisition_data WHERE requisition_id = '$requisition_id' AND parts_id = '$parts_id' AND loan = 0 LIMIT 1");
                    $requisition_info = mysqli_fetch_assoc($requisition_data_query);
                    $requisitioned_parts_qty = $requisition_info['parts_qty'];

                    $purchase_data_query_2 = mysqli_query($this->conn, "SELECT COUNT(*) AS purchase_count, SUM(parts_qty) AS purchased_parts_qty FROM rrmsteel_con_purchase_data r WHERE purchase_id = '$purchase_id' AND parts_id = '$parts_id'");
                    $purchase_data_info = mysqli_fetch_assoc($purchase_data_query_2);
                    $purchased_parts_qty = $purchase_data_info['purchased_parts_qty'];
                    $purchase_count = $purchase_data_info['purchase_count'];

                    $data1[] = [
                        'purchase_id' => $purchase_id,
                        'required_for' => $row['required_for'],
                        'parts_id' => $parts_id,
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'requisitioned_parts_qty' => $requisitioned_parts_qty,
                        'purchased_parts_qty' => $purchased_parts_qty,
                        'purchase_indx_f' => 1,
                        'purchase_indx_l' => (int)$purchase_count,
                        'parts_usage' => $row['parts_usage'],
                        'remarks' => $row['r_remarks']
                    ];

                    $data2[] = [
                        'purchase_data_id' => $row['purchase_data_id'],
                        'parts_id' => $parts_id,
                        'parts_qty' => $row['parts_qty'],
                        'price' => $row['price'],
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'party_remarks' => $row['party_remarks'],
                        'gate_no' => $row['gate_no'],
                        'challan_no' => $row['challan_no'],
                        'challan_photo' => $row['challan_photo'],
                        'bill_photo' => $row['bill_photo'],
                        'purchase_date' => $row['purchase_date']
                    ];

                    $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill WHERE FIND_IN_SET('".$row['purchase_data_id']."', purchase_data_ids)");

                    if(mysqli_num_rows($bill_query) > 0){
                        $data3[] = [
                            'bill_available' => 1
                        ];
                    } else{
                        $data3[] = [
                            'bill_available' => 0
                        ];
                    }
                }

                if($flag == 1){
                    $data1 = array_values(array_unique($data1, SORT_REGULAR));

                    foreach($data1 as $key => $value){
                        if($key == 0){
                            $purchase_indx_f = $value['purchase_indx_f'];
                            $purchase_indx_l = $value['purchase_indx_l'];
                        } else{
                            $purchase_indx_f = $purchase_indx_l + 2;
                            $purchase_indx_l = $purchase_indx_f + $value['purchase_indx_l'] - 1;
                        }

                        $data1[$key]['purchase_indx_f'] = $purchase_indx_f;
                        $data1[$key]['purchase_indx_l'] = $purchase_indx_l;
                    }

                    $reply = array(
                        'Type' => 'success',
                        'Reply1' => $data1,
                        'Reply2' => $data2,
                        'Reply3' => $data3
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No purchase data found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No purchase data found !'
                )));
            }
        }

        // FETCH A SPARE PURCHASE
        function fetch_purchase_spr($requisition_id, $party_id = null){
            $purchase_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_purchase WHERE requisition_id = '$requisition_id' LIMIT 1");

            if(mysqli_num_rows($purchase_query) > 0){
                $purchase_info = mysqli_fetch_assoc($purchase_query);
                $purchase_id = $purchase_info['purchase_id'];

                if(!$party_id || $party_id == 0){
                    $purchase_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks FROM rrmsteel_spr_purchase_data r INNER JOIN rrmsteel_parts pr ON pr.parts_id = r.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = r.party_id WHERE purchase_id = '$purchase_id' ORDER BY r.parts_id");
                } else{
                    $purchase_data_query = mysqli_query($this->conn, "SELECT *, r.remarks AS r_remarks FROM rrmsteel_spr_purchase_data r INNER JOIN rrmsteel_parts pr ON pr.parts_id = r.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = r.party_id WHERE purchase_id = '$purchase_id' AND r.party_id = '$party_id' ORDER BY r.parts_id");
                }

                $flag = 0;

                while($row = mysqli_fetch_assoc($purchase_data_query)){
                    $parts_id = $row['parts_id'];
                    $flag = 1;

                    $requisition_data_query = mysqli_query($this->conn, "SELECT parts_qty FROM rrmsteel_spr_requisition_data WHERE requisition_id = '$requisition_id' AND parts_id = '$parts_id' AND loan = 0 LIMIT 1");
                    $requisition_info = mysqli_fetch_assoc($requisition_data_query);
                    $requisitioned_parts_qty = $requisition_info['parts_qty'];

                    $purchase_data_query_2 = mysqli_query($this->conn, "SELECT COUNT(*) AS purchase_count, SUM(parts_qty) AS purchased_parts_qty FROM rrmsteel_spr_purchase_data r WHERE purchase_id = '$purchase_id' AND parts_id = '$parts_id'");
                    $purchase_data_info = mysqli_fetch_assoc($purchase_data_query_2);
                    $purchased_parts_qty = $purchase_data_info['purchased_parts_qty'];
                    $purchase_count = $purchase_data_info['purchase_count'];

                    $data1[] = [
                        'purchase_id' => $purchase_id,
                        'required_for' => $row['required_for'],
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'requisitioned_parts_qty' => $requisitioned_parts_qty,
                        'purchased_parts_qty' => $purchased_parts_qty,
                        'purchase_indx_f' => 1,
                        'purchase_indx_l' => (int)$purchase_count,
                        'old_spare_details' => $row['old_spare_details'],
                        'status' => (($row['status'] == 1) ? 'Repairable' : 'Unusual'),
                        'remarks' => $row['r_remarks']
                    ];

                    $data2[] = [
                        'purchase_data_id' => $row['purchase_data_id'],
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty'],
                        'price' => $row['price'],
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'party_remarks' => $row['party_remarks'],
                        'gate_no' => $row['gate_no'],
                        'challan_no' => $row['challan_no'],
                        'challan_photo' => $row['challan_photo'],
                        'bill_photo' => $row['bill_photo'],
                        'purchase_date' => $row['purchase_date']
                    ];

                    $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill WHERE FIND_IN_SET('".$row['purchase_data_id']."', purchase_data_ids)");

                    if(mysqli_num_rows($bill_query) > 0){
                        $data3[] = [
                            'bill_available' => 1
                        ];
                    } else{
                        $data3[] = [
                            'bill_available' => 0
                        ];
                    }
                }

                if($flag == 1){
                    $data1 = array_values(array_unique($data1, SORT_REGULAR));

                    foreach($data1 as $key => $value){
                        if($key == 0){
                            $purchase_indx_f = $value['purchase_indx_f'];
                            $purchase_indx_l = $value['purchase_indx_l'];
                        } else{
                            $purchase_indx_f = $purchase_indx_l + 2;
                            $purchase_indx_l = $purchase_indx_f + $value['purchase_indx_l'] - 1;
                        }

                        $data1[$key]['purchase_indx_f'] = $purchase_indx_f;
                        $data1[$key]['purchase_indx_l'] = $purchase_indx_l;
                    }

                    $reply = array(
                        'Type' => 'success',
                        'Reply1' => $data1,
                        'Reply2' => $data2,
                        'Reply3' => $data3,
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No purchase data found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No purchase data found !'
                )));
            }
        }

        // FETCH A CONSUMABLE PURCHASE BILL DATA
        function fetch_purchase_bill_con($purchase_id, $party_id){
            if($party_id){
                $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pr ON pr.parts_id = b.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = b.party_id WHERE b.purchase_id = '$purchase_id' AND b.party_id = '$party_id'");
            } else{
                $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pr ON pr.parts_id = b.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = b.party_id WHERE b.purchase_id = '$purchase_id'");
            }

            if(mysqli_num_rows($bill_query) > 0){
                while($row = mysqli_fetch_assoc($bill_query)){
                    $data[] = [
                        'bill_id' => $row['bill_id'],
                        'purchase_id' => $row['purchase_id'],
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'parts_qty' => $row['qty'],
                        'price' => $row['price'],
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name']
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
                    'Reply' => 'No bill data found !'
                )));
            }
        }

        // FETCH A SPARE PURCHASE BILL DATA
        function fetch_purchase_bill_spr($purchase_id, $party_id){
            if($party_id){
                $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pr ON pr.parts_id = b.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = b.party_id WHERE b.purchase_id = '$purchase_id' AND b.party_id = '$party_id'");
            } else{
                $bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pr ON pr.parts_id = b.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = b.party_id WHERE b.purchase_id = '$purchase_id'");
            }

            if(mysqli_num_rows($bill_query) > 0){
                while($row = mysqli_fetch_assoc($bill_query)){
                    $data[] = [
                        'purchase_id' => $row['purchase_id'],
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'parts_qty' => $row['qty'],
                        'price' => $row['price'],
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name']
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
                    'Reply' => 'No bill data found !'
                )));
            }
        }

        // FETCH FILTERED PURCHASE
        function fetch_filtered_purchase($type, $party_id, $parts_id, $parts_nickname, $date_range = null){
            $data1 = [];
            $data2 = [];

            $flag = 0;
            $flag2 = 0;

            if($date_range){
                $date_range = explode(' to ', $date_range);
                $start_date = $date_range[0];
                $end_date = $date_range[1];
            }

            if($type == 1){
                if(!$party_id && !$parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id");
                    } else{
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($con_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_spr_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && $parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id'");
                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id'");
                    } else{
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($con_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_spr_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && !$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_nickname = '$parts_nickname'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_nickname = '$parts_nickname'");
                    } else{
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_nickname = '$parts_nickname' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_nickname = '$parts_nickname' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($con_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_spr_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id'");
                    } else{
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($con_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_spr_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && $parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id'");
                    } else{
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($con_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_spr_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname'");
                    } else{
                        $con_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_con_purchase_data pr INNER JOIN rrmsteel_con_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");

                        $spr_purchase_query = mysqli_query($this->conn, "SELECT pr.*, p.purchase_id, p.requisition_id, pt.parts_name, pt.unit, py.party_name FROM rrmsteel_spr_purchase_data pr INNER JOIN rrmsteel_spr_purchase p ON p.purchase_id = pr.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = pr.parts_id INNER JOIN rrmsteel_party py ON py.party_id = pr.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname' AND pr.purchase_date >= '$start_date' AND pr.purchase_date <= '$end_date'");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($con_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 1)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_purchase_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_purchase_query)){
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

                            // PURCHASE DATE
                            $purchase_date = $row['purchase_date'];

                            $purchase_date_txt = '<span class="data-span">' . $purchase_date . '</span>';

                            $purchase_date_txt .= '<input type="text" class="form-control data-input d-none" oninput="purchase_date_2(this)" data-id="'. $parts_id .'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'.$purchase_date.'">';

                            // ACTION
                            $purchase_id = $row['purchase_id'];
                            $purchase_data_id = $row['purchase_data_id'];

                            $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this, '. $party_id . ', ' . $purchase_data_id . ', ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ', 2)"><i class="mdi mdi-pencil"></i></a>';

                            $action .= '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';

                            $action .= '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_purchase_data_spr_2(' . $purchase_data_id . ', this, ' . $purchase_id . ', ' . $parts_id . ', ' . $required_for . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                            $action .= '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_purchase_data_con_2(' . $purchase_data_id . ', this, ' . $parts_id . ', ' . $required_for . ', ' . $purchase_id . ')"><i class="mdi mdi-delete"></i></a>';

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_data_id' => $purchase_data_id,
                                'purchase_id' => $purchase_id,
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for_txt,
                                'parts_id' => $parts_id,
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $parts_qty_txt,
                                'price' => $parts_price_txt,
                                'party_name' => $party_name_txt,
                                'gate_no' => $gate_no_txt,
                                'challan_no' => $challan_no_txt,
                                'date' => $purchase_date_txt,
                                'action' => $action
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                }
            } else{
                if($date_range){
                    $start_date_timestamp = strtotime($start_date);
                    $end_date_timestamp = strtotime($end_date) + 86399;
                }

                if(!$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE p.parts_nickname = '$parts_nickname' AND rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE p.parts_nickname = '$parts_nickname' AND rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");
                    } else{
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE p.parts_nickname = '$parts_nickname' AND rd.loan = 0 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE p.parts_nickname = '$parts_nickname' AND rd.loan = 0 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");
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
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
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
                } elseif($parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");
                    } else{
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 0 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.parts_id = '$parts_id' AND rd.loan = 0 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");
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
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
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
                } else{
                    if($date_range == null){
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 0 AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");
                    } else{
                        $con_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_con_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 0 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");

                        $spr_requisition_data_query = mysqli_query($this->conn, "SELECT rd.*, p.parts_name, p.unit FROM rrmsteel_spr_requisition_data rd INNER JOIN rrmsteel_parts p ON p.parts_id = rd.parts_id WHERE rd.loan = 0 AND rd.requisition_data_created >= '$start_date_timestamp' AND rd.requisition_data_created <= '$end_date_timestamp' AND (NOT EXISTS (SELECT prd.parts_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = prd.purchase_id WHERE pr.requisition_id = rd.requisition_id AND prd.parts_id = rd.parts_id AND prd.parts_qty >= rd.parts_qty))");
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
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
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
                } 
            }

            if($flag = 1 && $flag2 == 1){
                $merged = array_merge($data1, $data2);
            } elseif($flag = 1 && $flag2 == 0){
                $merged = $data1;
            } elseif($flag = 0 && $flag2 == 1){
                $merged = $data2;
            } elseif($flag = 0 && $flag2 == 0){
                $merged = '';
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
                    'Reply' => 'No purchase data found !'
                )));
            }
        }

        // FETCH FILTERED PURCHASE
        function fetch_filtered_bill($type, $availability = null, $party_id, $parts_id, $parts_nickname, $date_range = null){
            $data1 = []; $data2 = []; $flag = 1;

            if($date_range){
                $date_range = explode(' to ', $date_range);
                $start_date = $date_range[0];
                $end_date = $date_range[1];
            }

            $flag = 0;
            $flag2 = 0;

            if($type == 1){
                if(!$party_id && !$parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_status = 1");
                    } else{
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\SPARE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && $parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_status = 1");
                    } else{
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\SPARE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && !$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_status = 1");
                    } else{
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\SPARE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND b.generate_status = 1");
                    } else{
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\SPARE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && $parts_id && !$parts_nickname){
                    if($date_range == null){
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id' AND b.generate_status = 1");
                    } else{
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND pr.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\SPARE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && $parts_nickname){
                    if($date_range == null){
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname' AND b.generate_status = 1");
                    } else{
                        $con_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                        $spr_bill_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pr.party_id = '$party_id' AND pt.parts_nickname = '$parts_nickname' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 1");
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\CONSUMABLE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'reference' => 'RRM\\SPARE-PURCHASE\\' . date('Y') . '-' . $row['purchase_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => $row['generate_date']
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                }
            } else{
                if(!$party_id && !$parts_id && !$parts_nickname){
                    if($availability == 1){
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        }
                    } else{
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_status = 0");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        }
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && $parts_id && !$parts_nickname){
                    if($availability == 1){
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        }
                    } else{
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_status = 0");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        }
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif(!$party_id && !$parts_id && $parts_nickname){
                    if($availability == 1){
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        }
                    } else{
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_status = 0");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        }
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && !$parts_nickname){
                    if($availability == 1){
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE pt.party_id = '$party_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE pt.party_id = '$party_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE pt.party_id = '$party_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE pt.party_id = '$party_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        }
                    } else{
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE py.party_id = '$party_id' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE py.party_id = '$party_id' AND b.generate_status = 0");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE py.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE py.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        }
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && $parts_id && !$parts_nickname){
                    if($availability == 1){
                        if(date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND pt.party_id = '$party_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND pt.party_id = '$party_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND pt.party_id = '$party_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_id = '$parts_id' AND pt.party_id = '$party_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        }
                    } else{
                        if(date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND py.party_id = '$party_id' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND py.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND py.party_id = '$party_id' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_id = '$parts_id' AND py.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        }
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
                } elseif($party_id && !$parts_id && $parts_nickname){
                    if($availability == 1){
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND pt.party_id = '$party_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND pt.party_id = '$party_id' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_con_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND pt.party_id = '$party_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_con_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT prd.*, prd.parts_qty AS qty, p.parts_name, p.unit, pt.party_name, pr.requisition_id FROM rrmsteel_spr_purchase_data prd INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = prd.purchase_id INNER JOIN rrmsteel_parts p ON p.parts_id = prd.parts_id INNER JOIN rrmsteel_party pt ON pt.party_id = prd.party_id WHERE p.parts_nickname = '$parts_nickname' AND pt.party_id = '$party_id' AND prd.purchase_date >= '$start_date' AND prd.purchase_date <= '$end_date' AND (NOT EXISTS (SELECT b.parts_id FROM rrmsteel_spr_bill b WHERE b.purchase_id = prd.purchase_id AND b.parts_id = prd.parts_id AND b.qty >= prd.parts_qty))");
                        }
                    } else{
                        if($date_range == null){
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND py.party_id = '$party_id' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND py.party_id = '$party_id' AND b.generate_status = 0");
                        } else{
                            $con_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_con_bill b INNER JOIN rrmsteel_con_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND py.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                            $spr_bill_query = mysqli_query($this->conn, "SELECT b.*, pt.parts_name, pt.unit, py.party_name, pr.requisition_id FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_spr_purchase pr ON pr.purchase_id = b.purchase_id INNER JOIN rrmsteel_parts pt ON pt.parts_id = b.parts_id INNER JOIN rrmsteel_party py ON py.party_id = b.party_id WHERE pt.parts_nickname = '$parts_nickname' AND py.party_id = '$party_id' AND b.generate_date >= '$start_date' AND b.generate_date <= '$end_date' AND b.generate_status = 0");
                        }
                    }

                    $i = 0;

                    if(mysqli_num_rows($con_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($con_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data1[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\CONSUMABLE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag = 1;
                    } else{
                        $flag = 0;
                    }

                    if(mysqli_num_rows($spr_bill_query) > 0){
                        while($row = mysqli_fetch_assoc($spr_bill_query)){
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

                            $parts_price_txt = '<i class="mdi mdi-currency-bdt"></i>' . $row['price'];

                            $data2[] = [
                                'sl' => ++$i,
                                'purchase_id' => $row['purchase_id'],
                                'reference' => 'RRM\\SPARE-REQUISITION\\' . date('Y') . '-' . $row['requisition_id'],
                                'required_for' => $required_for,
                                'parts_id' => $row['parts_id'],
                                'parts_name' => $row['parts_name'],
                                'parts_qty' => $row['qty'] . ' ' . $parts_unit,
                                'price' => $parts_price_txt,
                                'party_id' => $row['party_id'],
                                'party_name' => $row['party_name'],
                                'date' => (isset($row['purchase_date'])) ? $row['purchase_date'] : null,
                                'date2' => (isset($row['generate_date'])) ? $row['generate_date'] : null,
                            ];
                        }

                        $flag2 = 1;
                    } else{
                        $flag2 = 0;
                    }
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

            if($merged){
                $reply = array(
                    'Type' => 'success',
                    'Reply' => $merged
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No purchase data found !'
                )));
            }
        }

        // FETCH TOTAL PURCHASE AGAINST REQUISITION
        function fetch_tot_purchase_against_requisition(){
            // CONSUMABLE
            $con_requisition_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");
            $con_purchase_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE()) AND YEAR(purchase_date) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");

            $data1 = [];
            $data2 = [];

            if(mysqli_num_rows($con_requisition_query) > 0){
                while($row = mysqli_fetch_assoc($con_requisition_query)){
                    $data1[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($con_purchase_query) > 0){
                while($row = mysqli_fetch_assoc($con_purchase_query)){
                    $data2[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data3 = [];
            $data4 = [];

            foreach($data1 as $key => $value){
                array_push($data3, $value['parts_id']);

                foreach($data2 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data4, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $con_requisition_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $con_purchase_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");

            $data11 = [];
            $data22 = [];

            if(mysqli_num_rows($con_requisition_query2) > 0){
                while($row = mysqli_fetch_assoc($con_requisition_query2)){
                    $data11[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($con_purchase_query2) > 0){
                while($row = mysqli_fetch_assoc($con_purchase_query2)){
                    $data22[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data33 = [];
            $data44 = [];

            foreach($data11 as $key => $value){
                array_push($data33, $value['parts_id']);

                foreach($data22 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data44, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $con_requisition_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $con_purchase_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY parts_id ORDER BY parts_id");

            $data111 = [];
            $data222 = [];

            if(mysqli_num_rows($con_requisition_query3) > 0){
                while($row = mysqli_fetch_assoc($con_requisition_query3)){
                    $data111[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($con_purchase_query3) > 0){
                while($row = mysqli_fetch_assoc($con_purchase_query3)){
                    $data222[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data333 = [];
            $data444 = [];

            foreach($data111 as $key => $value){
                array_push($data333, $value['parts_id']);

                foreach($data222 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data444, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            // SPARE
            $spr_requisition_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");
            $spr_purchase_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE()) AND YEAR(purchase_date) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");

            $data5 = [];
            $data6 = [];

            if(mysqli_num_rows($spr_requisition_query) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_query)){
                    $data5[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_purchase_query) > 0){
                while($row = mysqli_fetch_assoc($spr_purchase_query)){
                    $data6[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data7 = [];
            $data8 = [];

            foreach($data5 as $key => $value){
                array_push($data7, $value['parts_id']);

                foreach($data6 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data8, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $spr_requisition_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $spr_purchase_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");

            $data55 = [];
            $data66 = [];

            if(mysqli_num_rows($spr_requisition_query2) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_query2)){
                    $data55[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_purchase_query2) > 0){
                while($row = mysqli_fetch_assoc($spr_purchase_query2)){
                    $data66[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data77 = [];
            $data88 = [];

            foreach($data55 as $key => $value){
                array_push($data77, $value['parts_id']);

                foreach($data66 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data88, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $spr_requisition_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $spr_purchase_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY parts_id ORDER BY parts_id");

            $data555 = [];
            $data666 = [];

            if(mysqli_num_rows($spr_requisition_query3) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_query3)){
                    $data555[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_purchase_query3) > 0){
                while($row = mysqli_fetch_assoc($spr_purchase_query3)){
                    $data666[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data777 = [];
            $data888 = [];

            foreach($data555 as $key => $value){
                array_push($data777, $value['parts_id']);

                foreach($data666 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data888, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data9[] = [
                'tot_requisition' => (count($data3) + count($data7)),
                'tot_purchase' => (count($data4) + count($data8)),
                'tot_requisition2' => (count($data33) + count($data77)),
                'tot_purchase2' => (count($data44) + count($data88)),
                'tot_requisition3' => (count($data333) + count($data777)),
                'tot_purchase3' => (count($data444) + count($data888))
            ];

            $reply = array(
                'Type' => 'success',
                'Reply' => $data9
            );

            exit(json_encode($reply));
        }
    }
?>