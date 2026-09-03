/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

/**
 * @deprecated Superseded by `DateTimePickerType` and the Gui DateTimePicker, which initialize and
 *   range-link the fields declaratively. Kept only for installations running spryker/gui older
 *   than 5.4.0.
 */
$(document).ready(function () {
    function getGtmDateTimeString(datetext) {
        var d = new Date();
        d = new Date(d.valueOf() + d.getTimezoneOffset() * 60000);

        var h = d.getHours();
        h = h < 10 ? '0' + h : h;

        var m = d.getMinutes();
        m = m < 10 ? '0' + m : m;

        var s = d.getSeconds();
        s = s < 10 ? '0' + s : s;

        return datetext + ' ' + h + ':' + m + ':' + s;
    }

    var $fromDateTime = $('.js-from-datetime');
    var $toDateTime = $('.js-to-datetime');

    // From spryker/gui 5.4.0 on, these fields are built with `DateTimePickerType`, which marks them
    // with `data-spryker-picker` and lets the Gui DateTimePicker initialize and range-link them.
    // Older Gui versions have no such type, so the legacy picker below is set up instead.
    if ($fromDateTime.is('[data-spryker-picker]')) {
        return;
    }

    $fromDateTime.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        defaultData: 0,
        onSelect: function (datetext) {
            $fromDateTime.val(getGtmDateTimeString(datetext));
        },
    });

    $toDateTime.datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        onSelect: function (datetext) {
            $toDateTime.val(getGtmDateTimeString(datetext));
        },
    });
});
