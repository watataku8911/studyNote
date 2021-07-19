//文字を一文字ずつ表示
$(function(){
    var setElm = $('.split'),
    delaySpeed = 200,
    fadeSpeed = 0;
 
    setText = setElm.html();
 
    setElm.css({visibility:'visible'}).children().addBack().contents().each(function(){
        var elmThis = $(this);
        if (this.nodeType == 3) {
            var $this = $(this);
            $this.replaceWith($this.text().replace(/(\S)/g, '<span class="textSplitLoad">$&</span>'));
        }
    });
    $(window).load(function(){
        splitLength = $('.textSplitLoad').length;
        setElm.find('.textSplitLoad').each(function(i){
            splitThis = $(this);
            splitTxt = splitThis.text();
            splitThis.delay(i*(delaySpeed)).css({display:'inline-block',opacity:'0'}).animate({opacity:'1'},fadeSpeed);
        });
        setTimeout(function(){
                setElm.html(setText);
        },splitLength*delaySpeed+fadeSpeed);
        setTimeout(function(){
                setElm.fadeOut();
        },splitLength*delaySpeed+fadeSpeed);
        setTimeout(function(){
                $('#contents p').fadeIn();
                $('#navi').fadeIn();
                $('h1').fadeIn();
                $('p').fadeIn();
                $('#sub_contents').fadeIn();
        },splitLength*delaySpeed+fadeSpeed+800);
    });
});


