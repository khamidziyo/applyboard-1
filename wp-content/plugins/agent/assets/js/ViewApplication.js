$(document).ready(function () {
    var student_id;

    const queryString = window.location.search;

    const urlParams = new URLSearchParams(queryString);

    var stu_id = urlParams.get('id');



    if (localStorage.getItem('data') != null) {
        var local_data = JSON.parse(localStorage.getItem('data'));

        switch (local_data.role) {

            case '3':
                var data = { student: stu_id, 'val': 'getApplicationsByAgent' };
                break;

            case '4':
                var data = { student: stu_id, 'val': 'getApplicationsBySubAgent' };
                break;

            default:
                swal({
                    title: "No role match found",
                    icon: 'error'
                })
                break;
        }
    } viewApplications(data);
})

function viewApplications(data) {
    $("#view_application_table").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No application available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [6, 7] }

        ],
        "ajax": ({
            url: agent_server_url + "Applications.php",
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
    });
}

$("#add_more_btn").click(function () {
    var html = "<span><input type='file' name='upload_document[]' required><button class='btn btn-danger remove_btn'>Remove</button></span><br>";
    $("#add_more_docs").append(html);
})

$(document).on('click', '.remove_btn', function () {
    $(this).parent().remove();
})

$(document).on('click', '.upload_document', function () {
    student_id = $(this).attr('data_id');
    $("#document_modal").modal('show');
})

$("#upload_document_form").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('upload_document_form');
    var form_data = new FormData(form);
    form_data.append('student_id', student_id);

    form_data.append('val', 'uploadDocument');

    $.ajax({
        url: agent_server_url + "UploadDocument.php",
        type: "post",
        data: form_data,
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function (request) {
            $("#upload_btn").attr('disabled', true);
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            $("#upload_btn").attr('disabled', false);
            if (verifyToken(response)) {

                sweetalert(response);

                if (response.status == 200) {
                    form.reset();
                    
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }

            } else {
                agentRedirectLogin();
            }
        }, error: function (err) {
            $("#upload_btn").attr('disabled', false);
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    })
})