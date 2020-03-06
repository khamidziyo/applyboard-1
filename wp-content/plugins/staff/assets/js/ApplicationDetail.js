

function openForm() {

    document.getElementById("chatContainer").style.display = "block";

    $("#user_name").html($("#stu_name").html());

    getMessages();
}

function getMessages() {

    var user = $("#chat_user").val();

    var local_data = JSON.parse(localStorage.getItem('data'));

    $.ajax({
        url: common_server_url + "GetMessages.php",
        data: { val: 'getMessages', user: user, role: local_data.role },
        dataType: "json",
        beforeSend: function (request) {

            // if token not found in the local Storage...
            if (!appendToken(request)) {
                staffRedirectLogin();
            }
        }, success: function (response) {

            // if token verified successfully...
            if (verifyToken(response)) {
                if (response.messages.length > 0) {
                    var msg_html = "<div>";
                    $.each(response.messages, function (k, obj) {

                        if (response.logged_user == obj.sender_id) {
                            msg_html += "<h4 style='color:green;float:right'>" + obj.message + "&nbsp;<small>" + obj.created_at + "</small></h4><br>";

                            msg_html += viewDocuments(obj.document, staff_assets_url);

                        } else {
                            msg_html += "<h4 style='color:red;'>" + obj.message + "&nbsp;<small>" + obj.created_at + "</small></h4><br>";

                            msg_html += viewDocuments(obj.document, student_assets_url);
                        }
                    })
                    msg_html += "</div>";
                    $("#previous_messages").html(msg_html);
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


function closeForm() {
    document.getElementById("chatContainer").style.display = "none";
}


// get the initial selected status value...
var status_val = $("#app_status").children("option:selected").val();

let searchParams = new URLSearchParams(window.location.search)
if (searchParams.has('a_id')) {
    let app_id = searchParams.get('a_id');
    // console.log(app_id);
    var data = { app_id: app_id };

} else {
    swal({
        title: "Application id is required",
        icon: 'error'
    })
}
$("#app_status").change(function () {

    var value = $(this).val();
    data.status = value;
    data.val = "updateStatus";
    updateApplicationStatus(data);
})


function updateApplicationStatus(data) {
    // console.log(data);

    swal({
        title: "Are you sure you want to update the application status",
        icon: "warning",
        buttons: ['Cancel', 'Yes,Update']
    }).then(function (val) {
        if (val) {

            $.ajax({
                url: staff_server_url + "UpdateApplication.php",
                type: "post",
                data: data,
                dataType: "json",
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

                            setTimeout(function () {
                                location.reload();
                            }, 1500);

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
        } else {
            $("#app_status").val(status_val);
        }
    })
};

$("#chatForm").submit(function (e) {
    e.preventDefault();

    var local_data = JSON.parse(localStorage.getItem('data'));

    var form = document.getElementById('chatForm');
    var form_data = new FormData(form);

    form_data.append('val', 'sendMessage');
    form_data.append('role', local_data.role);

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