<?php session_start();
include("functions.php");
if(isset($_POST['a']) ) { 
$action = $_POST['a'];
if(isset($_SESSION['reddit_session']))
$cookie = $_SESSION['reddit_session'];

if($action == "r") {
	if(isset($_POST['sub'])) {
		if($_POST['sub'] != "") {
		$subreddit = ($_POST['sub']); 
		$reddit_url = "http://www.reddit.com/r/";
		$reddit_url .= $subreddit;
		$reddit_url .= "/.json";
		}
	else {
		$subreddit = "frontpage";
		$reddit_url = "http://www.reddit.com/.json";
	}}

$cache_subreddit = check_cache($subreddit);
  
  if($cache_subreddit != false) {
  		$content = $cache_subreddit;
  	}
  else {
  		$content = get_url($reddit_url);
  		// write fresh stuff into cache
  		if(isset($_SESSION['user'])) {
			$user = $_SESSION['user'];
		} else { $user = ""; }
		$subreddit .= "_";
		$subreddit .= $user;
		$subreddit .= ".cache";
  		$create_cache = fopen("cache/$subreddit", "w");
  		fwrite($create_cache, $content);
  }
  
  if($content) {
	parse_sub($content);
   	     }
}


if($action == "vote") {
	$id = $_POST['id'];
	$dir = $_POST['dir'];
	$r = $_POST['r'];
	$uh = $_POST['uh'];
	
	$urltopost = "http://www.reddit.com/api/vote";
	$datatopost = array (
	"id" => "$id",
	"dir" => "$dir",
	"uh" => "$uh",
	"r"  => "$r"
	);
	
	$ch = curl_init ();
    curl_setopt($ch,CURLOPT_URL,$urltopost);
	curl_setopt ($ch, CURLOPT_POST, true);
	//curl_setopt ($ch, CURLOPT_COOKIE, "$_COOKIE['reddit_session']");
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec ($ch);
	echo $output;
	curl_close($ch);
	}
	
if($action == "login") {
	
	$user = $_POST['user'];
	$passwd = $_POST['passwd'];
	$urltopost = "http://www.reddit.com/api/login/";
	$datatopost = array (
	"api_type" => "json",
	"user" => "$user",
	"passwd" => "$passwd",
	);
	
	$response = post_request($datatopost,$urltopost,$response);
	
	$json = json_decode($response,true);
	var_dump($json);
		if(isset($json['json']['errors']['0']['1'])) {
			echo $json['json']['errors']['0']['1'];
		}
		
		else { $logged = "ok";}
	
	if(isset($logged)) {
	if($logged == "ok") {
		$uh = $json['json']['data']['modhash'];
		$reddit_session = $json['json']['data']['cookie'];
		
			$_SESSION['uh'] = $uh;
			$_SESSION['reddit_session'] = $reddit_session;
			$_SESSION['user'] = $user;
			$_SESSION['logged'] = "true";
			setcookie("reddit_session","$reddit_session", time()+60*60*24*7);
			setcookie("reddit_uh","$uh", time()+60*60*24*7);
			setcookie("reddit_user","$user", time()+60*60*24*7);
		
		echo "ok";
} }// if logged = ok
	

} // end login	



if($action == "more") {
	
	$reddit_url = "http://www.reddit.com/";
	$count = $_POST['count'];
	$after = $_POST['after'];
	$sub = $_POST['sub'];
		if($sub != "") { $reddit_url .= "r/$sub/.json?count=$count&after=$after";}
		else {$reddit_url .= ".json?count=$count&after=$after";}	
	
	$content = get_url_more($reddit_url);
	
	parse_sub($content);
}

if($action == "comments") {
		$url = $_POST['url'];
		$reddit_url = $url;
		$reddit_url .= ".json";	
		
		$content = get_url($reddit_url);

		$json = json_decode($content,true);
	//	parse_main($json);
		parse_comments($json);

		}

} // a

?>
