$(function () {
    let cropImage;
    let x1 = 0;
    let y1 = 0;
    let width = 0;
    let height = 0;
    $('#image-select-btn').on('click', function () {
        let img = $('#upload_image').find('img').get(0);
        let modalImage = $('#image').attr('src', img.src).get(0);
        cropImage = new Cropper(modalImage, {
            viewMode: 1,
            minContainerWidth: 400,
            minContainerHeight: 400,
            background: false,
            crop(event) {
                x1 = event.detail.x;
                y1 = event.detail.y;
                width = event.detail.width;
                height = event.detail.height;
                drawingCanvas();
            }
        });
    });
    // canvas に描画する
    function drawingCanvas () {
        let canvas = $('#canvas');
        const canvasCtx = document.getElementById('canvas').getContext('2d');
        canvasCtx.clearRect(0, 0, canvas.width(), canvas.height()); // 描画前にクリア
        canvasCtx.drawImage(document.getElementById("image"), x1, y1, width, height, 0, 0, 300, 300);
    }
    // モーダルを閉じる時に Cropper インスタンスを削除
    $('#previewModal').on('hidden.bs.modal', function () {
        cropImage.destroy();
    })

    $('#send-s3').on('click', function () {
        const image = document.querySelector("#canvas").toDataURL("image/png");
        let url = '/scores/upload';
        let params = { image: image };
        postAjax(url, params)
            .done((data, textStatus, jqXHR) => {
                // 一定時間ごとに S3 を確認し、テキストデータを取得する処理を記述
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                alert('ファイルのアップロードに失敗しました');
            })
    })

    $('.dropzone-field').on('click', function () {
        $('#hidden-file').click();
    })
    $('#rotate').on('click', function () {
        cropImage.rotate(90);
    })
    $('#zoom-in').on('click', function () {
        cropImage.zoom(0.1);
    })
    $('#zoom-out').on('click', function () {
        cropImage.zoom(-0.1);
    })
    $('#reset').on('click', function () {
        cropImage.reset();
    })
})