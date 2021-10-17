<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Requisition</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 2, 3, 4];
            if(!in_array($user_category, $user_categories)){
                header('location: ../dashboard');
            }
        ?>

        <style type="text/css">
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

            .select-custom{
                width: 100%;
                line-height: 34px;
                height: 32px;
                border: 1px solid #aaa;
                border-radius: 4px;
                padding-left: 8px;
                padding-right: 20px;
            }

            .consumable-table > thead > tr > th{
                vertical-align: middle;
            }
            .spare-table > tbody > tr > td{
                vertical-align: middle;
            }

            .hr-custom-style{
                border-top: 1px solid #808080;
            }

            .datatable > thead > tr > th, .datatable-2 > thead > tr > th{
                vertical-align: middle;
            }

            #consumable_table, #spare_table{
                max-height: 395px;
                overflow-x: auto;
                overflow-y: auto;
                border: 1px solid #dee2e6;
            }

            @media print{
                @page{
                    margin-top: 150px;
                    margin-bottom: 156px;
                }

                .page-break{
                    clear: both;
                    page-break-after: always;
                }

                .table-printed{
                    page-break-after: auto;
                }
                .table-printed > thead, .table-printed > tfoot{
                    display: table-header-group;
                }
                .table-printed > tr, .table-printed > td{
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
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
                <div class="row d-print-none">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Requisition List</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-file-document-edit-outline"></i> Requisition List</h4>
                        </div>
                    </div>
                </div>     
                <!-- end page title --> 
                
                <div class="row <?php if($user_category == 3 || $user_category == 4) echo 'd-none'; ?>">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="button-list">
                                <?php 
                                    $con_requisition_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS r_data FROM rrmsteel_con_requisition WHERE approval_status = 0"));
                                    $con_data = $con_requisition_info['r_data'];

                                    $spr_requisition_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS r_data FROM rrmsteel_spr_requisition WHERE approval_status = 0"));
                                    $spr_data = $spr_requisition_info['r_data'];
                                ?>

                                <button type="button" class="btn btn-xs btn-success waves-effect waves-light create-requisition" data-toggle="modal" data-target=".full-width-modal" <?php if(($con_data + $spr_data) == 30) echo 'disabled'; ?>><span class="btn-label"><i class="mdi mdi-note-plus-outline"></i></span>Create Requisition</button>

                                <?php 
                                    if(($con_data + $spr_data) == 30){
                                ?>
                                        &nbsp;&nbsp;

                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="Unable to create new requisition. Approvals for old rerquisitions are pending."></i>
                                <?php 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Start Modals For Create / Update Requisition -->
                <div class="modal fade full-width-modal" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="full-width-modalLabel"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>

                            <div class="modal-body" style="height: 500px; overflow: hidden;">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div id="rootwizard">
                                            <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-2 requisition-ul">
                                                <li class="nav-item">
                                                    <a href="#first" data-toggle="tab" id="consumable_nav" class="nav-link rounded-0 pt-2 pb-2">
                                                        <i class="fas fa-fire mr-1"></i>
                                                        <span class="d-none d-sm-inline">CONSUMABLE</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#second" data-toggle="tab" id="spare_nav" class="nav-link rounded-0 pt-2 pb-2">
                                                        <i class="fas fa-wrench mr-1"></i>
                                                        <span class="d-none d-sm-inline">SPARE</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content mb-0 b-0 pt-0">
                                                <div class="tab-pane" id="first">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div id="consumable_table">
                                                                <table class="table table-bordered table-responsive-md table-striped text-center mb-0 consumable-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="align-middle text-center" style="min-width: 100px;">SL.</th>
                                                                            <th class="align-middle text-center" style="min-width: 200px;">Required For</th>
                                                                            <th class="align-middle text-center" style="min-width: 300px;">Parts Name</th>
                                                                            <th class="align-middle text-center" style="min-width: 200px;">Quantity</th>
                                                                            <th class="align-middle text-center" style="min-width: 300px;">Where to Use</th>
                                                                            <th class="align-middle text-center" style="min-width: 300px;">Remarks</th>
                                                                            <th class="align-middle text-center" style="min-width: 100px;">Loan</th>
                                                                            <th class="align-middle text-center" style="min-width: 100px;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="consumable_body">
                                                                    </tbody>
                                                                </table>
                                                                
                                                                <div class="m-2 custom-control custom-checkbox approval d-none">
                                                                    <input type="checkbox" class="custom-control-input approval-status" id="customCheck1">
                                                                    <label class="custom-control-label" for="customCheck1"><?php if($user_category == 4) echo 'Accept'; else echo 'Approve'; ?> This Requisition</label>

                                                                    &nbsp;

                                                                    <?php
                                                                        if($user_category != 4){
                                                                    ?>
                                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="Once you approve & proceed, you cannot update this requisition anymore."></i>
                                                                    <?php 
                                                                        }
                                                                    ?>
                                                                </div>
                                                                
                                                                <div class="m-2 pull-right">
                                                                    <button type="button" class="btn btn-xs btn-info waves-effect waves-light table-add-1 <?php if($user_category == 4) echo 'd-none'; ?>"><span class="btn-label"><i class="fas fa-plus"></i></span>Add New Row</button>

                                                                    <div class="d-inline proceed-div">
                                                                        <button type="button" class="btn btn-xs btn-success waves-effect waves-light" onclick="proceed_consumable()"><span class="btn-label"><i class="fas fa-arrow-right"></i></span>Done & Proceed</button>
                                                                    </div>

                                                                    <div class="d-inline accept-div">
                                                                        <button type="button" class="btn btn-xs btn-success waves-effect waves-light"><span class="btn-label"><i class="fas fa-check"></i></span>Accept This Requisition</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div>

                                                <div class="tab-pane fade" id="second">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div id="spare_table">
                                                                <table class="table table-bordered table-responsive-md table-striped text-center mb-0 spare-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="align-middle text-center" style="min-width: 50px;">SL.</th>
                                                                            <th class="align-middle text-center" style="min-width: 200px;">Required For</th>
                                                                            <th class="align-middle text-center" style="min-width: 250px;">Parts Name</th>
                                                                            <th class="align-middle text-center" style="min-width: 200px;">Quantity</th>
                                                                            <th class="align-middle text-center" style="min-width: 250px;">Old Spare Details</th>
                                                                            <th class="align-middle text-center" style="min-width: 200px;">Status</th>
                                                                            <th class="align-middle text-center" style="min-width: 250px;">Remarks</th>
                                                                            <th class="align-middle text-center" style="min-width: 100px;">Loan</th>
                                                                            <th class="align-middle text-center" style="min-width: 100px;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="spare_body">
                                                                    </tbody>
                                                                </table>

                                                                <div class="m-2 custom-control custom-checkbox approval-2 d-none">
                                                                    <input type="checkbox" class="custom-control-input approval-status-2" id="customCheck2">
                                                                    <label class="custom-control-label" for="customCheck2"><?php if($user_category == 4) echo 'Accept'; else echo 'Approve'; ?> This Requisition</label>

                                                                    &nbsp;

                                                                    <?php
                                                                        if($user_category != 4){
                                                                    ?>
                                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="Once you approve & proceed, you cannot update this requisition anymore."></i>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </div>

                                                                <div class="m-2 pull-right">
                                                                    <button type="button" class="btn btn-xs btn-info waves-effect waves-light table-add-2 <?php if($user_category == 4) echo 'd-none'; ?>"><span class="btn-label"><i class="fas fa-plus"></i></span>Add New Row</button>

                                                                    <div class="d-inline proceed-div-2">
                                                                        <button type="button" class="btn btn-xs btn-success waves-effect waves-light" onclick="proceed_spare()"><span class="btn-label"><i class="fas fa-arrow-right"></i></span>Done & Proceed</button>
                                                                    </div>

                                                                    <div class="d-inline accept-div-2">
                                                                        <button type="button" class="btn btn-xs btn-success waves-effect waves-light"><span class="btn-label"><i class="fas fa-check"></i></span>Accept This Requisition</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div>
                                            </div> <!-- tab-content -->
                                        </div> <!-- end #rootwizard-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div>

                            <div class="modal-footer">
                                <button title="Scroll to left" type="button" class="btn btn-xs btn-info waves-effect waves-light scroll-left"><i class="fas fa-long-arrow-alt-left"></i></button>
                                <button title="Scroll to right" type="button" class="btn btn-xs btn-info waves-effect waves-light mr-2 scroll-right"><i class="fas fa-long-arrow-alt-right"></i></button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For Create Requisition -->

                <!-- Start Modals For REQUISITION PRINT -->
                <div class="modal fade full-width-modal-2" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header d-print-none">
                                <h4 class="modal-title" id="full-width-modalLabel">Print Requisition</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>

                            <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                <div class="card">
                                    <div class="card-body p-2 requisition-print">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="float-left">
                                                    <h4 class="m-0"><span class="requisition-type"></span> Parts Requisition</h4>

                                                    <br>

                                                    <p>
                                                        <strong>Reference</strong>: &emsp;<span class="float-right reference-span"></span><br>
                                                        <strong>Requisition Date</strong>: &emsp;<span class="float-right requisition-date-span"></span><br>
                                                        <strong>Approval Status</strong>: &emsp;<span class="float-right approval-status-span"></span><br>
                                                        <strong>Requisitioned By</strong>: &emsp;<span class="float-right requisition-by-span"></span><br>
                                                        <strong>Approved By</strong>: &emsp;<span class="float-right approved-by-span"></span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="float-right">
                                                    <img src="../../assets/images/rrm/rrmsteel-logo.png" alt="Logo" height="34">

                                                    <p>A Concern of <span>RRM Group</span></p>
                                                    <p class="text-muted"><b>The Rani Re-rolling Mills Ltd.</b><br>Roda #14, Plot #18, Shaympur, Kadamtali I/A,<br>Dhaka-1204.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <br><br><br>

                                        <div class="row print-div">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-centered table-bordered table-printed">
                                                        <thead class="print-title">
                                                        </thead>
                                                        <tbody class="print-records">
                                                        </tbody>
                                                        <tfoot class="print-footer">
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <h4 class="total-price-in-words"></h4>
                                            </div>
                                        </div>

                                        <div class="d-none page-break"></div>

                                        <div class="row py-lg-5">
                                            <div class="col-sm-1">
                                            </div>

                                            <div class="col-sm-2">
                                                <p class="text-center text-uppercase font-weight-bold r-sign"></p>
                                                <hr class="hr-custom-style">
                                                <p class="text-center">Requisite Person</p>
                                            </div>

                                            <div class="col-sm-2">
                                                <p class="text-center text-uppercase font-weight-bold s-sign"></p>
                                                <hr class="hr-custom-style">
                                                <p class="text-center">Store Incharge</p>
                                            </div>

                                            <div class="col-sm-2">
                                                <p class="text-center text-uppercase font-weight-bold p-sign"></p>
                                                <hr class="hr-custom-style">
                                                <p class="text-center">Purchase Incharge</p>
                                            </div>

                                            <div class="col-sm-2">
                                                <p class="text-center text-uppercase font-weight-bold e-sign"></p>
                                                <hr class="hr-custom-style">
                                                <p class="text-center">Executive Director</p>
                                            </div>

                                            <div class="col-sm-2">
                                                <p class="text-center text-uppercase font-weight-bold a-sign"></p>
                                                <hr class="hr-custom-style">
                                                <p class="text-center">Approval Authority</p>
                                            </div>

                                            <div class="col-sm-1">
                                            </div>
                                        </div>

                                        <br><br>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p class="font-weight-light font-italic text-success foot-note"></p>
                                            </div>
                                        </div>
                                    </div> <!-- end card-box -->

                                    <div class="row justify-content-center mb-3">
                                        <a onclick="print_requisition()" href="#" class="btn btn-xs btn-primary waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Requisition</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For REQUISITION PRINT -->
                
                <!-- REQUISITION LIST -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="rootwizard">
                                    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-3">
                                        <li class="nav-item">
                                            <a href="#third" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 active">
                                                <i class="fas fa-fire mr-1"></i>
                                                <span class="d-none d-sm-inline">CONSUMABLE</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#forth" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-wrench mr-1"></i>
                                                <span class="d-none d-sm-inline">SPARE</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content mb-0 b-0 pt-0">
                                        <div class="tab-pane active" id="third" style="overflow-x: auto; overflow-y: auto;">
                                            <table class="table datatable w-100 nowrap cell-border text-center">
                                                <thead style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th rowspan="2">SL.</th>
                                                        <th rowspan="2">Reference</th>
                                                        <th rowspan="2">Requisitioned By</th>
                                                        <th colspan="2">Approval Status</th>
                                                        <th colspan="1">Acceptance Status</th>
                                                        <th rowspan="2">Requisition Date</th>
                                                        <th rowspan="2">Action</th>
                                                    </tr>
                                                    <tr>
                                                        <th>By Store<br>Incharge</th>
                                                        <th>By Admin</th>
                                                        <th>By Purchase<br>Incharge</th>
                                                    </tr>
                                                </thead>
                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Requisitioned By</th>
                                                        <th colspan="2">Approval Status</th>
                                                        <th colspan="1">Acceptance Status</th>
                                                        <th>Requisition Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="tab-pane" id="forth" style="overflow-x: auto; overflow-y: auto;">
                                            <table class="table datatable-2 w-100 nowrap cell-border text-center">
                                                <thead style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th rowspan="2">SL.</th>
                                                        <th rowspan="2">Reference</th>
                                                        <th rowspan="2">Requisitioned By</th>
                                                        <th colspan="2">Approval Status</th>
                                                        <th colspan="1">Acceptance Status</th>
                                                        <th rowspan="2">Requisition Date</th>
                                                        <th rowspan="2">Action</th>
                                                    </tr>
                                                    <tr>
                                                        <th>By Store<br>Incharge</th>
                                                        <th>By Admin</th>
                                                        <th>By Purchase<br>Incharge</th>
                                                    </tr>
                                                </thead>
                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Requisitioned By</th>
                                                        <th colspan="2">Approval Status</th>
                                                        <th colspan="1">Acceptance Status</th>
                                                        <th>Requisition Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->
        
        <!-- Footer Start -->
        <?php require_once('../../footer-for-sub-page.php'); ?>
        <!-- end Footer -->

        <!-- Custom js -->
        <script type="text/javascript">
            $(document).ready(function(){
                // FETCH AND DISPLAY REQUISITION DATA
                let t;

                Swal.fire({
                    title: 'Fetching Requisition Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/requisition',
                        method: 'post',
                        data: {
                            requisition_data_type: 'fetch_all',
                            user_id: '<?= $user_id ?>',
                            user_category: '<?= $user_category ?>'
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(result){
                            if(result.Type == 'success'){
                                if(result.Reply){
                                    $('.datatable').DataTable({
                                        stateSave: !0,
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
                                            { data: 'sl' },
                                            { data: 'reference' },
                                            { data: 'requisition_by' },
                                            { data: 's_approval_status' },
                                            { data: 'approval_status' },
                                            { data: 'p_approval_status' },
                                            { data: 'requisition_created' },
                                            { data: 'action' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable').DataTable();

                                    table.destroy();
                                        
                                    $('.datatable').DataTable({
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

                                if(result.Reply2){
                                    $('.datatable-2').DataTable({
                                        stateSave: !0,
                                        language: {
                                            paginate: {
                                                previous: '<i class="mdi mdi-chevron-left">',
                                                next: '<i class="mdi mdi-chevron-right">'
                                            }
                                        },
                                        drawCallback: function(){
                                            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                                        },
                                        data: result.Reply2,
                                        columns: [
                                            { data: 'sl' },
                                            { data: 'reference' },
                                            { data: 'requisition_by' },
                                            { data: 's_approval_status' },
                                            { data: 'approval_status' },
                                            { data: 'p_approval_status' },
                                            { data: 'requisition_created' },
                                            { data: 'action' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable-2').DataTable();

                                    table.destroy();
                                        
                                    $('.datatable-2').DataTable({
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
                            } else if(data.Type == 'error'){
                                let table = $('.datatable, .datatable-2').DataTable();

                                table.destroy();
                                    
                                $('.datatable, .datatable-2').DataTable({
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

                // CREATE REQUISITION
                let sl = $('#consumable_table tbody').length + 1,
                    sl2 = $('#spare_table tbody').length + 1;
                
                $('.create-requisition').on('click', function(){
                    let t;

                    Swal.fire({
                        title: 'Loading Requisition Form',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        $('.modal-title').html('Create Requisition');

                        $('#consumable_table').find('table tbody').empty();
                        sl--;
                        $('.table-add-1').click();

                        $('#spare_table').find('table tbody').empty();
                        sl2--;
                        $('.table-add-2').click();
                  
                        $('#first, #consumable_nav').addClass('active');
                        $('#second, #spare_nav').removeClass('active');
                        $('.requisition-ul').removeClass('d-none');
                        $('.approval, .approval-2').addClass('d-none');

                        // CLOSE MODAL / RELOAD PAGE RESTRICTION
                        let modified = false;

                        $('#consumable_table tr td, #spare_table tr td').each(function(){
                            $(this).find('input').on('input', function(){
                                if($(this).val())
                                    modified = true;
                                else
                                    modified = false;
                            });

                            $(this).find('select').on('change', function(){
                                if($(this).val())
                                    modified = true;
                                else
                                    modified = false;
                            });

                            $(this).find('textarea').on('input', function(){
                                if($(this).val())
                                    modified = true;
                                else
                                    modified = false;
                            });
                        });

                        $('.full-width-modal').on('hide.bs.modal', function(){
                            if(modified){
                                if(confirm('Are you sure, you want to close?')){
                                    modified = false;

                                    return true;
                                } else{
                                    return false;
                                }
                            }
                        });

                        window.onbeforeunload = function(){
                            if(modified){
                                if(confirm('Are you sure, you want to close?')){
                                    modified = false;

                                    return true;
                                } else{
                                    return false;
                                }
                            }
                        }
                    });
                });

                // ADD CONSUMABLE TABLE ROW
                $('.table-add-1').on('click', function(){
                    $('#consumable_table tr').each(function(){
                        let required_for = $(this).find('td:eq(1)').find('select').val(),
                            parts = $(this).find('td:eq(2)').find('select').val(),
                            qty = $(this).find('td:eq(3)').find('input').val(),
                            use = $(this).find('td:eq(4)').find('textarea').val(),
                            remarks = $(this).find('td:eq(5)').find('textarea').val();

                        if(required_for === '' || parts === '' || qty === '' || use === '' || remarks === '')
                            $(this).find('td:eq(0)').find('span').removeClass('d-none');
                        else
                           $(this).find('td:eq(0)').find('span').addClass('d-none');
                    });

                    let flag = 1;

                    $('#consumable_table tr').each(function(){
                        let required_for = $(this).find('td:eq(1)').find('select').val(),
                            parts = $(this).find('td:eq(2)').find('select').val(),
                            qty = $(this).find('td:eq(3)').find('input').val(),
                            use = $(this).find('td:eq(4)').find('textarea').val(),
                            remarks = $(this).find('td:eq(5)').find('textarea').val();

                        if(required_for === '' || parts === '' || qty === '' || use === '' || remarks === ''){
                            flag = 0;

                            Swal.fire({
                                title: 'Error',
                                text: 'Empty table row data!',
                                type: 'error',
                                width: 450,
                                showCloseButton: true,
                                confirmButtonColor: '#5cb85c',
                                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                footer: 'Please fill all data in the table row.'
                            });

                            return false;
                        } else{
                            flag = 1;
                        }
                    });

                    if(flag == 1){
                        let trHTML = '';

                        trHTML += '<tr>';
                            trHTML += '<td class="align-middle text-center">' + sl + ' <span class="text-danger d-none">*</span></td>';
                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<select class="select-custom required-for">';
                                    trHTML += '<option value="">Choose</option>';
                                    trHTML += '<option value="1">BCP-CCM</option>';
                                    trHTML += '<option value="2">BCP-Furnace</option>';
                                    trHTML += '<option value="3">Concast-CCM</option>';
                                    trHTML += '<option value="4">Concast-Furnace</option>';
                                    trHTML += '<option value="5">HRM</option>';
                                    trHTML += '<option value="6">HRM Unit-2</option>';
                                    trHTML += '<option value="7">Lal Masjid</option>';
                                    trHTML += '<option value="8">Sonargaon</option>';
                                    trHTML += '<option value="9">General</option>';
                                trHTML += '</select>';
                            trHTML += '</td>';

                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<select class="select-b-parts-add parts-name" onchange="parts_name(this.value, 1, ' + sl + ')"></select>';
                            trHTML += '</td>';

                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<div class="input-group">';
                                    trHTML += '<input type="number" class="form-control qty" placeholder="Insert" oninput="qty(1)">';
                                        trHTML += '<div class="input-group-prepend">';
                                            trHTML += '<div class="input-group-text"><span id="unit"></span></div>';
                                        trHTML += '</div>';
                                    trHTML += '</div>';
                                trHTML += '<span class="float-left w-100 text-primary in-stock"></span>';
                            trHTML += '</td>';
                            trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control use" placeholder="Insert"></textarea></td>';
                            trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control remarks" placeholder="Insert"></textarea></td>';
                            trHTML += '<td class="align-middle text-center"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input loan" id="loan'+sl+'"><label class="custom-control-label" for="loan'+sl+'"></label></div></td>';
                            trHTML += '<td class="align-middle text-center"><button type="button" class="btn btn-xs btn-danger table-remove"><i class="mdi mdi-delete"></i></button></td>';
                        trHTML += '</tr>';

                        $('#consumable_table').find('table').append(trHTML);

                        $('.select-b-parts-add').select2({
                            width: '100%',
                            placeholder: 'Choose',
                            allowClear: false,
                            language: {
                                inputTooShort: function(){
                                    return 'Insert at least 2 characters.';
                                },
                                noResults: function(){
                                    return 'No results found.';
                                },
                                searching: function(){
                                    return 'Fetching parts...';
                                }
                            },
                            minimumInputLength: 2,
                            ajax: {
                                url: function(){
                                    if($(this).hasClass('select-b-req')){
                                        return '../../api/miscellaneous';
                                    } else{
                                        return '../../api/parts';
                                    }
                                },
                                dataType: 'json',
                                method: 'POST',
                                delay: 250,
                                cache: true,
                                data: function(param){
                                    if($(this).hasClass('select-b-req')){
                                        return{
                                            miscellaneous_data_type: 'fetch_req_for_by_srch_str',
                                            search_str: param.term
                                        };
                                    } else{
                                        return{
                                            parts_data_type: 'fetch_all_by_srch_str',
                                            parts_category: 2,
                                            search_str: param.term
                                        };
                                    }
                                },
                                processResults: function(data){
                                    let result = '';

                                    if($(this)[0].$element.hasClass('select-b-req')){
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.id,
                                                text: item.req_for
                                            };
                                        });
                                    } else{
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.parts_id+'|'+item.parts_unit+'|'+item.parts_qty,
                                                text: item.parts_name
                                            };
                                        });
                                    }

                                    return{
                                        results: result
                                    };
                                }
                            }
                        });

                        sl++;
                    }

                    let user_category = '<?= $user_category ?>';

                    if(parseInt(user_category) == 2)
                        $('.approval').addClass('d-none');

                    $('.accept-div button').addClass('d-none');
                });

                // REMOVE CONSUMABLE TABLE ROW
                $('#consumable_table').on('click', '.table-remove', function(){
                    if($('#consumable_table').find('tbody tr').length === 1){
                        Swal.fire({
                            title: 'Error',
                            text: 'Row cannot be deleted!',
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'The table should contain at least one row.'
                        });
                    } else{
                        $(this).parents('tr').detach();
                    }
                });

                // ADD SPARE TABLE ROW
                $('.table-add-2').on('click', function(){
                    $('#spare_table tr').each(function(){
                        let required_for = $(this).find('td:eq(1)').find('select').val(),
                            parts = $(this).find('td:eq(2)').find('select').val(),
                            qty = $(this).find('td:eq(3)').find('input').val(),
                            old = $(this).find('td:eq(4)').find('textarea').val(),
                            status = $(this).find('td:eq(5)').find('select').val(),
                            remarks = $(this).find('td:eq(6)').find('textarea').val();

                        if(required_for === '' || parts === '' || qty === '' || old === '' || status === '' || remarks === '')
                            $(this).find('td:eq(0)').find('span').removeClass('d-none');
                        else
                           $(this).find('td:eq(0)').find('span').addClass('d-none');
                    });

                    let flag = 1;

                    $('#spare_table tr').each(function(){
                        let required_for = $(this).find('td:eq(1)').find('select').val(),
                            parts = $(this).find('td:eq(2)').find('select').val(),
                            qty = $(this).find('td:eq(3)').find('input').val(),
                            old = $(this).find('td:eq(4)').find('textarea').val(),
                            status = $(this).find('td:eq(5)').find('select').val(),
                            remarks = $(this).find('td:eq(6)').find('textarea').val();

                        if(required_for === '' || parts === '' || qty === '' || old === '' || status === '' || remarks === ''){
                            flag = 0;

                            Swal.fire({
                                title: 'Error',
                                text: 'Empty table row data!',
                                type: 'error',
                                width: 450,
                                showCloseButton: true,
                                confirmButtonColor: '#5cb85c',
                                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                footer: 'Please fill all data in the table row.'
                            });

                            return false;
                        } else{
                            flag = 1;
                        }
                    });

                    if(flag == 1){
                        let trHTML = '';

                        trHTML += '<tr>';
                            trHTML += '<td class="align-middle text-center">' + sl2 + ' <span class="text-danger d-none">*</span></td>';
                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<select class="select-custom required-for-2">';
                                    trHTML += '<option value="">Choose</option>';
                                    trHTML += '<option value="1">BCP-CCM</option>';
                                    trHTML += '<option value="2">BCP-Furnace</option>';
                                    trHTML += '<option value="3">Concast-CCM</option>';
                                    trHTML += '<option value="4">Concast-Furnace</option>';
                                    trHTML += '<option value="5">HRM</option>';
                                    trHTML += '<option value="6">HRM Unit-2</option>';
                                    trHTML += '<option value="7">Lal Masjid</option>';
                                    trHTML += '<option value="8">Sonargaon</option>';
                                    trHTML += '<option value="9">General</option>';
                                trHTML += '</select>';
                            trHTML += '</td>';

                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<select data-placeholder="Choose" class="select-b-parts-add-2 parts-name-2" onchange="parts_name(this.value, 2, ' + sl2 + ')"></select>';
                            trHTML += '</td>';

                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<div class="input-group">';
                                    trHTML += '<input type="number" class="form-control qty-2" placeholder="Insert" oninput="qty(2)">';
                                    trHTML += '<div class="input-group-prepend">';
                                        trHTML += '<div class="input-group-text"><span id="unit_2"></span></div>';
                                    trHTML += '</div>';
                                trHTML += '</div>';
                                trHTML += '<span class="float-left w-100 text-primary in-stock-2"></span>';
                            trHTML += '</td>';
                            trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control old" placeholder="Insert"></textarea></td>';
                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<select class="select-custom status-2">';
                                    trHTML += '<option value="">Choose</option>';
                                    trHTML += '<option value="1">Repairable</option>';
                                    trHTML += '<option value="2">Unusual</option>';
                                trHTML += '</select>';
                            trHTML += '</td>';
                            trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control remarks-2" placeholder="Insert"></textarea></td>';
                            trHTML += '<td class="align-middle text-center"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input loan-2" id="loan_2'+sl2+'"><label class="custom-control-label" for="loan_2'+sl2+'"></label></div></td>';
                            trHTML += '<td class="align-middle text-center"><button type="button" class="btn btn-xs btn-danger table-remove"><i class="mdi mdi-delete"></i></button></td>';
                        trHTML += '</tr>';

                        $('#spare_table').find('table').append(trHTML);

                        $('.select-b-parts-add-2').select2({
                            width: '100%',
                            placeholder: 'Choose',
                            allowClear: false,
                            language: {
                                inputTooShort: function(){
                                    return 'Insert at least 2 characters.';
                                },
                                noResults: function(){
                                    return 'No results found.';
                                },
                                searching: function(){
                                    return 'Fetching parts...';
                                }
                            },
                            minimumInputLength: 2,
                            ajax: {
                                url: function(){
                                    if($(this).hasClass('select-b-req') || $(this).hasClass('select-b-status')){
                                        return '../../api/miscellaneous';
                                    } else{
                                        return '../../api/parts';
                                    }
                                },
                                dataType: 'json',
                                method: 'POST',
                                delay: 250,
                                cache: true,
                                data: function(param){
                                    if($(this).hasClass('select-b-req')){
                                        return{
                                            miscellaneous_data_type: 'fetch_req_for_by_srch_str',
                                            search_str: param.term
                                        };
                                    } else if($(this).hasClass('select-b-parts-add-2')){
                                        return{
                                            parts_data_type: 'fetch_all_by_srch_str',
                                            parts_category: 1,
                                            search_str: param.term
                                        };
                                    } else{
                                        return{
                                            miscellaneous_data_type: 'fetch_status_by_srch_str',
                                            search_str: param.term
                                        };
                                    }
                                },
                                processResults: function(data){
                                    let result = '';

                                    if($(this)[0].$element.hasClass('select-b-req')){
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.id,
                                                text: item.req_for
                                            };
                                        });
                                    } else if($(this)[0].$element.hasClass('select-b-parts-add-2')){
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.parts_id+'|'+item.parts_unit+'|'+item.parts_qty,
                                                text: item.parts_name
                                            };
                                        });
                                    } else{
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.id,
                                                text: item.status
                                            };
                                        });
                                    }

                                    return{
                                        results: result
                                    };
                                }
                            }
                        });

                        sl2++;
                    }

                    let user_category = '<?= $user_category ?>';

                    if(parseInt(user_category) == 2)
                        $('.approval-2').addClass('d-none');

                    $('.accept-div-2 button').addClass('d-none');
                });

                // REMOVE SPARE TABLE ROW
                $('#spare_table').on('click', '.table-remove', function(){
                    if($('#spare_table').find('tbody tr').length === 1){
                        Swal.fire({
                            title: 'Error',
                            text: 'Row cannot be deleted!',
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'The table should contain at least one row.'
                        });
                    } else{
                        $(this).parents('tr').detach();
                    }
                });

                $('.full-width-modal, .full-width-modal-2').on('shown.bs.modal', function(){
                    $('#consumable_table, #spare_table').animate({
                        scrollTop: 0,
                        scrollLeft: 0
                    }, 'faster');
                });

                $('.scroll-left').click(function(){
                    $('#consumable_table, #spare_table').animate({scrollLeft: '-=400'}, 'faster');
                });

                $('.scroll-right').click(function(){
                    $('#consumable_table, #spare_table').animate({scrollLeft: '+=400'}, 'faster');
                });
            });

            // PRINT REQUISITION
            function print_requisition(){
                let table_length = $('.print-div').find('table tr').length;

                if(parseInt(table_length) <= 5)
                    $('.print-div').css('margin-bottom', '320px');
                else if(parseInt(table_length) > 5 && parseInt(table_length) <= 10)
                    $('.print-div').css('margin-bottom', '160px');
                else if(parseInt(table_length) > 10 && parseInt(table_length) <= 12)
                    $('.print-div').css('margin-bottom', '80px');
                else if(parseInt(table_length) == 13)
                    $('.print-div').css('margin-bottom', '40px');
                else if(parseInt(table_length) > 13 && parseInt(table_length) <= 15)
                    $('.page-break').removeClass('d-none');
                else
                    $('.print-div').css('margin-bottom', '120px');

                let restore_page = $('body').html(),
                    print_content = $('.requisition-print').clone();

                $('body').empty().html(print_content);
                window.print();
                $('body').html(restore_page);
            }

            // GET PARTS NAME
            function parts_name(ele, ele2, ele3){
                let parts_arr = ele.split('|'),
                    unit = parts_arr[1],
                    unit_class = '',
                    in_stock = parts_arr[2];

                if(ele2 == 1)
                    unit_class = $('#consumable_table tbody').find('tr:nth-child(' + ele3 + ')').find('td:eq(3)').find('span');
                else
                    unit_class = $('#spare_table tbody').find('tr:nth-child(' + ele3 + ')').find('td:eq(3)').find('span');

                if(unit == 1)
                    unit_class.html('Bag');
                else if(unit == 2)
                    unit_class.html('Box');
                else if(unit == 3)
                    unit_class.html('Box/Pcs');
                else if(unit == 4)
                    unit_class.html('Bun');
                else if(unit == 5)
                    unit_class.html('Bundle');
                else if(unit == 6)
                    unit_class.html('Can');
                else if(unit == 7)
                    unit_class.html('Cartoon');
                else if(unit == 8)
                    unit_class.html('Challan');
                else if(unit == 9)
                    unit_class.html('Coil');
                else if(unit == 10)
                    unit_class.html('Drum');
                else if(unit == 11)
                    unit_class.html('Feet');
                else if(unit == 12)
                    unit_class.html('Gallon');
                else if(unit == 13)
                    unit_class.html('Item');
                else if(unit == 14)
                    unit_class.html('Job');
                else if(unit == 15)
                    unit_class.html('Kg');
                else if(unit == 16)
                    unit_class.html('Kg/Bundle');
                else if(unit == 17)
                    unit_class.html('Kv');
                else if(unit == 18)
                    unit_class.html('Lbs');
                else if(unit == 19)
                    unit_class.html('Ltr');
                else if(unit == 20)
                    unit_class.html('Mtr');
                else if(unit == 21)
                    unit_class.html('Pack');
                else if(unit == 22)
                    unit_class.html('Pack/Pcs');
                else if(unit == 23)
                    unit_class.html('Pair');
                else if(unit == 24)
                    unit_class.html('Pcs');
                else if(unit == 25)
                    unit_class.html('Pound');
                else if(unit == 26)
                    unit_class.html('Qty');
                else if(unit == 27)
                    unit_class.html('Roll');
                else if(unit == 28)
                    unit_class.html('Set');
                else if(unit == 29)
                    unit_class.html('Truck');
                else if(unit == 30)
                    unit_class.html('Unit');
                else if(unit == 31)
                    unit_class.html('Yeard');
                else if(unit == 32)
                    unit_class.html('(Unit Unknown)');
                else if(unit == 33)
                    unit_class.html('SFT');
                else if(unit == 34)
                    unit_class.html('RFT');
                else if(unit == 35)
                    unit_class.html('CFT');

                if(ele2 == 1)
                    $('#consumable_table').find('tr:nth-child(' + ele3 + ')').find('td:eq(3)').find('.in-stock').addClass('mt-1').html('In Stock: <strong>' + in_stock + '</strong> ' + unit_class.html() + '.');
                else
                    $('#spare_table').find('tr:nth-child(' + ele3 + ')').find('td:eq(3)').find('.in-stock-2').addClass('mt-1').html('In Stock: <strong>' + in_stock + '</strong> ' + unit_class.html() + '.');
            }

            // GET PARTS QTY
            function qty(ele){
                if(ele == 1){
                    $('#consumable_table tbody tr').each(function(){
                        if($(this).find('td:eq(3)').find('.qty').val() <= 0)
                            $(this).find('td:eq(3)').find('.qty').val('');
                    });
                } else{
                    $('#spare_table tbody tr').each(function(){
                        if($(this).find('td:eq(3)').find('.qty-2').val() <= 0)
                            $(this).find('td:eq(3)').find('.qty-2').val('');
                    });
                }
            }

            let requisition_data = '',
                con_s_approval_status = 0,
                con_p_approval_status = 0,
                con_approval_status = 0;

            // UPDATE CONSUMABLE REQUISITION
            function update_requisition(ele){
                let user_category = '<?= $user_category ?>';

                $('.modal-title').html('Update Requisition');
                $('#first').addClass('active');
                $('#consumable_nav').addClass('active');
                $('#second').removeClass('fade active show');
                $('#spare_nav').removeClass('active');
                $('.requisition-ul').addClass('d-none');
                $('.table-add-1').removeClass('d-none');

                let t;

                Swal.fire({
                    title: 'Fetching Requisition Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    // FETCH REQUISITION DATA
                    let requisition_id = ele;

                    $.ajax({
                        url: '../../api/requisition',
                        method: 'post',
                        data: {
                            requisition_data_type: 'fetch_consumable',
                            requisition_id: requisition_id
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                requisition_data = data;
                                con_s_approval_status = data.Reply[0].s_approval_status;
                                con_p_approval_status = data.Reply[0].p_approval_status;
                                con_approval_status = data.Reply[0].approval_status;
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

                    let trHTML = '';

                    $.each(requisition_data.Reply, function(i, requisition_item){
                        trHTML += '<tr>';
                            trHTML += '<td class="align-middle">' + (i+1) + '</td>';

                            let required_for = '';

                            if(requisition_item.required_for == 1)
                                required_for = 'BCP-CCM';
                            else if(requisition_item.required_for == 2)
                                required_for = 'BCP-Furnace';
                            else if(requisition_item.required_for == 3)
                                required_for = 'Concast-CCM';
                            else if(requisition_item.required_for == 4)
                                required_for = 'Concast-Furnace';
                            else if(requisition_item.required_for == 5)
                                required_for = 'HRM';
                            else if(requisition_item.required_for == 6)
                                required_for = 'HRM Unit-2';
                            else if(requisition_item.required_for == 7)
                                required_for = 'Lal Masjid';
                            else if(requisition_item.required_for == 8)
                                required_for = 'Sonargaon';
                            else if(requisition_item.required_for == 9)
                                required_for = 'General';

                            if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + required_for + '</td>';
                            } else{
                                trHTML += '<td class="align-middle text-center">';
                                    trHTML += '<select class="select-custom required-for">';
                                        trHTML += '<option value="">Choose</option>';
                                        trHTML += '<option value="1" ' + ((requisition_item.required_for == 1) ? 'selected' : '') + '>BCP-CCM</option>';
                                        trHTML += '<option value="2" ' + ((requisition_item.required_for == 2) ? 'selected' : '') + '>BCP-Furnace</option>';
                                        trHTML += '<option value="3" ' + ((requisition_item.required_for == 3) ? 'selected' : '') + '>Concast-CCM</option>';
                                        trHTML += '<option value="4" ' + ((requisition_item.required_for == 4) ? 'selected' : '') + '>Concast-Furnace</option>';
                                        trHTML += '<option value="5" ' + ((requisition_item.required_for == 5) ? 'selected' : '') + '>HRM</option>';
                                        trHTML += '<option value="6" ' + ((requisition_item.required_for == 6) ? 'selected' : '') + '>HRM Unit-2</option>';
                                        trHTML += '<option value="7" ' + ((requisition_item.required_for == 7) ? 'selected' : '') + '>Lal Masjid</option>';
                                        trHTML += '<option value="8" ' + ((requisition_item.required_for == 8) ? 'selected' : '') + '>Sonargaon</option>';
                                        trHTML += '<option value="9" ' + ((requisition_item.required_for == 9) ? 'selected' : '') + '>General</option>';
                                    trHTML += '</select>';
                                trHTML += '</td>';
                            }

                            if(((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4)){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.parts_name + '</td>';
                            } else if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 0) || parseInt(user_category) == 2 || parseInt(user_category) == 3){
                                trHTML += '<td class="align-middle text-center">';
                                    trHTML += '<select class="select-b-parts-upd parts-name" onchange="parts_name(this.value, 1, ' + (i+1) + '"><option value="'+requisition_item.parts_id+'" selected>'+requisition_item.parts_name+'</option></select>';
                                trHTML += '</td>';
                            }

                            trHTML += '<td class="align-middle text-center">';
                                let parts_unit = '';

                                if(requisition_item.parts_unit == 1)
                                    parts_unit = 'Bag';
                                else if(requisition_item.parts_unit == 2)
                                    parts_unit = 'Box';
                                else if(requisition_item.parts_unit == 3)
                                    parts_unit = 'Box/Pcs';
                                else if(requisition_item.parts_unit == 4)
                                    parts_unit = 'Bun';
                                else if(requisition_item.parts_unit == 5)
                                    parts_unit = 'Bundle';
                                else if(requisition_item.parts_unit == 6)
                                    parts_unit = 'Can';
                                else if(requisition_item.parts_unit == 7)
                                    parts_unit = 'Cartoon';
                                else if(requisition_item.parts_unit == 8)
                                    parts_unit = 'Challan';
                                else if(requisition_item.parts_unit == 9)
                                    parts_unit = 'Coil';
                                else if(requisition_item.parts_unit == 10)
                                    parts_unit = 'Drum';
                                else if(requisition_item.parts_unit == 11)
                                    parts_unit = 'Feet';
                                else if(requisition_item.parts_unit == 12)
                                    parts_unit = 'Gallon';
                                else if(requisition_item.parts_unit == 13)
                                    parts_unit = 'Item';
                                else if(requisition_item.parts_unit == 14)
                                    parts_unit = 'Job';
                                else if(requisition_item.parts_unit == 15)
                                    parts_unit = 'Kg';
                                else if(requisition_item.parts_unit == 16)
                                    parts_unit = 'Kg/Bundle';
                                else if(requisition_item.parts_unit == 17)
                                    parts_unit = 'Kv';
                                else if(requisition_item.parts_unit == 18)
                                    parts_unit = 'Lbs';
                                else if(requisition_item.parts_unit == 19)
                                    parts_unit = 'Ltr';
                                else if(requisition_item.parts_unit == 20)
                                    parts_unit = 'Mtr';
                                else if(requisition_item.parts_unit == 21)
                                    parts_unit = 'Pack';
                                else if(requisition_item.parts_unit == 22)
                                    parts_unit = 'Pack/Pcs';
                                else if(requisition_item.parts_unit == 23)
                                    parts_unit = 'Pair';
                                else if(requisition_item.parts_unit == 24)
                                    parts_unit = 'Pcs';
                                else if(requisition_item.parts_unit == 25)
                                    parts_unit = 'Pound';
                                else if(requisition_item.parts_unit == 26)
                                    parts_unit = 'Qty';
                                else if(requisition_item.parts_unit == 27)
                                    parts_unit = 'Roll';
                                else if(requisition_item.parts_unit == 28)
                                    parts_unit = 'Set';
                                else if(requisition_item.parts_unit == 29)
                                    parts_unit = 'Truck';
                                else if(requisition_item.parts_unit == 30)
                                    parts_unit = 'Unit';
                                else if(requisition_item.parts_unit == 31)
                                    parts_unit = 'Yeard';
                                else if(requisition_item.parts_unit == 32)
                                    parts_unit = '(Unit Unknown)';
                                else if(requisition_item.parts_unit == 33)
                                    parts_unit = 'SFT';
                                else if(requisition_item.parts_unit == 34)
                                    parts_unit = 'RFT';
                                else if(requisition_item.parts_unit == 35)
                                    parts_unit = 'CFT';

                                if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                    trHTML += requisition_item.r_qty + ' ' + parts_unit;
                                } else{
                                    trHTML += '<div class="input-group">';
                                        trHTML += '<input type="number" class="form-control qty" placeholder="Insert" value="'+requisition_item.r_qty+'" oninput="qty(1)">';
                                        trHTML += '<div class="input-group-prepend">';
                                            trHTML += '<div class="input-group-text"><span id="unit">' + parts_unit + '</span></div>';
                                        trHTML += '</div>';
                                    trHTML += '</div>';
                                }

                                trHTML += '<span class="float-left text-primary w-100 mt-1 in-stock">In Stock: <strong>' + requisition_item.i_qty + '</strong> ' + parts_unit + '</span>';
                            trHTML += '</td>';

                            if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.parts_usage + '</td>';
                            } else{
                                trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control use" placeholder="Insert">' + requisition_item.parts_usage + '</textarea></td>';
                            }
                            
                            if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.remarks + '</td>';
                            } else{
                                trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control remarks" placeholder="Insert">' + requisition_item.remarks + '</textarea></td>';
                            }

                            if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.loan + '</td>';
                            } else{
                                let checked = requisition_item.loan == 1 ? 'checked' : '';

                                trHTML += '<td class="align-middle text-center"><div class="custom-control custom-checkbox"><input ' + checked + ' type="checkbox" class="custom-control-input loan" id="loan'+i+'"><label class="custom-control-label" for="loan'+i+'"></label></div></td>';
                            }

                            if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td></td>';
                            } else{
                                trHTML += '<td class="align-middle"><button type="button" class="btn btn-xs btn-danger table-remove"><i class="mdi mdi-delete"></i></button></td>';
                            }
                        trHTML += '</tr>';

                        $('#consumable_table').find('table tbody').empty();
                        $('#consumable_table').find('table').append(trHTML);

                        $('.select-b-parts-upd').select2({
                            width: '100%',
                            placeholder: 'Choose',
                            allowClear: false,
                            language: {
                                inputTooShort: function(){
                                    return 'Insert at least 2 characters.';
                                },
                                noResults: function(){
                                    return 'No results found.';
                                },
                                searching: function(){
                                    return 'Fetching parts...';
                                }
                            },
                            minimumInputLength: 2,
                            ajax: {
                                url: function(){
                                    if($(this).hasClass('select-b-req')){
                                        return '../../api/miscellaneous';
                                    } else{
                                        return '../../api/parts';
                                    }
                                },
                                dataType: 'json',
                                method: 'POST',
                                delay: 250,
                                cache: true,
                                data: function(param){
                                    if($(this).hasClass('select-b-req')){
                                        return{
                                            miscellaneous_data_type: 'fetch_req_for_by_srch_str',
                                            search_str: param.term
                                        };
                                    } else{
                                        return{
                                            parts_data_type: 'fetch_all_by_srch_str',
                                            parts_category: 2,
                                            search_str: param.term
                                        };
                                    }
                                },
                                processResults: function(data){
                                    let result = '';

                                    if($(this)[0].$element.hasClass('select-b-req')){
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.id,
                                                text: item.req_for
                                            };
                                        });
                                    } else{
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.parts_id+'|'+item.parts_unit+'|'+item.parts_qty,
                                                text: item.parts_name
                                            };
                                        });
                                    }

                                    return{
                                        results: result
                                    };
                                }
                            }
                        });
                    });

                    if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 0) || (parseInt(user_category) == 3 && con_s_approval_status == 0 && con_p_approval_status == 0 && con_approval_status == 0)){
                        $('.approval').removeClass('d-none');

                        $('.proceed-div button').removeClass('d-none');
                        $('.accept-div button').addClass('d-none');
                    } else if(parseInt(user_category) == 2){
                        $('.accept-div button').addClass('d-none');
                    } else if(parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1){
                        $('.table-add-1').addClass('d-none');
                        $('.approval').addClass('d-none');
                        
                        $('.proceed-div button').addClass('d-none');
                        $('.accept-div button').removeClass('d-none').attr('onClick', 'approve_requisition('+ requisition_id +', '+ parseInt(user_category) +')');
                    } else if(parseInt(user_category) == 4){
                        $('.table-add-1').addClass('d-none');
                        $('.approval').addClass('d-none');

                        if(con_p_approval_status == 0){
                            $('.proceed-div button').addClass('d-none');
                            $('.accept-div button').removeClass('d-none').attr('onClick', 'approve_requisition('+ requisition_id +', '+ parseInt(user_category) +')');
                        }
                    }
                });
            }

            // PROCEED CONSUMABLE
            function proceed_consumable(){
                let user_category = '<?= $user_category ?>';
                
                $('#consumable_table tr').each(function(){
                    let required_for = $(this).find('td:eq(1)').find('select').val(),
                        parts = $(this).find('td:eq(2)').find('select').val(),
                        qty = $(this).find('td:eq(3)').find('input').val(),
                        use = $(this).find('td:eq(4)').find('textarea').val(),
                        remarks = $(this).find('td:eq(5)').find('textarea').val();

                    if(required_for === '' || parts === '' || qty === '' || use === '' || remarks === '')
                        $(this).find('td:eq(0)').find('span').removeClass('d-none');
                    else
                       $(this).find('td:eq(0)').find('span').addClass('d-none');
                });

                let flag = 1;

                $('#consumable_table tr').each(function(){
                    let required_for = $(this).find('td:eq(1)').find('select').val(),
                        parts = $(this).find('td:eq(2)').find('select').val(),
                        qty = $(this).find('td:eq(3)').find('input').val(),
                        use = $(this).find('td:eq(4)').find('textarea').val(),
                        remarks = $(this).find('td:eq(5)').find('textarea').val();

                    if(required_for === '' || parts === '' || qty === '' || use === '' || remarks === ''){
                        flag = 0;

                        Swal.fire({
                            title: 'Error',
                            text: 'Empty table row data!',
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'Please fill all data in the table row.'
                        });

                        return false;
                    } else{
                        flag = 1;
                    }
                });

                if(flag == 1){
                    let table_data = '';

                    let table_object = $('#consumable_table tbody tr').map(function(i){
                        let row = {
                            'required_for': $(this).find('td:eq(1)').find('select').val(),
                            'parts': $(this).find('td:eq(2)').find('select').val(),
                            'qty': $(this).find('td:eq(3)').find('input').val(),
                            'use': $(this).find('td:eq(4)').find('textarea').val(),
                            'remarks': $(this).find('td:eq(5)').find('textarea').val(),
                            'loan' : $(this).find('td:eq(6)').find('input').is(':checked') ? 1 : 0
                        };

                        table_data += '<tr>';
                            table_data += '<td>' + (i+1) + '</td>';
                            (($(this).find('td:eq(1)').find('.required-for').val() !== '') ? table_data += '<td>' + $(this).find('td:eq(1)').find('.required-for option:selected').text() + '</td>' : table_data += '<td></td>');
                            (($(this).find('td:eq(2)').find('.parts-name').val() !== '') ? table_data += '<td>' + $(this).find('td:eq(2)').find('.parts-name option:selected').text() + '</td>' : table_data += '<td></td>');
                            table_data += '<td>' + parseFloat(row.qty).toFixed(3) + ' ' + $(this).find('td:eq(3)').find('span').html() + '</td>';
                            (($(this).find('td:eq(3)').find('.in-stock').html() !== '') ? table_data += '<td>' + parseFloat($(this).find('td:eq(3)').find('.in-stock').html().replace(/[^0-9.-]+/g, '')).toFixed(3) + ' ' + $(this).find('td:eq(3)').find('span').html() + '</td>' : table_data += '<td></td>');
                            table_data += '<td>' + row.use + '</td>';
                            table_data += '<td>' + row.remarks + '</td>';
                            table_data += '<td>' + ((row.loan == 1) ? 'Loan' : '') + '</td>';
                        table_data += '</tr>';

                        return row;
                    }).get();

                    let table = '<table class="table table-responsive table-striped table-bordered consumable-table" style="max-height: 335px; overflow-x: auto; overflow-y: auto;">';
                            table += '<thead>';
                                table += '<tr>';
                                    table += '<th>SL.</th>';
                                    table += '<th>Required For</th>';
                                    table += '<th>Parts</th>';
                                    table += '<th>Quantity</th>';
                                    table += '<th>In Stock</th>';
                                    table += '<th>Where to Use</th>';
                                    table += '<th>Remarks</th>';
                                    table += '<th>Loan</th>';
                                table += '</tr>';
                            table += '</thead>';
                            table += '<tbody id="requisition_data">';
                                table += table_data;
                            table += '</tbody>';
                        table += '</table>';

                    let t;

                    Swal.fire({
                        title: 'Proceeding',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        Swal.fire({
                            title: 'Requisition Data',
                            html: table,
                            type: 'info',
                            width: 'auto',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '<i class="fas fa-upload"></i>&nbsp;&nbsp; Submit',
                            cancelButtonText: '<i class="fas fa-times"></i>&nbsp;&nbsp; Cancel'
                        }).then(function(result){
                            if(result.value){
                                let requisition_table_data = JSON.stringify(table_object);
                                
                                let requisition_id = 0;
                                if(requisition_data)
                                    requisition_id = requisition_data.Reply[0].requisition_id;

                                let approval_status = $('.approval-status').is(':checked');
                                if(approval_status == true)
                                    approval_status = 1;
                                else
                                    approval_status = 0;
                                
                                let interact_type = 'add',
                                    title = 'Adding Requisition';
                                if(requisition_id > 0){
                                    interact_type = 'update';
                                    title = 'Updating Requisition';
                                }

                                $.ajax({
                                    url: '../../api/interactionController',
                                    method: 'post',
                                    data: {
                                        interact_type: interact_type,
                                        interact: 'requisition',
                                        requisition_id: requisition_id,
                                        approval_status: approval_status,
                                        requisition_data: requisition_table_data
                                    },
                                    dataType: 'json',
                                    cache: false,
                                    success: function(data){
                                        if(data.Type == 'success'){
                                            let t;

                                            Swal.fire({
                                                title: title,
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
                                                        // window.location.reload(true);

                                                        $('#consumable_body').load(' #consumable_body > *');
                                                        $('.full-width-modal').modal('hide');
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
                                    },
                                });
                            }
                        });
                    });
                }
            }

            // VIEW CONSUMABLE REQUISITION
            function view_con_requisition(ele){
                let requisition_id = ele;

                $.ajax({
                    url: '../../api/requisition',
                    method: 'post',
                    data: {
                        requisition_data_type: 'fetch_consumable',
                        requisition_id: requisition_id
                    },
                    dataType: 'json',
                    cache: false,
                    async: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Fetching Requisition Data',
                                text: 'Please wait...',
                                timer: 100,
                                allowOutsideClick: false,
                                onBeforeOpen: function(){
                                    Swal.showLoading(), t = setInterval(function(){
                                    }, 100);
                                }
                            }).then(function(){
                                let reference = data.Reply[0].reference;
                                if(requisition_id < 10)
                                    requisition_id = reference + '0' + requisition_id;

                                let approval_status = data.Reply[0].approval_status;
                                if(approval_status == 0)
                                    approval_status = 'Pending';
                                else if(approval_status == 1)
                                    approval_status = 'Approved';
                                else if(approval_status == 2)
                                    approval_status = 'Rejected';

                                $('.requisition-type').html('Consumable');
                                $('.reference-span').html(data.Reply[0].reference + requisition_id);
                                $('.requisition-date-span').html(data.Reply[0].requisition_created);
                                $('.approval-status-span').html(approval_status);
                                $('.requisition-by-span').html(data.Reply[0].requisition_by);
                                $('.approved-by-span').html(data.Reply[0].approved_by);

                                let trHTML = '',
                                    required_for = '',
                                    parts_unit = '',
                                    price = '',
                                    total_price = 0;

                                $.each(data.Reply, function(i, requisition_item){
                                    if(requisition_item.required_for == 1)
                                        required_for = 'BCP-CCM';
                                    else if(requisition_item.required_for == 2)
                                        required_for = 'BCP-Furnace';
                                    else if(requisition_item.required_for == 3)
                                        required_for = 'Concast-CCM';
                                    else if(requisition_item.required_for == 4)
                                        required_for = 'Concast-Furnace';
                                    else if(requisition_item.required_for == 5)
                                        required_for = 'HRM';
                                    else if(requisition_item.required_for == 6)
                                        required_for = 'HRM Unit-2';
                                    else if(requisition_item.required_for == 7)
                                        required_for = 'Lal Masjid';
                                    else if(requisition_item.required_for == 8)
                                        required_for = 'Sonargaon';
                                    else if(requisition_item.required_for == 9)
                                        required_for = 'General';

                                    if(requisition_item.parts_unit == 1)
                                        parts_unit = 'Bag';
                                    else if(requisition_item.parts_unit == 2)
                                        parts_unit = 'Box';
                                    else if(requisition_item.parts_unit == 3)
                                        parts_unit = 'Box/Pcs';
                                    else if(requisition_item.parts_unit == 4)
                                        parts_unit = 'Bun';
                                    else if(requisition_item.parts_unit == 5)
                                        parts_unit = 'Bundle';
                                    else if(requisition_item.parts_unit == 6)
                                        parts_unit = 'Can';
                                    else if(requisition_item.parts_unit == 7)
                                        parts_unit = 'Cartoon';
                                    else if(requisition_item.parts_unit == 8)
                                        parts_unit = 'Challan';
                                    else if(requisition_item.parts_unit == 9)
                                        parts_unit = 'Coil';
                                    else if(requisition_item.parts_unit == 10)
                                        parts_unit = 'Drum';
                                    else if(requisition_item.parts_unit == 11)
                                        parts_unit = 'Feet';
                                    else if(requisition_item.parts_unit == 12)
                                        parts_unit = 'Gallon';
                                    else if(requisition_item.parts_unit == 13)
                                        parts_unit = 'Item';
                                    else if(requisition_item.parts_unit == 14)
                                        parts_unit = 'Job';
                                    else if(requisition_item.parts_unit == 15)
                                        parts_unit = 'Kg';
                                    else if(requisition_item.parts_unit == 16)
                                        parts_unit = 'Kg/Bundle';
                                    else if(requisition_item.parts_unit == 17)
                                        parts_unit = 'Kv';
                                    else if(requisition_item.parts_unit == 18)
                                        parts_unit = 'Lbs';
                                    else if(requisition_item.parts_unit == 19)
                                        parts_unit = 'Ltr';
                                    else if(requisition_item.parts_unit == 20)
                                        parts_unit = 'Mtr';
                                    else if(requisition_item.parts_unit == 21)
                                        parts_unit = 'Pack';
                                    else if(requisition_item.parts_unit == 22)
                                        parts_unit = 'Pack/Pcs';
                                    else if(requisition_item.parts_unit == 23)
                                        parts_unit = 'Pair';
                                    else if(requisition_item.parts_unit == 24)
                                        parts_unit = 'Pcs';
                                    else if(requisition_item.parts_unit == 25)
                                        parts_unit = 'Pound';
                                    else if(requisition_item.parts_unit == 26)
                                        parts_unit = 'Qty';
                                    else if(requisition_item.parts_unit == 27)
                                        parts_unit = 'Roll';
                                    else if(requisition_item.parts_unit == 28)
                                        parts_unit = 'Set';
                                    else if(requisition_item.parts_unit == 29)
                                        parts_unit = 'Truck';
                                    else if(requisition_item.parts_unit == 30)
                                        parts_unit = 'Unit';
                                    else if(requisition_item.parts_unit == 31)
                                        parts_unit = 'Yeard';
                                    else if(requisition_item.parts_unit == 32)
                                        parts_unit = '(Unit Unknown)';
                                    else if(requisition_item.parts_unit == 33)
                                        parts_unit = 'SFT';
                                    else if(requisition_item.parts_unit == 34)
                                        parts_unit = 'RFT';
                                    else if(requisition_item.parts_unit == 35)
                                        parts_unit = 'CFT';

                                    if(requisition_item.price != null)
                                        price = '<i class="mdi mdi-currency-bdt"></i>' + requisition_item.price;

                                    trHTML += '<tr>';
                                        trHTML += '<td class="text-center">' + (i+1) + '</td>';
                                        trHTML += '<td class="text-center">' + required_for + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.parts_name + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.r_qty + ' ' + parts_unit + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.i_qty + ' ' + parts_unit + '</td>';
                                        trHTML += '<td class="text-center">' + price + '</td>';
                                        trHTML += '<td class="text-center">' + '<i class="mdi mdi-currency-bdt"></i>' + (parseFloat(requisition_item.price) * parseInt(requisition_item.r_qty)).toFixed(2) + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.parts_usage + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.remarks + '</td>';
                                    trHTML += '</tr>';

                                    total_price = +total_price + +(requisition_item.price * requisition_item.r_qty);
                                });

                                $('.print-title').empty().append('<tr>\
                                                            <th class="text-center">SL.</th>\
                                                            <th class="text-center">Required For</th>\
                                                            <th class="text-center">Parts</th>\
                                                            <th class="text-center">Quantity</th>\
                                                            <th class="text-center">In Stock</th>\
                                                            <th class="text-center">Latest Unit Price</th>\
                                                            <th class="text-center">Total Price</th>\
                                                            <th class="text-center">Where to Use</th>\
                                                            <th class="text-center">Remarks</th>\
                                                        </tr>');

                                $('.print-records').empty().append(trHTML);

                                $('.print-footer').empty().append('<tr>\
                                                            <th colspan="9" class="text-center">Grand Total Price ='+ (!isNaN(total_price) ? ' <i class="mdi mdi-currency-bdt"></i>' + total_price.toFixed(2) : ' N/A') +'</th>\
                                                        </tr>');

                                let a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
                                let b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

                                // AMOUNT IN WORDS
                                function in_words(ele){
                                    if((ele = ele.toString()).length > 9)
                                        return 'Overflow';

                                    let n = ('000000000' + ele).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);

                                    if(!n)
                                        return;

                                    let str = '';
                                    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
                                    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
                                    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
                                    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
                                    str += (n[5] != 0) ? ((str != '') ? 'And ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
                                    
                                    return str;
                                }

                                total_price = Math.ceil(total_price);

                                if(total_price == 0)
                                    $('.total-price-in-words').html('Amount in Words: Zero Taka Only (Approx.).');
                                else
                                    $('.total-price-in-words').html('Amount in Words: ' + (price ? in_words(total_price) + ' Taka Only.' : ' N/A.'));

                                $('.r-sign').html(data.Reply[0].requisition_by);
                                $('.s-sign').html(data.Reply[0].s_approved_by);
                                $('.p-sign').html(data.Reply[0].p_approved_by);
                                $('.e-sign').html('<br>');
                                // $('.a-sign').html('<img width="150" height="18" src="../../assets/images/signature.png">');
                                // $('.a-sign').html(data.Reply[0].approved_by);
                                $('.a-sign').html('<br>');

                                $('.foot-note').html('*** This is a software generated requisition copy. Hence, no handwritten signature required for Requisite Person, Store Incharge & Purchase Incharge.');
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
            }

            let requisition_data2 = '',
                spr_s_approval_status = 0,
                spr_p_approval_status = 0,
                spr_approval_status = 0;

            // UPDATE SPARE REQUISITION
            function update_requisition2(ele){
                let user_category = '<?= $user_category ?>';

                $('.modal-title').html('Update Requisition');
                $('#first').removeClass('active');
                $('#consumable_nav').removeClass('active');
                $('#second').addClass('fade active show');
                $('#spare_nav').addClass('active');
                $('.requisition-ul').addClass('d-none');
                $('.table-add-2').removeClass('d-none');

                let t;

                Swal.fire({
                    title: 'Fetching Requisition Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    let requisition_id = ele;

                    $.ajax({
                        url: '../../api/requisition',
                        method: 'post',
                        data: {
                            requisition_data_type: 'fetch_spare',
                            requisition_id: requisition_id
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                requisition_data2 = data;
                                spr_s_approval_status = data.Reply[0].s_approval_status;
                                spr_p_approval_status = data.Reply[0].p_approval_status;
                                spr_approval_status = data.Reply[0].approval_status;
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

                    let trHTML = '';

                    $.each(requisition_data2.Reply, function(i, requisition_item){
                        trHTML += '<tr>';
                            trHTML += '<td class="align-middle">' + (i+1) + '</td>';

                            let required_for = '';

                            if(requisition_item.required_for == 1)
                                required_for = 'BCP-CCM';
                            else if(requisition_item.required_for == 2)
                                required_for = 'BCP-Furnace';
                            else if(requisition_item.required_for == 3)
                                required_for = 'Concast-CCM';
                            else if(requisition_item.required_for == 4)
                                required_for = 'Concast-Furnace';
                            else if(requisition_item.required_for == 5)
                                required_for = 'HRM';
                            else if(requisition_item.required_for == 6)
                                required_for = 'HRM Unit-2';
                            else if(requisition_item.required_for == 7)
                                required_for = 'Lal Masjid';
                            else if(requisition_item.required_for == 8)
                                required_for = 'Sonargaon';
                            else if(requisition_item.required_for == 9)
                                required_for = 'General';

                            if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + required_for + '</td>';
                            } else{
                                trHTML += '<td class="align-middle text-center">';
                                    trHTML += '<select class="select-custom required-for-2">';
                                        trHTML += '<option value="">Choose</option>';
                                        trHTML += '<option value="1" ' + ((requisition_item.required_for == 1) ? 'selected' : '') + '>BCP-CCM</option>';
                                        trHTML += '<option value="2" ' + ((requisition_item.required_for == 2) ? 'selected' : '') + '>BCP-Furnace</option>';
                                        trHTML += '<option value="3" ' + ((requisition_item.required_for == 3) ? 'selected' : '') + '>Concast-CCM</option>';
                                        trHTML += '<option value="4" ' + ((requisition_item.required_for == 4) ? 'selected' : '') + '>Concast-Furnace</option>';
                                        trHTML += '<option value="5" ' + ((requisition_item.required_for == 5) ? 'selected' : '') + '>HRM</option>';
                                        trHTML += '<option value="6" ' + ((requisition_item.required_for == 6) ? 'selected' : '') + '>HRM Unit-2</option>';
                                        trHTML += '<option value="7" ' + ((requisition_item.required_for == 7) ? 'selected' : '') + '>Lal Masjid</option>';
                                        trHTML += '<option value="8" ' + ((requisition_item.required_for == 8) ? 'selected' : '') + '>Sonargaon</option>';
                                        trHTML += '<option value="9" ' + ((requisition_item.required_for == 9) ? 'selected' : '') + '>General</option>';
                                    trHTML += '</select>';
                                trHTML += '</td>';
                            }

                            if(((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4)){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.parts_name + '</td>';
                            } else if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 0) || parseInt(user_category) == 2 || parseInt(user_category) == 3){
                                trHTML += '<td class="align-middle text-center">';
                                    trHTML += '<select class="select-b-parts-upd-2 parts-name-2" onchange="parts_name(this.value, 2, ' + (i+1) + ')"><option value="'+requisition_item.parts_id+'" selected>'+requisition_item.parts_name+'</option></select>';
                                trHTML += '</td>';
                            }

                            trHTML += '<td class="align-middle text-center">';
                                let parts_unit = '';

                                if(requisition_item.parts_unit == 1)
                                    parts_unit = 'Bag';
                                else if(requisition_item.parts_unit == 2)
                                    parts_unit = 'Box';
                                else if(requisition_item.parts_unit == 3)
                                    parts_unit = 'Box/Pcs';
                                else if(requisition_item.parts_unit == 4)
                                    parts_unit = 'Bun';
                                else if(requisition_item.parts_unit == 5)
                                    parts_unit = 'Bundle';
                                else if(requisition_item.parts_unit == 6)
                                    parts_unit = 'Can';
                                else if(requisition_item.parts_unit == 7)
                                    parts_unit = 'Cartoon';
                                else if(requisition_item.parts_unit == 8)
                                    parts_unit = 'Challan';
                                else if(requisition_item.parts_unit == 9)
                                    parts_unit = 'Coil';
                                else if(requisition_item.parts_unit == 10)
                                    parts_unit = 'Drum';
                                else if(requisition_item.parts_unit == 11)
                                    parts_unit = 'Feet';
                                else if(requisition_item.parts_unit == 12)
                                    parts_unit = 'Gallon';
                                else if(requisition_item.parts_unit == 13)
                                    parts_unit = 'Item';
                                else if(requisition_item.parts_unit == 14)
                                    parts_unit = 'Job';
                                else if(requisition_item.parts_unit == 15)
                                    parts_unit = 'Kg';
                                else if(requisition_item.parts_unit == 16)
                                    parts_unit = 'Kg/Bundle';
                                else if(requisition_item.parts_unit == 17)
                                    parts_unit = 'Kv';
                                else if(requisition_item.parts_unit == 18)
                                    parts_unit = 'Lbs';
                                else if(requisition_item.parts_unit == 19)
                                    parts_unit = 'Ltr';
                                else if(requisition_item.parts_unit == 20)
                                    parts_unit = 'Mtr';
                                else if(requisition_item.parts_unit == 21)
                                    parts_unit = 'Pack';
                                else if(requisition_item.parts_unit == 22)
                                    parts_unit = 'Pack/Pcs';
                                else if(requisition_item.parts_unit == 23)
                                    parts_unit = 'Pair';
                                else if(requisition_item.parts_unit == 24)
                                    parts_unit = 'Pcs';
                                else if(requisition_item.parts_unit == 25)
                                    parts_unit = 'Pound';
                                else if(requisition_item.parts_unit == 26)
                                    parts_unit = 'Qty';
                                else if(requisition_item.parts_unit == 27)
                                    parts_unit = 'Roll';
                                else if(requisition_item.parts_unit == 28)
                                    parts_unit = 'Set';
                                else if(requisition_item.parts_unit == 29)
                                    parts_unit = 'Truck';
                                else if(requisition_item.parts_unit == 30)
                                    parts_unit = 'Unit';
                                else if(requisition_item.parts_unit == 31)
                                    parts_unit = 'Yeard';
                                else if(requisition_item.parts_unit == 32)
                                    parts_unit = '(Unit Unknown)';
                                else if(requisition_item.parts_unit == 33)
                                    parts_unit = 'SFT';
                                else if(requisition_item.parts_unit == 34)
                                    parts_unit = 'RFT';
                                else if(requisition_item.parts_unit == 35)
                                    parts_unit = 'CFT';

                                if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4){
                                    trHTML += requisition_item.r_qty + ' ' + parts_unit;
                                } else{
                                    trHTML += '<div class="input-group">';
                                        trHTML += '<input type="number" class="form-control qty-2" placeholder="Insert" value="'+requisition_item.r_qty+'" oninput="qty(2)">';
                                        trHTML += '<div class="input-group-prepend">';
                                            trHTML += '<div class="input-group-text"><span id="unit_2">' + parts_unit + '</span></div>';
                                        trHTML += '</div>';
                                    trHTML += '</div>';
                                }

                                trHTML += '<span class="float-left text-primary w-100 mt-1 in-stock-2">In Stock: <strong>' + requisition_item.i_qty + '</strong> ' + parts_unit + '</span>';
                            trHTML += '</td>';

                            if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.old_spare_details + '</td>';
                            } else{
                                trHTML += '<td class="align-middle text-center"><textarea rows="2" class="form-control old" placeholder="Insert">' + requisition_item.old_spare_details + '</textarea></td>';
                            }

                            if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + ((requisition_item.status == 1) ? 'Repairable' : 'Unusual') + '</td>';
                            } else{
                                trHTML += '<td class="text-center align-middle">';
                                    trHTML += '<select class="select-custom status">';
                                        trHTML += '<option value="">Choose</option>';
                                        trHTML += '<option value="1" ' + ((requisition_item.status == 1) ? 'selected' : '') + '>Repairable</option>';
                                        trHTML += '<option value="2" ' + ((requisition_item.status == 2) ? 'selected' : '') + '>Unusual</option>';
                                    trHTML += '</select>';
                                trHTML += '</td>';
                            }

                            if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.remarks + '</td>';
                            } else{
                                trHTML += '<td class="text-center align-middle"><textarea rows="2" class="form-control remarks-2" placeholder="Insert">' + requisition_item.remarks + '</textarea></td>';
                            }

                            if((parseInt(user_category) == 1 && con_s_approval_status <= 1 && con_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td class="text-center align-middle">' + requisition_item.loan + '</td>';
                            } else{
                                let checked = requisition_item.loan == 1 ? 'checked' : '';

                                trHTML += '<td class="align-middle text-center"><div class="custom-control custom-checkbox"><input ' + checked + ' type="checkbox" class="custom-control-input loan-2" id="loan_2'+i+'"><label class="custom-control-label" for="loan_2'+i+'"></label></div></td>';
                            }

                            if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1) || parseInt(user_category) == 4){
                                trHTML += '<td></td>';
                            } else{
                                trHTML += '<td class="align-middle"><button type="button" class="btn btn-xs btn-danger table-remove"><i class="mdi mdi-delete"></i></button></td>';
                            }
                        trHTML += '</tr>';
                            
                        $('#spare_table').find('table tbody').empty();
                        $('#spare_table').find('table').append(trHTML);

                        $('.select-b-parts-upd-2').select2({
                            width: '100%',
                            placeholder: 'Choose',
                            allowClear: false,
                            language: {
                                inputTooShort: function(){
                                    return 'Insert at least 2 characters.';
                                },
                                noResults: function(){
                                    return 'No results found.';
                                },
                                searching: function(){
                                    return 'Fetching parts...';
                                }
                            },
                            minimumInputLength: 2,
                            ajax: {
                                url: function(){
                                    if($(this).hasClass('select-b-req') || $(this).hasClass('select-b-status')){
                                        return '../../api/miscellaneous';
                                    } else{
                                        return '../../api/parts';
                                    }
                                },
                                dataType: 'json',
                                method: 'POST',
                                delay: 250,
                                cache: true,
                                data: function(param){
                                    if($(this).hasClass('select-b-req')){
                                        return{
                                            miscellaneous_data_type: 'fetch_req_for_by_srch_str',
                                            search_str: param.term
                                        };
                                    } else if($(this).hasClass('select-b-parts-upd-2')){
                                        return{
                                            parts_data_type: 'fetch_all_by_srch_str',
                                            parts_category: 1,
                                            search_str: param.term
                                        };
                                    } else{
                                        return{
                                            miscellaneous_data_type: 'fetch_status_by_srch_str',
                                            search_str: param.term
                                        };
                                    }
                                },
                                processResults: function(data){
                                    let result = '';

                                    if($(this)[0].$element.hasClass('select-b-req')){
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.id,
                                                text: item.req_for
                                            };
                                        });
                                    } else if($(this)[0].$element.hasClass('select-b-parts-upd-2')){
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.parts_id+'|'+item.parts_unit+'|'+item.parts_qty,
                                                text: item.parts_name
                                            };
                                        });
                                    } else{
                                        result = data.Reply.map(function(item){
                                            return{
                                                id: item.id,
                                                text: item.status
                                            };
                                        });
                                    }

                                    return{
                                        results: result
                                    };
                                }
                            }
                        });
                    });

                    if((parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 0) || (parseInt(user_category) == 3 && spr_s_approval_status == 0 && spr_p_approval_status == 0 && spr_approval_status == 0)){
                        $('.approval-2').removeClass('d-none');

                        $('.proceed-div-2 button').removeClass('d-none');
                        $('.accept-div-2 button').addClass('d-none');
                    } else if(parseInt(user_category) == 2){
                        $('.accept-div-2 button').addClass('d-none');
                    } else if(parseInt(user_category) == 1 && spr_s_approval_status <= 1 && spr_approval_status == 1){
                        $('.table-add-2').addClass('d-none');
                        $('.approval-2').addClass('d-none');
                        
                        $('.proceed-div-2 button').addClass('d-none');
                        $('.accept-div-2 button').removeClass('d-none').attr('onClick', 'approve_requisition2('+ requisition_id +', '+ parseInt(user_category) +')');
                    } else if(parseInt(user_category) == 4){
                        $('.table-add-2').addClass('d-none');
                        $('.approval-2').addClass('d-none');

                        if(spr_p_approval_status == 0){
                            $('.proceed-div-2 button').addClass('d-none');
                            $('.accept-div-2 button').removeClass('d-none').attr('onClick', 'approve_requisition2('+ requisition_id +', '+ parseInt(user_category) +')');
                        }
                    }
                });
            }

            // PROCEED SPARE
            function proceed_spare(){
                let user_category = '<?= $user_category ?>';
                
                $('#spare_table tr').each(function(){
                    let required_for = $(this).find('td:eq(1)').find('select').val(),
                        parts = $(this).find('td:eq(2)').find('select').val(),
                        qty = $(this).find('td:eq(3)').find('input').val(),
                        old = $(this).find('td:eq(4)').find('textarea').val(),
                        status = $(this).find('td:eq(5)').find('select').val(),
                        remarks = $(this).find('td:eq(6)').find('textarea').val();

                    if(required_for === '' || parts === '' || qty === '' || old === '' || status === '' || remarks === '')
                        $(this).find('td:eq(0)').find('span').removeClass('d-none');
                    else
                       $(this).find('td:eq(0)').find('span').addClass('d-none');
                });

                let flag = 1;

                $('#spare_table tr').each(function(){
                    let required_for = $(this).find('td:eq(1)').find('select').val(),
                        parts = $(this).find('td:eq(2)').find('select').val(),
                        qty = $(this).find('td:eq(3)').find('input').val(),
                        old = $(this).find('td:eq(4)').find('textarea').val(),
                        status = $(this).find('td:eq(5)').find('select').val(),
                        remarks = $(this).find('td:eq(6)').find('textarea').val();

                    if(required_for === '' || parts === '' || qty === '' || old === '' || status === '' || remarks === ''){
                        flag = 0;

                        Swal.fire({
                            title: 'Error',
                            text: 'Empty table row data!',
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'Please fill all data in the table row.'
                        });

                        return false;
                    } else{
                        flag = 1;
                    }
                });

                if(flag == 1){
                    let table_data = '';

                    let table_object = $('#spare_table tbody tr').map(function(i){
                        let row = {
                            'required_for': $(this).find('td:eq(1)').find('select').val(),
                            'parts': $(this).find('td:eq(2)').find('select').val(),
                            'qty': $(this).find('td:eq(3)').find('input').val(),
                            'old': $(this).find('td:eq(4)').find('textarea').val(),
                            'status': $(this).find('td:eq(5)').find('select').val(),
                            'remarks': $(this).find('td:eq(6)').find('textarea').val(),
                            'loan' : $(this).find('td:eq(7)').find('input').is(':checked') ? 1 : 0
                        };

                        table_data += '<tr>';
                            table_data += '<td>' + (i+1) + '</td>';
                            (($(this).find('td:eq(1)').find('.required-for-2').val() !== '') ? table_data += '<td>' + $(this).find('td:eq(1)').find('.required-for-2 option:selected').text() + '</td>' : table_data += '<td></td>');
                            (($(this).find('td:eq(2)').find('.parts-name-2').val() !== '') ? table_data += '<td>' + $(this).find('td:eq(2)').find('.parts-name-2 option:selected').text() + '</td>' : table_data += '<td></td>');
                            table_data += '<td>' + parseFloat(row.qty).toFixed(3) + ' ' + $(this).find('td:eq(3)').find('span').html() + '</td>';
                            (($(this).find('td:eq(3)').find('.in-stock-2').html() !== '') ? table_data += '<td>' + parseFloat($(this).find('td:eq(3)').find('.in-stock-2').html().replace(/[^0-9.-]+/g, '')).toFixed(3) + ' ' + $(this).find('td:eq(3)').find('span').html() + '</td>' : table_data += '<td></td>');
                            table_data += '<td>' + row.old + '</td>';
                            (($(this).find('td:eq(5)').find('.status').val() !== '') ? table_data += '<td>' + $(this).find('td:eq(5)').find('.status option:selected').text() + '</td>' : table_data += '<td></td>');
                            table_data += '<td>' + row.remarks + '</td>';
                            table_data += '<td>' + ((row.loan == 1) ? 'Loan' : '') + '</td>';
                        table_data += '</tr>';

                        return row;
                    }).get();

                    let table = '<table class="table table-responsive table-striped table-bordered spare-table" style="max-height: 335px; overflow-x: auto; overflow-y: auto;">';
                            table += '<thead>';
                                table += '<tr>';
                                    table += '<th>SL.</td>';
                                    table += '<th>Required For</td>';
                                    table += '<th>Parts</td>';
                                    table += '<th>Quantity</td>';
                                    table += '<th>In Stock</td>';
                                    table += '<th>Old Spare Details</td>';
                                    table += '<th>Status</td>';
                                    table += '<th>Remarks</td>';
                                    table += '<th>Loan</td>';
                                table += '</tr>';
                            table += '</thead>';
                            table += '<tbody id="requisition_data_2">';
                                table += table_data;
                            table += '</tbody>';
                        table += '</table>';

                    let t;

                    Swal.fire({
                        title: 'Proceeding',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        Swal.fire({
                            title: 'Requisition Data',
                            html: table,
                            type: 'info',
                            width: 'auto',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '<i class="fas fa-upload"></i>&nbsp;&nbsp; Submit',
                            cancelButtonText: '<i class="fas fa-times"></i>&nbsp;&nbsp; Cancel'
                        }).then(function(result){
                            if(result.value){
                                let requisition_table_data = JSON.stringify(table_object);
                                
                                let requisition_id = 0;
                                if(requisition_data2)
                                    requisition_id = requisition_data2.Reply[0].requisition_id;

                                let approval_status = $('.approval-status-2').is(':checked');
                                if(approval_status == true)
                                    approval_status = 1;
                                else
                                    approval_status = 0;
                                
                                let interact_type = 'add',
                                    title = 'Adding Requisition';
                                if(requisition_id != 0){
                                    interact_type = 'update';
                                    title = 'Updating Requisition';
                                }

                                $.ajax({
                                    url: '../../api/interactionController',
                                    method: 'post',
                                    data: {
                                        interact_type: interact_type,
                                        interact: 'requisition2',
                                        requisition_id: requisition_id,
                                        approval_status: approval_status,
                                        requisition_data: requisition_table_data
                                    },
                                    dataType: 'json',
                                    cache: false,
                                    success: function(data){
                                        if(data.Type == 'success'){
                                            let t;

                                            Swal.fire({
                                                title: title,
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
                                                        // window.location.reload(true);

                                                        $('#spare_body').load(' #spare_body > *');
                                                        $('.full-width-modal').modal('hide');
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
                                    },
                                });
                            }
                        });
                    });
                }
            }

            // VIEW SPARE REQUISITION
            function view_spr_requisition(ele){
                let requisition_id = ele;

                $.ajax({
                    url: '../../api/requisition',
                    method: 'post',
                    data: {
                        requisition_data_type: 'fetch_spare',
                        requisition_id: requisition_id
                    },
                    dataType: 'json',
                    cache: false,
                    async: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Fetching Requisition Data',
                                text: 'Please wait...',
                                timer: 100,
                                allowOutsideClick: false,
                                onBeforeOpen: function(){
                                    Swal.showLoading(), t = setInterval(function(){
                                    }, 100);
                                }
                            }).then(function(){
                                let reference = data.Reply[0].reference;
                                if(requisition_id < 10)
                                    requisition_id = reference + '0' + requisition_id;

                                let approval_status = data.Reply[0].approval_status;
                                if(approval_status == 0)
                                    approval_status = 'Pending';
                                else if(approval_status == 1)
                                    approval_status = 'Approved';
                                else if(approval_status == 2)
                                    approval_status = 'Rejected';

                                $('.requisition-type').html('Spare');
                                $('.reference-span').html(data.Reply[0].reference + requisition_id);
                                $('.requisition-date-span').html(data.Reply[0].requisition_created);
                                $('.approval-status-span').html(approval_status);
                                $('.requisition-by-span').html(data.Reply[0].requisition_by);
                                $('.approved-by-span').html(data.Reply[0].approved_by);

                                let trHTML = '',
                                    required_for = '',
                                    parts_unit = '',
                                    status = '',
                                    price = '',
                                    total_price = 0;

                                $.each(data.Reply, function(i, requisition_item){
                                    if(requisition_item.required_for == 1)
                                        required_for = 'BCP-CCM';
                                    else if(requisition_item.required_for == 2)
                                        required_for = 'BCP-Furnace';
                                    else if(requisition_item.required_for == 3)
                                        required_for = 'Concast-CCM';
                                    else if(requisition_item.required_for == 4)
                                        required_for = 'Concast-Furnace';
                                    else if(requisition_item.required_for == 5)
                                        required_for = 'HRM';
                                    else if(requisition_item.required_for == 6)
                                        required_for = 'HRM Unit-2';
                                    else if(requisition_item.required_for == 7)
                                        required_for = 'Lal Masjid';
                                    else if(requisition_item.required_for == 8)
                                        required_for = 'Sonargaon';
                                    else if(requisition_item.required_for == 9)
                                        required_for = 'General';

                                    if(requisition_item.status == 1)
                                        status = 'Repairable';
                                    else if(requisition_item.status == 2)
                                        status = 'Unusual';

                                    if(requisition_item.parts_unit == 1)
                                        parts_unit = 'Bag';
                                    else if(requisition_item.parts_unit == 2)
                                        parts_unit = 'Box';
                                    else if(requisition_item.parts_unit == 3)
                                        parts_unit = 'Box/Pcs';
                                    else if(requisition_item.parts_unit == 4)
                                        parts_unit = 'Bun';
                                    else if(requisition_item.parts_unit == 5)
                                        parts_unit = 'Bundle';
                                    else if(requisition_item.parts_unit == 6)
                                        parts_unit = 'Can';
                                    else if(requisition_item.parts_unit == 7)
                                        parts_unit = 'Cartoon';
                                    else if(requisition_item.parts_unit == 8)
                                        parts_unit = 'Challan';
                                    else if(requisition_item.parts_unit == 9)
                                        parts_unit = 'Coil';
                                    else if(requisition_item.parts_unit == 10)
                                        parts_unit = 'Drum';
                                    else if(requisition_item.parts_unit == 11)
                                        parts_unit = 'Feet';
                                    else if(requisition_item.parts_unit == 12)
                                        parts_unit = 'Gallon';
                                    else if(requisition_item.parts_unit == 13)
                                        parts_unit = 'Item';
                                    else if(requisition_item.parts_unit == 14)
                                        parts_unit = 'Job';
                                    else if(requisition_item.parts_unit == 15)
                                        parts_unit = 'Kg';
                                    else if(requisition_item.parts_unit == 16)
                                        parts_unit = 'Kg/Bundle';
                                    else if(requisition_item.parts_unit == 17)
                                        parts_unit = 'Kv';
                                    else if(requisition_item.parts_unit == 18)
                                        parts_unit = 'Lbs';
                                    else if(requisition_item.parts_unit == 19)
                                        parts_unit = 'Ltr';
                                    else if(requisition_item.parts_unit == 20)
                                        parts_unit = 'Mtr';
                                    else if(requisition_item.parts_unit == 21)
                                        parts_unit = 'Pack';
                                    else if(requisition_item.parts_unit == 22)
                                        parts_unit = 'Pack/Pcs';
                                    else if(requisition_item.parts_unit == 23)
                                        parts_unit = 'Pair';
                                    else if(requisition_item.parts_unit == 24)
                                        parts_unit = 'Pcs';
                                    else if(requisition_item.parts_unit == 25)
                                        parts_unit = 'Pound';
                                    else if(requisition_item.parts_unit == 26)
                                        parts_unit = 'Qty';
                                    else if(requisition_item.parts_unit == 27)
                                        parts_unit = 'Roll';
                                    else if(requisition_item.parts_unit == 28)
                                        parts_unit = 'Set';
                                    else if(requisition_item.parts_unit == 29)
                                        parts_unit = 'Truck';
                                    else if(requisition_item.parts_unit == 30)
                                        parts_unit = 'Unit';
                                    else if(requisition_item.parts_unit == 31)
                                        parts_unit = 'Yeard';
                                    else if(requisition_item.parts_unit == 32)
                                        parts_unit = '(Unit Unknown)';
                                    else if(requisition_item.parts_unit == 33)
                                        parts_unit = 'SFT';
                                    else if(requisition_item.parts_unit == 34)
                                        parts_unit = 'RFT';
                                    else if(requisition_item.parts_unit == 35)
                                        parts_unit = 'CFT';

                                    if(requisition_item.price != null)
                                        price = '<i class="mdi mdi-currency-bdt"></i>' + requisition_item.price;

                                    trHTML += '<tr>';
                                        trHTML += '<td class="text-center">' + (i+1) + '</td>';
                                        trHTML += '<td class="text-center">' + required_for + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.parts_name + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.r_qty + ' ' + parts_unit + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.i_qty + ' ' + parts_unit + '</td>';
                                        trHTML += '<td class="text-center">' + price + '</td>';
                                        trHTML += '<td class="text-center">' + '<i class="mdi mdi-currency-bdt"></i>' + (parseFloat(requisition_item.price) * parseInt(requisition_item.r_qty)).toFixed(2) + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.old_spare_details + '</td>';
                                        trHTML += '<td class="text-center">' + status + '</td>';
                                        trHTML += '<td class="text-center">' + requisition_item.remarks + '</td>';
                                    trHTML += '</tr>';

                                    total_price = +total_price + +(requisition_item.price * requisition_item.r_qty);
                                });

                                $('.print-title').empty().append('<tr>\
                                                            <th class="text-center">SL.</th>\
                                                            <th class="text-center">Required For</th>\
                                                            <th class="text-center">Parts</th>\
                                                            <th class="text-center">Quantity</th>\
                                                            <th class="text-center">In Stock</th>\
                                                            <th class="text-center">Latest Unit Price</th>\
                                                            <th class="text-center">Total Price</th>\
                                                            <th class="text-center">Old Spare Details</th>\
                                                            <th class="text-center">Status</th>\
                                                            <th class="text-center" style="min-width: 160px">Remarks</th>\
                                                        </tr>');

                                $('.print-records').empty().append(trHTML);

                                $('.print-footer').empty().append('<tr>\
                                                            <th colspan="11" class="text-center">Grand Total Price ='+ (!isNaN(total_price) ? ' <i class="mdi mdi-currency-bdt"></i>' + total_price.toFixed(2) : ' N/A') +'</th>\
                                                        </tr>');

                                let a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
                                let b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

                                // AMOUNT IN WORDS
                                function in_words(ele){
                                    if((ele = ele.toString()).length > 9)
                                        return 'Overflow';

                                    let n = ('000000000' + ele).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);

                                    if(!n)
                                        return;

                                    let str = '';
                                    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
                                    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
                                    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
                                    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
                                    str += (n[5] != 0) ? ((str != '') ? 'And ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
                                    
                                    return str;
                                }

                                total_price = Math.ceil(total_price);

                                if(total_price == 0)
                                    $('.total-price-in-words').html('Amount in Words: Zero Taka Only (Approx.).');
                                else
                                    $('.total-price-in-words').html('Amount in Words: ' + (price ? in_words(total_price) + ' Taka Only.' : ' N/A.'));

                                $('.r-sign').html(data.Reply[0].requisition_by);
                                $('.s-sign').html(data.Reply[0].s_approved_by);
                                $('.p-sign').html(data.Reply[0].p_approved_by);
                                $('.e-sign').html('<br>');
                                // $('.a-sign').html('<img width="150" height="18" src="../../assets/images/signature.png">');
                                // $('.a-sign').html(data.Reply[0].approved_by);
                                $('.a-sign').html('<br>');

                                $('.foot-note').html('*** This is a software generated requisition copy. Hence, no handwritten signature required for Requisite Person, Store Incharge & Purchase Incharge.');
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
            }
        </script>
    </body>
</html>