<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Parts</title>
        
        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 3];
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

            .datatable > tbody > tr > td{
                vertical-align: middle;
            }
        </style>
    </head>

    <body>
        <!-- Navigation Bar-->
        <header id="topnav">
            <?php include('../../topbar-for-sub-page.php'); ?>
            <?php include('../../navbar-for-sub-page.php'); ?>
        </header>

        <div class="wrapper full-width-background">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Parts</a></li>
                                    <li class="breadcrumb-item active">Parts List</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-cube-outline"></i> Parts List</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <!-- Start Modals For Add Parts -->
                            <div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Add Parts</h4> 

                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>

                                        <form action="javascript:void(0);" method="post" name="formAddParts" class="add-parts-form" enctype="multipart/form-data" data-parsley-validate>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card" style="margin-bottom: 0">
                                                            <div class="card-body">
                                                                <div class="alert alert-success add-parts-success d-none fade show">
                                                                    <h4 class="mt-0">Success</h4>
                                                                    <p class="mb-0">All the required fields are filled!</p>
                                                                </div>

                                                                <div class="alert alert-danger add-parts-danger d-none fade show">
                                                                    <h4 class="mt-0">Error</h4>
                                                                    <p class="mb-0">Please fill all the required fields!</p>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="parts_name">Parts Name <span style="color: #f0643b">*</span></label>
                                                                            <input type="text" class="form-control" name="parts_name" id="parts_name" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="parts_nickname">Parts Nick Name</label>
                                                                            <input type="text" class="form-control" name="parts_nickname" id="parts_nickname" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="category">Category <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="category" id="category" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Spares</option>
                                                                                <option value="2">Consumable</option>

                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="subcategory">Subcategory <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="subcategory" id="subcategory" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">MP</option>
                                                                                <option value="2">LC</option>
                                                                                <option value="3">Both</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="subcategory">Subcategory 2 <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="subcategory_2" id="subcategory_2" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">New</option>
                                                                                <option value="2">Repair</option>
                                                                                <option value="3">Replacement</option>
                                                                                <option value="4">Refill</option>
                                                                                <option value="5">Forma</option>
                                                                                <option value="6">Service Charge</option>
                                                                                <option value="7">Transport</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="type">Parts Type <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="type" id="type" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Asset</option>
                                                                                <option value="2">Non-Asset</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="group">Group <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="group" id="group" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Mechanical</option>
                                                                                <option value="2">Electrical</option>
                                                                                <option value="3">Chemical</option>
                                                                                <option value="4">Machinery</option>
                                                                                <option value="5">IT</option>
                                                                                <option value="6">Rolls</option>
                                                                                <option value="7">General</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="unit">Unit <span style="color: #f0643b">*</span></label>
                                                                            <select data-placeholder="Choose" name="unit" id="unit" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Bag</option>
                                                                                <option value="2">Box</option>
                                                                                <option value="3">Box/Pcs</option>
                                                                                <option value="4">Bun</option>
                                                                                <option value="5">Bundle</option>
                                                                                <option value="6">Can</option>
                                                                                <option value="7">Cartoon</option>
                                                                                <option value="8">Challan</option>
                                                                                <option value="9">Coil</option>
                                                                                <option value="10">Drum</option>
                                                                                <option value="11">Feet</option>
                                                                                <option value="12">Gallon</option>
                                                                                <option value="13">Item</option>
                                                                                <option value="14">Job</option>
                                                                                <option value="15">Kg</option>
                                                                                <option value="16">Kg/Bundle</option>
                                                                                <option value="17">Kv</option>
                                                                                <option value="18">Lbs</option>
                                                                                <option value="19">Ltr</option>
                                                                                <option value="20">Mtr</option>
                                                                                <option value="21">Pack</option>
                                                                                <option value="22">Pack/Pcs</option>
                                                                                <option value="23">Pair</option>
                                                                                <option value="24">Pcs</option>
                                                                                <option value="25">Pound</option>
                                                                                <option value="26">Qty</option>
                                                                                <option value="27">Roll</option>
                                                                                <option value="28">Set</option>
                                                                                <option value="29">Truck</option>
                                                                                <option value="30">Unit</option>
                                                                                <option value="31">Yeard</option>
                                                                                <option value="32">(Unit Unknown)</option>
                                                                                <option value="33">SFT</option>
                                                                                <option value="34">RFT</option>
                                                                                <option value="35">CFT</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="alert_qty">Alert Quantity <span style="color: #f0643b">*</span></label>
                                                                            <input type="number" class="form-control" value="0.000" min="0.000" name="alert_qty" id="alert_qty" step="0.001" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="opening_qty">Opening Quantity <span style="color: #f0643b">*</span></label>
                                                                            <input type="number" class="form-control" value="0.000" min="0.000" name="opening_qty" id="opening_qty" step="0.001" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="opening_value">Opening Value <span style="color: #f0643b">*</span></label>
                                                                            <input type="number" class="form-control" value="0.00" min="0.00" name="opening_value" id="opening_value" step="0.01" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="inv_type">Inventory Type <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="inv_type" id="inv_type" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Inventory</option>
                                                                                <option value="2">Non-Inventory</option>
                                                                                <option value="3">Repair & Maintenance</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="parts_image">Parts Image</label>
                                                                            <input type="file" class="form-control" name="parts_image" id="parts_image" accept="image/*">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="remarks">Remarks</label>
                                                                            <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div> <!-- end card-->
                                                    </div> <!-- end col -->
                                                </div> 
                                            </div>

                                            <div class="modal-footer justify-content-center">
                                                <div class="row justify-content-center">
                                                    <button type="submit" class="btn btn-xs btn-success waves-effect waves-light"><span class="btn-label"><i class="fas fa-plus"></i></span>Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <!-- End Modals For Add Parts -->

                            <!-- Start Modals For Update Parts -->
                            <div class="modal fade bs-example-modal-lg2" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Update Parts</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>

                                        <form action="javascript:void(0);" method="post" name="formUpdateParts" class="update-parts-form" enctype="multipart/form-data" data-parsley-validate>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card" style="margin-bottom: 0">
                                                            <div class="card-body">
                                                                <div class="alert alert-success update-parts-success d-none fade show">
                                                                    <h4 class="mt-0">Success</h4>
                                                                    <p class="mb-0">All the required fields are filled!</p>
                                                                </div>

                                                                <div class="alert alert-danger update-parts-danger d-none fade show">
                                                                    <h4 class="mt-0">Error</h4>
                                                                    <p class="mb-0">Please fill all the required fields!</p>
                                                                </div>

                                                                <input type="hidden" name="parts_id" id="parts_id">

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_parts_name">Parts Name <span style="color: #f0643b">*</span></label>
                                                                            <input type="text" class="form-control" name="upd_parts_name" id="upd_parts_name" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_parts_nickname">Parts Nick Name</label>
                                                                            <input type="text" class="form-control" name="upd_parts_nickname" id="upd_parts_nickname" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="upd_category">Category <span style="color: #f0643b">*</span></label> 
                                                                            <select data-placeholder="Choose" name="upd_category" id="upd_category" class="select-b">
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Spare</option>
                                                                                <option value="2">Consumable</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="upd_sub_category">Subcategory <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="upd_subcategory" id="upd_subcategory" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">MP</option>
                                                                                <option value="2">LC</option>
                                                                                <option value="3">Both</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="upd_subcategory_2">Subcategory 2 <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="upd_subcategory_2" id="upd_subcategory_2" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">New</option>
                                                                                <option value="2">Repair</option>
                                                                                <option value="3">Replacement</option>
                                                                                <option value="4">Refill</option>
                                                                                <option value="5">Forma</option>
                                                                                <option value="6">Service Charge</option>
                                                                                <option value="7">Transport</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="upd_type">Parts Type <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="upd_type" id="upd_type" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Asset</option>
                                                                                <option value="2">Non-Asset</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="upd_group">Group <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="upd_group" id="upd_group" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Mechanical</option>
                                                                                <option value="2">Electrical</option>
                                                                                <option value="3">Chemical</option>
                                                                                <option value="4">Machinery</option>
                                                                                <option value="5">IT</option>
                                                                                <option value="6">Rolls</option>
                                                                                <option value="7">General</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="upd_inv_type">Inventory Type <span style="color: #f0643b">*</span></label>  
                                                                            <select data-placeholder="Choose" name="upd_inv_type" id="upd_inv_type" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Inventory</option>
                                                                                <option value="2">Non-Inventory</option>
                                                                                <option value="3">Repair & Maintenance</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_unit">Unit <span style="color: #f0643b">*</span></label>
                                                                            <select data-placeholder="Choose" name="upd_unit" id="upd_unit" class="select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Bag</option>
                                                                                <option value="2">Box</option>
                                                                                <option value="3">Box/Pcs</option>
                                                                                <option value="4">Bun</option>
                                                                                <option value="5">Bundle</option>
                                                                                <option value="6">Can</option>
                                                                                <option value="7">Cartoon</option>
                                                                                <option value="8">Challan</option>
                                                                                <option value="9">Coil</option>
                                                                                <option value="10">Drum</option>
                                                                                <option value="11">Feet</option>
                                                                                <option value="12">Gallon</option>
                                                                                <option value="13">Item</option>
                                                                                <option value="14">Job</option>
                                                                                <option value="15">Kg</option>
                                                                                <option value="16">Kg/Bundle</option>
                                                                                <option value="17">Kv</option>
                                                                                <option value="18">Lbs</option>
                                                                                <option value="19">Ltr</option>
                                                                                <option value="20">Mtr</option>
                                                                                <option value="21">Pack</option>
                                                                                <option value="22">Pack/Pcs</option>
                                                                                <option value="23">Pair</option>
                                                                                <option value="24">Pcs</option>
                                                                                <option value="25">Pound</option>
                                                                                <option value="26">Qty</option>
                                                                                <option value="27">Roll</option>
                                                                                <option value="28">Set</option>
                                                                                <option value="29">Truck</option>
                                                                                <option value="30">Unit</option>
                                                                                <option value="31">Yeard</option>
                                                                                <option value="32">(Unit Unknown)</option>
                                                                                <option value="33">SFT</option>
                                                                                <option value="34">RFT</option>
                                                                                <option value="35">CFT</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_alert_qty">Alert Quantity <span style="color: #f0643b">*</span></label>
                                                                            <input type="number" class="form-control" value="0.000" min="0.000" name="upd_alert_qty" id="upd_alert_qty" step="0.001" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_parts_image">Parts New Image</label>
                                                                            <input type="file" class="form-control" name="upd_parts_image" id="upd_parts_image" accept="image/*">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_remarks">Remarks</label>
                                                                            <input type="text" class="form-control" name="upd_remarks" id="upd_remarks" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div> <!-- end card-->
                                                    </div> <!-- end col -->                  
                                                </div> 
                                            </div>

                                            <div class="modal-footer justify-content-center">
                                                <div class="row justify-content-center">
                                                    <button type="submit" class="btn btn-xs btn-success waves-effect waves-light"><span class="btn-label"><i class="fas fa-edit"></i></span>Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <!-- End Modals For Update Parts -->

                            <div class="button-list">
                                <button type="button" class="btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg"><span class="btn-label"><i class="mdi mdi-shape-square-plus" style=""></i></span>Add Parts</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table datatable w-100 nowrap cell-border text-center">
                                    <thead style="color: #fff; background-color: #5089de;">
                                        <tr>
                                            <th>SL.</th>
                                            <th>Parts Name</th>
                                            <th>Unit</th>
                                            <th>Category</th>
                                            <th>Subcategory</th>
                                            <th>Subcategory 2</th>
                                            <th>Parts Type</th>
                                            <th>Group</th>
                                            <th>Inventory Type</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot style="color: #fff; background-color: #5089de;">
                                        <tr>
                                            <th>SL.</th>
                                            <th>Parts Name</th>
                                            <th>Unit</th>
                                            <th>Category</th>
                                            <th>Subcategory</th>
                                            <th>Subcategory 2</th>
                                            <th>Parts Type</th>
                                            <th>Group</th>
                                            <th>Inventory Type</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
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

        <!-- Validation init js-->
        <script src="../../assets/js/pages/parts-form-validation.init.js"></script>

        <!-- Custom js -->
        <script type="text/javascript">
            $(document).ready(function(){
                // FETCH AND DISPLAY PARTS DATA
                let t;

                Swal.fire({
                    title: 'Fetching Purchase Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/parts',
                        method: 'post',
                        data: {
                            parts_data_type: 'fetch_all'
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
                                        { data: 'sl' },
                                        { data: 'parts_name' },
                                        { data: 'parts_unit_txt' },
                                        { data: 'parts_category_txt' },
                                        { data: 'parts_subcategory_txt' },
                                        { data: 'parts_subcategory_2_txt' },
                                        { data: 'parts_type_txt' },
                                        { data: 'parts_group_txt' },
                                        { data: 'parts_inv_type_txt' },
                                        { data: 'parts_image' },
                                        { data: 'action' }
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

            // UPDATE PARTS
            function update_parts(ele){
                let t;

                Swal.fire({
                    title: 'Fetching Parts Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/parts',
                        method: 'post',
                        data: {
                            parts_data_type: 'fetch',
                            parts_id: ele
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                $('#parts_id').val(data.Reply[0].parts_id);
                                $('#upd_parts_name').val(data.Reply[0].parts_name);
                                $('#upd_parts_nickname').val(data.Reply[0].parts_nickname);
                                $('#upd_category').val(data.Reply[0].parts_category);
                                $('#upd_subcategory').val(data.Reply[0].parts_subcategory);
                                $('#upd_subcategory_2').val(data.Reply[0].parts_subcategory_2);
                                $('#upd_type').val(data.Reply[0].parts_type);
                                $('#upd_group').val(data.Reply[0].parts_group);
                                $('#upd_inv_type').val(data.Reply[0].parts_inv_type);
                                $('#upd_unit').val(data.Reply[0].parts_unit);
                                $('#upd_alert_qty').val(data.Reply[0].parts_alert_qty);
                                $('#upd_remarks').val(data.Reply[0].parts_remarks);

                                $('.select-b').select2({
                                    width: '100%',
                                    placeholder: 'Choose'
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
            }
        </script>
    </body>
</html>