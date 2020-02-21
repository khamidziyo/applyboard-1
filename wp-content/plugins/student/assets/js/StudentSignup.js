// when student regsiters...
$("#student_reg_form").submit(function (e) {
    e.preventDefault();

    // to show the loading image...
    $("#load_img").show();
    $("#sign_up").hide();

    // get the instance of form
    var form = document.getElementById('student_reg_form');

    // creating instance of form data class...
    var data = new FormData(form);

    //ajax to submit the student data...
    $.ajax({
        url: student_server_url + "StudentSignup.php",
        type: "post",
        dataType: "json",
        data: data,
        contentType: false,
        processData: false,

        // on success response...
        success: function (response) {
            $("#load_img").hide();
            $("#sign_up").show();
            sweetalert(response);

            if (response.status == 200) {

                setTimeout(function () {
                    window.location.reload();
                }, 1500)
            }
        },
        // on error response...
        error: function (error) {
            $("#load_img").hide();
            $("#sign_up").show();
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})


// signing in with facebook..
$('#facebook-button').on('click', function () {
    // Initialize with your OAuth.io app public key
    OAuth.initialize(Facebook_Oauth_Key);
    // Use popup for oauth
    OAuth.popup('facebook').then(facebook => {
        // console.log('facebook:', facebook);

        localStorage.setItem('tok', facebook.access_token);
        // Prompts 'welcome' message with User's email on successful login
        // #me() is a convenient method to retrieve user data without requiring you
        // to know which OAuth provider url to call
        facebook.me().then(data => {
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
            sweetalert(response);

            if (response.status == 200) {
                localStorage.setItem('data', JSON.stringify(response.data));

                // displaying the student dashboard page...
                setTimeout(function () {
                    window.location.href = base_url + "student-home/";
                }, 2000);
            }
        },
        error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}