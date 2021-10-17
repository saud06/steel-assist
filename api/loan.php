<?php
    use Loan\Loan;

    require_once('connection.php');
    require_once('model/Loan.php');
    
    if($_POST['loan_data_type'] == 'fetch_all'){
        $loan = new Loan();
        echo $loan->fetch_all(mysqli_real_escape_string($conn, $_POST['user_id']), mysqli_real_escape_string($conn, $_POST['user_category']));
    } elseif($_POST['loan_data_type'] == 'fetch_requisition_con'){
        if(!empty($_POST['requisition_id'])){
            $loan = new Loan();
            echo $loan->fetch_requisition_con(mysqli_real_escape_string($conn, $_POST['requisition_id']));
        }
    } elseif($_POST['loan_data_type'] == 'fetch_requisition_spr'){
        if(!empty($_POST['requisition_id'])){
            $loan = new Loan();
            echo $loan->fetch_requisition_spr(mysqli_real_escape_string($conn, $_POST['requisition_id']));
        }
    } elseif($_POST['loan_data_type'] == 'fetch_loan_con'){
        if(!empty($_POST['requisition_id'])){
            $loan = new Loan();

            if(isset($_POST['party_id'])){
                echo $loan->fetch_loan_con(mysqli_real_escape_string($conn, $_POST['requisition_id']), mysqli_real_escape_string($conn, $_POST['party_id']));
            } else{
                echo $loan->fetch_loan_con(mysqli_real_escape_string($conn, $_POST['requisition_id']));
            }
        }
    } elseif($_POST['loan_data_type'] == 'fetch_loan_spr'){
        if(!empty($_POST['requisition_id'])){
            $loan = new Loan();
           
            if(isset($_POST['party_id'])){
                echo $loan->fetch_loan_spr(mysqli_real_escape_string($conn, $_POST['requisition_id']), mysqli_real_escape_string($conn, $_POST['party_id']));
            } else{
                echo $loan->fetch_loan_spr(mysqli_real_escape_string($conn, $_POST['requisition_id']));
            }
        }
    } elseif($_POST['loan_data_type'] == 'fetch_filtered_loan'){
        if(!empty($_POST['type'])){
            $loan = new Loan();

            if(isset($_POST['date_range'])){
                echo $loan->fetch_filtered_loan(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname']), mysqli_real_escape_string($conn, $_POST['date_range']));
            } else{
               echo $loan->fetch_filtered_loan(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['party_id']), mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_nickname'])); 
            }
        }
    } elseif($_POST['loan_data_type'] == 'fetch_parts_loan_data'){
        if(!empty($_POST['parts_id']) && !empty($_POST['parts_cat'])){
            $loan = new Loan();
            echo $loan->fetch_parts_loan_data(mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_cat']));
        }
    } elseif($_POST['loan_data_type'] == 'fetch_parts_loan_repay_history_data'){
        if(!empty($_POST['parts_id']) && !empty($_POST['parts_cat'])){
            $loan = new Loan();
            echo $loan->fetch_parts_loan_repay_history_data(mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['parts_cat']));
        }
    }
?>