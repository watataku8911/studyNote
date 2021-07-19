//「ゴミ箱を空にする」ボタンが押されたらアラートを出す
function push() {
	var message = 'ゴミ箱を空にしますか？';
	if(confirm(message)) {
		location.href = 'php/deleteDust.php';
	} else {
		location.href = 'deleteNotes.php';
	}
}

//自動submit
$(function(){
  $("#delete").change(function(){
    $("#submit").submit();
  });
});

//pagetop
$(document).ready(function(){
    // hide #back-top first
    $("#back-top").hide();
    // fade in #back-top
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
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