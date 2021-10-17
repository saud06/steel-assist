$(document).ready(function(){
    // LOGIN
    $('form[name=formLogin]').on('submit', function(e){
        e.preventDefault();

        if($('form[name=formLogin] input[name=email]').val() !== '' && $('form[name=formLogin] input[name=password]').val() !== ''){
            $.ajax({
                url: 'api/auth',
                method: 'post',
                data: {
                    auth_type: 'login',
                    email: $('form[name=formLogin] input[name=email]').val(),
                    password: $('form[name=formLogin] input[name=password]').val()
                },
                dataType: 'json',
                cache: false,
                beforeSend: function(){
                    $('form[name=formLogin] button[type=submit]').attr('disabled', 'true').html('Loading...');
                },
                success: function(data){
                    if(data.Type == 'success'){
                        localStorage.setItem('welcome_loader', 1);
                        localStorage.setItem('user_fullname', data.Reply);

                        window.location.href = 'modules/dashboard.php';
                    } else if(data.Type == 'error'){
                        Swal.fire({
                            title: 'Error',
                            text: data.Reply,
                            type: 'error',
                            width: 450,
                            showCloseButton: true,
                            confirmButtonColor: '#5cb85c',
                            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                            footer: 'Please insert valid email & password.'
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
                complete: function(){
                    $('form[name=formLogin] button[type=submit]').removeAttr('disabled').html('Login to the System');
                }
            });
        }
    });

    // RECOVER PASSWORD
    $('form[name=formRecoverPassword]').on('submit', function(e){
        e.preventDefault();

        if ($('form[name=formRecoverPassword] input[name=email]').val() !== ''){
            $.ajax({
                url: 'api/auth',
                method: 'post',
                data: {
                    auth_type: 'forgot_password',
                    email: $('form[name=formRecoverPassword] input[name=email]').val()
                },
                dataType: 'json',
                cache: false,
                beforeSend: function(){
                    $('form[name=formRecoverPassword] button[type=submit]').attr('disabled', 'true').html('Loading...');
                },
                success: function(data){
                    if(data.Type == 'success'){
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
                                window.location.href = './';
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
                            footer: 'Please insert valid email'
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
                complete: function(){
                    $('form[name=formRecoverPassword] button[type=submit]').removeAttr('disabled').html('Submit');
                }
            });
        }
    });

    // RECOVER RESET
    $('form[name=formResetPassword]').on('submit', function(e){
        e.preventDefault();

        let password = $('form[name=formResetPassword] input[name=password]').val(),
            confirm_password = $('form[name=formResetPassword] input[name=confirm_password]').val();

        if(password !== '' && confirm_password !== ''){
            if(password === confirm_password){
                $.ajax({
                    url: 'api/auth',
                    method: 'post',
                    data: {
                        auth_type: 'reset_password',
                        password: password,
                        confirm_password: confirm_password,
                        uid: $('form[name=formResetPassword] input[name=uid]').val(),
                        token: $('form[name=formResetPassword] input[name=token]').val()
                    },
                    dataType: 'json',
                    cache: false,
                    beforeSend: function(){
                        $('form[name=formResetPassword] button[type=submit]').attr('disabled', 'true').html('Loading...');
                    },
                    success: function(data){
                        if(data.Type == 'success'){
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
                                    window.location.href = './';
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
                                footer: 'Please insert valid data'
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
                    complete: function(){
                        $('form[name=formResetPassword] button[type=submit]').removeAttr('disabled').html('Submit');
                    }
                });
            } else{
                Swal.fire({
                    title: 'Error',
                    text: 'Password does not match!',
                    type: 'error',
                    width: 450,
                    showCloseButton: true,
                    confirmButtonColor: '#5cb85c',
                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                    footer: 'Please match password & confirm password.'
                });
            }
        }
    });

    // ADD USER
    $('form[name=formAddUser]').on('submit', function(e){
        e.preventDefault();

        if($('form[name=formAddUser] select[name=category]').val() !== '' && $('form[name=formAddUser] input[name=fullname]').val() !== '' && $('form[name=formAddUser] input[name=email]').val() !== '' && $('form[name=formAddUser] input[name=password]').val() !== ''){
            $.ajax({
                url: '../../api/interactionController',
                method: 'post',
                data: {
                    interact_type: 'add',
                    interact: 'user',
                    category: $('form[name=formAddUser] select[name=category]').val(),
                    fullname: $('form[name=formAddUser] input[name=fullname]').val(),
                    designation: $('form[name=formAddUser] input[name=designation]').val(),
                    department: $('form[name=formAddUser] input[name=department]').val(),
                    mobile: $('form[name=formAddUser] input[name=mobile]').val(),
                    email: $('form[name=formAddUser] input[name=email]').val(),
                    password: $('form[name=formAddUser] input[name=password]').val()
                },
                dataType: 'json',
                cache: false,
                beforeSend: function(){
                    $('form[name=formAddUser] button[type=submit]').attr('disabled', 'true');
                    $('form[name=formAddUser] button[type=submit]').html('Loading...');
                },
                success: function(data){
                    if(data.Type == 'success'){
                        let t;

                        Swal.fire({
                            title: 'Adding User',
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
                                    window.location.reload(true);
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
                complete: function(){
                    $('form[name=formAddUser] button[type=submit]').removeAttr('disabled');
                    $('form[name=formAddUser] button[type=submit]').html('Submit');
                }
            });
        }
    });

    // UPDATE USER
    $('form[name=formUpdateUser]').on('submit', function(e){
        e.preventDefault();

        if((($('form[name=formUpdateUser] input[name=user_id]').val() > 1 && $('form[name=formUpdateUser] select[name=upd_category]').val() !== '') || ($('form[name=formUpdateUser] input[name=user_id]').val() == 1 && $('form[name=formUpdateUser] select[name=upd_category]').val() === '')) && $('form[name=formUpdateUser] input[name=upd_fullname]').val() !== '' && $('form[name=formUpdateUser] input[name=upd_email]').val() !== ''){
            $.ajax({
                url: '../../api/interactionController',
                method: 'post',
                data: {
                    interact_type: 'update',
                    interact: 'user',
                    id: $('form[name=formUpdateUser] input[name=user_id]').val(),
                    category: $('form[name=formUpdateUser] select[name=upd_category]').val(),
                    fullname: $('form[name=formUpdateUser] input[name=upd_fullname]').val(),
                    designation: $('form[name=formUpdateUser] input[name=upd_designation]').val(),
                    department: $('form[name=formUpdateUser] input[name=upd_department]').val(),
                    mobile: $('form[name=formUpdateUser] input[name=upd_mobile]').val(),
                    email: $('form[name=formUpdateUser] input[name=upd_email]').val(),
                    password: $('form[name=formUpdateUser] input[name=upd_password]').val()
                },
                dataType: 'json',
                cache: false,
                beforeSend: function(){
                    $('form[name=formUpdateUser] button[type=submit]').attr('disabled', 'true');
                    $('form[name=formUpdateUser] button[type=submit]').html('Loading...');
                },
                success: function(data){
                    if(data.Type == 'success'){
                        let t;

                        Swal.fire({
                            title: 'Updating User',
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
                                    window.location.reload(true);
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
                complete: function(){
                    $('form[name=formUpdateUser] button[type=submit]').removeAttr('disabled');
                    $('form[name=formUpdateUser] button[type=submit]').html('Submit');
                }
            });
        }
    });

    // ADD PARTS
    $('form[name=formAddParts]').on('submit', function(e){
        e.preventDefault();

        if($('form[name=formAddParts] input[name=parts_name]').val() !== '' && $('form[name=formAddParts] select[name=category]').val() !== '' && $('form[name=formAddParts] select[name=subcategory]').val() !== '' && $('form[name=formAddParts] select[name=subcategory_2]').val() !== '' && $('form[name=formAddParts] select[name=type]').val() !== '' && $('form[name=formAddParts] select[name=group]').val() !== '' && $('form[name=formAddParts] select[name=inv_type]').val() !== '' && $('form[name=formAddParts] select[name=unit]').val() !== '' && $('form[name=formAddParts] input[name=alert_qty]').val() !== '' && $('form[name=formAddParts] input[name=opening_qty]').val() !== '' && $('form[name=formAddParts] input[name=opening_value]').val() !== ''){
            let img_file_data = '',
                flag = 0,
                msg = '';

            if($('form[name=formAddParts] input[name=parts_image]').val() !== ''){
                let file_data = $('form[name=formAddParts] input[name=parts_image]').prop('files')[0];
                img_file_data = file_data.name;

                let form_data = new FormData();
                form_data.append('file', file_data);

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
                        if(data.Type === 'success'){
                            flag = 1;
                            msg = ' In addition to that, ' + data.Reply;
                        } else{
                            flag = 0;
                            msg = data.Reply;
                        }
                    }
                });
            } else{
                flag = 1;
                msg = '';
            }

            if(flag == 0){
                Swal.fire({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    width: 450,
                    showCloseButton: true,
                    confirmButtonColor: '#5cb85c',
                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                    footer: 'Please insert valid data.'
                });
            } else{
                $.ajax({
                    url: '../../api/interactionController',
                    method: 'post',
                    data: {
                        interact_type: 'add',
                        interact: 'parts',
                        parts_name: $('form[name=formAddParts] input[name=parts_name]').val(),
                        parts_nickname: $('form[name=formAddParts] input[name=parts_nickname]').val(),
                        category: $('form[name=formAddParts] select[name=category]').val(),
                        subcategory: $('form[name=formAddParts] select[name=subcategory]').val(),
                        subcategory_2: $('form[name=formAddParts] select[name=subcategory_2]').val(),
                        type: $('form[name=formAddParts] select[name=type]').val(),
                        group: $('form[name=formAddParts] select[name=group]').val(),
                        inv_type: $('form[name=formAddParts] select[name=inv_type]').val(),
                        unit: $('form[name=formAddParts] select[name=unit]').val(),
                        alert_qty: $('form[name=formAddParts] input[name=alert_qty]').val(),
                        opening_qty: $('form[name=formAddParts] input[name=opening_qty]').val(),
                        opening_value: $('form[name=formAddParts] input[name=opening_value]').val(),
                        parts_image: img_file_data,
                        remarks: $('form[name=formAddParts] input[name=remarks]').val()
                    },
                    dataType: 'json',
                    cache: false,
                    beforeSend: function(){
                        $('form[name=formAddParts] button[type=submit]').attr('disabled', 'true');
                        $('form[name=formAddParts] button[type=submit]').html('Loading...');
                    },
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Adding Parts',
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
                                    text: data.Reply + msg,
                                    type: 'success',
                                    width: 450,
                                    showCloseButton: false,
                                    allowOutsideClick: false,
                                    confirmButtonColor: '#5cb85c',
                                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                }).then((result) => {
                                    if(result.value){
                                        window.location.reload(true);
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
                    complete: function(){
                        $('form[name=formAddParts] button[type=submit]').removeAttr('disabled');
                        $('form[name=formAddParts] button[type=submit]').html('Submit');
                    }
                });
            }
        }
    });

    // UPDATE PARTS
    $('form[name=formUpdateParts]').on('submit', function(e){
        e.preventDefault();

        if($('form[name=formUpdateParts] input[name=upd_parts_name]').val() !== '' && $('form[name=formUpdateParts] select[name=upd_category]').val() !== '' && $('form[name=formUpdateParts] select[name=upd_subcategory]').val() && $('form[name=formUpdateParts] select[name=upd_subcategory_2]').val() !== '' && $('form[name=formUpdateParts] select[name=upd_type]').val() !== '' && $('form[name=formUpdateParts] select[name=upd_group]').val() !== '' && $('form[name=formUpdateParts] select[name=upd_inv_type]').val() !== '' && $('form[name=formUpdateParts] select[name=upd_unit]').val() !== '' && $('form[name=formUpdateParts] input[name=upd_alert_qty]').val() !== ''){
            let img_file_data = '',
                flag = 0,
                msg = '';

            if($('form[name=formUpdateParts] input[name=upd_parts_image]').val() !== ''){
                let file_data = $('form[name=formUpdateParts] input[name=upd_parts_image]').prop('files')[0];
                img_file_data = file_data.name;

                let form_data = new FormData();
                form_data.append('file', file_data);

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
                        if(data.Type === 'success'){
                            flag = 1;
                            msg = ' In addition to that, ' + data.Reply;
                        } else{
                            flag = 0;
                            msg = data.Reply;
                        }
                    }
                });
            } else{
                flag = 1;
                msg = '';
            }

            if(flag == 0){
                Swal.fire({
                    title: 'Error',
                    text: msg,
                    type: 'error',
                    width: 450,
                    showCloseButton: true,
                    confirmButtonColor: '#5cb85c',
                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
                    footer: 'Please insert valid data.'
                });
            } else{
                $.ajax({
                    url: '../../api/interactionController',
                    method: 'post',
                    data: {
                        interact_type: 'update',
                        interact: 'parts',
                        id: $('form[name=formUpdateParts] input[name=parts_id]').val(),
                        parts_name: $('form[name=formUpdateParts] input[name=upd_parts_name]').val(),
                        parts_nickname: $('form[name=formUpdateParts] input[name=upd_parts_nickname]').val(),
                        category: $('form[name=formUpdateParts] select[name=upd_category]').val(),
                        subcategory: $('form[name=formUpdateParts] select[name=upd_subcategory]').val(),
                        subcategory_2: $('form[name=formUpdateParts] select[name=upd_subcategory_2]').val(),
                        type: $('form[name=formUpdateParts] select[name=upd_type]').val(),
                        group: $('form[name=formUpdateParts] select[name=upd_group]').val(),
                        inv_type: $('form[name=formUpdateParts] select[name=upd_inv_type]').val(),
                        unit: $('form[name=formUpdateParts] select[name=upd_unit]').val(),
                        alert_qty: $('form[name=formUpdateParts] input[name=upd_alert_qty]').val(),
                        parts_image: img_file_data,
                        remarks: $('form[name=formUpdateParts] input[name=upd_remarks]').val()
                    },
                    dataType: 'json',
                    cache: false,
                    beforeSend: function(){
                        $('form[name=formUpdateParts] button[type=submit]').attr('disabled', 'true');
                        $('form[name=formUpdateParts] button[type=submit]').html('Loading...');
                    },
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Updating Parts',
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
                                    text: data.Reply + msg,
                                    type: 'success',
                                    width: 450,
                                    showCloseButton: false,
                                    allowOutsideClick: false,
                                    confirmButtonColor: '#5cb85c',
                                    confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                                }).then((result) => {
                                    if(result.value){
                                        window.location.reload(true);
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
                    complete: function(){
                        $('form[name=formUpdateParts] button[type=submit]').removeAttr('disabled');
                        $('form[name=formUpdateParts] button[type=submit]').html('Submit');
                    }
                });
            }
        }
    });

    // ADD PARTY
    $('form[name=formAddParty]').on('submit', function(e){
        e.preventDefault();

        if($('form[name=formAddParty] input[name=party_name]').val() !== '' && $('form[name=formAddParty] input[name=opening_ledger_balance]').val() !== ''){
            $.ajax({
                url: '../../api/interactionController',
                method: 'post',
                data: {
                    interact_type: 'add',
                    interact: 'party',
                    party_name: $('form[name=formAddParty] input[name=party_name]').val(),
                    mobile: $('form[name=formAddParty] input[name=mobile]').val(),
                    address: $('form[name=formAddParty] input[name=address]').val(),
                    opening_ledger_balance: $('form[name=formAddParty] input[name=opening_ledger_balance]').val(),
                    remarks: $('form[name=formAddParty] input[name=remarks]').val()
                },
                dataType: 'json',
                cache: false,
                beforeSend: function(){
                    $('form[name=formAddParty] button[type=submit]').attr('disabled', 'true');
                    $('form[name=formAddParty] button[type=submit]').html('Loading...');
                },
                success: function(data){
                    if(data.Type == 'success'){
                        let t;

                        Swal.fire({
                            title: 'Adding Party',
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
                                    window.location.reload(true);
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
                complete: function(){
                    $('form[name=formAddParty] button[type=submit]').removeAttr('disabled');
                    $('form[name=formAddParty] button[type=submit]').html('Submit');
                }
            });
        }
    });

    // UPDATE PARTY
    $('form[name=formUpdateParty]').on('submit', function(e){
        e.preventDefault();

        if($('form[name=formUpdateParty] input[name=upd_party_name]').val() !== ''){
            $.ajax({
                url: '../../api/interactionController',
                method: 'post',
                data: {
                    interact_type: 'update',
                    interact: 'party',
                    id: $('form[name=formUpdateParty] input[name=party_id]').val(),
                    party_name: $('form[name=formUpdateParty] input[name=upd_party_name]').val(),
                    mobile: $('form[name=formUpdateParty] input[name=upd_mobile]').val(),
                    address: $('form[name=formUpdateParty] input[name=upd_address]').val(),
                    remarks: $('form[name=formUpdateParty] input[name=upd_remarks]').val()
                },
                dataType: 'json',
                cache: false,
                beforeSend: function(){
                    $('form[name=formUpdateParty] button[type=submit]').attr('disabled', 'true');
                    $('form[name=formUpdateParty] button[type=submit]').html('Loading...');
                },
                success: function(data){
                    if(data.Type == 'success'){
                        let t;

                        Swal.fire({
                            title: 'Updating Party',
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
                                    window.location.reload(true);
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
                complete: function(){
                    $('form[name=formUpdateParty] button[type=submit]').removeAttr('disabled');
                    $('form[name=formUpdateParty] button[type=submit]').html('Submit');
                }
            });
        }
    });
});

// DELETE USER
function delete_user(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'The user will be deleted permanently!',
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
                        interact: 'user',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Deleting User',
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
                                        window.location.reload(true);
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
}

// DELETE PARTS
function delete_parts(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'The parts will be deleted permanently!',
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
                        interact: 'parts',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Deleting Parts',
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
                                        window.location.reload(true);
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
}

// DELETE PARTY
function delete_party(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'The party will be deleted permanently!',
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
                        interact: 'party',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Deleting Party',
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
                                        window.location.reload(true);
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
}

// PAYMENT PARTY
function pay(ele){
    let id = ele;
    let payment = $('#payment' + id).val();

    if(payment !== ''){
        Swal.fire({
            title: 'Are you sure?',
            text: 'Payment for this party will be updated!',
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
                        interact_type: 'add',
                        interact: 'payment',
                        id: id,
                        payment: payment,
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Processing party payment',
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
                                        window.location.reload(true);
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
                        } else if(data.Type == 'invalid'){
                            Swal.fire({
                                title: 'Info',
                                text: data.Reply,
                                type: 'info',
                                width: 450,
                                showCloseButton: true,
                                confirmButtonColor: '#5cb85c',
                                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                            }).then((result) => {
                                if(result.value){
                                    window.location.reload(true);
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
        });
    }
}

// APPROVE CONSUMABLE REQUISITION
function approve_requisition(ele, ele2){
    let id = ele,
        user_category = ele2,
        title = '',
        text = '';

    if(user_category == 4){
        title = 'Are you sure you want to accept?';
        text = 'The requisition will be accepted & can no longer be updated!';
    } else{
        title = 'Are you sure you want to approve?';
        text = 'The requisition will be approved & can no longer be updated!';
    }

    if(id !== ''){
        Swal.fire({
            title: title,
            text: text,
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
                        interact_type: 'update',
                        interact: 'requisition_approval',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            if(user_category == 4){
                                title = 'Accepting Requisition';
                            } else{
                                title = 'Approving Requisition';
                            }

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
                                        window.location.reload(true);
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
}

// REJECT CONSUMABLE REQUISITION
function reject_requisition(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to reject?',
            text: 'The requisition will be rejected & can no longer be updated!',
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
                        interact_type: 'update',
                        interact: 'requisition_rejection',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Rejecting Requisition',
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
                                        window.location.reload(true);
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
}

// DELETE CONSUMABLE REQUISITION
function delete_requisition(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'The requisition will be deleted permanently!',
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
                        interact: 'requisition',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Deleting Requisition',
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
                                        window.location.reload(true);
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
}

// APPROVE SPARE REQUISITION
function approve_requisition2(ele, ele2){
    let id = ele,
        user_category = ele2,
        title = '',
        text = '';

    if(user_category == 4){
        title = 'Are you sure you want to accept?';
        text = 'The requisition will be accepted & can no longer be updated!';
    } else{
        title = 'Are you sure you want to approve?';
        text = 'The requisition will be approved & can no longer be updated!';
    }

    if(id !== ''){
        Swal.fire({
            title: title,
            text: text,
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
                        interact_type: 'update',
                        interact: 'requisition_approval2',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Approving Requisition',
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
                                        window.location.reload(true);
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
}

// REJECT SPARE REQUISITION
function reject_requisition2(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to reject?',
            text: 'The requisition will be rejected & can no longer be updated!',
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
                        interact_type: 'update',
                        interact: 'requisition_rejection2',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Rejecting Requisition',
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
                                        window.location.reload(true);
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
}

// DELETE SPARE REQUISITION
function delete_requisition2(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'The requisition will be deleted permanently!',
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
                        interact: 'requisition2',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Deleting Requisition',
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
                                        window.location.reload(true);
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
}

// MARK CONSUMABLE PURCHASE
function mark_purchase(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will be marked as purchased & can no longer be updated!',
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
                        interact_type: 'update',
                        interact: 'mark_purchase',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Marking as Purchased',
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
                                        window.location.reload(true);
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
}

// MARK SPARE PURCHASE
function mark_purchase2(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will be marked as purchased & can no longer be updated!',
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
                        interact_type: 'update',
                        interact: 'mark_purchase2',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Marking as Purchased',
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
                                        window.location.reload(true);
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
}

// MARK CONSUMABLE LOAN
function mark_loan(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will be marked as borrowed & can no longer be updated!',
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
                        interact_type: 'update',
                        interact: 'mark_loan',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Marking as Borrowed',
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
                                        window.location.reload(true);
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
}

// MARK SPARE LOAN
function mark_loan2(ele){
    let id = ele;

    if(id !== ''){
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will be marked as borrowed & can no longer be updated!',
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
                        interact_type: 'update',
                        interact: 'mark_loan2',
                        id: id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Marking as Borrowed',
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
                                        window.location.reload(true);
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
}

// CONSUMABLE LOAN REPAY
function loan_repay(ele, ele2, ele3, ele4, ele5, ele6, ele7, ele8, ele9){
    let repay_qty = $(ele3).closest('tr').find('td:eq(5)').find('input').val(),
        repay_date = $(ele3).closest('tr').find('td:eq(4)').find('input').val();

    if(repay_qty && repay_date){
        Swal.fire({
            title: 'Are you sure?',
            text: 'Loan will be repaid for this parts! Inserted quantity will be issued for this parts as well!',
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
                        interact_type: 'add',
                        interact: 'loan_repay_con',
                        parts_id: ele,
                        req_for: ele2,
                        party_id: ele4,
                        req_qty: ele6,
                        borrow_qty: ele7,
                        borrow_date: ele5,
                        repay_qty: repay_qty,
                        repay_date: repay_date,
                        loan_id: ele8,
                        loan_data_id: ele9
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Adding Loan Repay Data',
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
                                        window.location.reload(true);
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
    } else{
        Swal.fire({
            title: 'Error',
            text: 'Empty required field(s)!',
            type: 'error',
            width: 450,
            showCloseButton: true,
            confirmButtonColor: '#5cb85c',
            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
            footer: 'Please insert repay quantity & date.'
        });
    }
}

// SPARE LOAN REPAY
function loan_repay2(ele, ele2, ele3, ele4, ele5, ele6, ele7, ele8, ele9){
    let repay_qty = $(ele3).closest('tr').find('td:eq(5)').find('input').val(),
        repay_date = $(ele3).closest('tr').find('td:eq(4)').find('input').val();

    if(repay_qty && repay_date){
        Swal.fire({
            title: 'Are you sure?',
            text: 'Loan will be repaid for this parts! Inserted quantity will be issued for this parts as well!',
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
                        interact_type: 'add',
                        interact: 'loan_repay_spr',
                        parts_id: ele,
                        req_for: ele2,
                        party_id: ele4,
                        req_qty: ele6,
                        borrow_qty: ele7,
                        borrow_date: ele5,
                        repay_qty: repay_qty,
                        repay_date: repay_date,
                        loan_id: ele8,
                        loan_data_id: ele9
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: 'Adding Loan Repay Data',
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
                                        window.location.reload(true);
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
    } else{
        Swal.fire({
            title: 'Error',
            text: 'Empty required field(s)!',
            type: 'error',
            width: 450,
            showCloseButton: true,
            confirmButtonColor: '#5cb85c',
            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
            footer: 'Please insert repay quantity & date.'
        });
    }
}

// UPDATE INVENTORY
function update_inventory(ele, ele2){
    let parts_id = ele2,
        action_type = $(ele).closest('tr').find('td').find('.action-type').val(),
        action_date = $(ele).closest('tr').find('td').find('.action-date').val(),
        req_for = ((action_type == 1) ? 0 : parseInt($(ele).closest('tr').find('td').find('.req-for').val())),
        qty = $(ele).closest('tr').find('td').find('.qty').val(),
        price = $(ele).closest('tr').find('td').find('.price').val();

    if((action_type == 1 && action_date && qty && price) || (action_type == 2 && req_for && action_date && qty)){
        Swal.fire({
            title: 'Are you sure?',
            text: ((action_type == 1) ? 'Inserted quantity will be received for this parts!' : 'Inserted quantity will be issued for this parts!'),
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
                        interact_type: 'update',
                        interact: ((action_type == 1) ? 'receive_parts_qty' : 'issue_parts_qty'),
                        parts_id: parts_id,
                        required_for: req_for,
                        action_date: action_date,
                        qty: qty,
                        price: price
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data){
                        if(data.Type == 'success'){
                            let t;

                            Swal.fire({
                                title: ((action_type == 1) ? 'Receiving parts quantity' : 'Issuing parts quantity'),
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
                                        window.location.reload(true);
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
                        } else if(data.Type == 'invalid'){
                            Swal.fire({
                                title: 'Info',
                                text: data.Reply,
                                type: 'info',
                                width: 450,
                                showCloseButton: true,
                                confirmButtonColor: '#5cb85c',
                                confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!'
                            }).then((result) => {
                                if(result.value){
                                    window.location.reload(true);
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
        });
    } else{
        Swal.fire({
            title: 'Error',
            text: 'Empty Field Data!',
            type: 'error',
            width: 450,
            showCloseButton: true,
            confirmButtonColor: '#5cb85c',
            confirmButtonText: '<i class="fas fa-thumbs-up"></i>&nbsp;&nbsp; Okay!',
            footer: 'Please insert all the field data.'
        });
    }
}