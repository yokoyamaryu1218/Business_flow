let $previousAddedClones = [];
let previousValues = {};

$(document).on('click', '#add_previous', addOptionRow); // 行追加
$(document).on('click', '.deleteButton', clickDeleteItem); // 行削除

function addOptionRow(e) {
    e.preventDefault(); // イベントのデフォルトの動作を防止

    // 現在の入力値を保存します。
    $(".previous-procedures .Form-Item").each(function (index) {
        let $select = $(this).find("select");
        if ($select.length > 0) {
            previousValues[index] = $select.val();
        }
    });

    let targetName = '';
    switch (e.target.id) {
        case 'add_previous':
            targetName = 'previous';
            break;
        default:
            return;
    }

    let $clone = getCloneOption('next_procedures_select_ary', targetName === 'previous');
    let $previousProcedures = $('.previous-procedures');

    let newStepNumber = $previousProcedures.find('.Form-Item').length + 1;
    $clone.find('.Form-Item-Label').html('手順' + newStepNumber);

    // 直前の行の入力値を複製
    let $previousStep = $previousProcedures.find('.Form-Item').last();
    if ($previousStep.length > 0) {
        $previousStep.find('input, textarea').each(function () {
            let $input = $(this);
            let inputValue = $input.val();
            $clone.find('input[name="' + $input.attr('name') + '"]').val(inputValue);
        });
    }

    $previousProcedures.append($clone);
    $previousAddedClones.push($clone);

    if ($previousAddedClones.length > 1) {
        for (let i = 0; i < $previousAddedClones.length - 1; i++) {
            $previousAddedClones[i].find('select').replaceWith(getCloneOption(targetName + '_select_ary', targetName === 'previous').find('select'));
        }
    }

    resetStepNames();
}

function createNewRow(id, isNext) {
    let $optionRow = $('#' + id);
    let newIndex = $('.previous-procedures .Form-Item').length + 1;

    // 新しい行を生成
    let $newRow = $('<div class="Form-Item"></div>');
    $newRow.html($optionRow.html());

    if (isNext) {
        $newRow.find('select[name^="previous_procedure_id"]').attr('name', 'previous_procedure_id[' + newIndex + ']');
    }

    // 選択値の保持
    $newRow.find('select').each(function () {
        let $select = $(this);
        let selectedValue = $select.val();
        $select.data('selectedValue', selectedValue);
    });

    // 入力値の復元
    $newRow.find('select').each(function () {
        let $select = $(this);
        let selectedValue = $select.data('selectedValue');
        $select.val(selectedValue);
    });

    return $newRow;
}

function resetStepNames() {
    let pageType = document.getElementById('pageType').value;

    let $previousProcedures = $('.previous-procedures');
    let $steps = $previousProcedures.find('.Form-Item');

    $steps.each(function (index) {
        let $step = $(this);
        let $label = $step.find('.Form-Item-Label');
        let isFirstStep = (index === 0);
        let isLastStep = (index === $steps.length - 1);
        let stepName;

        if (pageType === 'create') {
            stepName = (isFirstStep) ? '<span class="Form-Item-Label-Required">必須</span>最初の手順' : (isLastStep) ? '最後の手順' : '手順' + (index + 1);
        } else if (pageType === 'edit') {
            stepName = (isFirstStep) ? '最初の手順' : (isLastStep) ? '<span class="Form-Item-Label-Required">編集可</span>最後の手順' : '<span class="Form-Item-Label-Required">編集可</span>手順' + (index + 1);
        } else {
            stepName = (isFirstStep) ? '最初の手順' : (isLastStep) ? '最後の手順' : '手順' + (index + 1);
        }

        // 保存された値がある場合、それを適用します。
        if (previousValues.hasOwnProperty(index)) {
            $step.find("select").val(previousValues[index]);
        }

        $label.html(stepName);
    });

    // 配列の最後の部分を切り替える
    let lastStepIndex = $steps.length - 1;
    let $lastStep = $steps.eq(lastStepIndex);
    let isLastStep = (lastStepIndex === $steps.length - 1);

    if (isLastStep) {
        let $lastSelect = $lastStep.find('select');
        if ($lastSelect.length > 0) {
            let $clone = getCloneOption('next_procedures_select_ary', true);
            $clone.find('select[name="next_procedure_id[]"]').attr('name', 'previous_procedure_id[' + lastStepIndex + ']');
            $lastSelect.replaceWith($clone.find('select'));
        }
    }
}

function getCloneOption(id, isNext) {
    let $optionRow = $('#' + id).html();
    let newIndex = $('.previous-procedures .Form-Item').length;

    let $clone = $('<div class="Form-Item">' + $optionRow + '</div>');

    if (isNext) {
        $clone.find('select[name^="previous_procedure_id"]').attr('name', 'previous_procedure_id[' + newIndex + ']');
    }

    return $clone;
}

function clickDeleteItem(e) {
    e.preventDefault();

    // 現在の入力値を保存します。
    $(".previous-procedures .Form-Item").each(function (index) {
        let $select = $(this).find("select");
        if ($select.length > 0) {
            previousValues[index] = $select.val();
        }
    });

    let $item = $(this).closest('.Form-Item');
    let $container = $item.closest('.Form-Item-Container');

    $item.remove();

    resetStepNames();
}
