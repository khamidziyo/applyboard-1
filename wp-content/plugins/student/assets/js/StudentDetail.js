const queryString = window.location.search;
var stu_id = '';

const urlParams = new URLSearchParams(queryString);

local_data = JSON.parse(localStorage.getItem('data'));



stu_id = urlParams.get('stu_id');

switch (local_data.role) {

    // if role is agent...
    case "5":
        var data = { student: stu_id, 'val': 'getStudentByStaff' };

        break;

    // if role is sub agent...
    default:
        swal({
            title: "No match found",
            icon: "error"
        })
        break;
}

// function that calls when student updates the profile
getStudentProfile(data);


function getStudentProfile(data) {
    $.ajax({
        url: student_server_url + "StudentDetail.php",
        type: "get",
        dataType: "json",
        async: false,
        data: data,

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                studentRedirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {
                // if status is 200...

                if (response.status == 200) {
                    var doc_html = "";
                    var sub_marks_html = "";

                    var data = response.student;
                    $("#first_name").html(data.f_name)
                    $("#last_name").html(data.l_name)
                    $("#stu_email").html(data.email)
                    $("#dob").html(data.dob)
                    if (data.gender == '1') {
                        $("#gender").html("Male")
                    } else {
                        $("#gender").html('Female')
                    }

                    switch (data.has_visa) {
                        case '0':
                            $("#visa").html("No I don't have this")
                            break;
                        case '1':
                            $("#visa").html("USA F1 Visa")
                            break;

                        case '2':
                            $("#visa").html("Canadian study Permit or Visitor Visa")
                            break;
                    }
                    $("#passport").html(data.passport_no)
                    $("#nationality").html(data.cntry_name)

                    var grade_name = Object.keys(JSON.parse(data.grade_name));

                    $("#qualification").html(grade_name[0]);

                    $("#grade_scheme").html(data.grade_scheme);

                    $("#marks_score").html(data.score);


                    $("#language").html(data.lang_name)


                    if (response.hasOwnProperty('intake')) {
                        var intake = JSON.parse(response.intake);
                        var stu_intake = intake.month + "-" + intake.year;
                        $("#stu_intake").html(stu_intake);
                    }

                    if (response.hasOwnProperty('exams')) {
                        // decoding json to get the exams...
                        var exams = JSON.parse(response.exams);
                        // console.log(response.data);
                        var sub_arr = Object.values(exams);
                        // console.log(sub_arr);

                        // loop over subject array to get the exams and marks stored in array...
                        $.each(sub_arr, function (k, obj) {

                            sub_marks_html += "<h3>" + Object.keys(exams)[k] + "</h3>";


                            for (arr_key in Object.keys(obj)) {
                                sub_marks_html += Object.keys(obj)[arr_key] + "&nbsp;&nbsp;";

                                // to get the marks...
                                sub_marks_html += Object.values(obj)[arr_key] + "<br>"
                            }
                        })

                        $("#exams").html(sub_marks_html);
                    }

                    $("#profile_image").attr('src', student_assets_url + "images/" + data.image)

                    if (response.hasOwnProperty('document')) {
                        doc_html += "<ul>"
                        $.each(response.document, function (k, obj) {
                            doc_html += "<li><a href='" + student_assets_url + "documents/" + obj.document + "' download='" + obj.document + "'>" + obj.document + "</a></li>";
                        })
                        doc_html += "</ul>"
                        $("#documents").html(doc_html);
                    }

                    // console.log(response);

                } else {
                    errorSwal(response);
                }
            } else {
                // if the token is not in the localStorage...
                studentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    });
}
