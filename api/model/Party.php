<?php
    namespace Party;

    class Party{
        private $conn;

        function __construct(){
            $this->conn = $GLOBALS['conn'];
        }

        // FETCH A PARTY
        function fetch($party_id){
            $party_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_party WHERE party_id = '$party_id' LIMIT 1");

            if(mysqli_num_rows($party_query) > 0){
            	while($row = mysqli_fetch_assoc($party_query)){
	                $data[] = [
	                	'party_id' => $row['party_id'],
	                    'party_name' => $row['party_name'],
	                    'party_mobile' => $row['party_mobile'],
	                    'party_address' => $row['party_address'],
	                    'party_remarks' => $row['party_remarks']
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
                    'Reply' => 'No party found !'
                )));
            }
        }

        // FETCH ALL PARTY
        function fetch_all(){
            $party_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_party");

            if(mysqli_num_rows($party_query) > 0){
                $i = 0;

                while($row = mysqli_fetch_assoc($party_query)){
                    $party_id = $row['party_id'];

                    $party_ledger_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_party_ledger WHERE party_id = '$party_id'");

                    if(mysqli_num_rows($party_ledger_query) > 0){
                        $total_debit = 0;
                        $total_credit = 0;

                        while($row2 = mysqli_fetch_assoc($party_ledger_query)){
                            $total_debit += $row2['debit'];
                            $total_credit += $row2['credit'];
                        }

                        $current_balance = $total_debit - $total_credit;
                    }

                    if($row['opening_ledger_balance'] < 0)
                        $opening_ledger_balance = '<span class="text-danger">' . number_format((float)abs($row['opening_ledger_balance']), 2, '.', '') . '</span>';
                    else
                        $opening_ledger_balance = '<span class="text-success">' . number_format((float)$row['opening_ledger_balance'], 2, '.', '') . '</span>';

                    if($current_balance < 0)
                        $current_balance_txt = '<span class="text-danger">' . number_format((float)abs($current_balance), 2, '.', ''). '</span>';
                    else
                        $current_balance_txt = '<span class="text-success">' . number_format((float)$current_balance, 2, '.', ''). '</span>';

                    $payment = '<input type="number" class="form-control payment" name="payment" id="payment'.$party_id.'" min="0" max="'.$current_balance.'" data-id="'.$party_id.'" placeholder="Insert" oninput="payment('.$party_id.')" onchange="payment('.$party_id.')">';

                    $payment .= '<input type="hidden" class="temp-payment" id="temp_payment'.$party_id.'" value="'.$current_balance.'">';

                    $payment .= '&emsp;<button title="Pay" type="button" class="btn btn-xs btn-info pay" id="pay'.$party_id.'" data-id="'.$party_id.'" onclick="pay('.$party_id.')"><i class="mdi mdi-currency-bdt"></i></button>';

                    $action = '<a title="Ledger Record" href="javascript:void(0)" class="btn btn-xs btn-secondary" data-toggle="modal" data-target=".bs-example-modal-lg3" data-id="'.$party_id.'" onclick="party_ledger('.$party_id.')"><i class="mdi mdi-format-list-bulleted-type"></i></a>';
                    $action .= ' <a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success" data-toggle="modal" data-target=".bs-example-modal-lg2" data-id="'.$party_id.'" onclick="update_party('.$party_id.')"><i class="mdi mdi-pencil"></i></a>';
                    $action .= ' <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger" data-id="'.$party_id.'" onclick="delete_party('.$party_id.')"><i class="mdi mdi-delete"></i></a>';

                    $data[] = [
                        'sl' => ++$i,
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'party_mobile' => $row['party_mobile'],
                        'party_address' => $row['party_address'],
                        'opening_ledger_balance' => $opening_ledger_balance,
                        'current_balance' => $current_balance_txt,
                        'party_remarks' => $row['party_remarks'],
                        'payment' => $payment,
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
                    'Reply' => 'No party found !'
                )));
            }
        }

        // FETCH ALL PARTS BY SEARCH STRING
        function fetch_all_by_srch_str($search_str){
            $party_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_party WHERE party_name LIKE '$search_str%'");

            if(mysqli_num_rows($party_query) > 0){
                while($row = mysqli_fetch_assoc($party_query)){
                    $data[] = [
                        'party_id' => $row['party_id'],
                        'party_name' => $row['party_name'],
                        'party_mobile' => $row['party_mobile'],
                        'party_address' => $row['party_address'],
                        'party_remarks' => $row['party_remarks']
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

        // FETCH A PARTY LEDGER
        function fetch_party_ledger($party_id){
            $party_ledger_query = mysqli_query($this->conn, "SELECT * FROM rrmsteel_party_ledger WHERE party_id = '$party_id'");

            if(mysqli_num_rows($party_ledger_query) > 0){
                $total_balance = 0;
                $i = 0;

                while($row = mysqli_fetch_assoc($party_ledger_query)){
                    $debit = $row['debit'];
                    $credit = $row['credit'];

                    if($debit > 0){
                        $debit_class = 'text-success';
                    } else{
                        $debit_class = '';
                    }

                    if($credit > 0){
                        $credit_class = 'text-danger';
                    } else{
                        $credit_class = '';
                    }

                    $total_balance += ($debit - $credit);

                    if($total_balance > 0){
                        $total_class = 'text-success';
                    } else{
                        $total_class = 'text-danger';
                    }

                    $data[] = [
                        'sl' => ++$i,
                        'party_ledger_id' => $row['party_ledger_id'],
                        'party_id' => $party_id,
                        'description' => $row['description'],
                        'debit' => '<span class="' . $debit_class . '">' . $debit . '</span>',
                        'credit' => '<span class="' . $credit_class . '">' . $credit . '</span>',
                        'total' => '<span class="' . $total_class . '">' . number_format((float)$total_balance, 2, '.', '') . '</span>'
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
                    'Reply' => 'No party ledger found !'
                )));
            }
        }
    }
?>