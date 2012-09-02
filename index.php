<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>豆瓣电影250</title>
	<script type="text/javascript" src="jquery-1.8.1.min.js"></script>
	<script type="text/javascript">
	$(function(){
		$.getJSON("movie.json",function(data){
			$.each(data,function(k,v){
				$("<div id='item'><span class='num'>"+(k+1)+
				"</span><span class='pic'><img src='"+v.image+"'/></span>"+
				"<span class='detail'><ul>"+
				"<li><a href='"+v.url+"' target='_blank'>"+v.name+"</a></li>"+
				"<li>"+v.type+"</li>"+
				"<li>"+v.star+"</li>"+
				"</ul></span>"
				+"</div>").appendTo("#main");	
			});
		});
	});
	</script>
	<style type="text/css" media="screen">
		*{}
		ul{margin:0;padding:0;list-style:disc;}
		#main{margin:0 auto;width:980px;line-height:25px;}
		#item{margin:20px auto 0 auto;width:100%;height:100px;border-top:1px dashed #989898;padding:5px;font-size:12px;}
		#footer{font-size:12px;width:100%;height:50px;text-align:center;}
		.detail{margin-left:20px;display:block;float:left;width:500px;height:80px;}
		.pic{width:66px;height:98px;display:block;float:left;}
		.num{display: block;float: left;width: 50px;height:80px;}
	</style>
	
</head>
<body>
	<div id="main">
		<div id='title'><h1>豆瓣电影排行</h1></div>
		<!-- <div id='footer'>2012(c)<a href='www.sudobeta.com' target="_blank">welsonla</a></div> -->
	</div>
</body>
</html>
