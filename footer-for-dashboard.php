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
<?php include('../rightbar-for-dashboard.php'); ?>
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="../assets/js/vendor.min.js"></script>

<!-- Third Party js-->
<script src="../assets/libs/jquery-vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../assets/libs/jquery-vectormap/jquery-jvectormap-us-merc-en.js"></script>
<script src="../assets/libs/peity/jquery.peity.min.js"></script>
<script src="../assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="../assets/libs/apexcharts/irregular-data-series.js"></script>
<script src="../assets/libs/apexcharts/ohlc.js"></script>

<!-- Dashboard init -->
<script src="../assets/js/pages/dashboard-1.init.js"></script>

<!-- Sweet Alerts js -->
<script src="../assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- Sweet alert init js-->
<script src="../assets/js/pages/sweet-alerts.init.js"></script>

<!-- App js-->
<script src="../assets/js/app.min.js"></script>
<script src="../assets/js/app.admin.js"></script>

<!-- init js -->
<script src="../assets/js/pages/apexcharts.init.js"></script>

<!-- third party js -->
<script src="../assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/libs/datatables/dataTables.bootstrap4.js"></script>
<script src="../assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="../assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script src="../assets/libs/datatables/dataTables.buttons.min.js"></script>
<script src="../assets/libs/datatables/buttons.bootstrap4.min.js"></script>
<script src="../assets/libs/datatables/buttons.html5.min.js"></script>
<script src="../assets/libs/datatables/buttons.flash.min.js"></script>
<script src="../assets/libs/datatables/buttons.print.min.js"></script>
<script src="../assets/libs/datatables/dataTables.keyTable.min.js"></script>
<script src="../assets/libs/datatables/dataTables.select.min.js"></script>
<script src="../assets/libs/pdfmake/vfs_fonts.js"></script>

<!-- Datatables init -->
<script src="../assets/js/pages/datatables.init.js"></script>

