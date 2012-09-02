<?php
	header('Content-Type: text/html; charset=utf-8'); 
	
	require_once 'movie.php';
	
	$filmDetailArray = array();
	// $resultArr = array();
	// $pattenArray = array();	
	// 
	// for ($i=0; $i <225 ; $i++) { 
	// 	$content = file_get_contents("http://movie.douban.com/top250?start={$num}&filter=&format="); 
	// 	preg_match_all('/\<ol class="grid_view"\>.*\<\/ol\>/is', $content,$match);
	// 	preg_match('/\<li\>.*\<\/li\>/is',$match[0][0],$filmlist);
	// 
	// 	$resultArr=array_filter(preg_split('/\<li\>/',$filmlist[0]));
	// 
	// 	$pattenArray =pregRules();
	// 
	// 	pushFilmToArray();
	// 	
	// }
	

		
	for ($i=0; $i <=225 ; $i=$i+25) { 
		$content = file_get_contents("http://movie.douban.com/top250?start=$i&filter=&format="); 
		
		preg_match_all('/\<ol class="grid_view"\>.*\<\/ol\>/is', $content,$match);
		preg_match('/\<li\>.*\<\/li\>/is',$match[0][0],$filmlist);
			
		$resultArr=array_filter(preg_split('/\<li\>/',$filmlist[0]));
			
		$pattenArray =pregRules();
			
		pushFilmToArray();
	

	}
    echo '<pre>';
    print_r($filmDetailArray);
    echo '</pre>';
	
	if(count($filmDetailArray)>100){
		file_put_contents('movie.json',json_encode($filmDetailArray));
	}
	

	// json_encode($filmDetailArray)
	
	function analysisFilmPage($num)
	{
		global $filmDetailArray;
		global $resultArr;
		global $pattenArray;
		
		$content = file_get_contents("http://movie.douban.com/top250?start=$num&filter=&format="); 
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
		$filmPath = '/http:\/\/movie\.douban\.com\/subject\/\d{3,}\//';
		$imagePath= '/http:\/\/img\d{1}.douban.com\/spic\/.*\.jpg/';
		// http://img3.douban.com/spic/s3877058.jpg
		$fileName = '/\<span class=\"title\"\>.*\<\/span\>/';
		$filmType = '/\<div class=\"bd\"\>.*\<div class=\"star\"\>/is';
		$star     = '/\<div class=\"star\"\>.*\<p class=\"quote\"\>/is';

	    return array($filmPath,$imagePath,$fileName,$filmType,$star);
		
	}
	

	
	//将解析后的对象转换成Movie，存储到数组中
    function pushFilmToArray()
	{
		global $filmDetailArray;
		global $resultArr;
		global $pattenArray;
		
		for ($i=0; $i < count($resultArr) ; $i++) { 
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
	        array_push($filmDetailArray, $movie);
		}
	}
?>