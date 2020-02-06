
$("#dob").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "1980:2014"
});

var data = { val: 'getData' };

getStudentData(data);

const queryString = window.location.search;

const urlParams = new URLSearchParams(queryString);
if (urlParams.get('id')) {
    $("#submit_btn").val('Update Student');
    $("#img_input").attr('required',false);
    $("#grade_scheme").attr('required',false);

    var stu_id = urlParams.get('id');
    var data = { student: stu_id, 'val': 'editUser' };
    getUserProfile(data);
}
// function that invokes when agent edits any user...
function getUserProfile(data) {
    var doc_html = "<h2>Documents Uploaded</h2><ul>";

    $.ajax({
        url: agent_server_url + "GetStudentProfile.php",
        dataType: "json",
        data: data,

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    if (response.hasOwnProperty('data')) {
                        var data = { 'val': 'getExamByLanguage', 'id': response.data.language_prior };

                        // calling function to get exams of specific language...
                        getStudentData(data);


                        var exams=JSON.parse(response.data.exam);
                        
                        for (let key of Object.keys(exams)) {
                            $(".exam_input[value="+key+"]").prop("checked","true");
                        }

                        for (let value of Object.values(exams)) {
                            console.log(value); // John, then 30
                          }
                          
                
                        
                        $("#first_name").val(response.data.f_name)
                        $("#last_name").val(response.data.l_name)
                        $("#email").val(response.data.email)
                        $("#pass_number").val(response.data.passport_no)

                        if (response.data.gender == "1") {
                            $("#gender").val("male").prop('checked', true);
                        } else {
                            $("#gender").val("female").prop('checked', true);
                        }

                        $("#marks").val(response.data.score)
                        $("#dob").val(response.data.dob);
                        $("#lang_prior").val(response.data.language_prior);
                        $("#nationality").val(response.data.nationality);
                        $("#qualification").val(response.data.grade_id);
                        $("#visa").val(response.data.has_visa);
                        $("#image").show();
                        $("#image").attr('src', student_assets_url + "images/" + response.data.image)
                        $("#cur_image").val(response.data.image);

                        var data = { 'val': 'getGradeSchemeById', 'id': response.data.grade_id };
                        getStudentData(data);

                        $("#grade_scheme").val()
                        // console.log(response.data);
                    }
                    if (response.hasOwnProperty('documents')) {
                        if (response.documents.length > 0) {
                            // console.log(response.documents);
                            $.each(response.documents, function (k, obj) {
                                doc_html += "<li>" + obj.document + "<button type='button' class='btn btn-primary remove_doc'";
                                doc_html += "data_id=" + obj.id + ">Remove</button></li><br>";
                            })
                            doc_html += "</ul>";
                            $("#documents").html(doc_html);
                        }
                    }

                } else {
                    errorSwal(response);
                }

            }
        }, error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$(document).on('click', '.remove_doc', function () {
    var doc_id = $(this).attr('data_id');
    alert(doc_id);
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
        async: false,
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
                            exam_html += "<input type='checkbox' class='exam_input' value='" + obj.id + "' name='" + obj.name + "'>" + obj.name + "<br>";
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
    var exam = $(this).attr('name');

    var exam_id = $(this).val();

    if ($(this).prop('checked')) {
        // when user selects the exam...
        var marks_html = "";

        // then defining the text fields to enter marks in different subjects...
        marks_html += "<span id='" + exam + "'><h2>Marks Scored In " + exam + "</h2>"
        marks_html += "<p>Reading&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][reading] id='reading'></p>";
        marks_html += "<p>Writing&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][writing] id='writing'></p>";
        marks_html += "<p>Listening&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][listening] id='listening'></p>";
        marks_html += "<p>Speaking&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][speaking] id='speaking'></p>";
        marks_html += "</span>";
        // setting the marks html...
        $("#marks").append(marks_html);
    } else {
        $("#" + exam).remove();
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

                if (response.status == 200) {
                    form.reset();

                    setTimeout(function () {
                        window.location.href = base_url + "view-students/";
                    }, 1500);
                }


            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})