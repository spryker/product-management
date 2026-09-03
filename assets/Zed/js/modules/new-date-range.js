/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @deprecated Superseded by `DatePickerType` and the Gui DateTimePicker, which initialize and
 *   range-link the fields declaratively. Kept only for installations running spryker/gui older
 *   than 5.4.0.
 */
$(document).ready(function () {
    var $fromDate = $('.js-from-date');
    var $toDate = $('.js-to-date');

    if ($fromDate.is('[data-spryker-picker]')) {
        return;
    }

    $fromDate.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        maxDate: $toDate.val(),
        defaultData: 0,
        onClose: function (selectedDate) {
            $toDate.datepicker('option', 'minDate', selectedDate);
        },
    });

    $toDate.datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: $fromDate.val(),
        onClose: function (selectedDate) {
            $fromDate.datepicker('option', 'maxDate', selectedDate);
        },
    });
});
