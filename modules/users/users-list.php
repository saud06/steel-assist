<?php 
    require_once('../session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | User</title>

        <?php 
            require_once('../../sub-page-header.php');

            // PERMISSION
            $user_categories = [1];
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
                                    <li class="breadcrumb-item active">User List</li>
                                </ol>
                            </div>
                            <h4 class="page-title"><i class="mdi mdi-account-outline"></i> User List</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <!-- Start Modals For Add User -->
                            <div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Add User</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>

                                        <form action="javascript:void(0);" method="post" name="formAddUser" class="add-user-form" enctype="multipart/form-data" data-parsley-validate>
                                            <div class="modal-body" style="overflow: scroll; height: 500px !important;">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card" style="margin-bottom: 0">
                                                            <div class="card-body">
                                                                <div id="progressbarwizard">
                                                                    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-3">
                                                                        <li class="nav-item">
                                                                            <a href="#user_tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 user-tab">
                                                                                <i class="mdi mdi-account-circle mr-1"></i>
                                                                                <span class="d-none d-sm-inline">User Information</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a href="#login_tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 login-tab">
                                                                                <i class="mdi mdi-face-profile mr-1"></i>
                                                                                <span class="d-none d-sm-inline">Login Information</span>
                                                                            </a>
                                                                        </li>                  
                                                                    </ul>
                                                                
                                                                    <div class="tab-content b-0 mb-0 pt-0">
                                                                        <div id="bar" class="progress mb-3" style="height: 7px;">
                                                                            <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
                                                                        </div>

                                                                        <div class="alert alert-success add-user-success d-none fade show">
                                                                            <h4 class="mt-0">Success</h4>
                                                                            <p class="mb-0">All the required fields are filled!</p>
                                                                        </div>

                                                                        <div class="alert alert-danger add-user-danger d-none fade show">
                                                                            <h4 class="mt-0">Error</h4>
                                                                            <p class="mb-0">Please fill all the required fields!</p>
                                                                        </div>
                                                                
                                                                        <div class="tab-pane" id="user_tab">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label for="category">Category <span style="color: #f0643b">*</span></label>
                                                                                        <select data-placeholder="Choose" name="category" id="category" class="form-control select-b" required>
                                                                                            <option value="">Choose</option>
                                                                                            <option value="1">Admin</option>
                                                                                            <option value="2">Requisite Person</option>
                                                                                            <option value="3">Store In-charge</option>
                                                                                            <option value="4">Purchase In-charge</option>
                                                                                            <option value="5">Electricity In-charge</option>
                                                                                        </select>         
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label for="fullname">Full Name <span style="color: #f0643b">*</span></label>
                                                                                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Insert" required>
                                                                                    </div>
                                                                                </div>                                
                                                                            </div> <!-- end row -->

                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label for="designation">Designation</label>
                                                                                        <input type="text" class="form-control" name="designation" id="designation" placeholder="Insert">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label for="department">Department</label>
                                                                                        <input type="text" class="form-control" name="department" id="department" placeholder="Insert">
                                                                                    </div>
                                                                                </div>                                
                                                                            </div> <!-- end row -->

                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label for="mobile">Mobile</label>
                                                                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Insert">
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- end row -->
                                                                        </div>

                                                                        <div class="tab-pane" id="login_tab">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="form-group">
                                                                                        <label for="email">Email <span style="color: #f0643b">*</span></label>
                                                                                        <input type="text" class="form-control" name="email" id="email" placeholder="Insert" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="form-group">
                                                                                        <label for="password">Password <span style="color: #f0643b">*</span></label>
                                                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Insert" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- end row -->
                                                                        </div>

                                                                        <ul class="list-inline mb-0 wizard">
                                                                            <li class="previous list-inline-item">
                                                                                <a href="javascript: void(0);" class="btn btn-xs btn-secondary">Previous</a>
                                                                            </li>
                                                                            <li class="next list-inline-item float-right">
                                                                                <a href="javascript: void(0);" class="btn btn-xs btn-secondary">Next</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div> <!-- tab-content -->                                                                    
                                                                </div>
                                                            </div> <!-- end card-body -->
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
                            <!-- End Modals For Add User -->

                            <!-- Start Modals For Update User -->
                            <div class="modal fade bs-example-modal-lg2" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Update User</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        
                                        <form action="javascript:void(0);" method="post" name="formUpdateUser" class="update-user-form" enctype="multipart/form-data" data-parsley-validate>
                                            <div class="modal-body" style="overflow: scroll; height: 500px;">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card" style="margin-bottom: 0">
                                                            <div class="card-body">
                                                                <div class="alert alert-success update-user-success d-none fade show">
                                                                    <h4 class="mt-0">Success</h4>
                                                                    <p class="mb-0">All the required fields are filled!</p>
                                                                </div>

                                                                <div class="alert alert-danger update-user-danger d-none fade show">
                                                                    <h4 class="mt-0">Error</h4>
                                                                    <p class="mb-0">Please fill all the required fields!</p>
                                                                </div>

                                                                <input type="hidden" name="user_id" id="user_id">

                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_category">Category <span id="category_span" style="color: #f0643b">*</span></label>
                                                                            <select name="upd_category" id="upd_category" class="form-control select-b" required>
                                                                                <option value="">Choose</option>
                                                                                <option value="1">Admin</option>
                                                                                <option value="2">Requisite Person</option>
                                                                                <option value="3">Store In-charge</option>
                                                                                <option value="4">Purchase In-charge</option>
                                                                                <option value="5">Electricity In-charge</option>
                                                                            </select>         
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_fullname">Full Name <span style="color: #f0643b">*</span></label>
                                                                            <input type="text" class="form-control" name="upd_fullname" id="upd_fullname" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>                                
                                                                </div> <!-- end row -->

                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_designation">Designation</label>
                                                                            <input type="text" class="form-control" name="upd_designation" id="upd_designation" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_department">Department</label>
                                                                            <input type="text" class="form-control" name="upd_department" id="upd_department" placeholder="Insert">
                                                                        </div>
                                                                    </div>                                
                                                                </div> <!-- end row -->

                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_mobile">Mobile</label>
                                                                            <input type="text" class="form-control" name="upd_mobile" id="upd_mobile" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- end row -->

                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_email">Email <span style="color: #f0643b">*</span></label>
                                                                            <input type="text" class="form-control" name="upd_email" id="upd_email" placeholder="Insert" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="upd_password">Password</label>
                                                                            <input type="password" class="form-control" name="upd_password" id="upd_password" placeholder="Insert">
                                                                        </div>
                                                                    </div>
                                                                </div><!-- end row -->
                                                            </div> <!-- end card-body -->
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
                            <!-- End Modals For Update User -->

                            <div class="button-list">
                                <!-- Large modal -->
                                <button type="button" class="btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg"><span class="btn-label"><i class="mdi mdi-account-plus-outline" style=""></i></span>Add User</button>
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
                                            <th class="text-center">SL.</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot style="color: #fff; background-color: #5089de;">
                                        <tr>
                                            <th class="text-center">SL.</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
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
        <script src="../../assets/js/pages/user-form-validation.init.js"></script>

        <!-- Custom js -->
        <script type="text/javascript">
            $(document).ready(function(){
                // ADD USER MODAL
                $('form[name=formAddUser] .modal-footer, form[name=formAddUser] .previous').hide();

                $('.user-tab, .previous').on('click', function(){
                    $('.next').show();
                    $('.previous, .modal-footer').hide();
                });

                $('.login-tab, .next').on('click', function(){
                    $('.next').hide();
                    $('.previous, .modal-footer').show();
                });

                // FETCH AND DISPLAY USER DATA
                let t;

                Swal.fire({
                    title: 'Fetching User Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/user',
                        method: 'post',
                        data: {
                            user_data_type: 'fetch_all'
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
                                        { data: 'user_fullname' },
                                        { data: 'user_category' },
                                        { data: 'user_designation' },
                                        { data: 'user_department' },
                                        { data: 'user_email' },
                                        { data: 'user_mobile' },
                                        { data: 'user_status' },
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

            // UPDATE USER MODAL
            function update_user(ele){
                let t;

                Swal.fire({
                    title: 'Fetching User Data',
                    text: 'Please wait...',
                    timer: 100,
                    allowOutsideClick: false,
                    onBeforeOpen: function(){
                        Swal.showLoading(), t = setInterval(function(){
                        }, 100);
                    }
                }).then(function(){
                    $.ajax({
                        url: '../../api/user',
                        method: 'post',
                        data: {
                            user_data_type: 'fetch',
                            user_id: ele
                        },
                        dataType: 'json',
                        cache: false,
                        success: function(data){
                            if(data.Type == 'success'){
                                $('#user_id').val(data.Reply[0].user_id);
                                $('#upd_category').val(data.Reply[0].user_category).attr('required', true).css({'pointer-events': 'auto', 'touch-action': 'auto'});
                                $('#category_span').html('*');
                                $('#upd_designation').attr('readonly', false).css('pointer-events', 'auto');
                                $('#upd_department').attr('readonly', false).css('pointer-events', 'auto');
                                $('#upd_mobile').attr('readonly', false).css('pointer-events', 'auto');
                                $('#upd_fullname').val(data.Reply[0].user_fullname);
                                $('#upd_designation').val(data.Reply[0].user_designation);
                                $('#upd_department').val(data.Reply[0].user_department);
                                $('#upd_mobile').val(data.Reply[0].user_mobile);
                                $('#upd_email').val(data.Reply[0].user_email);
                                $('#upd_password').val('');

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