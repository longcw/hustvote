$(document).ready(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    
    //邀请码
    $('#code-need').on('ifChecked', function() {
        $('#limit').css('display', 'none');
    });
    $('#code-need').on('ifUnchecked', function() {
        $('#limit').css('display', 'block');
    });
    
    //验证邮箱
    $('#email-need').on('ifChecked', function() {
        $('#email-limit-block').css('display', 'block');
    });
    $('#email-need').on('ifUnchecked', function() {
        $('#email-limit-block').css('display', 'none');
    });
    
    //限制邮箱域名
    $('#email-limit').on('ifChecked', function() {
        $('#email-area-block').css('display', 'block');
    });
    $('#email-limit').on('ifUnchecked', function() {
        $('#email-area-block').css('display', 'none');
    });
});