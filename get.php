<?php ob_start();
include("functions.php");


if(isset($_POST['url'])) { 
		
	$reddit_url = $_POST['url']; 
	$content = get_url($reddit_url);
	
	$json = json_decode($content,true);
	parse_main($json);
	parse_comments($json);
	
	 
}


elseif(isset($_GET['img'])) { 		
		
	$img = ($_GET['img']); 
	 if($img) { 
	 	echo '<div class="max_hundred_wrap"><img class="max_hundred"  src="';
	 	echo $img;
	 	echo '" /></div>';
	 	}
}

elseif(isset($_GET['scrape_img'])) { 
	$img = $_GET['scrape_img'];
	$domain = $_GET['domain'];
	
	include 'scraper.php';
 
$html = file_get_html($img);

if($domain == "imgur.com") {
	echo '<div class="max_hundred_wrap">';
	foreach($html->find('div[class=image] a img') as $string) {
	echo '<img class="max_hundred" src="';
 	echo $string->src; }
	echo '""/></div>';
}

elseif($domain == "flickr.com") {
	echo '<div class="max_hundred_wrap">';
	foreach($html->find('div[class=photo-div] img') as $string) {
	echo '<img class="max_hundred" src="';
 	echo $string->src; }
	echo '""/></div>';
}

elseif($domain == "subimg.net") {
	
	$pieces = explode("=", $img);

		echo '<div class="max_hundred_wrap"><img class="max_hundred" src="http://subimg.net/';
		echo $pieces['1'];
		echo '.jpg" /></div>';
}

elseif($domain == "dumppix.com") {
	
	$pieces = explode("=", $img);
	
	echo '<div class="max_hundred_wrap"><img class="max_hundred" src="http://dumppix.com/images/';
	echo $pieces['1'];
	echo '" /></div>';
}

}

ob_end_flush(); 
?>