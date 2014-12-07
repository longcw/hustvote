$(document).ready(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });

    $('.pinned').pin({
        minWidth: 940
    });

    var choice_max = parseInt($('#choice-max').html());
    var choice_count = $('.choice:checked').size();

    $('.choice').on('ifChecked', function () {
        choice_count++;
        if (choice_count >= choice_max) {
            //disable
            $('.choice').not('input:checked').iCheck('disable');
        }
        if(choice_count === 1) {
            $('#selected').css('display', 'block');
        }
        
        var title = $(this).parent().parent().find('.title').html();
        var cid = $(this).attr('id');
        var plus = '<li id="s' + cid + '"><a href="#' + cid + '">' + title + '</a></li>';
        $('#select-end').before(plus);
    });

    $('.choice').on('ifUnchecked', function () {
        choice_count--;
        if (choice_count < choice_max) {
            //disable
            $('.choice').not('input:checked').iCheck('enable');
            var cid = $(this).attr('id');
            $('#s'+cid).remove();
        }
        if(choice_count <= 0) {
            $('#selected').css('display', 'none');
        }
    });
    
    //提交
    $('#submit-btn').click(function() {
       if(choice_count === 0) {
           alert('请至少选择一个选项');
       } else {
           var fingerprint = new Fingerprint().get();
           $('#fingerprint').val(fingerprint.toString());
           $('#joinForm').submit();
       }
    });
});