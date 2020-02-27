if (localStorage.getItem('data') != null) {
    swal({
        title: "You are already logged in.",
        icon: 'warning'
    })
    setTimeout(function () {
        window.location.href = base_url + "admin-dashboard";
    }, 1000);
}


    // when admin logins...
    $("#admin_login_form").submit(function (e) {
        e.preventDefault();

        // sending admin login data on server...
        $.ajax({
            url: admin_server_url + "AdminLogin.php",
            type: "post",
            data: $("#admin_login_form").serializeArray(),
            dataType: "json",
            success: function (response) {
                sweetalert(response);

                if (response.status == 200) {

                    // storing user data in local storage...
                    localStorage.setItem('data', JSON.stringify(response.data));

                    // loading the admin dashboard page...
                    setTimeout(function () {
                        window.location.href = base_url + "admin-dashboard/";
                    }, 2000);
                }

            },
            error: function (error) {
                var response = { status: 400, 'message': 'Internal Server Error' };
                errorSwal(response);
            }
        })
    })
