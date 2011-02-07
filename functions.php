<?php
function subnav() {
	$subreddit = "subnav";
	$cache_subreddit = check_cache($subreddit);
		
		if($cache_subreddit != false) {
		  		$content = $cache_subreddit;
		  	}
		else {
	
	
	if(isset($_SESSION['uh'])) {
			$reddit_url = "http://www.reddit.com/reddits/mine/.json";
			$content = get_url($reddit_url);
	} else { 
		$reddit_url = "http://www.reddit.com/reddits/.json"; 
		$content = get_url($reddit_url);
	
	}
	

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
	$json = json_decode($content,true);
      foreach($json['data']['children'] as $child) { 
		echo '<li class="subnav">';
		echo '<a href="';
		echo $child['data']['display_name'];
		echo '">';
		echo $child['data']['display_name'];
		echo "</a> - ";	
		echo "</li>";
      }
   }


function check_cache($subreddit) {
	if(isset($_SESSION['user'])) {
		$user = $_SESSION['user'];
	} else { $user = ""; }
		$subreddit .= "_";
		$subreddit .= $user;
		$subreddit .= ".cache";
		if(is_readable("cache/$subreddit")) {
			// check if fresh
			$seconds_old = "300";
			  if(FILEMTIME("cache/$subreddit") > (time()-$seconds_old) )	{	
			
			$cache_subreddit = file_get_contents("cache/$subreddit", true);
		   	   		
		   	   return $cache_subreddit;
			  }
   			} else { return false;}
   	}

// get request
function get_url($reddit_url) {
  		if(isset($_SESSION['uh'])) {
			$cookie = $_SESSION['reddit_session'];
			$uh = $_SESSION['uh'];
			$user = $_SESSION['user'];
			$reddit_url .= "?feed=$uh&user=$user";
		}
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$reddit_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
if(isset($_SESSION['uh'])) {
	curl_setopt($ch,CURLOPT_COOKIE,"reddit_session=$cookie");
}
    $content = curl_exec($ch);
    curl_close($ch);
    if($content) {
    return $content;
  }
  }

function get_url_more($reddit_url) {
  			if(isset($_SESSION['uh'])) {
				$cookie = $_SESSION['reddit_session'];
			}
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$reddit_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
if(isset($_SESSION['uh'])) {
	curl_setopt($ch,CURLOPT_COOKIE,"reddit_session=$cookie");
}
    $content = curl_exec($ch);
    curl_close($ch);
    if($content) {
    return $content;
  }
  }
// everything that involve a post request like vote, login, write etc etc.
function post_request($datatopost, $urltopost) {
		
		if(isset($_SESSION['uh'])) {
			$cookie = $_SESSION['reddit_session'];
			$uh = $_SESSION['uh'];
			$user = $_SESSION['user'];
			$reddit_url .= "?feed=$uh&user=$user";
		}
		
	$ch = curl_init ();
    curl_setopt($ch,CURLOPT_URL,$urltopost);
	curl_setopt ($ch, CURLOPT_POST, true);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
	
	if(isset($_SESSION['uh'])) {
		curl_setopt($ch,CURLOPT_COOKIE,"reddit_session=$cookie");
	}
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec ($ch);
	curl_close($ch);
	
	return $response;
}
  
 // wtf split that url
function parseUrl($scan) {
    $r  = "^(?:(?P<scheme>\w+)://)?";
    $r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
    $r .= "(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?" . "(?P<domain>\w+\.(?P<extension>\w+)))";
    $r .= "(?::(?P<port>\d+))?";
    $r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
    $r .= "(?:\?(?P<arg>[\w=&]+))?";
    $r .= "(?:#(?P<anchor>\w+))?";
    $r = "!$r!";                                                // Delimiters
    
    preg_match ( $r, $scan, $out );
    
    return $out;
}
 
function scan_url($scan) {
	$scan_for_img = substr($scan, -3);
 	$scan = parseUrl($scan);
 	
 	$right_list = array( "img" => array ( 
										"png",
 										"jpg",
 										"gif",
										"JPG",
										"PNG",
										"GIF",
 											),
						"reddit" => array ( 
							"reddit.com",
 							"self",
 											),
 						"scrape_img" => array ( 
							"imgur.com",
							"subimg.net",
							"flickr.com",
							

 											)
 						);
// some sites uses paths aka file=name.jpg we dont want them here
// $scan_for_img picks them up as direct links
//so we make an exclude list to check up against
	$exclude_list = array( "sites" => array (
											"dumppix.com",
											)
						);
if (in_array($scan_for_img, $right_list['img'])) {
	if(in_array($scan['domain'], $exclude_list['sites'])) {
		$scanned = "scrape_img";
		return $scanned; }
	else {
	$scanned = "url_img";
	return $scanned; }}
 	
 	elseif(in_array($scan['domain'], $right_list['scrape_img'])) {
 		$scanned = "scrape_img";
 		return $scanned; }
 		
	elseif(in_array($scan['domain'], $right_list['reddit'])) {
		$scanned = "url_reddit";
		return $scanned;
	}
	elseif(in_array($scan['subdomain'], $right_list['reddit'])) {
		$scanned = "url_reddit";
		return $scanned;
	}
 	
 else { $scanned = "new_tab"; 
 		return $scanned;
  }
}

function scrape_img($img) {

	include 'scraper.php';
 
$html = file_get_html($img);
 
foreach($html->find('img') as $string) {
 	$out = $string->src;   return $out; }
 
   }
   
function addbreaks($str) {
	//add breaks
	$order = array("\r\n", "\n", "\r");
	$replace = '<br />';
	$str = str_replace($order, $replace, $str);
	//add urls
	$search = array( '/\[(.*?)\]\((.*?)\)/is' ); 
	$replace = array('<a target="new" href="$2">$1</a>'); 
	$str = preg_replace ($search, $replace, $str); 
	
	return $str;
}


	// parse subreddits inc frontpage
   	   	function parse_sub($content) {
	$json = json_decode($content,true);
	
	$c = "0";
	
      foreach($json['data']['children'] as $child) { 
		$c++;
		
 		$odd_even = ( ($c % 2) ? 'odd' : 'even' );
		
      	
      	$scan = $child['data']['url'];
		$return = scan_url($scan);
			echo '<span class="hidden">';
			echo $child['data']['subreddit'];
			echo '</span><span class="hidden">';
				if (isset($_SESSION['uh'])) { 
			echo $_SESSION['uh'];
				}
			echo '</span>';
		echo '<li id="'. $child['kind'] .'_'. $child['data']['id'] .'" class="list_link left_border '.$odd_even.' ';
		echo $return;
		echo '">';
		echo '<span class="hidden">';
		echo $child['data']['url'];
		echo '</span><span class="hidden">';
		echo 'http://www.reddit.com';
		echo $child['data']['permalink'];
		echo '</span><span class="hidden">';
		echo $child['data']['domain'];
		echo '</span><span class="hidden">';
		echo $child['data']['num_comments'];
		echo '</span>';

		if($child['data']['thumbnail'] != "") {
			echo '<div class="thumb_wrap"><img class="thumb" src="';
			echo $child['data']['thumbnail'];
			echo '" /></div>';
			}
		
		echo '<div class="'. $return .'_icon icon"></div><span class="ups_small">';
		echo $child['data']['score'];
		echo '</span>';
		echo '<h2>';
		echo $child['data']['title'];
		echo "</h2>";
		
		
		echo $child['data']['author'];
		echo ' to <a href="?sub=';
		echo $child['data']['subreddit'];
		echo '">';
		echo $child['data']['subreddit'];
		echo '</a><div style="clear: both"></div></li>';
      }
  }

function parse_main($json) {

// headline + story
foreach($json['0']['data']['children']as $child) { 
	
	$str = $child['data']['selftext'];
	$child['data']['selftext'] = addbreaks($str);
	echo "<li>";
	echo '<h1>';
	echo $child['data']['title'];
	echo "</h1>";
	echo $child['data']['selftext'];
	echo "<br /><b>";
	echo $child['data']['author'];
	
	echo "</b></li><br /><h2>Comments:</h2>";
}}

function parse_comments($json) {
// first lvl comment
foreach($json['1']['data']['children']as $child) {

$str = $child['data']['body'];
$child['data']['body'] = addbreaks($str);
echo '<li class="comment"><div>';
echo $child['data']['ups'];
echo " by <b>";
echo $child['data']['author'];
echo '</b> </div>';
echo $child['data']['body'];
//echo "</li>"; 

// second lvl
if($child['data']['replies'] != "") { 	
		foreach($child['data']['replies']['data']['children'] as $lvl_two) { 
			
			if(isset($lvl_two['data']['body'])) {
				$str = $lvl_two['data']['body'];
				$lvl_two['data']['body'] = addbreaks($str);
				echo '<li class="comment_child"><div>';
				echo $lvl_two['data']['ups'];
				echo "<b>";
				echo $lvl_two['data']['author'];
				echo "</b>";
				echo '</div>';
				echo '';
				echo $lvl_two['data']['body'];
			//	echo "</li>";
	
	// lvl tree
if($lvl_two['data']['replies'] != "") {
		foreach($lvl_two['data']['replies']['data']['children'] as $lvl_tree) { 
					
			if(isset($lvl_tree['data']['body'])) {
				$str = $lvl_tree['data']['body'];
				$$lvl_tree['data']['body'] = addbreaks($str);
				echo '<li class="comment_child"><div>';
				echo $lvl_tree['data']['body'];
				echo $lvl_tree['data']['author'];
				echo '</div>';
			//	echo "</li>";
	// lvl four
if($lvl_tree['data']['replies'] != "") {
		foreach($lvl_tree['data']['replies']['data']['children'] as $lvl_four) { 
			
			if(isset($lvl_four['data']['body'])) {
					echo '<li class="comment_child"><div>';
					echo $lvl_four['data']['body'];
					echo '</div>';
				//	echo "</li>";
	// five			
if($lvl_four['data']['replies'] != "") {
		foreach($lvl_four['data']['replies']['data']['children'] as $lvl_five) {
			
			if(isset($lvl_five['data']['body'])) {
					echo '<li class="comment_child"><div>';
					echo $lvl_five['data']['body'];
					echo '</div>';
			//		echo "</li>";

				}}}echo "</li>";	}}}echo "</li>"; }}}echo "</li>"; }}}echo "</li>";
	} echo "</li>";
} //function parse_comments end

if(isset($_GET['a']) ) {
	$a = $_GET['a'];
if($a == "logout") {
	session_start();
	session_destroy();
		setcookie("reddit_session","", time()-60*60*24*7);
		setcookie("reddit_uh","", time()-60*60*24*7);
		setcookie("reddit_user","", time()-60*60*24*7);
	header("Location: /sub/?logout=ok");

	}}

?>