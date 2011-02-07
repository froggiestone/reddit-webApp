$(document).ready(function() {

	var x_sub = $(".more").children('span').get(0);
		var sub = $(x_sub).html();
	$.ajax({
   type: "POST",
   url: "/redditApp/action.php",
   data: "a=r&sub=" +sub,
   success: function(response){
	$("#left_wrap").empty();
	$("#left_wrap").append(response);
   }

 });


$('.url_img').live('click', function(url_img) {
	
	var adr = $(this).children(':first-child').html();
	var ex_title = $(this).find('h2').get(0);
		var title = $(ex_title).html();
	var x_permalink = $(this).find('span').get(1);
		var permalink = $(x_permalink).html();
	var x_num_comments = $(this).find('span').get(3);
		var num_comments = $(x_num_comments).html();
		url_img.stopPropagation();
	
	$.ajax({
   type: "GET",
   url: "/redditApp/get.php",
   data: "img=" +adr,
   success: function(data){
		$("#right").append('<h1>' +title+ '</h1>');
	$(data).hide().appendTo('#right');                        
	   var imagesCount = $('#right').find('img').length;
	   var imagesLoaded = 0;
	       $('#right').find('img').load( function() {
	           ++imagesLoaded;
	             if (imagesLoaded >= imagesCount) {
					$("#loading_top").animate({height: '0px', opacity: '0'});
					$('#right').children().show();
					$("#right").append('<br /><div class="button load_comments large standard">Load Comments '+num_comments+' <span class="hidden">' +permalink+ '</span></div>');
											
	                                     }
	});
	  var timeout = setTimeout( function() { 
		$('#right').children().show() }, 4000 );
		$("#loading_top").animate({height: '0px', opacity: '0'});
	
   }
   
 });
});
    
	$('.load_comments').live('click', function() {


	var x_permalink = $(this).find('span').get(0);
	var permalink = $(x_permalink).html();

	$.ajax({
   type: "POST",
   url: "/redditApp/action.php",
   data: "a=comments&url=" +permalink,
   success: function(response){
	 $("#loading_top").animate({height: '0px', opacity: '0'});
	$("#right").append(response);
   }

 });

    });

$('.url_reddit').live('click', function(url_reddit) {
	
	var adr = $(this).children(':first-child').html();
	$("#right").empty();
	url_reddit.stopPropagation();
	
	$.ajax({
   type: "POST",
   url: "/redditApp/get.php",
   data: "url=" +adr + ".json",
   success: function(response){
		 $("#loading_top").animate({height: '0px', opacity: '0'});
		$("#right").append(response);
   }
   
 });

    });
    
$('.new_tab').live('click', function(new_tab) {
	var ex_adr = $(this).children('span').get(0);
		var adr = $(ex_adr).html();
	
	var ex_com = $(this).children('span').get(1);
		var comments = $(ex_com).html();
	
	window.open (adr);
	new_tab.stopPropagation();
	
	$.ajax({
   type: "POST",
   url: "/redditApp/get.php",
   data: "url=" +comments + ".json",
   success: function(response){
		 $("#loading_top").animate({height: '0px', opacity: '0'});
		$("#right").empty();
		$("#right").append(response);
   }
   
 });

    });
    
$('.scrape_img').live('click', function(scrape_img) {
	
	var x_domain = $(this).children('span').get(2);
	var	domain =$(x_domain).html();
	
	var x_permalink = $(this).find('span').get(1);
		var permalink = $(x_permalink).html();
	
	var adr = $(this).children(':first-child').html();
	var ex_title = $(this).find('h2').get(0);
		var title = $(ex_title).html();
	scrape_img.stopPropagation();

	
	$.ajax({
   type: "GET",
   url: "/redditApp/get.php",
   data: "scrape_img=" +adr+ "&domain=" +domain,
   success: function(data){
		$("#right").append('<h1>' +title+ '</h1>');
	$(data).hide().appendTo('#right');                        
	                          var imagesCount = $('#right').find('img').length;
	                          var imagesLoaded = 0;
	                          $('#right').find('img').load( function() {
	                                     ++imagesLoaded;
	                                     if (imagesLoaded >= imagesCount) {
												$("#loading_top").animate({height: '0px', opacity: '0'});
											 $('#right').children().show();
											$("#right").append('<br /><div class="button load_comments large standard">Load Comments <span class="hidden">' +permalink+ '</span></div>');
										
	                                     }
	                                  });
	                         var timeout = setTimeout( function() { 
												$('#right').children().show() }, 4000 );
												 $("#loading_top").animate({height: '0px', opacity: '0'});


   }
   
 });

    });
// NEED COMPLETION
$(".arrow_up").click(function (arrow_up) {
	
	var ex_id = $(this).children('span').get(0);
		var id = $(ex_id).html();
	
	var ex_r = $(this).children('span').get(1);
		var r = $(ex_r).html();
		
	var ex_uh = $(this).children('span').get(2);
		var uh = $(ex_uh).html();
	
	$.ajax({
   type: "POST",
   url: "action.php",
   data: "a=upvote&dir=1&id=" +id+ "&r=" +r+ "&uh=" +uh,
   success: function(response){
		
		$("#right").empty();
		$("#right").append(response);
   }
   
 });

    });

$("#login").click(function () {

		var x_user = $("#userbox").children('input').get(0);
			var user = $(x_user).val();

		var x_passwd = $("#userbox").children('input').get(1);
			var passwd = $(x_passwd).val();

		$.ajax({
	   type: "POST",
	   url: "/redditApp/action.php",
	   data: "a=login&user=" +user+ "&passwd=" +passwd,
	   success: function(response){
			if(response == "ok") {
				window.location="/sub/";
			} else {
			$("#right").empty();
			$("#right").append(response);
		}
	   }

	 });

	    });

$('.list_link').live('click', function(list_link) {
	$('.list_link').removeClass('selected');
	$('.list_link').addClass('left_border');
	
	$(this).removeClass('left_border');
	$(this).addClass('selected');
	
	$("#right").empty();
	$("#loading_top").animate({height: '35px', opacity: '100'});
	
	var sTop = $(window).scrollTop();
 
 	if(sTop <= 88) {
 		$("#right").animate({ top: 10}, { duration: 500}) }
 	else {
  $("#right").animate({ top: +10 +sTop}, { duration: 500}) }
  
	list_link.stopPropagation();
});


$('.more').live('click', function() {
	$("#loading_top").animate({height: '35px', opacity: '100'});
	var ex_sub = $(this).children('span').get(0);
		var sub = $(ex_sub).html();	
	var id = $("#left_wrap").children("li:last").attr('id');
	
	$.ajax({
   type: "POST",
   url: "/redditApp/action.php",
   data: "a=more&sub="+sub+"&count=25&after="+id,
   success: function(response){
	 $("#loading_top").animate({height: '0px', opacity: '0'});
		$("#left_wrap").append(response);
		
   }
   
 });
	
});

$('.hot').live('click', function() {
	$("#loading_top").animate({height: '35px', opacity: '100'});
	var ex_sub = $(this).children('span').get(0);
		var sub = $(ex_sub).html();	
	var id = $("#left_wrap").children("li:last").attr('id');
	
	$.ajax({
   type: "POST",
   url: "/redditApp/action.php",
   data: "a=get&sub="+sub+"&count=25&after="+id,
   success: function(response){
	 $("#loading_top").animate({height: '0px', opacity: '0'});
		$("#left_wrap").append(response);
		
   }
   
 });
	
});

	}); //end document ready
