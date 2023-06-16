$(document).on('click', '#add_previous', addOptionRow); // 行追加
$(document).on('click', '.deleteButton', clickDeleteItem); // 行削除

function addOptionRow(e) {
    e.preventDefault(); // イベントのデフォルトの動作を防止

    let targetName = '';
    switch (e.target.id) {
        case 'add_previous':
            targetName = 'previous';
            break;
        default:
            return;
    }

    // オプションエレメントをクローンする
    let $clone = getCloneOption(targetName + '_select_ary');

    let $previousProcedures = $('.previous-procedures');
    let $lastStep = $previousProcedures.find('.Form-Item:last-child');

    // 手順番号を+1する
    let newStepNumber = $previousProcedures.find('.Form-Item').length + 1;
    $clone.find('.Form-Item-Label').text('手順' + newStepNumber);

    // 元のプルダウンの値をリセットする
    $clone.find('select[name="previous_procedure_id[]"]').val('');

    // 配列の最後に追加する
    $lastStep.before($clone);

    // 手順の名前を振りなおす
    resetStepNames();
}

function resetStepNames() {
    let $previousProcedures = $('.previous-procedures');
    let $steps = $previousProcedures.find('.Form-Item');

    $steps.each(function (index) {
        let $step = $(this);
        let $label = $step.find('.Form-Item-Label');
        let isFirstStep = (index === 0);
        let isLastStep = (index === $steps.length - 1);
        let stepName = (isFirstStep) ? '最初の手順' : (isLastStep) ? '最後の手順' : '手順' + (index + 1);
        $label.text(stepName);
    });
}

function getCloneOption(id) {
    // クローンするオプションエレメントを取得
    let $optionRow = $('#' + id).html();

    // クローンを作成
    let $clone = $('<div class="Form-Item">' + $optionRow + '</div>');

    // 新しいインデックスを割り当てる
    let newIndex = $('.previous-procedures .Form-Item').length;
    $clone.find('select[name="previous_procedure_id[]"]').attr('name', 'previous_procedure_id[' + newIndex + ']');

    return $clone;
}

function clickDeleteItem(e) {
    let $item = $(e.target).closest('.Form-Item');
    let $previousProcedures = $('.previous-procedures');
    $item.remove();

    // 手順の名前を振りなおす
    resetStepNames();
}