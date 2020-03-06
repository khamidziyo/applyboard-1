
var local_data = JSON.parse(localStorage.getItem('data'));

switch (local_data.role) {

    case '3':
        var data = { val: "getStudentsByAgent" };
        break;

    case '4':
        var data = { val: "getStudentsBySubAgent" }
        break;

    default:
        swal({
            title: "No role match found",
            icon: 'error'
        })
        break;
}
viewStudents(data);


function viewStudents(data) {

    $("#view_student_table").DataTable({
        "lengthMenu": [10, 20, 30, 40],
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No student available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [6, 7] }

        ],
        "ajax": ({
            url: agent_server_url + "Students.php",
            data: data,
            dataType: "json",
            beforeSend: function (request) {
                if (!appendToken(request)) {
                    agentRedirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {
            //Make your callback here.
            if (verifyToken(response)) {
                // console.log(response);
            } else {
                agentRedirectLogin();
            }
        }
    })
}


$(document).on('click', '.view_application', function () {
    var id = $(this).attr('data_id');
    window.location.href = base_url + "/view-applications?id=" + id;
})

$(document).on('click', '.edit_user', function () {
    var id = $(this).attr('data_id');
    window.location.href = base_url + "/add-student?id=" + id;
})

$(document).on('click', '.create_application', function () {
    var id = $(this).attr('data_id');

    window.location.href = base_url + "eligible-programs?id=" + id;
});


$(document).on('click', '.chatUser', function () {

    var receiver_id = $(this).attr('data_id');

    alert(receiver_id);
    // getMessages(receiver_id);

})


// function getMessages(user) {

//     var local_data = JSON.parse(localStorage.getItem('data'));

//     $.ajax({
//         url: common_server_url + "GetMessages.php",
//         data: { val: 'getMessages', user: user, role: local_data.role },
//         dataType: "json",
//         beforeSend: function (request) {

//             // if token not found in the local Storage...
//             if (!appendToken(request)) {
//                 staffRedirectLogin();
//             }
//         }, success: function (response) {

//             // if token verified successfully...
//             if (verifyToken(response)) {
//                 console.log(response);

//                 $("#chat_modal").modal('show');

//                 if (response.messages.length > 0) {
//                     var msg_html = "<div>";
//                     $.each(response.messages, function (k, obj) {

//                         if (user == obj.sender_id) {
//                             msg_html += "<h4 style='color:green;float:right'>" + obj.message + "&nbsp;<small>" + obj.created_at + "</small></h4><br>";

//                             msg_html += viewDocuments(obj.document, student_assets_url);

//                         } else {
//                             msg_html += "<h4 style='color:red;'>" + obj.message + "&nbsp;<small>" + obj.created_at + "</small></h4><br>";

//                             msg_html += viewDocuments(obj.document, staff_assets_url);
//                         }
//                     })
//                     msg_html += "</div>";
//                     $("#previous_messages").html(msg_html);
//                 }

//             } else {
//                 staffRedirectLogin();
//             }
//         }, error: function (error) {

//             // if any error occurs on internal server error...
//             console.error(error);
//             var response = { status: 400, message: 'Internal Server Error' };
//             errorSwal(response);
//         }
//     })
// }

// function viewDocuments(document_obj, url) {
//     let documents = JSON.parse(document_obj);
//     var html = "";

//     $.each(documents, function (key, doc_name) {
//         var doc_arr = doc_name.split('.');
//         var type = doc_arr[doc_arr.length - 1];

//         switch (type) {
//             case 'pdf':
//                 html += "<li><a href='" + url + "documents/" + doc_name + "' download='" + doc_name + "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/pdf48.png' width='48' height='48'>PDF</a></li><br>";
//                 break;

//             case 'xlsx':
//                 html += "<li><a href='" + url + "documents/" + doc_name + "' download='" + doc_name + "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/xlsx48.png' width='48' height='48'>XLSX</a></li><br>";
//                 break;

//             case 'png':
//                 html += "<li><div style='display: none;' id='hidden_image_" + key + "'><img src='" + url + "documents/" + doc_name + "' width='80%' height='80%'></div><a href='".url + "documents/" + doc_name + "' data-fancybox data-src='#hidden_image_" + key + "' download='" + doc_name + "'>Image</a></li><br>";
//                 break;

//             case 'jpg':
//                 html += "<li><div style='display: none;' id='hidden_image_" + key + "'><img src='".url + "documents/" + doc_name + "' width='80%' height='80%'></div><a href='".url + "documents/" + doc_name + "' data-fancybox data-src='#hidden_image_" + key + "' download='" + doc_name + "'>Image</a></li><br>";
//                 break;

//             case 'jpeg':
//                 html += "<li><div style='display: none;' id='hidden_image_" + key + "'><img src='".url + "documents/" + doc_name + "' width='80%' height='80%'></div><a href='".url + "documents/" + doc_name + "' data-fancybox data-src='#hidden_image_" + key + "' download='" + doc_name + "'>Image</a></li><br>";
//                 break;
//         }
//     });
//     return html;
// }

// $("#application_form").submit(function (e) {
//     e.preventDefault();

//     $.ajax({
//         url: agent_server_url + "AddApplication.php",
//         type: "post",
//         dataType: "json",
//         data: $("#application_form").serializeArray(),

//         beforeSend: function (request) {
//             if (!appendToken(request)) {
//                 agentRedirectLogin();
//             }
//         }, success: function (response) {
//             if (verifyToken(response)) {
//                 sweetalert(response);
//             } else {
//                 agentRedirectLogin();
//             }
//         }, error: function (error) {
//             var response = { 'status': 400, 'message': 'Internal Server Error' };
//             errorSwal(response);
//         }
//     })
// })