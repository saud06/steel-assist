<?php
    use Interaction\Interaction;

    require_once('connection.php');
    require_once('model/Interaction.php');

    if(!empty($_POST['interact_type'])){
        if($_POST['interact_type'] == 'add'){
            // USER
            if($_POST['interact'] == 'user'){
            	if(!empty($_POST['category']) && !empty($_POST['fullname']) && !empty($_POST['email']) && !empty($_POST['password'])){
            		$input = [
                        'category' => mysqli_real_escape_string($conn, $_POST['category']),
                        'fullname' => mysqli_real_escape_string($conn, $_POST['fullname']),
                        'designation' => mysqli_real_escape_string($conn, $_POST['designation']),
                        'department' => mysqli_real_escape_string($conn, $_POST['department']),
                        'mobile' => mysqli_real_escape_string($conn, $_POST['mobile']),
                        'email' => mysqli_real_escape_string($conn, $_POST['email']),
                        'password' => mysqli_real_escape_string($conn, $_POST['password'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addUser($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // PARTS
            elseif($_POST['interact'] == 'parts'){
                if(!empty($_POST['parts_name']) && !empty($_POST['category']) && !empty($_POST['subcategory']) && !empty($_POST['subcategory_2']) && !empty($_POST['type']) && !empty($_POST['group']) && !empty($_POST['inv_type']) && !empty($_POST['unit']) && $_POST['alert_qty'] !== '' && $_POST['opening_qty'] !== '' && $_POST['opening_value'] !== ''){
                    $input = [
                        'parts_name' => mysqli_real_escape_string($conn, $_POST['parts_name']),
                        'parts_nickname' => mysqli_real_escape_string($conn, $_POST['parts_nickname']),
                        'category' => mysqli_real_escape_string($conn, $_POST['category']),
                        'subcategory' => mysqli_real_escape_string($conn, $_POST['subcategory']),
                        'subcategory_2' => mysqli_real_escape_string($conn, $_POST['subcategory_2']),
                        'type' => mysqli_real_escape_string($conn, $_POST['type']),
                        'group' => mysqli_real_escape_string($conn, $_POST['group']),
                        'inv_type' => mysqli_real_escape_string($conn, $_POST['inv_type']),
                        'unit' => mysqli_real_escape_string($conn, $_POST['unit']),
                        'alert_qty' => mysqli_real_escape_string($conn, $_POST['alert_qty']),
                        'opening_qty' => mysqli_real_escape_string($conn, $_POST['opening_qty']),
                        'opening_value' => mysqli_real_escape_string($conn, $_POST['opening_value']),
                        'parts_image' => mysqli_real_escape_string($conn, $_POST['parts_image']),
                        'remarks' => mysqli_real_escape_string($conn, $_POST['remarks'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addParts($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // PARTY
            elseif($_POST['interact'] == 'party'){
                if(!empty($_POST['party_name'])){
                    $input = [
                        'party_name' => mysqli_real_escape_string($conn, $_POST['party_name']),
                        'mobile' => mysqli_real_escape_string($conn, $_POST['mobile']),
                        'address' => mysqli_real_escape_string($conn, $_POST['address']),
                        'opening_ledger_balance' => mysqli_real_escape_string($conn, $_POST['opening_ledger_balance']),
                        'remarks' => mysqli_real_escape_string($conn, $_POST['remarks'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addParty($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // PARTY LEDGER
            elseif($_POST['interact'] == 'payment'){
                if(!empty($_POST['payment'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'payment' => mysqli_real_escape_string($conn, $_POST['payment'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addPartyLedger($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE REQUISITION
            elseif($_POST['interact'] == 'requisition'){
                if(!empty($_POST['requisition_data'])){
                    $inter = new Interaction();
                    echo $inter->addConRequisition($_POST['requisition_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE REQUISITION
            elseif($_POST['interact'] == 'requisition2'){
                if(!empty($_POST['requisition_data'])){
                    $inter = new Interaction();
                    echo $inter->addSprRequisition($_POST['requisition_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE PURCHASE
            elseif($_POST['interact'] == 'purchase_con'){
                if(!empty($_POST['purchase_data'])){
                    $inter = new Interaction();
                    echo $inter->addConPurchase($_POST['requisition_id'], $_POST['requisitioned_parts'], $_POST['purchased_parts'], $_POST['purchase_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE PURCHASE
            elseif($_POST['interact'] == 'purchase_spr'){
                if(!empty($_POST['purchase_data'])){
                    $inter = new Interaction();
                    echo $inter->addSprPurchase($_POST['requisition_id'], $_POST['requisitioned_parts'], $_POST['purchased_parts'], $_POST['purchase_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE LOAN
            elseif($_POST['interact'] == 'loan_con'){
                if(!empty($_POST['loan_data'])){
                    $inter = new Interaction();
                    echo $inter->addConLoan($_POST['requisition_id'], $_POST['requisitioned_parts'], $_POST['borrowed_parts'], $_POST['loan_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE LOAN
            elseif($_POST['interact'] == 'loan_spr'){
                if(!empty($_POST['loan_data'])){
                    $inter = new Interaction();
                    echo $inter->addSprLoan($_POST['requisition_id'], $_POST['requisitioned_parts'], $_POST['borrowed_parts'], $_POST['loan_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE LOAN REPAY
            elseif($_POST['interact'] == 'loan_repay_con'){
                if(!empty($_POST['repay_qty']) && !empty($_POST['repay_date'])){
                    $input = [
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'party_id' => mysqli_real_escape_string($conn, $_POST['party_id']),
                        'req_qty' => mysqli_real_escape_string($conn, $_POST['req_qty']),
                        'borrow_qty' => mysqli_real_escape_string($conn, $_POST['borrow_qty']),
                        'borrow_date' => mysqli_real_escape_string($conn, $_POST['borrow_date']),
                        'repay_qty' => mysqli_real_escape_string($conn, $_POST['repay_qty']),
                        'repay_date' => mysqli_real_escape_string($conn, $_POST['repay_date']),
                        'loan_id' => mysqli_real_escape_string($conn, $_POST['loan_id']),
                        'loan_data_id' => mysqli_real_escape_string($conn, $_POST['loan_data_id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addConLoanRepay($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE LOAN REPAY
            elseif($_POST['interact'] == 'loan_repay_spr'){
                if(!empty($_POST['repay_qty']) && !empty($_POST['repay_date'])){
                    $input = [
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'party_id' => mysqli_real_escape_string($conn, $_POST['party_id']),
                        'req_qty' => mysqli_real_escape_string($conn, $_POST['req_qty']),
                        'borrow_qty' => mysqli_real_escape_string($conn, $_POST['borrow_qty']),
                        'borrow_date' => mysqli_real_escape_string($conn, $_POST['borrow_date']),
                        'repay_qty' => mysqli_real_escape_string($conn, $_POST['repay_qty']),
                        'repay_date' => mysqli_real_escape_string($conn, $_POST['repay_date']),
                        'loan_id' => mysqli_real_escape_string($conn, $_POST['loan_id']),
                        'loan_data_id' => mysqli_real_escape_string($conn, $_POST['loan_data_id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addSprLoanRepay($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE PURCHASE BILL
            elseif($_POST['interact'] == 'bill_con'){
                if(!empty($_POST['purchase_id'])){
                    $input = [
                        'purchase_id' => mysqli_real_escape_string($conn, $_POST['purchase_id']),
                        'purchase_data_id' => mysqli_real_escape_string($conn, $_POST['purchase_data_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'party_id' => mysqli_real_escape_string($conn, $_POST['party_id']),
                        'required_for' => mysqli_real_escape_string($conn, $_POST['required_for'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addConPurchaseBill($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE PURCHASE BILL
            elseif($_POST['interact'] == 'bill_spr'){
                if(!empty($_POST['purchase_id'])){
                    $input = [
                        'purchase_id' => mysqli_real_escape_string($conn, $_POST['purchase_id']),
                        'purchase_data_id' => mysqli_real_escape_string($conn, $_POST['purchase_data_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'party_id' => mysqli_real_escape_string($conn, $_POST['party_id']),
                        'required_for' => mysqli_real_escape_string($conn, $_POST['required_for'])
                    ];

                    $inter = new Interaction();
                    echo $inter->addSprPurchaseBill($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }
        }

        // UPDATE
        elseif($_POST['interact_type'] == 'update'){
            // USER
            if($_POST['interact'] == 'user'){
                if((($_POST['id'] > 1 && !empty($_POST['category'])) || ($_POST['id'] == 1 && empty($_POST['category']))) && !empty($_POST['fullname']) && !empty($_POST['email'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'category' => mysqli_real_escape_string($conn, $_POST['category']),
                        'fullname' => mysqli_real_escape_string($conn, $_POST['fullname']),
                        'designation' => mysqli_real_escape_string($conn, $_POST['designation']),
                        'department' => mysqli_real_escape_string($conn, $_POST['department']),
                        'mobile' => mysqli_real_escape_string($conn, $_POST['mobile']),
                        'email' => mysqli_real_escape_string($conn, $_POST['email']),
                        'password' => mysqli_real_escape_string($conn, $_POST['password'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateUser($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // PARTS
            elseif($_POST['interact'] == 'parts'){
                if(!empty($_POST['parts_name']) && !empty($_POST['category']) && !empty($_POST['subcategory']) && !empty($_POST['subcategory_2']) && !empty($_POST['type']) && !empty($_POST['group']) && !empty($_POST['inv_type']) && !empty($_POST['unit']) && $_POST['alert_qty'] !== ''){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'parts_name' => mysqli_real_escape_string($conn, $_POST['parts_name']),
                        'parts_nickname' => mysqli_real_escape_string($conn, $_POST['parts_nickname']),
                        'category' => mysqli_real_escape_string($conn, $_POST['category']),
                        'subcategory' => mysqli_real_escape_string($conn, $_POST['subcategory']),
                        'subcategory_2' => mysqli_real_escape_string($conn, $_POST['subcategory_2']),
                        'type' => mysqli_real_escape_string($conn, $_POST['type']),
                        'group' => mysqli_real_escape_string($conn, $_POST['group']),
                        'inv_type' => mysqli_real_escape_string($conn, $_POST['inv_type']),
                        'unit' => mysqli_real_escape_string($conn, $_POST['unit']),
                        'alert_qty' => mysqli_real_escape_string($conn, $_POST['alert_qty']),
                        'parts_image' => mysqli_real_escape_string($conn, $_POST['parts_image']),
                        'remarks' => mysqli_real_escape_string($conn, $_POST['remarks'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateParts($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // PARTY
            elseif($_POST['interact'] == 'party'){
                if(!empty($_POST['party_name'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'party_name' => mysqli_real_escape_string($conn, $_POST['party_name']),
                        'mobile' => mysqli_real_escape_string($conn, $_POST['mobile']),
                        'address' => mysqli_real_escape_string($conn, $_POST['address']),
                        'remarks' => mysqli_real_escape_string($conn, $_POST['remarks'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateParty($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // INVENTORY - RECEIVE
            elseif($_POST['interact'] == 'receive_parts_qty'){
                if(!empty($_POST['action_date']) && !empty($_POST['qty']) && !empty($_POST['price'])){
                    $input = [
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'action_date' => mysqli_real_escape_string($conn, $_POST['action_date']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'source' => 1
                    ];

                    $inter = new Interaction();
                    echo $inter->preReceivePartsQty($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // INVENTORY - ISSUE
            elseif($_POST['interact'] == 'issue_parts_qty'){
                if(!empty($_POST['required_for']) && !empty($_POST['action_date']) && !empty($_POST['qty'])){
                    $input = [
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'required_for' => mysqli_real_escape_string($conn, $_POST['required_for']),
                        'action_date' => mysqli_real_escape_string($conn, $_POST['action_date']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'source' => 2
                    ];

                    $inter = new Interaction();
                    echo $inter->issuePartsQty($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // INVENTORY - RECEIVE
            elseif($_POST['interact'] == 'inv_receive'){
                if(!empty($_POST['required_for']) && !empty($_POST['action_date']) && !empty($_POST['qty']) && !empty($_POST['price'])){
                    $input = [
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'source' => mysqli_real_escape_string($conn, $_POST['source']),
                        'required_for' => mysqli_real_escape_string($conn, $_POST['required_for']),
                        'action_date' => mysqli_real_escape_string($conn, $_POST['action_date']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price'])
                    ];

                    $inventory_history_id = mysqli_real_escape_string($conn, $_POST['inventory_history_id']);

                    $inter = new Interaction();
                    echo $inter->receivePartsQty($input, $inventory_history_id);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // INVENTORY - ISSUE
            elseif($_POST['interact'] == 'inv_issue'){
                if(!empty($_POST['required_for']) && !empty($_POST['action_date']) && !empty($_POST['qty']) && !empty($_POST['price'])){
                    $input = [
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'source' => mysqli_real_escape_string($conn, $_POST['source']),
                        'required_for' => mysqli_real_escape_string($conn, $_POST['required_for']),
                        'action_date' => mysqli_real_escape_string($conn, $_POST['action_date']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price'])
                    ];

                    $inventory_history_id = mysqli_real_escape_string($conn, $_POST['inventory_history_id']);

                    $inter = new Interaction();
                    echo $inter->issuePartsQty($input, $inventory_history_id);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE REQUISITION
            elseif($_POST['interact'] == 'requisition'){
                if(!empty($_POST['requisition_data'])){
                    $inter = new Interaction();
                    echo $inter->updateConRequisition($_POST['requisition_id'], $_POST['approval_status'], $_POST['requisition_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE REQUISITION
            elseif($_POST['interact'] == 'requisition2'){
                if(!empty($_POST['requisition_data'])){
                    $inter = new Interaction();
                    echo $inter->updateSprRequisition($_POST['requisition_id'], $_POST['approval_status'], $_POST['requisition_data']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // CONSUMABLE REQUISITION APPROVAL
            elseif($_POST['interact'] == 'requisition_approval'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->approveConRequisition($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE REQUISITION APPROVAL
            elseif($_POST['interact'] == 'requisition_approval2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->approveSprRequisition($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE REQUISITION REJECT
            elseif($_POST['interact'] == 'requisition_rejection'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->rejectConRequisition($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE REQUISITION REJECT
            elseif($_POST['interact'] == 'requisition_rejection2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->rejectSprRequisition($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE PURCHASE DATA
            elseif($_POST['interact'] == 'purchase_data'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'purchase_id' => mysqli_real_escape_string($conn, $_POST['purchase_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'party' => mysqli_real_escape_string($conn, $_POST['party']),
                        'gate_no' => mysqli_real_escape_string($conn, $_POST['gate_no']),
                        'challan_no' => mysqli_real_escape_string($conn, $_POST['challan_no']),
                        'challan_photo' => mysqli_real_escape_string($conn, $_POST['challan_photo']),
                        'bill_photo' => mysqli_real_escape_string($conn, $_POST['bill_photo']),
                        'purchase_date' => mysqli_real_escape_string($conn, $_POST['purchase_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateConPurchase($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE PURCHASE DATA
            elseif($_POST['interact'] == 'purchase_data2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'purchase_id' => mysqli_real_escape_string($conn, $_POST['purchase_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'party' => mysqli_real_escape_string($conn, $_POST['party']),
                        'gate_no' => mysqli_real_escape_string($conn, $_POST['gate_no']),
                        'challan_no' => mysqli_real_escape_string($conn, $_POST['challan_no']),
                        'challan_photo' => mysqli_real_escape_string($conn, $_POST['challan_photo']),
                        'bill_photo' => mysqli_real_escape_string($conn, $_POST['bill_photo']),
                        'purchase_date' => mysqli_real_escape_string($conn, $_POST['purchase_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateSprPurchase($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // MULTIPLE PURCHASE DATA
            elseif($_POST['interact'] == 'purchase_data_multi'){
                if(!empty($_POST['edit_data_arr'])){
                    $inter = new Interaction();
                    echo $inter->updatePurchaseMulti($_POST['edit_data_arr']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // MARK CONSUMABLE PURCHASE
            elseif($_POST['interact'] == 'mark_purchase'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->markConPurchase($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // MARK SPARE PURCHASE
            elseif($_POST['interact'] == 'mark_purchase2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->markSprPurchase($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE LOAN DATA
            elseif($_POST['interact'] == 'loan_data'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'loan_id' => mysqli_real_escape_string($conn, $_POST['loan_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'party' => mysqli_real_escape_string($conn, $_POST['party']),
                        'gate_no' => mysqli_real_escape_string($conn, $_POST['gate_no']),
                        'challan_no' => mysqli_real_escape_string($conn, $_POST['challan_no']),
                        'challan_photo' => mysqli_real_escape_string($conn, $_POST['challan_photo']),
                        'bill_photo' => mysqli_real_escape_string($conn, $_POST['bill_photo']),
                        'loan_date' => mysqli_real_escape_string($conn, $_POST['loan_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateConLoan($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE LOAN DATA
            elseif($_POST['interact'] == 'loan_data2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'loan_id' => mysqli_real_escape_string($conn, $_POST['loan_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'price' => mysqli_real_escape_string($conn, $_POST['price']),
                        'party' => mysqli_real_escape_string($conn, $_POST['party']),
                        'gate_no' => mysqli_real_escape_string($conn, $_POST['gate_no']),
                        'challan_no' => mysqli_real_escape_string($conn, $_POST['challan_no']),
                        'challan_photo' => mysqli_real_escape_string($conn, $_POST['challan_photo']),
                        'bill_photo' => mysqli_real_escape_string($conn, $_POST['bill_photo']),
                        'loan_date' => mysqli_real_escape_string($conn, $_POST['loan_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->updateSprLoan($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // MULTIPLE LOAN DATA
            elseif($_POST['interact'] == 'loan_data_multi'){
                if(!empty($_POST['edit_data_arr'])){
                    $inter = new Interaction();
                    echo $inter->updateLoanMulti($_POST['edit_data_arr']);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // MARK CONSUMABLE LOAN
            elseif($_POST['interact'] == 'mark_loan'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->markConLoan($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // MARK SPARE LOAN
            elseif($_POST['interact'] == 'mark_loan2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->markSprLoan($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE PURCHASE BILL
            elseif($_POST['interact'] == 'bill_con'){
                if(!empty($_POST['bill_date'])){
                    $input = [
                        'bill_date' => mysqli_real_escape_string($conn, $_POST['bill_date']),
                        'bill_id_arr' => $_POST['bill_id_arr']
                    ];

                    $inter = new Interaction();
                    echo $inter->updateConPurchaseBill($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }

            // SPARE PURCHASE BILL
            elseif($_POST['interact'] == 'bill_spr'){
                if(!empty($_POST['bill_date'])){
                    $input = [
                        'bill_date' => mysqli_real_escape_string($conn, $_POST['bill_date']),
                        'bill_id_arr' => $_POST['bill_id_arr']
                    ];

                    $inter = new Interaction();
                    echo $inter->updateSprPurchaseBill($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Please fill all the required fields.'
                    )));
                }
            }
        }

        // DELETE
        elseif($_POST['interact_type'] == 'delete'){
            // USER
            if($_POST['interact'] == 'user'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteUser($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // PARTS
            elseif($_POST['interact'] == 'parts'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteParts($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // PARTY
            elseif($_POST['interact'] == 'party'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteParty($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE REQUISITION
            elseif($_POST['interact'] == 'requisition'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteConRequisition($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE REQUISITION
            elseif($_POST['interact'] == 'requisition2'){
                if(!empty($_POST['id'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteSprRequisition($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE PURCHASE
            elseif($_POST['interact'] == 'purchase'){
                if(!empty($_POST['id']) && !empty($_POST['purchase_id']) && !empty($_POST['parts_id']) && !empty($_POST['req_for']) && !empty($_POST['qty']) && !empty($_POST['purchase_date'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'purchase_id' => mysqli_real_escape_string($conn, $_POST['purchase_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'purchase_date' => mysqli_real_escape_string($conn, $_POST['purchase_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteConPurchase($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE PURCHASE
            elseif($_POST['interact'] == 'purchase2'){
                if(!empty($_POST['id']) && !empty($_POST['purchase_id']) && !empty($_POST['parts_id']) && !empty($_POST['req_for']) && !empty($_POST['qty']) && !empty($_POST['purchase_date'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'purchase_id' => mysqli_real_escape_string($conn, $_POST['purchase_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'purchase_date' => mysqli_real_escape_string($conn, $_POST['purchase_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteSprPurchase($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // CONSUMABLE LOAN
            elseif($_POST['interact'] == 'loan'){
                if(!empty($_POST['id']) && !empty($_POST['loan_id']) && !empty($_POST['parts_id']) && !empty($_POST['req_for']) && !empty($_POST['qty']) && !empty($_POST['loan_date'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'loan_id' => mysqli_real_escape_string($conn, $_POST['loan_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'loan_date' => mysqli_real_escape_string($conn, $_POST['loan_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteConLoan($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }

            // SPARE LOAN
            elseif($_POST['interact'] == 'loan2'){
                if(!empty($_POST['id']) && !empty($_POST['loan_id']) && !empty($_POST['parts_id']) && !empty($_POST['req_for']) && !empty($_POST['qty']) && !empty($_POST['loan_date'])){
                    $input = [
                        'id' => mysqli_real_escape_string($conn, $_POST['id']),
                        'loan_id' => mysqli_real_escape_string($conn, $_POST['loan_id']),
                        'parts_id' => mysqli_real_escape_string($conn, $_POST['parts_id']),
                        'req_for' => mysqli_real_escape_string($conn, $_POST['req_for']),
                        'qty' => mysqli_real_escape_string($conn, $_POST['qty']),
                        'loan_date' => mysqli_real_escape_string($conn, $_POST['loan_date'])
                    ];

                    $inter = new Interaction();
                    echo $inter->deleteSprLoan($input);
                } else{
                    exit(json_encode(array(
                        'Type' => 'error',
                        'Reply' => 'Something went wrong.'
                    )));
                }
            }
        }
    }
?>