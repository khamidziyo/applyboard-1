$(".loader").hide();



// when user clicks on button to change password...
$("#reset_password_form").submit(function (e) {

    e.preventDefault();

    $("#reset_password_btn").attr('disabled', true);


    var myform = document.getElementById('reset_password_form');
    var form_data = new FormData(myform);

    // call to ajax to reset the password...
    $.ajax({
        url: student_server_url + "ResetPassword.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,

        // if success ajax response...
        success: function (response) {
            $("#reset_password_btn").attr('disabled', false);

            sweetalert(response);

            if (response.status == 200) {

                setTimeout(function () {
                    redirectAfterUpdatePassword();
                }, 1500)
            }
        },

        // if error ajax response...
        error: function (error) {
            $("#reset_password_btn").attr('disabled', false);

            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })

})

function redirectAfterUpdatePassword() {

    var param_obj = new URLSearchParams(window.location.search);

    if (param_obj.has('case')) {
        var user_type = param_obj.get('case');
        switch (user_type) {

            case 'student':
                window.location.href = base_url + "student-login";
                break;

            case 'admin':
                window.location.href = base_url + "admin-login";
                break;

            case 'agent':
                window.location.href = base_url + "agent-login";
                break;

            case 'subagent':
                window.location.href = base_url + "sub-agent-login";
                break;

            case 'staff':
                window.location.href = base_url + "staff-login";
                break;
            default:
                var response = { status: 400, message: 'No case matches.Try again' };
                errorSwal(response);
                break;
        }
    }
}