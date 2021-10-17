<!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <?= date('Y'); ?> &copy; Copyright by <a href="www.rrmsteel.com.bd" target="_blank">RRM Steel</a>
            </div>
            <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-sm-block">
                    <a href="javascript:void(0);" class="contact">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->

<!-- Right Sidebar -->
<?php include('../../rightbar-for-sub-page.php'); ?>
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="../../assets/js/vendor.min.js"></script>

<!-- third party js -->
<script src="../../assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/libs/datatables/dataTables.bootstrap4.js"></script>
<script src="../../assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="../../assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script src="../../assets/libs/datatables/dataTables.buttons.min.js"></script>
<script src="../../assets/libs/datatables/buttons.bootstrap4.min.js"></script>
<script src="../../assets/libs/datatables/buttons.html5.min.js"></script>
<script src="../../assets/libs/datatables/buttons.flash.min.js"></script>
<script src="../../assets/libs/datatables/buttons.print.min.js"></script>
<script src="../../assets/libs/datatables/dataTables.keyTable.min.js"></script>
<script src="../../assets/libs/datatables/dataTables.select.min.js"></script>
<script src="../../assets/libs/datatables/dataTables.rowsGroup.js"></script>
<script src="../../assets/libs/pdfmake/pdfmake.min.js"></script>
<script src="../../assets/libs/pdfmake/vfs_fonts.js"></script>
<script src="../../assets/libs/peity/jquery.peity.min.js"></script>
<script src="../../assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="../../assets/libs/jquery-vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../assets/libs/jquery-vectormap/jquery-jvectormap-us-merc-en.js"></script>

<!-- Datatables init -->
<script src="../../assets/js/pages/datatables.init.js"></script>

<!-- Responsive Table js -->
<script src="../../assets/libs/rwd-table/rwd-table.min.js"></script>
<script src="../../assets/js/pages/responsive-table.init.js"></script>

<!-- Plugins js-->
<script src="../../assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script src="../../assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="../../assets/libs/parsleyjs/parsley.min.js"></script>

<!-- Init js-->
<script src="../../assets/js/pages/form-wizard.init.js"></script>

<!-- App js-->
<script src="../../assets/js/app.min.js"></script>
<script src="../../assets/js/app.admin.js"></script>

<!-- select2 Dropdown with choose option-->
<script src="../../assets/js/select2.min.js"></script>
<script src="../../assets/js/select2-bootstrap4.js"></script>

<!-- Plugins js for datepicker-->
<script src="../../assets/libs/moment/moment.min.js"></script>
<script src="../../assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script src="../../assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
<script src="../../assets/libs/daterangepicker/daterangepicker.js"></script>

<!-- datepicker Init js-->
<script src="../../assets/js/pages/form-pickers.init.js"></script>

<!-- Plugin underscore -->
<script src="../../assets/libs/underscore/underscore-min.js"></script>

<!-- Sweet Alerts js -->
<script src="../../assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="../../assets/js/pages/sweet-alerts.init.js"></script>

<!-- Custom Script -->
<script type="text/javascript">
    $(document).ready(function(){
        // SESSION TIME OUT
        setTimeout(function(){
            Swal.fire({
                title: 'Warning',
                text: 'Session will expire in 5 minutes due to inactivity!',
                type: 'warning',
                width: 450,
                showCloseButton: true,
                confirmButtonColor: '#5cb85c',
                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
            });
        }, 3300000);

        setTimeout(function(){
            Swal.fire({
                title: 'Warning',
                text: 'Session expired due to inactivity!',
                type: 'warning',
                width: 450,
                showCloseButton: false,
                allowOutsideClick: false,
                confirmButtonColor: '#5cb85c',
                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
            }).then(function(){
                window.location.replace('../../logout');
            });
        }, 3600000);

        // CONTACT
        $('.contact').on('click', function(){
            Swal.fire({
                title: 'Contact Details',
                html: 'Saud Bin A. Mannan<br>Software Engineer<br>RRM Group<hr>Phone: +880 1708120129<hr>',
                type: 'info',
                showCloseButton: true,
                confirmButtonColor: '#5cb85c',
                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
            });
        });

        // PERMISSION
        let user_categories = new Array(1, 2, 3, 4);
        let user_category = '<?= $user_category ?>';

        if($.inArray(parseInt(user_category), user_categories) !== -1){
            // console.log('found');
        }

        if(user_category == 2){
            $('.user-link, .parts-link, .party-link, .purchase-link, .loan-link, .inventory-link, .report-link').css({'pointer-events' : 'none', 'opacity': 0.3});
        } else if(user_category == 3){
            $('.user-link, .party-link').css({'pointer-events' : 'none', 'opacity': 0.3});
        } else if(user_category == 4){
            $('.user-link, .parts-link, .loan-link, .inventory-link, .report-link').css({'pointer-events' : 'none', 'opacity': 0.3});
        }

        // LOGOUT
        $('#logout').on('click', function(){
            Swal.fire({
                title: 'Logout',
                text: 'Are you sure you want to logout?',
                type: 'question',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check"></i>&nbsp;&nbsp; Yes',
                cancelButtonText: '<i class="fas fa-times"></i>&nbsp;&nbsp; No'
            }).then((result) => {
                if(result.value){
                    window.location.href = '../../logout';
                }
            });
        });
    });
</script>