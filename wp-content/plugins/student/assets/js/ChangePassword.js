
var data = {};

$("#change_password_form").submit(function (e) {
    e.preventDefault();

    if (localStorage.getItem('data') != null) {
        data = JSON.parse(localStorage.getItem('data'));

        switch (data.role) {
            case 0:
                updateProfile(school_server_url);
                break;
            case '1':
                updateProfile(student_server_url);
                break;
        }
    }
});


function updateProfile(url) {
    $("#load_img").show();
    $("#reset").hide();

    $.ajax({
        url: url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: $("#change_password_form").serializeArray(),
        beforeSend: function (request) {
            if (!appendToken(request)) {
                redirectLogin();
            }
        },
        success: function (response) {
            $("#load_img").hide();
            $("#reset").show();

            if (verifyToken(response)) {
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
                    switch (window.data.role) {
                        case 0:
                            setTimeout(function () {
                                window.location.href = "http://localhost/wordpress/wordpress/index.php/school-profile/";
                            }, 1500);
                            break;
                        case '1':
                            setTimeout(function () {
                                window.location.href = "http://localhost/wordpress/wordpress/index.php/student-profile/";
                            }, 1500);
                            break;
                    }

                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        },
        error: function (error) {
            $("#load_img").hide();
            $("#reset").show();
            console.error(error);
            swal({
                title:"Internal Server Error",
                icon:'error'
            })
        }
    })

}


// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');

    switch (window.data.role) {
        case 0:

            setTimeout(function () {
                window.location.href = "http://localhost/wordpress/wordpress/index.php/school-login/";
            }, 1500);
            break;
        case '1':
            setTimeout(function () {
                window.location.href = "http://localhost/wordpress/wordpress/index.php/student-login/";
            }, 1500)

            break;
    }
}