<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Inventory History</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 3];
            if(!in_array($user_category, $user_categories)){
                header('location: ../dashboard');
            }
        ?>

        <style type="text/css">
            .action-type, .req-for, .qty, .price, .action-date{
                width: 160px;
                display: inline-block;
                text-align: center;
                height: 34px;
                margin-right: 5px;
            }

            .save{
                height: 32px;
                margin-left: 5px;
            }

            .datatable > tbody > tr > td{
                vertical-align: middle;
            }
        </style>
    </head>

    <body>
        <!-- Navigation Bar-->
        <header id="topnav">
            <!-- Topbar Start -->
            <?php include('../../topbar-for-sub-page.php'); ?>
            <!-- end Topbar -->

            <?php include('../../navbar-for-sub-page.php'); ?>
            <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->

        <div class="wrapper full-width-background">   
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Inventory List - History</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-history"></i> Inventory List - History</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
	                        <div class="row">
	                            <div class="col-12">
	                                <div class="card">
	                                    <div class="card-body">
	                                        <table class="table datatable w-100 nowrap cell-border text-center">
	                                            <thead style="color: #fff; background-color: #5089de;">
	                                                <tr>
	                                                    <th style="min-width: 80px"><i class="mdi mdi-pencil"></i></th>
	                                                    <th>Receive / Issue<br>Date</th>
	                                                    <th>Parts<br>Name</th>
	                                                    <th>Parts<br>Unit</th>
	                                                    <th>Opening<br>Qty.</th>
	                                                    <th>Opening<br>Value</th>
	                                                    <th>Parts<br>Rate</th>
	                                                    <th>Parts<br>Avg. Rate</th>
	                                                    <th style="min-width: 120px">Required<br>For</th>
	                                                    <th style="min-width: 100px">Received<br>Qty.</th>
	                                                    <th style="min-width: 120px">Received<br>Value</th>
	                                                    <th style="min-width: 100px">Issued<br>Qty.</th>
	                                                    <th style="min-width: 120px">Issued<br>Value</th>
	                                                    <th>Closing<br>Qty.</th>
	                                                    <th>Closing<br>Value</th>
	                                                    <th>Action<br>Datetime</th>
	                                                    <th>Updated<br>By</th>
	                                                </tr>
	                                            </thead>
	                                            <tfoot style="color: #fff; background-color: #5089de;">
	                                                <tr>
	                                                    <th><i class="mdi mdi-pencil"></i></th>
	                                                    <th>Receive / Issue<br>Date</th>
	                                                    <th>Parts<br>Name</th>
	                                                    <th>Parts<br>Unit</th>
	                                                    <th>Opening<br>Qty.</th>
	                                                    <th>Opening<br>Value</th>
	                                                    <th>Parts<br>Rate</th>
	                                                    <th>Parts<br>Avg. Rate</th>
	                                                    <th>Required<br>For</th>
	                                                    <th>Received<br>Qty.</th>
	                                                    <th>Received<br>Value</th>
	                                                    <th>Issued<br>Qty.</th>
	                                                    <th>Issued<br>Value</th>
	                                                    <th>Closing<br>Qty.</th>
	                                                    <th>Closing<br>Value</th>
	                                                    <th>Action<br>Datetime</th>
	                                                    <th>Updated<br>By</th>
	                                                </tr>
	                                            </tfoot>
	                                        </table>
	                                    </div> <!-- end card-body -->
	                                </div> <!-- end card -->
	                            </div> <!-- end col -->
	                        </div>
                        </div> <!-- end card-box-->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->

        <!-- Footer Start -->
        <?php require_once('../../footer-for-sub-page.php'); ?>
        <!-- end Footer -->

        <!-- Custom js -->
        <script type="text/javascript">
            $(document).ready(function(){
                // FETCH AND DISPLAY INVENTORY HISTORY DATA
                let t;

                Swal.fire({
                    title: 'Fetching History Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/inventory',
                        method: 'post',
                        data: {
                            inventory_data_type: 'inventory_history_list'
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(result){
                            if(result.Type == 'success'){
                                $('.datatable').DataTable({
                                    stateSave: !0,
                                    scrollX: !0,
                                    language: {
                                        paginate: {
                                            previous: '<i class="mdi mdi-chevron-left">',
                                            next: '<i class="mdi mdi-chevron-right">'
                                        }
                                    },
                                    drawCallback: function(){
                                        $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                                    },
                                    data: result.Reply,
                                    columns: [
                                        { data: 'action' },
                                        { data: 'history_date' },
                                        { data: 'parts_name' },
                                        { data: 'parts_unit' },
                                        { data: 'opening_qty' },
                                        { data: 'opening_value' },
                                        { data: 'parts_rate' },
                                        { data: 'parts_avg_rate' },
                                        { data: 'required_for' },
                                        { data: 'received_qty' },
                                        { data: 'received_value' },
                                        { data: 'issued_qty' },
                                        { data: 'issued_value' },
                                        { data: 'closing_qty' },
                                        { data: 'closing_value' },
                                        { data: 'inventory_history_created' },
                                        { data: 'user_fullname' }
                                    ]
                                });
                            } else if(data.Type == 'error'){
                                let table = $('.datatable').DataTable();

                                table.destroy();
                                    
                                $('.datatable').DataTable({
                                    stateSave: !0,
                                    scrollX: !0,
                                    language: {
                                        paginate: {
                                            previous: '<i class="mdi mdi-chevron-left">',
                                            next: '<i class="mdi mdi-chevron-right">'
                                        }
                                    },
                                    drawCallback: function(){
                                        $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                                    }
                                });
                            } else{
                                Swal.fire({
                                    title: 'Info',
                                    text: 'Server is under maintenance. Please try again later!',
                                    type: 'info',
                                    width: 450,
                                    showCloseButton: true,
                                    confirmButtonColor: '#5cb85c',
                                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                });
                            }

                            return false;
                        }
                    });
                });
            });

            // VALIDATE ACTION DATE FIELD
            function validate_action_date(ele){
                $(ele).closest('tr').find('td').find('.action-date').val('');
            }

            // EDIT
            let max_parts_qty = 0;

            function edit_btn(ele, ele2, ele3){
                $('.edt-btn').css({'pointer-events': 'none', 'opacity': 0.5});

                $(ele).closest('tr').find('.edt-btn, .data-span').addClass('d-none');
                $(ele).closest('tr').find('.cncl-btn, .upd-btn, .data-input').removeClass('d-none');

                let parts_id = ele3,
                    action_date = $(ele).closest('tr').find('td:eq(1)').find('span').html(),
                    req_for = $(ele).closest('tr').find('td:eq(8)').find('span').html(),
                    action_type = ele2,
                    qty = ((ele2 == 1) ? $(ele).closest('tr').find('td:eq(9)').find('span').html() : $(ele).closest('tr').find('td:eq(11)').find('span').html()),
                    price = ((ele2 == 1) ? $(ele).closest('tr').find('td:eq(10)').find('span').html() : $(ele).closest('tr').find('td:eq(12)').find('span').html());

                if(req_for.trim() == 'BCP-CCM')
                    req_for = 1;
                else if(req_for.trim() == 'BCP-Furnace')
                    req_for = 2;
                else if(req_for.trim() == 'Concast-CCM')
                    req_for = 3;
                else if(req_for.trim() == 'Concast-Furnace')
                    req_for = 4;
                else if(req_for.trim() == 'HRM')
                    req_for = 5;
                else if(req_for.trim() == 'HRM Unit-2')
                    req_for = 6;
                else if(req_for.trim() == 'Lal Masjid')
                    req_for = 7;
                else if(req_for.trim() == 'Sonargaon')
                    req_for = 8;
                else if(req_for.trim() == 'General')
                    req_for = 9;

                $(ele).closest('tr').find('td:eq(1)').find('input').val(action_date);
                $(ele).closest('tr').find('td:eq(8)').find('select').val(req_for);

                if(ele2 == 1){
                    $(ele).closest('tr').find('td:eq(9)').find('input').val(qty);
                    $(ele).closest('tr').find('td:eq(10)').find('input').val(price);
                } else{
                    $(ele).closest('tr').find('td:eq(11)').find('input').val(qty);
                    $(ele).closest('tr').find('td:eq(12)').find('input').val(price);
                }

                // SET MAX RECEIVE / ISSUE QTY.
                $.ajax({
                    url: '../../api/inventory',
                    method: 'post',
                    data: {
                        parts_id: parts_id,
                        required_for: req_for,
                        action_date: action_date,
                        inventory_data_type: ((action_type == 1) ? 'inventory_parts_receive' : 'inventory_parts_issue')
                    },
                    dataType: 'json',
                    cache: false,
                    async: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            if(parseFloat(data.Reply[0].parts_qty) > 0){
                                max_parts_qty = parseFloat(data.Reply[0].parts_qty) + parseFloat(qty);
                            } else{
                                max_parts_qty = parseFloat(qty);
                            }
                        }
                    }
                });
            }

            // CANCEL
            function cancel_btn(ele){
                $('.edt-btn').css({'pointer-events': 'auto', 'opacity': 1});

                $(ele).closest('tr').find('.edt-btn, .data-span').removeClass('d-none');
                $(ele).closest('tr').find('.cncl-btn, .upd-btn, .data-input').addClass('d-none');
            }

            // REQUIRED FOR
            function required_for(ele, ele2){
                let action_type = (($(ele).closest('tr').find('td').find('.rcv-qty').val() == undefined) ? 2 : 1),
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    qty = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val() : $(ele).closest('tr').find('td').find('.iss-qty').val()),
                    price = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-price').val() : $(ele).closest('tr').find('td').find('.iss-price').val()),
                    parts_id = ele2;

                if(action_date && $(ele).val() && qty && price){
                    let curr_action_date = $(ele).closest('tr').find('td:eq(1)').find('.data-span').html(),
                        curr_req_for = $(ele).closest('tr').find('td:eq(8)').find('.data-span').html();

                    if(action_date == curr_action_date && $(ele).find('option:selected').html() == curr_req_for){
                        $(ele).closest('tr').find('td').find('.upd-btn').removeClass('d-none');
                    } else{
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts_id: parts_id,
                                required_for: $(ele).val(),
                                action_date: action_date,
                                inventory_data_type: ((action_type == 1) ? 'inventory_parts_receive' : 'inventory_parts_issue')
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    if(data.Reply[0].parts_qty == null){
                                        Swal.fire({
                                            title: 'Error',
                                            text: ((action_type == 1) ? 'No purchase record found!' : 'No receive record found!'),
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: ((action_type == 1) ? 'Please purchase some before receive.' : 'Please receive some before issue.')
                                        }).then(function(){
                                            $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                                        });
                                    } else if(data.Reply[0].parts_qty <= 0){
                                        Swal.fire({
                                            title: 'Error',
                                            text: ((action_type == 1) ? 'All purchased & borrowed parts have been received!' : 'All received parts have been issued!'),
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: ((action_type == 1) ? 'Please purchase some to receive.' : 'Please receive some to issue.')
                                        }).then(function(){
                                            $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                                        });
                                    } else{
                                        max_parts_qty = parseFloat(data.Reply[0].parts_qty);

                                        if(action_type == 1){
                                            let curr_rcv_qty = $(ele).closest('tr').find('td:eq(9)').find('.data-span').html(),
                                                curr_rcv_price = $(ele).closest('tr').find('td:eq(10)').find('.data-span').html();

                                            if(parseFloat(data.Reply[0].parts_qty) > parseFloat(curr_rcv_qty)){
                                                $(ele).closest('tr').find('td').find('.rcv-qty').val(curr_rcv_qty);
                                                $(ele).closest('tr').find('td').find('.rcv-price').val(curr_rcv_price);
                                            } else{
                                                $(ele).closest('tr').find('td').find('.rcv-qty').val(data.Reply[0].parts_qty);
                                                $(ele).closest('tr').find('td').find('.rcv-price').val(data.Reply[0].parts_price.toFixed(2));
                                            }
                                        } else{
                                            let curr_iss_qty = $(ele).closest('tr').find('td:eq(11)').find('.data-span').html(),
                                                curr_iss_price = $(ele).closest('tr').find('td:eq(12)').find('.data-span').html();

                                            if(parseFloat(data.Reply[0].parts_qty) > parseFloat(curr_iss_qty)){
                                                $(ele).closest('tr').find('td').find('.iss-qty').val(curr_iss_qty);
                                                $(ele).closest('tr').find('td').find('.iss-price').val(curr_iss_price);
                                            } else{
                                                $(ele).closest('tr').find('td').find('.iss-qty').val(data.Reply[0].parts_qty);
                                                $(ele).closest('tr').find('td').find('.iss-price').val(data.Reply[0].parts_price.toFixed(2));
                                            }
                                        }

                                        $(ele).closest('tr').find('td').find('.upd-btn').removeClass('d-none');
                                    }
                                }
                            }
                        });
                    }
                } else{
                    $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                }
            }

            // ACTION DATE
            function action_date(ele, ele2){
                let action_type = (($(ele).closest('tr').find('td').find('.rcv-qty').val() == undefined) ? 2 : 1),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    qty = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val() : $(ele).closest('tr').find('td').find('.iss-qty').val()),
                    price = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-price').val() : $(ele).closest('tr').find('td').find('.iss-price').val()),
                    parts_id = ele2;

                if($(ele).val() && req_for && qty && price){
                    let curr_action_date = $(ele).closest('tr').find('td:eq(1)').find('.data-span').html(),
                        curr_req_for = $(ele).closest('tr').find('td:eq(8)').find('.data-span').html();

                    if($(ele).val() == curr_action_date && $(ele).find('option:selected').html() == curr_req_for){
                        $(ele).closest('tr').find('td').find('.upd-btn').removeClass('d-none');
                    } else{
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts_id: parts_id,
                                required_for: req_for,
                                action_date: $(ele).val(),
                                inventory_data_type: ((ele2 == 1) ? 'inventory_parts_receive' : 'inventory_parts_issue')
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    if(data.Reply[0].parts_qty == null){
                                        Swal.fire({
                                            title: 'Error',
                                            text: ((action_type == 1) ? 'No purchase record found!' : 'No receive record found!'),
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: ((action_type == 1) ? 'Please purchase some before receive.' : 'Please receive some before issue.')
                                        }).then(function(){
                                            $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                                        });
                                    } else if(data.Reply[0].parts_qty <= 0){
                                        Swal.fire({
                                            title: 'Error',
                                            text: ((action_type == 1) ? 'All purchased & borrowed parts have been received!' : 'All received parts have been issued!'),
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: ((action_type == 1) ? 'Please purchase some to receive.' : 'Please receive some to issue.')
                                        }).then(function(){
                                            $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                                        });
                                    } else{
                                        max_parts_qty = parseFloat(data.Reply[0].parts_qty);

                                        if(action_type == 1){
                                            let curr_rcv_qty = $(ele).closest('tr').find('td:eq(9)').find('.data-span').html(),
                                                curr_rcv_price = $(ele).closest('tr').find('td:eq(10)').find('.data-span').html();

                                            if(parseFloat(data.Reply[0].parts_qty) > parseFloat(curr_rcv_qty)){
                                                $(ele).closest('tr').find('td').find('.rcv-qty').val(curr_rcv_qty);
                                                $(ele).closest('tr').find('td').find('.rcv-price').val(curr_rcv_price);
                                            } else{
                                                $(ele).closest('tr').find('td').find('.rcv-qty').val(data.Reply[0].parts_qty);
                                                $(ele).closest('tr').find('td').find('.rcv-price').val(data.Reply[0].parts_price.toFixed(2));
                                            }
                                        } else{
                                            let curr_iss_qty = $(ele).closest('tr').find('td:eq(11)').find('.data-span').html(),
                                                curr_iss_price = $(ele).closest('tr').find('td:eq(12)').find('.data-span').html();

                                            if(parseFloat(data.Reply[0].parts_qty) > parseFloat(curr_iss_qty)){
                                                $(ele).closest('tr').find('td').find('.iss-qty').val(curr_iss_qty);
                                                $(ele).closest('tr').find('td').find('.iss-price').val(curr_iss_price);
                                            } else{
                                                $(ele).closest('tr').find('td').find('.iss-qty').val(data.Reply[0].parts_qty);
                                                $(ele).closest('tr').find('td').find('.iss-price').val(data.Reply[0].parts_price.toFixed(2));
                                            }
                                        }

                                        $(ele).closest('tr').find('td').find('.upd-btn').removeClass('d-none');
                                    }
                                }
                            }
                        });
                    }
                } else{
                    $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                }
            }

            // RECEIVE / ISSUE QTY
            function qty(ele){
                let action_type = (($(ele).closest('tr').find('td').find('.rcv-qty').val() == undefined) ? 2 : 1),
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    qty = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val() : $(ele).closest('tr').find('td').find('.iss-qty').val()),
                    price = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-price').val() : $(ele).closest('tr').find('td').find('.iss-price').val());

                if(!qty){
                    $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                } else if(parseFloat(qty) <= 0){
                    (action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val('') : $(ele).closest('tr').find('td').find('.iss-qty').val('');
                    $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');

                    return false;
                } else if(parseFloat(qty) > max_parts_qty){
                    (action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val(max_parts_qty.toFixed(3)) : $(ele).closest('tr').find('td').find('.iss-qty').val(max_parts_qty.toFixed(3));
                } else{
                    if(action_date && req_for && price){
                        $(ele).closest('tr').find('td').find('.upd-btn').removeClass('d-none');
                    } else{
                        $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                    }
                }
            }

            // RECEIVE / ISSUE PRICE
            function price(ele){
                let action_type = (($(ele).closest('tr').find('td').find('.rcv-qty').val() == undefined) ? 2 : 1),
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    qty = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val() : $(ele).closest('tr').find('td').find('.iss-qty').val()),
                    price = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-price').val() : $(ele).closest('tr').find('td').find('.iss-price').val());

                if(!price){
                    $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                } else if(parseFloat(price) <= 0){
                    (action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-price').val('') : $(ele).closest('tr').find('td').find('.iss-price').val('');
                    $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');

                    return false;
                } else{
                    if(action_date && req_for && qty){
                        $(ele).closest('tr').find('td').find('.upd-btn').removeClass('d-none');
                    } else{
                        $(ele).closest('tr').find('td').find('.upd-btn').addClass('d-none');
                    }
                }
            }

            // UPDATE INVENTORY
            function update_inventory(ele, ele2, ele3, ele4){
                let parts_id = ele2,
                    inventory_history_id = ele3,
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    action_type = (($(ele).closest('tr').find('td').find('.rcv-qty').val() == undefined) ? 2 : 1),
                    qty = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-qty').val() : $(ele).closest('tr').find('td').find('.iss-qty').val()),
                    price = ((action_type == 1) ? $(ele).closest('tr').find('td').find('.rcv-price').val() : $(ele).closest('tr').find('td').find('.iss-price').val()),
                    source = ele4

                if(action_date && req_for && qty && price){
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Inventory will be updated!',
                        type: 'warning',
                        showCloseButton: true,
                        showCancelButton: true,
                        confirmButtonColor: '#5cb85c',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '<i class="fas fa-check"></i>&nbsp;&nbsp; Yes',
                        cancelButtonText: '<i class="fas fa-times"></i>&nbsp;&nbsp; No'
                    }).then(function(result){
                        if(result.value){
                            $.ajax({
                                url: '../../api/interactionController',
                                method: 'post',
                                data: {
                                    interact_type: 'update',
                                    interact: ((action_type == 1) ? 'inv_receive' : 'inv_issue'),
                                    parts_id: parts_id,
                                    inventory_history_id: inventory_history_id,
                                    source : ele4,
                                    required_for: req_for,
                                    action_date: action_date,
                                    qty: qty,
                                    price: price
                                },
                                dataType: 'json',
                                cache: false,
                                success: function(data){
                                    if(data.Type == 'success'){
                                        let t;

                                        Swal.fire({
                                            title: 'Updating inventory data',
                                            text: 'Please wait...',
                                            timer: 100,
                                            allowOutsideClick: false,
                                            onBeforeOpen: function(){
                                                Swal.showLoading(), t = setInterval(function(){
                                                }, 100);
                                            }
                                        }).then(function(){
                                            Swal.fire({
                                                title: 'Success',
                                                text: data.Reply,
                                                type: 'success',
                                                width: 450,
                                                showCloseButton: false,
                                                allowOutsideClick: false,
                                                confirmButtonColor: '#5cb85c',
                                                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                            }).then((result) => {
                                                if(result.value){
                                                    window.location.reload(true);
                                                }
                                            });
                                        });
                                    } else if(data.Type == 'error'){
                                        Swal.fire({
                                            title: 'Error',
                                            text: data.Reply,
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: 'Please insert valid data.'
                                        });
                                    } else if(data.Type == 'invalid'){
                                        Swal.fire({
                                            title: 'Info',
                                            text: data.Reply,
                                            type: 'info',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                        }).then((result) => {
                                            if(result.value){
                                                window.location.reload(true);
                                            }
                                        });
                                    } else{
                                        Swal.fire({
                                            title: 'Info',
                                            text: 'Server is under maintenance. Please try again later!',
                                            type: 'info',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                        });
                                    }

                                    return false;
                                }
                            });
                        }
                    });
                } else{
                    Swal.fire({
                        title: 'Error',
                        text: 'Empty Field Data!',
                        type: 'error',
                        width: 450,
                        showCloseButton: true,
                        confirmButtonColor: '#5cb85c',
                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                        footer: 'Please insert all the field data.'
                    });
                }
            }
        </script>
    </body>
</html>