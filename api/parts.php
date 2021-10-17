<?php
    use Parts\Parts;

    require_once('connection.php');
    require_once('model/Parts.php');
    
    if($_POST['parts_data_type'] == 'fetch'){
        if(!empty($_POST['parts_id'])){
            $parts = new Parts();
            echo $parts->fetch(mysqli_real_escape_string($conn, $_POST['parts_id']));
        }
    } elseif($_POST['parts_data_type'] == 'fetch_all'){
        $parts = new Parts();
        echo $parts->fetch_all();
    } elseif($_POST['parts_data_type'] == 'fetch_all_by_srch_str'){
        $parts = new Parts();
        echo $parts->fetch_all_by_srch_str(mysqli_real_escape_string($conn, $_POST['parts_category']), mysqli_real_escape_string($conn, $_POST['search_str']));
    }
?>