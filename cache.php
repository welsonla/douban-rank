<?php
	header('Content-Type: text/html; charset=utf-8'); 
	
	require_once('movie.php');
	
	$filmDetailArray = array();

		
	for ($i=0; $i <=225 ; $i=$i+25) { 
		$content = file_get_contents("http://movie.douban.com/top250?start=$i&filter=&format="); 
		// echo $content;
		
		preg_match_all('/\<ol class="grid_view"\>.*\<\/ol\>/is', $content,$match);
		preg_match('/\<li\>.*\<\/li\>/is',$match[0][0],$filmlist);
		
		$resultArr=array_filter(preg_split('/\<li\>/',$filmlist[0]));
			
		$pattenArray =pregRules();
			
		pushFilmToArray();
	

	}
        
       // echo '<pre>';
       // print_r($filmDetailArray);
       // echo '</pre>';
	
	if(count($filmDetailArray)>100){
		
		file_put_contents('movie.json',json_encode(array_filter($filmDetailArray,'removeEmptyValue')));
		// echo "done!";
	}
        
        function removeEmptyValue($val){
			echo "========\n\t";
			if($val->name!=''){
				return true;
			}
			return false;
        }
        

	
	function analysisFilmPage($num)
	{
		global $filmDetailArray;
		global $resultArr;
		global $pattenArray;
		
		
		$content = file_get_contents("http://movie.douban.com/top250?start=$num&filter=&format="); 

		//摘取电影的相关部分
		preg_match_all('/\<ol class="grid_view"\>.*\<\/ol\>/is', $content,$match);
		preg_match('/\<li\>.*\<\/li\>/is',$match[0][0],$filmlist);

		$resultArr=array_filter(preg_split('/\<li\>/',$filmlist[0]));
	
		$pattenArray =pregRules();

		pushFilmToArray();
	}
	
	/*
	匹配各部分的正则表达式
	*/
    function pregRules()
	{
		//电影地址
		$filmPath = '/http:\/\/movie\.douban\.com\/subject\/\d{3,}\//';
		
		//封面图片
		$imagePath= '/http:\/\/img\d{1}.douban.com\/spic\/.*\.jpg/';
		
		//电影名字
		$fileName = '/\<span class=\"title\"\>.*\<\/span\>/';
		
		//电影类型与简介
		$filmType = '/\<div class=\"bd\"\>.*\<div class=\"star\"\>/is';
		
		//电影评分
		$star     = '/\<div class=\"star\"\>.*\<p class=\"quote\"\>/is';

	    $patten =  array($filmPath,$imagePath,$fileName,$filmType,$star);
		return $patten;
	}
	

	
	//将解析后的对象转换成Movie，存储到数组中
    function pushFilmToArray()
	{
		global $filmDetailArray;
		global $resultArr;
		global $pattenArray;
		
		for ($i=0; $i <= count($resultArr) ; $i++) { 
	        $movie = new Movie();

			for ($j=0; $j<=4 ; $j++) { 
				preg_match($pattenArray[$j],$resultArr[$i],$result);
				$results =trim(strip_tags($result[0]));
				
	             switch ($j) {
	                 case 0:
	                     $movie->url =$results;
	                     break;
	                 case 1:
	                     $movie->image = $results;
	                     break;
	                 case 2:
	                     $movie->name = $results;
	                     break;
	                 case 3:
	                     $movie->type = $results;
	                     break;
	                 case 4:
	                     $movie->star = $results;
	                     break;
	                 default:
                                 
	                     break;
	            }
			}
			
			
			//去除空数组
			if($movie->name!=""&&$movie->url!=""){
				array_push($filmDetailArray, $movie);
			}
	   
		}
    }
?>
