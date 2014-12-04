$(document).ready(function () {

    var $plus = $(".plus");
    var menued = $(".menu-ed");
    $plus.each(function () {
        var number = $(this).parent().find(".already");
        $(this).on("click", function (index) {

            $(this).css("position", "relative").animate({top: '-60px', opacity: '0'}, 400, function () {
                $(this).css({top: 0, opacity: 1});
            });
            var count = number.html(),
                    content = $(this).parent().html(),
                    thisValue = $(this).parent().val();
            count++;
            number.css("visibility", "visible").html(count);
            $(this).parent().attr("myNumber", count);
            if (count == 1) {
                var plus = "<li class='menu-item f12' value='" + thisValue + "' myNumber='" + count + "'>" + content + "</li>"

                $(".no-menu").css("display", "none");
                $(".menu-submit-holder").before(plus);
            } else {
                menued.find("[value=" + thisValue + "]").attr("myNumber", count);
            }

            menued.find("[value=" + thisValue + "] .already").html(count);
            menued.find(".plus").removeClass("plus").addClass("minus");
            menued.find(".already").css("visibility", "visible");
            var menuedName = menued.find(".menu-name");
            menuedName.each(function () {
                var thisName = $(this).html();
                $(this).attr("title", thisName);
            });
            var minus = $(".minus");
            minus.unbind("click");
            minus.bind("click", function () {

                $(this).css("position", "relative").animate({top: '-60px', opacity: '0'}, 400, function () {
                    $(this).css({top: 0, opacity: 1});
                });
                var count1 = $(this).parent().attr("myNumber");
                count1--;
                $(this).parent().find(".already").html(count1);
                var thisValue1 = $(this).parent().val();
                var thisM = $(".menu-ing").find("[value=" + thisValue1 + "] .already");
                if (count1 == 0) {
                    $(this).parent().remove();
                    thisM.css("visibility", "hidden");
                } else {
                    $(this).parent().attr("myNumber", count1);
                }
                thisM.html(count1);
                thisM.parent().attr("myNumber", count1);
                if (menued.find(".menu-item").length == 0) {
                    $(".no-menu").css("display", "block");
                }




            });
        });
    });
    $(".menu-submit").on("click", function (e) {

        var item = $(".menu-ed .menu-item");
        var string,
                a = [];
        item.each(function () {
            var value = $(this).attr("value"),
                    number = $(this).attr("myNumber");
            a.push(value + "=" + number);
            string = a.join("&");
        });
        var dhid = $("#dhid").html();
        string += "&" + "dhid=" + dhid;

        if (!item.get(0)) {
            attention("您还没有选择菜品哦");
        } else {
            $(this).html("提交中…");
            $.ajax(
                    {
                        url: "../doOrderMenu/post",
                        async: true,
                        method: "post",
                        data: string,
                        dataType: "json",
                        success: function (data) {
                            //var dataobj = eval("(" + data + ")"); //转换为json对象
                            if (data.status === true) {
                                window.location.href = "../orderaddress";
                            } else {
                                alert(data.intro);
                            }


                        }

                    });
            e.preventDefault();
        }





    });
    var marginTop = menued.offset().top,
            marginLeft = menued.offset().left;
    $(window).scroll(function () {
        var scrollt = document.documentElement.scrollTop + document.body.scrollTop;
        if (scrollt > marginTop + 100) {
            menued.css({position: "fixed", top: 0, left: marginLeft});
        } else {
            menued.attr("style", "");
        }

    });
    function fading() {
        $(".alert").fadeOut(300);
    }
    function attention(str) {

        var alt = $(".alert");
        alt.find(".alert-text").html(str);
        var length = str.length,
                width = length * 16;
        alt.width(width);
        var height = alt.height(),
                width1 = alt.width();

        alt.css({marginTop: (-height - 80) / 2, marginLeft: (-width1 - 80) / 2});



        alt.fadeIn(300);
        setTimeout(fading, 1000);
    }

});
