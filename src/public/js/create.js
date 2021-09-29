function load_s3_score() {
    $.ajax({
        // headers: {
        // POSTのときはトークンの記述がないと"419 (unknown status)"になるので注意
        // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // },
        // POSTだけではなく、GETのメソッドも呼び出せる
        type: 'GET',
        // type:'POST',
        // ルーティングで設定したURL
        url: '/scores/getS3Text/', // 引数も渡せる
        dataType: 'json',
    }).done(function (results) {
        // 成功したときのコールバック
        console.log('success');
        // console.log(results);
        $('#result_table').html(results.s3_contents);
        dragtable.init();
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // 失敗したときのコールバック
        console.log('failed')
        console.log(jqXHR)
        console.log(textStatus)
        console.log(errorThrown)
    }).always(function () {
        // 成否に関わらず実行されるコールバック
        console.log('done')
        // cssの読み込み直し（DOMで追加した要素のCSSが当たらないので）
        var link = document.createElement('LINK');
        link.rel = "stylesheet";
        link.type = "text/css";
        link.href = "../../../css/common.css";
        var head = document.getElementsByTagName('HEAD').item(0);
        head.appendChild(link);
c
        // 9行以上の追加は認められない
        checkEnableAddRow();
        addRowNumbering();
    });
    // });
};

// 9行以上は行追加できない制御をする関数
function checkEnableAddRow() {
    if ($('#edit_table tbody tr').length >= 9) {
        if ($('#edit_table tbody tr').length === 9) {
            $('#decision_btn').attr('disabled', false);
        } else {
            $('#decision_btn').attr('disabled', true);
        }
        $('#add_row').attr('disabled', true);
    } else {
        $('#add_row').attr('disabled', false);
        $('#decision_btn').attr('disabled', true);
    }
}
function addRowNumbering() {
    var row_cnt = $('#edit_table tbody tr').length;
    for (let index = 1; index <= row_cnt; index++) {
        var color_class = index > 9 ? 'row_cnt' : ''; //移動バーとゴミ箱があるので11から
        if (index == 1) {
            $('#edit_table tbody tr:first-child td:first-child').text(index);
            console.log('first')
        } else {
            console.log(index)
            $('#edit_table tbody tr:nth-child(' + index + ') td:first-child').text(index).attr('class', color_class);
        }
    }
}
// スコアカードの入力欄バリデーション
function scoreCardInputValidation() {
    let invalid = true;
    $('#result_table').find('input').each(function (key, el) {
        $(el).css('background-color', '');
        let value = $(el).val().trim();
        if (value.length === 0 || isNaN(value)) {
            $(el).css('background-color', 'red');
            invalid = false;
        }
    })
    return invalid;
}
// スコアカード選択肢バリデーション
function scoreCardSelectValidation(data) {
    let userSelect = false;
    let emptySelect = true;
    $.forEach($('.select_player'), function (el, key) {
        // ログインユーザー自身が選択されているかチェック
        if (data.id === Number($(el).val())) {
            userSelect = true;
        }
        // 未選択の項目が無いかチェック
        if ('----' === $(el).val()) {
            emptySelect = false;
        }
    });
    if (!userSelect) {
        return {result: false, message: 'ログインユーザーを選択して下さい。'};
    } else if (!emptySelect) {
        return {result: false, message: '未選択の項目があります。'};
    }
    return {result: true};
}
// テーブルの行を削除
$(document).on('click', '.btn_delete_row', function () {
    $(this).closest("tr").remove();
    // 9行以上の追加は認められない
    addRowNumbering();
    checkEnableAddRow();
});

// テーブルの列を削除
$(document).on('click', '.btn_delete_column', function () {
    // ボタンクリックされた場所が何番目の列か特定
    var column_no = $(this).parent().index() + 1;
    var target = '#edit_table td:nth-child(' + column_no + ')';
    var target2 = '#edit_table th:nth-child(' + column_no + ')';
    $(target).remove()
    $(target2).remove()
});

// テーブルに行を追加
// 9行以上の追加は認められない
$(document).on('click', '#add_row', function () {
    // 追加する<td></td>の数をカウント：一行に何列表示すればよいか
    var column_size = $('#edit_table tbody tr:first-child').children().length;
    // 追加する行のhtmlの組み立て
    var append_column = '<tr><td class=></td><td><i class="btn_delete_row fas fa-trash-alt" style="cursor: pointer; color: red;"></i></td>'
    for (var i = 1; i < column_size; i++) {
        append_column += '<td><input type="text" name="" value="" style="width:50px"></td>'
    }
    append_column += '</tr>'
    // 一番下に行を追加
    $('tbody').append(append_column);

    //行番号の振り直し
    var nine_cnt = $('#edit_table tbody tr').length;

    addRowNumbering();
    checkEnableAddRow();

    //--------------- 表上部に行を追加する場合------------------------------
    // $('tbody tr:first-child').after(append_column);
});
// テーブルに列を追加
$(document).on('click', '#add_column', function () {
    var append_thead_grab = '<th><i class="fas fa-arrows-alt-h grab"></i></th>';
    var append_thead_btn = '<th><i class="btn_delete_column fas fa-trash-alt" style="cursor: pointer; color: red;"></i></th>';
    var append_select_row = $('.select_player').first().parent().clone(true);

    $('thead tr:first-child').append(append_thead_grab);
    $('thead tr:nth-child(2)').append(append_thead_btn);
    $('thead tr:nth-child(3)').append(append_select_row);
    var append_tbody = '<td><input type="text" name="" value="" style="width:50px"></td>';
    $('tbody tr').append(append_tbody);
});

$(function () {
    // テーブルの読込
    $('#test_submit').on('click', load_s3_score);
    $(document).on('click', '#reload_table', load_s3_score);

    //スコアデータの保存
    $(document).on('click', '#decision_btn', function () {
        getUser()
            .done(function (data) {
                let selectValid = scoreCardSelectValidation(data);
                if (!selectValid.result) {
                    alert(selectValid.message);
                    return false;
                }
                if (!scoreCardInputValidation()) {
                    alert("入力に無効な値があります\n数値を入力してください");
                    return false;
                }
                $('#edit_table tbody tr').each(function(index, element){
                    let holeNumber = index + 1;
                    $(element).find('input').each(function (key, input) {
                        $(input).attr('name', 'hole_' + holeNumber + '[]');
                    })
                })
                $('#score-save-form').submit();
            })
            .fail(function () {
                alert('ユーザーの取得に失敗しました');
            });
    })

    // セレクトボックスを変更した時の処理
    $(document).on('change', '.select_player', function () {
        let options = {};
        let select = $(this).val();
        let count = 0;
        $.forEach($(this).children(), function (el, key) {
            options[el.value] = el.innerHTML;
        });
        $.forEach($('select'), function (el, key) {
            if (select === el.value) {
                count++;
            }
        });
        if (count > 1) {
            alert(options[$(this).val()] + ' はすでに選択されています。');
            $(this).val('----');
            $(this).children().first().attr('selected', 'selected');
        }
    })

});