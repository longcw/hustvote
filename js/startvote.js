$(document).ready(function(){
    //实例化编辑器
    var um_intro = UM.getEditor('umeditor_intro');
    
    var choice_count = $(".choice").size();
    var um_detail = new Array();
    
    $(".has-detail").each(function(){
        if($(this).attr("checked")) {
            var choice = $(this).parent().parent();
            var id = choice.parent().find(".umeditor-detail").attr("id");
            var key = choice.parent().find(".detail-content").attr("id");
            um_detail[key] = UM.getEditor(id);
        }
    });
    
    //提交表单
    $("#startForm").submit(function(e) {
        e.preventDefault();
        var intro_content = um_intro.getContent();
        $("#intro_content").text(intro_content);
        
        for(var key in um_detail) {
            var detail_content = $("#"+key);
            var choice = detail_content.parent().parent();
            if(choice.find(".has-detail").attr("checked")) {
                var detail = um_detail[key].getContent();
                detail_content.text(detail);
            } else {
                detail_content.text("");
            }
        }

        this.submit();
    });
    

    //显示选项细节输入框
    $(".has-detail").die().live("click", function() {
        var choice = $(this).parent().parent();
        if($(this).attr("checked")) {
            var key = choice.parent().find(".detail-content").attr("id");
            if(um_detail[key] === undefined) {
                var id = choice.parent().find(".umeditor-detail").attr("id");
                um_detail[key] = UM.getEditor(id);
            }
            choice.parent().find(".textarea").css("display", "block");
        } else {
            choice.parent().find(".textarea").css("display", "none");
        }
    });
    
    $(".del-choice").die().live("click", function() {
       var choice = $(this).parent().parent().parent();
       var index = choice.find(".choice-index").html();
       var key = choice.find(".detail-content").attr("id");
       delete um_detail[key];
       
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
                                    '<span class="add-on">更多细节 <input type="checkbox" class="has-detail" ></span>'+
                                    '<span class="add-on del-choice"><a><i class="icon-trash"></i>删除</a></span>'+
                                '</div><div class="textarea" style="display: none">'+
                                    '<div id="ume'+ choice_count + '" class="umeditor-detail" style="height:150px;"></div>'+
                                    '<textarea style="display: none" name="detail[]" id="detail'+ choice_count + '"  class="detail-content"></textarea>'+
                                '</div></div></div>';
        $(".choice-end").before(plus);
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
