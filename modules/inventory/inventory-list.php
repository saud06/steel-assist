<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Inventory Summary</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 3];
            if(!in_array($user_category, $user_categories)){
                header('location: ../dashboard');
            }
        ?>

        <style type="text/css">
            .summary-cat{
                border: 1px solid #5089de;
            }

            .pending-badge{
                background-color: #fa3e3e;
                border-radius: 10px;
                padding: 0px 7px;
                position: relative;
                bottom: 45px;
                left: 18px;
                color: #fff;
            }

            .select2-container .select2-selection--single{
                height: 34px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered{
                line-height: 34px;
            }
            .select2-container .select2-selection--single .select2-selection__arrow{
                height: 0px !important;
                top: 18px;
            }

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
                                    <li class="breadcrumb-item active">Inventory List - Summary</li>
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <i class="mdi mdi-shape-outline"></i> Inventory List - Summary

                                <button type="button" class="btn btn-xs btn-info waves-effect waves-light ml-2 transaction" data-id="1" data-toggle="modal" data-target=".bs-example-modal-lg"><span class="btn-label"><i class="mdi mdi-call-received"></span></i>Received</button>

                                <button type="button" class="btn btn-xs btn-success waves-effect waves-light ml-2 transaction" data-id="2" data-toggle="modal" data-target=".bs-example-modal-lg"><span class="btn-label"><i class="mdi mdi-call-made"></span></i>Issued</button>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <ul class="nav nav-pills navtab-bg nav-justified">
                                <li class="nav-item">
                                    <a href="#a" data-toggle="tab" aria-expanded="false" class="nav-link active summary-cat">
                                        <span class="d-none d-sm-inline">A</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#b" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">B</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#c" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">C</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#d" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">D</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#e" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">E</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#f" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">F</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#g" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">G</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#h" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">H</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#i" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">I</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#j" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">J</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#k" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">K</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#l" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">L</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#m" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">M</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#n" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">N</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#o" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">O</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#p" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">P</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#q" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">Q</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#r" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">R</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#s" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">S</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#t" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">T</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#u" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">U</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#v" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">V</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#w" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">W</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#x" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">X</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#y" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">Y</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#z" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">Z</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#9" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">#</span>
                                    </a>
                                    <span class="pending-badge d-none"></span>
                                </li>
                                <li class="nav-item">
                                    <a href="#_" data-toggle="tab" aria-expanded="true" class="nav-link summary-cat">
                                        <span class="d-none d-sm-inline">All</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane show active summary-div" id="a">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table class="table datatable w-100 nowrap cell-border text-center">
                                                        <thead style="color: #fff; background-color: #5089de;">
                                                            <tr>
                                                                <th>SL.</th>
                                                                <th>Parts Name</th>
                                                                <th>Parts Unit</th>
                                                                <th>Stock Quantity</th>
                                                                <th>Received Quantity</th>
                                                                <th>Issued Quantity</th>
                                                                <th>Average Rate</th>
                                                                <th>Receive / Issue Parts</th>
                                                                <!-- <th class="alert-danger">Delete Received / Issued Records</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tfoot style="color: #fff; background-color: #5089de;">
                                                            <tr>
                                                                <th>SL.</th>
                                                                <th>Parts Name</th>
                                                                <th>Parts Unit</th>
                                                                <th>Stock Quantity</th>
                                                                <th>Received Quantity</th>
                                                                <th>Issued Quantity</th>
                                                                <th>Average Rate</th>
                                                                <th>Receive / Issue Parts</th>
                                                                <!-- <th class="alert-danger">Delete Received / Issued Records</th> -->
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div> <!-- end card body-->
                                            </div> <!-- end card -->
                                        </div><!-- end col-->
                                    </div>
                                </div>
                            </div>

                            <!-- Start Modals For Received / Issued Record -->
                            <div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 1180px !important;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title transaction-title" id="myLargeModalLabel"></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        
                                        <div class="modal-body" style="overflow: scroll; height: 500px;">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card" style="margin-bottom: 0">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label for="parts">Parts Nick Name</label>
                                                                        <select data-placeholder="Choose" class="select-b parts-nickname">
                                                                            <option value="">Choose</option>
                                                                            <?php 
                                                                                $parts_nickname_query = mysqli_query($conn, "SELECT parts_nickname FROM rrmsteel_parts GROUP BY parts_nickname");

                                                                                if(mysqli_num_rows($parts_nickname_query) > 0){
                                                                                    while($row = mysqli_fetch_assoc($parts_nickname_query)){
                                                                            ?>
                                                                                        <option value="<?= $row['parts_nickname'] ?>"><?= $row['parts_nickname'] ?></option>
                                                                            <?php 
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-2">
                                                                    <div class="row">
                                                                        <label for="." style="visibility: hidden;">.</label>
                                                                    </div>
                                                                    <div class="row">
                                                                        <button type="button" class="btn btn-success waves-effect waves-light filter"><span class="btn-label"><i class="mdi mdi-filter-outline"></span></i>Filter</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <table id="basic-datatable3" class="table w-100 nowrap cell-border">
                                                                <thead style="color: #fff; background-color: #5089de;">
                                                                    <tr>
                                                                        <th class="text-center">SL.</th>
                                                                        <th class="text-center">Parts</th>
                                                                        <th class="text-center">Unit</th>
                                                                        <th class="text-center">Qty.</th>
                                                                        <th class="text-center">Value</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="records">
                                                                </tbody>
                                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                                    <tr>
                                                                        <th>SL.</th>
                                                                        <th>Parts</th>
                                                                        <th>Unit</th>
                                                                        <th>Qty.</th>
                                                                        <th>Value</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div> <!-- end card-body-->
                                                    </div> <!-- end card-->
                                                </div> <!-- end col -->                  
                                            </div> 
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <!-- End Modals For Party Ledger -->
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
                // FETCH SUMMARY DATA
                let t;

                Swal.fire({
                    title: 'Fetching Summary Data',
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
                            inventory_data_type: 'inventory_summary_list',
                            alpha: 'a'
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(result){
                            if(result.Type == 'success'){
                                $.each($('.nav-item'), function(i){
                                    if(jQuery.inArray($(this).find('.summary-cat').find('span').html(), result.Reply2) !== -1){
                                        let char = $(this).find('.summary-cat').find('span').html();

                                        let char_count = result.Reply2.filter(function(ele){
                                            return ele == char;
                                        }).length;

                                        $(this).find('.pending-badge').removeClass('d-none').html(char_count);
                                    } else{
                                        $(this).find('.pending-badge').addClass('d-none');
                                    }
                                });

                                /*let sorted_data = _.sortBy(result.Reply, function(arr){
                                    return arr.tot_rem_qty_frm_bill + arr.tot_rem_qty_frm_loan;
                                }).reverse();*/

                                $('.datatable').DataTable({
                                    stateSave: !0,
                                    scrollX: !0,
                                    ordering: !1,
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
                                    createdRow: function(row, data){
                                        if(data['parts_qty'] <= data['parts_alert_qty']){
                                            $(row).addClass('alert-danger');
                                        }
                                    },
                                    columnDefs: [
                                        {
                                            width: 600,
                                            targets: 7
                                        }
                                    ],
                                    columns: [
                                        {
                                            name: 'first',
                                            data: 'sl'
                                        },
                                        {
                                            name: 'second',
                                            data: 'parts_name'
                                        },
                                        {
                                            name: 'third',
                                            data: 'parts_unit'
                                        },
                                        {
                                            name: 'forth',
                                            data: 'parts_qty'
                                        },
                                        {
                                            name: 'fifth',
                                            data: 'tot_rcv_qty'
                                        },
                                        {
                                            name: 'sixth',
                                            data: 'tot_iss_qty'
                                        },
                                        {
                                            name: 'seventh',
                                            data: 'parts_avg_rate'
                                        },
                                        {
                                            name: 'eighth',
                                            data: 'action'
                                        }
                                    ],
                                    rowsGroup: [
                                        'first:name',
                                        'third:name',
                                        'forth:name',
                                        'fifth:name',
                                        'sixth:name',
                                        'seventh:name',
                                        'eighth:name'
                                    ]
                                });
                            } else if(data.Type == 'error'){
                                let table = $('.datatable').DataTable();

                                table.destroy();
                                    
                                $('.datatable').DataTable({
                                    stateSave: !0,
                                    scrollX: !0,
                                    ordering: !1,
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

                // FETCH SUMMARY DATA BY CATEGORY
                $('.summary-cat').on('click', function(){
                    $('.summary-div').attr('id', $(this).attr('href')[1]);

                    let alpha = $(this).attr('href')[1];

                    let t;

                    Swal.fire({
                        title: 'Fetching Summary Data',
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
                                inventory_data_type: 'inventory_summary_list',
                                alpha: alpha
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(result){
                                if(result.Type == 'success'){
                                    $.each($('.nav-item'), function(i){
                                        if(jQuery.inArray($(this).find('.summary-cat').find('span').html(), result.Reply2) !== -1){
                                            let char = $(this).find('.summary-cat').find('span').html();

                                            let char_count = result.Reply2.filter(function(ele){
                                                return ele == char;
                                            }).length;

                                            $(this).find('.pending-badge').removeClass('d-none').html(char_count);
                                        } else{
                                            $(this).find('.pending-badge').addClass('d-none');
                                        }
                                    });

                                    /*let sorted_data = _.sortBy(result.Reply, function(arr){
                                        return arr.tot_rem_qty_frm_bill + arr.tot_rem_qty_frm_loan;
                                    }).reverse();*/

                                    let table = $('.datatable').DataTable();
                                    table.destroy();

                                    $('.datatable').DataTable({
                                        stateSave: !0,
                                        scrollX: !0,
                                        ordering: !1,
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
                                        createdRow: function(row, data){
                                            if(data['parts_qty'] <= data['parts_alert_qty']){
                                                $(row).addClass('alert-danger');
                                            }
                                        },
                                        columnDefs: [
                                            {
                                                width: 600,
                                                targets: 7
                                            }
                                        ],
                                        columns: [
                                            {
                                                name: 'first',
                                                data: 'sl'
                                            },
                                            {
                                                name: 'second',
                                                data: 'parts_name'
                                            },
                                            {
                                                name: 'third',
                                                data: 'parts_unit'
                                            },
                                            {
                                                name: 'forth',
                                                data: 'parts_qty'
                                            },
                                            {
                                                name: 'fifth',
                                                data: 'tot_rcv_qty'
                                            },
                                            {
                                                name: 'sixth',
                                                data: 'tot_iss_qty'
                                            },
                                            {
                                                name: 'seventh',
                                                data: 'parts_avg_rate'
                                            },
                                            {
                                                name: 'eighth',
                                                data: 'action'
                                            }
                                        ],
                                        rowsGroup: [
                                            'first:name',
                                            'third:name',
                                            'forth:name',
                                            'fifth:name',
                                            'sixth:name',
                                            'seventh:name',
                                            'eighth:name'
                                        ]
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
                                        footer: 'Please try again.'
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

                // TRANSACTION
                $('.transaction').on('click', function(){
                    let tran_type = $(this).attr('data-id');

                    (tran_type == 1) ? $('.transaction-title').html('Received (This Month)') : $('.transaction-title').html('Issued (This Month)');

                    $('.filter').attr('data-id', tran_type);

                    let table = $('#basic-datatable3').DataTable();
                    table.destroy();
                    $('#records').empty();

                    let trHTML = '';

                    $.ajax({
                        url: '../../api/inventory',
                        method: 'post',
                        data: {
                            inventory_data_type: 'fetch_transaction',
                            tran_type: tran_type
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                let t;

                                Swal.fire({
                                    title: (tran_type == 1) ? 'Fetching Received Data' : 'Fetching Issued Data',
                                    text: 'Please wait...',
                                    timer: 100,
                                    allowOutsideClick: false,
                                    onBeforeOpen: function(){
                                        Swal.showLoading(), t = setInterval(function(){
                                        }, 100);
                                    }
                                });

                                $.each(data.Reply, function(i, item){
                                    trHTML += '<tr>';
                                        trHTML += '<td>' + (i+1) + '</td>';
                                        trHTML += '<td>' + item.parts_name + '</td>';
                                        trHTML += '<td>' + item.parts_unit + '</td>';
                                        trHTML += '<td>' + ((tran_type == 1) ? item.received_qty : item.issued_qty) + '</td>';
                                        trHTML += '<td>' + ((tran_type == 1) ? item.received_val : item.issued_val) + '</td>';
                                    trHTML += '</tr>';
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
                                    footer: 'Please try again.'
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

                    $('#records').append(trHTML);

                    $('#basic-datatable3').DataTable({
                        stateSave: !0,
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
                });

                // FILTER TRANSACTION
                $('.filter').click(function(){
                    let parts_nickname = $('.parts-nickname').val(),
                        tran_type = $(this).attr('data-id');

                    if(!parts_nickname){
                        Swal.fire({
                            title: 'Error',
                            text: 'Empty required data!',
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'Please choose party or parts.'
                        });
                    } else{
                        let table = $('#basic-datatable3').DataTable();
                        table.destroy();
                        $('#records').empty();

                        let trHTML = '';

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_filtered_transaction',
                                parts_nickname: parts_nickname,
                                tran_type: tran_type
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Fetching Filtered Data',
                                        text: 'Please wait...',
                                        timer: 100,
                                        allowOutsideClick: false,
                                        onBeforeOpen: function(){
                                            Swal.showLoading(), t = setInterval(function(){
                                            }, 100);
                                        }
                                    });

                                    $.each(data.Reply, function(i, item){
                                        trHTML += '<tr>';
                                            trHTML += '<td>' + (i+1) + '</td>';
                                            trHTML += '<td>' + item.parts_name + '</td>';
                                            trHTML += '<td>' + item.parts_unit + '</td>';
                                            trHTML += '<td>' + ((tran_type == 1) ? item.received_qty : item.issued_qty) + '</td>';
                                            trHTML += '<td>' + ((tran_type == 1) ? item.received_val : item.issued_val) + '</td>';
                                        trHTML += '</tr>';
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
                                        footer: 'Please try again.'
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

                        $('#records').append(trHTML);

                        $('#basic-datatable3').DataTable({
                            stateSave: !0,
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
                    }
                });
            });

            // ACTION TYPE
            function action_type(ele){
                let action_type = $(ele).closest('tr').find('td').find('.action-type').val();

                $(ele).closest('tr').find('td').find('.save').addClass('d-none');

                if(action_type){
                    if(action_type == 1){
                        $(ele).closest('tr').find('td').find('.req-for').val('').addClass('d-none');
                    } else{
                        $(ele).closest('tr').find('td').find('.req-for').val('').removeClass('d-none');
                    }

                    $(ele).closest('tr').find('td').find('.qty, .price').val('').addClass('d-none');
                    $(ele).closest('tr').find('td').find('.action-date').val('').removeClass('d-none');
                } else{
                    $(ele).closest('tr').find('td').find('.req-for, .qty, .price, .action-date').val('').addClass('d-none');
                }
            }

            let max_parts_qty = 0;

            // REQUIRED FOR
            function required_for(ele, ele2){
                let action_type = $(ele).closest('tr').find('td').find('.action-type').val(),
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    qty = $(ele).closest('tr').find('td').find('.qty').val(),
                    parts_id = ele2;

                if(action_type && $(ele).val() && action_date){
                    $.ajax({
                        url: '../../api/inventory',
                        method: 'post',
                        data: {
                            parts_id: parts_id,
                            action_date: action_date,
                            inventory_data_type: 'inventory_parts_issue'
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                if(data.Reply[0].parts_qty == null){
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'No inventory / receive record found!',
                                        type: 'error',
                                        width: 450,
                                        showCloseButton: true,
                                        confirmButtonColor: '#5cb85c',
                                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                        footer: 'Please receive some before issue.'
                                    }).then(function(){
                                        $(ele).closest('tr').find('td').find('.qty').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.price').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                                    });
                                } else if(data.Reply[0].parts_qty <= 0){
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'All received parts have been issued!',
                                        type: 'error',
                                        width: 450,
                                        showCloseButton: true,
                                        confirmButtonColor: '#5cb85c',
                                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                        footer: 'Please receive some to issue.'
                                    }).then(function(){
                                        $(ele).closest('tr').find('td').find('.qty').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.price').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                                    });
                                } else{
                                    max_parts_qty = data.Reply[0].parts_qty;
                                    
                                    $(ele).closest('tr').find('td').find('.qty').removeClass('d-none').val('');
                                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                                }
                            }
                        }
                    });
                }

                if(!$(ele).val() || !action_date){
                    $(ele).closest('tr').find('td').find('.qty, .price').val('');
                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                }
            }

            // ACTION DATE
            function action_date(ele, ele2){
                let action_type = $(ele).closest('tr').find('td').find('.action-type').val(),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    qty = $(ele).closest('tr').find('td').find('.qty').val();
                    parts_id = ele2;

                if(((action_type == 1) || (action_type == 2 && req_for)) && $(ele).val()){
                    $.ajax({
                        url: '../../api/inventory',
                        method: 'post',
                        data: {
                            parts_id: parts_id,
                            action_date: $(ele).val(),
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
                                        text: ((action_type == 1) ? 'No purchase record found; or all quantities have been received!' : 'No inventory / receive record found!'),
                                        type: 'error',
                                        width: 450,
                                        showCloseButton: true,
                                        confirmButtonColor: '#5cb85c',
                                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                        footer: ((action_type == 1) ? 'Please purchase some before receive.' : 'Please receive some before issue.')
                                    }).then(function(){
                                        $(ele).closest('tr').find('td').find('.qty').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.price').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                                    });
                                } else if(data.Reply[0].parts_qty <= 0){
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'All received parts have been issued!',
                                        type: 'error',
                                        width: 450,
                                        showCloseButton: true,
                                        confirmButtonColor: '#5cb85c',
                                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                        footer: ((action_type == 1) ? 'Please purchase some before receive.' : 'Please receive some before issue.')
                                    }).then(function(){
                                        $(ele).closest('tr').find('td').find('.qty').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.price').addClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                                    });
                                } else{
                                    max_parts_qty = data.Reply[0].parts_qty;

                                    if(action_type == 1){
                                        $(ele).closest('tr').find('td').find('.qty').removeClass('d-none').val(data.Reply[0].parts_qty);
                                        $(ele).closest('tr').find('td').find('.price').removeClass('d-none').val(data.Reply[0].parts_price.toFixed(2));
                                        $(ele).closest('tr').find('td').find('.save').removeClass('d-none');
                                    } else{
                                        $(ele).closest('tr').find('td').find('.qty').removeClass('d-none').val('');
                                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                                    }
                                }
                            }
                        }
                    });
                }

                if(!$(ele).val() || (action_type == 2 && !req_for)){
                    $(ele).closest('tr').find('td').find('.qty, .price').val('');
                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                }
            }

            // VALIDATE ACTION DATE FIELD
            function validate_action_date(ele){
                $(ele).closest('tr').find('td').find('.action-date').val('');
            }

            // QTY
            function qty(ele){
                let action_type = $(ele).closest('tr').find('td').find('.action-type').val(),
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    qty = $(ele).val(),
                    price = $(ele).closest('tr').find('td').find('.price').val();

                if(!qty){
                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                } else if(parseFloat(qty) <= 0){
                    $(ele).closest('tr').find('td').find('.qty').val('');
                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                } else if(parseFloat(qty) > parseFloat(max_parts_qty)){
                    $(ele).closest('tr').find('td').find('.qty').val(parseFloat(max_parts_qty));
                } else{
                    if((action_type == 1 && action_date && price) || (action_type == 2 && action_date && req_for)){
                        $(ele).closest('tr').find('td').find('.save').removeClass('d-none');
                    } else{
                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                    }
                }
            }

            // PRICE
            function price(ele){
                let action_type = $(ele).closest('tr').find('td').find('.action-type').val(),
                    action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
                    req_for = $(ele).closest('tr').find('td').find('.req-for').val(),
                    qty = $(ele).closest('tr').find('td').find('.qty').val(),
                    price = $(ele).val();

                if(!price){
                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                } else if(price <= 0){
                    $(ele).closest('tr').find('td').find('.price').val('');
                    $(ele).closest('tr').find('td').find('.save').addClass('d-none');

                    return false;
                } else{
                    if((action_type == 1 && action_date && qty) || (action_type == 2 && action_date && req_for && qty)){
                        $(ele).closest('tr').find('td').find('.save').removeClass('d-none');
                    } else{
                        $(ele).closest('tr').find('td').find('.save').addClass('d-none');
                    }
                }
            }
        </script>
    </body>
</html>