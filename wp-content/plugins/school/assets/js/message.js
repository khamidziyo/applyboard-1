$(document).ready(function () {
    var local_data;
    var sender_id;

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
                    console.log(response);
                    $.each(response.messages, function (k, obj) {
                        msg_html += "<label>User:&nbsp;&nbsp;" + obj.f_name + " " + obj.l_name + "</label>";
                        msg_html += "<p>Subject:&nbsp;&nbsp;" + obj.subject + "<p>"
                        if (obj.m_status == "0") {
                            msg_html += "<p>Message:&nbsp;&nbsp;<b>" + obj.message + "</b></p>";
                        } else {
                            msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p></br></br>";
                        }
                        msg_html += "<small>" + obj.created_at + "</small><br>";
                        msg_html += "<button sender='" + obj.f_name + " " + obj.l_name + "' class='btn btn-primary view_message'>View All Messages</button><br><br>"
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

    var data = { val: "getAllMessages" };
    switch (local_data.role) {

        // if logged in user is school...
        case 0:
            getMessagesBySenderId(data, school_server_url + "GetMessage.php", sender_name);
            break;

        // if logged in user is student...
        case '1':
            getMessagesBySenderId(data, student_server_url + "GetMessage.php");
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
                    // console.log(response);
                    $.each(response.messages, function (k, obj) {
                        if (response.id == obj.from_user) {

                            msg_html += "<span style='float:right';color:red>"
                            msg_html += "<p>Subject:&nbsp;&nbsp;" + obj.subject + "<p>";

                            msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p>";
                            msg_html += "<small>" + obj.created_at + "</small><br></br>";
                            msg_html += "</span>";
                        } else {

                            msg_html += "<span style='float:left'>"
                            msg_html += "<p>Subject:&nbsp;&nbsp;" + obj.subject + "<p>";

                            msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p>";
                            msg_html += "<small>" + obj.created_at + "</small><br></br>";
                            msg_html += "</span>";
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

$("#reply").click(function () {
    $("#message_modal").modal('hide');
    $("#send_message_modal").modal('show');
})


$("#message_form").submit(function (e) {
    e.preventDefault();
    var user = window.user_id;
    var form = document.getElementById('message_form');
    var form_data = new FormData(form);

    var sender = window.sender_id;
    alert(sender);
    return false;
    form_data.append('id', user);

    sendMessage(form_data);
});

// function to send the message...
function sendMessage(data) {
    $.ajax({
        url: school_server_url + "SendMessage.php",
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
                    document.getElementById('message_form').reset();
                    $("#message_modal").modal("hide");
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