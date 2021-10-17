<?php
    namespace Parts;

    class Parts{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // FETCH A PARTS
        function fetch($parts_id){
            $parts_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_parts WHERE parts_id = '$parts_id' LIMIT 1");

            if(mysqli_num_rows($parts_query) > 0){
            	while($row = mysqli_fetch_assoc($parts_query)){
	                $data[] = [
	                	'parts_id' => $row['parts_id'],
	                    'parts_name' => $row['parts_name'],
                        'parts_nickname' => $row['parts_nickname'],
	                    'parts_category' => $row['category'],
                        'parts_subcategory' => $row['subcategory'],
                        'parts_subcategory_2' => $row['subcategory_2'],
                        'parts_type' => $row['type'],
                        'parts_group' => $row['apply_group'],
                        'parts_inv_type' => $row['inv_type'],
	                    'parts_unit' => $row['unit'],
                        'parts_alert_qty' => $row['alert_qty'],
                        'parts_remarks' => $row['remarks']
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

        // FETCH ALL PARTS
        function fetch_all(){
            $parts_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id");

            if(mysqli_num_rows($parts_query) > 0){
                $i = 0;

                while($row = mysqli_fetch_assoc($parts_query)){
                    $parts_id = $row['parts_id'];

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

                    $parts_category = (($row['category'] == 1) ? 'Spare' : 'Consumable');

                    if($row['subcategory'] == 1)
                        $parts_subcategory = 'MP';
                    elseif($row['subcategory'] == 2)
                        $parts_subcategory = 'LC';
                    else
                        $parts_subcategory = 'Both';

                    if($row['subcategory_2'] == 1)
                        $parts_subcategory_2 = 'New';
                    elseif($row['subcategory_2'] == 2)
                        $parts_subcategory_2 = 'Repair';
                    elseif($row['subcategory_2'] == 3)
                        $parts_subcategory_2 = 'Replacement';
                    elseif($row['subcategory_2'] == 4)
                        $parts_subcategory_2 = 'Refill';
                    elseif($row['subcategory_2'] == 5)
                        $parts_subcategory_2 = 'Forma';
                    elseif($row['subcategory_2'] == 6)
                        $parts_subcategory_2 = 'Service Charge';
                    else
                        $parts_subcategory_2 = 'Transport';

                    $parts_type = (($row['type'] == 1) ? 'Asset' : 'Non-Asset');

                    if($row['apply_group'] == 1)
                        $parts_group = 'Mechanical';
                    elseif($row['apply_group'] == 2)
                        $parts_group = 'Electrical';
                    elseif($row['apply_group'] == 3)
                        $parts_group = 'Chemical';
                    elseif($row['apply_group'] == 4)
                        $parts_group = 'Machinery';
                    elseif($row['apply_group'] == 5)
                        $parts_group = 'IT';
                    elseif($row['apply_group'] == 6)
                        $parts_group = 'Rolls';
                    elseif($row['apply_group'] == 7)
                        $parts_group = 'General';

                    if($row['inv_type'] == 1)
                        $parts_inv_type = 'Inventory';
                    elseif($row['inv_type'] == 2)
                        $parts_inv_type = 'Non-Inventory';
                    elseif($row['inv_type'] == 3)
                        $parts_inv_type = 'Repair & Maintenance';

                    $parts_image = (($row['parts_image']) ? '<img width="80" height="80" src="../../assets/images/uploads/' . $row['parts_image'] . '">' : '');

                    $action = '<a href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".bs-example-modal-lg2" onclick="update_parts('.$parts_id.')"><i class="mdi mdi-pencil"></i></a>';
                    $action .= ' <a href="javascript:void(0)" class="btn btn-xs btn-danger" data-id="'.$parts_id.'" onclick="delete_parts('.$parts_id.')"><i class="mdi mdi-delete"></i></a>';

                    $data[] = [
                        'sl' => ++$i,
                        'parts_id' => $parts_id,
                        'parts_name' => $row['parts_name'],
                        'parts_nickname' => $row['parts_nickname'],
                        'parts_category' => $row['category'],
                        'parts_category_txt' => $parts_category,
                        'parts_subcategory' => $row['subcategory'],
                        'parts_subcategory_txt' => $parts_subcategory,
                        'parts_subcategory_2' => $row['subcategory_2'],
                        'parts_subcategory_2_txt' => $parts_subcategory_2,
                        'parts_type' => $row['type'],
                        'parts_type_txt' => $parts_type,
                        'parts_group' => $row['apply_group'],
                        'parts_group_txt' => $parts_group,
                        'parts_inv_type' => $row['inv_type'],
                        'parts_inv_type_txt' => $parts_inv_type,
                        'parts_unit' => $row['unit'],
                        'parts_unit_txt' => $parts_unit,
                        'parts_alert_qty' => $row['alert_qty'],
                        'parts_image' => $parts_image,
                        'parts_remarks' => $row['remarks'],
                        'parts_qty' => $row['parts_qty'],
                        'parts_avg_rate' => $row['parts_avg_rate'],
                        'action' => $action,
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

        // FETCH ALL PARTS BY SEARCH STRING
        function fetch_all_by_srch_str($parts_category, $search_str){
            $parts_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_parts p INNER JOIN rrmsteel_inv_summary i ON i.parts_id = p.parts_id WHERE p.parts_name LIKE '$search_str%' AND p.category = '$parts_category'");

            if(mysqli_num_rows($parts_query) > 0){
                while($row = mysqli_fetch_assoc($parts_query)){
                    $data[] = [
                        'parts_id' => $row['parts_id'],
                        'parts_name' => $row['parts_name'],
                        'parts_unit' => $row['unit'],
                        'parts_qty' => $row['parts_qty']
                    ];
                }

                $reply = array(
                    'Type' => 'success',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            } else{
                $data = [];

                $reply = array(
                    'Type' => 'error',
                    'Reply' => $data
                );

                exit(json_encode($reply));
            }
        }
    }
?>