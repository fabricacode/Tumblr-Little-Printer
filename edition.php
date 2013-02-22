<?php
     
     /***********************CONFIGURATION*******************/
     
     $publicationUrl="http://angelosemeraro.info/tumblr_lp/"; 
	 $blogName="littleprinterexample"; 
     $apiKey="Oinf84VRfHuiwg0Zjw2RD1Ta40casR4hI8gOUPcuBfw9rSkTt6";
     $fontFamily="Courier New";
     $titleFontSize=20;
     $textFontSize=18;
     
     
     
     
     /****************************************/
     
     
     
     
     
     
     
     
     
     
    $data="
<html>
    <head>
    	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<style type='text/css'>
			  body {
				width:384px;
				margin:0px;
			  }
			  
			  #headerContainer{
				width:384px;
				margin-bottom:10px;
				color:white;
				background-color:black;	
				font-weight: bold;
				font-size:".$titleFontSize."px;
				padding-top:3px;
				padding-bottom:3px;
				
			  }
			
			  #bannerContainer{
				width:384px;
			  }
			
			  #bannerContainer img{
				margin-top:5px;
				margin-bottom:5px;
			  }
			
			 img{
				max-width:384px;
				max-height:170px;
				margin-top:15px;
				margin-bottom:15px;
				margin-left:auto;
				margin-right:auto;
				display:block;
				height:auto;
			  }
			  
			  #textContainer{
				padding-top:0px;
				margin-bottom:15px;
				width:384px;
				font-size:".$textFontSize."px;
			  }
			  
			  #mainContainer{
				font-family: '".$fontFamily."';
				width:384px;
				border-bottom-width:2px;
				border-bottom-style:dotted;
				border-bottom-color:black;
				border-top-width:2px;
				border-top-style:dotted;
				border-top-color:black;
				margin-top:15px;
				margin-bottom:15px;
			  }
			  
			  p{
				margin:0px;
				padding:0px;
			  }
		</style>
	</head>
		<body>
		<div id='mainContainer'>
		<div id='bannerContainer'>
			<img src='".$publicationUrl."banner.png'/>
		</div>";
	$title="Title";
    $etag="";
    $textContainer="";
	$headerContainer="";
    $imgContainer="";
	if(!empty($_GET)) {
    	$blogName=htmlspecialchars($_GET["blog"]);
    }
    $jsonData = file_get_contents("http://api.tumblr.com/v2/blog/".$blogName.".tumblr.com/posts?api_key=".$apiKey);
	if(jsonData){
	
    	$decoded=json_decode($jsonData);
    
    	/*ETAG HEADER*/
    	$etag=$decoded->{'response'}->{'posts'}[0]->{'id'};
    	header("ETag: ".$etag);
    
    	/*TITLE*/
    	$title=$decoded->{'response'}->{'posts'}[0]->{'title'};
    	if(strlen($title)>0){
    		$headerContainer=$title;
    	}
    
    	/*TEXT*/
    
    	$textContainer=$textContainer.$decoded->{'response'}->{'posts'}[0]->{'body'};
    	$textContainer=$textContainer.$decoded->{'response'}->{'posts'}[0]->{'caption'};
  		$textContainer=strip_tags($textContainer,'<img>');
  	
    	/*IMG*/
    	$imgUrl=$decoded->{'response'}->{'posts'}[0]->{'photos'}[0]->{'alt_sizes'}[0]->{'url'};
    	if(strlen($imgUrl)>0){
    		$imgContainer=$imgContainer."<img src='".$imgUrl."' />";
   		}
   		else{
   			//ADDING THE FIRST IMAGE IN THE POST (IF THERE'S ONE)
    		$imgTmp=" ".$textContainer;
    		if(strpos($imgTmp , "<img")>0){
   		 		$imgUrl=substr($textContainer,strpos($textContainer , "<img"));
   		 		$endTag=strpos($imgUrl , '>')+1;
    			if($endTag){
		    		$imgUrl=substr($imgUrl,0,$endTag);
    				$imgContainer=$imgContainer.$imgUrl;
    			}
    		}
   		}
    
    	$textContainer=strip_tags($textContainer);
    	$textContainer=trim($textContainer);
    	$textContainer=str_replace("\n", "<br/>", $textContainer);
    
    	if(strlen($headerContainer)>0)
    		$data=$data."<div id='headerContainer'>".$headerContainer."</div>";
    	if(strlen($imgContainer)>0)
    		$data=$data."<div id='imgContainer'>".$imgContainer."</div>";
    	if(strlen($textContainer)>0)
    		$data=$data."<div id='textContainer'>".$textContainer."</div>";
    	$data=$data."</div></body></html>";
    	echo $data;
    }
?>