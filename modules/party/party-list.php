<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Party</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1, 3, 4];
            if(!in_array($user_category, $user_categories)){
                header('location: ../dashboard');
            }
        ?>

        <style type="text/css">
            .payment{
                width: 120px;
                display: inline-block;
                text-align: center;
                height: 30px;
                margin-right: 0;
            }
            .pay{
                height: 30px;
                display: none;
            }

            .datatable > tbody > tr > td, .datatable-2 > tbody > tr > td{
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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Party</a></li>
                                    <li class="breadcrumb-item active">Party List</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-account-multiple-outline"></i> Party List</h4>
                        </div>
                    </div>
                </div>     
                <!-- end page title -->
                
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <!-- Start Modals For Add Party -->
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Add Party</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        
                                        <form action="javascript:void(0);" method="post" name="formAddParty" class="add-party-form" enctype="multipart/form-data" data-parsley-validate>
                                            <div class="modal-body" style="overflow: scroll; height: 500px;">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card" style="margin-bottom: 0">
                                                            <div class="card-body">
                                                                <div class="alert alert-success add-party-success d-none fade show">
                                                                    <h4 class="mt-0">Success</h4>
                                                                    <p class="mb-0">All the required fields are filled!</p>
                                                                </div>

                                                                <div class="alert alert-danger add-party-danger d-none fade show">
                                                                    <h4 class="mt-0">Error</h4>
                                                                    <p class="mb-0">Please fill all the required fields!</p>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label for="party_name">Party Name <span style="color: #f0643b">*</span></label>
                                                                            <input type="text" class="form-control" name="party_name" id="party_name" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="mobile">Mobile</label>
                                                                            <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Insert" required>
                                                                        </div>
                                                                    </div> 

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="address">Address</label>
                                                                            <input type="text" class="form-control" name="address" placeholder="Insert" id="address">
                                                                        </div>
                                                                    </div>
                                                                </div> 

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="opening_ledger_balance">Opening Ledger Balance <span style="color: #f0643b">*</span></label><i class="fa fa-info-circle float-right mt-1" data-toggle="tooltip" data-placement="bottom" data-original-title="Insert positive value, if its Debit; Insert negative value, if its Credit."></i>
                                                                            <input type="number" class="form-control" name="opening_ledger_balance" id="opening_ledger_balance" placeholder="Insert" required>
                                                                        </div>
                                                                    </div> 

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="remarks">Remarks</label>
                                                                            <input type="textarea" class="form-control" name="remarks" id="remarks" placeholder="Insert">
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
                            <!-- End Modals For Add Party -->

                            <!-- Start Modals For Update Party -->
                            <div class="modal fade bs-example-modal-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Update Party</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>

                                        <form action="javascript:void(0);" method="post" name="formUpdateParty" class="update-party-form" enctype="multipart/form-data" data-parsley-validate>
                                            <div class="modal-body" style="overflow: scroll; height: 500px;">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card" style="margin-bottom: 0">
                                                            <div class="card-body">
                                                                <div class="alert alert-success update-party-success d-none fade show">
                                                                    <h4 class="mt-0">Success</h4>
                                                                    <p class="mb-0">All the required fields are filled!</p>
                                                                </div>

                                                                <div class="alert alert-danger update-party-danger d-none fade show">
                                                                    <h4 class="mt-0">Error</h4>
                                                                    <p class="mb-0">Please fill all the required fields!</p>
                                                                </div>

                                                                <input type="hidden" name="party_id" id="party_id">

                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label for="upd_party_name">Party Name <span style="color: #f0643b">*</span></label>
                                                                            <input type="text" class="form-control" name="upd_party_name" id="upd_party_name" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_mobile">Mobile</label>
                                                                            <input type="number" class="form-control" name="upd_mobile" id="upd_mobile" placeholder="Insert" required>
                                                                        </div>
                                                                    </div> 

                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_address">Address</label>
                                                                            <input type="text" class="form-control" name="upd_address" id="upd_address" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div> 

                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label for="upd_remarks">Remarks</label>
                                                                            <input type="textarea" class="form-control" name="upd_remarks" id="upd_remarks" placeholder="Insert">
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
                            <!-- End Modals For Update Party -->

                            <!-- Start Modals For Party Ledger -->
                            <div class="modal fade bs-example-modal-lg3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 1180px !important;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Party Ledger</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        
                                        <div class="modal-body" style="overflow: scroll; height: 500px;">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table class="table datatable-2 w-100 nowrap cell-border text-center">
                                                                <thead style="color: #fff; background-color: #5089de;">
                                                                    <tr>
                                                                        <th>SL.</th>
                                                                        <th>Ledger Description</th>
                                                                        <th>Debit Amount</th>
                                                                        <th>Credit Amount</th>
                                                                        <th>Total Balance</th>
                                                                    </tr>
                                                                </thead>
                                                                <tfoot style="color: #fff; background-color: #5089de;">
                                                                    <tr>
                                                                        <th>SL.</th>
                                                                        <th>Ledger Description</th>
                                                                        <th>Debit Amount</th>
                                                                        <th>Credit Amount</th>
                                                                        <th>Total Balance</th>
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

                            <div class="button-list">  
                                <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg"><span class="btn-label"><i class="mdi mdi-account-multiple-plus-outline" style=""></i></span>Add Party</button>
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
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Opening Balance</th>
                                            <th>Current Balance</th>
                                            <th>Remarks</th>
                                            <th>Payment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot style="color: #fff; background-color: #5089de;">
                                        <tr>
                                            <th>SL.</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Opening Balance</th>
                                            <th>Current Balance</th>
                                            <th>Remarks</th>
                                            <th>Payment</th>
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

        <?php require_once('../../footer-for-sub-page.php'); ?>

        <!-- Validation init js-->
        <script src="../../assets/js/pages/party-form-validation.init.js"></script>

        <!-- Custom js -->
        <script type="text/javascript">
            $(document).ready(function(){
                $('.pay').css('display', 'none');

                // FETCH AND DISPLAY PARTY DATA
                let t;

                Swal.fire({
                    title: 'Fetching Party Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/party',
                        method: 'post',
                        data: {
                            party_data_type: 'fetch_all'
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
                                        { data: 'party_name' },
                                        { data: 'party_mobile' },
                                        { data: 'party_address' },
                                        { data: 'opening_ledger_balance' },
                                        { data: 'current_balance' },
                                        { data: 'party_remarks' },
                                        { data: 'payment' },
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

            // PAYMENT
            function payment(ele){
                let id = ele;

                $('#pay' + id).css('display', 'inline-block');

                let payment = $('#payment' + id).val();
                let temp_payment = $('#temp_payment' + id).val();

                if(parseInt(payment) < 0){
                    $('#payment' + id).val(0);
                    return false;
                } else if(parseInt(payment) > parseInt(temp_payment)){
                    $('#payment' + id).val($('#temp_payment' + id).val());
                    return false;
                } else if(payment === '' || parseInt(payment) === 0){
                    $('.pay').css('display', 'none');
                }
            }

            // UPDATE PARTY MODAL
            function update_party(ele){
                let t;

                Swal.fire({
                    title: 'Fetching Party Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/party',
                        method: 'post',
                        data: {
                            party_data_type: 'fetch',
                            party_id: ele
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                $('#party_id').val(data.Reply[0].party_id);
                                $('#upd_party_name').val(data.Reply[0].party_name);
                                $('#upd_mobile').val(data.Reply[0].party_mobile);
                                $('#upd_address').val(data.Reply[0].party_address);
                                $('#upd_remarks').val(data.Reply[0].party_remarks);
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

            // PARTY LEDGER
            function party_ledger(ele){
                let t;

                Swal.fire({
                    title: 'Fetching Ledger Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/party',
                        method: 'post',
                        data: {
                            party_data_type: 'fetch_ledger',
                            party_id: ele
                        },
                        dataType: 'json',
                        cache: false,
                        async: false,
                        success: function(result){
                            if(result.Type == 'success'){
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
                                    data: result.Reply,
                                    columns: [
                                        { data: 'sl' },
                                        { data: 'description' },
                                        { data: 'debit' },
                                        { data: 'credit' },
                                        { data: 'total' }
                                    ]
                                });
                            } else if(data.Type == 'error'){
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