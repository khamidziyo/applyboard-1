
function sweetalert(response) {
    if (response.status == 200) {
        swal({
            title: response.message,
            icon: 'success'
        })
    } else if (response.status == 400) {
        swal({
            title: response.message,
            icon: 'error'
        })
    }
}

function errorSwal(response) {
    if (response.status == 400) {
        swal({
            title: response.message,
            icon: 'error'
        })
    }
}
