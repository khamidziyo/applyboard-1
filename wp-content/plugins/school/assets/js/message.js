var sender_id;
var receiver_id;
var local_data;


local_data = JSON.parse(localStorage.getItem('data'));

getMessages();

function getMessages() {

    var msg_html = "";

    switch (local_data.role) {

        // if logged in user is school...
        case 0:
            url = school_server_url + "GetMessages.php";
            break;

        // if logged in user is student...
        case '1':
            url = student_server_url + "GetMessages.php";
            break;

        // if logged in user is admin...
        case '2':
            break;

        // if logged in user is agent...
        case '3':
            url = common_server_url + "GetMessages.php";
            var data = { role: local_data.role, val: "getMessages" };
            break;

        default:
            let response = { status: 400, message: "User role not defined" };
            errorSwal(response);
            break;
    }

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

                // console.log(response);

                // if status is 200...
                if (response.status == 200) {

                    if (response.messages.length > 0) {

                        $.each(response.messages, function (k, obj) {
                            msg_html += "<label>User:&nbsp;&nbsp;" + obj.name + "</label>";
                            if (obj.status == "0") {
                                msg_html += "<p>Message:&nbsp;&nbsp;<b>" + obj.message + "</b></p>";
                            } else {
                                msg_html += "<p>Message:&nbsp;&nbsp;" + obj.message + "</p>";
                            }
                            msg_html += "<small>" + obj.created_at + "</small><br>";
                            msg_html += "<button sender=" + obj.receiver_id + " receiver=" + obj.sender_id
                            msg_html += " class='btn btn-primary view_message' data-toggle='collapse' data-target='#messageDiv'>View All Messages</button><br><br>"
                        })

                    } else {
                        msg_html += "<p>No message Found.</p>";
                    }

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

            var response = { status: 400, message: "Internal Server Error" };
            errorSwal(response);
            console.error(error);
        }
    })
}

$(document).on('click', '.view_message', function () {

    sender_id = $(this).attr('sender');
    receiver_id = $(this).attr('receiver');

    var data = { val: "getAllMessages", sender_id: sender_id, receiver_id: receiver_id, role: local_data.role };

    switch (local_data.role) {

        // if logged in user is school...
        case 0:
            getAllMessagesById(school_server_url + "GetMessage.php", data);
            break;

        // if logged in user is student...
        case '1':
            getAllMessagesById(student_server_url + "GetMessage.php", data);
            break;

        // if logged in user is agent...
        case '3':
            getAllMessagesById(common_server_url + "GetMessages.php", data);
            break;
    }
})

// function to get all the messages...
function getAllMessagesById(url, data) {

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
                redirect(local_data.role);
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // console.log(response);

                // if status is 200...
                if (response.status == 200) {

                    if (response.messages.length > 0) {
                        $.each(response.messages, function (k, obj) {
                            msg_html += "<div><p>Message:&nbsp;&nbsp;" + obj.message + "</p>";

                            msg_html += viewDocuments(obj.document, staff_assets_url);

                            msg_html += "<small>" + obj.created_at + "</small><br><br>";
                            msg_html += "</div></br>";
                        });

                    } else {
                        msg_html += "<h2>No message found.</h2>";
                    }

                    $("#all_messages").html(msg_html);

                } else {
                    errorSwal(response);
                }
            }

            //if token not verified...
            else {
                redirect(local_data.role);
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

function viewDocuments(document_obj, url) {
    let documents = JSON.parse(document_obj);
    var html = "";

    $.each(documents, function (key, doc_name) {

        var doc_arr = doc_name.split('.');

        var type = doc_arr[doc_arr.length - 1];

        switch (type) {
            case 'pdf':
                html += "<li><a href='" + url + "documents/" + doc_name + "' download='" + doc_name + "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/pdf48.png' width='48' height='48'>PDF</a></li><br>";
                break;

            case 'xlsx':
                html += "<li><a href='" + url + "documents/" + doc_name + "' download='" + doc_name + "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/xlsx48.png' width='48' height='48'>XLSX</a></li><br>";
                break;

            case 'png':
                html += "<li><div style='display: none;' id='hidden_image_" + key + "'><img src='" + url + "documents/" + doc_name + "' width='80%' height='80%'></div><a href='".url + "documents/" + doc_name + "' data-fancybox data-src='#hidden_image_" + key + "' download='" + doc_name + "'>Image</a></li><br>";
                break;

            case 'jpg':
                html += "<li><div style='display: none;' id='hidden_image_" + key + "'><img src='".url + "documents/" + doc_name + "' width='80%' height='80%'></div><a href='".url + "documents/" + doc_name + "' data-fancybox data-src='#hidden_image_" + key + "' download='" + doc_name + "'>Image</a></li><br>";
                break;

            case 'jpeg':
                html += "<li><div style='display: none;' id='hidden_image_" + key + "'><img src='".url + "documents/" + doc_name + "' width='80%' height='80%'></div><a href='".url + "documents/" + doc_name + "' data-fancybox data-src='#hidden_image_" + key + "' download='" + doc_name + "'>Image</a></li><br>";
                break;
        }
    });
    return html;
}

$("#chatForm").submit(function (e) {
    e.preventDefault();

    // // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    // var pusher = new Pusher('9d27859f518c27645ae1', {
    //     cluster: 'ap2',
    //     forceTLS: true
    // });

    // var channel = pusher.subscribe('my-channel');
    // channel.bind('my-event', function (data) {
    //     console("<p style='color:green'>" + data + "</p>");
    // });

    var form = document.getElementById('chatForm');
    var form_data = new FormData(form);

    form_data.append('val', 'sendMessage');
    form_data.append('role', local_data.role);
    form_data.append('sender_id', sender_id);
    form_data.append('receiver_id', receiver_id);


    $.ajax({
        url: common_server_url + "SendMessage.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {

            // if token not found in the local Storage...
            if (!appendToken(request)) {
                staffRedirectLogin();
            }
        }, success: function (response) {

            // if token verified successfully...
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    getMessages();
                    form.reset();
                }
            } else {
                staffRedirectLogin();
            }
        }, error: function (error) {

            // if any error occurs on internal server error...
            console.error(error);
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    })
})





