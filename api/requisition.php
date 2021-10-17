<?php
    use Requisition\Requisition;

    require_once('connection.php');
    require_once('model/Requisition.php');
    
    if($_POST['requisition_data_type'] == 'fetch_all'){
        $requisition = new Requisition();
        echo $requisition->fetch_all(mysqli_real_escape_string($conn, $_POST['user_id']), mysqli_real_escape_string($conn, $_POST['user_category']));
    } elseif($_POST['requisition_data_type'] == 'fetch_consumable'){
        if(!empty($_POST['requisition_id'])){
            $requisition = new Requisition();
            echo $requisition->fetch_consumable(mysqli_real_escape_string($conn, $_POST['requisition_id']));
        }
    } elseif($_POST['requisition_data_type'] == 'fetch_spare'){
        if(!empty($_POST['requisition_id'])){
            $requisition = new Requisition();
            echo $requisition->fetch_spare(mysqli_real_escape_string($conn, $_POST['requisition_id']));
        }
    } elseif($_POST['requisition_data_type'] == 'fetch_requisition_num'){
        $requisition = new Requisition();
        echo $requisition->fetch_requisition_num();
    }
?>