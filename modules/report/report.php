<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Report</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 3];
            if(!in_array($user_category, $user_categories)){
                header('location: ../dashboard');
            }
        ?>
        
        <style type="text/css">
            .select2-container .select2-selection--single .select2-selection__arrow{
                height: 0px !important;
            }

            @media print{
                .report-print-1to9, .report-print-10, .report-print-11, .report-print-12, .report-print-13, .report-print-specific, .report-print-32{
                    margin: 100px 0px;
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
                                    <li class="breadcrumb-item active">Report</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-finance"></i> All Reports</h4>
                        </div>
                    </div>
                </div>     
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-2">
                        <!-- Portlet card -->
                        <div class="card">
                            <div class="card-body">
                                <div id="cardCollpase3" class="collapse show">
                                    <div class="slimscroll" style="max-height: 550px;">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="nav flex-column nav-pills nav-pills-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                    <a class="nav-link mb-2 report-link-4" id="report-type12-tab" data-id="12" data-toggle="pill" href="#report-type12" role="tab" aria-controls="report-type12" aria-selected="false">Summary</a>

                                                    <a class="nav-link mb-2 report-link-7" id="report-type32-tab" data-id="32" data-toggle="pill" href="#report-type32" role="tab" aria-controls="report-type32" aria-selected="false">Summary - Consumption</a>

                                                    <a class="nav-link mb-2 report-link-3" id="report-type11-tab" data-id="11" data-toggle="pill" href="#report-type11" role="tab" aria-controls="report-type11" aria-selected="false">Overall</a>
                                                    <a class="nav-link mb-2 report-link-2" id="report-type10-tab" data-id="10" data-toggle="pill" href="#report-type10" role="tab" aria-controls="report-type10" aria-selected="false">Stock Quantity</a>
                                                    <a class="nav-link mb-2 report-link-5" id="report-type13-tab" data-id="13" data-toggle="pill" href="#report-type13" role="tab" aria-controls="report-type13" aria-selected="false">Stock Value</a>
                                                    
                                                    <!-- BCP -->
                                                    <a class="nav-link mb-2 report-link" id="report-type1-tab" data-id="1" data-toggle="pill" href="#report-type1" role="tab" aria-controls="report-type1" aria-selected="false">BCP - CCM</a>
                                                    <a class="nav-link mb-2 report-link" id="report-type2-tab" data-id="2" data-toggle="pill" href="#report-type2" role="tab" aria-controls="report-type2" aria-selected="false">BCP - Furnace</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type14-tab" data-id="14" data-toggle="pill" href="#report-type14" role="tab" aria-controls="report-type14" aria-selected="false">BCP - Chemical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type15-tab" data-id="15" data-toggle="pill" href="#report-type15" role="tab" aria-controls="report-type15" aria-selected="false">BCP - Electrical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type16-tab" data-id="16" data-toggle="pill" href="#report-type16" role="tab" aria-controls="report-type16" aria-selected="false">BCP - Mechanical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type17-tab" data-id="17" data-toggle="pill" href="#report-type17" role="tab" aria-controls="report-type17" aria-selected="false">BCP - General</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type18-tab" data-id="18" data-toggle="pill" href="#report-type18" role="tab" aria-controls="report-type18" aria-selected="false">BCP - Machinery</a>

                                                    <!-- CONCAST -->
                                                    <a class="nav-link mb-2 report-link" id="report-type3-tab" data-id="3" data-toggle="pill" href="#report-type3" role="tab" aria-controls="report-type3" aria-selected="false">Concast - CCM</a>
                                                    <a class="nav-link mb-2 report-link" id="report-type4-tab" data-id="4" data-toggle="pill" href="#report-type4" role="tab" aria-controls="report-type4" aria-selected="false">Concast - Furnace</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type19-tab" data-id="19" data-toggle="pill" href="#report-type19" role="tab" aria-controls="report-type19" aria-selected="false">Concast - Chemical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type20-tab" data-id="20" data-toggle="pill" href="#report-type20" role="tab" aria-controls="report-type20" aria-selected="false">Concast - Electrical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type21-tab" data-id="21" data-toggle="pill" href="#report-type21" role="tab" aria-controls="report-type21" aria-selected="false">Concast - Mechanical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type22-tab" data-id="22" data-toggle="pill" href="#report-type22" role="tab" aria-controls="report-type22" aria-selected="false">Concast - General</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type23-tab" data-id="23" data-toggle="pill" href="#report-type23" role="tab" aria-controls="report-type23" aria-selected="false">Concast - Machinery</a>

                                                    <!-- HRM -->
                                                    <a class="nav-link mb-2 report-link" id="report-type5-tab" data-id="5" data-toggle="pill" href="#report-type5" role="tab" aria-controls="report-type5" aria-selected="false">HRM</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type24-tab" data-id="24" data-toggle="pill" href="#report-type24" role="tab" aria-controls="report-type24" aria-selected="false">HRM - Electrical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type25-tab" data-id="25" data-toggle="pill" href="#report-type25" role="tab" aria-controls="report-type25" aria-selected="false">HRM - Mechanical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type26-tab" data-id="26" data-toggle="pill" href="#report-type26" role="tab" aria-controls="report-type26" aria-selected="false">HRM - General</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type27-tab" data-id="27" data-toggle="pill" href="#report-type27" role="tab" aria-controls="report-type27" aria-selected="false">HRM - Machinery</a>

                                                    <!-- HRM UNIT 2 -->
                                                    <a class="nav-link mb-2 report-link" id="report-type5-tab" data-id="6" data-toggle="pill" href="#report-type6" role="tab" aria-controls="report-type6" aria-selected="false">HRM Unit-2</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type28-tab" data-id="28" data-toggle="pill" href="#report-type28" role="tab" aria-controls="report-type28" aria-selected="false">HRM Unit 2 - Chemical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type29-tab" data-id="29" data-toggle="pill" href="#report-type29" role="tab" aria-controls="report-type29" aria-selected="false">HRM Unit 2 - Electrical</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type30-tab" data-id="30" data-toggle="pill" href="#report-type30" role="tab" aria-controls="report-type30" aria-selected="false">HRM Unit 2 - General</a>
                                                    <a class="nav-link mb-2 report-link-6" id="report-type31-tab" data-id="31" data-toggle="pill" href="#report-type31" role="tab" aria-controls="report-type31" aria-selected="false">HRM Unit 2 - Mechanical</a>

                                                    <a class="nav-link mb-2 report-link" id="report-type7-tab" data-id="7" data-toggle="pill" href="#report-type7" role="tab" aria-controls="report-type7" aria-selected="false">Lal Masjid</a>
                                                    <a class="nav-link mb-2 report-link" id="report-type8-tab" data-id="8" data-toggle="pill" href="#report-type8" role="tab" aria-controls="report-type8" aria-selected="false">Sonargaon</a>
                                                    <a class="nav-link mb-2 report-link" id="report-type9-tab" data-id="9" data-toggle="pill" href="#report-type9" role="tab" aria-controls="report-type9" aria-selected="false">General</a>
                                                </div>
                                            </div> <!-- end col-->
                                        </div> <!-- end row-->
                                    </div> <!-- end slimscroll -->
                                </div> <!-- collapsed end -->
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-sm-10 default-prev">
                        <div class="tab-content pt-0">
                            <div class="tab-pane fade active show" role="tabpanel" >
                                <div class="card">
                                    <div class="card-body" style="height: 600px"> 
                                        <div class="position-absolute text-center" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                            <h3>Generate Groupwise, Department Wise, Parts Wise & Datewise Reports for Any Month or Year!</h3>

                                            <h4><i class="mdi mdi-24px mdi-hand-pointing-left"></i> Find, filter & print out any reports you need from the left panel.</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-10 hidden-prev d-none">
                        <div class="tab-content pt-0">
                            <!-- REPORTS TAB - 1 to 9 -->
                            <div class="tab-pane report-pane fade" role="tabpanel">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="select-b type">
                                                <option value="1" selected>Issued</option>
                                                <option value="2">Received</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="parts">Parts</label>
                                            <select class="select-b parts">
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3">
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
                                            <button type="button" class="btn btn-success waves-effect waves-light filter"><span class="btn-label"><i class="mdi mdi-filter-outline"></span></i>Filter Report</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-2" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link d-block" data-toggle="modal" data-target="#modal-1to9-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-f d-none" data-toggle="modal" data-target="#modal-1to9-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title"></h4><br>

                                        <table class="table nowrap scroll-horizontal-datatable-3 cell-border">
                                            <thead class="records-h" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Stock<br>Qty.</th>
                                                    <th class="align-middle text-center">Avg.<br>Rate</th>
                                                    <th class="align-middle text-center">Date</th>
                                                    <th class="align-middle text-center">Total<br>Qty.</th>
                                                    <th class="align-middle text-center">Total<br>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records">
                                            </tbody>
                                            <tfoot class="records-f" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Stock<br>Qty.</th>
                                                    <th class="align-middle text-center">Avg.<br>Rate</th>
                                                    <th class="align-middle text-center">Date</th>
                                                    <th class="align-middle text-center">Total<br>Qty.</th>
                                                    <th class="align-middle text-center">Total<br>Amount</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div>

                            <!-- STOCK QUANTITY REPORTS TAB - 10 -->
                            <div class="tab-pane report-pane-2 fade" id="report-type10" role="tabpanel" aria-labelledby="report-type Report Report Report Report10-tab">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="parts">Parts</label>
                                            <select class="select-b parts-2">
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="date">Date Range</label>
                                            <input type="text" class="form-control date-2" data-toggle="date-picker" data-cancel-class="btn-warning">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-2"><span class="btn-label"><i class="mdi mdi-filter-outline"></span></i>Filter Report</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-3" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-2 d-block" data-toggle="modal" data-target="#modal-stock-quantity-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-2-f d-none" data-toggle="modal" data-target="#modal-stock-quantity-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title-2"></h4><br>

                                        <table class="table nowrap for-complex-header cell-border">
                                            <thead class="records-h-2" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center" rowspan="2">Sl.</th>
                                                    <th class="align-middle text-center" rowspan="2">Parts</th>
                                                    <th class="align-middle text-center" rowspan="2">Unit</th>
                                                    <th class="align-middle text-center" rowspan="2">Opening<br>Quantity</th>
                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                </tr>
                                                <tr>
                                                    <th>Received</th>
                                                    <th>Issued</th>
                                                    <th>Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records-2">
                                            </tbody>
                                            <tfoot class="records-f-2" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Opening<br>Quantity</th>
                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div>

                            <!-- STOCK VALUE REPORTS TAB - 13 -->
                            <div class="tab-pane report-pane-5 fade" id="report-type13" role="tabpanel" aria-labelledby="report-type Report Report Report13-tab">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="select-b type-2">
                                                <option value="1" selected>Issued</option>
                                                <option value="2">Received</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="parts">Parts</label>
                                            <select class="select-b parts-4">
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="date">Date Range</label>
                                            <input type="text" class="form-control date-5" data-toggle="date-picker" data-cancel-class="btn-warning">
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-5"><span class="btn-label"><i class="mdi mdi-filter-outline"></span></i>Filter Report</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-2" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-5 d-block" data-toggle="modal" data-target="#modal-stock-value-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-5-f d-none" data-toggle="modal" data-target="#modal-stock-value-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title-5"></h4><br>

                                        <table class="table nowrap for-complex-header2 cell-border">
                                            <thead class="records-h-5" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center" rowspan="2">Sl.</th>
                                                    <th class="align-middle text-center" rowspan="2">Parts</th>
                                                    <th class="align-middle text-center" rowspan="2">Unit</th>
                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                </tr>
                                                <tr>
                                                    <th>Rate</th>
                                                    <th>Quantity</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records-5">
                                            </tbody>
                                            <tfoot class="records-f-5" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div>

                            <!-- OVERALL REPORTS TAB - 11 -->
                            <div class="tab-pane report-pane-3 fade" id="report-type11" role="tabpanel" aria-labelledby="report-type Report Report11-tab">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="parts">Parts</label>
                                            <select class="select-b parts-3">
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="date">Date Range</label>
                                            <input type="text" class="form-control date-3" data-toggle="date-picker" data-cancel-class="btn-warning">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-3"><span class="btn-label"><i class="mdi mdi-filter-outline" style=""></i></span>Filter Report</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-3 d-block" data-toggle="modal" data-target="#modal-overall-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-3-f d-none" data-toggle="modal" data-target="#modal-overall-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title-3"></h4><br>

                                        <table class="table nowrap custom-datatable-for-received cell-border">
                                            <thead class="records-h-3" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Category</th>
                                                    <th class="align-middle text-center">Subcategory</th>
                                                    <th class="align-middle text-center">Nick<br>Name</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Opening<br>Quantity</th>
                                                    <th class="align-middle text-center">Opening<br>Value</th>
                                                    <th class="align-middle text-center">Parts<br>Rate</th>
                                                    <th class="align-middle text-center">Average<br>Rate</th>
                                                    <th class="align-middle text-center">Received<br>Qty.</th>
                                                    <th class="align-middle text-center">Reecived<br>value</th>
                                                    <th class="align-middle text-center">Issued<br>Qty.</th>
                                                    <th class="align-middle text-center">Issued<br>Value</th>
                                                    <th class="align-middle text-center">Closing<br>Qty.</th>
                                                    <th class="align-middle text-center">Closing<br>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records-3">
                                            </tbody>
                                            <tfoot class="records-f-3" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Category</th>
                                                    <th class="align-middle text-center">Subcategory</th>
                                                    <th class="align-middle text-center">Nick<br>Name</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Opening<br>Quantity</th>
                                                    <th class="align-middle text-center">Opening<br>Value</th>
                                                    <th class="align-middle text-center">Parts<br>Rate</th>
                                                    <th class="align-middle text-center">Average<br>Rate</th>
                                                    <th class="align-middle text-center">Received<br>Qty.</th>
                                                    <th class="align-middle text-center">Reecived<br>value</th>
                                                    <th class="align-middle text-center">Issued<br>Qty.</th>
                                                    <th class="align-middle text-center">Issued<br>Value</th>
                                                    <th class="align-middle text-center">Closing<br>Qty.</th>
                                                    <th class="align-middle text-center">Closing<br>Value</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div>

                            <!-- SPECIFIC REPORTS TAB - 14 to 31 -->
                            <div class="tab-pane report-pane-6 fade" role="tabpanel">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="date">Date Range</label>
                                            <input type="text" class="form-control date-6" data-toggle="date-picker" data-cancel-class="btn-warning">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-6"><span class="btn-label"><i class="mdi mdi-filter-outline" style=""></i></span>Filter Report</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-6" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-6 d-block" data-toggle="modal" data-target="#modal-specific-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-6-f d-none" data-toggle="modal" data-target="#modal-specific-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title-6"></h4><br>

                                        <table class="table nowrap custom-datatable-for-received cell-border">
                                            <thead class="records-h-6" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Rate</th>
                                                    <th class="align-middle text-center">Qty.</th>
                                                    <th class="align-middle text-center">Total value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records-6">
                                            </tbody>
                                            <tfoot class="records-f-6" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Sl.</th>
                                                    <th class="align-middle text-center">Parts</th>
                                                    <th class="align-middle text-center">Unit</th>
                                                    <th class="align-middle text-center">Rate</th>
                                                    <th class="align-middle text-center">Qty.</th>
                                                    <th class="align-middle text-center">Total value</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div>

                            <!-- SUMMARY REPORTS TAB - 12 -->
                            <div class="tab-pane report-pane-4 fade" id="report-type12" role="tabpanel" aria-labelledby="report-type Report12-tab">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="date">Date Range</label>
                                            <input type="text" class="form-control date-4" data-toggle="date-picker" data-cancel-class="btn-warning">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-4"><span class="btn-label"><i class="mdi mdi-filter-outline" style=""></i></span>Filter Report</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-4 d-block" data-toggle="modal" data-target="#modal-summary-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-4-f d-none" data-toggle="modal" data-target="#modal-summary-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title-4"></h4><br>

                                        <table class="table nowrap custom-datatable-for-summary cell-border">
                                            <thead class="records-h-4" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Required<br>For</th>
                                                    <th class="align-middle text-center">Opening<br>Value</th>
                                                    <th class="align-middle text-center">Received<br>Value</th>
                                                    <th class="align-middle text-center">Issued<br>Value</th>
                                                    <th class="align-middle text-center">Closing<br>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records-4">
                                            </tbody>
                                            <tfoot class="records-f-4" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Required<br>For</th>
                                                    <th class="align-middle text-center">Opening<br>Value</th>
                                                    <th class="align-middle text-center">Received<br>Value</th>
                                                    <th class="align-middle text-center">Issued<br>Value</th>
                                                    <th class="align-middle text-center">Closing<br>Value</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->                                    
                            </div>

                            <!-- CONSUMPTION SUMMARY REPORTS TAB - 32 -->
                            <div class="tab-pane report-pane-7 fade" id="report-type32" role="tabpanel" aria-labelledby="report-type Report32-tab">
                                <div class="row margin-right-auto">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="date">Date Range</label>
                                            <input type="text" class="form-control date-7" data-toggle="date-picker" data-cancel-class="btn-warning">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row">
                                            <button type="button" class="btn btn-success waves-effect waves-light filter-7"><span class="btn-label"><i class="mdi mdi-filter-outline" style=""></i></span>Filter Report</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" style="float: right;">
                                        <div class="row">
                                            <label for="." style="visibility: hidden;">.</label> 
                                        </div>
                                        <div class="row" style="float: right;">
                                            <div class="button-list">
                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-7 d-block" data-toggle="modal" data-target="#modal-consumption-summary-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>

                                                <button type="button" class="btn btn-purple waves-effect waves-light print-report-link-7-f d-none" data-toggle="modal" data-target="#modal-consumption-summary-report"><span class="btn-label"><i class="mdi mdi-printer mr-1"></span></i>Print Report</button>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                        <h4 class="date-title-7"></h4><br>

                                        <table class="table nowrap custom-datatable-for-summary cell-border">
                                            <thead class="records-h-7" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Department</th>
                                                    <th class="align-middle text-center">Chemical</th>
                                                    <th class="align-middle text-center">Mechanical</th>
                                                    <th class="align-middle text-center">Electrical</th>
                                                    <th class="align-middle text-center">General</th>
                                                    <th class="align-middle text-center">Machinery</th>
                                                    <th class="align-middle text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="records-7">
                                            </tbody>
                                            <tfoot class="records-f-7" style="color: #fff; background-color: #5089de;">
                                                <tr>
                                                    <th class="align-middle text-center">Department</th>
                                                    <th class="align-middle text-center">Chemical</th>
                                                    <th class="align-middle text-center">Mechanical</th>
                                                    <th class="align-middle text-center">Electrical</th>
                                                    <th class="align-middle text-center">General</th>
                                                    <th class="align-middle text-center">Machinery</th>
                                                    <th class="align-middle text-center">Total</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->                                    
                            </div>

                            <!-- MODAL PRINT REPORT 1 to 9 -->
                            <div class="modal fade" id="modal-1to9-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-1to9">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title"></span></h4>

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

                                                        <table class="table table-bordered nowrap table-print-1to9">
                                                            <thead class="records-h-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Stock Qty.</th>
                                                                    <th class="align-middle text-center">Avg. Rate</th>
                                                                    <th class="align-middle text-center">Total Qty.</th>
                                                                    <th class="align-middle text-center">Total Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-print">
                                                            </tbody>
                                                            <tfoot class="records-f-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Stock Qty.</th>
                                                                    <th class="align-middle text-center">Avg. Rate</th>
                                                                    <th class="align-middle text-center">Total Qty.</th>
                                                                    <th class="align-middle text-center">Total Amount</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-1to9')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- MODAL PRINT STOCK QUANTITY REPORT -->
                            <div class="modal fade" id="modal-stock-quantity-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-10">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title">Stock Quantity Report</span></h4>

                                                                    <br>

                                                                    <span class="float-right date-title-2"></span>
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

                                                        <table class="table table-bordered nowrap table-print-10">
                                                            <thead class="records-h-2-print">
                                                                <tr>
                                                                    <th class="align-middle text-center" rowspan="2">Sl.</th>
                                                                    <th class="align-middle text-center" rowspan="2">Parts</th>
                                                                    <th class="align-middle text-center" rowspan="2">Unit</th>
                                                                    <th class="align-middle text-center" rowspan="2">Opening Quantity</th>
                                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Received</th>
                                                                    <th>Issue</th>
                                                                    <th>Stock</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-2-print">
                                                            </tbody>
                                                            <tfoot class="records-f-2-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Opening Quantity</th>
                                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-10')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- MODAL PRINT STOCK VALUE REPORT -->
                            <div class="modal fade" id="modal-stock-value-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-13">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title">Stock Value Report</span></h4>
                                                                    <br>

                                                                    <span class="float-right date-title-5"></span>
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

                                                        <table class="table table-bordered nowrap table-print-13">
                                                            <thead class="records-h-5-print">
                                                                <tr>
                                                                    <th class="align-middle text-center" rowspan="2">Sl.</th>
                                                                    <th class="align-middle text-center" rowspan="2">Parts</th>
                                                                    <th class="align-middle text-center" rowspan="2">Unit</th>
                                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Rate</th>
                                                                    <th>Quantity</th>
                                                                    <th>Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-5-print">
                                                            </tbody>
                                                            <tfoot class="records-f-5-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center" colspan="3">Date</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-13')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- MODAL PRINT OVERALL REPORT -->
                            <div class="modal fade" id="modal-overall-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-11">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title">Overall Report</span></h4>
                                                                    <br>

                                                                    <span class="float-right date-title-3"></span>
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

                                                        <table class="table table-bordered nowrap table-print-11">
                                                            <thead class="records-h-3-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Category</th>
                                                                    <th class="align-middle text-center">Subcategory</th>
                                                                    <th class="align-middle text-center">Nick<br>Name</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Opening<br>Quantity</th>
                                                                    <th class="align-middle text-center">Opening<br>Value</th>
                                                                    <th class="align-middle text-center">Parts<br>Rate</th>
                                                                    <th class="align-middle text-center">Average<br>Rate</th>
                                                                    <th class="align-middle text-center">Received<br>Qty.</th>
                                                                    <th class="align-middle text-center">Reecived<br>value</th>
                                                                    <th class="align-middle text-center">Issued<br>Qty.</th>
                                                                    <th class="align-middle text-center">Issued<br>Value</th>
                                                                    <th class="align-middle text-center">Closing<br>Qty.</th>
                                                                    <th class="align-middle text-center">Closing<br>Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-3-print">
                                                            </tbody>
                                                            <tfoot class="records-f-3-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Category</th>
                                                                    <th class="align-middle text-center">Subcategory</th>
                                                                    <th class="align-middle text-center">Nick<br>Name</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Opening<br>Quantity</th>
                                                                    <th class="align-middle text-center">Opening<br>Value</th>
                                                                    <th class="align-middle text-center">Parts<br>Rate</th>
                                                                    <th class="align-middle text-center">Average<br>Rate</th>
                                                                    <th class="align-middle text-center">Received<br>Qty.</th>
                                                                    <th class="align-middle text-center">Reecived<br>value</th>
                                                                    <th class="align-middle text-center">Issued<br>Qty.</th>
                                                                    <th class="align-middle text-center">Issued<br>Value</th>
                                                                    <th class="align-middle text-center">Closing<br>Qty.</th>
                                                                    <th class="align-middle text-center">Closing<br>Value</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-11')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- MODAL PRINT SPECIFIC REPORT -->
                            <div class="modal fade" id="modal-specific-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-specific">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title"></span></h4>

                                                                    <br>

                                                                    <span class="float-right date-title-6"></span>
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

                                                        <table class="table table-bordered nowrap table-print-specific">
                                                            <thead class="records-h-6-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Rate</th>
                                                                    <th class="align-middle text-center">Qty.</th>
                                                                    <th class="align-middle text-center">Total value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-6-print">
                                                            </tbody>
                                                            <tfoot class="records-f-6-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Sl.</th>
                                                                    <th class="align-middle text-center">Parts</th>
                                                                    <th class="align-middle text-center">Unit</th>
                                                                    <th class="align-middle text-center">Rate</th>
                                                                    <th class="align-middle text-center">Qty.</th>
                                                                    <th class="align-middle text-center">Total value</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-specific')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- MODAL PRINT SUMMARY REPORT -->
                            <div class="modal fade" id="modal-summary-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header d-print-none">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title">Summary Report</span></h4>
                                                                    <br>

                                                                    <span class="float-right date-title-4"></span>
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

                                                        <table class="table table-bordered nowrap table-print-12">
                                                            <thead class="records-h-4-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Required For</th>
                                                                    <th class="align-middle text-center">Opening Value</th>
                                                                    <th class="align-middle text-center">Received Value</th>
                                                                    <th class="align-middle text-center">Issued Value</th>
                                                                    <th class="align-middle text-center">Closing Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-4-print">
                                                            </tbody>
                                                            <tfoot class="records-f-4-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Required For</th>
                                                                    <th class="align-middle text-center">Opening Value</th>
                                                                    <th class="align-middle text-center">Received Value</th>
                                                                    <th class="align-middle text-center">Issued Value</th>
                                                                    <th class="align-middle text-center">Closing Value</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-12')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- MODAL PRINT CONSUMPTION SUMMARY REPORT -->
                            <div class="modal fade" id="modal-consumption-summary-report" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-full modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header d-print-none">
                                            <h4 class="modal-title" id="full-width-modalLabel">Print Report</h4>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>

                                        <div class="modal-body" style="overflow-y: auto; height: 500px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-box report-print-32">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="float-left">
                                                                    <h4 class="m-0"><span class="report-title">Summary Report</span></h4>
                                                                    <br>

                                                                    <span class="float-right date-title-7"></span>
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

                                                        <table class="table table-bordered nowrap table-print-32">
                                                            <thead class="records-h-7-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Department</th>
                                                                    <th class="align-middle text-center">Chemical</th>
                                                                    <th class="align-middle text-center">Mechanical</th>
                                                                    <th class="align-middle text-center">Electrical</th>
                                                                    <th class="align-middle text-center">General</th>
                                                                    <th class="align-middle text-center">Machinery</th>
                                                                    <th class="align-middle text-center">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="records-7-print">
                                                            </tbody>
                                                            <tfoot class="records-f-7-print">
                                                                <tr>
                                                                    <th class="align-middle text-center">Department</th>
                                                                    <th class="align-middle text-center">Chemical</th>
                                                                    <th class="align-middle text-center">Mechanical</th>
                                                                    <th class="align-middle text-center">Electrical</th>
                                                                    <th class="align-middle text-center">General</th>
                                                                    <th class="align-middle text-center">Machinery</th>
                                                                    <th class="align-middle text-center">Total</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="justify-content: center !important;">
                                            <a onclick="print_requisition('report-print-32')" href="" class="btn btn-purple waves-effect waves-light"><span class="btn-label"><i class="fas fa-print"></i></span>Print Report</a>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                        </div>
                    </div>
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
                $('.date, .date-2, .date-3, .date-4, .date-5, .date-6, .date-7').daterangepicker({
                    locale: {
                        separator: ' to ',
                        format: 'YYYY-MM-DD'
                    }
                }).on('keydown keyup', function(){
                    return false;
                });

                // VIEW REPORT 1-9
                $('.report-link').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // GET PARTS
                        let parts_name_option = '<option value="0">All</option>';

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
                                        parts_name_option += '<option value="'+parts_item.parts_id+'">'+parts_item.parts_name+'</option>';
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

                        $('.parts').empty().append(parts_name_option);

                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Stock<br>Qty.</th>\
                                            <th class="align-middle text-center">Avg.<br>Rate</th>';

                        $('.report-pane-2, .report-pane-3, .report-pane-4, .report-pane-5, .report-pane-6').removeClass('active show');
                        $('.report-pane').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = '';

                        table = $('.scroll-horizontal-datatable-3').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_all_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit,
                                                parts_qty: inventory.parts_qty,
                                                parts_avg_rate: inventory.parts_avg_rate
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_all_parts_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center">' + history_date_arr2[i] + '</th>';
                                    });

                                    table_header2 += '<th class="align-middle text-center">Total<br>Qty.</th>\
                                                    <th class="align-middle text-center">Total<br>Amount</th>';

                                    $('.records-h, .records-f').empty().append('<tr>' + table_header + table_header2 + '</tr>');
                        
                                    $('.records').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_qty + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_avg_rate + '</td>';

                                            let tot_qty = 0;

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + sorted_data[k].issued_qty + '</td>';
                                                                tot_qty = +tot_qty + +sorted_data[k].issued_qty;
                                                                flag = true;
                                                                break;
                                                            }
                                                            else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];

                                            table_data += '<td class="align-middle text-center">' + tot_qty.toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parts_info[i].parts_avg_rate * tot_qty).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records').append(table_data);

                                    $('.scroll-horizontal-datatable-3').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.scroll-horizontal-datatable-3').DataTable();
                                    table.destroy();

                                    $('.records-h, .records-f').empty().append('<tr>' + table_header + '<th class="align-middle text-center">Date</th><th class="align-middle text-center">Total<br>Qty.</th><th class="align-middle text-center">Total<br>Amount</th></tr>');
                        
                                    $('.records').empty();

                                    $('.scroll-horizontal-datatable-3').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 1-9
                $('.print-report-link').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '';

                        if(report_id == 1)
                            $('.report-title').html('BCP-CCM Report');
                        else if(report_id == 2)
                            $('.report-title').html('BCP-Furnace Report');
                        else if(report_id == 3)
                            $('.report-title').html('Concast-CCM Report');
                        else if(report_id == 4)
                            $('.report-title').html('Concast-Furnace Report');
                        else if(report_id == 5)
                            $('.report-title').html('HRM Report');
                        else if(report_id == 6)
                            $('.report-title').html('HRM Unit-2 Report');
                        else if(report_id == 7)
                            $('.report-title').html('Lal Masjid Report');
                        else if(report_id == 8)
                            $('.report-title').html('Sonargaon Report');
                        else if(report_id == 9)
                            $('.report-title').html('General Report');

                        table_header = '<th class="align-middle text-center">Sl.</th>\
                                        <th class="align-middle text-center">Partsss</th>\
                                        <th class="align-middle text-center">Unit</th>\
                                        <th class="align-middle text-center">Stock Qty.</th>\
                                        <th class="align-middle text-center">Avg. Rate</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_all_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit,
                                                parts_qty: inventory.parts_qty,
                                                parts_avg_rate: inventory.parts_avg_rate
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_all_parts_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center">' + history_date_arr2[i] + '</th>';
                                    });

                                    table_header2 += '<th class="align-middle text-center">Total Qty.</th>\
                                                    <th class="align-middle text-center">Total Amount</th>';

                                    $('.table-print-1to9 .records-h-print, .table-print-1to9 .records-f-print').empty().append('<tr>' + table_header + table_header2 + '</tr>');
                        
                                    $('.table-print-1to9 .records-print').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_qty + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_avg_rate + '</td>';

                                            let tot_qty = 0;

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + sorted_data[k].issued_qty + '</td>';
                                                                tot_qty = +tot_qty + +sorted_data[k].issued_qty;
                                                                flag = true;
                                                                break;
                                                            }
                                                            else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];

                                            table_data += '<td class="align-middle text-center">' + tot_qty.toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parts_info[i].parts_avg_rate * tot_qty).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-1to9 .records-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-1to9 .records-h-print, .table-print-1to9 .records-f-print').empty().append('<tr>' + table_header + '<th class="align-middle text-center">Date</th><th class="align-middle text-center">Total >Qty.</th><th class="align-middle text-center">Total Amount</th></tr>');
                        
                                    $('.table-print-1to9 .records-print').empty().append('<tr><td class="text-center" colspan="8">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // VIEW REPORT 10
                $('.report-link-2').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link-2').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-2-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-2').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Quantity Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // GET PARTS
                        let parts_name_option = '<option value="0">All</option>';

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
                                        parts_name_option += '<option value="'+parts_item.parts_id+'">'+parts_item.parts_name+'</option>';
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

                        $('.parts-2').empty().append(parts_name_option);

                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>\
                                            <th class="align-middle text-center" rowspan="2">Opening<br>Qty.</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening<br>Qty.</th>';

                        $('.report-pane-2').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.for-complex-header').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });
                        
                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_stock_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [],
                                        parts_name_arr = [],
                                        history_date_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.records-h-2').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.records-f-2').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.records-2').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            let new_opening_qty = 0;

                                            $.each(sorted_data, function(key, value){
                                                if(value.parts_id == parts_id_arr2[i]){
                                                    new_opening_qty = value.opening_qty;

                                                    return false;
                                                }
                                            });

                                            table_data += '<td class="align-middle text-center">' + new_opening_qty + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                let stock_qty = 0,
                                                    temp_stock_qty = 0;

                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].received_qty == 0) ? '-' : sorted_data[k].received_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';

                                                                if(j == 0){
                                                                    stock_qty = (+new_opening_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                } else{
                                                                    stock_qty = (+temp_stock_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                }

                                                                table_data += '<td class="align-middle text-center">' + stock_qty.toFixed(3) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        if(stock_qty == 0){
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + new_opening_qty + '</td>';
                                                            temp_stock_qty = new_opening_qty;
                                                        } else{
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + stock_qty + '</td>';
                                                            temp_stock_qty = stock_qty;
                                                        }
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }

                                    $('.records-2').append(table_data);

                                    $('.for-complex-header').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.for-complex-header').DataTable();
                                    table.destroy();

                                    $('.records-h-2').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th></tr>');

                                    $('.records-f-2').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.records-2').empty();

                                    $('.for-complex-header').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 10
                $('.print-report-link-2').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-2').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Quantity Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>\
                                            <th class="align-middle text-center" rowspan="2">Opening Qty.</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening Qty.</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_stock_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.table-print-10 .records-h-2-print').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.table-print-10 .records-f-2-print').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.table-print-10 .records-2-print').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            let new_opening_qty = 0;

                                            $.each(sorted_data, function(key, value){
                                                if(value.parts_id == parts_id_arr2[i]){
                                                    new_opening_qty = value.opening_qty;

                                                    return false;
                                                }
                                            });

                                            table_data += '<td class="align-middle text-center">' + new_opening_qty + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                let stock_qty = 0,
                                                    temp_stock_qty = 0;

                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].received_qty == 0) ? '-' : sorted_data[k].received_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';

                                                                if(j == 0){
                                                                    stock_qty = (+new_opening_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                } else{
                                                                    stock_qty = (+temp_stock_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                }

                                                                table_data += '<td class="align-middle text-center">' + stock_qty.toFixed(3) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        if(stock_qty == 0){
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + new_opening_qty + '</td>';
                                                            temp_stock_qty = new_opening_qty;
                                                        } else{
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + stock_qty + '</td>';
                                                            temp_stock_qty = stock_qty;
                                                        }
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-10 .records-2-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-10 .records-h-2-print').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th></tr>');

                                    $('.table-print-10 .records-f-2-print').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.table-print-10 .records-2-print').empty().append('<tr><td class="text-center" colspan="7">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // VIEW REPORT 11
                $('.report-link-3').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link-3').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-3-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-3').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Overall Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // GET PARTS
                        let parts_name_option = '<option value="0">All</option>';

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
                                        parts_name_option += '<option value="'+parts_item.parts_id+'">'+parts_item.parts_name+'</option>';
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

                        $('.parts-3').empty().append(parts_name_option);

                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Category</th>\
                                            <th class="align-middle text-center">Subcategory</th>\
                                            <th class="align-middle text-center">Nick<br>Name</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening<br>Quantity</th>\
                                            <th class="align-middle text-center">Opening<br>Value</th>\
                                            <th class="align-middle text-center">Parts<br>Rate</th>\
                                            <th class="align-middle text-center">Average<br>Rate</th>\
                                            <th class="align-middle text-center">Received<br>Qty.</th>\
                                            <th class="align-middle text-center">Received<br>value</th>\
                                            <th class="align-middle text-center">Issued<br>Qty.</th>\
                                            <th class="align-middle text-center">Issued<br>Value</th>\
                                            <th class="align-middle text-center">Closing<br>Qty.</th>\
                                            <th class="align-middle text-center">Closing<br>Value</th>';

                        $('.report-pane-3').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.custom-datatable-for-received').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_overall_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_nickname: inventory.parts_nickname,
                                                parts_category: inventory.parts_category,
                                                parts_subcategory: inventory.parts_subcategory,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_overall_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort()));

                                    $('.records-h-3, .records-f-3').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-3').empty();

                                    let table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';

                                            let parts_category = '';

                                            if(parts_info[i].parts_category == 1) parts_category = 'Spare';
                                            else if(parts_info[i].parts_category == 2) parts_category = 'Consumable';

                                            table_data += '<td class="align-middle text-center">' + parts_category + '</td>';

                                            let parts_subcategory = '';

                                            if(parts_info[i].parts_subcategory == 1) parts_subcategory = 'MP';
                                            else if(parts_info[i].parts_subcategory == 2) parts_subcategory = 'LC';
                                            else if(parts_info[i].parts_subcategory == 3) parts_subcategory = 'MP + LC';

                                            table_data += '<td class="align-middle text-center">' + parts_subcategory + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_nickname + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_value).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records-3').append(table_data);

                                    $('.custom-datatable-for-received').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-received').DataTable();
                                    table.destroy();

                                    $('.records-h-3, .records-f-3').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-3').empty();

                                    $('.custom-datatable-for-received').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 11
                $('.print-report-link-3').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-3').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Overall Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Category</th>\
                                            <th class="align-middle text-center">Subcategory</th>\
                                            <th class="align-middle text-center">Nick<br>Name</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening<br>Quantity</th>\
                                            <th class="align-middle text-center">Opening<br>Value</th>\
                                            <th class="align-middle text-center">Parts<br>Rate</th>\
                                            <th class="align-middle text-center">Average<br>Rate</th>\
                                            <th class="align-middle text-center">Received<br>Qty.</th>\
                                            <th class="align-middle text-center">Received<br>value</th>\
                                            <th class="align-middle text-center">Issued<br>Qty.</th>\
                                            <th class="align-middle text-center">Issued<br>Value</th>\
                                            <th class="align-middle text-center">Closing<br>Qty.</th>\
                                            <th class="align-middle text-center">Closing<br>Value</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_overall_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_nickname: inventory.parts_nickname,
                                                parts_category: inventory.parts_category,
                                                parts_subcategory: inventory.parts_subcategory,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_overall_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort()));

                                    $('.table-print-11 .records-h-3-print, .table-print-11 .records-f-3-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-11 .records-3-print').empty();

                                    let table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';

                                            let parts_category = '';

                                            if(parts_info[i].parts_category == 1) parts_category = 'Spare';
                                            else if(parts_info[i].parts_category == 2) parts_category = 'Consumable';

                                            table_data += '<td class="align-middle text-center">' + parts_category + '</td>';

                                            let parts_subcategory = '';

                                            if(parts_info[i].parts_subcategory == 1) parts_subcategory = 'MP';
                                            else if(parts_info[i].parts_subcategory == 2) parts_subcategory = 'LC';
                                            else if(parts_info[i].parts_subcategory == 3) parts_subcategory = 'MP + LC';

                                            table_data += '<td class="align-middle text-center">' + parts_subcategory + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_nickname + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_value).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-11 .records-3-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-11 .records-h-3-print, table-print-11 .records-f-3-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-11 .records-3-print').empty().append('<tr><td class="text-center" colspan="16">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // VIEW REPORT 12
                $('.report-link-4').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link-4').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-4-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-4').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Required<br>For</th>\
                                            <th class="align-middle text-center">Opening<br>Value</th>\
                                            <th class="align-middle text-center">Received<br>Value</th>\
                                            <th class="align-middle text-center">Issued<br>Value</th>\
                                            <th class="align-middle text-center">Closing<br>Value</th>';

                        $('.report-pane-4').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.custom-datatable-for-summary').DataTable();
                        
                        table.destroy();

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                curr_mon_status: 0,
                                inventory_data_type: 'fetch_all_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.records-h-4').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-4').empty();

                                    let table_data = '',
                                        grand_tot_opening_val = 0,
                                        grand_tot_received_val = 0,
                                        grand_tot_issued_val = 0,
                                        grand_tot_closing_val = 0;

                                    $.each(data.Reply, function(i, inventory){
                                        let required_for = '';

                                        if(i == 0)
                                            required_for = 'BCP';
                                        else if(i == 1)
                                            required_for = 'Concast';
                                        else if(i == 2)
                                            required_for = 'HRM';
                                        else if(i == 3)
                                            required_for = 'HRM Unit-2';
                                        else if(i == 4)
                                            required_for = 'Lal Masjid';
                                        else if(i == 5)
                                            required_for = 'Sonargaon';
                                        else if(i == 6)
                                            required_for = 'General';

                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + required_for + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (inventory.tot_opening_val).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (inventory.tot_received_val).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (inventory.tot_issued_val).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (inventory.tot_closing_val).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_opening_val = +grand_tot_opening_val + +inventory.tot_opening_val;
                                        grand_tot_received_val = +grand_tot_received_val + +inventory.tot_received_val;
                                        grand_tot_issued_val = +grand_tot_issued_val + +inventory.tot_issued_val;
                                        grand_tot_closing_val = +grand_tot_closing_val + +inventory.tot_closing_val;
                                    });
                                    
                                    $('.records-4').append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_opening_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_received_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_issued_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_closing_val.toFixed(2) + '</th>';

                                    $('.records-f-4').empty().append('<tr>' + table_footer + '</tr>');

                                    $('.custom-datatable-for-summary').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-summary').DataTable();
                                    table.destroy();

                                    $('.records-h-4, .records-f-4').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-4').empty();

                                    $('.custom-datatable-for-summary').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 12
                $('.print-report-link-4').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-4').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Required For</th>\
                                            <th class="align-middle text-center">Opening Value</th>\
                                            <th class="align-middle text-center">Received Value</th>\
                                            <th class="align-middle text-center">Issued Value</th>\
                                            <th class="align-middle text-center">Closing Value</th>';

                        $('.table').css('width', '100%');

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                curr_mon_status: 0,
                                inventory_data_type: 'fetch_all_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.table-print-12 .records-h-4-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-12 .records-4-print').empty();

                                    let table_data = '',
                                        grand_tot_opening_val = 0,
                                        grand_tot_received_val = 0,
                                        grand_tot_issued_val = 0,
                                        grand_tot_closing_val = 0;

                                    $.each(data.Reply, function(i, inventory){
                                        let required_for = '';

                                        if(i == 0)
                                            required_for = 'BCP';
                                        else if(i == 1)
                                            required_for = 'Concast';
                                        else if(i == 2)
                                            required_for = 'HRM';
                                        else if(i == 3)
                                            required_for = 'HRM Unit-2';
                                        else if(i == 4)
                                            required_for = 'Lal Masjid';
                                        else if(i == 5)
                                            required_for = 'Sonargaon';
                                        else if(i == 6)
                                            required_for = 'General';

                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + required_for + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_opening_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_received_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_issued_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_closing_val.toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_opening_val = +grand_tot_opening_val + +inventory.tot_opening_val;
                                        grand_tot_received_val = +grand_tot_received_val + +inventory.tot_received_val;
                                        grand_tot_issued_val = +grand_tot_issued_val + +inventory.tot_issued_val;
                                        grand_tot_closing_val = +grand_tot_closing_val + +inventory.tot_closing_val;
                                    });
                                    
                                    $('.table-print-12 .records-4-print').empty().append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_opening_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_received_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_issued_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_closing_val.toFixed(2) + '</th>';

                                    $('.table-print-12 .records-f-4-print').empty().append('<tr>' + table_footer + '</tr>');
                                } else if(data.Type == 'error'){
                                    $('.table-print-12 .records-h-4-print, .table-print-12 .records-f-4-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-12 .records-4-print').empty().append('<tr><td class="text-center" colspan="5">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // VIEW REPORT 13
                $('.report-link-5').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link-5').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-5-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-5').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Value Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // GET PARTS
                        let parts_name_option = '<option value="0">All</option>';

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
                                        parts_name_option += '<option value="'+parts_item.parts_id+'">'+parts_item.parts_name+'</option>';
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

                        $('.parts-4').empty().append(parts_name_option);

                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>';

                        $('.report-pane-5').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.for-complex-header2').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type-2').val(),
                                inventory_data_type: 'fetch_all_parts_stock_date_2'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.records-h-5').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.records-f-5').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.records-5').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].avg_parts_rate == 0) ? '-' : parseFloat(sorted_data[k].avg_parts_rate).toFixed(2)) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].parts_val == 0) ? '-' : sorted_data[k].parts_val.toFixed(2)) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td><td>-</td><td>-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records-5').append(table_data);

                                    $('.for-complex-header2').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.for-complex-header2').DataTable();
                                    table.destroy();

                                    $('.records-h-5').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th></tr>');

                                    $('.records-f-5').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.records-5').empty();

                                    $('.for-complex-header2').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 13
                $('.print-report-link-5').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-5').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Value Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_all_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type-2').val(),
                                inventory_data_type: 'fetch_all_parts_stock_date_2'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.table-print-13 .records-h-5-print').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.table-print-13 .records-f-5-print').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.table-print-13 .records-5-print').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].avg_parts_rate == 0) ? '-' : parseFloat(sorted_data[k].avg_parts_rate).toFixed(2)) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].parts_val == 0) ? '-' : sorted_data[k].parts_val.toFixed(2)) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td><td>-</td><td>-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-13 .records-5-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-13 .records-h-5-print').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th></tr>');

                                    $('.table-print-13 .records-f-5-print').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.table-print-13 .records-5-print').empty().append('<tr><td class="text-center" colspan="6">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // VIEW REPORT 14-31
                $('.report-link-6').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link-6').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-6-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-6').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Rate</th>\
                                            <th class="align-middle text-center">Qty.</th>\
                                            <th class="align-middle text-center">Total value</th>';

                        $('.report-pane, .report-pane-2, .report-pane-3, .report-pane-4, .report-pane-5').removeClass('active show');
                        $('.report-pane-6').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');
                        
                        let table = $('.custom-datatable-for-received').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                required_for: report_id,
                                inventory_data_type: 'fetch_specific_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                required_for: report_id,
                                inventory_data_type: 'fetch_specific_parts_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort()));

                                    $('.records-h-6, .records-f-6').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-6').empty();

                                    let table_data = '',
                                        grand_tot_parts_val = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_val).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_parts_val = +grand_tot_parts_val + +data.Reply[i].parts_val;
                                    }
                                    
                                    $('.records-6').append(table_data);

                                    let table_footer = '';
                                
                                    table_footer += '<th class="align-middle text-center" colspan="5">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_parts_val.toFixed(2) + '</th>';

                                    $('.records-f-6').empty().append('<tr>' + table_footer + '</tr>');

                                    $('.custom-datatable-for-received').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-received').DataTable();
                                    table.destroy();

                                    $('.records-h-6, .records-f-6').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-6').empty();

                                    $('.custom-datatable-for-received').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 14-31
                $('.print-report-link-6').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-6').html('Current Month Data For <strong>All Parts</strong> (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Rate</th>\
                                            <th class="align-middle text-center">Qty.</th>\
                                            <th class="align-middle text-center">Total value</th>';

                        if(report_id == 14)
                            $('.report-title').html('BCP - Chemical Report');
                        else if(report_id == 15)
                            $('.report-title').html('BCP - Electrical Report');
                        else if(report_id == 16)
                            $('.report-title').html('BCP - Mechanical Report');
                        else if(report_id == 17)
                            $('.report-title').html('BCP - General Report');
                        else if(report_id == 18)
                            $('.report-title').html('BCP - Machinery Report');
                        else if(report_id == 19)
                            $('.report-title').html('Concast - Chemical Report');
                        else if(report_id == 20)
                            $('.report-title').html('Concast - Electrical Report');
                        else if(report_id == 21)
                            $('.report-title').html('Concast - Mechanical Report');
                        else if(report_id == 22)
                            $('.report-title').html('Concast - General Report');
                        else if(report_id == 23)
                            $('.report-title').html('Concast - Machinery Report');
                        else if(report_id == 24)
                            $('.report-title').html('HRM - Electrical Report');
                        else if(report_id == 25)
                            $('.report-title').html('HRM - Mechanical Report');
                        else if(report_id == 26)
                            $('.report-title').html('HRM - General Report');
                        else if(report_id == 27)
                            $('.report-title').html('HRM - Machinery Report');
                        else if(report_id == 28)
                            $('.report-title').html('HRM Unit 2 - Electrical Report');
                        else if(report_id == 29)
                            $('.report-title').html('HRM Unit 2 - Mechanical Report');
                        else if(report_id == 30)
                            $('.report-title').html('HRM Unit 2 - General Report');
                        else if(report_id == 31)
                            $('.report-title').html('HRM Unit 2 - Machinery Report');

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                required_for: report_id,
                                inventory_data_type: 'fetch_specific_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                required_for: report_id,
                                inventory_data_type: 'fetch_specific_parts_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort()));

                                    $('.table-print-specific .records-h-6-print, .table-print-specific .records-f-6-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-specific .records-6-print').empty();

                                    let table_data = '',
                                        grand_tot_parts_val = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_val).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_parts_val = +grand_tot_parts_val + +data.Reply[i].parts_val;
                                    }
                                    
                                    $('.table-print-specific .records-6-print').empty().append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center" colspan="5">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_parts_val.toFixed(2) + '</th>';

                                    $('.table-print-specific .records-f-6-print').empty().append('<tr>' + table_footer + '</tr>');
                                } else if(data.Type == 'error'){
                                    $('.table-print-specific .records-h-6-print, .table-print-specific .records-f-6-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-specific .records-6-print').empty().append('<tr><td class="text-center" colspan="6">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });
                
                // VIEW REPORT 32
                $('.report-link-7').on('click', function(){
                    $('.default-prev').addClass('d-none');
                    $('.hidden-prev').removeClass('d-none');

                    $('.print-report-link-7').removeClass('d-none').addClass('d-block');
                    $('.print-report-link-7-f').removeClass('d-block').addClass('d-none');

                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-7').html('Current Month Data (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Consumption Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Department</th>\
                                            <th class="align-middle text-center">Chemical</th>\
                                            <th class="align-middle text-center">Mechanical</th>\
                                            <th class="align-middle text-center">Electrical</th>\
                                            <th class="align-middle text-center">General</th>\
                                            <th class="align-middle text-center">Machinery</th>\
                                            <th class="align-middle text-center">Total</th>';

                        $('.report-pane-7').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.custom-datatable-for-summary').DataTable();
                        
                        table.destroy();

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_issued_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.records-h-7').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-7').empty();

                                    let table_data = '',
                                        grand_tot_chemical = 0,
                                        grand_tot_mechanical = 0,
                                        grand_tot_electrical = 0,
                                        grand_tot_general = 0,
                                        grand_tot_machinery = 0,
                                        grand_tot = 0;

                                    $.each(data.Reply[0], function(i, inventory){
                                        table_data += '<tr>';
                                            if(i == 'bcp'){
                                                table_data += '<td class="align-middle text-center">BCP</td>';
                                            } else if(i == 'con'){
                                                table_data += '<td class="align-middle text-center">Concast</td>';
                                            } else if(i == 'hrm'){
                                                table_data += '<td class="align-middle text-center">HRM</td>';
                                            } else if(i == 'hrm2'){
                                                table_data += '<td class="align-middle text-center">HRM 2</td>';
                                            }

                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.chemical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.mechanical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.electrical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.general).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.machinery).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery)).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_chemical += parseFloat(inventory.chemical);
                                        grand_tot_mechanical += parseFloat(inventory.mechanical);
                                        grand_tot_electrical += parseFloat(inventory.electrical);
                                        grand_tot_general += parseFloat(inventory.general);
                                        grand_tot_machinery += parseFloat(inventory.machinery);
                                        grand_tot += (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery));
                                    });
                                    
                                    $('.records-7').append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_chemical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_mechanical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_electrical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_general.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_machinery.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot.toFixed(2) + '</th>';

                                    $('.records-f-7').empty().append('<tr>' + table_footer + '</tr>');

                                    $('.custom-datatable-for-summary').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-summary').DataTable();
                                    table.destroy();

                                    $('.records-h-7, .records-f-7').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-7').empty();

                                    $('.custom-datatable-for-summary').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT REPORT 32
                $('.print-report-link-7').on('click', function(){
                    // DATE TITLE
                    let curr_mon_date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
                    let f_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth(), 1);
                    f_date = f_date.getFullYear() + '-' + ('0' + (f_date.getMonth() + 1)).slice(-2) + '-' + ('0' + f_date.getDate()).slice(-2);
                    let l_date = new Date(curr_mon_date.getFullYear(), curr_mon_date.getMonth() + 1, 0);
                    l_date = l_date.getFullYear() + '-' + ('0' + (l_date.getMonth() + 1)).slice(-2) + '-' + ('0' + l_date.getDate()).slice(-2);
                    
                    $('.date-title-7').html('Current Month Data (' + f_date + ' to ' + l_date + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Consumption Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Department</th>\
                                            <th class="align-middle text-center">Chemical</th>\
                                            <th class="align-middle text-center">Mechanical</th>\
                                            <th class="align-middle text-center">Electrical</th>\
                                            <th class="align-middle text-center">General</th>\
                                            <th class="align-middle text-center">Machinery</th>\
                                            <th class="align-middle text-center">Total</th>';

                        
                        $('.report-title').html('Consumption Summary Report');

                        $('.table').css('width', '100%');

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                inventory_data_type: 'fetch_issued_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.table-print-32 .records-h-7-print, .table-print-32 .records-f-7-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-32 .records-7-print').empty();

                                    let table_data = '',
                                        grand_tot_chemical = 0,
                                        grand_tot_mechanical = 0,
                                        grand_tot_electrical = 0,
                                        grand_tot_general = 0,
                                        grand_tot_machinery = 0,
                                        grand_tot = 0;

                                    $.each(data.Reply[0], function(i, inventory){
                                        table_data += '<tr>';
                                            if(i == 'bcp'){
                                                table_data += '<td class="align-middle text-center">BCP</td>';
                                            } else if(i == 'con'){
                                                table_data += '<td class="align-middle text-center">Concast</td>';
                                            } else if(i == 'hrm'){
                                                table_data += '<td class="align-middle text-center">HRM</td>';
                                            } else if(i == 'hrm2'){
                                                table_data += '<td class="align-middle text-center">HRM 2</td>';
                                            }

                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.chemical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.mechanical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.electrical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.general).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.machinery).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery)).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_chemical += parseFloat(inventory.chemical);
                                        grand_tot_mechanical += parseFloat(inventory.mechanical);
                                        grand_tot_electrical += parseFloat(inventory.electrical);
                                        grand_tot_general += parseFloat(inventory.general);
                                        grand_tot_machinery += parseFloat(inventory.machinery);
                                        grand_tot += (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery));
                                    });
                                    
                                    $('.table-print-32 .records-7-print').empty().append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_chemical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_mechanical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_electrical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_general.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_machinery.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot.toFixed(2) + '</th>';

                                    $('.table-print-32 .records-f-7-print').empty().append('<tr>' + table_footer + '</tr>');
                                } else if(data.Type == 'error'){
                                    $('.table-print-32 .records-h-7-print, .table-print-32 .records-f-7-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-32 .records-7-print').empty().append('<tr><td class="text-center" colspan="7">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 1-9
                $('.filter').on('click', function(){
                    $('.print-report-link').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    let selected_parts = (($('.parts').val() == 0) ? 'All Parts' : $('.parts').select2('data')[0]['text']);

                    $('.date-title').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date').val() + ')');

                    let report_id = $('.nav').find('.active').attr('data-id'),
                        table_header = '<th class="align-middle text-center">Sl.</th>\
                                    <th class="align-middle text-center">Parts</th>\
                                    <th class="align-middle text-center">Unit</th>\
                                    <th class="align-middle text-center">Stock<br>Qty.</th>\
                                    <th class="align-middle text-center">Avg.<br>Rate</th>';

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        $('.report-pane-2, .report-pane-3, .report-pane-4, .report-pane-5, .report-pane-6').removeClass('active show');
                        $('.report-pane').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = $('.scroll-horizontal-datatable-3').DataTable();
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                parts: $('.parts').val(),
                                date_range: $('.date').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit,
                                                parts_qty: inventory.parts_qty,
                                                parts_avg_rate: inventory.parts_avg_rate
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                parts: $('.parts').val(),
                                date_range: $('.date').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_parts_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center">' + history_date_arr2[i] + '</th>';
                                    });

                                    table_header2 += '<th class="align-middle text-center">Total<br>Qty.</th>\
                                                    <th class="align-middle text-center">Total<br>Amount</th>';

                                    $('.records-h, .records-f').empty().append('<tr>' + table_header + table_header2 + '</tr>');
                        
                                    $('.records').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_qty + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_avg_rate + '</td>';

                                            let tot_qty = 0;

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + sorted_data[k].issued_qty + '</td>';
                                                                tot_qty = +tot_qty + +sorted_data[k].issued_qty;
                                                                flag = true;
                                                                break;
                                                            }
                                                            else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];

                                            table_data += '<td class="align-middle text-center">' + tot_qty.toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parts_info[i].parts_avg_rate * tot_qty).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records').append(table_data);

                                    $('.scroll-horizontal-datatable-3').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.scroll-horizontal-datatable-3').DataTable();
                                    table.destroy();

                                    $('.records-h, .records-f').empty().append('<tr>' + table_header + '<th class="align-middle text-center">Date</th><th class="align-middle text-center">Total<br>Qty.</th><th class="align-middle text-center">Total<br>Amount</th></tr>');
                        
                                    $('.records').empty();

                                    $('.scroll-horizontal-datatable-3').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 1-9
                $('.print-report-link-f').on('click', function(){
                    // DATE TITLE
                    let selected_parts = (($('.parts').val() == 0) ? 'All Parts' : $('.parts').select2('data')[0]['text']);

                    $('.date-title').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date').val() + ')');

                    let report_id = $('.nav').find('.active').attr('data-id'),
                        table_header = '<th class="align-middle text-center">Sl.</th>\
                                    <th class="align-middle text-center">Parts</th>\
                                    <th class="align-middle text-center">Unit</th>\
                                    <th class="align-middle text-center">Stock Qty.</th>\
                                    <th class="align-middle text-center">Avg. Rate</th>';

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                parts: $('.parts').val(),
                                date_range: $('.date').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit,
                                                parts_qty: inventory.parts_qty,
                                                parts_avg_rate: inventory.parts_avg_rate
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type').val(),
                                parts: $('.parts').val(),
                                date_range: $('.date').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_parts_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center">' + history_date_arr2[i] + '</th>';
                                    });

                                    table_header2 += '<th class="align-middle text-center">Total Qty.</th>\
                                                    <th class="align-middle text-center">Total Amount</th>';

                                    $('.table-print-1to9 .records-h-print, .table-print-1to9 .records-f-print').empty().append('<tr>' + table_header + table_header2 + '</tr>');
                        
                                    $('.table-print-1to9 .records-print').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_qty + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_avg_rate + '</td>';

                                            let tot_qty = 0;

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + sorted_data[k].issued_qty + '</td>';
                                                                tot_qty = +tot_qty + +sorted_data[k].issued_qty;
                                                                flag = true;
                                                                break;
                                                            }
                                                            else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];

                                            table_data += '<td class="align-middle text-center">' + tot_qty.toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parts_info[i].parts_avg_rate * tot_qty).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-1to9 .records-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-1to9 .records-h-print, .table-print-1to9 .records-f-print').empty().append('<tr>' + table_header + '<th class="align-middle text-center">Date</th><th class="align-middle text-center">Total Qty.</th><th class="align-middle text-center">Total Amount</th></tr>');
                        
                                    $('.table-print-1to9 .records-print').empty().append('<tr><td class="text-center" colspan="8">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 10
                $('.filter-2').on('click', function(){
                    $('.print-report-link-2').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-2-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    let selected_parts = (($('.parts-2').val() == 0) ? 'All Parts' : $('.parts-2').select2('data')[0]['text']);

                    $('.date-title-2').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date-2').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Quantity Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>\
                                            <th class="align-middle text-center" rowspan="2">Opening<br>Qty.</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening<br>Qty.</th>';

                        $('.report-pane-2').addClass('show');
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.for-complex-header').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-2').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-2').val(),
                                date_range: $('.date-2').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.records-h-2').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.records-f-2').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.records-2').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            let new_opening_qty = 0;

                                            $.each(sorted_data, function(key, value){
                                                if(value.parts_id == parts_id_arr2[i]){
                                                    new_opening_qty = value.opening_qty;

                                                    return false;
                                                }
                                            });

                                            table_data += '<td class="align-middle text-center">' + new_opening_qty + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                let stock_qty = 0,
                                                    temp_stock_qty = 0;

                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].received_qty == 0) ? '-' : sorted_data[k].received_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';

                                                                if(j == 0){
                                                                    stock_qty = (+new_opening_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                } else{
                                                                    stock_qty = (+temp_stock_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                }

                                                                table_data += '<td class="align-middle text-center">' + stock_qty.toFixed(3) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        if(stock_qty == 0){
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + new_opening_qty + '</td>';
                                                            temp_stock_qty = new_opening_qty;
                                                        } else{
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + stock_qty + '</td>';
                                                            temp_stock_qty = stock_qty;
                                                        }
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records-2').append(table_data);

                                    $('.for-complex-header').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.for-complex-header').DataTable();
                                    table.destroy();

                                    $('.records-h-2').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th></tr>');

                                    $('.records-f-2').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.records-2').empty();

                                    $('.for-complex-header').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 10
                $('.print-report-link-2-f').on('click', function(){
                    // DATE TITLE
                    let selected_parts = (($('.parts-2').val() == 0) ? 'All Parts' : $('.parts-2').select2('data')[0]['text']);

                    $('.date-title-2').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date-2').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Quantity Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>\
                                            <th class="align-middle text-center" rowspan="2">Opening Qty.</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening Qty.</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-2').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-2').val(),
                                date_range: $('.date-2').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_date'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.table-print-10 .records-h-2-print').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.table-print-10 .records-f-2-print').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.table-print-10 .records-2-print').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            let new_opening_qty = 0;

                                            $.each(sorted_data, function(key, value){
                                                if(value.parts_id == parts_id_arr2[i]){
                                                    new_opening_qty = value.opening_qty;

                                                    return false;
                                                }
                                            });

                                            table_data += '<td class="align-middle text-center">' + new_opening_qty + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                let stock_qty = 0,
                                                    temp_stock_qty = 0;

                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].received_qty == 0) ? '-' : sorted_data[k].received_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';

                                                                if(j == 0){
                                                                    stock_qty = (+new_opening_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                } else{
                                                                    stock_qty = (+temp_stock_qty + +sorted_data[k].received_qty) - sorted_data[k].issued_qty;
                                                                    temp_stock_qty = stock_qty;
                                                                }

                                                                table_data += '<td class="align-middle text-center">' + stock_qty.toFixed(3) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        if(stock_qty == 0){
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + new_opening_qty + '</td>';
                                                            temp_stock_qty = new_opening_qty;
                                                        } else{
                                                            table_data += '<td class="align-middle text-center">-</td><td>-</td><td>' + stock_qty + '</td>';
                                                            temp_stock_qty = stock_qty;
                                                        }
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-10 .records-2-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-10 .records-h-2-print').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Received</th><th class="align-middle text-center">Issued</th><th class="align-middle text-center">Stock</th></tr>');

                                    $('.table-print-10 .records-f-2-print').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.table-print-10 .records-2-print').empty().append('<tr><td class="text-center" colspan="7">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 11
                $('.filter-3').on('click', function(){
                    $('.print-report-link-3').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-3-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    let selected_parts = (($('.parts-3').val() == 0) ? 'All Parts' : $('.parts-3').select2('data')[0]['text']);

                    $('.date-title-3').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date-3').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Overall Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Category</th>\
                                            <th class="align-middle text-center">Subcategory</th>\
                                            <th class="align-middle text-center">Nick<br>Name</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening<br>Quantity</th>\
                                            <th class="align-middle text-center">Opening<br>Value</th>\
                                            <th class="align-middle text-center">Parts<br>Rate</th>\
                                            <th class="align-middle text-center">Average<br>Rate</th>\
                                            <th class="align-middle text-center">Received<br>Qty.</th>\
                                            <th class="align-middle text-center">Received<br>value</th>\
                                            <th class="align-middle text-center">Issued<br>Qty.</th>\
                                            <th class="align-middle text-center">Issued<br>Value</th>\
                                            <th class="align-middle text-center">Closing<br>Qty.</th>\
                                            <th class="align-middle text-center">Closing<br>Value</th>';

                        $('.report-pane-3').addClass('show');
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.custom-datatable-for-received').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-3').val(),
                                inventory_data_type: 'fetch_filtered_parts_overall_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_nickname: inventory.parts_nickname,
                                                parts_category: inventory.parts_category,
                                                parts_subcategory: inventory.parts_subcategory,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-3').val(),
                                date_range: $('.date-3').val(),
                                inventory_data_type: 'fetch_filtered_parts_overall_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    $('.records-h-3, .records-f-3').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-3').empty();

                                    let table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<data.Reply.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';

                                            let parts_category = '';

                                            if(data.Reply[i].parts_category == 1) parts_category = 'Spare';
                                            else if(data.Reply[i].parts_category == 2) parts_category = 'Consumable';

                                            table_data += '<td class="align-middle text-center">' + parts_category + '</td>';

                                            let parts_subcategory = '';

                                            if(data.Reply[i].parts_subcategory == 1) parts_subcategory = 'MP';
                                            else if(data.Reply[i].parts_subcategory == 2) parts_subcategory = 'LC';
                                            else if(data.Reply[i].parts_subcategory == 3) parts_subcategory = 'MP + LC';

                                            table_data += '<td class="align-middle text-center">' + parts_subcategory + '</td>';
                                            table_data += '<td class="align-middle text-center">' + data.Reply[i].parts_nickname + '</td>';
                                            table_data += '<td class="align-middle text-center">' + data.Reply[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(data.Reply[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(data.Reply[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(data.Reply[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(data.Reply[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(data.Reply[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(data.Reply[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(data.Reply[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(data.Reply[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(data.Reply[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(data.Reply[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(data.Reply[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(data.Reply[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(data.Reply[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(data.Reply[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(data.Reply[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(data.Reply[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(data.Reply[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(data.Reply[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(data.Reply[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(data.Reply[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(data.Reply[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(data.Reply[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(data.Reply[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(data.Reply[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(data.Reply[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(data.Reply[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(data.Reply[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(data.Reply[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(data.Reply[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(data.Reply[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(data.Reply[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(data.Reply[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_value).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records-3').append(table_data);

                                    $('.custom-datatable-for-received').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-received').DataTable();
                                    table.destroy();

                                    $('.records-h-3, .records-f-3').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-3').empty();

                                    $('.custom-datatable-for-received').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 11
                $('.print-report-link-3-f').on('click', function(){
                    // DATE TITLE
                    let selected_parts = (($('.parts-3').val() == 0) ? 'All Parts' : $('.parts-3').select2('data')[0]['text']);

                    $('.date-title-3').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date-3').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Overall Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Category</th>\
                                            <th class="align-middle text-center">Subcategory</th>\
                                            <th class="align-middle text-center">Nick<br>Name</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Opening<br>Quantity</th>\
                                            <th class="align-middle text-center">Opening<br>Value</th>\
                                            <th class="align-middle text-center">Parts<br>Rate</th>\
                                            <th class="align-middle text-center">Average<br>Rate</th>\
                                            <th class="align-middle text-center">Received<br>Qty.</th>\
                                            <th class="align-middle text-center">Received<br>value</th>\
                                            <th class="align-middle text-center">Issued<br>Qty.</th>\
                                            <th class="align-middle text-center">Issued<br>Value</th>\
                                            <th class="align-middle text-center">Closing<br>Qty.</th>\
                                            <th class="align-middle text-center">Closing<br>Value</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-3').val(),
                                inventory_data_type: 'fetch_filtered_parts_overall_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_nickname: inventory.parts_nickname,
                                                parts_category: inventory.parts_category,
                                                parts_subcategory: inventory.parts_subcategory,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-3').val(),
                                date_range: $('.date-3').val(),
                                inventory_data_type: 'fetch_filtered_parts_overall_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    $('table-print-11 .records-h-3-print, table-print-11 .records-f-3-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('table-print-11 .records-3-print').empty();

                                    let table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<data.Reply.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';

                                            let parts_category = '';

                                            if(data.Reply[i].parts_category == 1) parts_category = 'Spare';
                                            else if(data.Reply[i].parts_category == 2) parts_category = 'Consumable';

                                            table_data += '<td class="align-middle text-center">' + parts_category + '</td>';

                                            let parts_subcategory = '';

                                            if(data.Reply[i].parts_subcategory == 1) parts_subcategory = 'MP';
                                            else if(data.Reply[i].parts_subcategory == 2) parts_subcategory = 'LC';
                                            else if(data.Reply[i].parts_subcategory == 3) parts_subcategory = 'MP + LC';

                                            table_data += '<td class="align-middle text-center">' + parts_subcategory + '</td>';
                                            table_data += '<td class="align-middle text-center">' + data.Reply[i].parts_nickname + '</td>';
                                            table_data += '<td class="align-middle text-center">' + data.Reply[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(data.Reply[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(data.Reply[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(data.Reply[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(data.Reply[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(data.Reply[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(data.Reply[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(data.Reply[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(data.Reply[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(data.Reply[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(data.Reply[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(data.Reply[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(data.Reply[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(data.Reply[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(data.Reply[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(data.Reply[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(data.Reply[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(data.Reply[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(data.Reply[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(data.Reply[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(data.Reply[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(data.Reply[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(data.Reply[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(data.Reply[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(data.Reply[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(data.Reply[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(data.Reply[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(data.Reply[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(data.Reply[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(data.Reply[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(data.Reply[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(data.Reply[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(data.Reply[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].opening_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].received_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_value).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].closing_value).toFixed(2) + '</td>';
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.table-print-11 .records-3-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-11 .records-h-3-print, .table-print-11 .records-f-3-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-11 .records-3-print').empty().append('<tr><td class="text-center" colspan="16">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 12
                $('.filter-4').on('click', function(){
                    $('.print-report-link-4').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-4-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    $('.date-title-4').html('Filtered Data (' + $('.date-4').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Required<br>For</th>\
                                            <th class="align-middle text-center">Opening<br>Value</th>\
                                            <th class="align-middle text-center">Received<br>Value</th>\
                                            <th class="align-middle text-center">Issued<br>Value</th>\
                                            <th class="align-middle text-center">Closing<br>Value</th>';

                        $('.report-pane-4').addClass('show');
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.custom-datatable-for-summary').DataTable();
                        
                        table.destroy();

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-4').val(),
                                inventory_data_type: 'fetch_filtered_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.records-h-4, .records-f-4').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-4').empty();

                                    let table_data = '',
                                        grand_tot_opening_val = 0,
                                        grand_tot_received_val = 0,
                                        grand_tot_issued_val = 0,
                                        grand_tot_closing_val = 0;

                                    $.each(data.Reply, function(i, inventory){
                                        let required_for = '';

                                        if(i == 0)
                                            required_for = 'BCP';
                                        else if(i == 1)
                                            required_for = 'Concast';
                                        else if(i == 2)
                                            required_for = 'HRM';
                                        else if(i == 3)
                                            required_for = 'HRM Unit-2';
                                        else if(i == 4)
                                            required_for = 'Lal Masjid';
                                        else if(i == 5)
                                            required_for = 'Sonargaon';
                                        else if(i == 6)
                                            required_for = 'General';

                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + required_for + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_opening_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_received_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_issued_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_closing_val.toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_opening_val = +grand_tot_opening_val + +inventory.tot_opening_val;
                                        grand_tot_received_val = +grand_tot_received_val + +inventory.tot_received_val;
                                        grand_tot_issued_val = +grand_tot_issued_val + +inventory.tot_issued_val;
                                        grand_tot_closing_val = +grand_tot_closing_val + +inventory.tot_closing_val;
                                    });
                                    
                                    $('.records-4').append(table_data);

                                    let table_footer = '';
                                
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_opening_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_received_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_issued_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_closing_val.toFixed(2) + '</th>';

                                    $('.records-f-4').empty().append('<tr>' + table_footer + '</tr>');

                                    $('.custom-datatable-for-summary').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-summary').DataTable();
                                    table.destroy();

                                    $('.records-h-4, .records-f-4').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-4').empty();

                                    $('.custom-datatable-for-summary').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 12
                $('.print-report-link-4-f').on('click', function(){
                    // DATE TITLE
                    $('.date-title-4').html('Filtered Data (' + $('.date-4').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Required For</th>\
                                            <th class="align-middle text-center">Opening Value</th>\
                                            <th class="align-middle text-center">Received Value</th>\
                                            <th class="align-middle text-center">Issued Value</th>\
                                            <th class="align-middle text-center">Closing Value</th>';

                        $('.table').css('width', '100%');

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-4').val(),
                                inventory_data_type: 'fetch_filtered_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.table-print-12 .records-h-4-print, .table-print-12 .records-f-4-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-12 .records-4-print').empty();

                                    let table_data = '',
                                        grand_tot_opening_val = 0,
                                        grand_tot_received_val = 0,
                                        grand_tot_issued_val = 0,
                                        grand_tot_closing_val = 0;

                                    $.each(data.Reply, function(i, inventory){
                                        let required_for = '';

                                        if(i == 0)
                                            required_for = 'BCP';
                                        else if(i == 1)
                                            required_for = 'Concast';
                                        else if(i == 2)
                                            required_for = 'HRM';
                                        else if(i == 3)
                                            required_for = 'HRM Unit-2';
                                        else if(i == 4)
                                            required_for = 'Lal Masjid';
                                        else if(i == 5)
                                            required_for = 'Sonargaon';
                                        else if(i == 6)
                                            required_for = 'General';

                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + required_for + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_opening_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_received_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_issued_val.toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + inventory.tot_closing_val.toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_opening_val = +grand_tot_opening_val + +inventory.tot_opening_val;
                                        grand_tot_received_val = +grand_tot_received_val + +inventory.tot_received_val;
                                        grand_tot_issued_val = +grand_tot_issued_val + +inventory.tot_issued_val;
                                        grand_tot_closing_val = +grand_tot_closing_val + +inventory.tot_closing_val;
                                    });
                                    
                                    $('.table-print-12 .records-4-print').empty().append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_opening_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_received_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_issued_val.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_closing_val.toFixed(2) + '</th>';

                                    $('.table-print-12 .records-f-4-print').empty().append('<tr>' + table_footer + '</tr>');
                                } else if(data.Type == 'error'){
                                    $('.table-print-12 .records-h-4-print, .table-print-12 .records-f-4-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-12 .records-4-print').empty().append('<tr><td class="text-center" colspan="5">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 13
                $('.filter-5').on('click', function(){
                    $('.print-report-link-5').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-5-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    let selected_parts = (($('.parts-4').val() == 0) ? 'All Parts' : $('.parts-4').select2('data')[0]['text']);

                    $('.date-title-5').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date-5').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Value Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>';

                        $('.report-pane-5').addClass('show');
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.for-complex-header2').DataTable();
                        
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-4').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type-2').val(),
                                parts: $('.parts-4').val(),
                                date_range: $('.date-5').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_date_2'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.records-h-5').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.records-f-5').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.records-5').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].avg_parts_rate == 0) ? '-' : parseFloat(sorted_data[k].avg_parts_rate).toFixed(2)) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].parts_val == 0) ? '-' : sorted_data[k].parts_val.toFixed(2)) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td><td>-</td><td>-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records-5').append(table_data);

                                    $('.for-complex-header2').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.for-complex-header2').DataTable();
                                    table.destroy();

                                    $('.records-h-5').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th></tr>');

                                    $('.records-f-5').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.records-5').empty();

                                    $('.for-complex-header2').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 13
                $('.print-report-link-5-f').on('click', function(){
                    // DATE TITLE
                    let selected_parts = (($('.parts-4').val() == 0) ? 'All Parts' : $('.parts-4').select2('data')[0]['text']);

                    $('.date-title-5').html('Filtered Data For <strong>' + selected_parts + '</strong> (' + $('.date-5').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Stock Value Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center" rowspan="2">Sl.</th>\
                                            <th class="align-middle text-center" rowspan="2">Parts</th>\
                                            <th class="align-middle text-center" rowspan="2">Unit</th>',
                            table_footer = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>';

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];
                        
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                parts: $('.parts-4').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });

                                    parts_info = _.sortBy(parts_info, 'parts_id');
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                type: $('.type-2').val(),
                                parts: $('.parts-4').val(),
                                date_range: $('.date-5').val(),
                                inventory_data_type: 'fetch_filtered_parts_stock_date_2'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let history_date_arr = [],
                                        parts_id_arr = [],
                                        parts_name_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                        parts_name_arr.push(inventory.parts_name);
                                        history_date_arr.push(inventory.history_date);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort())),
                                        parts_name_arr2 = Array.from(new Set(parts_name_arr.sort())),
                                        history_date_arr2 = Array.from(new Set(history_date_arr.sort())),
                                        table_header2 = '',
                                        table_header3 = '',
                                        table_footer2 = '';

                                    $.each(history_date_arr2, function(i){
                                        table_header2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                        table_header3 += '<th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th>';
                                        table_footer2 += '<th class="align-middle text-center" colspan="3">' + history_date_arr2[i] + '</th>';
                                    });

                                    $('.table-print-13 .records-h-5-print').empty().append('<tr>' + table_header + table_header2 + '</tr><tr>' + table_header3 + '</tr>');
                                    $('.table-print-13 .records-f-5-print').empty().append('<tr>' + table_footer + table_footer2 + '</tr>');
                        
                                    $('.table-print-13 .records-5-print').empty();

                                    let sorted_data = _.sortBy(data.Reply, 'parts_id'),
                                        table_data = '',
                                        temp_parts_id = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_name_arr2[i] + '</td>';

                                            let unit_no = 0,
                                                parts_unit = '';

                                            $.each(data.Reply, function(j, inventory2){
                                                if(inventory2.parts_id == parts_id_arr2[i]){
                                                    unit_no = inventory2.parts_unit;

                                                    return false;
                                                }
                                            });

                                            if(unit_no == 1) parts_unit = 'Bag';
                                            else if(unit_no == 2) parts_unit = 'Box';
                                            else if(unit_no == 3) parts_unit = 'Box/Pcs';
                                            else if(unit_no == 4) parts_unit = 'Bun';
                                            else if(unit_no == 5) parts_unit = 'Bundle';
                                            else if(unit_no == 6) parts_unit = 'Can';
                                            else if(unit_no == 7) parts_unit = 'Cartoon';
                                            else if(unit_no == 8) parts_unit = 'Challan';
                                            else if(unit_no == 9) parts_unit = 'Coil';
                                            else if(unit_no == 10) parts_unit = 'Drum';
                                            else if(unit_no == 11) parts_unit = 'Feet';
                                            else if(unit_no == 12) parts_unit = 'Gallon';
                                            else if(unit_no == 13) parts_unit = 'Item';
                                            else if(unit_no == 14) parts_unit = 'Job';
                                            else if(unit_no == 15) parts_unit = 'Kg';
                                            else if(unit_no == 16) parts_unit = 'Kg/Bundle';
                                            else if(unit_no == 17) parts_unit = 'Kv';
                                            else if(unit_no == 18) parts_unit = 'Lbs';
                                            else if(unit_no == 19) parts_unit = 'Ltr';
                                            else if(unit_no == 20) parts_unit = 'Mtr';
                                            else if(unit_no == 21) parts_unit = 'Pack';
                                            else if(unit_no == 22) parts_unit = 'Pack/Pcs';
                                            else if(unit_no == 23) parts_unit = 'Pair';
                                            else if(unit_no == 24) parts_unit = 'Pcs';
                                            else if(unit_no == 25) parts_unit = 'Pound';
                                            else if(unit_no == 26) parts_unit = 'Qty';
                                            else if(unit_no == 27) parts_unit = 'Roll';
                                            else if(unit_no == 28) parts_unit = 'Set';
                                            else if(unit_no == 29) parts_unit = 'Truck';
                                            else if(unit_no == 30) parts_unit = 'Unit';
                                            else if(unit_no == 31) parts_unit = 'Yeard';
                                            else if(unit_no == 32) parts_unit = '(Unit Unknown)';
                                            else if(unit_no == 33) parts_unit = 'SFT';
                                            else if(unit_no == 34) parts_unit = 'RFT';
                                            else if(unit_no == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            if(parts_id_arr2[i] != temp_parts_id){
                                                for(let j=0; j<history_date_arr2.length; j++){
                                                    let flag = true;

                                                    for(let k=0; k<sorted_data.length; k++){
                                                        if(sorted_data[k].parts_id == parts_id_arr2[i]){
                                                            if(sorted_data[k].history_date == history_date_arr2[j]){
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].avg_parts_rate == 0) ? '-' : parseFloat(sorted_data[k].avg_parts_rate).toFixed(2)) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].issued_qty == 0) ? '-' : sorted_data[k].issued_qty) + '</td>';
                                                                table_data += '<td class="align-middle text-center">' + ((sorted_data[k].parts_val == 0) ? '-' : sorted_data[k].parts_val.toFixed(2)) + '</td>';

                                                                flag = true;
                                                                break;
                                                            } else{
                                                                flag = false;
                                                            }
                                                        }
                                                    }

                                                    if(flag == false){
                                                        table_data += '<td class="align-middle text-center">-</td><td>-</td><td>-</td>';
                                                    }
                                                }
                                            }

                                            temp_parts_id = parts_id_arr2[i];
                                        table_data += '</tr>';
                                    }
                                    
                                    $('.records-5-print').empty().append(table_data);
                                } else if(data.Type == 'error'){
                                    $('.table-print-13 .records-h-5-print').empty().append('<tr>' + table_header + '<th class="align-middle text-center" colspan="3">Date</th></tr><tr><th class="align-middle text-center">Rate</th><th class="align-middle text-center">Qty.</th><th class="align-middle text-center">Value</th></tr>');

                                    $('.table-print-13 .records-f-5-print').empty().append('<tr>' + table_footer + '<th class="align-middle text-center" colspan="3">Date</th></tr>');
                        
                                    $('.table-print-13 .records-5-print').empty().append('<tr><td class="text-center" colspan="6">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 14-31
                $('.filter-6').on('click', function(){
                    $('.print-report-link-6').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-6-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    $('.date-title-6').html('Filtered Data For <strong>All Parts</strong> (' + $('.date-6').val() + ')');

                    let report_id = $('.nav').find('.active').attr('data-id'),
                        table_header = '<th class="align-middle text-center">Sl.</th>\
                                        <th class="align-middle text-center">Parts</th>\
                                        <th class="align-middle text-center">Unit</th>\
                                        <th class="align-middle text-center">Rate</th>\
                                        <th class="align-middle text-center">Qty.</th>\
                                        <th class="align-middle text-center">Total value</th>';

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        $('.report-pane, .report-pane-2, .report-pane-3, .report-pane-4, .report-pane-5').removeClass('active show');
                        $('.report-pane-6').addClass('active show').attr({'id': 'report-type'+report_id, 'aria-labelledby': 'report-type'+report_id+'-tab'});
                        $('.table').css('width', '100%');

                        let table = $('.custom-datatable-for-received').DataTable();
                        table.destroy();

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-6').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_specific_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });
                                }

                                return false;
                            }
                        });

                        // PARTS INFO DATE
                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-6').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_specific_parts_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort()));

                                    $('.records-h-6, .records-f-6').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-6').empty();

                                    let table_data = '',
                                        grand_tot_parts_val = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_val).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_parts_val = +grand_tot_parts_val + +data.Reply[i].parts_val;
                                    }
                                    
                                    $('.records-6').append(table_data);

                                    let table_footer = '';
                                
                                    table_footer += '<th class="align-middle text-center" colspan="5">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_parts_val.toFixed(2) + '</th>';

                                    $('.records-f-6').empty().append('<tr>' + table_footer + '</tr>');

                                    $('.custom-datatable-for-received').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-received').DataTable();
                                    table.destroy();

                                    $('.records-h-6, .records-f-6').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-6').empty();

                                    $('.custom-datatable-for-received').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 14-31
                $('.print-report-link-6-f').on('click', function(){
                    // DATE TITLE
                    $('.date-title-6').html('Filtered Data For <strong>All Parts</strong> (' + $('.date-6').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Report Data',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let report_id = $('.nav').find('.active').attr('data-id'),
                            table_header = '<th class="align-middle text-center">Sl.</th>\
                                            <th class="align-middle text-center">Parts</th>\
                                            <th class="align-middle text-center">Unit</th>\
                                            <th class="align-middle text-center">Rate</th>\
                                            <th class="align-middle text-center">Qty.</th>\
                                            <th class="align-middle text-center">Total value</th>';

                        if(report_id == 14)
                            $('.report-title').html('BCP - Chemical Report');
                        else if(report_id == 15)
                            $('.report-title').html('BCP - Electrical Report');
                        else if(report_id == 16)
                            $('.report-title').html('BCP - Mechanical Report');
                        else if(report_id == 17)
                            $('.report-title').html('BCP - General Report');
                        else if(report_id == 18)
                            $('.report-title').html('BCP - Machinery Report');
                        else if(report_id == 19)
                            $('.report-title').html('Concast - Chemical Report');
                        else if(report_id == 20)
                            $('.report-title').html('Concast - Electrical Report');
                        else if(report_id == 21)
                            $('.report-title').html('Concast - Mechanical Report');
                        else if(report_id == 22)
                            $('.report-title').html('Concast - General Report');
                        else if(report_id == 23)
                            $('.report-title').html('Concast - Machinery Report');
                        else if(report_id == 24)
                            $('.report-title').html('HRM - Electrical Report');
                        else if(report_id == 25)
                            $('.report-title').html('HRM - Mechanical Report');
                        else if(report_id == 26)
                            $('.report-title').html('HRM - General Report');
                        else if(report_id == 27)
                            $('.report-title').html('HRM - Machinery Report');
                        else if(report_id == 28)
                            $('.report-title').html('HRM Unit 2 - Electrical Report');
                        else if(report_id == 29)
                            $('.report-title').html('HRM Unit 2 - Mechanical Report');
                        else if(report_id == 30)
                            $('.report-title').html('HRM Unit 2 - General Report');
                        else if(report_id == 31)
                            $('.report-title').html('HRM Unit 2 - Machinery Report');

                        $('.table').css('width', '100%');

                        // PARTS INFO
                        let parts_info = [];

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-6').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_specific_parts_info'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    let temp_parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        if(temp_parts_id_arr.indexOf(inventory.parts_id) <= -1){
                                            parts_info.push({
                                                parts_id: inventory.parts_id,
                                                parts_name: inventory.parts_name,
                                                parts_unit: inventory.parts_unit
                                            });
                                        }

                                        temp_parts_id_arr.push(inventory.parts_id);
                                    });
                                }

                                return false;
                            }
                        });

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-6').val(),
                                required_for: report_id,
                                inventory_data_type: 'fetch_filtered_specific_parts_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    // SET TABLE HEADER
                                    let parts_id_arr = [];

                                    $.each(data.Reply, function(i, inventory){
                                        parts_id_arr.push(inventory.parts_id);
                                    });

                                    let parts_id_arr2 = Array.from(new Set(parts_id_arr.sort()));

                                    $('.table-print-specific .records-h-6-print, .table-print-specific .records-f-6-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-specific .records-6-print').empty();

                                    let table_data = '',
                                        grand_tot_parts_val = 0;

                                    for(let i=0; i<parts_id_arr2.length; i++){
                                        table_data += '<tr>';
                                            table_data += '<td class="align-middle text-center">' + (i+1) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parts_info[i].parts_name + '</td>';

                                            let parts_unit = '';

                                            if(parts_info[i].parts_unit == 1) parts_unit = 'Bag';
                                            else if(parts_info[i].parts_unit == 2) parts_unit = 'Box';
                                            else if(parts_info[i].parts_unit == 3) parts_unit = 'Box/Pcs';
                                            else if(parts_info[i].parts_unit == 4) parts_unit = 'Bun';
                                            else if(parts_info[i].parts_unit == 5) parts_unit = 'Bundle';
                                            else if(parts_info[i].parts_unit == 6) parts_unit = 'Can';
                                            else if(parts_info[i].parts_unit == 7) parts_unit = 'Cartoon';
                                            else if(parts_info[i].parts_unit == 8) parts_unit = 'Challan';
                                            else if(parts_info[i].parts_unit == 9) parts_unit = 'Coil';
                                            else if(parts_info[i].parts_unit == 10) parts_unit = 'Drum';
                                            else if(parts_info[i].parts_unit == 11) parts_unit = 'Feet';
                                            else if(parts_info[i].parts_unit == 12) parts_unit = 'Gallon';
                                            else if(parts_info[i].parts_unit == 13) parts_unit = 'Item';
                                            else if(parts_info[i].parts_unit == 14) parts_unit = 'Job';
                                            else if(parts_info[i].parts_unit == 15) parts_unit = 'Kg';
                                            else if(parts_info[i].parts_unit == 16) parts_unit = 'Kg/Bundle';
                                            else if(parts_info[i].parts_unit == 17) parts_unit = 'Kv';
                                            else if(parts_info[i].parts_unit == 18) parts_unit = 'Lbs';
                                            else if(parts_info[i].parts_unit == 19) parts_unit = 'Ltr';
                                            else if(parts_info[i].parts_unit == 20) parts_unit = 'Mtr';
                                            else if(parts_info[i].parts_unit == 21) parts_unit = 'Pack';
                                            else if(parts_info[i].parts_unit == 22) parts_unit = 'Pack/Pcs';
                                            else if(parts_info[i].parts_unit == 23) parts_unit = 'Pair';
                                            else if(parts_info[i].parts_unit == 24) parts_unit = 'Pcs';
                                            else if(parts_info[i].parts_unit == 25) parts_unit = 'Pound';
                                            else if(parts_info[i].parts_unit == 26) parts_unit = 'Qty';
                                            else if(parts_info[i].parts_unit == 27) parts_unit = 'Roll';
                                            else if(parts_info[i].parts_unit == 28) parts_unit = 'Set';
                                            else if(parts_info[i].parts_unit == 29) parts_unit = 'Truck';
                                            else if(parts_info[i].parts_unit == 30) parts_unit = 'Unit';
                                            else if(parts_info[i].parts_unit == 31) parts_unit = 'Yeard';
                                            else if(parts_info[i].parts_unit == 32) parts_unit = '(Unit Unknown)';
                                            else if(parts_info[i].parts_unit == 33) parts_unit = 'SFT';
                                            else if(parts_info[i].parts_unit == 34) parts_unit = 'RFT';
                                            else if(parts_info[i].parts_unit == 35) parts_unit = 'CFT';

                                            table_data += '<td class="align-middle text-center">' + parts_unit + '</td>';

                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_avg_rate).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].issued_qty).toFixed(3) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(data.Reply[i].parts_val).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_parts_val = +grand_tot_parts_val + +data.Reply[i].parts_val;
                                    }
                                    
                                    $('.table-print-specific .records-6-print').empty().append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center" colspan="5">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_parts_val.toFixed(2) + '</th>';

                                    $('.table-print-specific .records-f-6-print').empty().append('<tr>' + table_footer + '</tr>');
                                } else if(data.Type == 'error'){
                                    $('.table-print-specific .records-h-6-print, .table-print-specific .records-f-6-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-specific .records-6-print').empty().append('<tr><td class="text-center" colspan="6">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });

                // FILTER REPORT 32
                $('.filter-7').on('click', function(){
                    $('.print-report-link-7').removeClass('d-block').addClass('d-none');
                    $('.print-report-link-7-f').removeClass('d-none').addClass('d-block');

                    // DATE TITLE
                    $('.date-title-7').html('Filtered Data (' + $('.date-7').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Consumption Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Department</th>\
                                        <th class="align-middle text-center">Chemical</th>\
                                        <th class="align-middle text-center">Mechanical</th>\
                                        <th class="align-middle text-center">Electrical</th>\
                                        <th class="align-middle text-center">General</th>\
                                        <th class="align-middle text-center">Machinery</th>\
                                        <th class="align-middle text-center">Total</th>';

                        $('.report-pane-7').addClass('show');
                        $('.table').css('width', '100%');

                        let table = '';
                        
                        table = $('.custom-datatable-for-summary').DataTable();
                        
                        table.destroy();

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-7').val(),
                                inventory_data_type: 'fetch_filtered_issued_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.records-h-7, .records-f-7').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-7').empty();

                                    let table_data = '',
                                        grand_tot_chemical = 0,
                                        grand_tot_mechanical = 0,
                                        grand_tot_electrical = 0,
                                        grand_tot_general = 0,
                                        grand_tot_machinery = 0,
                                        grand_tot = 0;

                                    $.each(data.Reply[0], function(i, inventory){
                                        table_data += '<tr>';
                                            if(i == 'bcp'){
                                                table_data += '<td class="align-middle text-center">BCP</td>';
                                            } else if(i == 'con'){
                                                table_data += '<td class="align-middle text-center">Concast</td>';
                                            } else if(i == 'hrm'){
                                                table_data += '<td class="align-middle text-center">HRM</td>';
                                            } else if(i == 'hrm2'){
                                                table_data += '<td class="align-middle text-center">HRM 2</td>';
                                            }

                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.chemical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.mechanical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.electrical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.general).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.machinery).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery)).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_chemical += parseFloat(inventory.chemical);
                                        grand_tot_mechanical += parseFloat(inventory.mechanical);
                                        grand_tot_electrical += parseFloat(inventory.electrical);
                                        grand_tot_general += parseFloat(inventory.general);
                                        grand_tot_machinery += parseFloat(inventory.machinery);
                                        grand_tot += (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery));
                                    });
                                    
                                    $('.records-7').append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_chemical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_mechanical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_electrical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_general.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_machinery.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot.toFixed(2) + '</th>';

                                    $('.records-f-7').empty().append('<tr>' + table_footer + '</tr>');

                                    $('.custom-datatable-for-summary').DataTable({
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
                                } else if(data.Type == 'error'){
                                    let table = $('.custom-datatable-for-summary').DataTable();
                                    table.destroy();

                                    $('.records-h-7, .records-f-7').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.records-7').empty();

                                    $('.custom-datatable-for-summary').DataTable({
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

                                return false;
                            }
                        });
                    });
                });

                // PRINT FILTERED REPORT 32
                $('.print-report-link-7-f').on('click', function(){
                    // DATE TITLE
                    $('.date-title-7').html('Filtered Data (' + $('.date-7').val() + ')');

                    let t;

                    Swal.fire({
                        title: 'Loading Consumption Summary Report',
                        text: 'Please wait...',
                        timer: 100,
                        allowOutsideClick: false,
                        onBeforeOpen: function(){
                            Swal.showLoading(), t = setInterval(function(){
                            }, 100);
                        }
                    }).then(function(){
                        // CREATE TABLE
                        let table_header = '<th class="align-middle text-center">Department</th>\
                                            <th class="align-middle text-center">Chemical</th>\
                                            <th class="align-middle text-center">Mechanical</th>\
                                            <th class="align-middle text-center">Electrical</th>\
                                            <th class="align-middle text-center">General</th>\
                                            <th class="align-middle text-center">Machinery</th>\
                                            <th class="align-middle text-center">Total</th>';

                        $('.table').css('width', '100%');

                        $.ajax({
                            url: '../../api/inventory',
                            method: 'post',
                            data: {
                                date_range: $('.date-7').val(),
                                inventory_data_type: 'fetch_filtered_issued_parts_summary_details'
                            },
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function(data){
                                if(data.Type == 'success'){
                                    $('.table-print-32 .records-h-7-print, .table-print-32 .records-f-7-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-32 .records-7-print').empty();

                                    let table_data = '',
                                        grand_tot_chemical = 0,
                                        grand_tot_mechanical = 0,
                                        grand_tot_electrical = 0,
                                        grand_tot_general = 0,
                                        grand_tot_machinery = 0,
                                        grand_tot = 0;

                                    $.each(data.Reply[0], function(i, inventory){
                                        table_data += '<tr>';
                                            if(i == 'bcp'){
                                                table_data += '<td class="align-middle text-center">BCP</td>';
                                            } else if(i == 'con'){
                                                table_data += '<td class="align-middle text-center">Concast</td>';
                                            } else if(i == 'hrm'){
                                                table_data += '<td class="align-middle text-center">HRM</td>';
                                            } else if(i == 'hrm2'){
                                                table_data += '<td class="align-middle text-center">HRM 2</td>';
                                            }

                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.chemical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.mechanical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.electrical).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.general).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + parseFloat(inventory.machinery).toFixed(2) + '</td>';
                                            table_data += '<td class="align-middle text-center">' + (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery)).toFixed(2) + '</td>';
                                        table_data += '</tr>';

                                        grand_tot_chemical += parseFloat(inventory.chemical);
                                        grand_tot_mechanical += parseFloat(inventory.mechanical);
                                        grand_tot_electrical += parseFloat(inventory.electrical);
                                        grand_tot_general += parseFloat(inventory.general);
                                        grand_tot_machinery += parseFloat(inventory.machinery);
                                        grand_tot += (parseFloat(inventory.chemical) + parseFloat(inventory.mechanical) + parseFloat(inventory.electrical) + parseFloat(inventory.general) + parseFloat(inventory.machinery));
                                    });
                                    
                                    $('.table-print-32 .records-7-print').empty().append(table_data);

                                    let table_footer = '';
                                    
                                    table_footer += '<th class="align-middle text-center">Grand Total</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_chemical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_mechanical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_electrical.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_general.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot_machinery.toFixed(2) + '</th>';
                                    table_footer += '<th class="align-middle text-center">' + grand_tot.toFixed(2) + '</th>';

                                    $('.table-print-32 .records-f-7-print').empty().append('<tr>' + table_footer + '</tr>');
                                } else if(data.Type == 'error'){
                                    $('.table-print-32 .records-h-7-print, .table-print-32 .records-f-7-print').empty().append('<tr>' + table_header + '</tr>');
                        
                                    $('.table-print-32 .records-7-print').empty().append('<tr><td class="text-center" colspan="7">No data available in table</td></tr>');
                                }

                                return false;
                            }
                        });
                    });
                });
            });

            // PRINT REQUISITION
            function print_requisition(ele){
                var restorepage = $('body').html();
                var printcontent = $('.' + ele).clone();
                $('body').empty().html(printcontent);
                window.print();
                $('body').html(restorepage);
            }
        </script>
    </body>
</html>