

$("#create_sublogin").click(function () {
    $("#sub_login_modal").modal('show');
})

// calling function to get user id from local storage...
getData();

// var local_data = {};
var notification_html = "<ul>";
if (localStorage.getItem('data') != null) {
    local_data = JSON.parse(localStorage.getItem('data'));
}


// get the user data from local storage...
function getData() {


    if (localStorage.getItem('data') != null) {
        local_data = JSON.parse(localStorage.getItem('data'));

        switch (local_data.role) {
            case 0:
                var school = { val: 'schoolProfile' };
                getUserProfile(school_server_url + 'SchoolProfile.php', school, school_assets_url);
                break;
            case "1":
                var user = { val: 'studentProfile' };
                getUserProfile(student_server_url + 'StudentProfile.php', user, student_assets_url);
                break;

            case "2":
                var user = { val: 'adminProfile' };
                getUserProfile(admin_server_url + "AdminDashboard.php", user, admin_assets_url);
                break;

            // if the logged in user is agent...
            case "3":
                var user = { val: 'agentProfile' };
                getUserProfile(agent_server_url + "AgentDashboard.php", user, agent_assets_url);
                break;

            // if the logged in user is agent...
            case "4":
                var user = { val: 'subAgentProfile' };
                getUserProfile(agent_server_url + "AgentDashboard.php", user, agent_assets_url);
                break;

            case '5':
                var user = { val: 'getStaffProfile' };
                getUserProfile(staff_server_url + "StaffProfile.php", user, staff_assets_url);
                break;
        }
    }
}


// when agent creates the profile of sub agent...
$("#sub_agent_form").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('sub_agent_form');
    var form_data = new FormData(form);
    $.ajax({
        url: agent_server_url + "AddSubAgent.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#sub_login_modal").modal('hide');

                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
                sweetalert(response);
            } else {
                agentRedirectLogin();
            }
        },
        error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})



// funtion that loads on script load to get the user profile...
function getUserProfile(url, user_data, asset_path) {
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: user_data,
        beforeSend: function (request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },
        success: function (response) {

            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#after_login").show();

                    $.each(response.notification, function (key, obj) {
                        notification_html += "<li class='notifications' id=" + obj.id + ">" + obj.message + " ";
                        notification_html += obj.u_email + " for " + obj.c_name + "</li>";
                    })
                    notification_html += "</ul>";
                    $("#notif_count").html(response.notification_count);

                    $("#user_email").html(response.data.email);


                    if (response.data.image != null) {
                        var img = "<img src='" + asset_path + "images/" + response.data.image + "' width='50px' height='50px' id='stu_image'>";
                        $("#user_image").html(img);
                    } else {
                        // console.log(response.data.image);
                        var img = "<img src='" + asset_path + "images/default_image.png' width='50px' height='50px' id='stu_image'>";
                        $("#user_image").html(img);
                    }
                } else {
                    swal({
                        title: response.message,
                        icon: "error"
                    })
                }

            } else {

                // then redirecting to login page if the token is expired...
                adminRedirectLogin();
            }

        },
        error: function (err) {
            swal({
                title: "Internal Server error",
                icon: "error"
            })
        }
    })
}
// when admin clicks on logout button...
$("#logout").click(function (e) {
    // console.log(local_data.role);

    e.preventDefault();
    // displaying warning message...
    swal({
        title: "Are you sure you want to logout",
        icon: 'warning',
        buttons: [
            'Cancel',
            'Yes Logout me'
        ]
    }).then(function (val) {
        if (val) {
            localStorage.removeItem('data');
            swal({
                title: "Logout Successfully",
                icon: "success"
            })
            switch (local_data.role) {

                // if logged in user is school...
                case 0:
                    setTimeout(function () {
                        window.location.href = base_url + "school-login/";
                    }, 2000)
                    break;

                // if logged in user is student...
                case "1":
                    setTimeout(function () {
                        window.location.href = base_url + "student-login/";
                    }, 2000)
                    break;

                // if logged in user is admin...
                case "2":
                    setTimeout(function () {
                        window.location.href = base_url + "admin-login/";
                    }, 2000)
                    break;

                // if logged in user is agent...
                case "3":
                    setTimeout(function () {
                        window.location.href = base_url + "agent-login/";
                    }, 2000)
                    break;

                // if logged in user is sub agent...
                case "4":
                    setTimeout(function () {
                        window.location.href = base_url + "sub-agent-login/";
                    }, 2000)
                    break;

                      // if logged in user is sub agent...
                case "5":
                    setTimeout(function () {
                        window.location.href = base_url + "staff-login//";
                    }, 2000)
                    break;
            }
        }
    })

})
$("#notification").hover(function () {
    //  console.log(notification_html);
    $("#user_notification").html(notification_html);
})

$("#notification").click(function () {
    window.location.href = base_url + "notification-detail/";
})