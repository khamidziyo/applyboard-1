$(document).ready(function () {

    var local_data;
    var user_id;

    if (localStorage.getItem('data') != null) {
        local_data = JSON.parse(localStorage.getItem('data'));

        switch (local_data.role) {

            // if logged in user is school...
            case 0:
                getMessages(school_server_url + "GetMessage.php");

                break;

            // if logged in user is student...
            case '1':
                getMessages(student_server_url + "GetMessage.php");
                break;

            // if logged in user is admin...
            case '2':
                break;

            default:
                swal({
                    title: "User role not defined",
                    icon: 'error'
                })
                break;
        }
    }

})

function getMessages(url) {

    var msg_html = "";

    $.ajax({
        url: url,
        type: "get",
        dataType: "json",
        data: { val: "getMessages" },

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    // console.log(response);

                    $.each(response.sent_messages, function (k, obj) {
                        msg_html += "<label>User:&nbsp;&nbsp;" + obj.name + "</label>";
                        if (obj.status == "0") {
                            msg_html += "<p>Message:&nbsp;&nbsp;<b>" + obj.message + "</b></p>";
                        } else {
                            msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p>";
                        }
                        msg_html += "<small>" + obj.created_at + "</small><br>";
                        msg_html += "<button sender='" + obj.name + "' user_id=" + btoa(obj.u_id) + " class='btn btn-primary view_message'>View All Messages</button><br><br>"
                    })
                    $("#messages").html(msg_html);
                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            }

            //if token not verified...
            else {
                redirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
            console.error(error);
        }
    })
}

$(document).on('click', '.view_message', function () {
    sender_name = $(this).attr('sender');

    user_id = $(this).attr('user_id');

    var data = { val: "getAllMessages", user: user_id };

    switch (local_data.role) {

        // if logged in user is school...
        case 0:
            getMessagesBySenderId(data, school_server_url + "GetMessage.php", sender_name);
            break;

        // if logged in user is student...
        case '1':
            getMessagesBySenderId(data, student_server_url + "GetMessage.php", sender_name);
            break;

    }
})

// function to get all the messages...
function getMessagesBySenderId(data, url, sender) {

    var msg_html = "";

    $.ajax({
        url: url,
        type: "get",
        dataType: "json",
        data: data,

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    $("#message_modal").modal("show");
                    $("#sender_name").html(sender);

                    // return false;
                    $.each(response.messages, function (k, obj) {
                        if (response.id == obj.from_user) {
                            msg_html += "<div class='sent_messages'>"

                            msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p>";
                            msg_html += "<small>" + obj.created_at + "</small><br><br>";
                            msg_html += "</div></br>";
                        } else {
                            msg_html += "<div class='receive_messages'>"

                            msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p>";
                            msg_html += "<small>" + obj.created_at + "</small><br><br>";
                            msg_html += "</div>";
                        }
                    });

                    $("#all_messages").html(msg_html);

                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            }

            //if token not verified...
            else {
                redirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
            console.error(error);
        }
    })
}

$("#message_form").submit(function (e) {
    e.preventDefault();
    var form = document.getElementById('message_form');
    var form_data = new FormData(form);
    form_data.append('user', user_id);

    switch (local_data.role) {

        case 0:

            sendMessage(form_data, school_server_url);
            break;

        case '1':
            sendMessage(form_data, student_server_url);
            break;
    }
})

// function to send the message...
function sendMessage(data, url) {

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('9d27859f518c27645ae1', {
        cluster: 'ap2',
        forceTLS: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function (data) {
        console("<p style='color:green'>"+data+"</p>");
    });

    $.ajax({
        url: url + "SendMessage.php",
        type: "post",
        dataType: "json",
        data: data,
        contentType: false,
        processData: false,

        // function to append the token in the request...
        beforeSend: function (req) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(req)) {
                redirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
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
            console.error(error);
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
        }
    })
}


// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    switch (local_data.role) {

        // if logged in user is school...
        case 0:
            setTimeout(function () {
                window.location.href = base_url + "school-login/";
            }, 2000);
            break;

        // if logged in user is student...
        case '1':
            setTimeout(function () {
                window.location.href = base_url + "student-login/";
            }, 2000);

            break;
    }

}