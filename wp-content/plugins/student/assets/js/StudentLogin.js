$(document).ready(function () {

    if (localStorage.getItem('data') != null) {
        swal({
            title: "You are already logged in.",
            icon: 'warning'
        })
        setTimeout(function () {
            window.location.href = base_url + "student-dashboard";
        }, 1000);
    }

    // signing in with facebook..
    $('#facebook-button').on('click', function () {
        // Initialize with your OAuth.io app public key
        OAuth.initialize(Facebook_Oauth_Key);

        // Use popup for oauth
        OAuth.popup('facebook').then(facebook => {
            console.log('facebook:', facebook);

            // Prompts 'welcome' message with User's email on successful login
            // #me() is a convenient method to retrieve user data without requiring you
            // to know which OAuth provider url to call
            facebook.me().then(data => {
                console.log('me data:', data);

                var data = {
                    val: "facebookLogin",
                    social_id: data.id,
                    email: data.email,
                    f_name: data.firstname,
                    l_name: data.lastname,
                    image: data.avatar
                };

                // function for social logins...
                socialLogin(data);

            })
        });
    })

    // /signing in with google

    $('#google-button').on('click', function () {
        // Initialize with your OAuth.io app public key
        OAuth.initialize(Google_Oauth_Key);
        // Use popup for oauth
        OAuth.popup('google').then(google => {
            console.log('google:', google);
            // Retrieves user data from oauth provider
            // Prompts 'welcome' message with User's email on successful login
            // #me() is a convenient method to retrieve user data without requiring you
            // to know which OAuth provider url to call
            google.me().then(data => {
                console.log('me data:', data);
                // var data = {
                //     val: "googleLogin",
                //     social_id: data.id,
                //     email: data.email,
                //     f_name: data.firstname,
                //     l_name: data.lastname,
                //     image: data.avatar
                // };

                // function for social logins...
                // socialLogin(data);
            });
            // Retrieves user data from OAuth provider by using #get() and
            // OAuth provider url
            google.get('/plus/v1/people/me').then(data => {
                console.log('self data:', data);
            })
        });
    })

    // function to login the user...
    function socialLogin(data) {
        $.ajax({
            url: student_server_url + "StudentLogin.php",
            type: "post",
            dataType: "json",
            data: data,
            success: function (response) {
                // console.log(response.data);

                sweetalert(response);
                if (response.status == 200) {

                    localStorage.setItem('data', JSON.stringify(response.data));

                    // displaying the student dashboard page...
                    setTimeout(function () {
                        window.location.href = base_url + "student-dashboard/";
                    }, 2000);
                }
            },
            error: function (err) {
                var response = { status: 400, message: 'Internal Server error while login.' };
                errorSwal(response);
            }
        })
    }


    $("#student_login_form").submit(function (e) {
        e.preventDefault();
        var verify_btn;

        $("#sign_in_btn").attr('disabled', true);

        // get the instance of form
        var form = document.getElementById('student_login_form');

        // creating instance of form data...
        var data = new FormData(form);

        data.append("val", "normalLogin");

        $.ajax({
            url: student_server_url + "StudentLogin.php",
            type: "post",
            dataType: "json",
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {

                $("#sign_in_btn").attr('disabled', false);

                switch (response.status) {

                    // if status is suuccess...
                    case 200:
                        sweetalert(response)

                        localStorage.setItem('data', JSON.stringify(response.data));

                        // displaying the student dashboard page...
                        setTimeout(function () {
                            window.location.href = base_url + "student-dashboard/";
                        }, 2000);
                        break;

                    // if user is not verified...
                    case 209:
                        // displaying warning message...
                        swal({
                            title: response.message,
                            icon: 'warning',
                            buttons: [
                                'Cancel',
                                'Yes Verify'
                            ]
                        }).then(function (val) {
                            if (val) {
                                verify_btn = $(this);
                                verify_btn.attr('disabled', true);

                                response.data.val = "Student_Verification";

                                $.ajax({
                                    url: student_server_url + "VerificationMail.php",
                                    type: "post",
                                    data: response.data,
                                    dataType: "json",
                                    success: function (response) {
                                        verify_btn.attr('disabled', false);
                                        sweetalert(response)
                                    },
                                    error: function (error) {
                                        verify_btn.attr('disabled', false);
                                        var response = { status: 400, 'message': 'Internal Server Error' };
                                        errorSwal(response);
                                    }
                                })
                            }
                        })
                        break;

                    // if account is deactivated by admin...
                    case 400:
                        errorSwal(response);
                        break;
                }
            },
            error: function (error) {
                $("#sign_in_btn").attr('disabled', false);
                var response = { status: 400, 'message': 'Internal Server Error' };
                errorSwal(response);
            }
        })
    })
})