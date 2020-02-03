$(document).ready(function () {

    $("#dob").datepicker({
        changeMonth: true,
        changeYear: true
    });

    getStudentData();
})

// function to get nationality,language and highest qualification...
function getStudentData() {
    var cntry_html = "";
    var grade_html = "";
    var language_html = "";
    var grades = [];

    $.ajax({
        url: agent_server_url + "GetData.php",
        dataType: "json",
        data: { val: 'getData' },

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    console.log(response);
                    if (response.hasOwnProperty('cntry_data')) {
                        // country html...
                        cntry_html += "<option selected='selected' disabled>Select Country</option>";

                        // each loop to display countries in drop down...
                        $.each(response.cntry_data, function (k, obj) {
                            cntry_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#nationality").html(cntry_html);
                    }

                    if (response.hasOwnProperty('grade')) {
                        grade_html += "<option selected='selected' disabled>Select Grade</option>";

                        // each loop to dynamically display all classes in drop down...
                        $.each(response.grade, function (k, obj) {

                            // decoding the json...
                            grades.push(JSON.parse(obj.grade_scheme));

                            // loop to get all the classess extracted from json...
                            $.each(JSON.parse(obj.grade_scheme), function (grade) {
                                grade_html += "<option value=" + obj.id + ">" + grade + "</option>";
                            })

                            //set the html of grade drop down...
                            $("#qualification").html(grade_html);
                        });
                    }

                    if (response.hasOwnProperty('languages')) {

                        // language html...
                        language_html += "<option selected='selected' disabled>Select</option>";

                        // each loop to display countries in drop down...
                        $.each(response.languages, function (k, obj) {
                            language_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#lang_prior").html(language_html);
                    }

                }
            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$("#add_student").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('add_student');
    var form_data = new FormData(form);
    form_data.append('val', 'addStudent');

    $.ajax({
        url: agent_server_url + "AddStudent.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);

            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})