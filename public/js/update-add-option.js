$(document).on('click', '#add_previous', addOptionRow); // フォーカス追加
$(document).on('click', '#delete_previous', clickDeleteItem) // 著者削除

$(document).on('click', '#add_next', addOptionRow); // フォーカス追加
$(document).on('click', '#delete_next', clickDeleteItem) // 著者削除

$(document).on('click', '#add_book', addOptionRow); // フォーカス追加
$(document).on('click', '#delete_book', clickDeleteItem) // 著者削除

function addOptionRow(e) {
    e.preventDefault(); // イベントのデフォルトの動作を防止

    let targetName = '';
    switch (e.target.id) {
        case 'add_previous':
            targetName = 'previous';
            break;
        case 'add_next':
            targetName = 'next';
            break;
        case 'add_book':
            targetName = 'book';
            break;
        default:
            return;
    }

    // オプションエレメントをクローンする
    let $clone = getCloneOption(targetName + '_select_ary');

    // 追加先の要素を取得
    let $targetElement = $(e.target).closest('.Form-Item');
    if ($targetElement.next().length > 0) {
        $targetElement.next().after($clone);
    } else {
        $targetElement.after($clone);
    }
}

function getCloneOption(id) {
    // クローンするオプションエレメントを取得
    let $optionRow = $('#' + id).html();

    // クローンを作成
    let $clone = $('<div class="Form-Item">' + $optionRow + '</div>');

    return $clone;
}

function clickDeleteItem(e) {
    $(e.target).closest('.Form-Item').remove();
}