<!-- Custom js -->
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
                window.location.replace('../logout');
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

        // WELCOME LOADER
        let welcome_loader = localStorage.getItem('welcome_loader');
        let user_fullname = localStorage.getItem('user_fullname');

        if(welcome_loader == 1){
            Swal.fire({
                title: 'Login Successful',
                text: 'Welcome, ' + user_fullname + '!',
                type: 'success',
                width: 450,
                padding: 50,
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 3000
            });

            localStorage.removeItem('welcome_loader');
            localStorage.removeItem('user_fullname');
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
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if(result.value){
                    window.location.href = '../logout';
                }
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

        // AMOUNT FORMATTER
        let amount = 5000.25;
        let locale = 'en';
        let options = {style: 'currency', currency: 'bdt', minimumFractionDigits: 2, maximumFractionDigits: 2};
        let formatter = new Intl.NumberFormat(locale, options);

        // VIEW CONSUMPTION SUMMARY REPORT
        table_header = '<th class="align-middle text-center">Dept.</th>\
                        <th class="align-middle text-center">Chemical</th>\
                        <th class="align-middle text-center">Mechanical</th>\
                        <th class="align-middle text-center">Electrical</th>\
                        <th class="align-middle text-center">General</th>\
                        <th class="align-middle text-center">Machinery</th>\
                        <th class="align-middle text-center">Total</th>';

        $('.table').css('width', '100%');

        let table = '';
        
        table = $('.custom-datatable-for-summary').DataTable();
        
        table.destroy();

        $.ajax({
            url: '../api/inventory',
            method: 'post',
            data: {
                inventory_data_type: 'fetch_issued_parts_summary_details'
            },
            dataType: 'json',
            cache: false,
            async: false,
            success: function(data){
                if(data.Type == 'success'){
                    $('.records-h').empty().append('<tr>' + table_header + '</tr>');
        
                    $('.records').empty();

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
                    
                    $('.records').append(table_data);

                    let table_footer = '';
                                    
                    table_footer += '<th class="align-middle text-center">Total</th>';
                    table_footer += '<th class="align-middle text-center">' + grand_tot_chemical.toFixed(2) + '</th>';
                    table_footer += '<th class="align-middle text-center">' + grand_tot_mechanical.toFixed(2) + '</th>';
                    table_footer += '<th class="align-middle text-center">' + grand_tot_electrical.toFixed(2) + '</th>';
                    table_footer += '<th class="align-middle text-center">' + grand_tot_general.toFixed(2) + '</th>';
                    table_footer += '<th class="align-middle text-center">' + grand_tot_machinery.toFixed(2) + '</th>';
                    table_footer += '<th class="align-middle text-center">' + grand_tot.toFixed(2) + '</th>';

                    $('.records-f').empty().append('<tr>' + table_footer + '</tr>');

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

        // VIEW REQUISITION DATA
        $.ajax({
            url: '../api/requisition',
            method: 'post',
            data: {
                requisition_data_type: 'fetch_requisition_num'
            },
            dataType: 'json',
            cache: false,
            async: false,
            success: function(data){
                if(data.Type == 'success'){
                    $('.tot-pending').html(data.Reply[0].tot_pending);
                    $('.tot-approved').html(data.Reply[0].tot_approved);
                    $('.tot-rejected').html(data.Reply[0].tot_rejected);
                }

                return false;
            }
        });

        // VIEW RECEIVE / ISSUE QTY. & VALUE
        $.ajax({
            url: '../api/inventory',
            method: 'post',
            data: {
                inventory_data_type: 'inventory_parts_tot_rcv_iss_qty_val'
            },
            dataType: 'json',
            cache: false,
            async: false,
            success: function(data){
                if(data.Type == 'success'){
                    $('.tot-received-qty').html(parseFloat(data.Reply[0].tot_received_qty).toFixed(3));
                    $('.tot-issued-qty').html(parseFloat(data.Reply[0].tot_issued_qty).toFixed(3));
                    $('.tot-received-val').html('<i class="mdi mdi-currency-bdt"></i>' + parseFloat(data.Reply[0].tot_received_val).toFixed(2));
                    $('.tot-issued-val').html('<i class="mdi mdi-currency-bdt"></i>' + parseFloat(data.Reply[0].tot_issued_val).toFixed(2));
                }

                return false;
            }
        });

        // VIEW TOTAL PURCHASE AGAINST REQUISITION
        $.ajax({
            url: '../api/purchase',
            method: 'post',
            data: {
                purchase_data_type: 'fetch_tot_purchase_against_requisition'
            },
            dataType: 'json',
            cache: false,
            async: false,
            success: function(data){
                if(data.Type == 'success'){
                    $('.pur').html(data.Reply[0].tot_purchase + '/' + data.Reply[0].tot_requisition);
                    $('.pur-2').html(data.Reply[0].tot_purchase2 + '/' + data.Reply[0].tot_requisition2);
                    $('.pur-3').html(data.Reply[0].tot_purchase3 + '/' + data.Reply[0].tot_requisition3);

                    let pur_per = (data.Reply[0].tot_requisition == 0) ? 0 : (data.Reply[0].tot_purchase / data.Reply[0].tot_requisition) * 100,
                        pur_per2 = (data.Reply[0].tot_requisition2 == 0) ? 0 : (data.Reply[0].tot_purchase2 / data.Reply[0].tot_requisition2) * 100,
                        pur_per3 = (data.Reply[0].tot_requisition3 == 0) ? 0 : (data.Reply[0].tot_purchase3 / data.Reply[0].tot_requisition3) * 100;

                    $('.pur-per').html(pur_per.toFixed(0) + '%');
                    $('.pur-per-2').html(pur_per2.toFixed(0) + '%');
                    $('.pur-per-3').html(pur_per3.toFixed(0) + '%');

                    $('.pr-style').css('width', pur_per.toFixed(0) + '%');
                    $('.pr-style-2').css('width', pur_per2.toFixed(0) + '%');
                    $('.pr-style-3').css('width', pur_per3.toFixed(0) + '%');

                    $('.pr-style').attr('aria-valuenow', pur_per.toFixed(0));
                    $('.pr-style-2').attr('aria-valuenow', pur_per2.toFixed(0));
                    $('.pr-style-3').attr('aria-valuenow', pur_per3.toFixed(0));
                }

                return false;
            }
        });

        // VIEW TOTAL RECEIVE AGAINST PURCHASE
        $.ajax({
            url: '../api/inventory',
            method: 'post',
            data: {
                inventory_data_type: 'fetch_tot_receive_against_purchase'
            },
            dataType: 'json',
            cache: false,
            async: false,
            success: function(data){
                if(data.Type == 'success'){
                    $('.rec').html(data.Reply[0].tot_receive + '/' + data.Reply[0].tot_purchase);
                    $('.rec-2').html(data.Reply[0].tot_receive2 + '/' + data.Reply[0].tot_purchase2);
                    $('.rec-3').html(data.Reply[0].tot_receive3 + '/' + data.Reply[0].tot_purchase3);

                    let rec_per = (data.Reply[0].tot_purchase == 0) ? 0 : (data.Reply[0].tot_receive / data.Reply[0].tot_purchase) * 100,
                        rec_per2 = (data.Reply[0].tot_purchase2 == 0) ? 0 : (data.Reply[0].tot_receive2 / data.Reply[0].tot_purchase2) * 100,
                        rec_per3 = (data.Reply[0].tot_purchase3 == 0) ? 0 : (data.Reply[0].tot_receive3 / data.Reply[0].tot_purchase3) * 100;

                    $('.rec-per').html(rec_per.toFixed(0) + '%');
                    $('.rec-per-2').html(rec_per2.toFixed(0) + '%');
                    $('.rec-per-3').html(rec_per3.toFixed(0) + '%');

                    $('.rcv-style').css('width', rec_per.toFixed(0) + '%');
                    $('.rcv-style-2').css('width', rec_per2.toFixed(0) + '%');
                    $('.rcv-style-3').css('width', rec_per3.toFixed(0) + '%');

                    $('.rcv-style').attr('aria-valuenow', rec_per.toFixed(0));
                    $('.rcv-style-2').attr('aria-valuenow', rec_per2.toFixed(0));
                    $('.rcv-style-3').attr('aria-valuenow', rec_per3.toFixed(0));
                }

                return false;
            }
        });
    });
        
    function showTime(){
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        let date = new Date(new Date().toLocaleString('en-US', {timeZone: 'Asia/Dhaka'}));
        
        let d = date.getDate();
        let mn = monthNames[date.getMonth()];
        let y = date.getFullYear();
        let h = date.getHours();
        let m = date.getMinutes();
        let s = date.getSeconds();
        let session = 'AM';

        d = (d < 10) ? d = '0' + d : d;
      
        if(h == 0)
            h = 12;

        if(h >= 12)
            session = 'PM';
      
        if(h > 12)
            h = h - 12;

        m = (m < 10) ? m = '0' + m : m;
        s = (s < 10) ? s = '0' + s : s;

        let time = mn + ' ' + d + ', ' + h + ':' + m + ':' + s + ' ' + session;

        $('#clock').html(time);

        setTimeout(showTime, 1000);
    }

    showTime();
</script>