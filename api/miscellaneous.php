<?php
    use Miscellaneous\Miscellaneous;

    require_once('connection.php');
    require_once('model/Miscellaneous.php');
    
    if($_POST['miscellaneous_data_type'] == 'fetch_req_for_by_srch_str'){
        $miscellaneous = new Miscellaneous();
        echo $miscellaneous->fetch_req_for_by_srch_str(mysqli_real_escape_string($conn, $_POST['search_str']));
    } elseif($_POST['miscellaneous_data_type'] == 'fetch_status_by_srch_str'){
        $miscellaneous = new Miscellaneous();
        echo $miscellaneous->fetch_status_by_srch_str(mysqli_real_escape_string($conn, $_POST['search_str']));
    }
?>