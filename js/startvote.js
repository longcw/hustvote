$(document).ready(function(){
    
    //实例化编辑器
    UM.getEditor('umeditor_intro');
    var detailEditor = [];
    var choice_count = $(".choice").size();
    
    $(".has-detail").each(function(){
        var choice = $(this).parent().parent();
        var id = choice.find(".umeditor-detail").attr("id");
        detailEditor[id] = UM.getEditor(id);
    });
    
    //提交
    $('#startForm').submit(function(e) {
        e.preventDefault();
        $('.textarea').each(function() {
            var id = $(this).find('.umeditor-detail').attr('id');
            var content = detailEditor[id].getContent();
            $(this).find('.detail-content').text(content);
        });
        this.submit();
    });

    //显示选项细节输入框
    $(".has-detail").die().live("click", function() {
        var choice = $(this).parent().parent();
        if($(this).html() === '详情') {
            choice.find(".textarea").css("display", "block");
            $(this).html('隐藏');
        } else {
            choice.find(".textarea").css("display", "none");
            $(this).html('详情');
        }
    });
    
    $(".del-choice").die().live("click", function() {
       var choice = $(this).parent().parent().parent();
       var index = choice.find(".choice-index").html();
       var indexs = choice.parent().find(".choice-index");
       indexs.each(function() {
           if($(this).html() > index) {
               $(this).html(parseInt($(this).html())-1);
           }
       });
       choice.remove();
    });
    
    //添加选项
    $(".choice-add").on("click", function() {
        var count = $(".choice").size();
        var plus = '<div class="control-group">'+
                            '<!-- 选项-->'+
                            '<div class="controls">'+
                                '<div class="input-prepend input-append">'+
                                    '<span class="add-on choice-index">' + (count+1) + '</span>'+
                                    '<input class="span3 choice" placeholder="选项描述" name="choice[]" type="text">'+
                                    '<button class="btn has-detail" type="button">详情</button>'+
                                    '<button class="btn del-choice"><i class="icon-trash"></i>删除</button>'+
                                '</div><div class="textarea" style="display: none">'+
                                    '<script id="ume'+ choice_count + '" class="umeditor-detail" type="text/plain" style="height:150px;"></script>'+
                                    '<textarea class="detail-content" name="detail[]" style="display:none"></textarea>' +
                                '</div></div></div>';
        $(".choice-end").before(plus);
        id = 'ume' + choice_count;
        detailEditor[id] = UM.getEditor(id);
        choice_count++;
    });
    
    //日期
    $('.date').datetimepicker({
	//timepicker:false,
	format:' Y-m-d H:i',
	 minDate:'+2000/01/01',
	 maxDate:'+2040/01/14'
    });
    
    //选择时间
    $('.start-select').on('click', function() {
        if($(this).attr('value') == 1) {
            $('#start-time').css('display', 'block');
        } else {
            $('#start-time').css('display', 'none');
        }
        
    });
    $('.end-select').on('click', function() {
        if($(this).attr('value') == 1) {
            $('#end-time').css('display', 'block');
        } else {
            $('#end-time').css('display', 'none');
        }
        
    });
});
