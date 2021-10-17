<?php 
    require_once('session.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RRM | Home</title>

        <?php 
            require_once('../dashboard-header.php');
        ?>
    </head>

    <body>
        <!-- Navigation Bar-->
        <header id="topnav">
            <!-- Topbar Start -->
            <?php include('../topbar-for-dashboard.php'); ?>
            <!-- end Topbar -->

            <?php include('../navbar-for-dashboard.php'); ?>
            <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->

        <div class="wrapper full-width-background">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card cta-box bg-secondary text-white mt-3">
                            <div class="card-body pt-1 pb-1">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <div class="avatar-md bg-soft-light rounded-circle spinner-grow text-center mr-2" style="float: left;">
                                            <i class="mdi mdi-bullhorn font-22 avatar-title text-danger"></i>
                                        </div>

                                        <h3 class="mt-2 mr-0 font-weight-normal text-white cta-box-title"><strong>Notice</strong>: Please complete receive & issue parts for the month of April, 2021.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card cta-box bg-secondary text-white mt-3">
                            <div class="card-body pt-1 pb-1">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <div class="avatar-md bg-soft-light rounded-circle text-center mr-2" style="float: left;">
                                            <i class="mdi mdi-calendar-clock font-22 avatar-title text-primary"></i>
                                        </div>

                                        <h4 class="mr-0 font-weight-normal text-white clock" style="margin-top: 1.075rem !important;" id="clock"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-6">
                        <!-- Portlet card -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-0">REQUISITION</h4>

                                <div id="cardCollpase1" class="collapse pt-3 show">
                                    <div class="bg-soft-light border-light border">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <p class="text-muted mb-0 mt-3">PENDING THIS MONTH</p>

                                                <h2 class="font-weight-normal mb-3">
                                                    <small class="mdi mdi-checkbox-blank-circle text-warning align-middle mr-1"></small>
                                                    <span class="tot-pending"></span>
                                                </h2>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-muted mb-0 mt-3">APPROVED THIS MONTH</p>

                                                <h2 class="font-weight-normal mb-3">
                                                    <small class="mdi mdi-checkbox-blank-circle text-success align-middle mr-1"></small>
                                                    <span class="tot-approved"></span>
                                                </h2>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-muted mb-0 mt-3">REJECTED THIS MONTH</p>

                                                <h2 class="font-weight-normal mb-3">
                                                    <small class="mdi mdi-checkbox-blank-circle text-danger align-middle mr-1"></small>
                                                    <span class="tot-rejected"></span>
                                                </h2>
                                            </div>
                                        </div>

                                        <hr class="mt-0 mb-0">

                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="mt-2 mb-2">
                                                    <p class="text-muted mb-0 mt-3">THIS MONTH</p>

                                                    <div id="apex-pie-1" class="apex-charts"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mt-2 mb-2">
                                                    <p class="text-muted mb-0 mt-3"><?= date('F', strtotime('-1 month')) . ', ' . date('Y'); ?></p>

                                                    <div id="apex-pie-11" class="apex-charts"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mt-2 mb-2">
                                                    <p class="text-muted mb-0 mt-3"><?= date('F', strtotime('-2 months')) . ', ' . date('Y'); ?></p>

                                                    <div id="apex-pie-111" class="apex-charts"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- collapsed end -->
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-xl-3">
                        <div class="card text-white bg-success text-xs-center">
                            <div class="card-body pt-1 pb-1">
                                <blockquote class="card-bodyquote mt-0 mb-0">
                                    <h2 class="text-light text-center"><?= date('F, Y') ?></h2>
                                    <h5 class="text-light text-center">Received Qty.: <span class="tot-received-qty" style="font-size: 1.5rem;"></span></h5>
                                    <h5 class="text-light text-center">Issued Qty.: <span class="tot-issued-qty" style="font-size: 1.5rem;"></span></h5>
                                </blockquote>
                            </div>
                        </div> <!-- end card-box-->

                        <div class="card text-white text-xs-center">
                            <div class="card-body">
                                <h4 class="header-title">Received & Issued Qty</h4>

                                <div class="mt-2 mb-2">
                                    <div id="apex-line-1" class="apex-charts"></div>
                                </div>
                            </div>
                        </div> <!-- end card-box-->
                    </div>

                    <div class="col-xl-3">
                        <div class="card text-white bg-info text-xs-center">
                            <div class="card-body pt-1 pb-1">
                                <blockquote class="card-bodyquote mt-0 mb-0">
                                    <h2 class="text-light text-center"><?= date('F, Y') ?></h2>
                                    <h5 class="text-light text-center">Received Value: <span class="tot-received-val" style="font-size: 1.5rem;"></span></h5>
                                    <h5 class="text-light text-center">Issued Value: <span class="tot-issued-val" style="font-size: 1.5rem;"></span></h5>
                                </blockquote>
                            </div>
                        </div> <!-- end card-box-->

                        <div class="card text-white text-xs-center">
                            <div class="card-body">
                                <h4 class="header-title">Received & Issued Value</h4>

                                <div class="mt-2 mb-2">
                                    <div id="apex-line-11" class="apex-charts"></div>
                                </div>
                            </div>
                        </div> <!-- end card-box-->
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-3 mt-0">Purchase & Loan Against Requisition</h4>

                                <small class="text-muted">This Month:</small>
                                
                                <h4 class="d-inline ml-1">
                                    <span class="badge badge-light-dark pur"></span>
                                </h4>

                                <div class="progress-w-percent mb-0">
                                    <span class="progress-value font-weight-bold pur-per"></span>

                                    <div class="progress progress-sm">
                                        <div class="progress-bar pr-style" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <small class="text-muted"><?= date('F', strtotime('-1 month')) . ', ' . date('Y'); ?>:</small>

                                <h4 class="d-inline ml-1">
                                    <span class="badge badge-light-dark pur-2"></span>
                                </h4>

                                <div class="progress-w-percent mb-0">
                                    <span class="progress-value font-weight-bold pur-per-2"></span>

                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success pr-style-2" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <small class="text-muted"><?= date('F', strtotime('-2 month')) . ', ' . date('Y'); ?>:</small>

                                <h4 class="d-inline ml-1">
                                    <span class="badge badge-light-dark pur-3"></span>
                                </h4>

                                <div class="progress-w-percent mb-0">
                                    <span class="progress-value font-weight-bold pur-per-3"></span>

                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-danger pr-style-3" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-3 mt-0">Receive Against Purchase & Loan</h4>

                                <small class="text-muted">This Month:</small>
                                
                                <h4 class="d-inline ml-1">
                                    <span class="badge badge-light-dark rec"></span>
                                </h4>

                                <div class="progress-w-percent mb-0">
                                    <span class="progress-value font-weight-bold rec-per"></span>

                                    <div class="progress progress-sm">
                                        <div class="progress-bar rcv-style" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <small class="text-muted"><?= date('F', strtotime('-1 month')) . ', ' . date('Y'); ?>:</small>

                                <h4 class="d-inline ml-1">
                                    <span class="badge badge-light-dark rec-2"></span>
                                </h4>

                                <div class="progress-w-percent mb-0">
                                    <span class="progress-value font-weight-bold rec-per-2"></span>

                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success rcv-style-2" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <small class="text-muted"><?= date('F', strtotime('-2 month')) . ', ' . date('Y'); ?>:</small>

                                <h4 class="d-inline ml-1">
                                    <span class="badge badge-light-dark rec-3"></span>
                                </h4>

                                <div class="progress-w-percent mb-0">
                                    <span class="progress-value font-weight-bold rec-per-3"></span>

                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-danger rcv-style-3" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body" style="overflow-x: auto; overflow-y: auto;">
                                <h4 class="header-title mb-0">CONSUMPTION SUMMARY DATA - <?= date('F, Y'); ?></h4><br>

                                <table class="table w-100 custom-datatable-for-summary nowrap cell-border">
                                    <thead class="records-h" style="color: #fff; background-color: #5089de;">
                                        <tr>
                                            <th class="align-middle text-center">Dept.</th>
                                            <th class="align-middle text-center">Chemical</th>
                                            <th class="align-middle text-center">Mechanical</th>
                                            <th class="align-middle text-center">Electrical</th>
                                            <th class="align-middle text-center">General</th>
                                            <th class="align-middle text-center">Machinery</th>
                                            <th class="align-middle text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="records">
                                    </tbody>
                                    <tfoot class="records-f" style="color: #fff; background-color: #5089de;">
                                        <tr>
                                            <th class="align-middle text-center">Dept.</th>
                                            <th class="align-middle text-center">Chemical</th>
                                            <th class="align-middle text-center">Mechanical</th>
                                            <th class="align-middle text-center">Electrical</th>
                                            <th class="align-middle text-center">General</th>
                                            <th class="align-middle text-center">Machinery</th>
                                            <th class="align-middle text-center">Total</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">CONSUMPTION SUMMARY CHART - <?= date('F, Y'); ?></h4>

                                <div class="mt-2 mb-2">
                                    <div id="apex-column-1" class="apex-charts"></div>
                                </div> <!-- collapsed end -->
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-xl-6">
                        <!-- Portlet card -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-0">Recent Activity</h4>

                                <div class="pt-3">
                                    <div class="slimscroll" style="max-height: 370px;">
                                        <div class="timeline-alt">
                                            <?php 
                                                $audit_loog_query = mysqli_query($conn, "SELECT * FROM rrmsteel_audit_log a INNER JOIN rrmsteel_user u ON u.user_id = a.user_id ORDER BY a.audit_id DESC LIMIT 50");

                                                while($row = mysqli_fetch_assoc($audit_loog_query)){
                                            ?>
                                                    <div class="timeline-item" style="display: flow-root;">
                                                        <i class="timeline-icon"></i>
                                                        <div class="timeline-item-info">
                                                            <span class="text-body font-weight-semibold mb-1 d-block"><?= $row['module']; ?></span>
                                                            <small><?= $row['action'] . ' by ' . $row['user_fullname']; ?></small>
                                                            <p class="d-inline pull-right pr-2">
                                                                <small class="text-muted"><?= date('d M, Y \a\t h:i A', $row['action_time']); ?></small>
                                                            </p>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                        <!-- end timeline -->
                                    </div> <!-- end slimscroll -->
                                </div> <!-- collapsed end -->
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div>
                <!-- end row -->
            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->

        <!-- Footer Start -->
        <?php include('../footer-for-dashboard.php'); ?>
        <!-- end Footer -->

        <script type="text/javascript">
            $(document).ready(function(){
                $('#basic-datatable2').DataTable({
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
        </script>
    </body>
</html>