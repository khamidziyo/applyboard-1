$("#forgot_pwd_btn").click(function (e) {

    e.preventDefault();

    $("#load_img").show();
    $("#forgot_pwd_btn").hide();

    $response = [];

    var email = $("#email").val();
    $.ajax({
        url: student_server_url + "ForgotPassword.php",
        dataType: "json",
        data: { email: email },
        success: function (response) {
            $("#load_img").hide();
            $("#forgot_pwd_btn").show();
            sweetalert(response);
        },
        error: function (error) {
            $("#load_img").hide();
            $("#forgot_pwd_btn").show();
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response)
        }
    })
})