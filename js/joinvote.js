$(document).ready(function () {
    var vid = $('#start_voteid').value;
    
    //检测投票权限
    var vote_error = $('#vote-error').attr('value').toString();
    if (vote_error !== 'modal-none') {
        $('#' + vote_error).modal('show');
        $('input').iCheck('disable');
    }

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });

    $('.pinned').pin({
        minWidth: 800
    });

    var choice_max = parseInt($('#choice-max').html());
    var choice_count = $('.choice:checked').size();

    //弹出选项详情
    $('.title').die().live('click', function (e) {
        e.preventDefault();
        $('#choice-modal-title').html($(this).html());
        var cid = $(this).attr('cid');
        var string = 'cid=' + cid;
        $.ajax(
                {
                    url: "../choice",
                    async: true,
                    method: "get",
                    data: string,
                    dataType: "json",
                    success: function (data) {
                        if (data.status === true) {
                            $('#choice-modal-body').html(data.choice_intro);
                        }
                    }

                });

//        var select_button = $('#select-button')
//        if($('#c'+cid).parent().hasClass('checked')) {
//            select_button.css('display:block');
//            select_button.html('取消选中');
//        } else {
//            if(choice_max <= choice_count) {
//                //选满
//                select_button.css('display:none');
//            } else {
//                select_button.css('display:block');
//                select_button.html('选中');
//            }
//        }
        $('#modal-choice').modal();
    });

    $('.choice').on('ifChecked', function () {
        if (choice_count >= choice_max) {
            return;
        }
        choice_count++;
        if (choice_count >= choice_max) {
            //disable
            $('.choice').not('input:checked').iCheck('disable');
        }
        if (choice_count === 1) {
            $('#selected').css('display', 'block');
        }

        var title = $(this).parent().parent().find('.title').html();
        var cid = $(this).attr('id');
        var plus = '<li id="s' + cid + '"><a href="#" class="title" cid="' + cid.toString().substr(1) + '">' + title + '</a></li>';
        $('#select-end').before(plus);
    });

    $('.choice').on('ifUnchecked', function () {
        choice_count--;
        if (choice_count < choice_max) {
            //disable
            $('.choice').not('input:checked').iCheck('enable');
            var cid = $(this).attr('id');
            $('#s' + cid).remove();
        }
        if (choice_count <= 0) {
            $('#selected').css('display', 'none');
        }
    });

    //提交
    $('#submit-btn').click(function () {
        if (choice_count === 0) {
            alert('请至少选择一个选项');
        } else {
            var fingerprint = new Fingerprint().get();
            $('#fingerprint').val(fingerprint.toString());
            $('#modal-confirm').modal();
        }
    });

    $('#confirm-button').click(function () {
        if (choice_count === 0) {
            alert('请至少选择一个选项');
        } else {
            $('#joinForm').submit();
        }

    });
    
    
});