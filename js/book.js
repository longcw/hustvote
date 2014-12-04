var oHall=$("#hall");
var hallLabel=$("#hall-label");
var oRoom=$("#room");
var roomLabel=$("#room-label");
var info=$(".select-info:eq(0)");
$('#date').datetimepicker({
	timepicker:false,
	format:' Y-m-d ',
	 minDate:'-1970/01/01',
	 maxDate:'+1970/01/14'
});
$('#time').datetimepicker({
	datepicker:false,
	format:' H:i ',
	allowTimes:[
	'10:30', '11:00', '11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00',
	'16:30','17:00','17:30',  
	'18:00', '18:30', '19:00', '19:30', '20:00','20:30','21:00','21:30','22:00','22:30','23:00'
	]
});
hallLabel.click(function(){
	oHall.attr("checked",true);
	oRoom.attr("checked",false);
	hallLabel.css({"backgroundPositionX": -91+"px"})
	hallLabel.css({"backgroundPositionY": 0})
	roomLabel.css({"backgroundPositionX": -180+"px"})
	roomLabel.css({"backgroundPositionY": -114+"px"})
	info.html("已选择【<strong>大厅</strong>】");
});
roomLabel.click(function(){
	oHall.attr("checked",false);
	oRoom.attr("checked",true);
	roomLabel.css({"backgroundPositionX": -272+"px"})
	roomLabel.css({"backgroundPositionY": 0})
	hallLabel.css({"backgroundPositionX": -1+"px"})
	hallLabel.css({"backgroundPositionY": -114+"px"})
	info.html("已选择【<strong>包间</strong>】");
});
$("#date , #time , #number , .contact ,.phone-number").focus(function(){
	this.style.borderColor="#ff9600";
});
$("#date , #time , #number , .contact ,.phone-number").blur(function(){
	this.style.borderColor="#CCC";
});

var oDate = new Date(); 
year = oDate.getFullYear(); 
month= oDate.getMonth()+1;
if (month<10) {
	month="0"+month;
}
date=oDate.getDate();
if (date<10) {
	date="0"+date;
}
hour=oDate.getHours();
minute=oDate.getMinutes();
if (minute<30){
	minute=30;
}else{
	minute="00";
	if (hour==24) {
		hour=0;
	}else{
		hour++;
	}
}
oTime=hour+":"+minute;
$("#date").val(year+"-"+month+"-"+date);
$("#time").val(oTime);
function isTel(str){
	var phoneReg=/^1[3|4|5|8][0-9]\d{4,8}$/;
	return phoneReg.test(str);
}
$(".sub-btn").click(function(e){
e.preventDefault();
	if (!($("#room").attr("checked") || $("#hall").attr("checked"))) {
		
		alert("未选择就餐类型");
	}else if(!$("#number").val()){
		
		alert("请输入4人以上的就餐人数");
	}else if(!$(".contact").val()){
		
		alert("请输入联系人姓名");
	}else if (!$("input[name='sex']:checked").get(0)){
		
		alert("请输入联系人性别");
	}else if(!$(".phone-number").val()){
		
		alert("请输入手机号码");
	}else if(!isTel($(".phone-number").val())){
		
		alert("请输入正确的手机号码")
	}else{
$("#booking").submit();
}
});
$(".cancel-btn").click(function(){
	$("#booking").get(0).reset();
});


