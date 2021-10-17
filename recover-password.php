<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>RRM Inventory by RRM Gorup | Recover Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A Fully Featured Requisition, Purchase and Inventory Management Software by RRM Steel" name="description" />
        <meta content="RRM Steel" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/rrm_steel_favicon.png">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="authentication-bg authentication-bg-pattern">
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center w-75 m-auto">
                                    <span><img src="assets/images/rrm/rrmsteel-logo.png" alt="" height="50"></span>
                                    <p class="text-muted mb-4 mt-3">Enter your email.</p>
                                </div>

                                <h5 class="auth-title">Recover Password</h5>

                                <form action="javascript:void(0);" method="post" name="formRecoverPassword" class="recover-pass-form" enctype="multipart/form-data" data-parsley-validate>
                                    <div class="alert alert-success recover-pass-success d-none fade show">
                                        <h4 class="mt-0">Success</h4>
                                        <p class="mb-0">All the required fields are filled!</p>
                                    </div>

                                    <div class="alert alert-danger recover-pass-danger d-none fade show">
                                        <h4 class="mt-0">Error</h4>
                                        <p class="mb-0">Please fill all the required fields!</p>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" id="email" placeholder="Enter your email" required>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button type="submit" class="btn btn-xs btn-success btn-block waves-effect waves-light"><span class="btn-label float-left"><i class="fas fa-upload"></i></span>Submit</button>
                                    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">Back to <a href="../purchase" class="text-muted ml-1"><b class="font-weight-semibold">Login</b></a></p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            <?= date('Y'); ?> &copy; Copyright by <a href="www.rrmsteel.com.bd" target="_blank">RRM Steel</a>
        </footer>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Plugin js-->
        <script src="assets/libs/parsleyjs/parsley.min.js"></script>

        <!-- Validation init js-->
        <script src="assets/js/pages/recover-pass-form-validation.init.js"></script>

        <!-- Sweet Alerts js -->
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <!-- Sweet alert init js-->
        <script src="assets/js/pages/sweet-alerts.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        <script src="assets/js/app.admin.js"></script>
    </body>
</html>