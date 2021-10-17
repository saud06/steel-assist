<?php
    use Purchase\Purchase;

    require_once('connection.php');
    require_once('model/Purchase.php');
    
    if($_POST['purchase_data_type'] == 'fetch_all'){
        $purchase = new Purchase();
        echo $purchase->fetch_all(mysqli_real_escape_string($conn, $_POST['user_id']), mysqli_real_escape_string($conn, $_POST['user_category']));
    } elseif($_POST['purchase_data_type'] == 'fetch_requisition_con'){
        if(!empty($_POST['requisition_id'])){
            $purchase = new Purchase();
            echo $purchase->fetch_requisition_con(mysqli_real_escape_string($conn, $_POST['requisition_id']));
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_requisition_spr'){
        if(!empty($_POST['requisition_id'])){
            $purchase = new Purchase();
            echo $purchase->fetch_requisition_spr(mysqli_real_escape_string($conn, $_POST['requisition_id']));
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_purchase_con'){
        if(!empty($_POST['requisition_id'])){
            $purchase = new Purchase();

            if(isset($_POST['party_id'])){
                echo $purchase->fetch_purchase_con(mysqli_real_escape_string($conn, $_POST['requisition_id']), mysqli_real_escape_string($conn, $_POST['party_id']));
            } else{
                echo $purchase->fetch_purchase_con(mysqli_real_escape_string($conn, $_POST['requisition_id']));
            }
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_purchase_spr'){
        if(!empty($_POST['requisition_id'])){
            $purchase = new Purchase();
           
            if(isset($_POST['party_id'])){
                echo $purchase->fetch_purchase_spr(mysqli_real_escape_string($conn, $_POST['requisition_id']), mysqli_real_escape_string($conn, $_POST['party_id']));
            } else{
                echo $purchase->fetch_purchase_spr(mysqli_real_escape_string($conn, $_POST['requisition_id']));
            }
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_filtered_purchase'){
        if(!empty($_POST['type'])){
            $purchase = new Purchase();

            if(isset($_POST['date_range'])){
                echo $purchase->fetch_filtered_purchase(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname']), mysqli_real_escape_string($conn, $_POST['date_range']));
            } else{
               echo $purchase->fetch_filtered_purchase(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname'])); 
            }
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_tot_purchase_against_requisition'){
        $purchase = new Purchase();
        echo $purchase->fetch_tot_purchase_against_requisition();
    } elseif($_POST['purchase_data_type'] == 'fetch_purchase_bill_con'){
        if(!empty($_POST['purchase_id'])){
            $purchase = new Purchase();
            echo $purchase->fetch_purchase_bill_con(mysqli_real_escape_string($conn, $_POST['purchase_id']), mysqli_real_escape_string($conn, $_POST['party_id']));
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_purchase_bill_spr'){
        if(!empty($_POST['purchase_id'])){
            $purchase = new Purchase();
            echo $purchase->fetch_purchase_bill_spr(mysqli_real_escape_string($conn, $_POST['purchase_id']), mysqli_real_escape_string($conn, $_POST['party_id']));
        }
    } elseif($_POST['purchase_data_type'] == 'fetch_filtered_bill'){
        if(!empty($_POST['type'])){
            $purchase = new Purchase();

            if($_POST['type'] == 2){
                if(isset($_POST['date_range'])){
                    echo $purchase->fetch_filtered_bill(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['availability']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname']), mysqli_real_escape_string($conn, $_POST['date_range']));
                } else{
                   echo $purchase->fetch_filtered_bill(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['availability']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname'])); 
                }
            } else{
                if(isset($_POST['date_range'])){
                    echo $purchase->fetch_filtered_bill(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname']), mysqli_real_escape_string($conn, $_POST['date_range']));
                } else{
                   echo $purchase->fetch_filtered_bill(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname'])); 
                }
            }
        }
    }
?>