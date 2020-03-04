$("#forgot_password_form").submit(function (e) {

    e.preventDefault();
    $("#forgot_pwd_btn").attr('disabled', true);


    var form = document.getElementById('forgot_password_form');
    var form_data = new FormData(form);

    $.ajax({
        url: student_server_url + "ForgotPassword.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,

        success: function (response) {
            $("#forgot_pwd_btn").attr('disabled', false);
            sweetalert(response);
        },
        error: function (error) {
            $("#forgot_pwd_btn").attr('disabled', false);

            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response)
        }
    })

})