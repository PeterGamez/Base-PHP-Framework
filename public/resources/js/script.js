(function ($) {
    /* form submit and disable button */
    $('form').submit(function () {
        $('button[type=submit]').prop('disabled', true);
        $('button[type=submit]').text('กำลังบันทึก');
    });
})(jQuery);