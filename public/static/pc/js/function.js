$('div.code').each(function(index, element) {
    var el = $(element);
    el.append('<div class="codetool" id="codetool"><a href="javascript:;" class="selall" title="すべて選択"></a><a href="javascript:;" class="copy" title="コピー"></a><div class="code_n"></div></div>');
    var jb51Input = document.createElement('textarea');
    el.find('.code_n')[0].appendChild(jb51Input);
    //console.log(el.find('pre')[0].innerText);
    var jb51yy=el.find('pre').attr("class");
    var jb51yyarr;
    if(jb51yy!=""){
        jb51yyarr=jb51yy.split(";")
        jb51yy=jb51yyarr[0];
        jb51yy=jb51yy.replace("brush:","");
        jb51yy=jb51yy.replace(";","");
        switch(jb51yy) {
            case 'py':
                jb51yy='python';
                break;
            case 'vb':
                if("undefined" != typeof lmname){
                    if(lmname=="vbs"){
                        jb51yy='vbscript';
                    }else if(lmname=="vb"){
                        jb51yy='vb';
                    }else if(lmname=="ASP编程"){
                        jb51yy='asp';
                    }else{
                        jb51yy='vb';
                    }
                }
                break;
            case 'js':
                jb51yy='javascript';
                break;
            case 'cpp':
                jb51yy='c++';
                break;
            case 'ps':
                if("undefined" != typeof lmname && lmname=="PowerShell"){
                    jb51yy='powerShell';
                }else{
                    jb51yy='bat';
                }
                break;
            case 'plain':
                jb51yy='テキスト';
                break;
            case 'xhtml':
                jb51yy='html';
                break;
            default:
        }
        //el.find('input.Jb51yuyan')[0].value = jb51yy;
        el.find('.selall').attr("title","すべて選択");
        el.find('.copy').attr("title","コピー"+jb51yy+"コード");
    }
    jb51Input.value = el.find('pre')[0].innerText;
    el.find('.selall')[0].onclick = function() {
        selall(el.find('.container')[0]);
    }
    el.find('.copy')[0].onclick = function() {
        //copy(el.find('.container')[0],el.find('.code_n')[0]);
        copy2(jb51Input);
    }
});
$('div.codetitle').each(function(index, element) {
    var el = $(element);
    el.find('.copybut')[0].onclick = function() {
        copy(el.next()[0],el[0]);
    }
});
$('div.msgheader').each(function(index, element) {
    var el = $(element);
    el.find('.copybut')[0].onclick = function() {
        copy(el.next()[0],el[0]);
    }
});
$("#navCategory").find("a").click(function(){
    var navA= $(this).attr("href").substring(1);
    var t = $("a[name='"+navA+"']").offset().top;
    console.log(t);
    $(window).scrollTop(t);
});

function selall(e) {
    var range = window.getSelection ? window.getSelection() : document.selection.createRange();
    var ni = e.childNodes.length;
    range.removeAllRanges();
    range.selectAllChildren(e);
    return range
}
function copy(e, c) {
    var jb51Input = document.createElement('textarea');
    jb51Input.style.opacity	=0;
    jb51Input.style.position="absolute";
    jb51Input.style.left="100%";
    c.appendChild(jb51Input);
    var range = selall(e);
    //console.log(range);
    jb51Input.value = e.innerText; //range.toString();
    range.removeAllRanges();
    //console.log($(e).html());
    jb51Input.select();
    if (document.execCommand('copy')){
        showot("コピーしました");
        return true;
    }
    c.removeChild(jb51Input);
}
function copy2(e){
    if(typeof(e)=='string')e=document.getElementById(e);
    e.select();
    if (document.execCommand('copy')){
        showot("コピーしました");
        return true;
    }
    if (e.setSelectionRange) { // 标准浏览器
        e.setSelectionRange(0, 0)
    } else { // IE9-
        var range = e.createTextRange()
        range.moveEnd("character", 0)
    }
}
function showot(s){
    var st=document.createElement('span');
    st.className="jb51Tips";
    st.innerHTML=s;
    document.body.appendChild(st);
    setTimeout(function(){
        document.body.removeChild(st);
    },500)
    //st.setAttribute('style','')
}

$('#content').find('.code').hover(function (){
    $(this).find('.codetool').show();
},function (){
    $(this).find('.codetool').hide();
});

function getid(objectId) {
    if(document.getElementById && document.getElementById(objectId)) {
        return document.getElementById(objectId);
    }
    else if (document.all && document.all(objectId)) {
        return document.all(objectId);
    }
    else if (document.layers && document.layers[objectId]) {
        return document.layers[objectId];
    }
    else {
        return false;
    }
}
function copyIdText(id)
{
    Dcopy( getid(id).innerText,getid(id) );
}
function copyIdHtml(id)
{
    Dcopy( getid(id).innerHTML,getid(id) );
}
function Dcopy(txt,obj)
{
    if(window.clipboardData)
    {
        window.clipboardData.clearData();
        window.clipboardData.setData("Text", txt);
        alert("コピーしました！")
        if(obj.style.display != 'none'){
            var rng = document.body.createTextRange();
            rng.moveToElementText(obj);
            rng.scrollIntoView();
            rng.select();
            rng.collapse(false);
        }
    }
    else
        alert("テキストを選択し、Ctrl + Cを使用してコピーしてください!");
}
