<?php
    use Inventory\Inventory;

    require_once('connection.php');
    require_once('model/Inventory.php');

    if($_POST['inventory_data_type'] == 'fetch_tot_receive_against_purchase'){
        $inventory = new Inventory();
        echo $inventory->fetch_tot_receive_against_purchase();
    } elseif($_POST['inventory_data_type'] == 'inventory_parts_receive'){
        $inventory = new Inventory();
        echo $inventory->inventory_parts_receive(mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['action_date']));
    } elseif($_POST['inventory_data_type'] == 'inventory_parts_issue'){
        $inventory = new Inventory();
        echo $inventory->inventory_parts_issue(mysqli_real_escape_string($conn, $_POST['parts_id']), mysqli_real_escape_string($conn, $_POST['action_date']));
    } elseif($_POST['inventory_data_type'] == 'inventory_parts_tot_rcv_iss_qty_val'){
        $inventory = new Inventory();
        echo $inventory->inventory_parts_tot_rcv_iss_qty_val();
    } elseif($_POST['inventory_data_type'] == 'inventory_summary_list'){
        $inventory = new Inventory();
        echo $inventory->inventory_summary_list(mysqli_real_escape_string($conn, $_POST['alpha']));
    } elseif($_POST['inventory_data_type'] == 'inventory_history_list'){
        $inventory = new Inventory();
        echo $inventory->inventory_history_list();
    } elseif($_POST['inventory_data_type'] == 'fetch_parts_date_validity'){
        $inventory = new Inventory();
        echo $inventory->fetch_parts_date_validity(mysqli_real_escape_string($conn, $_POST['parts_id']));
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_date'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_date(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['required_for']));
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_info(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['required_for']));
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_stock_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_stock_info();
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_stock_date'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_stock_date();
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_stock_date_2'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_stock_date_2(mysqli_real_escape_string($conn, $_POST['type']));
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_overall_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_overall_info();
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_overall_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_overall_details();
    } elseif($_POST['inventory_data_type'] == 'fetch_specific_parts_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_specific_parts_info(mysqli_real_escape_string($conn, $_POST['required_for']));
    }  elseif($_POST['inventory_data_type'] == 'fetch_specific_parts_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_specific_parts_details(mysqli_real_escape_string($conn, $_POST['required_for']));
    } elseif($_POST['inventory_data_type'] == 'fetch_all_parts_summary_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_all_parts_summary_details(mysqli_real_escape_string($conn, $_POST['curr_mon_status']));
    } elseif($_POST['inventory_data_type'] == 'fetch_issued_parts_summary_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_issued_parts_summary_details();
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_date'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_date(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['parts']), mysqli_real_escape_string($conn, $_POST['date_range']), mysqli_real_escape_string($conn, $_POST['required_for']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_info(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['parts']), mysqli_real_escape_string($conn, $_POST['date_range']), mysqli_real_escape_string($conn, $_POST['required_for']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_stock_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_stock_info(mysqli_real_escape_string($conn, $_POST['parts']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_stock_date'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_stock_date(mysqli_real_escape_string($conn, $_POST['parts']), mysqli_real_escape_string($conn, $_POST['date_range']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_stock_date_2'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_stock_date_2(mysqli_real_escape_string($conn, $_POST['type']), mysqli_real_escape_string($conn, $_POST['parts']), mysqli_real_escape_string($conn, $_POST['date_range']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_overall_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_overall_info(mysqli_real_escape_string($conn, $_POST['parts']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_overall_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_overall_details(mysqli_real_escape_string($conn, $_POST['parts']), mysqli_real_escape_string($conn, $_POST['date_range']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_specific_parts_info'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_specific_parts_info(mysqli_real_escape_string($conn, $_POST['date_range']), mysqli_real_escape_string($conn, $_POST['required_for']));
    }  elseif($_POST['inventory_data_type'] == 'fetch_filtered_specific_parts_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_specific_parts_details(mysqli_real_escape_string($conn, $_POST['date_range']), mysqli_real_escape_string($conn, $_POST['required_for']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_parts_summary_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_parts_summary_details(mysqli_real_escape_string($conn, $_POST['date_range']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_issued_parts_summary_details'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_issued_parts_summary_details(mysqli_real_escape_string($conn, $_POST['date_range']));
    } elseif($_POST['inventory_data_type'] == 'fetch_transaction'){
        $inventory = new Inventory();
        echo $inventory->fetch_transaction(mysqli_real_escape_string($conn, $_POST['tran_type']));
    } elseif($_POST['inventory_data_type'] == 'fetch_filtered_transaction'){
        $inventory = new Inventory();
        echo $inventory->fetch_filtered_transaction(mysqli_real_escape_string($conn, $_POST['parts_nickname']), mysqli_real_escape_string($conn, $_POST['tran_type']));
    }
?>