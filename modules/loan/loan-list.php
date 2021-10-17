<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM Inventory by RRM Gorup | Loan</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 3, 4];
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

            .consumable-table > thead > tr > th{
                vertical-align: middle;
            }
            .spare-table > tbody > tr > td{
                vertical-align: middle;
            }

            .datatable-5 > tbody > tr > td{
                vertical-align: middle;
            }

            .hr-custom-style{
                border-top: 1px solid #808080;
            }

            #consumable_table, #spare_table{
                max-height: 395px;
                overflow-x: auto;
                overflow-y: auto;
                border: 1px solid #dee2e6;
            }

            #loan_view_table{
                max-height: 375px;
                overflow-x: auto;
                overflow-y: auto;
                border: 1px solid #dee2e6;
            }

            #loan_repay_table, #loan_repay_history_table{
                max-height: 410px;
                overflow-x: auto;
                overflow-y: auto;
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
                                    <li class="breadcrumb-item active">Loan List</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-swap-vertical-variant"></i> Loan List</h4>
                        </div>
                    </div>
                </div>     
                <!-- end page title -->

                <!-- Start Modals For Create / Update Loan -->
                <div class="modal fade full-width-modal" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="full-width-modalLabel">Loan Parts</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>

                            <div class="modal-body" style="height: 500px; overflow: hidden;">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="tab-content mb-0 b-0 pt-0">
                                            <div class="tab-pane" id="first">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div id="consumable_table">
                                                            <table class="table table-bordered table-responsive-md table-striped text-center mb-0 consumable-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="align-middle text-center" style="min-width: 50px;">SL.</th>
                                                                        <th class="align-middle text-center" style="min-width: 120px;">Add to List<br><div title="Add all" class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input add-all-to-list" id="add_all_to_list" onclick="add_all_to_list()"><label class="custom-control-label" for="add_all_to_list"></label></div></th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Required For</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Parts Name</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Quantity</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Where to Use</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Price</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Party Name</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Gate No.</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Challan No.</th>
                                                                        <th class="align-middle text-center" style="min-width: 250px;">Challan Photo</th>
                                                                        <th class="align-middle text-center" style="min-width: 250px;">Bill Photo</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Remarks</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Loan Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="consumable_table_body">
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="row mt-3 proceed-div">
                                                            <div class="col-md-12">
                                                                <div class="pull-right">
                                                                    <button type="button" class="btn btn-xs btn-success waves-effect waves-light" onclick="proceed_consumable()"><span class="btn-label"><i class="fas fa-arrow-right"></i></span>Proceed</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="second">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div id="spare_table" style="overflow-x: auto;">
                                                            <table class="table table-bordered table-responsive-md table-striped text-center mb-0 spare-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="align-middle text-center" style="min-width: 50px;">SL.</th>
                                                                        <th class="align-middle text-center" style="min-width: 120px;">Add to List<br><div title="Add all" class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input add-all-to-list-2" id="add_all_to_list_2" onclick="add_all_to_list_2()"><label class="custom-control-label" for="add_all_to_list_2"></label></div></th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Required For</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Parts Name</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Quantity</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Old Spares Details</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Status</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Price</th>
                                                                        <th class="align-middle text-center" style="min-width: 200px;">Party Name</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Gate No.</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Challan No.</th>
                                                                        <th class="align-middle text-center" style="min-width: 250px;">Challan Photo</th>
                                                                        <th class="align-middle text-center" style="min-width: 250px;">Bill Photo</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Remarks</th>
                                                                        <th class="align-middle text-center" style="min-width: 150px;">Loan Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="row mt-3 proceed-div-2">
                                                            <div class="col-md-12">
                                                                <div class="pull-right">
                                                                    <button type="button" class="btn btn-xs btn-success waves-effect waves-light" onclick="proceed_spare()"><span class="btn-label"><i class="fas fa-arrow-right"></i></span>Proceed</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- tab-content -->
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button title="Scroll to left" type="button" class="btn btn-xs btn-info waves-effect waves-light scroll-left"><i class="fas fa-long-arrow-alt-left"></i></button>
                                <button title="Scroll to right" type="button" class="btn btn-xs btn-info waves-effect waves-light mr-2 scroll-right"><i class="fas fa-long-arrow-alt-right"></i></button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For Create Loan -->

                <!-- Start Modals For LOAN VIEW -->
                <div class="modal fade full-width-modal-2" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="full-width-modalLabel">
                                    View / Update Loan

                                    <div class="form-group mt-2 view-by-party-div">
                                        <select data-placeholder="Choose Party" class="form-control select-b view-by-party">
                                            <option value="">Choose Party</option>
                                        </select>
                                    </div>
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>

                            <div class="modal-body" style="height: 430px; overflow: hidden;">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="loan_view_table">
                                                    <table class="table table-bordered table-responsive-md table-striped text-center mb-0">
                                                        <thead>
                                                            <th class="text-center" style="min-width: 50px;">Sl.</th>
                                                            <th class="text-center" style="min-width: 200px;">Quantity</th>
                                                            <th class="text-center" style="min-width: 200px;">Price</th>
                                                            <th class="text-center" style="min-width: 250px;">Party</th>
                                                            <th class="text-center" style="min-width: 150px;">Gate No.</th>
                                                            <th class="text-center" style="min-width: 150px;">Challan No.</th>
                                                            <th class="text-center" style="min-width: 250px;">Challan Photo</th>
                                                            <th class="text-center" style="min-width: 250px;">Bill Photo</th>
                                                            <th class="text-center" style="min-width: 200px;">Loan Date</th>
                                                            <th class="text-center" style="min-width: 150px;">Action</th>
                                                        </thead>
                                                        <tbody class="loan-records">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For LOAN VIEW -->
                
                <!-- LOAN LIST -->
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
                                        <li class="nav-item">
                                            <a href="#fifth" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-search mr-1"></i>
                                                <span class="d-none d-sm-inline">FILTERED</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sixth" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-undo mr-1"></i>
                                                <span class="d-none d-sm-inline">Loan Repay</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content mb-0 b-0 pt-0">
                                        <div class="tab-pane active" id="third" style="overflow-x: auto; overflow-y: auto;">
                                            <table class="table datatable w-100 nowrap cell-border text-center">
                                                <thead style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Requisitioned By</th>
                                                        <th>Accepted By</th>
                                                        <th>Requisition Date</th>
                                                        <th>Loan Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="third_body">
                                                </tbody>
                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Requisitioned By</th>
                                                        <th>Accepted By</th>
                                                        <th>Requisition Date</th>
                                                        <th>Loan Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="tab-pane" id="forth" style="overflow-x: auto; overflow-y: auto;">
                                            <table class="table datatable-2 w-100 nowrap cell-border text-center">
                                                <thead style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Requisitioned By</th>
                                                        <th>Accepted By</th>
                                                        <th>Requisition Date</th>
                                                        <th>Loan Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="forth_body">
                                                </tbody>
                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Requisitioned By</th>
                                                        <th>Accepted By</th>
                                                        <th>Requisition Date</th>
                                                        <th>Loan Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="tab-pane" id="fifth" style="overflow-x: auto; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="type">Status</label>
                                                        <select data-placeholder="Pending" class="form-control select-b type">
                                                            <option value="1">Borrowed</option>
                                                            <option value="2" selected>Pending</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">    
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="type">Party</label>
                                                        <select data-placeholder="Choose" class="form-control select-b party">
                                                            <option value="">Choose</option>
                                                            <?php 
                                                                $party_query = mysqli_query($conn, "SELECT * FROM rrmsteel_party");

                                                                if(mysqli_num_rows($party_query) > 0){
                                                                    while($row = mysqli_fetch_assoc($party_query)){
                                                            ?>
                                                                        <option value="<?= $row['party_id'] ?>"><?= $row['party_name'] ?></option>
                                                            <?php 
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2 parts-div">
                                                    <div class="form-group">
                                                        <label for="parts">Parts Name</label>
                                                        <select data-placeholder="Choose" class="form-control select-b parts">
                                                            <option value="">Choose</option>
                                                            <?php 
                                                                $parts_query = mysqli_query($conn, "SELECT parts_id, parts_name FROM rrmsteel_parts");

                                                                if(mysqli_num_rows($parts_query) > 0){
                                                                    while($row = mysqli_fetch_assoc($parts_query)){
                                                            ?>
                                                                        <option value="<?= $row['parts_id'] ?>"><?= $row['parts_name'] ?></option>
                                                            <?php 
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2 parts-nickname-div">
                                                    <div class="form-group">
                                                        <label for="parts">Parts Nick Name</label>
                                                        <select data-placeholder="Choose" class="form-control select-b parts-nickname">
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
                                                    <div class="form-group">
                                                        <label for="date">Date Range</label>
                                                        <input type="text" class="form-control date" data-toggle="date-picker" data-cancel-class="btn-warning">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="row">
                                                        <label for="." style="visibility: hidden;">.</label>
                                                    </div>
                                                    <div class="row">
                                                        <button type="button" class="btn btn-success waves-effect waves-light filter"><span class="btn-label"><i class="mdi mdi-filter-outline"></span></i>Filter</button>

                                                        <div class="button-list ml-1">
                                                            <button type="button" class="btn btn-purple waves-effect waves-light print-report-link d-block" data-toggle="modal" data-target=".full-width-modal-3"><span class="btn-label"><i class="mdi mdi-printer"></span></i>Print</button>

                                                            <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-f d-none" data-toggle="modal" data-target=".full-width-modal-3"><span class="btn-label"><i class="mdi mdi-printer"></span></i>Print</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="table datatable-3 w-100 nowrap cell-border text-center">
                                                <thead style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Required For</th>
                                                        <th>Parts Name</th>
                                                        <th style="min-width: 130px">Qty.</th>
                                                        <th style="min-width: 100px">Price</th>
                                                        <th style="min-width: 200px">Party Name</th>
                                                        <th>Gate No.</th>
                                                        <th>Challan No.</th>
                                                        <th>Requisition Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fifth_body">
                                                </tbody>
                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Reference</th>
                                                        <th>Required For</th>
                                                        <th>Parts Name</th>
                                                        <th>Qty.</th>
                                                        <th>Price</th>
                                                        <th>Party Name</th>
                                                        <th>Gate No.</th>
                                                        <th>Challan No.</th>
                                                        <th>Requisition Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <div class="row mt-3 mb-3 d-none upd-multiple-div">
                                                <div class="col-lg-12 text-center">
                                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="update_multiple()"><span class="btn-label"><i class="mdi mdi-pencil"></span></i>Update Multiple</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="sixth" style="overflow-x: auto; overflow-y: auto;">
                                            <table class="table datatable-4 w-100 nowrap cell-border text-center">
                                                <thead style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Parts Name</th>
                                                        <th>Parts Unit</th>
                                                        <th>Stock Quantity</th>
                                                        <th>Borrowed Quantity</th>
                                                        <th>Repaid Quantity</th>
                                                        <th>Average Rate</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sixth_body">
                                                </tbody>
                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                    <tr>
                                                        <th>SL.</th>
                                                        <th>Parts Name</th>
                                                        <th>Parts Unit</th>
                                                        <th>Stock Quantity</th>
                                                        <th>Borrowed Quantity</th>
                                                        <th>Repaid Quantity</th>
                                                        <th>Average Rate</th>
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

                <!-- Start Modals For LOAN PRINT -->
                <div class="modal fade full-width-modal-3" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header d-print-none">
                                <h4 class="modal-title" id="full-width-modalLabel">Print Loan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>

                            <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                <div class="card">
                                    <div class="card-body loan-print">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="float-left">
                                                    <h4 class="m-0">Loan List - <span class="loan-list-type"></span></h4>
                                                    <br>

                                                    <span class="float-right date-title"></span>
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
                                                    <table class="table table-centered table-bordered table-printed text-center">
                                                        <thead class="print-title">
                                                        </thead>
                                                        <tbody class="print-records">
                                                        </tbody>
                                                        <tfoot class="print-footer">
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-none page-break"></div>
                                    </div> <!-- end card-box -->

                                    <div class="row justify-content-center mb-3">
                                        <a onclick="print_loan()" href="#" class="btn btn-primary waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Loan Data</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For LOAN PRINT -->

                <!-- Start Modals For Parts Loan -->
                <div class="modal fade bs-example-modal-lg3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title col-md-10">
                                    Parts Loan Data: <span class="loan-repay-title"></span>

                                    <br>

                                    <span id="test" class="badge badge-secondary mt-1">
                                        Available Stock Qty.:

                                        <span class="badge badge-light stock-qty"></span>
                                    </span>
                                </h4>

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            
                            <div class="modal-body" style="height: 430px; overflow: hidden;">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="loan_repay_table">
                                                    <table class="table datatable-5 w-100 nowrap cell-border text-center">
                                                        <thead style="color: #fff; background-color: #5089de;">
                                                            <tr>
                                                                <th>SL.</th>
                                                                <th>Required For</th>
                                                                <th>Requisitioned Qty.</th>
                                                                <th>Borrowed Qty.</th>
                                                                <th>Repay Date</th>
                                                                <th>Repay Qty.</th>
                                                                <th>Price</th>
                                                                <th>Party Name</th>
                                                                <th>Borrow Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="loan_repay_body">
                                                        </tbody>
                                                        <tfoot style="color: #fff; background-color: #5089de;">
                                                            <tr>
                                                                <th>SL.</th>
                                                                <th>Required For</th>
                                                                <th>Requisitioned Qty.</th>
                                                                <th>Borrowed Qty.</th>
                                                                <th>Repay Date</th>
                                                                <th>Repay Qty.</th>
                                                                <th>Price</th>
                                                                <th>Party Name</th>
                                                                <th>Borrow Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col -->                  
                                </div> 
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For Parts Loan -->

                <!-- Start Modals For Loan Repay History -->
                <div class="modal fade bs-example-modal-lg4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-full modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title col-md-10">
                                    Loan Repay History: <span class="loan-repay-history-title"></span>
                                </h4>

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            
                            <div class="modal-body" style="height: 430px; overflow: hidden;">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="loan_repay_history_table">
                                                    <table class="table datatable-6 w-100 nowrap cell-border text-center">
                                                        <thead style="color: #fff; background-color: #5089de;">
                                                            <tr>
                                                                <th>SL.</th>
                                                                <th>Parts Name</th>
                                                                <th>Parts Unit</th>
                                                                <th>Repaid Qty.</th>
                                                                <th>Repay Date</th>
                                                                <th>Party Name</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="loan_repay_history_body">
                                                        </tbody>
                                                        <tfoot style="color: #fff; background-color: #5089de;">
                                                            <tr>
                                                                <th>SL.</th>
                                                                <th>Parts Name</th>
                                                                <th>Parts Unit</th>
                                                                <th>Repaid Qty.</th>
                                                                <th>Repay Date</th>
                                                                <th>Party Name</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col -->                  
                                </div> 
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Modals For Loan Repay History -->
            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->
        
        <!-- Footer Start -->
        <?php require_once('../../footer-for-sub-page.php'); ?>
        <!-- end Footer -->

        <!-- Custom js -->
        <script type="text/javascript">
            $(document).ready(function(){
                $('.full-width-modal').on('shown.bs.modal', function(){
                    $('#consumable_table, #spare_table, #loan_view_table, #loan_repay_table, #loan_repay_history_table').animate({
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

                // FETCH AND DISPLAY LOAN DATA
                let t;

                Swal.fire({
                    title: 'Fetching Loan Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/loan',
                        method: 'post',
                        data: {
                            loan_data_type: 'fetch_all',
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
                                            { data: 'requisitioned_by' },
                                            { data: 'accepted_by' },
                                            { data: 'requisition_created' },
                                            { data: 'loan_status' },
                                            { data: 'action' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable').DataTable();

                                    table.clear().draw();
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
                                            { data: 'requisitioned_by' },
                                            { data: 'accepted_by' },
                                            { data: 'requisition_created' },
                                            { data: 'loan_status' },
                                            { data: 'action' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable-2').DataTable();

                                    table.clear().draw();
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

                                if(result.Reply3){
                                    $('.datatable-3').DataTable({
                                        // stateSave: !0,
                                        language: {
                                            paginate: {
                                                previous: '<i class="mdi mdi-chevron-left">',
                                                next: '<i class="mdi mdi-chevron-right">'
                                            }
                                        },
                                        drawCallback: function(){
                                            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                                        },
                                        data: result.Reply3,
                                        columns: [
                                            { data: 'sl' },
                                            { data: 'reference' },
                                            { data: 'required_for' },
                                            { data: 'parts_name' },
                                            { data: 'parts_qty' },
                                            { data: 'empty_data' },
                                            { data: 'empty_data' },
                                            { data: 'empty_data' },
                                            { data: 'empty_data' },
                                            { data: 'date' },
                                            { data: 'empty_data' }
                                        ],
                                        columnDefs: [
                                            {
                                                targets: [ 5, 6, 7, 8, 10 ],
                                                visible: !1,
                                                searchable: !1
                                            }
                                        ],
                                        order: [[ 9, 'DESC' ]]
                                    });
                                } else{
                                    let table = $('.datatable-3').DataTable();

                                    table.clear().draw();
                                    table.destroy();
                                        
                                    $('.datatable-3').DataTable({
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

                                if(result.Reply4){
                                    $('.datatable-4').DataTable({
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
                                        data: result.Reply4,
                                        columns: [
                                            { data: 'sl' },
                                            { data: 'parts_name' },
                                            { data: 'parts_unit' },
                                            { data: 'parts_qty' },
                                            { data: 'borrowed_qty' },
                                            { data: 'repaid_qty' },
                                            { data: 'parts_avg_rate' },
                                            { data: 'action' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable-4').DataTable();

                                    table.clear().draw();
                                    table.destroy();
                                        
                                    $('.datatable-4').DataTable({
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
                            } else if(result.Type == 'error'){
                                let table = $('.datatable, .datatable-2, .datatable-3, .datatable-4').DataTable();

                                table.clear().draw();
                                table.destroy();
                                    
                                $('.datatable, .datatable-2, .datatable-3, .datatable-4').DataTable({
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

                $('.party').prop('disabled', true);

                $('.date').daterangepicker({
                    locale: {
                        separator: ' to ',
                        format: 'YYYY-MM-DD'
                    }
                }).on('keydown keyup', function(){
                    return false;
                });

                $('.view-by-party').change(function(){
                    let data_id = $(this).attr('data-id').split(','),
                        type = data_id[0],
                        requisition_id = data_id[1],
                        tr_indx = data_id[2];

                    if(type == 1){
                        $.ajax({
                            url: '../../api/loan',
                            method: 'post',
                            data: {
                                loan_data_type: 'fetch_loan_con',
                                requisition_id: requisition_id,
                                party_id: $(this).val()
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Fetching Loan Data',
                                        text: 'Please wait...',
                                        timer: 100,
                                        allowOutsideClick: false,
                                        onBeforeOpen: function(){
                                            Swal.showLoading(), t = setInterval(function(){
                                            }, 100);
                                        }
                                    }).then(function(){
                                        let trHTML = '',
                                            required_for = '',
                                            parts_unit = '';

                                        $.each(data.Reply1, function(i, loan_item_1){
                                            if(loan_item_1.required_for == 1)
                                                required_for = 'BCP-CCM';
                                            else if(loan_item_1.required_for == 2)
                                                required_for = 'BCP-Furnace';
                                            else if(loan_item_1.required_for == 3)
                                                required_for = 'Concast-CCM';
                                            else if(loan_item_1.required_for == 4)
                                                required_for = 'Concast-Furnace';
                                            else if(loan_item_1.required_for == 5)
                                                required_for = 'HRM';
                                            else if(loan_item_1.required_for == 6)
                                                required_for = 'HRM Unit-2';
                                            else if(loan_item_1.required_for == 7)
                                                required_for = 'Lal Masjid';
                                            else if(loan_item_1.required_for == 8)
                                                required_for = 'Sonargaon';
                                            else if(loan_item_1.required_for == 9)
                                                required_for = 'General';

                                            if(loan_item_1.parts_unit == 1)
                                                parts_unit = 'Bag';
                                            else if(loan_item_1.parts_unit == 2)
                                                parts_unit = 'Box';
                                            else if(loan_item_1.parts_unit == 3)
                                                parts_unit = 'Box/Pcs';
                                            else if(loan_item_1.parts_unit == 4)
                                                parts_unit = 'Bun';
                                            else if(loan_item_1.parts_unit == 5)
                                                parts_unit = 'Bundle';
                                            else if(loan_item_1.parts_unit == 6)
                                                parts_unit = 'Can';
                                            else if(loan_item_1.parts_unit == 7)
                                                parts_unit = 'Cartoon';
                                            else if(loan_item_1.parts_unit == 8)
                                                parts_unit = 'Challan';
                                            else if(loan_item_1.parts_unit == 9)
                                                parts_unit = 'Coil';
                                            else if(loan_item_1.parts_unit == 10)
                                                parts_unit = 'Drum';
                                            else if(loan_item_1.parts_unit == 11)
                                                parts_unit = 'Feet';
                                            else if(loan_item_1.parts_unit == 12)
                                                parts_unit = 'Gallon';
                                            else if(loan_item_1.parts_unit == 13)
                                                parts_unit = 'Item';
                                            else if(loan_item_1.parts_unit == 14)
                                                parts_unit = 'Job';
                                            else if(loan_item_1.parts_unit == 15)
                                                parts_unit = 'Kg';
                                            else if(loan_item_1.parts_unit == 16)
                                                parts_unit = 'Kg/Bundle';
                                            else if(loan_item_1.parts_unit == 17)
                                                parts_unit = 'Kv';
                                            else if(loan_item_1.parts_unit == 18)
                                                parts_unit = 'Lbs';
                                            else if(loan_item_1.parts_unit == 19)
                                                parts_unit = 'Ltr';
                                            else if(loan_item_1.parts_unit == 20)
                                                parts_unit = 'Mtr';
                                            else if(loan_item_1.parts_unit == 21)
                                                parts_unit = 'Pack';
                                            else if(loan_item_1.parts_unit == 22)
                                                parts_unit = 'Pack/Pcs';
                                            else if(loan_item_1.parts_unit == 23)
                                                parts_unit = 'Pair';
                                            else if(loan_item_1.parts_unit == 24)
                                                parts_unit = 'Pcs';
                                            else if(loan_item_1.parts_unit == 25)
                                                parts_unit = 'Pound';
                                            else if(loan_item_1.parts_unit == 26)
                                                parts_unit = 'Qty';
                                            else if(loan_item_1.parts_unit == 27)
                                                parts_unit = 'Roll';
                                            else if(loan_item_1.parts_unit == 28)
                                                parts_unit = 'Set';
                                            else if(loan_item_1.parts_unit == 29)
                                                parts_unit = 'Truck';
                                            else if(loan_item_1.parts_unit == 30)
                                                parts_unit = 'Unit';
                                            else if(loan_item_1.parts_unit == 31)
                                                parts_unit = 'Yeard';
                                            else if(loan_item_1.parts_unit == 32)
                                                parts_unit = '(Unit Unknown)';
                                            else if(loan_item_1.parts_unit == 33)
                                                parts_unit = 'SFT';
                                            else if(loan_item_1.parts_unit == 34)
                                                parts_unit = 'RFT';
                                            else if(loan_item_1.parts_unit == 35)
                                                parts_unit = 'CFT';

                                            trHTML += '<tr>';
                                                trHTML += '<td class="text-left alert-info" colspan="11">\
                                                                <i class="mdi mdi-drag-variant"></i> Required For: ' + required_for + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Parts Name: ' + loan_item_1.parts_name + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Old Spares Details:  ' + loan_item_1.parts_usage + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Remarks: ' + loan_item_1.remarks + '\
                                                                \
                                                                <span class="float-right">(' + loan_item_1.borrowed_parts_qty + '/' + loan_item_1.requisitioned_parts_qty + ' Borrowed)</span>\
                                                                <div class="progress mt-1 mr-1 progress-sm w-25 float-right" style="background: #fff;">\
                                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: ' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '%;" aria-valuenow="' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '" aria-valuemin="0" aria-valuemax="100"></div>\
                                                                </div>\
                                                                \
                                                            </td>';
                                            trHTML += '</tr>';

                                            $.each(data.Reply2, function(j, loan_item_2){
                                                if(loan_item_2.parts_id == loan_item_1.parts_id){
                                                    trHTML += '<tr>';
                                                        trHTML += '<td class="align-middle text-center">' + (j+1) + ' <span class="text-danger d-none err-span">*</span></td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.parts_qty + ' ' + parts_unit + '</span>';

                                                            trHTML += '<div class="input-group data-input d-none">';
                                                                trHTML += '<input type="number" class="form-control" placeholder="Insert" value="'+loan_item_2.parts_qty+'" oninput="qty_2(this, ' + loan_item_2.parts_qty + ', ' + loan_item_1.requisitioned_parts_qty + ', ' + loan_item_1.loan_indx_f + ', ' + loan_item_1.loan_indx_l + ')">';
                                                                trHTML += '<div class="input-group-prepend">';
                                                                    trHTML += '<div class="input-group-text">' + parts_unit + '</div>';
                                                                trHTML += '</div>';
                                                            trHTML += '</div>';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' + loan_item_2.price + '</span>';

                                                            trHTML += '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.price+'" oninput="price_2(this, ' + loan_item_2.price + ')">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.party_name + '</span>';

                                                            trHTML += '<div class="data-input d-none">';
                                                                trHTML += '<select class="select-b-party" onchange="party_name_2(this)">';
                                                                    trHTML += '<option selected value="'+loan_item_2.party_id+'|'+loan_item_2.party_remarks+'">'+loan_item_2.party_name+'</option>';
                                                                trHTML += '</select>';
                                                                trHTML += '<span class="float-left mt-1 text-primary remarks"></span>';
                                                            trHTML += '</div>';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.gate_no + '</span>';

                                                            trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.gate_no+'">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.challan_no + '</span>';

                                                            trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.challan_no+'">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">';
                                                                if(loan_item_2.challan_photo)
                                                                    trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.challan_photo + '" target="_blank">' + loan_item_2.challan_photo + '</a>';
                                                                else
                                                                    trHTML += '';
                                                            trHTML += '</span>';

                                                            trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                        trHTML += '</td>';
                                                        
                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">';
                                                                if(loan_item_2.bill_photo)
                                                                    trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.bill_photo + '" target="_blank">' + loan_item_2.bill_photo + '</a>';
                                                                else
                                                                    trHTML += '';
                                                            trHTML += '</span>';

                                                            trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.loan_date + '</span>';

                                                            trHTML += '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'+ loan_item_2.parts_id +'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'+loan_item_2.loan_date+'">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this)"><i class="mdi mdi-pencil"></i></a>';
                                                            trHTML += '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';
                                                            trHTML += '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con(' + loan_item_2.loan_data_id + ', this, ' + loan_item_1.loan_id + ', ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';
                                                            trHTML += '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con(' + loan_item_2.loan_data_id + ', this, ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + loan_item_1.loan_id + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-delete"></i></a>';
                                                        trHTML += '</td>';
                                                    trHTML += '</tr>';
                                                }
                                            });
                                        });

                                        $('.loan-records').empty().append(trHTML);

                                        $('.select-b-party').select2({
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
                                                    return '../../api/party';
                                                },
                                                dataType: 'json',
                                                method: 'POST',
                                                delay: 250,
                                                cache: false,
                                                data: function(param){
                                                    return{
                                                        party_data_type: 'fetch_all_by_srch_str',
                                                        search_str: param.term
                                                    };
                                                },
                                                processResults: function(data){
                                                    let result = data.Reply.map(function(item){
                                                        return{
                                                            id: item.party_id+'|'+item.party_remarks,
                                                            text: item.party_name
                                                        };
                                                    });

                                                    return{
                                                        results: result
                                                    };
                                                }
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
                    } else{
                        $.ajax({
                            url: '../../api/loan',
                            method: 'post',
                            data: {
                                loan_data_type: 'fetch_loan_spr',
                                requisition_id: requisition_id,
                                party_id: $(this).val()
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Fetching Loan Data',
                                        text: 'Please wait...',
                                        timer: 100,
                                        allowOutsideClick: false,
                                        onBeforeOpen: function(){
                                            Swal.showLoading(), t = setInterval(function(){
                                            }, 100);
                                        }
                                    }).then(function(){
                                        let trHTML = '',
                                            required_for = '',
                                            parts_unit = '';

                                        $.each(data.Reply1, function(i, loan_item_1){
                                            if(loan_item_1.required_for == 1)
                                                required_for = 'BCP-CCM';
                                            else if(loan_item_1.required_for == 2)
                                                required_for = 'BCP-Furnace';
                                            else if(loan_item_1.required_for == 3)
                                                required_for = 'Concast-CCM';
                                            else if(loan_item_1.required_for == 4)
                                                required_for = 'Concast-Furnace';
                                            else if(loan_item_1.required_for == 5)
                                                required_for = 'HRM';
                                            else if(loan_item_1.required_for == 6)
                                                required_for = 'HRM Unit-2';
                                            else if(loan_item_1.required_for == 7)
                                                required_for = 'Lal Masjid';
                                            else if(loan_item_1.required_for == 8)
                                                required_for = 'Sonargaon';
                                            else if(loan_item_1.required_for == 9)
                                                required_for = 'General';

                                            if(loan_item_1.parts_unit == 1)
                                                parts_unit = 'Bag';
                                            else if(loan_item_1.parts_unit == 2)
                                                parts_unit = 'Box';
                                            else if(loan_item_1.parts_unit == 3)
                                                parts_unit = 'Box/Pcs';
                                            else if(loan_item_1.parts_unit == 4)
                                                parts_unit = 'Bun';
                                            else if(loan_item_1.parts_unit == 5)
                                                parts_unit = 'Bundle';
                                            else if(loan_item_1.parts_unit == 6)
                                                parts_unit = 'Can';
                                            else if(loan_item_1.parts_unit == 7)
                                                parts_unit = 'Cartoon';
                                            else if(loan_item_1.parts_unit == 8)
                                                parts_unit = 'Challan';
                                            else if(loan_item_1.parts_unit == 9)
                                                parts_unit = 'Coil';
                                            else if(loan_item_1.parts_unit == 10)
                                                parts_unit = 'Drum';
                                            else if(loan_item_1.parts_unit == 11)
                                                parts_unit = 'Feet';
                                            else if(loan_item_1.parts_unit == 12)
                                                parts_unit = 'Gallon';
                                            else if(loan_item_1.parts_unit == 13)
                                                parts_unit = 'Item';
                                            else if(loan_item_1.parts_unit == 14)
                                                parts_unit = 'Job';
                                            else if(loan_item_1.parts_unit == 15)
                                                parts_unit = 'Kg';
                                            else if(loan_item_1.parts_unit == 16)
                                                parts_unit = 'Kg/Bundle';
                                            else if(loan_item_1.parts_unit == 17)
                                                parts_unit = 'Kv';
                                            else if(loan_item_1.parts_unit == 18)
                                                parts_unit = 'Lbs';
                                            else if(loan_item_1.parts_unit == 19)
                                                parts_unit = 'Ltr';
                                            else if(loan_item_1.parts_unit == 20)
                                                parts_unit = 'Mtr';
                                            else if(loan_item_1.parts_unit == 21)
                                                parts_unit = 'Pack';
                                            else if(loan_item_1.parts_unit == 22)
                                                parts_unit = 'Pack/Pcs';
                                            else if(loan_item_1.parts_unit == 23)
                                                parts_unit = 'Pair';
                                            else if(loan_item_1.parts_unit == 24)
                                                parts_unit = 'Pcs';
                                            else if(loan_item_1.parts_unit == 25)
                                                parts_unit = 'Pound';
                                            else if(loan_item_1.parts_unit == 26)
                                                parts_unit = 'Qty';
                                            else if(loan_item_1.parts_unit == 27)
                                                parts_unit = 'Roll';
                                            else if(loan_item_1.parts_unit == 28)
                                                parts_unit = 'Set';
                                            else if(loan_item_1.parts_unit == 29)
                                                parts_unit = 'Truck';
                                            else if(loan_item_1.parts_unit == 30)
                                                parts_unit = 'Unit';
                                            else if(loan_item_1.parts_unit == 31)
                                                parts_unit = 'Yeard';
                                            else if(loan_item_1.parts_unit == 32)
                                                parts_unit = '(Unit Unknown)';
                                            else if(loan_item_1.parts_unit == 33)
                                                parts_unit = 'SFT';
                                            else if(loan_item_1.parts_unit == 34)
                                                parts_unit = 'RFT';
                                            else if(loan_item_1.parts_unit == 35)
                                                parts_unit = 'CFT';

                                            trHTML += '<tr>';
                                                trHTML += '<td class="text-left alert-info" colspan="11">\
                                                                <i class="mdi mdi-drag-variant"></i> Required For: ' + required_for + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Parts Name: ' + loan_item_1.parts_name + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Old Spares Details:  ' + loan_item_1.old_spare_details + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Status:  ' + loan_item_1.status + '&emsp;&emsp;\
                                                                <i class="mdi mdi-drag-variant"></i> Remarks: ' + loan_item_1.remarks + '\
                                                                \
                                                                <span class="float-right">(' + loan_item_1.borrowed_parts_qty + '/' + loan_item_1.requisitioned_parts_qty + ' borrowed)</span>\
                                                                <div class="progress mt-1 mr-1 progress-sm w-25 float-right" style="background: #fff;">\
                                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: ' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '%;" aria-valuenow="' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '" aria-valuemin="0" aria-valuemax="100"></div>\
                                                                </div>\
                                                                \
                                                            </td>';
                                            trHTML += '</tr>';

                                            $.each(data.Reply2, function(j, loan_item_2){
                                                if(loan_item_2.parts_id == loan_item_1.parts_id){
                                                    trHTML += '<tr>';
                                                        trHTML += '<td class="align-middle text-center">' + (j+1) + ' <span class="text-danger d-none err-span">*</span></td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.parts_qty + ' ' + parts_unit + '</span>';

                                                            trHTML += '<div class="input-group data-input d-none">';
                                                                trHTML += '<input type="number" class="form-control" placeholder="Insert" value="'+loan_item_2.parts_qty+'" oninput="qty_2(this, ' + loan_item_2.parts_qty + ', ' + loan_item_1.requisitioned_parts_qty + ', ' + loan_item_1.loan_indx_f + ', ' + loan_item_1.loan_indx_l + ')">';
                                                                trHTML += '<div class="input-group-prepend">';
                                                                    trHTML += '<div class="input-group-text">' + parts_unit + '</div>';
                                                                trHTML += '</div>';
                                                            trHTML += '</div>';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' + loan_item_2.price + '</span>';

                                                            trHTML += '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.price+'" oninput="price_2(this, ' + loan_item_2.price + ')">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.party_name + '</span>';

                                                            trHTML += '<div class="data-input d-none">';
                                                                trHTML += '<select class="select-b-party" onchange="party_name_2(this)">';
                                                                    trHTML += '<option selected value="'+loan_item_2.party_id+'|'+loan_item_2.party_remarks+'">'+loan_item_2.party_name+'</option>';
                                                                trHTML += '</select>';
                                                                trHTML += '<span class="float-left mt-1 text-primary remarks"></span>';
                                                            trHTML += '</div>';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.gate_no + '</span>';

                                                            trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.gate_no+'">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.challan_no + '</span>';

                                                            trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.challan_no+'">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">';
                                                                if(loan_item_2.challan_photo)
                                                                    trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.challan_photo + '" target="_blank">' + loan_item_2.challan_photo + '</a>';
                                                                else
                                                                    trHTML += '';
                                                            trHTML += '</span>';

                                                            trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                        trHTML += '</td>';
                                                        
                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">';
                                                                if(loan_item_2.bill_photo)
                                                                    trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.bill_photo + '" target="_blank">' + loan_item_2.bill_photo + '</a>';
                                                                else
                                                                    trHTML += '';
                                                            trHTML += '</span>';

                                                            trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<span class="data-span">' + loan_item_2.loan_date + '</span>';

                                                            trHTML += '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'+ loan_item_2.parts_id +'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'+loan_item_2.loan_date+'">';
                                                        trHTML += '</td>';

                                                        trHTML += '<td class="align-middle text-center">';
                                                            trHTML += '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this)"><i class="mdi mdi-pencil"></i></a>';
                                                            trHTML += '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';
                                                            trHTML += '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr(' + loan_item_2.loan_data_id + ', this, ' + loan_item_1.loan_id + ', ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';
                                                            trHTML += '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_spr(' + loan_item_2.loan_data_id + ', this, ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + loan_item_1.loan_id + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-delete"></i></a>';
                                                        trHTML += '</td>';
                                                    trHTML += '</tr>';
                                                }
                                            });
                                        });

                                        $('.loan-records').empty().append(trHTML);

                                        $('.select-b-party').select2({
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
                                                    return '../../api/party';
                                                },
                                                dataType: 'json',
                                                method: 'POST',
                                                delay: 250,
                                                cache: false,
                                                data: function(param){
                                                    return{
                                                        party_data_type: 'fetch_all_by_srch_str',
                                                        search_str: param.term
                                                    };
                                                },
                                                processResults: function(data){
                                                    let result = data.Reply.map(function(item){
                                                        return{
                                                            id: item.party_id+'|'+item.party_remarks,
                                                            text: item.party_name
                                                        };
                                                    });

                                                    return{
                                                        results: result
                                                    };
                                                }
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
                });

                $('.type').on('change', function(){
                    if($(this).val() == 1)
                        $('.party').prop('disabled', false);
                    else
                        $('.party').prop('disabled', true);

                    $('.parts, .parts-nickname-div').show();

                    $('.party, .parts, .parts-nickname').val('').trigger('change');

                    let t;

                    Swal.fire({
                        title: 'Fetching Loan Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        fetch_loan_data($('.type').val());
                    });
                });

                $('.parts').on('change', function(){
                    if($(this).val()){
                        $('.parts-nickname-div').hide();
                    }
                });

                $('.parts-nickname').on('change', function(){
                    if($(this).val()){
                        $('.parts-div').hide();
                    }
                });

                $('.print-report-link').removeClass('d-none').addClass('d-block');
                $('.print-report-link-f').removeClass('d-block').addClass('d-none');

                $('.filter').click(function(){
                    $('.print-report-link').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-f').removeClass('d-none').addClass('d-block');
                    
                    let type = $('.type').val(),
                        party_id = $('.party').val(),
                        parts_id = $('.parts').val(),
                        parts_nickname = $('.parts-nickname').val(),
                        date_range = $('.date').val();
                    
                    let t;

                    Swal.fire({
                        title: 'Fetching Filtered Loan Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        fetch_loan_data(type, party_id, parts_id, parts_nickname, date_range);
                    });
                });

                // PRINT LOAN
                $('.print-report-link').on('click', function(){
                    $('.loan-list-type').html($('.type').select2('data')[0].text);
                    $('.date-title').html('Loan Data For <strong>All Parts & Parties</strong>.');

                    let type = $('.type').val();

                    if(!type){
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
                        let t;

                        Swal.fire({
                            title: 'Loading Loan Data',
                            text: 'Please wait...',
                            timer: 100,
                            allowOutsideClick: false,
                            onBeforeOpen: function(){
                                Swal.showLoading(), t = setInterval(function(){
                                }, 100);
                            }
                        }).then(function(){
                            if(type == 1){
                                col_title = 'Borrow Date';
                            } else{
                               col_title = 'Requisition Date'; 
                            }

                            // CREATE TABLE
                            let table_header = '';

                            if(type == 1){
                                table_header = '<th class="table-head-align">SL.</th>\
                                                <th class="table-head-align">Reference</th>\
                                                <th class="table-head-align">Required For</th>\
                                                <th class="table-head-align">Parts Name</th>\
                                                <th class="table-head-align">Qty.</th>\
                                                <th class="table-head-align">Price</th>\
                                                <th class="table-head-align">Party Name</th>\
                                                <th class="table-head-align">Gate No.</th>\
                                                <th class="table-head-align">Challan No.</th>\
                                                <th class="table-head-align">' + col_title + '</th>';
                            } else{
                                table_header = '<th class="table-head-align">SL.</th>\
                                                <th class="table-head-align">Reference</th>\
                                                <th class="table-head-align">Required For</th>\
                                                <th class="table-head-align">Parts Name</th>\
                                                <th class="table-head-align">Qty.</th>\
                                                <th class="table-head-align">' + col_title + '</th>';
                            }

                            $('.table').css('width', '100%');

                            $.ajax({
                                url: '../../api/loan',
                                method: 'post',
                                data: {
                                    loan_data_type: 'fetch_filtered_loan',
                                    type: type,
                                    party_id: null,
                                    parts_id: null,
                                    parts_nickname : null,
                                },
                                dataType: 'json',
                                cache: false,
                                async: false,
                                success: function(data){
                                    if(data.Type == 'success'){
                                        let table_header = '',
                                            table_data = '',
                                            table_footer = '',
                                            loan_info = _.sortBy(data.Reply, 'date').reverse();

                                        if(type == 1){
                                            let grand_tot_price = 0.00;

                                            $.each(loan_info, function(i, data){
                                                table_data += '<tr>';
                                                    table_data += '<td class="align-middle">' + (i+1) + '</td>';
                                                    table_data += '<td class="align-middle">' + data.reference + '</td>';
                                                    table_data += '<td class="align-middle">' + data.required_for + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_name + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_qty + '</td>';
                                                    table_data += '<td class="align-middle">' + data.price + '</td>';
                                                    table_data += '<td class="align-middle">' + data.party_name + '</td>';
                                                    table_data += '<td class="align-middle">' + data.gate_no + '</td>';
                                                    table_data += '<td class="align-middle">' + data.challan_no + '</td>';
                                                    table_data += '<td class="align-middle">' + data.date + '</td>';
                                                table_data += '</tr>';

                                                grand_tot_price += parseFloat(data.price);
                                            });

                                            table_footer = '<th class="table-head-align" colspan="5"></th>\
                                                            <th class="table-head-align"><i class="mdi mdi-currency-bdt"></i>' + grand_tot_price.toFixed(2) + '</th>\
                                                            <th class="table-head-align" colspan="4"></th>';
                                        } else{
                                            $.each(loan_info, function(i, data){
                                                table_data += '<tr>';
                                                    table_data += '<td class="align-middle">' + (i+1) + '</td>';
                                                    table_data += '<td class="align-middle">' + data.reference + '</td>';
                                                    table_data += '<td class="align-middle">' + data.required_for + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_name + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_qty + '</td>';
                                                    table_data += '<td class="align-middle">' + data.date + '</td>';
                                                table_data += '</tr>';
                                            });
                                        }
                                        
                                        $('.table-printed .print-title').empty().append('<tr>' + table_header + '</tr>');
                                        $('.table-printed .print-records').empty().append(table_data);
                                        if(type == 1){
                                            $('.table-printed .print-footer').empty().append(table_footer);
                                        }
                                    } else if(data.Type == 'error'){
                                        $('.table-printed .print-records').empty().append('<tr>' + table_header + '</tr>');
                        
                                        if(type == 1){
                                            $('.table-printed .print-records').empty().append('<tr><td class="text-center" colspan="10">No data available in table</td></tr>');
                                        } else{
                                            $('.table-printed .print-records').empty().append('<tr><td class="text-center" colspan="6">No data available in table</td></tr>');
                                        }
                                    }

                                    return false;
                                }
                            });
                        });
                    }
                });

                // PRINT FILTERED LOAN
                $('.print-report-link-f').on('click', function(){
                    $('.loan-list-type').html($('.type').select2('data')[0].text);
                    $('.date-title').html('Loan Data of <strong>(' + $('.date').val() + ')</strong>.');

                    let type = $('.type').val(),
                        party_id = $('.party').val(),
                        parts_id = $('.parts').val(),
                        parts_nickname = $('.parts-nickname').val(),
                        date_range = $('.date').val();

                    if(!type){
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
                        let t;

                        Swal.fire({
                            title: 'Loading Filtered Loan Data',
                            text: 'Please wait...',
                            timer: 100,
                            allowOutsideClick: false,
                            onBeforeOpen: function(){
                                Swal.showLoading(), t = setInterval(function(){
                                }, 100);
                            }
                        }).then(function(){
                            if(type == 1){
                                col_title = 'Borrow Date';
                            } else{
                               col_title = 'Requisition Date'; 
                            }

                            // CREATE TABLE
                            let table_header = '';

                            if(type == 1){
                                table_header = '<th class="table-head-align">SL.</th>\
                                                <th class="table-head-align">Reference</th>\
                                                <th class="table-head-align">Required For</th>\
                                                <th class="table-head-align">Parts Name</th>\
                                                <th class="table-head-align">Qty.</th>\
                                                <th class="table-head-align">Price</th>\
                                                <th class="table-head-align">Party Name</th>\
                                                <th class="table-head-align">Gate No.</th>\
                                                <th class="table-head-align">Challan No.</th>\
                                                <th class="table-head-align">' + col_title + '</th>';
                            } else{
                                table_header = '<th class="table-head-align">SL.</th>\
                                                <th class="table-head-align">Reference</th>\
                                                <th class="table-head-align">Required For</th>\
                                                <th class="table-head-align">Parts Name</th>\
                                                <th class="table-head-align">Qty.</th>\
                                                <th class="table-head-align">' + col_title + '</th>';
                            }

                            $('.table').css('width', '100%');

                            $.ajax({
                                url: '../../api/loan',
                                method: 'post',
                                data: {
                                    loan_data_type: 'fetch_filtered_loan',
                                    type: type,
                                    party_id: party_id,
                                    parts_id: parts_id,
                                    parts_nickname: parts_nickname,
                                    date_range: date_range
                                },
                                dataType: 'json',
                                cache: false,
                                async: false,
                                success: function(data){
                                    if(data.Type == 'success'){
                                        let table_data = '',
                                            table_footer = '',
                                            loan_info = _.sortBy(data.Reply, 'date').reverse();

                                        if(type == 1){
                                            let grand_tot_price = 0.00;

                                            $.each(loan_info, function(i, data){
                                                table_data += '<tr>';
                                                    table_data += '<td class="align-middle">' + (i+1) + '</td>';
                                                    table_data += '<td class="align-middle">' + data.reference + '</td>';
                                                    table_data += '<td class="align-middle">' + data.required_for + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_name + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_qty + '</td>';
                                                    table_data += '<td class="align-middle">' + data.price + '</td>';
                                                    table_data += '<td class="align-middle">' + data.party_name + '</td>';
                                                    table_data += '<td class="align-middle">' + data.gate_no + '</td>';
                                                    table_data += '<td class="align-middle">' + data.challan_no + '</td>';
                                                    table_data += '<td class="align-middle">' + data.date + '</td>';
                                                        table_data += '</td>';
                                                table_data += '</tr>';

                                                grand_tot_price += parseFloat(data.price);
                                            });

                                            table_footer = '<th class="table-head-align" colspan="5"></th>\
                                                            <th class="table-head-align"><i class="mdi mdi-currency-bdt"></i>' + grand_tot_price.toFixed(2) + '</th>\
                                                            <th class="table-head-align" colspan="4"></th>';
                                        } else{
                                            $.each(loan_info, function(i, data){
                                                table_data += '<tr>';
                                                    table_data += '<td class="align-middle">' + (i+1) + '</td>';
                                                    table_data += '<td class="align-middle">' + data.reference + '</td>';
                                                    table_data += '<td class="align-middle">' + data.required_for + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_name + '</td>';
                                                    table_data += '<td class="align-middle">' + data.parts_qty + '</td>';
                                                    table_data += '<td class="align-middle">' + data.date + '</td>';
                                                table_data += '</tr>';
                                            });
                                        }
                                        
                                        $('.table-printed .print-title').empty().append('<tr>' + table_header + '</tr>');
                                        $('.table-printed .print-records').empty().append(table_data);
                                        if(type == 1){
                                            $('.table-printed .print-footer').empty().append(table_footer);
                                        }
                                    } else if(data.Type == 'error'){
                                        $('.table-printed .print-records').empty().append('<tr>' + table_header + '</tr>');
                        
                                        if(type == 1){
                                            $('.table-printed .print-records').empty().append('<tr><td class="text-center" colspan="10">No data available in table</td></tr>');
                                        } else{
                                            $('.table-printed .print-records').empty().append('<tr><td class="text-center" colspan="6">No data available in table</td></tr>');
                                        }
                                    }

                                    return false;
                                }
                            });
                        });
                    }
                });
            });

            // FETCH LOAN DATA
            function fetch_loan_data(type, party_id = null, parts_id = null, parts_nickname = null, date_range = null){
                if(type == 1){
                    col_title = 'Borrow Date';
                } else{
                   col_title = 'Requisition Date'; 
                }

                $.ajax({
                    url: '../../api/loan',
                    method: 'post',
                    data: {
                        loan_data_type: 'fetch_filtered_loan',
                        type: type,
                        party_id: party_id,
                        parts_id: parts_id,
                        parts_nickname: parts_nickname,
                        date_range: date_range
                    },
                    dataType: 'json',
                    cache: false,
                    async: false,
                    success: function(result){
                        if(result.Type == 'success'){
                            let table = $('.datatable-3').DataTable();

                            table.destroy();

                            $('.datatable-3').DataTable({
                                // stateSave: !0,
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
                                    { data: 'required_for' },
                                    { data: 'parts_name' },
                                    { data: 'parts_qty' },
                                    { data: (type == 1) ? 'price' : 'empty_data' },
                                    { data: (type == 1) ? 'party_name' : 'empty_data' },
                                    { data: (type == 1) ? 'gate_no' : 'empty_data' },
                                    { data: (type == 1) ? 'challan_no' : 'empty_data' },
                                    {
                                        title: col_title,
                                        data: 'date'
                                    },
                                    { data: (type == 1) ? 'action' : 'empty_data' },
                                ],
                                columnDefs: [
                                    {
                                        targets: (type == 1) ? [] : [ 5, 6, 7, 8, 10 ],
                                        visible: !1,
                                        searchable: !1
                                    }
                                ],
                                order: [[ 9, 'DESC' ]],
                                footerCallback: function(row, data, start, end, display){
                                    let api = this.api();

                                    if(type == 1){
                                        // Remove the formatting to get integer data for summation
                                        let int_val = function(i){
                                            return typeof i === 'string' ? i.match(/\d+/)*1 : typeof i === 'number' ? i : 0;
                                        };

                                        let total = api.column(5).data().reduce(function(a, b){
                                            return int_val(a) + int_val(b);
                                        }, 0);

                                        // Update footer
                                        $(api.column(5).footer()).html('Total Price: <i class="mdi mdi-currency-bdt"></i>' + total.toFixed(2));
                                    }
                                }
                            });
                        } else if(result.Type == 'error'){
                            let table = $('.datatable-3').DataTable();

                            table.clear().draw();
                            table.destroy();
                                
                            $('.datatable-3').DataTable({
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
            }

            // PRINT LOAN
            function print_loan(){
                let restore_page = $('body').html(),
                    print_content = $('.loan-print').clone();

                $('body').empty().html(print_content);
                window.print();
                $('body').html(restore_page);
            }

            // GET PARTS QTY
            function qty(ele, ele2){
                if(ele == 1){
                    $('#consumable_table tbody tr').each(function(){
                        if($(this).find('td:eq(4)').find('.qty').val() <= 0)
                            $(this).find('td:eq(4)').find('.qty').val('');
                        // else if($(this).find('td:eq(4)').find('.qty').val() > ele2)
                            // $(this).find('td:eq(4)').find('.qty').val(ele2);
                    });
                } else{
                    $('#spare_table tbody tr').each(function(){
                        if($(this).find('td:eq(4)').find('.qty-2').val() <= 0)
                            $(this).find('td:eq(4)').find('.qty-2').val('');
                        // else if($(this).find('td:eq(4)').find('.qty-2').val() > ele2)
                            // $(this).find('td:eq(4)').find('.qty-2').val(ele2);
                    });
                }
            }

            // GET PARTS QTY 2
            function qty_2(ele, ele2, ele3, ele4, ele5){
                /*let tot_borrowed = 0;

                $('.loan-records tr').each(function(){
                    if($(this).index() >= ele4 && $(this).index() <= ele5)
                        tot_borrowed += parseInt($(this).find('td:eq(1)').find('input').val()) ? parseInt($(this).find('td:eq(1)').find('input').val()) : 0;
                });

                if(parseInt($(ele).val()) <= 0 || tot_borrowed > ele3)*/

                if(parseInt($(ele).val()) <= 0)
                    $(ele).closest('tr').find('td:eq(1)').find('input').val(ele2);
            }

            // GET PARTS QTY 3
            function qty_3(ele, ele2){
                if(parseInt($(ele).val()) <= 0)
                    $(ele).closest('tr').find('td:eq(4)').find('input').val(ele2);
            }

            // GET PARTS QTY 4
            function qty_4(ele, ele2){
                let repay_date = $(ele).closest('tr').find('td:eq(4)').find('input').val(),
                    repay_qty = $(ele).val();

                if(!repay_qty){
                    $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                } else if(parseFloat(repay_qty) <= 0){
                    $(ele).closest('tr').find('td:eq(5)').find('input').val('');
                    $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                } else if(parseFloat($(ele).val()) > parseFloat(ele2)){
                    $(ele).closest('tr').find('td:eq(5)').find('input').val(ele2);
                } else{
                    if(repay_date){
                        $(ele).closest('tr').find('td:eq(9)').find('a').removeClass('disabled');
                    } else{
                        $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                    }
                }
            }

            // GET PRICE
            function price(ele){
                if(ele == 1){
                    $('#consumable_table tbody tr').each(function(){
                        if($(this).find('td:eq(6)').find('.price').val() < 0)
                            $(this).find('td:eq(6)').find('.price').val('');
                    });
                } else{
                    $('#spare_table tbody tr').each(function(){
                        if($(this).find('td:eq(7)').find('.price-2').val() < 0)
                            $(this).find('td:eq(7)').find('.price-2').val('');
                    });
                }
            }

            function price_2(ele, ele2){
                if(parseInt($(ele).val()) < 0)
                    $(ele).closest('tr').find('td:eq(2)').find('input').val(ele2.toFixed(2));
            }

            // GET PARTY NAME
            function party_name(ele, ele2, ele3){
                let party_arr = ele.split('|'),
                    remarks = party_arr[1];

                if(ele2 == 1){
                    if(remarks)
                        $('#consumable_table tbody').find('tr:nth-child(' + ele3 + ')').find('td:eq(7)').find('.remarks').html('Remarks: ' + remarks);
                    else
                        $('#consumable_table tbody').find('tr:nth-child(' + ele3 + ')').find('td:eq(7)').find('.remarks').html('');
                } else{
                    if(remarks)
                        $('#spare_table tbody').find('tr:nth-child(' + ele3 + ')').find('td:eq(8)').find('.remarks-2').html('Remarks: ' + remarks);
                    else
                        $('#spare_table tbody').find('tr:nth-child(' + ele3 + ')').find('td:eq(8)').find('.remarks-2').html('');
                }
            }

            function party_name_2(ele){
                let party_arr = $(ele).val().split('|'),
                    remarks = party_arr[1];
            }

            // VALIDATE LOAN DATE FIELD
            function loan_date(ele, ele2){
                if(ele == 1)
                    $('#consumable_table tbody').find('tr:nth-child(' + ele2 + ')').find('td:eq(13)').find('input').val('');
                else
                    $('#spare_table tbody').find('tr:nth-child(' + ele2 + ')').find('td:eq(14)').find('input').val('');
            }

            function loan_date_2(ele){
                $(ele).closest('tr').find('td:eq(8)').find('input').val('');
            }

            // VALIDATE LOAN REPAY DATE FIELD
            function loan_repay_date(ele){
                $(ele).closest('tr').find('td:eq(4)').find('input').val('');
            }

            // ADD ALL TO CON LIST
            function add_all_to_list(){
                $('.add-to-list').each(function(){
                    if($(this).attr('disabled') == undefined){
                        if($('.add-all-to-list').is(':checked'))
                            $(this).prop('checked', true);
                        else
                            $(this).prop('checked', false);
                    }
                });
            }

            // ADD ALL TO SPARE LIST
            function add_all_to_list_2(){
                $('.add-to-list-2').each(function(){
                    if($(this).attr('disabled') == undefined){
                        if($('.add-all-to-list-2').is(':checked'))
                            $(this).prop('checked', true);
                        else
                            $(this).prop('checked', false);
                    }
                });
            }

            // ADD TO CON LIST
            function add_to_list(){
                let flag = 1;

                $('.add-to-list').each(function(i){
                    if(!$(this).is(':checked')){
                        flag = 0;

                        return false;
                    }
                });

                if(flag == 1)
                    $('.add-all-to-list').prop('checked', true);
                else
                    $('.add-all-to-list').prop('checked', false);
            }

            // ADD TO SPARE LIST
            function add_to_list_2(){
                let flag = 1;

                $('.add-to-list-2').each(function(i){
                    if(!$(this).is(':checked')){
                        flag = 0;

                        return false;
                    }
                });

                if(flag == 1)
                    $('.add-all-to-list-2').prop('checked', true);
                else
                    $('.add-all-to-list-2').prop('checked', false);
            }

            let edit_data_arr = [];

            function edit_btn(ele, ele2 = null, ele3 = null, ele4 = null, ele5 = null, ele6 = null, ele7 = null){
                // CREATE PARTY OPTION
                $('.select-b-party').select2({
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
                            return '../../api/party';
                        },
                        dataType: 'json',
                        method: 'POST',
                        delay: 250,
                        cache: false,
                        data: function(param){
                            return{
                                party_data_type: 'fetch_all_by_srch_str',
                                search_str: param.term
                            };
                        },
                        processResults: function(data){
                            let result = data.Reply.map(function(item){
                                return{
                                    id: item.party_id,
                                    text: item.party_name
                                };
                            });

                            return{
                                results: result
                            };
                        }
                    }
                });

                if(ele2){
                    $(ele).closest('tr').find('td:eq(6)').find('.select-b').val(ele2).trigger('change');
                }

                if(ele3){
                    $('.upd-multiple-div').removeClass('d-none');

                    edit_data_arr.push({
                        tr_indx: $(ele).closest('tr').index(),
                        loan_data_id: ele3,
                        loan_id: ele4,
                        parts_id: ele5,
                        req_for: ele6,
                        type: ele7
                    });
                }

                $(ele).closest('tr').find('.data-span').addClass('d-none');
                $(ele).closest('tr').find('.data-input').removeClass('d-none');
                
                $(ele).closest('tr').find('.edt-btn').addClass('d-none');
                $(ele).closest('tr').find('.cncl-btn').removeClass('d-none');
                $(ele).closest('tr').find('.upd-btn').removeClass('d-none');
            }

            function cancel_btn(ele){
                $(ele).closest('tr').find('.data-span').removeClass('d-none');
                $(ele).closest('tr').find('.data-input').addClass('d-none');
                
                $(ele).closest('tr').find('.edt-btn').removeClass('d-none');
                $(ele).closest('tr').find('.cncl-btn').addClass('d-none');
                $(ele).closest('tr').find('.upd-btn').addClass('d-none');
            }

            // UPDATE MULTIPLE
            function update_multiple(){
                 Swal.fire({
                    title: 'Are you sure you want to update?',
                    text: 'Multiple loan data will be updated!',
                    type: 'warning',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonColor: '#5cb85c',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fas fa-check"></i>&nbsp;&nbsp; Yes',
                    cancelButtonText: '<i class="fas fa-times"></i>&nbsp;&nbsp; No'
                }).then(function(result){
                    if(result.value){
                        let table = $('.datatable-3').DataTable(),
                            counter = 0;

                        table.rows().every(function(indx, element){
                            let item = edit_data_arr.find(item => item.tr_indx === indx);

                            if(item != undefined){
                                let row = $(this.node());

                                edit_data_arr[counter].qty = row.find('td:eq(4)').find('input').val();
                                edit_data_arr[counter].price = row.find('td:eq(5)').find('input').val();
                                edit_data_arr[counter].party = row.find('td:eq(6)').find('select').val();
                                edit_data_arr[counter].gate_no = row.find('td:eq(7)').find('input').val();
                                edit_data_arr[counter].challan_no = row.find('td:eq(8)').find('input').val();
                                edit_data_arr[counter].loan_date = row.find('td:eq(9)').find('input').val();

                                counter++;
                            }
                        });

                        if(edit_data_arr.length > 0){
                            let t;

                            Swal.fire({
                                title: 'Fetching Loan Data',
                                text: 'Please wait...',
                                timer: 100,
                                allowOutsideClick: false,
                                onBeforeOpen: function(){
                                    Swal.showLoading(), t = setInterval(function(){
                                    }, 100);
                                }
                            }).then(function(){
                                $.ajax({
                                    url: '../../api/interactionController',
                                    method: 'post',
                                    data: {
                                        interact_type: 'update',
                                        interact: 'loan_data_multi',
                                        edit_data_arr: edit_data_arr
                                    },
                                    dataType: 'json',
                                    cache: false,
                                    success: function(data){
                                        if(data.Type == 'success'){
                                            let t;

                                            Swal.fire({
                                                title: 'Updating Loan Data',
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

                                                        edit_data_arr.length = 0;

                                                        $('.upd-multiple-div').addClass('d-none');

                                                        fetch_loan_data($('.type').val());
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
                                                footer: 'Something went wrong.'
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
                        }
                    }
                });
            }

            var requisition_data = '';

            // CONSUMABLE LOAN
            function loan_con(ele){
                $('#first').addClass('active');
                $('#second').removeClass('fade active show');

                let t;

                Swal.fire({
                    title: 'Fetching Loan Data',
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
                        url: '../../api/loan',
                        method: 'post',
                        data: {
                            loan_data_type: 'fetch_requisition_con',
                            requisition_id: requisition_id
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                requisition_data = data;
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

                    /*let parts_id_arr = [],
                        parts_name_arr = [];

                    $.each(requisition_data.Reply, function(i, requisitioned_parts_id){
                        parts_id_arr.push(requisitioned_parts_id.parts_id);
                    });

                    $.ajax({
                        url: '../../api/parts',
                        method: 'post',
                        data: {
                            parts_data_type: 'fetch_all'
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                $.each(data.Reply, function(i, parts_item){
                                    if(parts_item.parts_category <= 2 && jQuery.inArray(parts_item.parts_id, parts_id_arr) !== -1){
                                        for(let j=0; j<parts_id_arr.length; j++){
                                            if(parts_id_arr[j] == parts_item.parts_id)
                                                parts_name_arr.push(parts_item.parts_name);
                                        }
                                    }
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
                    });*/

                    let trHTML = '';

                    $.each(requisition_data.Reply, function(i, requisition_item){
                        trHTML += '<tr '+ ((requisition_item.parts_id == requisition_item.borrowed_parts_id && parseFloat(requisition_item.borrowed_parts_qty) >= parseFloat(requisition_item.r_qty)) ? 'style=" background: #cfe8cf;"' : '') +'>';
                            trHTML += '<td class="align-middle">' + (i+1) + ' <span class="text-danger d-none">*</span></td>';

                            let checked = '',
                                disabled = '';

                            if(requisition_item.parts_id == requisition_item.borrowed_parts_id && parseFloat(requisition_item.borrowed_parts_qty) >= parseFloat(requisition_item.r_qty)){
                                checked = 'checked';
                                disabled = 'disabled';
                            }

                            trHTML += '<td class="align-middle"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input add-to-list" id="add_to_list'+i+'" onclick="add_to_list()" '+ checked + ' ' + disabled +'><label class="custom-control-label" for="add_to_list'+i+'"></label></div></td>';

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

                            trHTML += '<td class="text-center align-middle">' + required_for + '</td>';
                            trHTML += '<td class="text-center align-middle">' + requisition_item.parts_name + '</td>';

                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<div class="input-group">';
                                    trHTML += '<input type="number" class="form-control qty" placeholder="Insert" oninput="qty(1, '+ (requisition_item.r_qty - requisition_item.borrowed_parts_qty) +')" '+ disabled +'>';
                                    trHTML += '<div class="input-group-prepend">';
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

                                        trHTML += '<div class="input-group-text"><span id="unit">' + parts_unit + '</span></div>';
                                    trHTML += '</div>';
                                trHTML += '</div>';

                                trHTML += '<span class="float-left mt-1 text-primary w-100 in-stock">In Stock: <strong>' + requisition_item.i_qty + '</strong> ' + parts_unit + '</span><span class="float-left text-info w-100 borrowed">Borrowed: <strong>' + requisition_item.borrowed_parts_qty + '</strong> of <strong>' + requisition_item.r_qty + '</strong> ' + parts_unit + '</span>';
                            trHTML += '</td>';

                            trHTML += '<td class="text-center align-middle">' + requisition_item.parts_usage + '</td>';
                            
                            trHTML += '<td class="align-middle"><input type="number" class="form-control price" placeholder="Insert" oninput="price(1)" '+ disabled +'></td>';
                                
                            trHTML += '<td class="align-middle">';
                                trHTML += '<select class="select-b-party party-name" onchange="party_name(this.value, 1, ' + (i+1) + ')" '+ disabled +'></select>';
                                trHTML += '<span class="float-left mt-1 text-primary remarks"></span>';
                            trHTML += '</td>';

                            trHTML += '<td class="align-middle"><input type="text" class="form-control gate-no" placeholder="Insert" '+ disabled +'></td>';
                            trHTML += '<td class="align-middle"><input type="text" class="form-control challan-no" placeholder="Insert" '+ disabled +'></td>';
                            trHTML += '<td class="align-middle"><input type="file" class="form-control challan-photo" accept="image/*" '+ disabled +'></td>';
                            trHTML += '<td class="align-middle"><input type="file" class="form-control bill-photo" accept="image/*" '+ disabled +'></td>';

                            trHTML += '<td class="text-center align-middle">' + requisition_item.remarks + '</td>';

                            trHTML += '<td class="align-middle"><input type="text" class="form-control loan-date" style="width: 150px;" oninput="loan_date(1, '+ (i+1) +')" data-id="'+ requisition_item.parts_id +'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" '+ disabled +'></td>';
                        trHTML += '</tr>';

                        $('#consumable_table').find('table tbody').empty();
                        $('#consumable_table').find('table').append(trHTML);

                        $('.select-b-party').select2({
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
                                    return '../../api/party';
                                },
                                dataType: 'json',
                                method: 'POST',
                                delay: 250,
                                cache: false,
                                data: function(param){
                                    return{
                                        party_data_type: 'fetch_all_by_srch_str',
                                        search_str: param.term
                                    };
                                },
                                processResults: function(data){
                                    let result = data.Reply.map(function(item){
                                        return{
                                            id: item.party_id+'|'+item.party_remarks,
                                            text: item.party_name
                                        };
                                    });

                                    return{
                                        results: result
                                    };
                                }
                            }
                        });
                    });
                });
            }

            // PROCEED CONSUMABLE
            function proceed_consumable(){
                $('#consumable_table tr').each(function(){
                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled'),
                        qty = $(this).find('td:eq(4)').find('input').val(),
                        price = $(this).find('td:eq(6)').find('input').val(),
                        party = $(this).find('td:eq(7)').find('select').val(),
                        gate_no = $(this).find('td:eq(8)').find('input').val(),
                        challan_no = $(this).find('td:eq(9)').find('input').val(),
                        loan_date = $(this).find('td:eq(13)').find('input').val();

                    if(listed_disabled != 'disabled'){
                        if(listed == 1 && (qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === ''))
                            $(this).find('td:eq(0)').find('span').removeClass('d-none');
                        else
                           $(this).find('td:eq(0)').find('span').addClass('d-none');
                    }
                });

                let flag = 2;

                // GET SELECTED ROW
                $('#consumable_table tbody tr').each(function(){
                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled');

                    if(listed_disabled != 'disabled'){
                        if(listed == 1){
                            flag = 2;

                            return false;
                        } else{
                            flag = 0;
                        }
                    }
                });

                // GET SELECTED ROW DATA
                $('#consumable_table tbody tr').each(function(){
                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled'),
                        qty = $(this).find('td:eq(4)').find('input').val(),
                        price = $(this).find('td:eq(6)').find('input').val(),
                        party = $(this).find('td:eq(7)').find('select').val(),
                        gate_no = $(this).find('td:eq(8)').find('input').val(),
                        challan_no = $(this).find('td:eq(9)').find('input').val(),
                        loan_date = $(this).find('td:eq(13)').find('input').val();

                    if(listed_disabled != 'disabled'){
                        if(listed == 1 && (qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === '')){
                            flag = 1;

                            return false;
                        }
                    }
                });

                if(flag == 0){
                    Swal.fire({
                        title: 'Error',
                        text: 'Please select a row!',
                        type: 'error',
                        width: 450,
                        showCloseButton: true,
                        confirmButtonColor: '#5cb85c',
                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                        footer: 'Please fill all data in the table row.'
                    });
                } else if(flag == 1){
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
                } else{
                    let table_data = '';

                    let table_object = $('#consumable_table tbody tr').map(function(i){
                        let row = '',
                            listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                            listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled');

                        if(listed_disabled != 'disabled'){
                            if(listed == 1){
                                let challan_photo = $(this).find('td:eq(10)').find('input').prop('files')[0],
                                    bill_photo = $(this).find('td:eq(11)').find('input').prop('files')[0],
                                    challan_file_data = '',
                                    bill_file_data = '';

                                if(challan_photo != undefined)
                                    challan_file_data = challan_photo.name;

                                if(bill_photo != undefined)
                                    bill_file_data = bill_photo.name;

                                row = {
                                    'listed': listed,
                                    'required_for': $(this).find('td:eq(2)').html(),
                                    'parts': $(this).find('td:eq(3)').html(),
                                    'qty': $(this).find('td:eq(4)').find('input').val(),
                                    'loan': $(this).find('td:eq(4)').find('.borrowed').find('strong:eq(0)').html(),
                                    'req': $(this).find('td:eq(4)').find('.borrowed').find('strong:eq(1)').html(),
                                    'use': $(this).find('td:eq(5)').html(),
                                    'price': $(this).find('td:eq(6)').find('input').val(),
                                    'party': $(this).find('td:eq(7)').find('select').val(),
                                    'gate_no': $(this).find('td:eq(8)').find('input').val(),
                                    'challan_no': $(this).find('td:eq(9)').find('input').val(),
                                    'challan_photo': challan_file_data,
                                    'bill_photo': bill_file_data,
                                    'remarks': $(this).find('td:eq(12)').html(),
                                    'loan_date': $(this).find('td:eq(13)').find('input').val()
                                };
                            } else{
                                return;
                            }
                        } else{
                            return;
                        }
                        
                        if(listed_disabled != 'disabled'){
                            if(listed == 1){
                                table_data += '<tr>';
                                    table_data += '<td>' + (i+1) + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(2)').html() + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(3)').html() + '</td>';
                                    table_data += '<td>' + parseFloat(row.qty).toFixed(3) + ' ' + $(this).find('td:eq(4)').find('span').html() + '</td>';
                                    table_data += '<td>' + parseFloat($(this).find('td:eq(4)').find('.in-stock').html().replace(/[^0-9.-]+/g, '')).toFixed(3) + ' ' + $(this).find('td:eq(4)').find('span').html() + '</td>';
                                    table_data += '<td>' + row.use + '</td>';
                                    table_data += '<td><i class="mdi mdi-currency-bdt"></i>' + parseFloat(row.price).toFixed(2) + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(7)').find('.party-name option:selected').html() + '</td>';
                                    table_data += '<td>' + row.gate_no + '</td>';
                                    table_data += '<td>' + row.challan_no + '</td>';
                                    table_data += '<td>' + row.remarks + '</td>';
                                    table_data += '<td>' + row.loan_date + '</td>';
                                table_data += '</tr>';
                            }
                        }

                        return row;
                    }).get();

                    let table = '<table class="table table-responsive table-striped table-bordered consumable-table">';
                        table += '<thead>';
                            table += '<tr>';
                                table += '<th>SL.</td>';
                                table += '<th>Required For</td>';
                                table += '<th>Parts</td>';
                                table += '<th>Quantity</td>';
                                table += '<th>In Stock</td>';
                                table += '<th>Where to Use</td>';
                                table += '<th>Price</td>';
                                table += '<th>Party</td>';
                                table += '<th>Gate No.</td>';
                                table += '<th>Challan No.</td>';
                                table += '<th>Remarks</td>';
                                table += '<th>Loan Date</td>';
                            table += '</tr>';
                        table += '</thead>';
                        table += '<tbody id="consumable_data">';
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
                            title: 'Loan Data',
                            html: table,
                            type: 'info',
                            width: 'auto',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Submit'
                        }).then(function(result){
                            if(result.value){
                                let loan_table_data = JSON.stringify(table_object);
                                
                                let requisition_id = 0;
                                if(requisition_data)
                                    requisition_id = requisition_data.Reply[0].requisition_id;

                                let flag = 1,
                                    msg = '';

                                // GET SELECTED ROWS IMAGE DATA
                                $('#consumable_table tbody tr').each(function(){
                                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled');

                                    if(listed_disabled != 'disabled'){
                                        if(listed == 1){
                                            let challan_photo = $(this).find('td:eq(10)').find('input').prop('files')[0],
                                                bill_photo = $(this).find('td:eq(11)').find('input').prop('files')[0];
                                                
                                            if(challan_photo != undefined){
                                                let form_data = new FormData();
                                                form_data.append('file', challan_photo);

                                                $.ajax({
                                                    url: '../../api/uploadImage',
                                                    method: 'post',
                                                    data: form_data,
                                                    dataType: 'json',
                                                    contentType : false,
                                                    processData: false,
                                                    cache: false,
                                                    async: false,
                                                    success: function(data){
                                                        if(data.Type === 'error'){
                                                            flag = 0;
                                                            msg = data.Reply;
                                                        }
                                                    }
                                                });

                                                if(flag == 0)
                                                    return false;
                                            }

                                            if(bill_photo != undefined){
                                                let form_data = new FormData();
                                                form_data.append('file', bill_photo);

                                                $.ajax({
                                                    url: '../../api/uploadImage',
                                                    method: 'post',
                                                    data: form_data,
                                                    dataType: 'json',
                                                    contentType : false,
                                                    processData: false,
                                                    cache: false,
                                                    async: false,
                                                    success: function(data){
                                                        if(data.Type === 'error'){
                                                            flag = 0;
                                                            msg = data.Reply;
                                                        }
                                                    }
                                                });

                                                if(flag == 0)
                                                    return false;
                                            }
                                        }
                                    }
                                });

                                if(flag == 0){
                                    Swal.fire({
                                        title: 'Error',
                                        text: msg,
                                        type: 'error',
                                        width: 450,
                                        showCloseButton: true,
                                        confirmButtonColor: '#5cb85c',
                                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                        footer: 'Please insert valid image.'
                                    });
                                } else{
                                    let borrowed_parts = 0;

                                    $.each(table_object, function(i, obj_data){
                                        if((parseFloat(obj_data.qty) + parseFloat(obj_data.loan)) >= parseFloat(obj_data.req))
                                            borrowed_parts++;
                                    });

                                    $.ajax({
                                        url: '../../api/interactionController',
                                        method: 'post',
                                        data: {
                                            interact_type: 'add',
                                            interact: 'loan_con',
                                            requisition_id: requisition_id,
                                            requisitioned_parts: $('#consumable_table tbody tr').length,
                                            borrowed_parts: borrowed_parts,
                                            loan_data: loan_table_data
                                        },
                                        dataType: 'json',
                                        cache: false,
                                        success: function(data){
                                            if(data.Type == 'success'){
                                                let t;

                                                Swal.fire({
                                                    title: 'Adding Loan Data',
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

                                                            $('#third_body').load(' #third_body > *');
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
                            }
                        });
                    });
                }
            }

            var requisition_data2 = '';

            // SPARE LOAN
            function loan_spr(ele){
                $('#first').removeClass('active');
                $('#second').addClass('fade active show');

                let t;

                Swal.fire({
                    title: 'Fetching Loan Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    loan_date = '';

                    let requisition_id = ele;

                    $.ajax({
                        url: '../../api/loan',
                        method: 'post',
                        data: {
                            loan_data_type: 'fetch_requisition_spr',
                            requisition_id: requisition_id
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                requisition_data2 = data;
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

                    /*let parts_id_arr = [],
                        parts_name_arr = [];

                    $.each(requisition_data2.Reply, function(i, requisitioned_parts_id){
                        parts_id_arr.push(requisitioned_parts_id.parts_id);
                    });

                    $.ajax({
                        url: '../../api/parts',
                        method: 'post',
                        data: {
                            parts_data_type: 'fetch_all'
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                $.each(data.Reply, function(i, parts_item){
                                    if(parts_item.parts_category <= 1 && jQuery.inArray(parts_item.parts_id, parts_id_arr) !== -1){
                                        for(let j=0; j<parts_id_arr.length; j++){
                                            if(parts_id_arr[j] == parts_item.parts_id)
                                                parts_name_arr.push(parts_item.parts_name);
                                        }
                                    }
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
                    });*/

                    let trHTML = '';

                    $.each(requisition_data2.Reply, function(i, requisition_item){
                        trHTML += '<tr '+ ((requisition_item.parts_id == requisition_item.borrowed_parts_id && parseFloat(requisition_item.borrowed_parts_qty) >= parseFloat(requisition_item.r_qty)) ? 'style=" background: #cfe8cf;"' : '') +'>';
                            trHTML += '<td class="align-middle">' + (i+1) + ' <span class="text-danger d-none">*</span></td>';

                            let checked = '',
                                disabled = '';

                            if(requisition_item.parts_id == requisition_item.borrowed_parts_id && parseFloat(requisition_item.borrowed_parts_qty) >= parseFloat(requisition_item.r_qty)){
                                checked = 'checked';
                                disabled = 'disabled';
                            }
                            
                            trHTML += '<td class="align-middle"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input add-to-list-2" id="add_to_list_2'+i+'" onclick="add_to_list_2()" '+ checked + ' ' + disabled +'><label class="custom-control-label" for="add_to_list_2'+i+'"></label></div></td>';

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

                            trHTML += '<td class="text-center align-middle">' + required_for + '</td>';
                            trHTML += '<td class="text-center align-middle">' + requisition_item.parts_name + '</td>';

                            trHTML += '<td class="align-middle text-center">';
                                trHTML += '<div class="input-group">';
                                    trHTML += '<input type="number" class="form-control qty-2" placeholder="Insert" oninput="qty(2, '+ (requisition_item.r_qty - requisition_item.borrowed_parts_qty) +')" '+ disabled +'>';
                                    trHTML += '<div class="input-group-prepend">';
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

                                        trHTML += '<div class="input-group-text"><span id="unit_2">' + parts_unit + '</span></div>';
                                    trHTML += '</div>';
                                trHTML += '</div>';

                                trHTML += '<span class="float-left mt-1 text-primary w-100 in-stock-2">In Stock: <strong>' + requisition_item.i_qty + '</strong> ' + parts_unit + '</span><span class="float-left text-info w-100 borrowed-2">Borrowed: <strong>' + requisition_item.borrowed_parts_qty + '</strong> of <strong>' + requisition_item.r_qty + '</strong> ' + parts_unit + '</span>';
                            trHTML += '</td>';

                            trHTML += '<td class="text-center align-middle">' + requisition_item.old_spare_details + '</td>';
                            trHTML += '<td class="text-center align-middle">' + ((requisition_item.status == 1) ? 'Repairable' : 'Unusual') + '</td>';
                            trHTML += '<td class="align-middle"><input type="number" class="form-control price-2" placeholder="Insert" oninput="price(2)" '+ disabled +'></td>';
                                
                            trHTML += '<td class="align-middle">';
                                trHTML += '<select class="select-b-party party-name-2" onchange="party_name(this.value, 2, ' + (i+1) + ')" '+ disabled +'></select>';
                                trHTML += '<span class="float-left mt-1 text-primary remarks-2"></span>';
                            trHTML += '</td>';

                            trHTML += '<td class="align-middle"><input type="text" class="form-control gate-no-2" placeholder="Insert" '+ disabled +'></td>';
                            trHTML += '<td class="align-middle"><input type="text" class="form-control challan-no-2" placeholder="Insert" '+ disabled +'></td>';
                            trHTML += '<td class="align-middle"><input type="file" class="form-control challan-photo-2" accept="image/*" '+ disabled +'></td>';
                            trHTML += '<td class="align-middle"><input type="file" class="form-control bill-photo-2" accept="image/*" '+ disabled +'></td>';
                            trHTML += '<td class="text-center align-middle">' + requisition_item.remarks + '</td>';
                            trHTML += '<td class="align-middle"><input type="text" class="form-control loan-date-2" style="width: 150px;" oninput="loan_date(2, '+ (i+1) +')" data-id="'+ requisition_item.parts_id +'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" '+ disabled +'></td>';
                        trHTML += '</tr>';
                            
                        $('#spare_table').find('table tbody').empty();
                        $('#spare_table').find('table').append(trHTML);

                        $('.select-b-party').select2({
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
                                    return '../../api/party';
                                },
                                dataType: 'json',
                                method: 'POST',
                                delay: 250,
                                cache: false,
                                data: function(param){
                                    return{
                                        party_data_type: 'fetch_all_by_srch_str',
                                        search_str: param.term
                                    };
                                },
                                processResults: function(data){
                                    let result = data.Reply.map(function(item){
                                        return{
                                            id: item.party_id+'|'+item.party_remarks,
                                            text: item.party_name
                                        };
                                    });

                                    return{
                                        results: result
                                    };
                                }
                            }
                        });
                    });
                });
            }

            // PROCEED SPARE
            function proceed_spare(){
                $('#spare_table tr').each(function(){
                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled'),
                        qty = $(this).find('td:eq(4)').find('input').val(),
                        price = $(this).find('td:eq(7)').find('input').val(),
                        party = $(this).find('td:eq(8)').find('select').val(),
                        gate_no = $(this).find('td:eq(9)').find('input').val(),
                        challan_no = $(this).find('td:eq(10)').find('input').val(),
                        loan_date = $(this).find('td:eq(14)').find('input').val();

                    if(listed_disabled != 'disabled'){
                        if(listed == 1 && (qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === ''))
                            $(this).find('td:eq(0)').find('span').removeClass('d-none');
                        else
                           $(this).find('td:eq(0)').find('span').addClass('d-none');
                    }
                });

                let flag = 2;

                // GET SELECTED ROW
                $('#spare_table tbody tr').each(function(){
                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled');

                    if(listed_disabled != 'disabled'){
                        if(listed == 1){
                            flag = 2;

                            return false;
                        } else{
                            flag = 0;
                        }
                    }
                });

                // GET SELECTED ROW DATA
                $('#spare_table tbody tr').each(function(){
                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled'),
                        qty = $(this).find('td:eq(4)').find('input').val(),
                        price = $(this).find('td:eq(7)').find('input').val(),
                        party = $(this).find('td:eq(8)').find('select').val(),
                        gate_no = $(this).find('td:eq(9)').find('input').val(),
                        challan_no = $(this).find('td:eq(10)').find('input').val(),
                        loan_date = $(this).find('td:eq(14)').find('input').val();

                    if(listed_disabled != 'disabled'){
                        if(listed == 1 && (qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === '')){
                            flag = 1;

                            return false;
                        }
                    }
                });

                if(flag == 0){
                    Swal.fire({
                        title: 'Error',
                        text: 'Please select a row!',
                        type: 'error',
                        width: 450,
                        showCloseButton: true,
                        confirmButtonColor: '#5cb85c',
                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                        footer: 'Please fill all data in the table row.'
                    });
                } else if(flag == 1){
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
                } else{
                    let table_data = '';

                    let table_object = $('#spare_table tbody tr').map(function(i){
                        let row = '',
                            listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                            listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled');

                        if(listed_disabled != 'disabled'){
                            if(listed == 1){
                                let challan_photo = $(this).find('td:eq(11)').find('input').prop('files')[0],
                                    bill_photo = $(this).find('td:eq(12)').find('input').prop('files')[0],
                                    challan_file_data = '',
                                    bill_file_data = '';

                                if(challan_photo != undefined)
                                    challan_file_data = challan_photo.name;

                                if(bill_photo != undefined)
                                    bill_file_data = bill_photo.name;

                                row = {
                                    'listed': listed,
                                    'required_for': $(this).find('td:eq(2)').html(),
                                    'parts': $(this).find('td:eq(3)').html(),
                                    'qty': $(this).find('td:eq(4)').find('input').val(),
                                    'loan': $(this).find('td:eq(4)').find('.borrowed-2').find('strong:eq(0)').html(),
                                    'req': $(this).find('td:eq(4)').find('.borrowed-2').find('strong:eq(1)').html(),
                                    'old': $(this).find('td:eq(5)').html(),
                                    'status': $(this).find('td:eq(6)').html(),
                                    'price': $(this).find('td:eq(7)').find('input').val(),
                                    'party': $(this).find('td:eq(8)').find('select').val(),
                                    'gate_no': $(this).find('td:eq(9)').find('input').val(),
                                    'challan_no': $(this).find('td:eq(10)').find('input').val(),
                                    'challan_photo': challan_file_data,
                                    'bill_photo': bill_file_data,
                                    'remarks': $(this).find('td:eq(13)').html(),
                                    'loan_date': $(this).find('td:eq(14)').find('input').val()
                                };
                            } else{
                                return;
                            }
                        } else{
                            return;
                        }
                        
                        if(listed_disabled != 'disabled'){
                            if(listed == 1){
                                table_data += '<tr>';
                                    table_data += '<td>' + (i+1) + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(2)').html() + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(3)').html() + '</td>';
                                    table_data += '<td>' + parseFloat(row.qty).toFixed(3) + ' ' + $(this).find('td:eq(4)').find('span').html() + '</td>';
                                    table_data += '<td>' + parseFloat($(this).find('td:eq(4)').find('.in-stock-2').html().replace(/[^0-9.-]+/g, '')).toFixed(3) + ' ' + $(this).find('td:eq(4)').find('span').html() + '</td>';
                                    table_data += '<td>' + row.old + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(6)').html() + '</td>';
                                    table_data += '<td><i class="mdi mdi-currency-bdt"></i>' + parseFloat(row.price).toFixed(2) + '</td>';
                                    table_data += '<td>' + $(this).find('td:eq(8)').find('.party-name-2 option:selected').html() + '</td>';
                                    table_data += '<td>' + row.gate_no + '</td>';
                                    table_data += '<td>' + row.challan_no + '</td>';
                                    table_data += '<td>' + row.remarks + '</td>';
                                    table_data += '<td>' + row.loan_date + '</td>';
                                table_data += '</tr>';
                            }
                        }

                        return row;
                    }).get();

                    let table = '<table class="table table-responsive table-striped table-bordered spare-table">';
                        table += '<thead>';
                            table += '<tr>';
                                table += '<th>SL.</td>';
                                table += '<th>Required For</td>';
                                table += '<th>Parts</td>';
                                table += '<th>Quantity</td>';
                                table += '<th>In Stock</td>';
                                table += '<th>Old Spares Details</td>';
                                table += '<th>Status</td>';
                                table += '<th>Price</td>';
                                table += '<th>Party</td>';
                                table += '<th>Gate No.</td>';
                                table += '<th>Challan No.</td>';
                                table += '<th>Remarks</td>';
                                table += '<th>Loan Date</td>';
                            table += '</tr>';
                        table += '</thead>';
                        table += '<tbody id="spare_data">';
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
                            title: 'Loan Data',
                            html: table,
                            type: 'info',
                            width: 'auto',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Submit'
                        }).then(function(result){
                            if(result.value){
                                let loan_table_data = JSON.stringify(table_object);
                                
                                let requisition_id = 0;
                                if(requisition_data2)
                                    requisition_id = requisition_data2.Reply[0].requisition_id;

                                let flag = 1,
                                    msg = '';

                                // GET SELECTED ROWS IMAGE DATA
                                $('#spare_table tbody tr').each(function(){
                                    let listed = $(this).find('td:eq(1)').find('input').is(':checked') ? 1 : 0,
                                        listed_disabled = $(this).find('td:eq(1)').find('input').attr('disabled');

                                    if(listed_disabled != 'disabled'){
                                        if(listed == 1){
                                            let challan_photo = $(this).find('td:eq(11)').find('input').prop('files')[0],
                                                bill_photo = $(this).find('td:eq(12)').find('input').prop('files')[0];
                                                
                                            if(challan_photo != undefined){
                                                let form_data = new FormData();
                                                form_data.append('file', challan_photo);

                                                $.ajax({
                                                    url: '../../api/uploadImage',
                                                    method: 'post',
                                                    data: form_data,
                                                    dataType: 'json',
                                                    contentType : false,
                                                    processData: false,
                                                    cache: false,
                                                    async: false,
                                                    success: function(data){
                                                        if(data.Type === 'error'){
                                                            flag = 0;
                                                            msg = data.Reply;
                                                        }
                                                    }
                                                });

                                                if(flag == 0)
                                                    return false;
                                            }

                                            if(bill_photo != undefined){
                                                let form_data = new FormData();
                                                form_data.append('file', bill_photo);

                                                $.ajax({
                                                    url: '../../api/uploadImage',
                                                    method: 'post',
                                                    data: form_data,
                                                    dataType: 'json',
                                                    contentType : false,
                                                    processData: false,
                                                    cache: false,
                                                    async: false,
                                                    success: function(data){
                                                        if(data.Type === 'error'){
                                                            flag = 0;
                                                            msg = data.Reply;
                                                        }
                                                    }
                                                });

                                                if(flag == 0)
                                                    return false;
                                            }
                                        }
                                    }
                                });

                                if(flag == 0){
                                    Swal.fire({
                                        title: 'Error',
                                        text: msg,
                                        type: 'error',
                                        width: 450,
                                        showCloseButton: true,
                                        confirmButtonColor: '#5cb85c',
                                        confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                        footer: 'Please insert valid image.'
                                    });
                                } else{
                                    let borrowed_parts = 0;

                                    $.each(table_object, function(i, obj_data){
                                        if((parseFloat(obj_data.qty) + parseFloat(obj_data.loan)) >= parseFloat(obj_data.req))
                                            borrowed_parts++;
                                    });

                                    $.ajax({
                                        url: '../../api/interactionController',
                                        method: 'post',
                                        data: {
                                            interact_type: 'add',
                                            interact: 'loan_spr',
                                            requisition_id: requisition_id,
                                            requisitioned_parts: $('#spare_table tbody tr').length,
                                            borrowed_parts: borrowed_parts,
                                            loan_data: loan_table_data
                                        },
                                        dataType: 'json',
                                        cache: false,
                                        success: function(data){
                                            if(data.Type == 'success'){
                                                let t;

                                                Swal.fire({
                                                    title: 'Adding Loan Data',
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

                                                            $('#forth_body').load(' #forth_body > *');
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
                            }
                        });
                    });
                }
            }

            // VIEW CONSUMABLE LOAN
            function view_loan_con(ele, ele2 = null, ele3 = null, ele4 = null){
                let requisition_id = ele;

                $.ajax({
                    url: '../../api/loan',
                    method: 'post',
                    data: {
                        loan_data_type: 'fetch_loan_con',
                        requisition_id: requisition_id,
                        party_id: (ele4) ? ele4 : null
                    },
                    dataType: 'json',
                    cache: false,
                    async: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Fetching Loan Data',
                                text: 'Please wait...',
                                timer: 100,
                                allowOutsideClick: false,
                                onBeforeOpen: function(){
                                    Swal.showLoading(), t = setInterval(function(){
                                    }, 100);
                                }
                            }).then(function(){
                                let party_name_option = '';
                                let party_id_arr = [];

                                $.each(data.Reply2, function(i, party_item){
                                    if(party_id_arr.includes(party_item.party_id) === false){
                                        party_id_arr.push(party_item.party_id);

                                        let selected = (party_item.party_id == ele4) ? 'selected' : '';

                                        party_name_option += '<option value="'+party_item.party_id+'" ' + selected + '>'+party_item.party_name+'</option>';
                                    }
                                });

                                let tr_indx = ((ele2) ? $(ele2).closest('tr').index() : ele3);

                                $('.view-by-party').attr('data-id', [1, requisition_id, tr_indx]).empty().append('<option value="">Choose</option><option value="0" selected>All Parties</option>' + party_name_option);

                                let trHTML = '',
                                    required_for = '',
                                    parts_unit = '';

                                $.each(data.Reply1, function(i, loan_item_1){
                                    if(loan_item_1.required_for == 1)
                                        required_for = 'BCP-CCM';
                                    else if(loan_item_1.required_for == 2)
                                        required_for = 'BCP-Furnace';
                                    else if(loan_item_1.required_for == 3)
                                        required_for = 'Concast-CCM';
                                    else if(loan_item_1.required_for == 4)
                                        required_for = 'Concast-Furnace';
                                    else if(loan_item_1.required_for == 5)
                                        required_for = 'HRM';
                                    else if(loan_item_1.required_for == 6)
                                        required_for = 'HRM Unit-2';
                                    else if(loan_item_1.required_for == 7)
                                        required_for = 'Lal Masjid';
                                    else if(loan_item_1.required_for == 8)
                                        required_for = 'Sonargaon';
                                    else if(loan_item_1.required_for == 9)
                                        required_for = 'General';

                                    if(loan_item_1.parts_unit == 1)
                                        parts_unit = 'Bag';
                                    else if(loan_item_1.parts_unit == 2)
                                        parts_unit = 'Box';
                                    else if(loan_item_1.parts_unit == 3)
                                        parts_unit = 'Box/Pcs';
                                    else if(loan_item_1.parts_unit == 4)
                                        parts_unit = 'Bun';
                                    else if(loan_item_1.parts_unit == 5)
                                        parts_unit = 'Bundle';
                                    else if(loan_item_1.parts_unit == 6)
                                        parts_unit = 'Can';
                                    else if(loan_item_1.parts_unit == 7)
                                        parts_unit = 'Cartoon';
                                    else if(loan_item_1.parts_unit == 8)
                                        parts_unit = 'Challan';
                                    else if(loan_item_1.parts_unit == 9)
                                        parts_unit = 'Coil';
                                    else if(loan_item_1.parts_unit == 10)
                                        parts_unit = 'Drum';
                                    else if(loan_item_1.parts_unit == 11)
                                        parts_unit = 'Feet';
                                    else if(loan_item_1.parts_unit == 12)
                                        parts_unit = 'Gallon';
                                    else if(loan_item_1.parts_unit == 13)
                                        parts_unit = 'Item';
                                    else if(loan_item_1.parts_unit == 14)
                                        parts_unit = 'Job';
                                    else if(loan_item_1.parts_unit == 15)
                                        parts_unit = 'Kg';
                                    else if(loan_item_1.parts_unit == 16)
                                        parts_unit = 'Kg/Bundle';
                                    else if(loan_item_1.parts_unit == 17)
                                        parts_unit = 'Kv';
                                    else if(loan_item_1.parts_unit == 18)
                                        parts_unit = 'Lbs';
                                    else if(loan_item_1.parts_unit == 19)
                                        parts_unit = 'Ltr';
                                    else if(loan_item_1.parts_unit == 20)
                                        parts_unit = 'Mtr';
                                    else if(loan_item_1.parts_unit == 21)
                                        parts_unit = 'Pack';
                                    else if(loan_item_1.parts_unit == 22)
                                        parts_unit = 'Pack/Pcs';
                                    else if(loan_item_1.parts_unit == 23)
                                        parts_unit = 'Pair';
                                    else if(loan_item_1.parts_unit == 24)
                                        parts_unit = 'Pcs';
                                    else if(loan_item_1.parts_unit == 25)
                                        parts_unit = 'Pound';
                                    else if(loan_item_1.parts_unit == 26)
                                        parts_unit = 'Qty';
                                    else if(loan_item_1.parts_unit == 27)
                                        parts_unit = 'Roll';
                                    else if(loan_item_1.parts_unit == 28)
                                        parts_unit = 'Set';
                                    else if(loan_item_1.parts_unit == 29)
                                        parts_unit = 'Truck';
                                    else if(loan_item_1.parts_unit == 30)
                                        parts_unit = 'Unit';
                                    else if(loan_item_1.parts_unit == 31)
                                        parts_unit = 'Yeard';
                                    else if(loan_item_1.parts_unit == 32)
                                        parts_unit = '(Unit Unknown)';
                                    else if(loan_item_1.parts_unit == 33)
                                        parts_unit = 'SFT';
                                    else if(loan_item_1.parts_unit == 34)
                                        parts_unit = 'RFT';
                                    else if(loan_item_1.parts_unit == 35)
                                        parts_unit = 'CFT';

                                    trHTML += '<tr>';
                                        trHTML += '<td class="text-left alert-info" colspan="10">\
                                                        <i class="mdi mdi-drag-variant"></i> Required For: ' + required_for + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Parts Name: ' + loan_item_1.parts_name + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Old Spares Details:  ' + loan_item_1.parts_usage + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Remarks: ' + loan_item_1.remarks + '\
                                                        \
                                                        <span class="float-right">(' + loan_item_1.borrowed_parts_qty + '/' + loan_item_1.requisitioned_parts_qty + ' Borrowed)</span>\
                                                        <div class="progress mt-1 mr-1 progress-sm w-25 float-right" style="background: #fff;">\
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: ' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '%;" aria-valuenow="' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '" aria-valuemin="0" aria-valuemax="100"></div>\
                                                        </div>\
                                                        \
                                                    </td>';
                                    trHTML += '</tr>';

                                    $.each(data.Reply2, function(j, loan_item_2){
                                        if(loan_item_2.parts_id == loan_item_1.parts_id){
                                            trHTML += '<tr>';
                                                trHTML += '<td class="align-middle text-center">' + (j+1) + '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.parts_qty + ' ' + parts_unit + '</span>';

                                                    trHTML += '<div class="input-group data-input d-none">';
                                                        trHTML += '<input type="number" class="form-control" placeholder="Insert" value="'+loan_item_2.parts_qty+'" oninput="qty_2(this, ' + loan_item_2.parts_qty + ', ' + loan_item_1.requisitioned_parts_qty + ', ' + loan_item_1.loan_indx_f + ', ' + loan_item_1.loan_indx_l + ')">';
                                                        trHTML += '<div class="input-group-prepend">';
                                                            trHTML += '<div class="input-group-text">' + parts_unit + '</div>';
                                                        trHTML += '</div>';
                                                    trHTML += '</div>';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' + loan_item_2.price + '</span>';

                                                    trHTML += '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.price+'" oninput="price_2(this, ' + loan_item_2.price + ')">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.party_name + '</span>';

                                                    trHTML += '<div class="data-input d-none">';
                                                        trHTML += '<select class="select-b-party" onchange="party_name_2(this)">';
                                                            trHTML += '<option selected value="'+loan_item_2.party_id+'|'+loan_item_2.party_remarks+'">'+loan_item_2.party_name+'</option>';
                                                        trHTML += '</select>';
                                                        trHTML += '<span class="float-left mt-1 text-primary remarks"></span>';
                                                    trHTML += '</div>';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.gate_no + '</span>';

                                                    trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.gate_no+'">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.challan_no + '</span>';

                                                    trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.challan_no+'">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">';
                                                        if(loan_item_2.challan_photo)
                                                            trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.challan_photo + '" target="_blank">' + loan_item_2.challan_photo + '</a>';
                                                        else
                                                            trHTML += '';
                                                    trHTML += '</span>';

                                                    trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                trHTML += '</td>';
                                                
                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">';
                                                        if(loan_item_2.bill_photo)
                                                            trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.bill_photo + '" target="_blank">' + loan_item_2.bill_photo + '</a>';
                                                        else
                                                            trHTML += '';
                                                    trHTML += '</span>';

                                                    trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.loan_date + '</span>';

                                                    trHTML += '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'+ loan_item_2.parts_id +'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'+loan_item_2.loan_date+'">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this)"><i class="mdi mdi-pencil"></i></a>';
                                                    trHTML += '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';
                                                    trHTML += '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_con(' + loan_item_2.loan_data_id + ', this, ' + loan_item_1.loan_id + ', ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';
                                                    trHTML += '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_con(' + loan_item_2.loan_data_id + ', this, ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + loan_item_1.loan_id + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-delete"></i></a>';
                                                trHTML += '</td>';
                                            trHTML += '</tr>';
                                        }
                                    });
                                });

                                $('.loan-records').empty().append(trHTML);

                                $('.select-b-party').select2({
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
                                            return '../../api/party';
                                        },
                                        dataType: 'json',
                                        method: 'POST',
                                        delay: 250,
                                        cache: false,
                                        data: function(param){
                                            return{
                                                party_data_type: 'fetch_all_by_srch_str',
                                                search_str: param.term
                                            };
                                        },
                                        processResults: function(data){
                                            let result = data.Reply.map(function(item){
                                                return{
                                                    id: item.party_id+'|'+item.party_remarks,
                                                    text: item.party_name
                                                };
                                            });

                                            return{
                                                results: result
                                            };
                                        }
                                    }
                                });
                            });
                        } else if(data.Type == 'error'){
                            if(ele2){
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
                                $('.full-width-modal-2').modal('hide');
                            }
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

                        // RELOAD CONSUMABLE LOAN TABLE DATA
                        if(!ele2){
                            $.ajax({
                                url: '../../api/loan',
                                method: 'post',
                                data: {
                                    loan_data_type: 'fetch_all',
                                    user_id: '<?= $user_id ?>',
                                    user_category: '<?= $user_category ?>'
                                },
                                dataType: 'json',
                                cache: false,
                                async: false,
                                success: function(result){
                                    if(result.Type == 'success'){
                                        if(result.Reply){
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
                                                },
                                                data: result.Reply,
                                                columns: [
                                                    { data: 'sl' },
                                                    { data: 'reference' },
                                                    { data: 'requisitioned_by' },
                                                    { data: 'accepted_by' },
                                                    { data: 'requisition_created' },
                                                    { data: 'loan_status' },
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

                                        if(result.Reply3){
                                            let table = $('.datatable-3').DataTable();

                                            table.destroy();

                                            $('.datatable-3').DataTable({
                                                // stateSave: !0,
                                                language: {
                                                    paginate: {
                                                        previous: '<i class="mdi mdi-chevron-left">',
                                                        next: '<i class="mdi mdi-chevron-right">'
                                                    }
                                                },
                                                drawCallback: function(){
                                                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                                                },
                                                data: result.Reply3,
                                                columns: [
                                                    { data: 'sl' },
                                                    { data: 'reference' },
                                                    { data: 'required_for' },
                                                    { data: 'parts_name' },
                                                    { data: 'parts_qty' },
                                                    { data: 'empty_data' },
                                                    { data: 'empty_data' },
                                                    { data: 'empty_data' },
                                                    { data: 'empty_data' },
                                                    { data: 'date' },
                                                    { data: 'empty_data' }
                                                ],
                                                columnDefs: [
                                                    {
                                                        targets: [ 5, 6, 7, 8, 10 ],
                                                        visible: !1,
                                                        searchable: !1
                                                    }
                                                ],
                                                order: [[ 9, 'DESC' ]]
                                            });
                                        } else{
                                            let table = $('.datatable-3').DataTable();

                                            table.destroy();
                                                
                                            $('.datatable-3').DataTable({
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
                                        let table = $('.datatable, .datatable-3').DataTable();

                                        table.destroy();
                                            
                                        $('.datatable, .datatable-3').DataTable({
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
                        }

                        return false;
                    }
                });
            }

            // UPDATE CONSUMABLE LOAN DATA
            function update_loan_data_con(ele, ele2, ele3, ele4, ele5, ele6, ele7){
                let loan_data_id = ele,
                    parts_id = ele4,
                    req_for = ele5,
                    qty = $(ele2).closest('tr').find('td:eq(1)').find('input').val(),
                    price = $(ele2).closest('tr').find('td:eq(2)').find('input').val(),
                    party = $(ele2).closest('tr').find('td:eq(3)').find('select').val(),
                    gate_no = $(ele2).closest('tr').find('td:eq(4)').find('input').val(),
                    challan_no = $(ele2).closest('tr').find('td:eq(5)').find('input').val(),
                    challan_photo = $(ele2).closest('tr').find('td:eq(6)').find('input').prop('files')[0],
                    bill_photo = $(ele2).closest('tr').find('td:eq(7)').find('input').prop('files')[0],
                    challan_file_data = '',
                    bill_file_data = '',
                    loan_date = $(ele2).closest('tr').find('td:eq(8)').find('input').val();

                if(qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === ''){
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
                } else{
                    if(challan_photo != undefined)
                        challan_file_data = challan_photo.name;

                    if(bill_photo != undefined)
                        bill_file_data = bill_photo.name;

                    if(challan_photo != undefined){
                        let form_data = new FormData();
                        form_data.append('file', challan_photo);

                        $.ajax({
                            url: '../../api/uploadImage',
                            method: 'post',
                            data: form_data,
                            dataType: 'json',
                            contentType : false,
                            processData: false,
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type === 'error'){
                                    flag = 0;
                                    msg = data.Reply;
                                }
                            }
                        });

                        if(flag == 0)
                            return false;
                    }

                    if(bill_photo != undefined){
                        let form_data = new FormData();
                        form_data.append('file', bill_photo);

                        $.ajax({
                            url: '../../api/uploadImage',
                            method: 'post',
                            data: form_data,
                            dataType: 'json',
                            contentType : false,
                            processData: false,
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type === 'error'){
                                    flag = 0;
                                    msg = data.Reply;
                                }
                            }
                        });

                        if(flag == 0)
                            return false;
                    }

                    $.ajax({
                        url: '../../api/interactionController',
                        method: 'post',
                        data: {
                            interact_type: 'update',
                            interact: 'loan_data',
                            id: loan_data_id,
                            loan_id: ele3,
                            parts_id: ele4,
                            req_for: ele5,
                            qty: qty,
                            price: price,
                            party: party,
                            gate_no: gate_no,
                            challan_no: challan_no,
                            challan_photo: challan_file_data,
                            bill_photo: bill_file_data,
                            loan_date: loan_date
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                let t;

                                Swal.fire({
                                    title: 'Updating Loan Data',
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

                                            view_loan_con(ele6, null, ele7, $('.view-by-party').val());
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
                                    footer: 'Something went wrong.'
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
            }

            function update_loan_data_con_2(ele, ele2, ele3, ele4, ele5){
                $('.upd-multiple-div').addClass('d-none');
                edit_data_arr.length = 0;

                let loan_data_id = ele,
                    parts_id = ele4,
                    req_for = ele5,
                    qty = $(ele2).closest('tr').find('td:eq(4)').find('input').val(),
                    price = $(ele2).closest('tr').find('td:eq(5)').find('input').val(),
                    party = $(ele2).closest('tr').find('td:eq(6)').find('select').val(),
                    gate_no = $(ele2).closest('tr').find('td:eq(7)').find('input').val(),
                    challan_no = $(ele2).closest('tr').find('td:eq(8)').find('input').val(),
                    loan_date = $(ele2).closest('tr').find('td:eq(9)').find('input').val();

                if(qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === ''){
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
                } else{
                    $.ajax({
                        url: '../../api/interactionController',
                        method: 'post',
                        data: {
                            interact_type: 'update',
                            interact: 'loan_data',
                            id: loan_data_id,
                            loan_id: ele3,
                            parts_id: ele4,
                            req_for: ele5,
                            qty: qty,
                            price: price,
                            party: party,
                            gate_no: gate_no,
                            challan_no: challan_no,
                            challan_photo: '',
                            bill_photo: '',
                            loan_date: loan_date
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                let t;

                                Swal.fire({
                                    title: 'Updating Loan Data',
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

                                            fetch_loan_data($('.type').val());
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
                                    footer: 'Something went wrong.'
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
            }

            // DELETE CONSUMABLE LOAN DATA
            function delete_loan_data_con(ele, ele2, ele3, ele4, ele5, ele6, ele7){
                let loan_data_id = ele,
                    qty = $(ele2).closest('tr').find('td:eq(1)').find('input').val(),
                    loan_date = $(ele2).closest('tr').find('td:eq(8)').find('input').val(),
                    parts_id = ele3,
                    req_for = ele4;

                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: 'The loan data will be deleted permanently!',
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
                                interact_type: 'delete',
                                interact: 'loan',
                                id: loan_data_id,
                                loan_id: ele5,
                                parts_id: parts_id,
                                req_for: req_for,
                                qty: qty,
                                loan_date: loan_date
                            },
                            dataType: 'json',
                            cache: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Deleting Loan Data',
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

                                                view_loan_con(ele6, null, ele7, $('.view-by-party').val());
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
                                        footer: 'Something went wrong.'
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
            }

            function delete_loan_data_con_2(ele, ele2, ele3, ele4, ele5){
                $('.upd-multiple-div').addClass('d-none');
                edit_data_arr.length = 0;

                let loan_data_id = ele,
                    qty = $(ele2).closest('tr').find('td:eq(4)').find('input').val(),
                    loan_date = $(ele2).closest('tr').find('td:eq(9)').find('input').val(),
                    parts_id = ele3,
                    req_for = ele4;

                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: 'The loan data will be deleted permanently!',
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
                                interact_type: 'delete',
                                interact: 'loan',
                                id: loan_data_id,
                                loan_id: ele5,
                                parts_id: parts_id,
                                req_for: req_for,
                                qty: qty,
                                loan_date: loan_date
                            },
                            dataType: 'json',
                            cache: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Deleting Loan Data',
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

                                                fetch_loan_data($('.type').val());
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
                                        footer: 'Something went wrong.'
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
            }

            // VIEW SPARE LOAN
            function view_loan_spr(ele, ele2 = null, ele3 = null, ele4 = null, ele5 = null){
                let requisition_id = ele;

                $.ajax({
                    url: '../../api/loan',
                    method: 'post',
                    data: {
                        loan_data_type: 'fetch_loan_spr',
                        requisition_id: requisition_id,
                        party_id: (ele5) ? ele5 : null
                    },
                    dataType: 'json',
                    cache: false,
                    async: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Fetching Loan Data',
                                text: 'Please wait...',
                                timer: 100,
                                allowOutsideClick: false,
                                onBeforeOpen: function(){
                                    Swal.showLoading(), t = setInterval(function(){
                                    }, 100);
                                }
                            }).then(function(){
                                let party_name_option = '';
                                let party_id_arr = [];

                                $.each(data.Reply2, function(i, party_item){
                                    if(party_id_arr.includes(party_item.party_id) === false){
                                        party_id_arr.push(party_item.party_id);

                                        let selected = (party_item.party_id == ele5) ? 'selected' : '';

                                        party_name_option += '<option value="'+party_item.party_id+'" ' + selected + '>'+party_item.party_name+'</option>';
                                    }
                                });

                                let tr_indx = ((ele2) ? $(ele2).closest('tr').index() : ele3);

                                $('.view-by-party').attr('data-id', [2, requisition_id, tr_indx]).empty().append('<option value="">Choose</option><option value="0" selected>All Parties</option>' + party_name_option);

                                let trHTML = '',
                                    required_for = '',
                                    parts_unit = '';

                                $.each(data.Reply1, function(i, loan_item_1){
                                    if(loan_item_1.required_for == 1)
                                        required_for = 'BCP-CCM';
                                    else if(loan_item_1.required_for == 2)
                                        required_for = 'BCP-Furnace';
                                    else if(loan_item_1.required_for == 3)
                                        required_for = 'Concast-CCM';
                                    else if(loan_item_1.required_for == 4)
                                        required_for = 'Concast-Furnace';
                                    else if(loan_item_1.required_for == 5)
                                        required_for = 'HRM';
                                    else if(loan_item_1.required_for == 6)
                                        required_for = 'HRM Unit-2';
                                    else if(loan_item_1.required_for == 7)
                                        required_for = 'Lal Masjid';
                                    else if(loan_item_1.required_for == 8)
                                        required_for = 'Sonargaon';
                                    else if(loan_item_1.required_for == 9)
                                        required_for = 'General';

                                    if(loan_item_1.parts_unit == 1)
                                        parts_unit = 'Bag';
                                    else if(loan_item_1.parts_unit == 2)
                                        parts_unit = 'Box';
                                    else if(loan_item_1.parts_unit == 3)
                                        parts_unit = 'Box/Pcs';
                                    else if(loan_item_1.parts_unit == 4)
                                        parts_unit = 'Bun';
                                    else if(loan_item_1.parts_unit == 5)
                                        parts_unit = 'Bundle';
                                    else if(loan_item_1.parts_unit == 6)
                                        parts_unit = 'Can';
                                    else if(loan_item_1.parts_unit == 7)
                                        parts_unit = 'Cartoon';
                                    else if(loan_item_1.parts_unit == 8)
                                        parts_unit = 'Challan';
                                    else if(loan_item_1.parts_unit == 9)
                                        parts_unit = 'Coil';
                                    else if(loan_item_1.parts_unit == 10)
                                        parts_unit = 'Drum';
                                    else if(loan_item_1.parts_unit == 11)
                                        parts_unit = 'Feet';
                                    else if(loan_item_1.parts_unit == 12)
                                        parts_unit = 'Gallon';
                                    else if(loan_item_1.parts_unit == 13)
                                        parts_unit = 'Item';
                                    else if(loan_item_1.parts_unit == 14)
                                        parts_unit = 'Job';
                                    else if(loan_item_1.parts_unit == 15)
                                        parts_unit = 'Kg';
                                    else if(loan_item_1.parts_unit == 16)
                                        parts_unit = 'Kg/Bundle';
                                    else if(loan_item_1.parts_unit == 17)
                                        parts_unit = 'Kv';
                                    else if(loan_item_1.parts_unit == 18)
                                        parts_unit = 'Lbs';
                                    else if(loan_item_1.parts_unit == 19)
                                        parts_unit = 'Ltr';
                                    else if(loan_item_1.parts_unit == 20)
                                        parts_unit = 'Mtr';
                                    else if(loan_item_1.parts_unit == 21)
                                        parts_unit = 'Pack';
                                    else if(loan_item_1.parts_unit == 22)
                                        parts_unit = 'Pack/Pcs';
                                    else if(loan_item_1.parts_unit == 23)
                                        parts_unit = 'Pair';
                                    else if(loan_item_1.parts_unit == 24)
                                        parts_unit = 'Pcs';
                                    else if(loan_item_1.parts_unit == 25)
                                        parts_unit = 'Pound';
                                    else if(loan_item_1.parts_unit == 26)
                                        parts_unit = 'Qty';
                                    else if(loan_item_1.parts_unit == 27)
                                        parts_unit = 'Roll';
                                    else if(loan_item_1.parts_unit == 28)
                                        parts_unit = 'Set';
                                    else if(loan_item_1.parts_unit == 29)
                                        parts_unit = 'Truck';
                                    else if(loan_item_1.parts_unit == 30)
                                        parts_unit = 'Unit';
                                    else if(loan_item_1.parts_unit == 31)
                                        parts_unit = 'Yeard';
                                    else if(loan_item_1.parts_unit == 32)
                                        parts_unit = '(Unit Unknown)';
                                    else if(loan_item_1.parts_unit == 33)
                                        parts_unit = 'SFT';
                                    else if(loan_item_1.parts_unit == 34)
                                        parts_unit = 'RFT';
                                    else if(loan_item_1.parts_unit == 35)
                                        parts_unit = 'CFT';

                                    trHTML += '<tr>';
                                        trHTML += '<td class="text-left alert-info" colspan="10">\
                                                        <i class="mdi mdi-drag-variant"></i> Required For: ' + required_for + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Parts Name: ' + loan_item_1.parts_name + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Old Spares Details:  ' + loan_item_1.old_spare_details + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Status:  ' + loan_item_1.status + '&emsp;&emsp;\
                                                        <i class="mdi mdi-drag-variant"></i> Remarks: ' + loan_item_1.remarks + '\
                                                        \
                                                        <span class="float-right">(' + loan_item_1.borrowed_parts_qty + '/' + loan_item_1.requisitioned_parts_qty + ' borrowed)</span>\
                                                        <div class="progress mt-1 mr-1 progress-sm w-25 float-right" style="background: #fff;">\
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: ' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '%;" aria-valuenow="' + ((loan_item_1.borrowed_parts_qty / loan_item_1.requisitioned_parts_qty) * 100) + '" aria-valuemin="0" aria-valuemax="100"></div>\
                                                        </div>\
                                                        \
                                                    </td>';
                                    trHTML += '</tr>';

                                    $.each(data.Reply2, function(j, loan_item_2){
                                        if(loan_item_2.parts_id == loan_item_1.parts_id){
                                            trHTML += '<tr>';
                                                trHTML += '<td class="align-middle text-center">' + (j+1) + '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.parts_qty + ' ' + parts_unit + '</span>';

                                                    trHTML += '<div class="input-group data-input d-none">';
                                                        trHTML += '<input type="number" class="form-control" placeholder="Insert" value="'+loan_item_2.parts_qty+'" oninput="qty_2(this, ' + loan_item_2.parts_qty + ', ' + loan_item_1.requisitioned_parts_qty + ', ' + loan_item_1.loan_indx_f + ', ' + loan_item_1.loan_indx_l + ')">';
                                                        trHTML += '<div class="input-group-prepend">';
                                                            trHTML += '<div class="input-group-text">' + parts_unit + '</div>';
                                                        trHTML += '</div>';
                                                    trHTML += '</div>';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span"><i class="mdi mdi-currency-bdt"></i>' + loan_item_2.price + '</span>';

                                                    trHTML += '<input type="number" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.price+'" oninput="price_2(this, ' + loan_item_2.price + ')">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.party_name + '</span>';

                                                    trHTML += '<div class="data-input d-none">';
                                                        trHTML += '<select class="select-b-party" onchange="party_name_2(this)">';
                                                            trHTML += '<option selected value="'+loan_item_2.party_id+'|'+loan_item_2.party_remarks+'"' + '>'+loan_item_2.party_name+'</option>';
                                                        trHTML += '</select>';
                                                        trHTML += '<span class="float-left mt-1 text-primary remarks"></span>';
                                                    trHTML += '</div>';    
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.gate_no + '</span>';

                                                    trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.gate_no+'">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.challan_no + '</span>';

                                                    trHTML += '<input type="text" class="form-control data-input d-none" placeholder="Insert" value="'+loan_item_2.challan_no+'">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">';
                                                        if(loan_item_2.challan_photo)
                                                            trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.challan_photo + '" target="_blank">' + loan_item_2.challan_photo + '</a>';
                                                        else
                                                            trHTML += '';
                                                    trHTML += '</span>';

                                                    trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                trHTML += '</td>';
                                                
                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">';
                                                        if(loan_item_2.bill_photo)
                                                            trHTML += '<a title="View photo" href="../../assets/images/uploads/' + loan_item_2.bill_photo + '" target="_blank">' + loan_item_2.bill_photo + '</a>';
                                                        else
                                                            trHTML += '';
                                                    trHTML += '</span>';

                                                    trHTML += '<input type="file" class="form-control data-input d-none" accept="image/*">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<span class="data-span">' + loan_item_2.loan_date + '</span>';

                                                    trHTML += '<input type="text" class="form-control data-input d-none" oninput="loan_date_2(this)" data-id="'+ loan_item_2.parts_id +'" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd" data-date-start-date="" data-date-end-date="1d" placeholder="Insert date" value="'+loan_item_2.loan_date+'">';
                                                trHTML += '</td>';

                                                trHTML += '<td class="align-middle text-center">';
                                                    trHTML += '<a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info mr-1 edt-btn" onclick="edit_btn(this)"><i class="mdi mdi-pencil"></i></a>';
                                                    trHTML += '<a title="Cancel" href="javascript:void(0)" class="btn btn-xs btn-warning d-none mr-1 cncl-btn" onclick="cancel_btn(this)"><i class="mdi mdi-cancel"></i></a>';
                                                    trHTML += '<a title="Update" href="javascript:void(0)" class="btn btn-xs btn-success d-none mr-1 upd-btn" onclick="update_loan_data_spr(' + loan_item_2.loan_data_id + ', this, ' + loan_item_1.loan_id + ', ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-arrow-up-bold-outline"></i></a>';
                                                    trHTML += '<a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger dlt-btn" onclick="delete_loan_data_spr(' + loan_item_2.loan_data_id + ', this, ' + loan_item_2.parts_id + ', ' + loan_item_1.required_for + ', ' + loan_item_1.loan_id + ', ' + requisition_id + ', ' + tr_indx + ')"><i class="mdi mdi-delete"></i></a>';
                                                trHTML += '</td>';
                                            trHTML += '</tr>';
                                        }
                                    });
                                });

                                $('.loan-records').empty().append(trHTML);

                                $('.select-b-party').select2({
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
                                            return '../../api/party';
                                        },
                                        dataType: 'json',
                                        method: 'POST',
                                        delay: 250,
                                        cache: false,
                                        data: function(param){
                                            return{
                                                party_data_type: 'fetch_all_by_srch_str',
                                                search_str: param.term
                                            };
                                        },
                                        processResults: function(data){
                                            let result = data.Reply.map(function(item){
                                                return{
                                                    id: item.party_id+'|'+item.party_remarks,
                                                    text: item.party_name
                                                };
                                            });

                                            return{
                                                results: result
                                            };
                                        }
                                    }
                                });
                            });
                        } else if(data.Type == 'error'){
                            if(ele2){
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
                                $('.full-width-modal-2').modal('hide');
                            }
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

                        // RELOAD SPARE LOAN TABLE DATA
                        if(!ele2){
                            $.ajax({
                                url: '../../api/loan',
                                method: 'post',
                                data: {
                                    loan_data_type: 'fetch_all',
                                    user_id: '<?= $user_id ?>',
                                    user_category: '<?= $user_category ?>'
                                },
                                dataType: 'json',
                                cache: false,
                                async: false,
                                success: function(result){
                                    if(result.Type == 'success'){
                                        if(result.Reply2){
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
                                                },
                                                data: result.Reply2,
                                                columns: [
                                                    { data: 'sl' },
                                                    { data: 'reference' },
                                                    { data: 'requisitioned_by' },
                                                    { data: 'accepted_by' },
                                                    { data: 'requisition_created' },
                                                    { data: 'loan_status' },
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

                                        if(result.Reply3){
                                            let table = $('.datatable-3').DataTable();

                                            table.destroy();

                                            $('.datatable-3').DataTable({
                                                // stateSave: !0,
                                                language: {
                                                    paginate: {
                                                        previous: '<i class="mdi mdi-chevron-left">',
                                                        next: '<i class="mdi mdi-chevron-right">'
                                                    }
                                                },
                                                drawCallback: function(){
                                                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                                                },
                                                data: result.Reply3,
                                                columns: [
                                                    { data: 'sl' },
                                                    { data: 'reference' },
                                                    { data: 'required_for' },
                                                    { data: 'parts_name' },
                                                    { data: 'parts_qty' },
                                                    { data: 'empty_data' },
                                                    { data: 'empty_data' },
                                                    { data: 'empty_data' },
                                                    { data: 'empty_data' },
                                                    { data: 'date' },
                                                    { data: 'empty_data' }
                                                ],
                                                columnDefs: [
                                                    {
                                                        targets: [ 5, 6, 7, 8, 10 ],
                                                        visible: !1,
                                                        searchable: !1
                                                    }
                                                ],
                                                order: [[ 9, 'DESC' ]]
                                            });
                                        } else{
                                            let table = $('.datatable-3').DataTable();

                                            table.destroy();
                                                
                                            $('.datatable-3').DataTable({
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
                                        let table = $('.datatable-2, .datatable-3').DataTable();

                                        table.destroy();
                                            
                                        $('.datatable-2, .datatable-3').DataTable({
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
                        }

                        return false;
                    }
                });
            }

            // UPDATE SPARE LOAN DATA
            function update_loan_data_spr(ele, ele2, ele3, ele4, ele5, ele6, ele7){
                let loan_data_id = ele,
                    parts_id = ele4,
                    req_for = ele5,
                    qty = $(ele2).closest('tr').find('td:eq(1)').find('input').val(),
                    price = $(ele2).closest('tr').find('td:eq(2)').find('input').val(),
                    party = $(ele2).closest('tr').find('td:eq(3)').find('select').val(),
                    gate_no = $(ele2).closest('tr').find('td:eq(4)').find('input').val(),
                    challan_no = $(ele2).closest('tr').find('td:eq(5)').find('input').val(),
                    challan_photo = $(ele2).closest('tr').find('td:eq(6)').find('input').prop('files')[0],
                    bill_photo = $(ele2).closest('tr').find('td:eq(7)').find('input').prop('files')[0],
                    challan_file_data = '',
                    bill_file_data = '',
                    loan_date = $(ele2).closest('tr').find('td:eq(8)').find('input').val();

                if(qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === ''){
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
                } else{
                    if(challan_photo != undefined)
                        challan_file_data = challan_photo.name;

                    if(bill_photo != undefined)
                        bill_file_data = bill_photo.name;

                    if(challan_photo != undefined){
                        let form_data = new FormData();
                        form_data.append('file', challan_photo);

                        $.ajax({
                            url: '../../api/uploadImage',
                            method: 'post',
                            data: form_data,
                            dataType: 'json',
                            contentType : false,
                            processData: false,
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type === 'error'){
                                    flag = 0;
                                    msg = data.Reply;
                                }
                            }
                        });

                        if(flag == 0)
                            return false;
                    }

                    if(bill_photo != undefined){
                        let form_data = new FormData();
                        form_data.append('file', bill_photo);

                        $.ajax({
                            url: '../../api/uploadImage',
                            method: 'post',
                            data: form_data,
                            dataType: 'json',
                            contentType : false,
                            processData: false,
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type === 'error'){
                                    flag = 0;
                                    msg = data.Reply;
                                }
                            }
                        });

                        if(flag == 0)
                            return false;
                    }

                    $.ajax({
                        url: '../../api/interactionController',
                        method: 'post',
                        data: {
                            interact_type: 'update',
                            interact: 'loan_data2',
                            id: loan_data_id,
                            loan_id: ele3,
                            parts_id: ele4,
                            req_for: ele5,
                            qty: qty,
                            price: price,
                            party: party,
                            gate_no: gate_no,
                            challan_no: challan_no,
                            challan_photo: challan_file_data,
                            bill_photo: bill_file_data,
                            loan_date: loan_date
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                let t;

                                Swal.fire({
                                    title: 'Updating Loan Data',
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
                                            
                                            view_loan_spr(ele6, null, ele7, $('.view-by-party').val());
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
                                    footer: 'Something went wrong.'
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
            }

            function update_loan_data_spr_2(ele, ele2, ele3, ele4, ele5){
                $('.upd-multiple-div').addClass('d-none');
                edit_data_arr.length = 0;

                let loan_data_id = ele,
                    parts_id = ele4,
                    req_for = ele5,
                    qty = $(ele2).closest('tr').find('td:eq(4)').find('input').val(),
                    price = $(ele2).closest('tr').find('td:eq(5)').find('input').val(),
                    party = $(ele2).closest('tr').find('td:eq(6)').find('select').val(),
                    gate_no = $(ele2).closest('tr').find('td:eq(7)').find('input').val(),
                    challan_no = $(ele2).closest('tr').find('td:eq(8)').find('input').val(),
                    loan_date = $(ele2).closest('tr').find('td:eq(9)').find('input').val();

                if(qty === '' || price === '' || party === '' || gate_no === '' || challan_no === '' || loan_date === ''){
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
                } else{
                    $.ajax({
                        url: '../../api/interactionController',
                        method: 'post',
                        data: {
                            interact_type: 'update',
                            interact: 'loan_data2',
                            id: loan_data_id,
                            loan_id: ele3,
                            parts_id: ele4,
                            req_for: ele5,
                            qty: qty,
                            price: price,
                            party: party,
                            gate_no: gate_no,
                            challan_no: challan_no,
                            challan_photo: '',
                            bill_photo: '',
                            loan_date: loan_date
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                let t;

                                Swal.fire({
                                    title: 'Updating Loan Data',
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

                                            fetch_loan_data($('.type').val());
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
                                    footer: 'Something went wrong.'
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
            }

            // DELETE SPARE LOAN DATA
            function delete_loan_data_spr(ele, ele2, ele3, ele4, ele5, ele6, ele7){
                let loan_data_id = ele,
                    qty = $(ele2).closest('tr').find('td:eq(1)').find('input').val(),
                    loan_date = $(ele2).closest('tr').find('td:eq(8)').find('input').val(),
                    parts_id = ele3,
                    req_for = ele4;

                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: 'The loan data will be deleted permanently!',
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
                                interact_type: 'delete',
                                interact: 'loan2',
                                id: loan_data_id,
                                loan_id: ele5,
                                parts_id: parts_id,
                                req_for: req_for,
                                qty: qty,
                                loan_date: loan_date
                            },
                            dataType: 'json',
                            cache: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Deleting Loan Data',
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

                                                view_loan_spr(ele6, null, ele7, ele8, $('.view-by-party').val());
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
                                        footer: 'Something went wrong.'
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
            }

            function delete_loan_data_spr_2(ele, ele2, ele3, ele4, ele5){
                $('.upd-multiple-div').addClass('d-none');
                edit_data_arr.length = 0;

                let loan_data_id = ele,
                    qty = $(ele2).closest('tr').find('td:eq(4)').find('input').val(),
                    loan_date = $(ele2).closest('tr').find('td:eq(9)').find('input').val(),
                    parts_id = ele3,
                    req_for = ele4;

                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: 'The loan data will be deleted permanently!',
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
                                interact_type: 'delete',
                                interact: 'loan2',
                                id: loan_data_id,
                                loan_id: ele5,
                                parts_id: parts_id,
                                req_for: req_for,
                                qty: qty,
                                loan_date: loan_date
                            },
                            dataType: 'json',
                            cache: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let t;

                                    Swal.fire({
                                        title: 'Deleting Loan Data',
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

                                                Swal.fire({
                                                    title: 'Success',
                                                    text: data.Reply,
                                                    type: 'success',
                                                    width: 450,
                                                    showCloseButton: false,
                                                    allowOutsideClick: false,
                                                    confirmButtonColor: '#5cb85c',
                                                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                                });

                                                fetch_loan_data($('.type').val());
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
                                        footer: 'Something went wrong.'
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
            }

            // PARTS LOAN
            function parts_loan(ele, ele2, ele3){
                $('.loan-repay-title').html('<strong>' + $(ele3).closest('tr').find('td:eq(1)').html() + '</strong>');
                $('.stock-qty').html('<strong>' + $(ele3).closest('tr').find('td:eq(3)').html() + ' ' + $(ele3).closest('tr').find('td:eq(2)').html() + '</strong>');

                // FETCH AND DISPLAY LOAN REPAY DATA
                let t;

                Swal.fire({
                    title: 'Fetching Loan Repay Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/loan',
                        method: 'post',
                        data: {
                            loan_data_type: 'fetch_parts_loan_data',
                            parts_id: ele,
                            parts_cat: ele2
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(result){
                            if(result.Type == 'success'){
                                if(result.Reply){
                                    let table = $('.datatable-5').DataTable();

                                    table.destroy();

                                    $('.datatable-5').DataTable({
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
                                            { data: 'required_for' },
                                            { data: 'req_qty' },
                                            { data: 'parts_qty' },
                                            { data: 'action_date' },
                                            { data: 'repay_qty' },
                                            { data: 'price' },
                                            { data: 'party_name' },
                                            { data: 'loan_date' },
                                            { data: 'action' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable-5').DataTable();

                                    table.destroy();
                                        
                                    $('.datatable-5').DataTable({
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
                                let table = $('.datatable-5').DataTable();

                                table.destroy();
                                    
                                $('.datatable-5').DataTable({
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
            }

            // PARTS LOAN REPAY HISTORY
            function loan_repay_history(ele, ele2, ele3){
                $('.loan-repay-history-title').html('<strong>' + $(ele3).closest('tr').find('td:eq(1)').html() + '</strong>');

                // FETCH AND DISPLAY LOAN REPAY HISTORY DATA
                let t;

                Swal.fire({
                    title: 'Fetching Loan Repay History Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/loan',
                        method: 'post',
                        data: {
                            loan_data_type: 'fetch_parts_loan_repay_history_data',
                            parts_id: ele,
                            parts_cat: ele2
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(result){
                            if(result.Type == 'success'){
                                if(result.Reply){
                                    let table = $('.datatable-6').DataTable();

                                    table.destroy();

                                    $('.datatable-6').DataTable({
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
                                            { data: 'parts_name' },
                                            { data: 'parts_unit' },
                                            { data: 'repaid_qty' },
                                            { data: 'repay_date' },
                                            { data: 'party_name' }
                                        ]
                                    });
                                } else{
                                    let table = $('.datatable-6').DataTable();

                                    table.destroy();

                                    $('.datatable-6').DataTable({
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
                            } else if(result.Type == 'error'){
                                let table = $('.datatable-6').DataTable();

                                table.destroy();

                                $('.datatable-6').DataTable({
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
            }

            // LOAN REPAY ACTION DATE
            function action_date(ele, ele2){
                let req_for = $(ele).closest('tr').find('td:eq(1)').html(),
                    parts_id = ele2;

                if(req_for == 'BCP-CCM')
                    req_for = 1;
                else if(req_for == 'BCP-Furnace')
                    req_for = 2;
                else if(req_for == 'Concast-CCM')
                    req_for = 3;
                else if(req_for == 'Concast-Furnace')
                    req_for = 4;
                else if(req_for == 'HRM')
                    req_for = 5;
                else if(req_for == 'HRM Unit-2')
                    req_for = 6;
                else if(req_for == 'Lal Masjid')
                    req_for = 7;
                else if(req_for == 'Sonargaon')
                    req_for = 8;
                else if(req_for == 'General')
                    req_for = 9;

                if($(ele).val()){
                    if(new Date($(ele).val()) < new Date($(ele).closest('tr').find('td:eq(8)').html())){
                        Swal.fire({
                            title: 'Error',
                            text: 'No loan record found!',
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'Repay date should be greater than borrow date.'
                        }).then(function(){
                            $(ele).closest('tr').find('td:eq(5)').find('input').prop('readonly', true).val('');
                            $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                        });
                    } else{
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts_id: parts_id,
                                required_for: req_for,
                                action_date: $(ele).val(),
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
                                            text: 'No inventory record found!',
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: 'Please receive some before repay.'
                                        }).then(function(){
                                            $(ele).closest('tr').find('td:eq(5)').find('input').prop('readonly', true).val('');
                                            $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                                        });
                                    } else if(data.Reply[0].parts_qty <= 0){
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'All received parts have been issued / repaid!',
                                            type: 'error',
                                            width: 450,
                                            showCloseButton: true,
                                            confirmButtonColor: '#5cb85c',
                                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                                            footer: 'Please receive some to repay.'
                                        }).then(function(){
                                            $(ele).closest('tr').find('td:eq(5)').find('input').prop('readonly', true).val('');
                                            $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                                        });
                                    } else{
                                        $(ele).closest('tr').find('td:eq(5)').find('input').prop('readonly', false).val('');
                                        $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                                    }
                                }
                            }
                        });
                    }
                } else{
                    $(ele).closest('tr').find('td:eq(5)').find('input').prop('readonly', true).val('');
                    $(ele).closest('tr').find('td:eq(9)').find('a').addClass('disabled');
                }
            }
        </script>
    </body>
</html>