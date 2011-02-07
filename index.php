<?php session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 
 if(!isset($_SESSION['reddit_session'])) {
	if(isset($_COOKIE['reddit_session'])) {
		$_SESSION['uh'] = $_COOKIE['reddit_uh'];
		$_SESSION['reddit_session'] = $_COOKIE['reddit_session'];
		$_SESSION['user'] = $_COOKIE['reddit_user'];
	}
		// not logged in and no cookie
	}
?>
<!DOCTYPE html>
<html lang="en">
	
<head>
	<title>Reddit WebApp</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css'>
		<link type="text/css" rel="stylesheet" href="/redditApp/css/style.css">
		<script type="text/javascript" src="/redditApp/js/jquery.js"></script>
	<script type="text/javascript" src="/redditApp/js/custom.js"></script>	
</head>
<?php include("functions.php"); ?>
<body>
<div id="top"><a href="/sub/">
<div id="logo"></div></a>
	<?php if(isset($_GET['sub'])) { echo $_GET['sub'];} else {echo "Frontpage"; } ?> 
	<div id="userbox">
		<?php if(isset($_SESSION['reddit_session'])) {
				echo $_SESSION['user'];
				echo '<a href="/redditApp/functions.php?a=logout"> logout</a>';
		} else {
			echo '
		user: <input type="text" name="user" /> password: <input type="password" name="passwd" />
		<input id="login" type="submit" name="submit"/>
			';
		}
		
		?>
		</div>	
</div>
<div id="subnav"><?php subnav(); ?></div>
<div id="subnav_bottom"></div>
<div id="loading_top" class="hidden"><div class="loading"></div><h3>Loading</h3></div>
	<div id="wrap">
		<div id="left">
	<div class="center">

		<div class="button medium standard hot">Whats Hot</div>
		<div class="button medium standard new">New</div>
		<div class="button medium standard top">Top</div>
		
	</div>
	
<div id="left_wrap">
	<div class="center">
 <img src="/redditApp/img/loading.gif" /><h2>Grabbing content</h2>
	</div>
	
	<div style="clear: both"></div>
</div>
	
<div class="center">
	<div class="button large standard more"><span class="hidden"><?php if(isset($_GET['sub'])) { $sub = $_GET['sub']; echo "$sub"; } ?>
</span>Load More</div>
	</div>
</div>

<div id="right">
	<h2>Alpha release 0.3 [last update: 1. feb. 2011.]</h2>
	<p>
	This is a very early release, hell, most things dont even work yet. However, i feel 
	its a good idea to get it tested right from the beginning, so you my good sir are a early alpha tester</p>
	<ul>
	<li><b>TODO</b></li>
	<li>login - 80% done</li>
	<li>new / hot / contro - In progress</li>
	<li>voting 50%</li>
	<li>drag n drop top bar</li>
	<li>more stuff displayed via ajax (loaded into the page)</li>
	<li>write comment / fix comments</li> 
	</ul>

</div>

</body>

</html>