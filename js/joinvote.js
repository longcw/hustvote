$(document).ready(function () {
    var vid = $('#start_voteid').val();
    var uid = $('#is-login').html();
    var vote_uid = $('#vote-uid').html();
    var to_uid = vote_uid;
    var to_token;
    var comment_page = 1;
    var comment_add_count = 0;

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

//    $('.pinned').pin({
//        minWidth: 800
//    });

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


    //真正提交，验证验证码
    $('#confirm-button').click(function () {
        if (choice_count === 0) {
            alert('请至少选择一个选项');
        } else {
            var cpinput = $('#captcha-input');
            if (cpinput.size() > 0) {
                var cphint = $('#cphint');
                cphint.css('display', 'block');
                cphint.html('验证中...');
                $.ajax(
                        {
                            url: "../../home/captcha_test",
                            async: true,
                            method: "get",
                            data: 'captcha=' + cpinput.val(),
                            dataType: "json",
                            success: function (status) {
                                if (status !== true) {
                                    cphint.html('验证码错误');
                                } else {
                                    $('#captcha').val(cpinput.val());
                                    $('#joinForm').submit();
                                }
                            }

                        });
            } else {
                $('#joinForm').submit();
            }

        }

    });

    //发表评论
    $('#comment-button').on('click', function (e){
        e.preventDefault();
        if (uid === '0') {
            alert('请先登录');
            return;
        }
        var content = $('#comment-content').val().toString();
        if (content.length === 0) {
            alert('还是写点什么吧');
            return;
        }
        if (content.indexOf(to_token) < 0) {
            to_uid = vote_uid;
        }

        var str = 'content=' + content + '&vid=' + vid + '&to_uid=' + to_uid;
        $.ajax(
                {
                    url: "../doAddComment",
                    async: true,
                    type: "post",
                    data: str,
                    dataType: "json",
                    success: function (data) {
                        if (data.status !== true) {
                            alert(data.msg);
                        } else {
                            $('#comment-head').after(data.str);
                            $('#comment-content').val('');
                            var comment_count = $('#comment-count');
                            var count = parseInt(comment_count.html());
                            count++;
                            comment_count.html(count);
                            $('#comment-count-header').html(count + ' 条评论');
                            comment_add_count++;
                        }
                    }

                });

    });

    $('.comment-reply').die().live('click', function (e){
        e.preventDefault();
        to_uid = $(this).attr('from-uid');
        //alert(to_uid)
        var to_name = $(this).attr('nickname');
        to_token = '回复 ' + to_name + '：';
        $('#comment-content').val(to_token);
    });

    $('#next-comment').on('click', function (e) {
        if(comment_page <= 0) {
            return;
        }
        $.ajax(
                {
                    url: "../doGetNextCommentPage/" + vid + '/' + comment_page + '/' + comment_add_count,
                    async: true,
                    type: "get",
                    dataType: "json",
                    success: function (data) {
                        if (data.status !== true) {
                            var button = $('#next-comment');
                            button.html('没有更多评论了');
                            button.addClass('disabled');
                            comment_page = -1;
                        } else {
                            $('#comment-bottom').before(data.comments);
                            comment_page++;
                        }
                    }

                });
    });


});

//验证码
function changeCaptcha() {
    var img = $('#captcha-img');
    var origin = img.attr('src');
    var src = origin.substring(0, origin.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
    img.attr('src', src);
}