<?php
    namespace Inventory;

    class Inventory{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // FETCH TOTAL RECEIVE AGAINST PURCHASE
        function fetch_tot_receive_against_purchase(){
            // CONSUMABLE
            $con_requisition_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");
            $con_purchase_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE()) AND YEAR(purchase_date) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");
            $con_receive_query = mysqli_query($this->conn, "SELECT i.parts_id, SUM(i.received_qty) AS parts_qty FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE p.category = 2 AND i.source = 1 AND MONTH(i.history_date) = MONTH(CURDATE()) AND YEAR(i.history_date) = YEAR(CURDATE()) GROUP BY i.parts_id ORDER BY i.parts_id");

            $data1 = [];
            $data2 = [];
            $data3 = [];

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

            if(mysqli_num_rows($con_receive_query) > 0){
                while($row = mysqli_fetch_assoc($con_receive_query)){
                    $data3[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data5 = [];

            foreach($data1 as $key => $value){
                foreach($data2 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            $data5[] = [
                                'parts_id' => $value2['parts_id'],
                                'parts_qty' => $value2['parts_qty']
                            ];
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data7 = [];

            foreach($data5 as $key => $value){
                foreach($data3 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] == $value['parts_qty']){
                            array_push($data7, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $con_requisition_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $con_purchase_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $con_receive_query2 = mysqli_query($this->conn, "SELECT i.parts_id, SUM(i.received_qty) AS parts_qty FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE p.category = 2 AND i.source = 1 AND MONTH(i.history_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(i.history_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY i.parts_id ORDER BY i.parts_id");

            $data11 = [];
            $data22 = [];
            $data33 = [];

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

            if(mysqli_num_rows($con_receive_query2) > 0){
                while($row = mysqli_fetch_assoc($con_receive_query2)){
                    $data33[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data55 = [];

            foreach($data11 as $key => $value){
                foreach($data22 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            $data55[] = [
                                'parts_id' => $value2['parts_id'],
                                'parts_qty' => $value2['parts_qty']
                            ];
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data77 = [];

            foreach($data55 as $key => $value){
                foreach($data33 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            array_push($data77, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $con_requisition_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_con_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $con_purchase_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_con_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $con_receive_query3 = mysqli_query($this->conn, "SELECT i.parts_id, SUM(i.received_qty) AS parts_qty FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE p.category = 2 AND i.source = 1 AND MONTH(i.history_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(i.history_date) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY i.parts_id ORDER BY i.parts_id");

            $data111 = [];
            $data222 = [];
            $data333 = [];

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

            if(mysqli_num_rows($con_receive_query3) > 0){
                while($row = mysqli_fetch_assoc($con_receive_query3)){
                    $data333[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data555 = [];

            foreach($data111 as $key => $value){
                foreach($data222 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            $data555[] = [
                                'parts_id' => $value2['parts_id'],
                                'parts_qty' => $value2['parts_qty']
                            ];
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data777 = [];

            foreach($data555 as $key => $value){
                foreach($data333 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] == $value['parts_qty']){
                            array_push($data777, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            // SPARE
            $spr_requisition_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");
            $spr_purchase_query = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE()) AND YEAR(purchase_date) = YEAR(CURDATE()) GROUP BY parts_id ORDER BY parts_id");
            $spr_receive_query = mysqli_query($this->conn, "SELECT i.parts_id, SUM(i.received_qty) AS parts_qty FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE p.category = 1 AND i.source = 1 AND MONTH(i.history_date) = MONTH(CURDATE()) AND YEAR(i.history_date) = YEAR(CURDATE()) GROUP BY i.parts_id ORDER BY i.parts_id");

            $data8 = [];
            $data9 = [];
            $data10 = [];

            if(mysqli_num_rows($spr_requisition_query) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_query)){
                    $data8[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_purchase_query) > 0){
                while($row = mysqli_fetch_assoc($spr_purchase_query)){
                    $data9[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_receive_query) > 0){
                while($row = mysqli_fetch_assoc($spr_receive_query)){
                    $data10[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data12 = [];

            foreach($data8 as $key => $value){
                foreach($data9 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            $data12[] = [
                                'parts_id' => $value2['parts_id'],
                                'parts_qty' => $value2['parts_qty']
                            ];
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data14 = [];

            foreach($data12 as $key => $value){
                foreach($data10 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] == $value['parts_qty']){
                            array_push($data14, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $spr_requisition_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $spr_purchase_query2 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $spr_receive_query2 = mysqli_query($this->conn, "SELECT i.parts_id, SUM(i.received_qty) AS parts_qty FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE p.category = 1 AND i.source = 1 AND MONTH(i.history_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(i.history_date) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY i.parts_id ORDER BY i.parts_id");

            $data88 = [];
            $data99 = [];
            $data1010 = [];

            if(mysqli_num_rows($spr_requisition_query2) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_query2)){
                    $data88[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_purchase_query2) > 0){
                while($row = mysqli_fetch_assoc($spr_purchase_query2)){
                    $data99[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_receive_query2) > 0){
                while($row = mysqli_fetch_assoc($spr_receive_query2)){
                    $data1010[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data1212 = [];

            foreach($data88 as $key => $value){
                foreach($data99 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            $data1212[] = [
                                'parts_id' => $value2['parts_id'],
                                'parts_qty' => $value2['parts_qty']
                            ];
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data1414 = [];

            foreach($data1212 as $key => $value){
                foreach($data1010 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] == $value['parts_qty']){
                            array_push($data1414, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $spr_requisition_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) as parts_qty FROM rrmsteel_spr_requisition_data WHERE MONTH(FROM_UNIXTIME(requisition_data_created)) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(FROM_UNIXTIME(requisition_data_created)) = YEAR(CURDATE() - INTERVAL 1 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $spr_purchase_query3 = mysqli_query($this->conn, "SELECT parts_id, SUM(parts_qty) AS parts_qty FROM rrmsteel_spr_purchase_data WHERE MONTH(purchase_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(purchase_date) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY parts_id ORDER BY parts_id");
            $spr_receive_query3 = mysqli_query($this->conn, "SELECT i.parts_id, SUM(i.received_qty) AS parts_qty FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE p.category = 1 AND i.source = 1 AND MONTH(i.history_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(i.history_date) = YEAR(CURDATE() - INTERVAL 2 MONTH) GROUP BY i.parts_id ORDER BY i.parts_id");

            $data888 = [];
            $data999 = [];
            $data101010 = [];

            if(mysqli_num_rows($spr_requisition_query3) > 0){
                while($row = mysqli_fetch_assoc($spr_requisition_query3)){
                    $data888[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_purchase_query3) > 0){
                while($row = mysqli_fetch_assoc($spr_purchase_query3)){
                    $data999[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            if(mysqli_num_rows($spr_receive_query3) > 0){
                while($row = mysqli_fetch_assoc($spr_receive_query3)){
                    $data101010[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }
            }

            $data121212 = [];

            foreach($data888 as $key => $value){
                foreach($data999 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] >= $value['parts_qty']){
                            $data121212[] = [
                                'parts_id' => $value2['parts_id'],
                                'parts_qty' => $value2['parts_qty']
                            ];
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data141414 = [];

            foreach($data121212 as $key => $value){
                foreach($data101010 as $key2 => $value2){
                    if($value2['parts_id'] == $value['parts_id']){
                        if($value2['parts_qty'] == $value['parts_qty']){
                            array_push($data141414, $value2['parts_id']);
                        }
                    } else{
                        continue;
                    }
                }
            }

            $data15[] = [
                'tot_purchase' => (count($data5) + count($data12)),
                'tot_receive' => (count($data7) + count($data14)),
                'tot_purchase2' => (count($data55) + count($data1212)),
                'tot_receive2' => (count($data77) + count($data1414)),
                'tot_purchase3' => (count($data555) + count($data121212)),
                'tot_receive3' => (count($data777) + count($data141414))
            ];

            $reply = array(
                'Type' => 'success',
                'Reply' => $data15
            );

            exit(json_encode($reply));
        }

        // FETCH INVENTORY TRANSACTION
        function fetch_transaction($tran_type){
            // INVENTORY HISTORY DATA
            if($tran_type == 1){
                $inventory_history_query = mysqli_query($this->conn, "SELECT h.parts_id, p.parts_name, p.unit, SUM(h.received_qty) AS tot_received_qty, SUM(h.received_value) AS tot_received_val FROM rrmsteel_inv_history h INNER JOIN rrmsteel_parts p ON h.parts_id = p.parts_id WHERE h.source = 1 AND MONTH(h.history_date) = MONTH(CURDATE()) GROUP BY h.parts_id");
            } else{
                $inventory_history_query = mysqli_query($this->conn, "SELECT h.parts_id, p.parts_name, p.unit, SUM(h.issued_qty) AS tot_issued_qty, SUM(h.issued_value) AS tot_issued_val FROM rrmsteel_inv_history h INNER JOIN rrmsteel_parts p ON h.parts_id = p.parts_id WHERE h.source = 2 AND MONTH(h.history_date) = MONTH(CURDATE()) GROUP BY h.parts_id");
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $parts_unit = '';

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

                    if($tran_type == 1){
                        $data[] = [
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'received_qty' => $row['tot_received_qty'],
                            'received_val' => $row['tot_received_val']
                        ];
                    } else{
                        $data[] = [
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'issued_qty' => $row['tot_issued_qty'],
                            'issued_val' => $row['tot_issued_val']
                        ];
                    }
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            }
        }

        // FETCH FILTERED INVENTORY TRANSACTION
        function fetch_filtered_transaction($parts_nickname, $tran_type){
            // INVENTORY HISTORY DATA
            if($tran_type == 1){
                $inventory_history_query = mysqli_query($this->conn, "SELECT h.parts_id, p.parts_name, p.unit, SUM(h.received_qty) AS tot_received_qty, SUM(h.received_value) AS tot_received_val FROM rrmsteel_inv_history h INNER JOIN rrmsteel_parts p ON h.parts_id = p.parts_id WHERE p.parts_nickname = '$parts_nickname' AND h.source = 1 AND MONTH(h.history_date) = 4 GROUP BY h.parts_id");
            } else{
                $inventory_history_query = mysqli_query($this->conn, "SELECT h.parts_id, p.parts_name, p.unit, SUM(h.issued_qty) AS tot_issued_qty, SUM(h.issued_value) AS tot_issued_val FROM rrmsteel_inv_history h INNER JOIN rrmsteel_parts p ON h.parts_id = p.parts_id WHERE p.parts_nickname = '$parts_nickname' AND h.source = 2 AND MONTH(h.history_date) = 4 GROUP BY h.parts_id");
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $parts_unit = '';

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

                    if($tran_type == 1){
                        $data[] = [
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'received_qty' => $row['tot_received_qty'],
                            'received_val' => $row['tot_received_val']
                        ];
                    } else{
                        $data[] = [
                            'parts_id' => $row['parts_id'],
                            'parts_name' => $row['parts_name'],
                            'parts_unit' => $parts_unit,
                            'issued_qty' => $row['tot_issued_qty'],
                            'issued_val' => $row['tot_issued_val']
                        ];
                    }
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            }
        }

        // INVENTORY PARTS RECEIVE
        function inventory_parts_receive($parts_id, $action_date){
            // INVENTORY HISTORY DATA
            $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date < '$action_date'");
            $inventory_history_query_rows = mysqli_num_rows($inventory_history_query);

            if($inventory_history_query_rows == 0){
                $parts_qty = null;
                $parts_price = null;

                $data[] = [
                    'parts_qty' => $parts_qty,
                    'parts_price' => $parts_price
                ];
            } else{
                // PARTS CATEGORY
                $parts_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT category FROM rrmsteel_parts WHERE parts_id = '$parts_id' LIMIT 1"));
                $parts_category = $parts_data_info['category'];

                // PURCHASED & BORROWED PARTS
                if($parts_category == 1){
                    $purchase_bill_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(qty) AS tot_billed_qty, SUM(received_qty) AS tot_received_qty, (SUM(qty) - SUM(received_qty)) AS tot_rem_qty, SUM(price) AS tot_billed_price FROM rrmsteel_spr_bill WHERE parts_id = '$parts_id' AND generate_date <= '$action_date' AND generate_status = 1 GROUP BY parts_id HAVING SUM(received_qty) != SUM(qty)"));

                    $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS tot_borrowed_qty, SUM(received_qty) AS tot_received_qty, (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty, SUM(price) AS tot_borrowed_price FROM rrmsteel_spr_loan_data WHERE parts_id = '$parts_id' AND loan_date <= '$action_date' GROUP BY parts_id HAVING SUM(received_qty) != SUM(parts_qty)"));
                } else{
                    $purchase_bill_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(qty) AS tot_billed_qty, SUM(received_qty) AS tot_received_qty, (SUM(qty) - SUM(received_qty)) AS tot_rem_qty, SUM(price) AS tot_billed_price FROM rrmsteel_con_bill WHERE parts_id = '$parts_id' AND generate_date <= '$action_date' AND generate_status = 1 GROUP BY parts_id HAVING SUM(received_qty) != SUM(qty)"));

                    $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT SUM(parts_qty) AS tot_borrowed_qty, SUM(received_qty) AS tot_received_qty, (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty, SUM(price) AS tot_borrowed_price FROM rrmsteel_con_loan_data WHERE parts_id = '$parts_id' AND loan_date <= '$action_date' GROUP BY parts_id HAVING SUM(received_qty) != SUM(parts_qty)"));
                }

                if(isset($purchase_bill_info) && isset($loan_data_info)){
                    $parts_qty = $purchase_bill_info['tot_rem_qty'] + $loan_data_info['tot_rem_qty'];
                    $parts_price = (($purchase_bill_info['tot_billed_price'] * $purchase_bill_info['tot_rem_qty']) + (($loan_data_info['tot_borrowed_price'] * $loan_data_info['tot_rem_qty']))) / ($purchase_bill_info['tot_billed_qty'] + $loan_data_info['tot_borrowed_qty']);
                } elseif(isset($purchase_bill_info) && !isset($loan_data_info)){
                    $parts_qty = $purchase_bill_info['tot_rem_qty'];
                    $parts_price = ($purchase_bill_info['tot_billed_price'] * $purchase_bill_info['tot_rem_qty']) / $purchase_bill_info['tot_billed_qty'];
                } elseif(!isset($purchase_bill_info) && isset($loan_data_info)){
                    $parts_qty = $loan_data_info['tot_rem_qty'];
                    $parts_price = ($loan_data_info['tot_borrowed_price'] * $loan_data_info['tot_rem_qty']) / $loan_data_info['tot_borrowed_qty'];
                } else{
                    $parts_qty = null;
                    $parts_price = null;
                }

                $data[] = [
                    'parts_qty' => $parts_qty,
                    'parts_price' => $parts_price
                ];
            }

            $reply = array(
                'Type' => 'success',
                'Reply' => $data
            );

            exit(json_encode($reply));
        }

        // INVENTORY PARTS ISSUE
        function inventory_parts_issue($parts_id, $action_date){
            // INVENTORY HISTORY DATA
            $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date < '$action_date'");

            if(mysqli_num_rows($inventory_history_query) == 0){
                $parts_qty = null;
            } else{
                // AVAILABLE FOR SELECTED
                $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_avg_rate, closing_qty FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date = '$action_date' LIMIT 1"));

                // NOT AVAILABLE FOR SELECTED
                $inventory_history_info1 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_avg_rate, closing_qty FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date <= '$action_date' ORDER BY history_date DESC, inventory_history_created DESC LIMIT 1"));
                
                if(isset($inventory_history_info)){
                    $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT parts_avg_rate, closing_qty FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date >= '$action_date' AND closing_qty = (SELECT MIN(closing_qty) AS min_closing_qty FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date >= '$action_date') ORDER BY history_date, inventory_history_created LIMIT 1"));
                    $parts_qty = $inventory_history_info2['closing_qty'];
                    $parts_price = $inventory_history_info2['parts_avg_rate'];
                } else{
                    $parts_qty = $inventory_history_info1['closing_qty'];
                    $parts_price = $inventory_history_info1['parts_avg_rate'];
                }
            }

            $data[] = [
                'parts_qty' => $parts_qty,
                'parts_price' => ($parts_qty * $parts_price)
            ];

            $reply = array(
                'Type' => 'success',
                'Reply' => $data
            );

            exit(json_encode($reply));
        }

        // INVENTORY PARTS TOTAL RECEIVE & ISSUE QTY. & VALUE
        function inventory_parts_tot_rcv_iss_qty_val(){
            $tot_issued_qty = 0;
            $tot_received_qty = 0;
            $tot_issued_val = 0;
            $tot_received_val = 0;

            $flag1 = 0;
            $flag2 = 0;
            $flag3 = 0;

            $inventory_history_query = mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty, SUM(issued_qty) AS issued_qty, SUM(received_value) AS received_value, SUM(issued_value) AS issued_value FROM rrmsteel_inv_history WHERE source > 0 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE())");

            if(mysqli_num_rows($inventory_history_query) > 0){
                $flag1 = 1;

                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $tot_received_qty += $row['received_qty'];
                    $tot_issued_qty += $row['issued_qty'];
                    $tot_received_val += $row['received_value'];
                    $tot_issued_val += $row['issued_value'];
                }
            }

            $inventory_history_query2 = mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty, SUM(issued_qty) AS issued_qty, SUM(received_value) AS received_value, SUM(issued_value) AS issued_value FROM rrmsteel_inv_history WHERE source > 0 AND MONTH(history_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(history_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)");

            $tot_issued_qty2 = 0;
            $tot_received_qty2 = 0;
            $tot_issued_val2 = 0;
            $tot_received_val2 = 0;

            if(mysqli_num_rows($inventory_history_query2) > 0){
                $flag2 = 1;

                while($row = mysqli_fetch_assoc($inventory_history_query2)){
                    $tot_received_qty2 += $row['received_qty'];
                    $tot_issued_qty2 += $row['issued_qty'];
                    $tot_received_val2 += $row['received_value'];
                    $tot_issued_val2 += $row['issued_value'];
                }
            }

            $inventory_history_query3 = mysqli_query($this->conn, "SELECT SUM(received_qty) AS received_qty, SUM(issued_qty) AS issued_qty, SUM(received_value) AS received_value, SUM(issued_value) AS issued_value FROM rrmsteel_inv_history WHERE source > 0 AND MONTH(history_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(history_date) = YEAR(CURDATE() - INTERVAL 2 MONTH)");

            $tot_issued_qty3 = 0;
            $tot_received_qty3 = 0;
            $tot_issued_val3 = 0;
            $tot_received_val3 = 0;

            if(mysqli_num_rows($inventory_history_query3) > 0){
                $flag3 = 1;

                while($row = mysqli_fetch_assoc($inventory_history_query3)){
                    $tot_received_qty3 += $row['received_qty'];
                    $tot_issued_qty3 += $row['issued_qty'];
                    $tot_received_val3 += $row['received_value'];
                    $tot_issued_val3 += $row['issued_value'];
                }
            }

            if($flag1 == 1 || $flag2 == 1 || $flag3 == 1){
                $data[] = [
                    'tot_received_qty' => $tot_received_qty,
                    'tot_issued_qty' => $tot_issued_qty,
                    'tot_received_val' => $tot_received_val,
                    'tot_issued_val' => $tot_issued_val,
                    'tot_received_qty2' => $tot_received_qty2,
                    'tot_issued_qty2' => $tot_issued_qty2,
                    'tot_received_val2' => $tot_received_val2,
                    'tot_issued_val2' => $tot_issued_val2,
                    'tot_received_qty3' => $tot_received_qty3,
                    'tot_issued_qty3' => $tot_issued_qty3,
                    'tot_received_val3' => $tot_received_val3,
                    'tot_issued_val3' => $tot_issued_val3
                ];

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // INVENTORY SUMMARY LIST
        function inventory_summary_list($alpha){
            if($alpha == '_')
                $where = '';
            elseif($alpha == 9)
                $where = "p.parts_name regexp '^[0-9]+'";
            else
                $where = "p.parts_name LIKE '".$alpha."%'";

            if($where){
                $inventory_history_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit, p.category, p.alert_qty, i.inventory_id, i.parts_qty AS parts_qty, i.parts_avg_rate, SUM(h.received_qty) AS tot_rcv_qty, SUM(h.issued_qty) AS tot_iss_qty FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE " . $where . " GROUP BY p.parts_id");
            } else{
                $inventory_history_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit, p.category, p.alert_qty, i.inventory_id, i.parts_qty AS parts_qty, i.parts_avg_rate, SUM(h.received_qty) AS tot_rcv_qty, SUM(h.issued_qty) AS tot_iss_qty FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id GROUP BY p.parts_id");
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                $i = 1;

                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $parts_id = $row['parts_id'];
                    $parts_name = $row['parts_name'];

                    if($row['category'] == 2){
                        $purchase_bill_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(qty) - SUM(received_qty)) AS tot_rem_qty_frm_bill FROM rrmsteel_con_bill WHERE parts_id = '$parts_id' AND generate_status = 1 GROUP BY parts_id HAVING SUM(received_qty) != SUM(qty)"));

                        $tot_rem_qty_frm_bill = (isset($purchase_bill_info) ? $purchase_bill_info['tot_rem_qty_frm_bill'] : '0.000');

                        $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty_frm_loan FROM rrmsteel_con_loan_data WHERE parts_id = '$parts_id' GROUP BY parts_id HAVING SUM(received_qty) != SUM(parts_qty)"));

                        $tot_rem_qty_frm_loan = (isset($loan_data_info) ? $loan_data_info['tot_rem_qty_frm_loan'] : '0.000');
                    } else{
                        $purchase_bill_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(qty) - SUM(received_qty)) AS tot_rem_qty_frm_bill FROM rrmsteel_spr_bill WHERE parts_id = '$parts_id' AND generate_status = 1 GROUP BY parts_id HAVING SUM(received_qty) != SUM(qty)"));

                        $tot_rem_qty_frm_bill = (isset($purchase_bill_info) ? $purchase_bill_info['tot_rem_qty_frm_bill'] : '0.000');

                        $loan_data_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT (SUM(parts_qty) - (SUM(repay_qty) + SUM(received_qty))) AS tot_rem_qty_frm_loan FROM rrmsteel_spr_loan_data WHERE parts_id = '$parts_id' GROUP BY parts_id HAVING SUM(received_qty) != SUM(parts_qty)"));

                        $tot_rem_qty_frm_loan = (isset($loan_data_info) ? $loan_data_info['tot_rem_qty_frm_loan'] : '0.000');
                    }

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

                    if($row['parts_qty'] <= $row['alert_qty']){
                        $action = '<i class="fa fa-info-circle mt-1 mr-2" title="Warning: Low quantity!"></i>';
                    } else{
                        $action = '';
                    }

                    $action .= '<select class="action-type" onchange="action_type(this)">
                                    <option value="">Choose type</option>
                                    <option value="1">Receive</option>
                                    <option value="2">Issue</option>
                                </select>

                                <input type="text" class="form-control d-none action-date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" onchange="action_date(this, ' . $parts_id . ')" oninput="validate_action_date(this)">

                                <br>

                                <select class="mt-1 d-none req-for" onchange="required_for(this, ' . $parts_id . ')">
                                    <option value="">Choose required for</option>
                                    <option value="1">BCP-CCM</option>
                                    <option value="2">BCP-Furnace</option>
                                    <option value="3">Concast-CCM</option>
                                    <option value="4">Concast-Furnace</option>
                                    <option value="5">HRM</option>
                                    <option value="6">HRM Unit-2</option>
                                    <option value="7">Lal Masjid</option>
                                    <option value="8">Sonargaon</option>
                                    <option value="9">General</option>
                                </select>
                                
                                <input type="number" class="form-control mt-1 mr-0 d-none qty" placeholder="Insert qty." oninput="qty(this)">
                                
                                <input type="number" class="form-control mt-1 d-none price" placeholder="Insert price" oninput="price(this)">';

                    $action .= '<button title="Save" type="button" class="btn btn-xs btn-info ml-1 d-none save" onclick="update_inventory(this, ' . $parts_id . ')"><i class="mdi mdi-content-save"></i></button>';

                    $pending = '<span class="d-none">' . $i . '.1' . ' ' . $parts_name . '</span>';

                    $pending .= '<p class="m-0 text-center">Pending Receive Till Date:&emsp; <span class="text-success">Purchase: </span><span class="text-success"><strong>' . $tot_rem_qty_frm_bill . '</strong> ' . $parts_unit . '</span> &nbsp;|&nbsp; <span class="text-primary">Loan: </span><span class="text-primary"><strong>' . $tot_rem_qty_frm_loan . '</strong> ' . $parts_unit . '</span></p>';

                    $data[] = [
                        'sl' => $i,
                        'parts_id' => $parts_id,
                        'parts_name' => '<strong>' . $parts_name . '</strong>',
                        'parts_unit' => $parts_unit,
                        'parts_qty' => $row['parts_qty'],
                        'parts_alert_qty' => $row['alert_qty'],
                        'tot_rcv_qty' => $row['tot_rcv_qty'],
                        'tot_iss_qty' => $row['tot_iss_qty'],
                        'parts_avg_rate' => $row['parts_avg_rate'],
                        'inventory_id' => $row['inventory_id'],
                        'tot_rem_qty_frm_bill' => 0,
                        'tot_rem_qty_frm_loan' => 0,
                        'action' => $action
                    ];

                    array_push($data, [
                        'sl' => $i,
                        'parts_name' => $pending,
                        'parts_unit' => $parts_unit,
                        'parts_qty' => $row['parts_qty'],
                        'parts_alert_qty' => $row['alert_qty'],
                        'tot_rcv_qty' => $row['tot_rcv_qty'],
                        'tot_iss_qty' => $row['tot_iss_qty'],
                        'parts_avg_rate' => $row['parts_avg_rate'],
                        'inventory_id' => $row['inventory_id'],
                        'tot_rem_qty_frm_bill' => $tot_rem_qty_frm_bill,
                        'tot_rem_qty_frm_loan' => $tot_rem_qty_frm_loan,
                        'action' => $action
                    ]);

                    $i++;
                }

                $char_arr = [];

                $purchase_bill_info_2 = mysqli_query($this->conn, "SELECT SUBSTR(p.parts_name, 1, 1) AS parts_char FROM rrmsteel_con_bill b INNER JOIN rrmsteel_parts p ON p.parts_id = b.parts_id WHERE b.generate_status = 1 GROUP BY p.parts_name HAVING SUM(b.received_qty) != SUM(b.qty)");

                if(mysqli_num_rows($purchase_bill_info_2) > 0){
                    while($row = mysqli_fetch_assoc($purchase_bill_info_2)){
                        array_push($char_arr, $row['parts_char']);
                    }
                }

                $loan_data_info_2 = mysqli_query($this->conn, "SELECT SUBSTR(p.parts_name, 1, 1) AS parts_char FROM rrmsteel_con_loan_data l INNER JOIN rrmsteel_parts p ON p.parts_id = l.parts_id GROUP BY p.parts_name HAVING SUM(l.received_qty) != SUM(l.parts_qty)");

                if(mysqli_num_rows($loan_data_info_2) > 0){
                    while($row = mysqli_fetch_assoc($loan_data_info_2)){
                        array_push($char_arr, $row['parts_char']);
                    }
                }

                $purchase_bill_info_3 = mysqli_query($this->conn, "SELECT SUBSTR(p.parts_name, 1, 1) AS parts_char FROM rrmsteel_spr_bill b INNER JOIN rrmsteel_parts p ON p.parts_id = b.parts_id WHERE b.generate_status = 1 GROUP BY p.parts_name HAVING SUM(b.received_qty) != SUM(b.qty)");

                if(mysqli_num_rows($purchase_bill_info_3) > 0){
                    while($row = mysqli_fetch_assoc($purchase_bill_info_3)){
                        array_push($char_arr, $row['parts_char']);
                    }
                }

                $loan_data_info_3 = mysqli_query($this->conn, "SELECT SUBSTR(p.parts_name, 1, 1) AS parts_char FROM rrmsteel_spr_loan_data l INNER JOIN rrmsteel_parts p ON p.parts_id = l.parts_id GROUP BY p.parts_name HAVING SUM(l.received_qty) != SUM(l.parts_qty)");

                if(mysqli_num_rows($loan_data_info_3) > 0){
                    while($row = mysqli_fetch_assoc($loan_data_info_3)){
                        array_push($char_arr, $row['parts_char']);
                    }
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data,
                    'Reply2' => $char_arr
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No summary data found !'
                )));
            }
        }

        // INVENTORY HISTORY LIST
        function inventory_history_list(){
            $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id INNER JOIN rrmsteel_user u ON u.user_id = i.user_id WHERE i.source > 0 ORDER BY i.history_date DESC, i.inventory_history_created DESC");

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $inventory_history_id = $row['inventory_history_id'];
                    $parts_id = $row['parts_id'];
                    $source = $row['source'];
                    $required_for = $row['required_for'];
                    $received_qty = $row['received_qty'];
                    $received_val = $row['received_value'];
                    $issued_qty = $row['issued_qty'];
                    $issued_val = $row['issued_value'];
                    $history_date = $row['history_date'];

                    $action = '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info edt-btn' . (($source == 0) ? 'd-none' : '') . '" onclick="edit_btn(this, ' . (($received_qty > 0) ? 1 : 2) . ', ' . $parts_id . ')"><i class="mdi mdi-pencil"></i></a>';
                    $action .= ' <a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-danger d-none cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';
                    $action .= ' <a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none upd-btn" onclick="update_inventory(this, ' . $parts_id . ', ' . $inventory_history_id . ', ' . $source . ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';

                    $history_date_txt = '<span class="">' . $history_date . '</span>';
                    $history_date_txt .= '<input type="text" class="form-control d-none action-date" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" onchange="action_date(this, ' . $parts_id . ')" value="'.$history_date.'" oninput="validate_action_date(this)">';

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

                    if($required_for == 1)
                        $required_for_txt = '<span class="">BCP-CCM</span>';
                    elseif($required_for == 2)
                        $required_for_txt = '<span class="">BCP-Furnace</span>';
                    elseif($required_for == 3)
                        $required_for_txt = '<span class="">Concast-CCM</span>';
                    elseif($required_for == 4)
                        $required_for_txt = '<span class="">Concast-Furnace</span>';
                    elseif($required_for == 5)
                        $required_for_txt = '<span class="">HRM</span>';
                    elseif($required_for == 6)
                        $required_for_txt = '<span class="">HRM Unit-2</span>';
                    elseif($required_for == 7)
                        $required_for_txt = '<span class="">Lal Masjid</span>';
                    elseif($required_for == 8)
                        $required_for_txt = '<span class="">Sonargaon</span>';
                    elseif($required_for == 9)
                        $required_for_txt = '<span class="">General</span>';

                    $required_for_txt .= '<select class="form-control d-none req-for" onchange="required_for(this, ' . $parts_id . ')">
                                            <option value="">Choose</option>
                                            <option value="1"' . (($required_for == 1) ? 'selected' : '') . '>BCP-CCM</option>
                                            <option value="2"' . (($required_for == 2) ? 'selected' : '') . '>BCP-Furnace</option>
                                            <option value="3"' . (($required_for == 3) ? 'selected' : '') . '>Concast-CCM</option>
                                            <option value="4"' . (($required_for == 4) ? 'selected' : '') . '>Concast-Furnace</option>
                                            <option value="5"' . (($required_for == 5) ? 'selected' : '') . '>HRM</option>
                                            <option value="6"' . (($required_for == 6) ? 'selected' : '') . '>HRM Unit-2</option>
                                            <option value="7"' . (($required_for == 7) ? 'selected' : '') . '>Lal Masjid</option>
                                            <option value="8"' . (($required_for == 8) ? 'selected' : '') . '>Sonargaon</option>
                                            <option value="9"' . (($required_for == 9) ? 'selected' : '') . '>General</option>
                                        </select>';

                    $received_qty_txt = '';
                    $received_val_txt = '';
                    $issued_qty_txt = '';
                    $issued_val_txt = '';

                    if($source == 1){
                        $received_qty_txt .= '<span class="data-span">' . $received_qty . '</span>';
                        $received_qty_txt .= '<input type="number" class="form-control d-none data-input rcv-qty" placeholder="Insert" value="'.$received_qty.'" oninput="qty(this)">';

                        $received_val_txt .= '<span class="data-span">' . $received_val . '</span>';
                        $received_val_txt .= '<input type="number" class="form-control d-none data-input rcv-price" placeholder="Insert" value="'.$received_val.'" oninput="price(this)">';
                    } elseif($source > 1){
                        $issued_qty_txt .= '<span class="data-span">' . $issued_qty . '</span>';
                        $issued_qty_txt .= '<input type="number" class="form-control d-none data-input iss-qty" placeholder="Insert" value="'.$issued_qty.'" oninput="qty(this)">';

                        $issued_val_txt .= '<span class="data-span">' . $issued_val . '</span>';
                        $issued_val_txt .= '<input type="number" class="form-control d-none data-input iss-price" placeholder="Insert" value="'.$issued_val.'" oninput="price(this)">';
                    }

                    $data[] = [
                        'action' => $action,
                        'inventory_history_id' => $inventory_history_id,
                        'source' => $source,
                        'required_for' => $required_for_txt,
                        'parts_id' => $parts_id,
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $parts_unit,
                        'parts_rate' => $row['parts_rate'],
                        'parts_avg_rate' => $row['parts_avg_rate'],
                        'opening_qty' => $row['opening_qty'],
                        'opening_value' => $row['opening_value'],
                        'received_qty' => $received_qty_txt,
                        'received_value' => $received_val_txt,
                        'issued_qty' => $issued_qty_txt,
                        'issued_value' => $issued_val_txt,
                        'closing_qty' => $row['closing_qty'],
                        'closing_value' => $row['closing_value'],
                        'history_date' => $history_date_txt,
                        'inventory_history_created' => date('d M, Y \a\t h:i a', $row['inventory_history_created']),
                        'user_fullname' => $row['user_fullname']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH INVENTORY PARTS DATE VALIDITY
        function fetch_parts_date_validity($parts_id){
            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' ORDER BY history_date DESC LIMIT 1"));

            $start_date = '';
            $history_date = '';

            if(isset($inventory_history_info['history_date'])){
                $history_date = $inventory_history_info['history_date'];
                $date_diff = time() - strtotime($history_date);
                $days_num = round($date_diff / (60 * 60 * 24));

                if($days_num > 29)
                    $start_date = -29 . 'd';
                else
                    $start_date = -$days_num . 'd';
            } else{
                $history_date = '';
            }

            $data[] = [
                'start_date' => $start_date,
                'latest_date' => $history_date
            ];

            $reply = array(
                'Type' => 'success',
                'Reply' => $data
            );

            exit(json_encode($reply));
        }

        // FETCH ALL INVENTORY PARTS INFO
        function fetch_all_parts_info($type, $required_for){
            if($type == 1){
                $inventory_history_query = mysqli_query($this->conn, "SELECT *, i.parts_avg_rate AS p_avg FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source > 1 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) ORDER BY h.history_date");
            } else{
                $inventory_history_query = mysqli_query($this->conn, "SELECT *, i.parts_avg_rate AS p_avg FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source = 1 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) ORDER BY h.history_date");
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'parts_qty' => $row['parts_qty'],
                        'parts_avg_rate' => $row['p_avg']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS DATE
        function fetch_all_parts_date($type, $required_for){
            if($type == 1){
                $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source > 1 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) ORDER BY h.history_date");
            } else{
                $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source = 1 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) ORDER BY h.history_date");
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    if($type == 1){
                        $qty = $row['issued_qty'];
                    } else{
                        $qty = $row['received_qty'];
                    }

                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'issued_qty' => $qty,
                        'history_date' => date('Y-m-d', strtotime($row['history_date']))
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS INFO
        function fetch_filtered_parts_info($type, $parts, $date_range, $required_for){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($type == 1){
                if($parts == 0){
                    $inventory_history_query = mysqli_query($this->conn, "SELECT *, i.parts_avg_rate AS p_avg FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source > 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                } else{
                    $inventory_history_query = mysqli_query($this->conn, "SELECT *, i.parts_avg_rate AS p_avg FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.parts_id = '$parts' AND h.source > 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                }
            } else{
                if($parts == 0){
                    $inventory_history_query = mysqli_query($this->conn, "SELECT *, i.parts_avg_rate AS p_avg FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source = 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                } else{
                    $inventory_history_query = mysqli_query($this->conn, "SELECT *, i.parts_avg_rate AS p_avg FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.parts_id = '$parts' AND h.source = 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                }
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'parts_qty' => $row['parts_qty'],
                        'parts_avg_rate' => $row['p_avg']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS DATE
        function fetch_filtered_parts_date($type, $parts, $date_range, $required_for){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($type == 1){
                if($parts == 0){
                    $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source > 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                } else{
                    $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.parts_id = '$parts' AND h.source > 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                }
            } else{
                if($parts == 0){
                    $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.source = 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date");
                } else{
                   $inventory_history_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_inv_history h ON h.parts_id = i.parts_id INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE h.required_for = '$required_for' AND h.parts_id = '$parts' AND h.source = 1 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' ORDER BY h.history_date"); 
                }
            }

            if(mysqli_num_rows($inventory_history_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    if($type == 1){
                        $qty = $row['issued_qty'];
                    } else{
                        $qty = $row['received_qty'];
                    }

                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'issued_qty' => $qty,
                        'history_date' => date('Y-m-d', strtotime($row['history_date']))
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS STOCK INFO
        function fetch_all_parts_stock_info(){
            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS STOCK DATE
        function fetch_all_parts_stock_date(){
            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];
                    $parts_name = $row['parts_name'];
                    $parts_unit = $row['unit'];

                    $inventory_history_query = mysqli_query($this->conn, "SELECT SUM(issued_qty) AS tot_issued_qty, SUM(received_qty) AS tot_received_qty, history_date FROM rrmsteel_inv_history WHERE source > 0 AND parts_id = '$parts_id' AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) GROUP BY history_date ORDER BY history_date");

                    if(mysqli_num_rows($inventory_history_query) > 0){
                        $flag = 1;

                        while($row2 = mysqli_fetch_assoc($inventory_history_query)){
                            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.closing_qty, i.closing_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE MONTH(history_date) < MONTH(CURDATE()) GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND MONTH(i.history_date) < MONTH(CURDATE()) ORDER BY i.history_date DESC, i.inventory_history_created DESC LIMIT 1"));

                            if(isset($inventory_history_info)){
                                $opening_qty = $inventory_history_info['closing_qty'];
                            } else{
                                $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.opening_qty, i.opening_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE MONTH(history_date) = MONTH(CURDATE()) GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND MONTH(i.history_date) = MONTH(CURDATE()) ORDER BY i.history_date, i.inventory_history_created LIMIT 1"));

                                if(isset($inventory_history_info2)){
                                    $opening_qty = $inventory_history_info2['opening_qty'];
                                } else{
                                    $opening_qty = 0;
                                }
                            }

                            $data[] = [
                                'parts_id' => $parts_id,
                                'parts_name' => $parts_name,
                                'parts_unit' => $parts_unit,
                                'opening_qty' => $opening_qty,
                                'received_qty' => $row2['tot_received_qty'],
                                'issued_qty' => $row2['tot_issued_qty'],
                                'history_date' => $row2['history_date']
                            ];
                        }
                    }
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS STOCK DATE 2
        function fetch_all_parts_stock_date_2($type){
            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];
                    $parts_name = $row['parts_name'];
                    $parts_unit = $row['unit'];
                    $parts_avg_rate = $row['parts_avg_rate'];

                    if($type == 1){
                        $inventory_history_query = mysqli_query($this->conn, "SELECT history_date, SUM(issued_qty) AS tot_qty FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND source > 1 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) GROUP BY history_date ORDER BY history_date");
                    } else{
                        $inventory_history_query = mysqli_query($this->conn, "SELECT history_date, SUM(received_qty) AS tot_qty FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND source = 1 AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) GROUP BY history_date ORDER BY history_date");
                    }
                    
                    if(mysqli_num_rows($inventory_history_query) > 0){
                        $flag = 1;

                        while($row2 = mysqli_fetch_assoc($inventory_history_query)){
                            $data[] = [
                                'parts_id' => $parts_id,
                                'parts_name' => $parts_name,
                                'parts_unit' => $parts_unit,
                                'avg_parts_rate' => $parts_avg_rate,
                                'issued_qty' => $row2['tot_qty'],
                                'parts_val' => $parts_avg_rate * $row2['tot_qty'],
                                'history_date' => date('Y-m-d', strtotime($row2['history_date']))
                            ];
                        }
                    }
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS STOCK INFO
        function fetch_filtered_parts_stock_info($parts){
            if($parts == 0){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");
            } else{
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.parts_id = '$parts'");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS STOCK DATE
        function fetch_filtered_parts_stock_date($parts, $date_range){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($parts == 0){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");
            } else{
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.parts_id = '$parts'");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];
                    $parts_name = $row['parts_name'];
                    $parts_unit = $row['unit'];

                    $inventory_history_query = mysqli_query($this->conn, "SELECT SUM(issued_qty) AS tot_issued_qty, SUM(received_qty) AS tot_received_qty, history_date FROM rrmsteel_inv_history WHERE source > 0 AND parts_id = '$parts_id' AND history_date >= '$start_date' AND history_date <= '$end_date' GROUP BY history_date ORDER BY history_date");

                    if(mysqli_num_rows($inventory_history_query) > 0){
                        $flag = 1;

                        while($row2 = mysqli_fetch_assoc($inventory_history_query)){
                            $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.closing_qty, i.closing_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE history_date < '$start_date' GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND i.history_date < '$start_date' ORDER BY i.history_date DESC, i.inventory_history_created DESC LIMIT 1"));

                            if(isset($inventory_history_info)){
                                $opening_qty = $inventory_history_info['closing_qty'];
                            } else{
                                $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.opening_qty, i.opening_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE history_date = '$start_date' GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND i.history_date = '$start_date' ORDER BY i.history_date, i.inventory_history_created LIMIT 1"));

                                if(isset($inventory_history_info2)){
                                    $opening_qty = $inventory_history_info2['opening_qty'];
                                } else{
                                    $opening_qty = 0;
                                }
                            }
                            
                            $data[] = [
                                'parts_id' => $parts_id,
                                'parts_name' => $parts_name,
                                'parts_unit' => $parts_unit,
                                'opening_qty' => $opening_qty,
                                'received_qty' => $row2['tot_received_qty'],
                                'issued_qty' => $row2['tot_issued_qty'],
                                'history_date' => $row2['history_date']
                            ];
                        }
                    }
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS STOCK DATE 2
        function fetch_filtered_parts_stock_date_2($type, $parts, $date_range){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($parts == 0){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");
            } else{
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.parts_id = '$parts'");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];
                    $parts_name = $row['parts_name'];
                    $parts_unit = $row['unit'];

                    if($type == 1){
                        $inventory_history_query = mysqli_query($this->conn, "SELECT AVG(parts_avg_rate) AS avg_rate, SUM(issued_qty) AS tot_qty, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND source > 1  AND history_date >= '$start_date' AND history_date <= '$end_date' GROUP BY history_date ORDER BY history_date");
                    } else{
                        $inventory_history_query = mysqli_query($this->conn, "SELECT AVG(parts_avg_rate) AS avg_rate, SUM(received_qty) AS tot_qty, history_date FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND source = 1  AND history_date >= '$start_date' AND history_date <= '$end_date' GROUP BY history_date ORDER BY history_date");
                    }
                    
                    if(mysqli_num_rows($inventory_history_query) > 0){
                        $flag = 1;

                        while($row2 = mysqli_fetch_assoc($inventory_history_query)){
                            $data[] = [
                                'parts_id' => $parts_id,
                                'parts_name' => $parts_name,
                                'parts_unit' => $parts_unit,
                                'avg_parts_rate' => $row2['avg_rate'],
                                'issued_qty' => $row2['tot_qty'],
                                'parts_val' => $row2['avg_rate'] * $row2['tot_qty'],
                                'history_date' => date('Y-m-d', strtotime($row2['history_date']))
                            ];
                        }
                    }
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS OVERALL INFO
        function fetch_all_parts_overall_info(){
            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_nickname' => $row['parts_nickname'],
                        'parts_category' => $row['category'],
                        'parts_subcategory' => $row['subcategory'],
                        'parts_unit' => $row['unit']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS OVERALL DETAILS
        function fetch_all_parts_overall_details(){
            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];
                    $parts_rate = $row['parts_rate'];
                    $parts_avg_rate = $row['parts_avg_rate'];
                    $flag = 1;

                    $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.closing_qty, i.closing_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE MONTH(history_date) < MONTH(CURDATE()) GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND MONTH(i.history_date) < MONTH(CURDATE()) ORDER BY i.history_date DESC, i.inventory_history_created DESC LIMIT 1"));

                    if(isset($inventory_history_info)){
                        $opening_qty = $inventory_history_info['closing_qty'];
                        $opening_val = $inventory_history_info['closing_value'];
                    } else{
                        $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.opening_qty, i.opening_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE MONTH(history_date) = MONTH(CURDATE()) GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND MONTH(i.history_date) = MONTH(CURDATE()) ORDER BY i.history_date, i.inventory_history_created LIMIT 1"));

                        if(isset($inventory_history_info2)){
                            $opening_qty = $inventory_history_info2['opening_qty'];
                            $opening_val = $inventory_history_info2['opening_value'];
                        } else{
                            $opening_qty = 0;
                            $opening_val = 0;
                        }
                    }

                    $inventory_history_query = mysqli_query($this->conn, "SELECT received_qty, received_value, issued_qty, issued_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE())");

                    $received_qty = 0;
                    $received_val = 0;
                    $issued_qty = 0;
                    $issued_val = 0;

                    while($row = mysqli_fetch_assoc($inventory_history_query)){
                        $received_qty += $row['received_qty'];
                        $received_val += $row['received_value'];
                        $issued_qty += $row['issued_qty'];
                        $issued_val += $row['issued_value'];
                    }

                    $closing_qty = $opening_qty + $received_qty - $issued_qty;
                    $closing_val = $opening_val + $received_val - $issued_val;
                    
                    $data[] = [
                        'parts_id' => $parts_id,
                        'opening_qty' => $opening_qty,
                        'opening_value' => $opening_val,
                        'parts_rate' => $parts_rate,
                        'parts_avg_rate' => $parts_avg_rate,
                        'received_qty' => $received_qty,
                        'received_value' => $received_val,
                        'issued_qty' => $issued_qty,
                        'issued_value' => $issued_val,
                        'closing_qty' => $closing_qty,
                        'closing_value' => $closing_val
                    ];
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS OVERALL INFO
        function fetch_filtered_parts_overall_info($parts){
            if($parts){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.parts_id = '$parts'");
            } else{
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_nickname' => $row['parts_nickname'],
                        'parts_category' => $row['category'],
                        'parts_subcategory' => $row['subcategory'],
                        'parts_unit' => $row['unit']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS OVERALL DETAILS
        function fetch_filtered_parts_overall_details($parts, $date_range){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($parts == 0){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");
            } else{
                $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.parts_id = '$parts'");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];
                    $parts_name = $row['parts_name'];
                    $parts_nickname = $row['parts_nickname'];
                    $parts_category = $row['category'];
                    $parts_subcategory = $row['subcategory'];
                    $parts_unit = $row['unit'];
                    $flag = 1;

                    $inventory_history_info = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.parts_rate, i.closing_qty, i.closing_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE history_date < '$start_date' GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND i.history_date < '$start_date' ORDER BY i.history_date DESC, i.inventory_history_created DESC LIMIT 1"));

                    if(isset($inventory_history_info)){
                        $opening_qty = $inventory_history_info['closing_qty'];
                        $opening_val = $inventory_history_info['closing_value'];
                        $parts_rate = $inventory_history_info['parts_rate'];
                    } else{
                        $inventory_history_info2 = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT i.parts_id, i.opening_qty, i.opening_value, i.parts_rate FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE history_date = '$start_date' GROUP BY parts_id) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE i.parts_id = '$parts_id' AND i.history_date = '$start_date' ORDER BY i.history_date, i.inventory_history_created LIMIT 1"));

                        if(isset($inventory_history_info2)){
                            $opening_qty = $inventory_history_info2['opening_qty'];
                            $opening_val = $inventory_history_info2['opening_value'];
                            $parts_rate = $inventory_history_info2['parts_rate'];
                        } else{
                            $opening_qty = 0;
                            $opening_val = 0;
                            $parts_rate = 0;
                        }
                    }

                    $inventory_history_query = mysqli_query($this->conn, "SELECT received_qty, received_value, issued_qty, issued_value FROM rrmsteel_inv_history WHERE parts_id = '$parts_id' AND history_date >= '$start_date' AND history_date <= '$end_date'");

                    $received_qty = 0;
                    $received_val = 0;
                    $issued_qty = 0;
                    $issued_val = 0;

                    while($row = mysqli_fetch_assoc($inventory_history_query)){
                        $received_qty += $row['received_qty'];
                        $received_val += $row['received_value'];
                        $issued_qty += $row['issued_qty'];
                        $issued_val += $row['issued_value'];
                    }

                    $parts_avg_rate = ($opening_qty + $received_qty) > 0 ? (($opening_val + $received_val) / ($opening_qty + $received_qty)) : 0;

                    $closing_qty = $opening_qty + $received_qty - $issued_qty;
                    $closing_val = $opening_val + $received_val - $issued_val;
                    
                    $data[] = [
                        'parts_id' => $parts_id,
                        'parts_name' => $parts_name,
                        'parts_nickname' => $parts_nickname,
                        'parts_category' => $parts_category,
                        'parts_subcategory' => $parts_subcategory,
                        'parts_unit' => $parts_unit,
                        'opening_qty' => $opening_qty,
                        'opening_value' => $opening_val,
                        'parts_rate' => $parts_rate,
                        'parts_avg_rate' => $parts_avg_rate,
                        'received_qty' => $received_qty,
                        'received_value' => $received_val,
                        'issued_qty' => $issued_qty,
                        'issued_value' => $issued_val,
                        'closing_qty' => $closing_qty,
                        'closing_value' => $closing_val
                    ];
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH INVENTORY SPECIFIC PARTS INFO
        function fetch_specific_parts_info($required_for){
            if($required_for == 14){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 15){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 16){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 17){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 18){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 19){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 20){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 21){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 22){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 23){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 24){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 25){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 26){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 27){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 28){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 29){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 30){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 31){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH INVENTORY SPECIFIC PARTS DETAILS
        function fetch_specific_parts_details($required_for){
            if($required_for == 14){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 15){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 16){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 17){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 18){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 19){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 20){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 21){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 22){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 23){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 24){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 25){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 26){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 27){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND h.required_for = 5 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 28){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 29){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 30){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            } elseif($required_for == 31){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 6 AND MONTH(h.history_date) = MONTH(CURDATE()) AND YEAR(h.history_date) = YEAR(CURDATE()) GROUP BY p.parts_id");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $flag = 1;

                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'issued_qty' => $row['tot_issued'],
                        'parts_avg_rate' => $row['parts_avg_rate'],
                        'parts_val' => $row['parts_avg_rate'] * $row['tot_issued']
                    ];
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY SPECIFIC PARTS INFO
        function fetch_filtered_specific_parts_info($date_range, $required_for){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($required_for == 14){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 15){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 16){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 17){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 18){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 19){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 20){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 21){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 22){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 23){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 24){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 25){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 26){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 27){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 28){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 29){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 30){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 31){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, p.parts_name, p.unit FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit']
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
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY SPECIFIC PARTS DETAILS
        function fetch_filtered_specific_parts_details($date_range, $required_for){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            if($required_for == 14){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 15){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 16){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 17){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 18){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 1 OR h.required_for = 2) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 19){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 20){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 21){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 22){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 23){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND (h.required_for = 3 OR h.required_for = 4) AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 24){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 25){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 26){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 27){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 4 AND h.source > 1 AND h.required_for = 5 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 28){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 3 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 29){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 2 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 30){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 1 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            } elseif($required_for == 31){
                $inventory_summary_query = mysqli_query($this->conn, "SELECT p.parts_id, i.parts_avg_rate, SUM(h.issued_qty) AS tot_issued FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id INNER JOIN rrmsteel_inv_history h ON h.parts_id = p.parts_id WHERE p.apply_group = 7 AND h.source > 1 AND h.required_for = 6 AND h.history_date >= '$start_date' AND h.history_date <= '$end_date' GROUP BY p.parts_id");
            }

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $flag = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $flag = 1;

                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'issued_qty' => $row['tot_issued'],
                        'parts_avg_rate' => $row['parts_avg_rate'],
                        'parts_val' => $row['parts_avg_rate'] * $row['tot_issued']
                    ];
                }

                if($flag == 1){
                    $reply = array(
                        'Type' => 'success',
                        'Reply' => $data
                    );

                    exit(json_encode($reply));
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'No history found !'
                    )));
                }
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ALL INVENTORY PARTS SUMMARY DETAILS
        function fetch_all_parts_summary_details($curr_mon_status){
            $required_for_val_arr = [];

            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $tot_opening_val1 = 0;
                $tot_opening_val2 = 0;
                $tot_opening_val3 = 0;
                $tot_opening_val4 = 0;
                $tot_opening_val5 = 0;
                $tot_opening_val6 = 0;
                $tot_opening_val7 = 0;

                $tot_received_val1 = 0;
                $tot_received_val2 = 0;
                $tot_received_val3 = 0;
                $tot_received_val4 = 0;
                $tot_received_val5 = 0;
                $tot_received_val6 = 0;
                $tot_received_val7 = 0;

                $tot_issued_val1 = 0;
                $tot_issued_val2 = 0;
                $tot_issued_val3 = 0;
                $tot_issued_val4 = 0;
                $tot_issued_val5 = 0;
                $tot_issued_val6 = 0;
                $tot_issued_val7 = 0;

                $tot_closing_val1 = 0;
                $tot_closing_val2 = 0;
                $tot_closing_val3 = 0;
                $tot_closing_val4 = 0;
                $tot_closing_val5 = 0;
                $tot_closing_val6 = 0;
                $tot_closing_val7 = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];

                    $inventory_history_query = mysqli_query($this->conn, "SELECT i.parts_id, i.required_for, i.closing_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE source > 0 AND MONTH(history_date) < MONTH(CURDATE()) GROUP BY parts_id, required_for) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE source > 0 AND MONTH(i.history_date) < MONTH(CURDATE()) AND i.parts_id = '$parts_id' ORDER BY i.required_for, i.history_date DESC, i.inventory_history_created DESC");

                    if(mysqli_num_rows($inventory_history_query) > 0){
                        $required_for = 0;

                        while($row = mysqli_fetch_assoc($inventory_history_query)){
                            if($required_for != $row['required_for']){
                                switch($row['required_for']){
                                    case 1:
                                        $tot_opening_val1 += $row['closing_value'];
                                        break;
                                    case 2:
                                        $tot_opening_val1 += $row['closing_value'];
                                        break;
                                    case 3:
                                        $tot_opening_val2 += $row['closing_value'];
                                        break;
                                    case 4:
                                        $tot_opening_val2 += $row['closing_value'];
                                        break;
                                    case 5:
                                        $tot_opening_val3 += $row['closing_value'];
                                        break;
                                    case 6:
                                        $tot_opening_val4 += $row['closing_value'];
                                        break;
                                    case 7:
                                        $tot_opening_val5 += $row['closing_value'];
                                        break;
                                    case 8:
                                        $tot_opening_val6 += $row['closing_value'];
                                        break;
                                    case 9:
                                        $tot_opening_val7 += $row['closing_value'];
                                        break;
                                }
                                
                                $required_for = $row['required_for'];
                            } else{
                                continue;
                            }
                        }
                    } else{
                        $inventory_history_query2 = mysqli_query($this->conn, "SELECT i.parts_id, i.required_for, i.opening_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history WHERE source > 0 AND MONTH(history_date) = MONTH(CURDATE()) GROUP BY parts_id, required_for) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE source > 0 AND MONTH(i.history_date) = MONTH(CURDATE()) AND i.parts_id = '$parts_id' ORDER BY i.required_for, i.history_date DESC, i.inventory_history_created DESC");

                        if(mysqli_num_rows($inventory_history_query2) > 0){
                            $required_for = 0;

                            while($row = mysqli_fetch_assoc($inventory_history_query2)){
                                if($required_for != $row['required_for']){
                                    switch($row['required_for']){
                                        case 1:
                                            $tot_opening_val1 += $row['opening_value'];
                                            break;
                                        case 2:
                                            $tot_opening_val1 += $row['opening_value'];
                                            break;
                                        case 3:
                                            $tot_opening_val2 += $row['opening_value'];
                                            break;
                                        case 4:
                                            $tot_opening_val2 += $row['opening_value'];
                                            break;
                                        case 5:
                                            $tot_opening_val3 += $row['opening_value'];
                                            break;
                                        case 6:
                                            $tot_opening_val4 += $row['opening_value'];
                                            break;
                                        case 7:
                                            $tot_opening_val5 += $row['opening_value'];
                                            break;
                                        case 8:
                                            $tot_opening_val6 += $row['opening_value'];
                                            break;
                                        case 9:
                                            $tot_opening_val7 += $row['opening_value'];
                                            break;
                                    }
                                    
                                    $required_for = $row['required_for'];
                                } else{
                                    continue;
                                }
                            }
                        } else{
                            $tot_opening_val1 += 0;
                            $tot_opening_val2 += 0;
                            $tot_opening_val3 += 0;
                            $tot_opening_val4 += 0;
                            $tot_opening_val5 += 0;
                            $tot_opening_val6 += 0;
                            $tot_opening_val7 += 0;
                        }
                    }

                    $inventory_history_query = mysqli_query($this->conn, "SELECT required_for, SUM(received_value) AS received_value, SUM(issued_value) AS issued_value FROM rrmsteel_inv_history WHERE source > 0 AND parts_id = '$parts_id' AND MONTH(history_date) = MONTH(CURDATE()) AND YEAR(history_date) = YEAR(CURDATE()) GROUP BY required_for ORDER BY required_for");

                    while($row = mysqli_fetch_assoc($inventory_history_query)){
                        switch($row['required_for']){
                            case 1:
                                $tot_received_val1 += $row['received_value'];
                                $tot_issued_val1 += $row['issued_value'];
                                break;
                            case 2:
                                $tot_received_val1 += $row['received_value'];
                                $tot_issued_val1 += $row['issued_value'];
                                break;
                            case 3:
                                $tot_received_val2 += $row['received_value'];
                                $tot_issued_val2 += $row['issued_value'];
                                break;
                            case 4:
                                $tot_received_val2 += $row['received_value'];
                                $tot_issued_val2 += $row['issued_value'];
                                break;
                            case 5:
                                $tot_received_val3 += $row['received_value'];
                                $tot_issued_val3 += $row['issued_value'];
                                break;
                            case 6:
                                $tot_received_val4 += $row['received_value'];
                                $tot_issued_val4 += $row['issued_value'];
                                break;
                            case 7:
                                $tot_received_val5 += $row['received_value'];
                                $tot_issued_val5 += $row['issued_value'];
                                break;
                            case 8:
                                $tot_received_val6 += $row['received_value'];
                                $tot_issued_val6 += $row['issued_value'];
                                break;
                            case 9:
                                $tot_received_val7 += $row['received_value'];
                                $tot_issued_val7 += $row['issued_value'];
                                break;
                        }
                    }
                }

                $tot_closing_val1 = $tot_opening_val1 + $tot_received_val1 - $tot_issued_val1;
                $tot_closing_val2 = $tot_opening_val2 + $tot_received_val2 - $tot_issued_val2;
                $tot_closing_val3 = $tot_opening_val3 + $tot_received_val3 - $tot_issued_val3;
                $tot_closing_val4 = $tot_opening_val4 + $tot_received_val4 - $tot_issued_val4;
                $tot_closing_val5 = $tot_opening_val5 + $tot_received_val5 - $tot_issued_val5;
                $tot_closing_val6 = $tot_opening_val6 + $tot_received_val6 - $tot_issued_val6;
                $tot_closing_val7 = $tot_opening_val7 + $tot_received_val7 - $tot_issued_val7;

                for($i=1; $i<=7; $i++){
                    switch($i){
                        case 1:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val1,
                                'tot_received_val' => $tot_received_val1,
                                'tot_issued_val' => $tot_issued_val1,
                                'tot_closing_val' => $tot_closing_val1
                            ]);
                            break;
                        case 2:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val2,
                                'tot_received_val' => $tot_received_val2,
                                'tot_issued_val' => $tot_issued_val2,
                                'tot_closing_val' => $tot_closing_val2
                            ]);
                            break;
                        case 3:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val3,
                                'tot_received_val' => $tot_received_val3,
                                'tot_issued_val' => $tot_issued_val3,
                                'tot_closing_val' => $tot_closing_val3
                            ]);
                            break;
                        case 4:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val4,
                                'tot_received_val' => $tot_received_val4,
                                'tot_issued_val' => $tot_issued_val4,
                                'tot_closing_val' => $tot_closing_val4
                            ]);
                            break;
                        case 5:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val5,
                                'tot_received_val' => $tot_received_val5,
                                'tot_issued_val' => $tot_issued_val5,
                                'tot_closing_val' => $tot_closing_val5
                            ]);
                            break;
                        case 6:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val6,
                                'tot_received_val' => $tot_received_val6,
                                'tot_issued_val' => $tot_issued_val6,
                                'tot_closing_val' => $tot_closing_val6
                            ]);
                            break;
                        case 7:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val7,
                                'tot_received_val' => $tot_received_val7,
                                'tot_issued_val' => $tot_issued_val7,
                                'tot_closing_val' => $tot_closing_val7
                            ]);
                            break;
                    }
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $required_for_val_arr
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH FILTERED INVENTORY PARTS SUMMARY DETAILS
        function fetch_filtered_parts_summary_details($date_range){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            $required_for_val_arr = [];

            $inventory_summary_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_inv_summary i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id");

            if(mysqli_num_rows($inventory_summary_query) > 0){
                $tot_opening_val1 = 0;
                $tot_opening_val2 = 0;
                $tot_opening_val3 = 0;
                $tot_opening_val4 = 0;
                $tot_opening_val5 = 0;
                $tot_opening_val6 = 0;
                $tot_opening_val7 = 0;

                $tot_received_val1 = 0;
                $tot_received_val2 = 0;
                $tot_received_val3 = 0;
                $tot_received_val4 = 0;
                $tot_received_val5 = 0;
                $tot_received_val6 = 0;
                $tot_received_val7 = 0;

                $tot_issued_val1 = 0;
                $tot_issued_val2 = 0;
                $tot_issued_val3 = 0;
                $tot_issued_val4 = 0;
                $tot_issued_val5 = 0;
                $tot_issued_val6 = 0;
                $tot_issued_val7 = 0;

                $tot_closing_val1 = 0;
                $tot_closing_val2 = 0;
                $tot_closing_val3 = 0;
                $tot_closing_val4 = 0;
                $tot_closing_val5 = 0;
                $tot_closing_val6 = 0;
                $tot_closing_val7 = 0;

                while($row = mysqli_fetch_assoc($inventory_summary_query)){
                    $parts_id = $row['parts_id'];

                    $inventory_history_query = mysqli_query($this->conn, "SELECT i.parts_id, i.required_for, i.closing_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history GROUP BY parts_id, required_for) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE source > 0 AND i.history_date < '$start_date' AND i.parts_id = '$parts_id' ORDER BY i.required_for, i.history_date DESC, i.inventory_history_created DESC");

                    if(mysqli_num_rows($inventory_history_query) > 0){
                        $required_for = 0;

                        while($row = mysqli_fetch_assoc($inventory_history_query)){
                            if($required_for != $row['required_for']){
                                switch($row['required_for']){
                                    case 1:
                                        $tot_opening_val1 += $row['closing_value'];
                                        break;
                                    case 2:
                                        $tot_opening_val1 += $row['closing_value'];
                                        break;
                                    case 3:
                                        $tot_opening_val2 += $row['closing_value'];
                                        break;
                                    case 4:
                                        $tot_opening_val2 += $row['closing_value'];
                                        break;
                                    case 5:
                                        $tot_opening_val3 += $row['closing_value'];
                                        break;
                                    case 6:
                                        $tot_opening_val4 += $row['closing_value'];
                                        break;
                                    case 7:
                                        $tot_opening_val5 += $row['closing_value'];
                                        break;
                                    case 8:
                                        $tot_opening_val6 += $row['closing_value'];
                                        break;
                                    case 9:
                                        $tot_opening_val7 += $row['closing_value'];
                                        break;
                                }
                                
                                $required_for = $row['required_for'];
                            } else{
                                continue;
                            }
                        }
                    } else{
                        $inventory_history_query2 = mysqli_query($this->conn, "SELECT i.parts_id, i.required_for, i.opening_value FROM (SELECT parts_id, MAX(history_date) AS max_date FROM rrmsteel_inv_history GROUP BY parts_id, required_for) i2 INNER JOIN rrmsteel_inv_history i ON i.parts_id = i2.parts_id AND i.history_date = i2.max_date WHERE source > 0 AND i.history_date = '$start_date' AND i.parts_id = '$parts_id' ORDER BY i.required_for, i.history_date DESC, i.inventory_history_created DESC");

                        if(mysqli_num_rows($inventory_history_query2) > 0){
                            $required_for = 0;

                            while($row = mysqli_fetch_assoc($inventory_history_query2)){
                                if($required_for != $row['required_for']){
                                    switch($row['required_for']){
                                        case 1:
                                            $tot_opening_val1 += $row['opening_value'];
                                            break;
                                        case 2:
                                            $tot_opening_val1 += $row['opening_value'];
                                            break;
                                        case 3:
                                            $tot_opening_val2 += $row['opening_value'];
                                            break;
                                        case 4:
                                            $tot_opening_val2 += $row['opening_value'];
                                            break;
                                        case 5:
                                            $tot_opening_val3 += $row['opening_value'];
                                            break;
                                        case 6:
                                            $tot_opening_val4 += $row['opening_value'];
                                            break;
                                        case 7:
                                            $tot_opening_val5 += $row['opening_value'];
                                            break;
                                        case 8:
                                            $tot_opening_val6 += $row['opening_value'];
                                            break;
                                        case 9:
                                            $tot_opening_val7 += $row['opening_value'];
                                            break;
                                    }
                                    
                                    $required_for = $row['required_for'];
                                } else{
                                    continue;
                                }
                            }
                        } else{
                            $tot_opening_val1 += 0;
                            $tot_opening_val2 += 0;
                            $tot_opening_val3 += 0;
                            $tot_opening_val4 += 0;
                            $tot_opening_val5 += 0;
                            $tot_opening_val6 += 0;
                            $tot_opening_val7 += 0;
                        }
                    }

                    $inventory_history_query = mysqli_query($this->conn, "SELECT required_for, SUM(received_value) AS received_value, SUM(issued_value) AS issued_value FROM rrmsteel_inv_history WHERE source > 0 AND parts_id = '$parts_id' AND history_date >= '$start_date' AND history_date <= '$end_date' GROUP BY required_for ORDER BY required_for");

                    while($row = mysqli_fetch_assoc($inventory_history_query)){
                        switch($row['required_for']){
                            case 1:
                                $tot_received_val1 += $row['received_value'];
                                $tot_issued_val1 += $row['issued_value'];
                                break;
                            case 2:
                                $tot_received_val1 += $row['received_value'];
                                $tot_issued_val1 += $row['issued_value'];
                                break;
                            case 3:
                                $tot_received_val2 += $row['received_value'];
                                $tot_issued_val2 += $row['issued_value'];
                                break;
                            case 4:
                                $tot_received_val2 += $row['received_value'];
                                $tot_issued_val2 += $row['issued_value'];
                                break;
                            case 5:
                                $tot_received_val3 += $row['received_value'];
                                $tot_issued_val3 += $row['issued_value'];
                                break;
                            case 6:
                                $tot_received_val4 += $row['received_value'];
                                $tot_issued_val4 += $row['issued_value'];
                                break;
                            case 7:
                                $tot_received_val5 += $row['received_value'];
                                $tot_issued_val5 += $row['issued_value'];
                                break;
                            case 8:
                                $tot_received_val6 += $row['received_value'];
                                $tot_issued_val6 += $row['issued_value'];
                                break;
                            case 9:
                                $tot_received_val7 += $row['received_value'];
                                $tot_issued_val7 += $row['issued_value'];
                                break;
                        }
                    }
                }

                $tot_closing_val1 = $tot_opening_val1 + $tot_received_val1 - $tot_issued_val1;
                $tot_closing_val2 = $tot_opening_val2 + $tot_received_val2 - $tot_issued_val2;
                $tot_closing_val3 = $tot_opening_val3 + $tot_received_val3 - $tot_issued_val3;
                $tot_closing_val4 = $tot_opening_val4 + $tot_received_val4 - $tot_issued_val4;
                $tot_closing_val5 = $tot_opening_val5 + $tot_received_val5 - $tot_issued_val5;
                $tot_closing_val6 = $tot_opening_val6 + $tot_received_val6 - $tot_issued_val6;
                $tot_closing_val7 = $tot_opening_val7 + $tot_received_val7 - $tot_issued_val7;

                for($i=1; $i<=7; $i++){
                    switch($i){
                        case 1:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val1,
                                'tot_received_val' => $tot_received_val1,
                                'tot_issued_val' => $tot_issued_val1,
                                'tot_closing_val' => $tot_closing_val1
                            ]);
                            break;
                        case 2:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val2,
                                'tot_received_val' => $tot_received_val2,
                                'tot_issued_val' => $tot_issued_val2,
                                'tot_closing_val' => $tot_closing_val2
                            ]);
                            break;
                        case 3:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val3,
                                'tot_received_val' => $tot_received_val3,
                                'tot_issued_val' => $tot_issued_val3,
                                'tot_closing_val' => $tot_closing_val3
                            ]);
                            break;
                        case 4:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val4,
                                'tot_received_val' => $tot_received_val4,
                                'tot_issued_val' => $tot_issued_val4,
                                'tot_closing_val' => $tot_closing_val4
                            ]);
                            break;
                        case 5:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val5,
                                'tot_received_val' => $tot_received_val5,
                                'tot_issued_val' => $tot_issued_val5,
                                'tot_closing_val' => $tot_closing_val5
                            ]);
                            break;
                        case 6:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val6,
                                'tot_received_val' => $tot_received_val6,
                                'tot_issued_val' => $tot_issued_val6,
                                'tot_closing_val' => $tot_closing_val6
                            ]);
                            break;
                        case 7:
                            array_push($required_for_val_arr, (object)[
                                'tot_opening_val' => $tot_opening_val7,
                                'tot_received_val' => $tot_received_val7,
                                'tot_issued_val' => $tot_issued_val7,
                                'tot_closing_val' => $tot_closing_val7
                            ]);
                            break;
                    }
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $required_for_val_arr
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }

        // FETCH ISSUED INVENTORY PARTS SUMMARY DETAILS
        function fetch_issued_parts_summary_details(){
            $inventory_history_query = mysqli_query($this->conn, "SELECT i.required_for, SUM(CASE WHEN p.apply_group = 1 THEN i.issued_value ELSE 0 END) AS tot_mechanical, SUM(CASE WHEN p.apply_group = 2 THEN i.issued_value ELSE 0 END) AS tot_electrical, SUM(CASE WHEN p.apply_group = 3 THEN i.issued_value ELSE 0 END) AS tot_chemical, SUM(CASE WHEN p.apply_group = 4 THEN i.issued_value ELSE 0 END) AS tot_machinery, SUM(CASE WHEN p.apply_group = 7 THEN i.issued_value ELSE 0 END) AS tot_general FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.source > 1 AND i.required_for <= 6 AND MONTH(i.history_date) = MONTH(CURDATE()) GROUP BY i.required_for ORDER BY i.required_for");

            $bcp_mechanical = 0;
            $bcp_electrical = 0;
            $bcp_chemical = 0;
            $bcp_machinery = 0;
            $bcp_general = 0;

            $con_mechanical = 0;
            $con_electrical = 0;
            $con_chemical = 0;
            $con_machinery = 0;
            $con_general = 0;

            $hrm_mechanical = 0;
            $hrm_electrical = 0;
            $hrm_chemical = 0;
            $hrm_machinery = 0;
            $hrm_general = 0;

            $hrm2_mechanical = 0;
            $hrm2_electrical = 0;
            $hrm2_chemical = 0;
            $hrm2_machinery = 0;
            $hrm2_general = 0;
            
            if(mysqli_num_rows($inventory_history_query) > 0){
                $i = 1;
                
                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    if($i == 1 || $i == 2){
                        $bcp_mechanical += $row['tot_mechanical'];
                        $bcp_electrical += $row['tot_electrical'];
                        $bcp_chemical += $row['tot_chemical'];
                        $bcp_machinery += $row['tot_machinery'];
                        $bcp_general += $row['tot_general'];
                    } elseif($i == 3 || $i == 4){
                        $con_mechanical += $row['tot_mechanical'];
                        $con_electrical += $row['tot_electrical'];
                        $con_chemical += $row['tot_chemical'];
                        $con_machinery += $row['tot_machinery'];
                        $con_general += $row['tot_general'];
                    } elseif($i == 5){
                        $hrm_mechanical = $row['tot_mechanical'];
                        $hrm_electrical = $row['tot_electrical'];
                        $hrm_chemical = $row['tot_chemical'];
                        $hrm_machinery = $row['tot_machinery'];
                        $hrm_general = $row['tot_general'];
                    } else{
                        $hrm2_mechanical = $row['tot_mechanical'];
                        $hrm2_electrical = $row['tot_electrical'];
                        $hrm2_chemical = $row['tot_chemical'];
                        $hrm2_machinery = $row['tot_machinery'];
                        $hrm2_general = $row['tot_general'];
                    }

                    $i++;
                }
            }

            $bcp['mechanical'] = $bcp_mechanical;
            $bcp['electrical'] = $bcp_electrical;
            $bcp['chemical'] = $bcp_chemical;
            $bcp['machinery'] = $bcp_machinery;
            $bcp['general'] = $bcp_general;

            $con['mechanical'] = $con_mechanical;
            $con['electrical'] = $con_electrical;
            $con['chemical'] = $con_chemical;
            $con['machinery'] = $con_machinery;
            $con['general'] = $con_general;

            $hrm['mechanical'] = $hrm_mechanical;
            $hrm['electrical'] = $hrm_electrical;
            $hrm['chemical'] = $hrm_chemical;
            $hrm['machinery'] = $hrm_machinery;
            $hrm['general'] = $hrm_general;

            $hrm2['mechanical'] = $hrm2_mechanical;
            $hrm2['electrical'] = $hrm2_electrical;
            $hrm2['chemical'] = $hrm2_chemical;
            $hrm2['machinery'] = $hrm2_machinery;
            $hrm2['general'] = $hrm2_general;

            $data[] = [
                'bcp' => $bcp,
                'con' => $con,
                'hrm' => $hrm,
                'hrm2' => $hrm2
            ];

            $reply = array(
                'Type' => 'success',
                'Reply' => $data
            );

            exit(json_encode($reply));
        }

        function fetch_filtered_issued_parts_summary_details($date_range){
            $date_range = explode(' to ', $date_range);
            $start_date = $date_range[0];
            $end_date = $date_range[1];

            $inventory_history_query = mysqli_query($this->conn, "SELECT i.required_for, SUM(CASE WHEN p.apply_group = 1 THEN i.issued_value ELSE 0 END) AS tot_mechanical, SUM(CASE WHEN p.apply_group = 2 THEN i.issued_value ELSE 0 END) AS tot_electrical, SUM(CASE WHEN p.apply_group = 3 THEN i.issued_value ELSE 0 END) AS tot_chemical, SUM(CASE WHEN p.apply_group = 4 THEN i.issued_value ELSE 0 END) AS tot_machinery, SUM(CASE WHEN p.apply_group = 7 THEN i.issued_value ELSE 0 END) AS tot_general FROM rrmsteel_inv_history i INNER JOIN rrmsteel_parts p ON p.parts_id = i.parts_id WHERE i.source > 1 AND i.required_for <= 6 AND i.history_date >= '$start_date' AND i.history_date <= '$end_date' GROUP BY i.required_for ORDER BY i.required_for");

            if(mysqli_num_rows($inventory_history_query) > 0){
                $bcp_mechanical = 0;
                $bcp_electrical = 0;
                $bcp_chemical = 0;
                $bcp_machinery = 0;
                $bcp_general = 0;

                $con_mechanical = 0;
                $con_electrical = 0;
                $con_chemical = 0;
                $con_machinery = 0;
                $con_general = 0;

                $hrm_mechanical = 0;
                $hrm_electrical = 0;
                $hrm_machinery = 0;
                $hrm_general = 0;

                $hrm2_mechanical = 0;
                $hrm2_electrical = 0;
                $hrm2_machinery = 0;
                $hrm2_general = 0;

                $i = 1;

                while($row = mysqli_fetch_assoc($inventory_history_query)){
                    if($i == 1 || $i == 2){
                        $bcp_mechanical += $row['tot_mechanical'];
                        $bcp_electrical += $row['tot_electrical'];
                        $bcp_chemical += $row['tot_chemical'];
                        $bcp_machinery += $row['tot_machinery'];
                        $bcp_general += $row['tot_general'];
                    } elseif($i == 3 || $i == 4){
                        $con_mechanical += $row['tot_mechanical'];
                        $con_electrical += $row['tot_electrical'];
                        $con_chemical += $row['tot_chemical'];
                        $con_machinery += $row['tot_machinery'];
                        $con_general += $row['tot_general'];
                    } elseif($i == 5){
                        $hrm_mechanical = $row['tot_mechanical'];
                        $hrm_electrical = $row['tot_electrical'];
                        $hrm_chemical = $row['tot_chemical'];
                        $hrm_machinery = $row['tot_machinery'];
                        $hrm_general = $row['tot_general'];
                    } else{
                        $hrm2_mechanical = $row['tot_mechanical'];
                        $hrm2_electrical = $row['tot_electrical'];
                        $hrm2_chemical = $row['tot_chemical'];
                        $hrm2_machinery = $row['tot_machinery'];
                        $hrm2_general = $row['tot_general'];
                    }

                    $i++;
                }

                $bcp['mechanical'] = $bcp_mechanical;
                $bcp['electrical'] = $bcp_electrical;
                $bcp['chemical'] = $bcp_chemical;
                $bcp['machinery'] = $bcp_machinery;
                $bcp['general'] = $bcp_general;

                $con['mechanical'] = $con_mechanical;
                $con['electrical'] = $con_electrical;
                $con['chemical'] = $con_chemical;
                $con['machinery'] = $con_machinery;
                $con['general'] = $con_general;

                $hrm['mechanical'] = $hrm_mechanical;
                $hrm['electrical'] = $hrm_electrical;
                $hrm['chemical'] = $hrm_chemical;
                $hrm['machinery'] = $hrm_machinery;
                $hrm['general'] = $hrm_general;

                $hrm2['mechanical'] = $hrm2_mechanical;
                $hrm2['electrical'] = $hrm2_electrical;
                $hrm2['chemical'] = $hrm2_chemical;
                $hrm2['machinery'] = $hrm2_machinery;
                $hrm2['general'] = $hrm2_general;

                $data[] = [
                    'bcp' => $bcp,
                    'con' => $con,
                    'hrm' => $hrm,
                    'hrm2' => $hrm2
                ];

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            } else{
                exit(json_encode(array(
                    'Type' => 'error',
                    'Reply' => 'No history found !'
                )));
            }
        }
    }
?>