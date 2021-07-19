//マークダウンをHTMLに変換する
$(function() {
    marked.setOptions({
        langPrefix: ''
    });

    $('#edit').keyup(function() {
        var md = sanitize($(this).val());
        var html = marked(md);
        $('#preview').html(html);
        $('#preview pre code').each(function(i, e) {
            $(e).text(unsanitize($(e).text()));
            hljs.highlightBlock(e, e.className);
        });
    });

});
function sanitize(html) {
    return $('<div />').text(html).html().replace(/&gt;/g, ">");
}
function unsanitize(html) {
    return $('<div />').html(html).text();
}

//textarea内でTabキーでのインデントを可能にする
$('textarea').on('keydown', function(e) {
    // タブ押したかどうか
    if (e.keyCode == 9) {
        var isShift = e.shiftKey; // Shift押されているか
        var elm = e.target;
        var txt = elm.value;
        var slct = {left: elm.selectionStart, right: elm.selectionEnd}; // テキスト選択の開始・終了位置
        if(slct.left == slct.right && !isShift) {
            // テキスト選択されていない場合はカーソル位置にタブ挿入
            elm.value = txt.substr(0, slct.left) + '\t' + txt.substr(slct.left, txt.length);
            // タブが挿入された分テキスト選択の位置がズレるので調整
            slct.left++;
            slct.right++;
        } else {
            // テキスト選択されている場合行頭にタブ追加・Shiftも押してたら行頭のタブを削除
            var lineStart = txt.substr(0, slct.left).split('\n').length - 1; // 開始行
            var lineEnd = txt.substr(0, slct.right).split('\n').length - 1; // 終了行
            var lines = txt.split('\n'); // テキストを行ごとの配列に変換
            for(i = lineStart; i <= lineEnd; i++) {
                // 一行ごとの処理
                if(!isShift) {
                    // 行頭にタブ挿入
                    lines[i] = '\t' + lines[i];
                    // タブが挿入された分テキスト選択の位置がズレるのでry
                    if(i == lineStart) slct.left++;
                    slct.right++;
                } else if(lines[i].substr(0, 1) == '\t') {
                    // 行頭にタブがあるときだけ削除
                    lines[i] = lines[i].substr(1);
                    // タブが挿入された分ry
                    if(i == lineStart) slct.left--;
                    slct.right--;
                }
            }
            // 変換後の配列を文字列に戻してtextareaへ
            elm.value = lines.join('\n');
        }
        // テキスト選択の位置を更新
        elm.setSelectionRange(slct.left, slct.right);
        return false;
    }
});

//マークダウン、スクロール関係
$(function () {
    var $elements = $('textarea#edit, div#preview');

    var sync = function(e){
        console.log('qqqq');
        var $other = $elements.not(this).off('scroll'), other = $other.get(0);
        var percentage = this.scrollTop / (this.scrollHeight - this.offsetHeight);
        other.scrollTop = percentage * (other.scrollHeight - other.offsetHeight);
        setTimeout( function(){ $other.on('scroll', sync ); },10);
    }

$elements.on( 'scroll', sync);
});











