
$(".loader").hide();

if (localStorage.getItem('data') != null) {
    swal({
        title: "You are already logged in.",
        icon: 'warning'
    })
    setTimeout(function () {
        window.location.href = base_url + "school-dashboard";
    }, 1000);
}

// function when script is loaded...
$(document).ready(function () {
    // when school admin logins with email and password...
    $("#school_login_form").submit(function (e) {
        e.preventDefault();
        var data = $("#school_login_form").serializeArray();

        $.ajax({
            url: school_server_url + 'SchoolLogin.php',
            type: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.status == 200) {
                    sweetalert(response);

                    localStorage.setItem('data', JSON.stringify(response.data));
                    setTimeout(function () {
                        window.location.href = base_url + "school-dashboard/";
                    }, 2000)

                }
                if (response.status == 401) {
                    swal({
                        title: response.message,
                        icon: 'warning',
                        showConfirmButton: true,
                    }).then(function () {

                        // calling send mail function if user not verified...
                        sendMail(response.data);
                    });
                }
                if (response.status == 400) {
                    errorSwal(response);
                }
            },
            error: function (error) {
                var response = { 'status': 400, 'message': 'Internal Server Error' };
                errorSwal(response);
            }
        })
    })


    // function to send mail if user not verified...
    function sendMail(data) {
        $("#login").hide();
        $("#loading_gif").show();
        // console.log(data);

        $.ajax({
            url: school_server_url + "SendVerificationMail.php",
            type: 'post',
            dataType: 'JSON',
            data: {
                val: "verification_mail",
                data: data
            },
            success: function (response) {
                $("#loading_gif").hide();
                $("#login").show();

                sweetalert(response);
            },
            error: function (error) {
                $("#loading_gif").hide();
                $("#login").show();
                var response = { 'status': 400, 'message': 'Internal Server Error' };
                errorSwal(response);
            }
        })
    }
})