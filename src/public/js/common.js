// ajax による POST通信
function postAjax(url, params = {}) {
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: params,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done((data, textStatus, jqXHR) => {
        console.log(data);
    }).fail((jqXHR, textStatus, errorThrown) => {
        console.error(textStatus);
    });
}

// ajax による GET通信
function getAjax(url) {
    $.ajax({
        url: url,
        type: 'GET',
    }).done((data, textStatus, jqXHR) => {
        console.log(data);
    }).fail((jqXHR, textStatus, errorThrown) => {
        console.error(textStatus);
    });
}