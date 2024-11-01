=== WP RecentComments & Reply AJAX(WP RC Reply AJAX) ===
Contributors: QiQiBoY
Author Homepage: http://www.qiqiboy.com
Tags: wordpress, ajax, recentcomments, reply, widget, sidebar
Requires at least: 2.7
Tested up to: 3.2
Stable tag: 2.0.14

Display recent comments in your blog sidebar. With it, you can reply everyone from widget sidebar by Ajax type.

== Description ==

*Display recent comments in your blog sidebar.<br>
*with it, you can reply everyone from widget sidebar by Ajax type.<br>
*If you have questions, Please contact [imqiqiboy#gmail.com] or leave a message in my blog(http://www.qiqiboy.com).<br>
*支持翻页查看的ajax版最新评论<br>
*可以对一条评论进行回复，这也只需在侧边栏就可完成，而且是ajax方式。（点击一条评论右边的右向双箭头，进入单条评论查看模式，然后点击右边的回复按钮即可进行回复）<br>
*回复支持嵌套，自动添加@回复，支持无限嵌套，无需担心被回复人的层深问题。<br>
*支持列表输出回复按钮，回复更方便<br>
*可设置只允许博主回复
*支持"ctrl+enter"快捷回复
*提供技术支持及咨询，联系邮件请发【imqiqiboy#gmail.com】或者在我博客【http://www.qiqiboy.com】留言<br>

= plugin update 2.0.3 =
Add public external interface, you can call <code>WIDGET.RCCMT()</code> to refresh the list of recent comments<br>
新增公共外部接口，你可以调用<code>WIDGET.RCCMT()</code>来刷新最新评论列表。willin版的ajax评论回复修改版可以到此下载http://u.115.com/file/f46b026a9。此版本可以即时刷新最新评论列表。<br>
这是一个完全重构后的测试版，如果你在使用中有任何不适，可以换回稳定的1.2.1版（下载地址：http://downloads.wordpress.org/plugin/wp-rc-reply-ajax.1.2.1.zip）。<br>
2.0版完全重构，速度更快，更节省带宽。使用数据缓存技术，翻看过的评论列表不必重新加载，往回翻页不必重复请求数据，反应更快！
This is a beta version completely reconstructed, if you have any discomfort in use, you can exchange for a stable version 1.2.1 (Download: http://downloads.wordpress.org/plugin/wp-rc-reply -ajax.1.2.1.zip). <br>
2.0 PR, faster, more economical bandwidth. Use of data cache, look over the list without having to reload the comments, do not have to repeat the request back page of data, the reaction faster!
== Installation ==

1. Download the plugin archive and expand it (you've likely already done this).
2. Put the 'WP-RC-Reply-AJAX' directory into your wp-content/plugins/ directory.
3. Go to the Plugins page in your WordPress Administration area and click 'Activate' for WP-RC-Reply-AJAX.
4. Go to the WP-RC-Reply-AJAX Options page (Settings > WP-RC-Reply Option).
5. Go to widgets page to add the widget to your sidebar.
	Also you can use function <code><?php wp_rc_reply_echo('number=&length=&size=&at='); ?></code> at the place you want to show.

下载插件，上传到插件目录，在后台管理中激活插件，到设置页面进行简单设置，然后在小工具页面向边栏添加边栏回复小工具即可。
当然，你也可以直接在需要显示最新评论的地方调用<code>&lt;?php wp_rc_reply_echo('number=&length=&size=&at='); ?><code>。<br>
可选参数number是显示数量，length是截断长度，size是头像大小，at是从第几层开始自动添加@回复。<br>
ep:<code>&lt;?php wp_rc_reply_echo('number=8'); ?></code> or <code>&lt;?php wp_rc_reply_echo('number=8&length=25'); ?></code>

== Screenshots ==

1. 使用效果截图：回复功能
2. 后台小工具截图：widget[小工具]

== Changelog ==
= 2.0.14 =
2011/07/13
修改widget格式问题，使之符合主题设置
= 2.1.0 =
2010/11/29
插件名称改为WP RecentComments & Reply AJAX
= 2.0.13 =
2010/11/24
修复初次安装无法使用问题
= 2.0.12 =
2010/11/08
修复与WP Enjoy Reading插件的兼容性
= 2.0.11 =
2010/10/29
修改一些小问题，完善兼容性
= 2.0.10 =
2010/10/24
增加自定义是否显示详细评论按钮，增加显示文章链接
= 2.0.9 =
2010/10/20
修复样式上的一个小问题
= 2.0.8 =
2010/10/20
修复IE8以下版本浏览器的错误问题
= 2.0.7 =
2010/10/19
动画过度效果修正
= 2.0.6 =
2010/10/19
一些逻辑修改。
= 2.0.5 =
2010/10/19
插件适应性修正，适用于更多主机博客。<br>
评论页面切换增加过渡效果。<br>
一些翻译错误的修正。<br>
= 2.0.4 =
2010/10/09
修复新访客留言后刷新页面前再次留言时资料信息未记录的问题。<br>
= 2.0.3 =
Add public external interface, you can call <code>WIDGET.RCCMT()<code> to refresh the list of recent comments<br>
新增公共外部接口，你可以调用WIDGET.RCCMT()来刷新最新评论列表。willin版的ajax评论回复修改版可以到此下载http://u.115.com/file/f46b026a9。此版本可以即时刷新最新评论列表。
= 2.0.2 =
修正兼容性
= 2.0.1 =
2.0正式版发布
= 2.0beta =
评论结构的完全重构，新一代边栏回复，更快，更迅速，更省流量。
= 1.2.1 =
修正最新评论列表的回复按钮没有正确取到父级ID的问题；
JS脚本优化，解决提交时信息提示框位置错乱问题。
= 1.2.0 =
大量更新，插件更稳定，使用更方便。<br>
*可设置只允许博主回复<br>
*支持"ctrl+enter"快捷回复<br>
= 1.1.6 =
css文件修剪
= 1.1.5 =
评论列表输出使用get_comment_link(), 所以url带有评论分页comment-page-[num]。通过过滤可以解决由此带来的页面锚点问题。<br>
如果插件更新后页面出现错误，请到设置页面点击关闭过滤。
Review the list of output using get_comment_link (), so url with comments page comment-page-[num]. Can be solved by filtering the resulting page anchor problem. <br>
If the plug-in error after the update page, go to Settings page click 'Close filter'.
= 1.1.3 =
添加刷新功能。。你可以在最新评论的首页选择是否输出刷新按钮。
add refresh Function。
= 1.0 =
2010/08/20
插件开发完毕，上线。
= 1.0.2 =
修正一些乱码、找不到路径问题。
强烈建议升级，对之前的这些错误表示抱歉，是我测试不全面所致。
= 1.1.1 =
重大升级，修复一系列bug。另外对插件调用函数<code>&lt;?php wp_rc_reply_echo('number=&length=&size=&at='); ?></code>进行了功能增强，可选参数为4个：<?php wp_rc_reply_echo('number=&length=&size=&at='); ?>。<br>
可选参数number是显示数量，length是截断长度，size是头像大小，at是从第几层开始自动添加@回复。<br>
ep:<code>&lt;?php wp_rc_reply_echo('number=8'); ?></code> or <code>&lt;?php wp_rc_reply_echo('number=8&length=25'); ?></code><br>
你现在可以直接使用<code>&lt;?php wp_rc_reply_echo('number=&length=&size=&at='); ?></code>在不同页面控制显示不同数量、不同头像大小等的评论了。
= 1.1.2 =
修正字符显示，兼容w3c验证。