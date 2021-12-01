$(function () {
    // 編集ボタンクリック
    $(document).on('click', '.friend-edit', function () {
        let name = $(this).prev().prev().html();
        let editField = $(this).parent().next();
        $(this).closest('.friend-box').hide();
        editField.removeClass('d-none').addClass('d-flex');
        editField.find('input[name="name"]').val(name);
    })
    // 削除ボタンクリック
    $(document).on('click', '.friend-trash', function () {
        let $this = $(this);
        let name = $this.prev().html();
        if (confirm(name + ' を削除しますか？')) {
            let url = 'friend/delete/' + $this.parent().parent().attr('id');
            postAjax(url, {})
                .done(data => {
                    $this.closest('.friend-box').remove();
                    $this.closest('.friend-box').next().remove();
                    showToastMessage(data.result, data.message);
                })
                .fail(data => {
                    alert('エラーが発生しました')
                })
        }
    })
    // 更新ボタンクリック
    $(document).on('click', '.update-icon', function () {
        let name = $(this).prev().val();
        let url = 'friend/update/' + $(this).parent().parent().attr('id');
        if (name === '') {
            alert('名前を入力して下さい');
            return false;
        }
        postAjax(url, {name: name})
            .done(data => {
                $(this).parent().removeClass('d-flex').addClass('d-none');
                $(this).parent().prev().show().find('.friend-name').html(name);
                showToastMessage(data.result, data.message);
            })
            .fail(data => {
                alert('エラーが発生しました');
            });
    })

    $('#add-friend-container').on('click', function () {
        let html = `
                <div class="friend-container" id="">
                    <p class="friend-box mb-3 border-b-2 d-none">
                        <span class="friend-name"></span>
                        <span class="float-right text-danger ml-1 friend-trash" style="height: 29px;">
                            <i class="fas fa-trash cursor-pointer fa-sm" style="line-height: 29px;"></i>
                        </span>
                        <span class="float-right text-success friend-edit" style="height: 29px;">
                            <i class="fas fa-edit cursor-pointer fa-sm" style="line-height: 29px;"></i>
                        </span>
                    </p>
                    <p class="d-flex friend-input mb-3">
                        <input name="name" class="form-control form-control-sm" value="" style="width: 85%;">
                        <i class="fas fa-check-circle my-auto ml-1 cursor-pointer text-success store-icon"></i>
                        <i class="fas fa-times my-auto ml-1 cursor-pointer text-danger cancel-icon"></i>
                    </p>
                </div>`;
        $('#friends-container').append(html);
    })

    $(document).on('click', '.store-icon', function () {
        let name = $(this).prev().val();
        let url = 'friend/store';
        if (name === '') {
            alert('名前を入力して下さい');
            return false;
        }
        postAjax(url, {name: name})
            .done(data => {
                showToastMessage(data.result, data.message);
                location.reload();
            })
            .fail(data => {
                alert('エラーが発生しました');
            });
    })

    $(document).on('click', '.cancel-icon', function () {
        $(this).closest('.friend-container').remove();
    })
})