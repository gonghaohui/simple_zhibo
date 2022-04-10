$('body').click(function(e) {
    var target = $(e.target);
    if(!target.is('#comment_form *') && !target.is('.bt-comment-show') && !target.is('#commentEditBox *')) {
        if(!target.is('.btn-reply')){
            if($("#commentEditBox").size()>0){
                $("#commentEditBox").remove();
            }
        }

        if ( $('.opt-box').is(':visible') ) {
            $('.bt-comment-show').show();
            $('#comment_form .comment-content').removeClass('open');
            $('.opt-box').hide();
        }
    }else{
        if(!target.is('#commentEditBox *')){
            if($("#commentEditBox").size()>0){
                $("#commentEditBox").remove();
            }

        }

        //show first comment
        if ( $('.opt-box').is(':hidden') && !target.is('#commentEditBox *')) {
            $('.bt-comment-show').hide();
            $('#comment_form .comment-content').addClass('open');
            $('.opt-box').show();
        }
    }

});

// Firefox, Google Chrome, Opera, Safari, Internet Explorer from version 9
function OnInput (event) {
    if($(event.target).attr("id") == 'comment_content'){
        $('#tip_comment em').text(1000 - Number(event.target.value.length));
    }else{
        $('#new_tip_comment em').text(1000 - Number(event.target.value.length));
    }

}

// Internet Explorer
function OnPropChanged (event) {
    if($(event.target).attr("id") == 'comment_content'){
        $('#tip_comment em').text(1000 - Number(event.target.value.length));
    }else{
        $('#new_tip_comment em').text(1000 - Number(event.target.value.length));
    }
}

$(".new-opt-floating").on("click",".btn-reply",function(){
    if ( $('.opt-box').is(':visible') ) {
        $('.bt-comment-show').show();
        $('#comment_form .comment-content').removeClass('open');
        $('.opt-box').hide();
    }

    if($("#commentEditBox").size()>0){
        $("#commentEditBox").remove();
    }

    var name = $(this).parent().siblings('.name').text();
    var article_id = $("#article_id").val();
    var comment_id = $(this).data("commentid");

    // <div class="left-box">
    //         <span>名前</span>:<input name="ip_address" type="text" placeholder="{$ip_address}" value="">
    //         </div>


    var comment_form = '<div class="comment-edit-box d-flex" id="commentEditBox">';
    comment_form = comment_form + '<form id="commentformNew">';
    comment_form = comment_form + '<textarea oninput="OnInput(event)" onpropertychange="OnPropChanged(event)" class="comment-content open" name="comment_content" id="comment_contentNew" placeholder="返事：'+ name +'" maxlength="1000"></textarea>';
    comment_form = comment_form + '<div class="opt-box-new">';
    comment_form = comment_form + '<input type="hidden" name="article_id" value="'+ article_id +'">';
    comment_form = comment_form + '<input type="hidden" name="comment_id" value="'+ comment_id +'">';
    comment_form = comment_form + '<input type="hidden" name="reply_name" value="'+ name +'">';

    comment_form = comment_form + '<div class="left-box"><span>名前:</span><input name="ip_address" type="text" placeholder="{$ip_address}" value=""></div>';

    comment_form = comment_form + '<div class="right-box-new">';
    comment_form = comment_form + '<span id="new_tip_comment" class="tip">You can still enter <em>1000</em> characters</span>';
    comment_form = comment_form + '<input type="button" class="btn btn-sm btn-cancel" id="closeNew" value="キャンセル">';
    comment_form = comment_form + '<input type="submit" class="btn btn-sm btn-comment" value="コメント">';
    comment_form = comment_form + '</div></div></form></div>';

    $(this).parent().parent().parent().parent().after(comment_form);

});



//send
$(document).on("click",".btn-comment,#closeNew",function(){
    var close_button = $(this).attr("id");
    if(close_button == 'closeNew'){
        //cancel
        $("#commentEditBox").remove();
        return false;
    }else{
        var form_id = $(this).parent().parent().parent().attr("id");
        if(form_id == 'comment_form'){
            //comment
            var content = $("#"+form_id).children("#comment_content").val();
            if(content == '')
            {
                alert("コメントを空にすることはできません");
                return false;
            }
            // ajax_post_comment(form_id);

        }else{
            //reply
            var content = $("#"+form_id).children("#comment_contentNew").val();
            if(content == '')
            {
                alert("返信を空にすることはできません");
                return false;
            }
        }
        ajax_post_comment(form_id);
        // alert(content);
        return false;


    }

});