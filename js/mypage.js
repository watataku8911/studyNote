//自動submit
$(function(){
  $("#usage2select1").change(function(){
    $("#submit_form").submit();
  });
});


//pagetop
$(document).ready(function() {
    // hide #back-top first
    $("#back-top").hide();
    // fade in #back-top
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 150) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').stop(true, true).fadeOut();
            }
        });
        // scroll body to 0px on click
        $('#back-top a').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
});

//メッセージ
$(function() {
	$("#messages").css("display","none");
	$("#messages")
		.fadeIn().delay(3000).fadeOut(1000);
});

//時計
 function clock()
{
  // 数字が 1ケタのとき、「0」を加えて 2ケタにする
  var twoDigit =function(num){
    　     var digit
         if( num < 10 )
          { digit = "0" + num; }
         else { digit = num; }
         return digit;
   }

 // 現在日時を表すインスタンスを取得
  var now = new Date();

    var year = now.getFullYear();
    var month = twoDigit(now.getMonth() + 1)
    var day = twoDigit(now.getDate());
    var hour = twoDigit(now.getHours());
    var minute = twoDigit(now.getMinutes());
    var second = twoDigit(now.getSeconds());
 //　HTML: <div id="clock_date">(ココの日付文字列を書き換え)</div>
document.getElementById("clock_date").textContent =  year + "年" + month + "月" + day + "日";

//　HTML: <div id="clock_time">(ココの時刻文字列を書き換え)</div>
document.getElementById("clock_time").textContent = hour + "時" + minute + "分" + second + "秒";

}
// 上記のclock関数を1000ミリ秒ごと(毎秒)に実行
setInterval(clock, 1000);

//ツールチップ
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

