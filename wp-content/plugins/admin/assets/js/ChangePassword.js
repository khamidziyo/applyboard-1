// when user clicks on button to change password...
$("#change_password_form").submit(function (e) {

    e.preventDefault();
    var role;

    if (localStorage.getItem('data') != null) {
        var data = localStorage.getItem('data');
        var local_data = JSON.parse(data);
        role = local_data.role;
    }

    $("#reset").attr('disabled', true);

    var myform = document.getElementById('change_password_form');
    var form_data = new FormData(myform);
    form_data.append('role', role);
    // call to ajax to reset the password...
    $.ajax({
        url: admin_server_url + "ChangePassword.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType:false,
        processData:false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        // if success ajax response...
        success: function (response) {
            if (verifyToken(response)) {
                $("#reset").attr('disabled', false);

                sweetalert(response);
            } else {
                adminRedirectLogin();
            }
        },

        // if error ajax response...
        error: function (error) {
            $("#reset").attr('disabled', false);

            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);

        }
    })

})

