// function when script is loaded...
$(document).ready(function() {
    // when school admin logins with email and password...
    $("#school_login_form").submit(function(e) {
        e.preventDefault();
        var data = $("#school_login_form").serializeArray();

        $.ajax({
            url: school_server_url + 'SchoolLogin.php',
            type: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: "success"
                    })
                    localStorage.setItem('data',JSON.stringify(response.data));
                    setTimeout(function() {
                        window.location.href = "http://localhost/wordpress/wordpress/index.php/school-dashboard/";
                    }, 2000)

                }
                if (response.status == 401) {
                    swal({
                        title: response.message,
                        icon: 'warning',
                        showConfirmButton: true,
                    }).then(function() {

                        // calling send mail function if user not verified...
                        sendMail(response.data);
                    });
                }
                if (response.status == 400) {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            },
            error: function(error) {
                swal({
                    title: error,
                    icon: 'error'
                })
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
            success: function(response) {
                $("#loading_gif").hide();
                $("#login").show();
                if (response.status == 200) {
                    swal({
                        icon: 'success',
                        title: response.message
                    })
                } else {
                    swal({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function(error) {
                $("#loading_gif").hide();
                $("#login").show();
                swal({
                    icon: 'error',
                    title: error
                })
            }
        })
    }
})