$(document).ready(function() {

    var data = { val: "getCountries" };

    getCountryAndSchool(data);

});

function getCountryAndSchool(data) {

    $.ajax({
        url: admin_server_url + "Courses.php",
        type: "get",
        data: data,
        dataType: "json",
        beforeSend: function(request) {
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        success: function(response) {
            if (verifyToken(response)) {
                var html = "";
                if (response.status == 200) {

                    switch (response.type) {

                        case 'countries':
                            html += "<option selected='true' disabled>Select Country</option>"
                            html += "<option value='all'>All Country</option>";
                            $.each(response.data, function(k, v) {
                                html += "<option value='" + v.id + "'>" + v.name + "</option>";
                            })
                            $("#countries").html(html);

                            break;

                        case 'schools':
                            html += "<option selected='true' disabled>Select School</option>"
                            $.each(response.data, function(k, v) {
                                html += "<option value='" + v.id + "'>" + v.name + "</option>";
                            })
                            $("#schools").html(html);
                            break;

                    }
                }
            } else {
                adminRedirectLogin();
            }
        },
        error: function(error) {
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$("#countries").change(function() {
    $("#course_table").hide();
    var cntry_id = $(this).val();
    var data = { cntry_id: cntry_id, val: "getSchools" };
    getCountryAndSchool(data);

})

// function that invokes when user selects the school...
$("#schools").change(function() {
    $("#course_table").show();
    var s_id = $(this).val();
    var data = { val: "getCourses", school: btoa(s_id) };

    getCourseBySchool(data);

});

function getCourseBySchool(data) {
    $("#course_table").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
        "processing": true,
        "serverSide": true,
        "language": {
            "emptyTable": "No Course available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [2, 3, 4, 5, 6, 7] }

        ],
        "ajax": ({
            url: admin_server_url + "Courses.php",
            data: data,
            dataType: "json",
            beforeSend: function(request) {
                if (!appendToken(request)) {
                    adminRedirectLogin();
                }
            }
        }),
        "initComplete": function(seting, response) {
            //Make your callback here.
            if (verifyToken(response)) {
                console.log(response);
            } else {
                adminRedirectLogin();
            }
        }
    })
}


