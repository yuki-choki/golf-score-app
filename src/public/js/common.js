// ajax による POST通信
function postAjax(url, params = {}) {
    return $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: params,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
}
function formPostAjax(url, params = {}) {
    return $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: params,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
}

// ajax による GET通信
function getAjax(url) {
    return $.ajax({
        url: url,
        type: 'GET',
    });
}

//user_id がcreate.jsに渡ってこない2021/06/22
function getUser() {
    let url = '/users/getUser';
    return getAjax(url);
}