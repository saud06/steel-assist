<?php
    use Party\Party;

    require_once('connection.php');
    require_once('model/Party.php');
    
    if($_POST['party_data_type'] == 'fetch'){
        if(!empty($_POST['party_id'])){
            $party = new Party();
            echo $party->fetch(mysqli_real_escape_string($conn, $_POST['party_id']));
        }
    } elseif($_POST['party_data_type'] == 'fetch_all'){
        $party = new Party();
        echo $party->fetch_all();
    } elseif($_POST['party_data_type'] == 'fetch_all_by_srch_str'){
        $party = new Party();
        echo $party->fetch_all_by_srch_str(mysqli_real_escape_string($conn, $_POST['search_str']));
    } elseif($_POST['party_data_type'] == 'fetch_ledger'){
        if(!empty($_POST['party_id'])){
            $party = new Party();
            echo $party->fetch_party_ledger(mysqli_real_escape_string($conn, $_POST['party_id']));
        }
    }
?>