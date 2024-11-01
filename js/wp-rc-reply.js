/*!
 Author: QiQiBoY
 Update: 2011/02/04
 Author URI: http://www.qiqiboy.com/
 */
/*
 ################################################
 contact me	:	1. imqiqiboy#gmail.com
				2. http://www.qiqiboy.com/contact
				3. http://www.qiqiboy.com/guestbook
 ################################################
 */
;
(function(){

    function $(id){
        return document.getElementById(id)
    }
    function $$(c, t, p){
        var at = p.getElementsByTagName(t);
        var ms = new Array();
        var r = new RegExp('(^|\\s)' + c.replace(/\-/g, '\\-') + '(\\s|$)');
        var e;
        for (var i = 0; i < at.length; i++) {
            e = at[i];
            if (r.test(e.className)) {
                ms.push(e)
            }
        }
        return ms
    }
    function endIt(e){
        if (window.event) {
            window.event.cancelBubble = true;
            window.event.returnValue = false;
            return
        }
        if (e.preventDefault && e.stopPropagation) {
            e.preventDefault();
            e.stopPropagation()
        }
    }
    function addListener(element, name, observer, useCapture){
        if (element.addEventListener) {
            element.addEventListener(name, observer, useCapture)
        }
        else 
            if (element.attachEvent) {
                element.attachEvent('on' + name, observer)
            }
    }
	documentReady=(function(){
		var load_events = [],load_timer,script,done,exec,old_onload,init = function () {done = true;clearInterval(load_timer);while (exec = load_events.shift())exec(); if (script) script.onreadystatechange = '';};
		return function (func) {
			if (done) return func();
			if (!load_events[0]) {
				if (document.addEventListener)
					document.addEventListener("DOMContentLoaded", init, false);
				else if (/MSIE/i.test(navigator.userAgent)){
					document.write("<script id=__rc_ie_onload defer src=//0><\/scr"+"ipt>");
					script = document.getElementById("__rc_ie_onload");
					script.onreadystatechange = function() {
						if (this.readyState == "complete")
							init();
					};
				}else
				if (/WebKit/i.test(navigator.userAgent)) {
					load_timer = setInterval(function() {
						if (/loaded|complete/.test(document.readyState))
							init();
					}, 10);
				}else{
					old_onload = window.onload;
					window.onload = function() {
						init();
						if (old_onload) old_onload();
					};
				}
			}
			load_events.push(func);
		}
	})();
    function createxmlHttp(){
        var xmlHttp;
        try {
            xmlHttp = new XMLHttpRequest()
        } 
        catch (e) {
            try {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")
            } 
            catch (e) {
                try {
                    xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")
                } 
                catch (e) {
                    alert("Your browser does not support ajax!");
                    return false
                }
            }
        }
        return xmlHttp
    }
    function submitshortCut(moz_ev){
        var ev = null;
        if (window.event) {
            ev = window.event
        }
        else {
            ev = moz_ev
        }
        if (ev != null && ev.ctrlKey && ev.keyCode == 13) {
            $('rc_submit').click()
        }
    }
    function removeNode(obj){
        if (typeof obj == "string") 
            $(obj).parentNode.removeChild($(obj));
        else 
            obj.parentNode.removeChild(obj);
    }
    function insertBe(obj, tar){
        tar.parentNode.insertBefore(obj, tar);
    }
    var it, baseurl = "http://" + window.location.host + "/wp-content/";//get your blog url
    var finds = document.getElementsByTagName('link');
    for (var i = 0; i < finds.length; i++) {
        if (finds[i].href.indexOf('wp-content') > 0) {
            baseurl = finds[i].href.substring(0, finds[i].href.indexOf('wp-content') + 11);
            break;
        }
    }
    
    dataJson = new Array(), options = {//the options
        uname: '',//the name, email, url... of the current visitor
        umail: '',
        uurl: '',
        isuser: 0,//the visitor is the admin?
        isrefresh: 1,//show refresh button or no?
		issingle: 1,
		ispost: 0,
        shortcut: 1,//use submit shortcut (ctrl+enter)
        newest: 'newest',//some buttons' name
        newer: 'newer',
        older: 'older',
        refresh: 'refresh',
        back: 'back',
        reply: 'reply',
        navbtn: 1,//show pagenavi?
        number: 8,//the number of the recent comments one page
        onlyadmin: 0,//only admin can reply?
        at: 0,//the depth to add @XXX
        replybtn: 1,//show reply button?
        avaright: 0,//put the avatar right?
        strs: {//some tips
            str1: 'You have already reply to this comment.',
            str2: 'is submiting, please wait...',
            str3: 'ERROR: Please input your name!',
            str4: 'ERROR: Please input a right email!',
            str5: 'ERROR: Please input a comment!',
            str6: 'submit succussed! Thank you.',
            str7: 'Welcome back, ',
            str8: 'change ',
            str9: 'cancle submit',
            str0: 'login in as '
        }
    };
    function getOptions(){//get some options from your blog
        var url = '?action=rc_reply_options';
        xmlHttp = createxmlHttp();
        xmlHttp.open("GET", url, true);
        xmlHttp.setRequestHeader("Content-type", "charset=UTF-8");
        xmlHttp.setRequestHeader('Ajax-Request', 'wp-rc-reply-ajax');
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
                if (xmlHttp.status == 200) {
					xmlHttp.responseText.match(/wp-rec-reply-(.*)-wp-rec-reply/);
                    options = eval("(" + RegExp.$1 + ")");
                }
				if ($('wp-rc-reply'))
					loadRc_Comm(0, options.number);
            }
        }
        xmlHttp.send(null);
    }
    documentReady(getOptions);
    function loadRc_Comm(start, number){
        if (start < dataJson.length) {//if the cache has the comments
            var data = '', json = dataJson, end = start + number > json.length ? json.length : start + number;
            for (var i = start; i < end; i++) {
                data += '<li id="rc_com_' + json[i].comId + '" class="rc_comment">';
                if (json[i].avatar != '') 
                    data += '<div class="rc_avatar"' + (options.avaright ? ' style="float:right;"' : '') + '>' + json[i].avatar + '</div>';
                data += '<div class="rc_author"><a id="rc_author_' + json[i].comId + '" href="' + json[i].comlink + '">' + json[i].author + '</a></div>';
                if(options.ispost)data += '<div class="rc_post_title"><a title="'+json[i].post+'" href="'+json[i].comlink+'">'+json[i].post+'</a></div>';
				data += '<div class="rc_excerpt">' +
                json[i].excerpt +
                '<span class="rc_more">';
				if(!options.issingle)data+='<a href="javascript:;" class="rc_more_btn">&raquo;</a>';
                if ((!options.onlyadmin || options.isuser) && options.replybtn) 
                    data += '<a href="javascript:;" class="rc_reply_btn">@</a>';
                data += '</span></div></li>'
            }
            
            if (options.navbtn) {
                data += '<li id="rc_nav" class="rc_comment">';
                if (start > number) {
                    data += '<div id="rc_newest" class="rc_newest"><a href="javascript:;">&laquo;' + options.newest + '</a></div>';
                }
                if (start > 0) {
                    data += '<div id="rc_newer" class="rc_newer"><a href="javascript:;">&laquo;' + options.newer + '</a></div>';
                }
                else 
                    if (options.isrefresh) {
                        data += '<div id="rc_refresh" class="rc_newer"><a href="javascript:;">&laquo;' + options.refresh + '&raquo;</a></div>';
                    }
                if (json.length >= start + number) {
                    data += '<div id="rc_older" class="rc_older"><a href="javascript:;">' + options.older + '&raquo;</a></div>';
                }
                data += '</li>';
            }
            opacityAnimate(300, function(){
                $('wp-rc-reply').innerHTML = data;
                if ($('rc_newest')) 
                    $('rc_newest').onclick = function(){
                        loadRc_Comm(0, number);
                    }
                if ($('rc_newer')) 
                    $('rc_newer').onclick = function(){
                        loadRc_Comm(start - number, number);
                    }
                if ($('rc_refresh')) 
                    $('rc_refresh').onclick = RCCMT;
                if ($('rc_older')) 
                    $('rc_older').onclick = function(){
                        loadRc_Comm(start + number, number);
                    }
                var morebtns = $$('rc_more_btn', 'a', $('wp-rc-reply'));
                for (var i = 0; i < morebtns.length; i++) {
                    (function(){
                        var _i = i;
                        morebtns[_i].onclick = function(){
                            var com = json[start + _i], data = '';
                            data += '<li id="rc_com_' + com.comId + '" class="rc_comment">';
                            if (com.avatar != '') 
                                data += '<div class="rc_avatar"' + (options.avaright ? ' style="float:right;"' : '') + '>' + com.avatar + '</div>';
                            data += '<div class="rc_author"><a id="rc_author_' + com.comId + '" href="' + com.comlink + '">' + com.author + '</a></div>' +
                            '<div class="rc_post"><a href="' +
                            com.comlink.replace(/\/comment-page-[0-9]+|#.*/gi, '') +
                            '">' +
                            com.post +
                            '</a></div><div class="rc_content">' +
                            com.content +
                            '<div class="rc_reply"><a id="rc_reply" href="javascript:;">' +
                            options.reply +
                            '</a></div></div></li>' +
                            '<li id="rc_nav" class="rc_comment"><div id="rc_back" class="rc_newest"><a href="javascript:;">&laquo;' +
                            options.back +
                            '</a></div></li>';
                            opacityAnimate(300, function(){
                                $('wp-rc-reply').innerHTML = data;
                                $('rc_back').onclick = function(){
                                    loadRc_Comm(start, number);
                                }
                                $('rc_reply').onclick = function(){
                                    rc_reply(start + _i);
                                }
                            });
                        }
                    })();
                }
                var replybtns = $$('rc_reply_btn', 'a', $('wp-rc-reply'));
                for (var i = 0; i < replybtns.length; i++) {
                    (function(){
                        var _i = i;
                        replybtns[_i].onclick = function(){
                            rc_reply(start + _i);
                        }
                    })();
                }
            });
            return;
        }
        var url = '?action=rc_reply_comment&start=' + start + '&number=' + number;
        xmlHttp = createxmlHttp();
        xmlHttp.open("GET", url, true);
        xmlHttp.setRequestHeader("Content-type", "charset=UTF-8");
        xmlHttp.setRequestHeader('Ajax-Request', 'wp-rc-reply-ajax');
        if ($('rc_nav') || 0) 
            $('rc_nav').innerHTML = '<DIV style="background:url(' + baseurl + 'plugins/wp-rc-reply-ajax/img/loading.gif) left center no-repeat;padding-left:20px;' + ')" class="newer ajax-loader">Loading...<p></p></DIV>';
        if ($('wp-rc-reply') || 0) 
            $('wp-rc-reply').style.cursor = 'wait';
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
                if (xmlHttp.status == 200) {//successed!
                    var _json;
                    try {
						xmlHttp.responseText.match(/wp-rec-reply-(.*)-wp-rec-reply/);
                        _json = eval("(" + RegExp.$1 + ")");
                    } 
                    catch (e) {
                        alert(e + '<br>error! Please contact imqiqiboy#gmail.com!');
                    }
                    if (_json == undefined || !_json.length) {
                        alert('something error!');
                        return;
                    }
                    dataJson = dataJson.concat(_json);
                    loadRc_Comm(start, number);
                }
                else {
                    $('rc_nav').innerHTML = '<p>Oops, failed to load data. <small><a id="rc_reload" href="javascript:void(0);">[Reload]</a></small></p>';
                    $('rc_reload').onclick = function(){
                        loadRc_Comm(start, number);
                    }
                }
                $('wp-rc-reply').style.cursor = 'auto';
            }
        };
        xmlHttp.send(null)
    }
    function opacityAnimate(time, callback){
        var start = new Date(), val;
        var AnimateTimer = setInterval(function(){
            var now = new Date();
            if (now - start < time) {
                val = 1 - Math.floor(((now - start) / time) * 100) / 100;
                $('wp-rc-reply').style.opacity = val;
                $('wp-rc-reply').style.filter = 'alpha(opacity=' + val * 100 + ')';
            }
            else {
                clearInterval(AnimateTimer);
				if (callback) 
                    callback();
                start = new Date();
                AnimateTimer = setInterval(function(){
                    var now = new Date();
                    if (now - start < time) {
                        val = Math.floor(((now - start) / time) * 100) / 100;
                        $('wp-rc-reply').style.opacity = val;
                        $('wp-rc-reply').style.filter = 'alpha(opacity=' + val * 100 + ')';
                    }
                    else {
                        clearInterval(AnimateTimer);
                        $('wp-rc-reply').style.opacity = 1;
                        $('wp-rc-reply').style.filter = 'alpha(opacity=' + 100 + ')';
                    }
                }, 4);
            }
        }, 4);
    }
    var arr = '';
    function rc_reply(index){
    
        if (arr.indexOf(dataJson[index].comId) > -1) {
            //if have be reply,create div#rc_loading to tell people 'You have already reply to this comment.'
            if ($("rc_loading") || 0) {
                var rc_loading = $("rc_loading");
                rc_loading.style.disply = "block";
            }
            else {
                var rc_loading = document.createElement('div');
                rc_loading.id = "rc_loading";
            }
            rc_loading.innerHTML = options.strs.str1;
            rc_loading.style.background = "#B2F60C";//set background color
            $('rc_com_' + dataJson[index].comId).appendChild(rc_loading);//append node to page
            return;
        }
        
        var rc_respond = document.createElement('div'), addat = '',//the form box to respond
 cancle = '<a id="cancle-reply" class="cancle-reply" href="javascript:;">' + options.strs.str9 + '</a>';
        if (options.at > 0 && options.at <= dataJson[index].comDepth)//add @reply
            addat = '<a rel=\'nofollow\' href=\'#comment-' + dataJson[index].comId + '\'>@' + $('rc_author_' + dataJson[index].comId).innerHTML + '</a>\n';
        rc_respond.id = 'rc_respond';
        if (options.isuser)//is admin?
            rc_respond.innerHTML = '<form id="rc_commentform" method="post" action="' + baseurl.replace("wp-content/", "") + 'wp-comments-post.php">' +
            '<div id="rc_welcome">' +
            options.strs.str0 +
            '<strong>' +
            options.uname +
            '</strong>. &raquo;' +
            '</div><div id="rc_info" style="display:none"><p><input type="text" tabindex="1" size="22" value="' +
            options.uname +
            '" id="rc_author" name="author"><label for="author"><small>Name</small></label></p>' +
            '<p><input type="text" aria-required="true" tabindex="2" size="22" value="' +
            options.umail +
            '" id="rc_email" name="email"><label for="email"><small>Mail</small></label></p>' +
            '<p><input type="text" tabindex="3" size="22" value="' +
            options.uurl +
            '" id="rc_url" name="url"><label for="url"><small>Website</small></label></p>' +
            '</div><p><textarea tabindex="4" rows="5" cols="35" id="rc_comment" name="comment">' +
            addat +
            '</textarea></p>' +
            '<p><input type="submit" value="Submit" tabindex="5" id="rc_submit" name="submit">' +
            cancle +
            '</p>' +
            '</form>';
        else 
            options.uname == '' && options.umail == '' ? rc_respond.innerHTML = '<form id="rc_commentform" method="post" action="' + baseurl.replace("wp-content/", "") + 'wp-comments-post.php">' +
            '<p><input type="text" tabindex="1" size="22" value="' +
            options.uname +
            '" id="rc_author" name="author"><label for="author"><small>Name</small></label></p>' +
            '<p><input type="text" aria-required="true" tabindex="2" size="22" value="' +
            options.umail +
            '" id="rc_email" name="email"><label for="email"><small>Mail</small></label></p>' +
            '<p><input type="text" tabindex="3" size="22" value="' +
            options.uurl +
            '" id="rc_url" name="url"><label for="url"><small>Website</small></label></p>' +
            '<p><textarea tabindex="4" rows="5" cols="35" id="rc_comment" name="comment">' +
            addat +
            '</textarea></p>' +
            '<p><input type="submit" value="Submit" tabindex="5" id="rc_submit" name="submit">' +
            cancle +
            '</p>' +
            '</form>' : rc_respond.innerHTML = '<form id="rc_commentform" method="post" action="' + baseurl.replace("wp-content/", "") + 'wp-comments-post.php">' +
            '<div id="rc_welcome">' +
            options.strs.str7 +
            '<strong>' +
            options.uname +
            '</strong>. <a onclick="if(WIDGET.$(\'rc_info\').style.display==\'none\')WIDGET.$(\'rc_info\').style.display=\'block\';else WIDGET.$(\'rc_info\').style.display=\'none\';" href="javascript:void(0)">' +
            options.strs.str8 +
            ' &raquo;</a>' +
            '</div><div id="rc_info" style="display:none"><p><input type="text" tabindex="1" size="22" value="' +
            options.uname +
            '" id="rc_author" name="author"><label for="author"><small>Name</small></label></p>' +
            '<p><input type="text" aria-required="true" tabindex="2" size="22" value="' +
            options.umail +
            '" id="rc_email" name="email"><label for="email"><small>Mail</small></label></p>' +
            '<p><input type="text" tabindex="3" size="22" value="' +
            options.uurl +
            '" id="rc_url" name="url"><label for="url"><small>Website</small></label></p>' +
            '</div><p><textarea tabindex="4" rows="5" cols="35" id="rc_comment" name="comment">' +
            addat +
            '</textarea></p>' +
            '<p><input type="submit" value="Submit" tabindex="5" id="rc_submit" name="submit">' +
            cancle +
            '</p>' +
            '</form>';
        
        if ($('rc_respond') || 0) {
            removeNode('rc_respond');
        }
        $('rc_com_' + dataJson[index].comId).appendChild(rc_respond);
        $('cancle-reply').onclick = function(){
            $('rc_respond').style.display = 'none';
        }
        if ($('rc_commentform') || 0) {
            addListener($('rc_commentform'), 'submit', endIt, false);//prevent the default behavior of form
            addListener($('rc_commentform'), 'submit', sendrc_comment, false);//submit by ajax
        }
        if (options.shortcut) {
            $('rc_comment').onkeydown = function(e){
                submitshortCut(e);
            }
        }
        function sendrc_comment(){
        
            var url = window.location.href, content = 'action=rc_reply' + '&author=' + encodeURIComponent($("rc_author").value) + '&email=' + encodeURIComponent($("rc_email").value) + '&url=' + encodeURIComponent($("rc_url").value) + '&comment=' + encodeURIComponent($("rc_comment").value) + '&comment_post_ID=' + encodeURIComponent(dataJson[index].comPostId) + '&comment_parent=' + encodeURIComponent(dataJson[index].comParent), xmlHttp = createxmlHttp();//create xmlhttp Object
            xmlHttp.open("POST", url, true);
            
            $('wp-rc-reply').style.cursor = 'wait';
            $('rc_comment').style.cursor = 'wait';
            $('rc_submit').disabled = true;
            $('rc_url').disabled = true;
            $('rc_author').disabled = true;
            $('rc_email').disabled = true;
            $('rc_comment').style.background = 'url(' + baseurl + 'plugins/wp-rc-reply-ajax/img/ajax-loader.gif) center center no-repeat';
            
            clearTimeout(it);//clear timeout set
            if ($('rc_loading') || 0) {//if div#rc_loading exist on page
                if ($('rc_loading').parentNode.id != 'rc_com_' + dataJson[index].comId) 
                    insertBe($('rc_loading'), $('rc_respond'));
                $('rc_loading').style.display = "block";
                $('rc_loading').innerHTML = options.strs.str2;
            }
            else {//if no, create div#rc_loading
                var rc_loading = document.createElement('div');
                rc_loading.id = "rc_loading";
                rc_loading.innerHTML = options.strs.str2;
                insertBe(rc_loading, $('rc_respond'));
            }
            
            if ($('rc_author') || 0) {
                if ($('rc_author').value == '') {//have not input name
                    setStatus(options.strs.str3);
                    setDisplayScroll('rc_loading');
                    return;
                }
                //not input email or input an error email
                if ($('rc_email').value == '' || (!/^(?:[a-zA-Z0-9]+[_\-\+\.]?)*[a-zA-Z0-9]+@(?:([a-zA-Z0-9]+[_\-]?)*[a-zA-Z0-9]+\.)+([a-zA-Z]{2,})+$/.test($('rc_email').value))) {
                    setStatus(options.strs.str4);
                    setDisplayScroll('rc_loading');
                    return;
                }
            }
            
            if ($('rc_comment') && $('rc_comment').value == '') {//not input message
                setStatus(options.strs.str5);
                setDisplayScroll('rc_loading');
                return;
            }
            
            xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");//request header type
            xmlHttp.onreadystatechange = function(){
                clearTimeout(it);
                if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
                    if (xmlHttp.status == 200) {
                        options.uname = $("rc_author").value;
                        options.umail = $("rc_email").value;
                        options.uurl = $("rc_url").value;
                        var data = xmlHttp.responseText;
                        $('rc_loading').innerHTML = options.strs.str6 + xmlHttp.responseText;
                        $('rc_comment').value = '';
                        $('rc_respond').style.display = "none";
                        setDisplayScroll('rc_loading');
                        arr += ' ' + dataJson[index].comId;//add the parent_comment_id to array arr.
                    }
                    else {
                        $('rc_loading').innerHTML = xmlHttp.responseText.replace(/<(?!p).*?>(?:.*?<\/.*?>)?/gi, '');
                        setDisplayScroll('rc_loading');
                        addListener($('rc_commentform'), 'submit', endIt, false);
                        addListener($('rc_commentform'), 'submit', sendrc_comment, false);
                    }
                    $('rc_submit').disabled = false;
                    $('rc_url').disabled = false;
                    $('rc_author').disabled = false;
                    $('rc_email').disabled = false;
                    $('rc_comment').style.background = '#fff';
                    $('wp-rc-reply').style.cursor = 'auto';
                    $('rc_comment').style.cursor = 'auto';
                }
            };
            xmlHttp.send(content);
            return false;
        }
    }
    
    function setStatus(d){
        $('rc_loading').innerHTML = d;
        if ($('rc_url') || 0) {
            $('rc_url').disabled = false;
            $('rc_author').disabled = false;
            $('rc_email').disabled = false;
        }
        $('rc_comment').style.background = '#fff';
        $('rc_submit').disabled = false;
        $('wp-rc-reply').style.cursor = 'auto';
        $('rc_comment').style.cursor = 'text';
    }
    function setDisplayScroll(o){
        it = setTimeout(function(){
            if ($(o) || 0) 
                $(o).style.display = "none";
        }, 5000);
    }
	/**
     * @author liuqiqi
     * @public rc_comment_start
     * @param none
     * @version 1.0
     */
    function rc_comment_start(){
        getOptions();
    }
    /**
     * @author liuqiqi
     * @public RCCMT
     * @param none
     * @version 1.0
     */
    function RCCMT(){
        dataJson = new Array();
        loadRc_Comm(0, options.number);
    }
    window.WIDGET = {};
    window.WIDGET['$'] = $;
    window.WIDGET['RCCMT'] = RCCMT;
	window.WIDGET['rc_comment_start'] = rc_comment_start;
})();
