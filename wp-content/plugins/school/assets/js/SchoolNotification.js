
// to get all the notifications...
getNotifications();

var notification_html = "<ul>";


function getNotifications() {
    $.ajax({
        url: school_server_url + "GetNotifications.php",
        type: "get",
        data: { val: "getNotifications" },
        dataType: "json",
        beforeSend: function (req) {
            if (!appendToken(req)) {
                redirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $.each(response.notification, function (key, obj) {
                        switch (obj.status) {
                            case '0':
                                notification_html += "<li style='color:red' class='notifications' id=" + obj.id + ">";
                                notification_html+="<a class='notifications' data_id="+obj.id+" href='#'>" + obj.message + " ";
                                notification_html += obj.u_email + " for " + obj.c_name+"'</a></li>";
                                break;
                            case '1':
                                notification_html += "<li style='color:green' class='notifications' id=" + obj.id + ">" + obj.message + " ";
                                notification_html += obj.u_email + " for " + obj.c_name+"</li>";
                                break;
                        }

                    })
                    notification_html += "</ul>";
                    $("#notification_detail").html(notification_html);
                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        }, error: function (error) {
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
        }

    })
}

$(document).on('click','.notifications',function(){
    var not_id=$(this).attr('data_id');
    alert(not_id);
})

// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/school-login/";
    }, 2000)
}