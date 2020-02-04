$(document).ready(function () {

    $("#dob").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1980:2014"
    });

    var data = { val: 'getData' };

    getStudentData(data);
})

// function to get nationality,language and highest qualification...
function getStudentData(data) {
    var cntry_html = "";
    var grade_html = "";
    var language_html = "";
    var exam_html = "";
    var grade_scheme_html = "";
    var grades = [];

    $.ajax({
        url: agent_server_url + "GetData.php",
        dataType: "json",
        data: data,

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    // console.log(response);
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


                    if (response.hasOwnProperty('exam_data')) {

                        // each loop to display exams as radio buttons...
                        $.each(response.exam_data, function (k, obj) {
                            exam_html += "<input type='radio' class='exam_input' value='" + obj.id + "' name='exams'>" + obj.name + "<br>";
                        });

                        // displaying exams in the ...
                        $("#exams").html(exam_html);
                    }

                    if (response.hasOwnProperty('grade_data')) {

                        // each loop to display grade schemes in drop down...
                        grade_scheme_html += "<option selected disabled>Select Grade Scheme</option>";
                        $.each(response.grade_data, function (grade, scheme_arr) {
                            $.each(scheme_arr, function (k, scheme) {
                                grade_scheme_html += "<option value='" + scheme + "'>" + scheme + "</option>";
                            })
                        });
                        $("#grade_scheme").html(grade_scheme_html);
                    }

                }
                errorSwal(response);

            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$(document).on('click', '.exam_input', function () {

    if ($(this).prop('checked')) {
        // when user selects the exam...
        var marks_html = "";
        var exam = $(this).val();

        // then defining the text fields to enter marks in different subjects...
        marks_html += "<h2>Marks Scored In " + exam + "</h2>"
        marks_html += "<p>Reading&nbsp;&nbsp;<input type='text' name=exams[" + exam + "][reading] id='reading'></p>";
        marks_html += "<p>Writing&nbsp;&nbsp;<input type='text' name=exams[" + exam + "][writing] id='writing'></p>";
        marks_html += "<p>Listening&nbsp;&nbsp;<input type='text' name=exams[" + exam + "][listening] id='listening'></p>";
        marks_html += "<p>Speaking&nbsp;&nbsp;<input type='text' name=exams[" + exam + "][speaking] id='speaking'></p>";

        // setting the marks html...
        $("#marks").html(marks_html);
    }

})

// when user changes the language based on language exam will appear...
$("#lang_prior").change(function () {
    var lang_id = $(this).val();
    var data = { 'val': 'getExamByLanguage', 'id': lang_id };

    // calling function to get exams of specific language...
    getStudentData(data);
})


$("#qualification").change(function () {
    var grade_id = $(this).val();
    var data = { 'val': 'getGradeSchemeById', 'id': grade_id };
    getStudentData(data);
})

$("#img_input").change(function () {
    previewImage(this);
})

function previewImage(file_input) {
    $("#image").show();
    if (file_input.files && file_input.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the source of document file
            $('#image').attr('src', e.target.result);
        }
        reader.readAsDataURL(file_input.files[0]);
    }
}

$("#add_student").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('add_student');
    var form_data = new FormData(form);
    form_data.append('val', 'addStudent');

    $.ajax({
        url: student_server_url + "AddStudent.php",
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
                form.reset();

                setTimeout(function () {
                    window.location.href = base_url + "view-students/";

                }, 1500);

            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})