<html>
<head>
<title>{$title}</title>
<meta name="http-equiv" content="text/html;charset=utf-8" />
<script type="text/javascript" src="/js/jquery.14.js"></script>
{literal}
<script>
$(function(){
//alert('jQuery is OK!');
//$('P').empty();
$('img').hide();
});
</script>
{/literal}
</head>
<body>
<h1>It works!</h1>
<p>This is the default web page for this server.</p>
<p>The web server software is running but no content has been added, yet.</p>
<p>1.smarty 安装和使用</p>
<p>2.Adodb 的安装和使用</p>
<p>3.Pear的代码研究和使用</p>
<p>4.Wordpress的代码研究和使用</p>
<img src="/images/11.gif" style="background-image:url(/images/1012.jpg);width:1035px;height:687px" />
{if $inf!=''}
<table>
{section name=i loop=$inf}
<tr><td>{$inf[i].title}</td><td>{$inf[i].code}</td></tr>
{/section}
</table>
{/if}
<div>{$pages}</div>
{insert name='ServerTime' script='/var/www/go/no_cache_func.php' str=$time}
</body>
</html>
