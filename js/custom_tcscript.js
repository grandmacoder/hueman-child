/*
	custom_tcscript.js
	
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
*/
jQuery(document).ready(function($) {
$('#qi_popup_terms').click(function(e){
$.fancybox({
		'href': '#QI_info_statement',
        'titleShow'  : false,
		'width':800,
        'height':700,
        'autoSize' : false,
        'transitionIn'  : 'fade',
        'transitionOut' : 'fade'
});
return false;
});
$('#submitnewsletter').click(function(e){
	e.preventDefault;
$.fancybox({
		 href: "http://wtest2.transitioncoalition.org/mailchimp-subscribe-form/",
         type: "iframe",
		  scrolling : "no",
		  height:600,
		  autoHeight: false,
		  autoScale: false,
});
return false;
});

	/*flip nav */	
  $(".flipnav li a").on("click", function() {
  var activeTab = $(this).attr("href");
  $('#card > div').removeClass('flipped after before');
  $(activeTab).addClass('flipped');
  $(activeTab).prevAll('.flipper').addClass('before');
  $(activeTab).nextAll('.flipper').addClass('after');
  return false;
});
$('a.openwin').click(function(e) {
	 e.preventDefault();
     newwindow=window.open($(this).attr('href'),'','height=600,width=850');
		if (window.focus) {newwindow.focus()}
		return false;
           
});

/*
-------------------
bind user pass2 and pass1 input fields
-------------------
*/
$('#pass1').change(function() 
{  
//change pass 2 (hidden) to pass 1 to get past the vadlidation on core wp user.php line 139
$('#pass2').val($(this).val()); 
});
/*
-------------------
Browser check
-------------------
*/
var current_page = $(location).attr('href');

var baseURL = window.location.protocol+"//"+window.location.host;
//check the browser capability
var userAgent = navigator.userAgent.toLowerCase();
// Figure out what browser is being used.
    var Browser = {
        Version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
        Chrome: /chrome/.test(userAgent),
        Safari: /webkit/.test(userAgent),
        Opera: /opera/.test(userAgent),
        IE: /msie/.test(userAgent) && !/opera/.test(userAgent),
        Mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent),
        Check: function() { alert(userAgent); }
    };
    if (Browser.Chrome || Browser.Mozilla || Browser.IE) {
		if(Browser.Version < 10){
			 $(".checkUserAgent").show();
		}
    }
// Find all iframes
var $iframes = $( "iframe" );
 // Find &#x26; save the aspect ratio for all iframes
$iframes.each(function () {
  $( this ).data( "ratio", this.height / this.width )
    // Remove the hardcoded width &#x26; height attributes
    .removeAttr( "width" )
    .removeAttr( "height" );
});
 
// Resize the iframes when the window is resized
$( window ).resize( function () {
  $iframes.each( function() {
    // Get the parent container&#x27;s width
    var width = $( this ).parent().width();
    $( this ).width( width )
      .height( width * $( this ).data( "ratio" ) );
  });
// Resize to fix all iframes on page load.
}).resize();



/*
-------------------------
custom tooltip options
------------------------
*/

/*
---------------------
change colors on selected areas of page content
---------------------
*/
$('#1_highlight').mouseover(function(){
		var fulltext= $('#highlight_area_text').html();
		var textonly=($(fulltext).filter('.highlight1off').text());
		var newfulltext = fulltext.replace(new RegExp("highlight1off","g"), "highlight1on");
        $('#highlight_area_text').html(newfulltext);
		$('#highlight_text_result').html("<p><strong>Results: </strong>"+textonly+"</p>");
    });
$('#1_highlight').mouseout(function(){
		var fulltext= $('#highlight_area_text').html();
		var newfulltext = fulltext.replace(new RegExp("highlight1on","g"), "highlight1off");
		$('#highlight_area_text').html(newfulltext);
		$('#highlight_text_result').html("<p></p>");
    });
$('#2_highlight').mouseover(function(){
		var fulltext= $('#highlight_area_text').html();
		var textonly=($(fulltext).filter('.highlight2off').text());
		var newfulltext = fulltext.replace(new RegExp("highlight2off","g"), "highlight2on");
		$('#highlight_area_text').html(newfulltext);
		$('#highlight_text_result').html("<p><strong>Results: </strong>"+textonly+"</p>");
    });
$('#2_highlight').mouseout(function(){
		var fulltext= $('#highlight_area_text').html();
		var newfulltext = fulltext.replace(new RegExp("highlight2on","g"), "highlight2off");
		$('#highlight_area_text').html(newfulltext);
		$('#highlight_text_result').html("<p></p>");
    });
$('#3_highlight').mouseover(function(){
		var fulltext= $('#highlight_area_text').html();
		var textonly=($(fulltext).filter('.highlight3off').text());
		var newfulltext = fulltext.replace(new RegExp("highlight3off","g"), "highlight3on");
		$('#highlight_area_text').html(newfulltext);
		$('#highlight_text_result').html("<p><strong>Results: </strong>"+textonly+"</p>");
    });
$('#3_highlight').mouseout(function(){
		var fulltext= $('#highlight_area_text').html();
		var newfulltext = fulltext.replace(new RegExp("highlight3on","g"), "highlight3off");
		$('#highlight_area_text').html(newfulltext);
		$('#highlight_text_result').html("<p></p>");
    });

/*
-------------------
Course registration
-------------------
*/
if (current_page.indexOf('tc-registration') > -1 ) {
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var params={};
window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(str,key,value){params[key] = value;});
var coursekey = params['coursekey'];
if (coursekey){
//find out the course information, coach information, user information, and login status.	
$.ajax({
                   type: "POST",
                   url: baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php",
                   dataType: 'json', 
                   data: {'action':'get_registration_info', 'coursekey':coursekey},
                   success: function(returnvars) {
				  //if the user is already registered set a message
				  if (returnvars['alreadyregistered'] > 0){
				  $('#messagearea').html("<p class='registermsg'>You are already registered for '" + returnvars['coursetitle'] +"' which begins on "+ returnvars['startdate']+".</p>"); 	  
					}
				  //else if the user is logged in
                  else if (returnvars['currentuser'] > 0){
					 //show a description and welcom message in the div
				    $('#coursedescription').html("<p><strong>You are registering for <em>'" +returnvars['coursetitle'] +"'</em> starting on "+returnvars['startdate']+".</strong></p><p><br><strong>Description: </strong>" + returnvars['coursedescription']+"</p><p><br><strong>To register, please provide the information below. Thanks.</strong><br><br></p>" );   
					  //create a registration form and show it in the div  
					var form = $("<form/>", 
                       { id:'addtocourse' }
                    );
					form.append( 
						$("<label />", 
					    {      text:'What is your knowledge of this LERN topic?',
							   style:'width:80%' }
						 )
					);
					form.append( 
						$("<textarea />", 
					    {      id:'currentknowledge',
							   placeholder:'Please enter your current understanding of the topic. ' , 
							   name:'currentknowledge', 
							   style:'width:80%' }
						 )
					);
					form.append(
					     $("<br />", 
					        { }
						   )
					);
					form.append( 
						$("<label />", 
					    {      text:'Describe your current work.',
							   style:'width:30%;' }
						 )
					);
					form.append( 
						$("<input />", 
					    {      id:'currentwork',
							   name:'currentwork', 
							   placeholder:'Please describe your current work. ', 
							   style:'width:65%; margin-left: 30px;',
							   value: returnvars['currentwork']
							   }
						 )
						
					);
					form.append(
					     $("<br />", 
					        { }
						   )
					);
				    form.append( 
						$("<label />", 
					    {      text:'Describe who/where you serve.',
							   style:'width:30%;' }
						 )
					);
					form.append( 
						$("<input />", 
					    {      id:'whoserve',
							   name:'whoserve', 
							   placeholder:'Please describe who/where you currently serve. ', 
							   style:'width:65%;margin-left: 58px;',
							   value: returnvars['whoserve'] }
						 )
					);
					form.append(
					     $("<br />", 
					        { }
						   )
					);

					form.append( 
						 $("<input>", 
							  { type:'button', 
								value:'Submit', 
								id:'submit_course_registration',
								style:'width:20%' }
						   )
					);
						 if (returnvars['uploadavatar'] == 1){
						  //show a message requiring avatar uploadavatar
						  $("#registration_message").html("<p><strong>Please upload your picture. This helps the coach and others get to know you! Thanks.</strong></p>");
						 // $("#registrationform").hide();
						  $( "#registration_avatarupload" ).show();
						  }
					$("#registrationform").append(form);   
				   
				   //get button click on the form
                       $("#submit_course_registration").click(function() {
					   var validatemsg="";
					   if ($('#currentknowledge').val() == "" ){validatemsg+="Please describe your current knowledge of the topic.<br>";}
					   if ($('#currentwork').val() == "" ){validatemsg+="Please enter a description of your current work.<br>";}
					   if ($('#whoserve').val() == "" ){validatemsg+="Please enter a description of who you serve.<br>";}
						   if (validatemsg !=""){
							$('#validatearea').html("<p class='validatemsg'>"+ validatemsg +"</p>");
						   }
						   else{   //form was valid so process it
							var data = $('#addtocourse').serialize();
						    data+="&action=addusertocourse&coursekey="+coursekey;
							console.log(baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php'+data);
									$.ajax({
									   type: "POST",
									   url: baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php',
									   dataType: 'json', 
									   data: data,
									   success: function(response){
										 $("#registrationform").hide();
										 $("#coursedescription").hide();
										 $("#registration_message").hide();
										 $("#validatearea").hide();
										 $("#registration_avatarupload").hide();
										 $("#messagearea").html("<p class='registermsg'>You have been successfully added to the course and should receive a confirmation email shortly.</p>");
										},
										error: function(xhr, textStatus, errorThrown){
											alert(textStatus);
										},
								});//end ajax call   
							 }//end else the data is valid
						});//end button click function
				   }
				   else{
					//show a message to either login or register
                   $('#messagearea').html("<p class='registermsg'>You must log in to sign up for '" + returnvars['coursetitle'] +"'. <br>If you do not have an account please create one now.<br>Use the form on the left to login or create an account.</p>"); 					
				   }
				   },
				    error: function(xhr, status,error) {
					console.log("There was an error" + error);
					}
            });//end ajax call 
}
else{
$('#messagearea').html("<p class='registermsg'>Sorry, you do not have a valid enrollment link and therefore cannot register.</p>");	
}
}//end if we are on the course registration page
//show generic fancybox
$('#join-waiting-list').click(function(e){  
var courseid=$(this).attr('data-courseid');
$('#waitlistcourse').val(courseid);
$.fancybox({
		'padding':  20,
		'autoScale':true,
		'autoDimensions':true,
		'showCloseButton':true,
        'href': '#addtowaitinglist', 
        'modal': false,
		
    });
return false;
});
$('#submitwaitlist').click(function(e){
e.preventDefault();
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var validatemsg="";
			if ($('#waitlistemail').val() == "" ){validatemsg+="Please enter your email address.<br>";}
			if ($('#waitlistname').val() == "" ){validatemsg+="Please enter your name<br>";}
			if (validatemsg !=""){
				$('#validatearea').html( validatemsg );
			}
           else{   //form was valid so process it
							var data = $('#waitlistform').serialize();
						    data+="&action=savetowaitlist";
									$.ajax({
									   type: "POST",
									   url: urltoget ,
									   dataType: 'json', 
									   data: data,
									   success: function(response){
										 if (response['success'] == 1){
										 $("#validatearea").html("You have successfully been added to the waiting list! Thank you.");
										 }
										 else{
											$("#validatearea").html("There was a problem adding you to the list. Please contact us."); 
										 }
										},
										error: function(xhr, textStatus, errorThrown){
											alert(textStatus);
										},
								});//end ajax call   
			 }//end else the data is valid
	return false;
});
/*
-------------------
Coaches corner
-------------------
*/
if (current_page.indexOf('coachs-corner') > -1 ) {
//Create the chart
var baseURL = window.location.protocol+"//"+window.location.host;
//first get percentage chart
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var courseid=$( "div.courseid" ).text();
	$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_course_percentages','courseid': courseid},
                   success: function(response){
					if (response['hasdata'] == 0){
					//$("#progressbystudentcontainer").html("");	
					}
					else{
				    //draw the chart
					$("#progressbystudentcontainer").html(response['progressbystudent']);
			        var arr20 = response['progress'][0]['names'].split(',');
					var arr20count=arr20.length -1;
					var arr40 = response['progress'][1]['names'].split(',');
					var arr40count=arr40.length -1;
					var arr60 = response['progress'][2]['names'].split(',');
					var arr60count=arr60.length-1;
					var arr80 = response['progress'][3]['names'].split(',');
					var arr80count=arr80.length-1;
					var arr100 = response['progress'][4]['names'].split(',');
					var arr100count=arr100.length-1;
					//create listing by name based on %
					var studentsbypercent="<br><h6>0-20% Complete</h6>" + response['progress'][0]['names'] +"<br><br><h6>20-40% Complete</h6>" + response['progress'][1]['names'] + "<br><br><h6>40-60% Complete</h6>" + response['progress'][2]['names'] + "<br><br><h6>60-80% Complete</h6>" + response['progress'][3]['names'] + "<br><br><h6>80-100% Complete</h6>" + response['progress'][4]['names'];
					$("#progressbystudentcontainer").html(studentsbypercent);
					$('#progresschartcontainer').highcharts({
									    chart: {
										type: 'pie',
										options3d: {
										enabled: true,
										alpha: 45,
										beta: 0
										}
										},
										title: {
											text: 'LERN completion'
										},
										plotOptions: {
											pie: {
												allowPointSelect: true,
												cursor: 'pointer',
												depth: 35,
												dataLabels: {
													enabled: true,
													format: '{point.name}'
												}
											}
										},
										series: [{
											type: 'pie',
											name: 'Percent of all Lerners at this level ',
											data: [{
											name: arr20count+ ' Lerners are 0%-20% complete.',
											 y: Math.round(response['progress'][0]['percent']),
											 sliced: true,
										     selected: true
											}, 
											{
											name: arr40count+ ' Lerners are 20%-40% complete.',
											y: Math.round(response['progress'][1]['percent']),
											}, {
											name: arr60count+ ' Lerners are 40%-60% complete.',
											y: Math.round(response['progress'][2]['percent']),
											}, {
											name: arr80count+ ' Lerners are 60%-80% complete.',
											y: Math.round(response['progress'][3]['percent']),
											}, {
											name: arr80count+ ' Lerners are 80%-100% complete.',
											y: Math.round(response['progress'][4]['percent']),
										   }]
										}]
								});
								
				  		}//end else there was data
							   },
								error: function(xhr, textStatus, errorThrown){
									alert("error was " + textStatus + errorThrown);
								},
								complete: function(response){
								},

            });//end ajax call 
}
//capture coaches corner button clicks
//Show the LERN discussion topics from the Network page
$('#showdiscussiontopics').click(function(e){
var baseURL = window.location.protocol+"//"+window.location.host;
//first get percentage chart
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var courseid=$( "div.courseid" ).text();
$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_associated_topics','courseid': courseid},
                   success: function(response){
                    var htmloutput = response['returnhtml'];
				    $("#qaprogressbyquestion").hide();
					$("#qaprogressbystudent").hide();
					$("#byquestion_bystudent").hide();
                    $("#progresschartcontainer").hide();
					$("#progressbystudentcontainer").hide();
					$("#currenttopics").html(htmloutput);
                    $("#addatopiccontainer").show();
					},
					error: function(xhr, textStatus, errorThrown){
						console.log( textStatus + " " + errorThrown);
					},
			});//end ajax call 	
});
//submit LERN discussion topic to Network page
$('#submitcommenttopic').click(function(e){
e.preventDefault();
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var validatemsg="";
			if ($('#topic-title').val() == "" ){validatemsg+="Please enter the title.<br>";}
			if ($('#topic-description').val() == "" ){validatemsg+="Please enter the discussion topic.<br>";}
			if (validatemsg !=""){
				$('#validatearea').html( validatemsg );
			}
           else{   //form was valid so process it
							var data = $('#addtopicform').serialize();
						    data+="&action=addatopic";
									$.ajax({
									   type: "POST",
									   url: urltoget ,
									   dataType: 'json', 
									   data: data,
									   success: function(response){
										 if (response['success'] > 0){
										 $("#validatearea").html("You have successfully added a topic.");
										 var linkstring ='<a href="#" onclick="return deletetopic('+response['success']+');"><img src="/wp-content/uploads/2014/10/deleteicon.png" width="20" height="20">Remove this topic</a><strong>&nbsp;|&nbsp;'+response["topic_title"]+ ', '+response["topic_description"]+'</strong>';
										 $("#currenttopics").append(linkstring);
										 }
										 else{
											$("#validatearea").html("There was a problem creating the topic. Please contact us."); 
										 }
										},
										error: function(xhr, textStatus, errorThrown){
											alert(textStatus+errorThrown);
										},
								});//end ajax call   
			 }//end else the data is valid
	return false;

});

//show LERN q/a by student
$('#showqabystudent').click(function(e){
$("#qaprogressbyquestion").hide();
$("#qaprogressbystudent").html("<img src='/wp-content/uploads/2017/02/page-loader.gif'><br>Working on it...");
$("#qaprogressbystudent").show();
var baseURL = window.location.protocol+"//"+window.location.host;
//first get percentage chart
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var courseid=$( "div.courseid" ).text();
$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_qa_by_student','courseid': courseid},
                   success: function(response){
                    var htmloutput = response['returnhtml'];
					$("#qaprogressbyquestion").hide();
					$("#addatopiccontainer").hide();
					$("#qaprogressbystudent").html(htmloutput);
					$("#qaprogressbystudent").show();
					$("#progressbystudentcontainer").hide();
				     },
					error: function(xhr, textStatus, errorThrown){
						alert(textStatus);
					},
					
            });//end ajax call 
});
$('#showqabyquestion').click(function(e){
var baseURL = window.location.protocol+"//"+window.location.host;
$("#qaprogressbystudent").hide();
$("#qaprogressbyquestion").html("<img src='/wp-content/uploads/2017/02/page-loader.gif'><br>Working on it...");
 $("#qaprogressbyquestion").show();
//first get percentage chart
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var courseid=$( "div.courseid" ).text();
	$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_qa_by_question','courseid': courseid},
                   success: function(response){
				    $("#qaprogressbystudent").hide();
					$("#qaprogressbyquestion").html("<br>Q/A completion so far: "+ response['totalfinished']+" out of "+response['totalstudents']+" lerners.<br><br><strong>"+response['q1']+"</strong><br><span style='background: rgba(164,179,87,1);height: auto;padding-left: 3px;padding-top: 3px;padding-bottom: 3px;'>"+response['q1mastered']+" student(s) selected mastered. </span><br><span style='background: rgba(241,231,103,1);height: auto;padding-left: 3px;padding-bottom: 3px;'>"+response['q1proficient']+" student(s) selected proficient. </span><br><span style='background: rgba(248,80,50,1);height: auto;padding-left: 3px;padding-bottom: 3px;'>"+response['q1developing']+" student(s) selected developing.</span><br><br><strong>"+response['q2']+"</strong><br><span style='background: rgba(164,179,87,1);height: auto;padding-left: 3px;padding-top: 3px;padding-bottom: 3px;'>"+response['q2mastered']+" students selected mastered. </span><br><span style='background: rgba(241,231,103,1);height: auto;padding-left: 3px;padding-bottom: 3px;'>"+response['q2proficient']+" students selected proficient. </span><br> <span style='background: rgba(248,80,50,1);height: auto;padding-left: 3px;padding-bottom: 3px;'>"+response['q2developing']+" students selected developing.</span><br>");
			        $("#qaprogressbyquestion").show();

								
					},
					error: function(xhr, textStatus, errorThrown){
						alert(textStatus);
					},
					
            });//end ajax call 
});
//capture coaches corner clicks
$('#showstudentqamenu').click(function(e){
var baseURL = window.location.protocol+"//"+window.location.host;
//first get percentage chart
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var courseid=$( "div.courseid" ).text();
	$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'check_answers_exist','courseid': courseid},
                   success: function(response){
					if (response['haveanswers'] =='Yes' || response['studentsenrolled'] =='Yes' ){
				    //see if there are any answers yet for the topic
						$("#progresschartcontainer" ).hide();
						$("#progressbystudentcontainer").hide();
						$("#addatopiccontainer").hide();
						$("#byquestion_bystudent" ).show();
					}
					else{
					$("#progressbystudentcontainer").html("<h4>" + response['haveanswers'] + " " +response['studentsenrolled']+"<h4>");
					$("#progressbystudentcontainer").show();	
					$("#addatopiccontainer").hide();
					$("#byquestion_bystudent" ).hide();
					$("#progresschartcontainer" ).hide();
					}
					},
					error: function(xhr, textStatus, errorThrown){
						alert(textStatus + errorThrown);
					},
					
            });//end ajax call 	
});

$('#gototopic').click(function(e){
var baseURL = window.location.protocol+"//"+window.location.host;
//first get percentage chart
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var courseid=$( "div.courseid" ).text();
	$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_course_main_page','courseid': courseid},
                   success: function(response){
				    var intropagepath=response['intropagepath'];
					$(location).attr("href", intropagepath);
					},
					error: function(xhr, textStatus, errorThrown){
					console.log(textStatus);
					},
			 });//end ajax call 
});

$('#showpercentcompleted').click(function(e){
$( "#progresschartcontainer" ).show();
$("#progressbystudentcontainer").show();
$( "#byquestion_bystudent" ).hide();
 $("#qaprogressbystudent").hide();
 $("#qaprogressbyquestion").hide();
 $("#addatopiccontainer").hide();
});
/*-----------------
LERN network page
------------------*/
/*
-------------------
links to multiple iframe comment pages LERN
-------------------
*/
//create links for multiple discussion iframes with comment topic posts
$('a.dicussiframe').click(function(e){
//get the iframe info with postid from the link
var iframeurl = baseURL +"?p="+$(this).attr("data-postid");
$('#commentiframepanel').html('<iframe id="commentiframe1" src="'+iframeurl+'" width=100% height=1500 frameborder=0 allowfullscreen scrolling="auto"></iframe>');
$("#iframeloading").show(0).delay(2000).hide(0);
e.preventDefault();
});	
/*
-------------------
Module choices from pd hub module report page
-------------------
*/
$('#btnSubmitModuleChoices').click(function(e){
var current_page = $(location).attr('href');
var baseURL = window.location.protocol+"//"+window.location.host;
//check the select list first	
var group
var checkedValues="";
checkedValues= $('input:checkbox:checked').map(function() {
return this.value;
}).get();
var selectListVal=$('#pd_hub_roster_select').val();
var groupID = $('#roster_group').val();
if (selectListVal > 0){
	group=selectListVal;
	}
else{
	group=groupID;
}
if (checkedValues==""){alert("Please select the module(s) for this report.");}
else{
//create a qs
var PageToLoad = baseURL+="/student-progress/?id="+group+"&course_ids="+checkedValues;
window.location.href =PageToLoad ;
}
});
/*
-------------------
Subscribe to bbpress forums
-------------------
*/
$('#subscribe_to_forum').click(function(e){
	var user_id = $(this).data('userid');
    var forum_post_id =$(this).data('postid'); 
	var baseURL = window.location.protocol+"//"+window.location.host;
    var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
	$.ajax({
                   type: "POST",
                   url: baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php',
                   dataType: 'json', 
                   data: {'action':'subscribe_user_to_forum', 'user_id':user_id, 'forum_post_id':forum_post_id},
                   success: function(response){
				 	},
					error: function(xhr, textStatus, errorThrown){
						alert(textStatus);
					},
					complete: function(response){
					alert("You were subscribed to this forum topic!");
					location.reload();
					},
            });//end ajax call 
	
});
/*
-------------------
Unsubscribe to bbpress forums
-------------------
*/
$('#unsubscribe_to_forum').click(function(e){
	var user_id = $(this).data('userid');
    var forum_post_id =$(this).data('postid'); 
	var baseURL = window.location.protocol+"//"+window.location.host;
    var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
	$.ajax({
                   type: "POST",
                   url: baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php',
                   dataType: 'json', 
                   data: {'action':'unsubscribe_user_to_forum', 'user_id':user_id, 'forum_post_id':forum_post_id},
                   success: function(response){
				 	},
					error: function(xhr, textStatus, errorThrown){
						alert(textStatus);
					},
					complete: function(response){
						alert("You were unsubscribed to this forum topic!");
					location.reload();
					},
            });//end ajax call 
	
});
/*
-------------------
Implementation and reflection plan
-------------------
*/
$('a.printreflectionandimplementation').click(function(e){
	//set user cookies if not logged in or not a user otherwise get user information from the user id (data on link)
	//php saves email state and role for the link
		e.preventDefault();
	        var divContents = $("#reflectionplansummary").html();
            var printWindow = window.open('', '', 'height=1100,width=900');
            printWindow.document.write('<html><head><title>Reflection and Implementation Plan</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
});
/*
-------------------
Print LERN roster
-------------------
*/
$('a.embedprintroster').click(function(e){

	//set user cookies if not logged in or not a user otherwise get user information from the user id (data on link)
	//php saves email state and role for the link
		e.preventDefault();
	        var divContents = $("#lerncourseroster").html();
            var printWindow = window.open('', '', 'height=1100,width=900');
            printWindow.document.write('<html><head><title>LERN Roster</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
});
/*
-------------------
Print LERN group checklist needs
-------------------
*/
$('a.embedprintchecklistneeds').click(function(e){
	//set user cookies if not logged in or not a user otherwise get user information from the user id (data on link)
	//php saves email state and role for the link
		e.preventDefault();
	        var divContents = $("#lernchecklistresults").html();
            var printWindow = window.open('', '', 'height=1100,width=900');
            printWindow.document.write('<html><head><title>LERN checklist needs</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
});
/*
-------------------
Print LERN next steps
-------------------
*/
$('a.embednextsteps').click(function(e){
	//set user cookies if not logged in or not a user otherwise get user information from the user id (data on link)
	//php saves email state and role for the link
		e.preventDefault();
	        var divContents = $("#lernnextsteps").html();
            var printWindow = window.open('', '', 'height=1100,width=900');
            printWindow.document.write('<html><head><title>LERN next steps</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
});
/*
-------------------
Print LERN challenges
-------------------
*/
$('a.embedchallenges').click(function(e){
	//set user cookies if not logged in or not a user otherwise get user information from the user id (data on link)
	//php saves email state and role for the link
		e.preventDefault();
	        var divContents = $("#lernchallenges").html();
            var printWindow = window.open('', '', 'height=1100,width=900');
            printWindow.document.write('<html><head><title>LERN Challenges</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
});
/*
-------------------
Capture login date and time
-------------------
*/
$('a.captureLinkClickLoggedIn').click(function(e){
	//set user cookies if not logged in or not a user otherwise get user information from the user id (data on link)
	//php saves email state and role for the link
		e.preventDefault();
		var currentuserid = $(this).data('currentuserid');
        var selectedpostid =$(this).data('selectedpostid'); 
		var currentpage = $(this).attr("href");
		var baseURL = window.location.protocol+"//"+window.location.host;
        var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
	    if (currentuserid > 0){
	      $.post(urltoget, {'action':'capture_loggedin_linkclick','post_id':selectedpostid, 'current_page':currentpage}, function(ret){
                var clickedLink = ret;
				var newwindow = window.open(clickedLink,'_blank');
			    newwindow.focus();		
			    return false;
		
		});
	  }
	  else{
	  var newwindow = window.open(currentpage,'_blank');
	  newwindow.focus();		
      return false;	
	  }
});
/*
-------------------
Alert to close windows when user starts a pre or post test
-------------------
*/
if (current_page.indexOf('pre-test') > -1 || current_page.indexOf('post-test') > -1 ){
var urlParts = current_page.split('/');
var arraypos = (urlParts.length) -2;
var pageName = urlParts[arraypos];
//see if the user has finished the test yet based on the page name
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
 //return the html string for the dialog box
        $.post(urltoget,{'action':'check_exists_pre_post','pageName':pageName,}, function(ret){
	var obj = jQuery.parseJSON( ret );
        var pre_post_exists =obj.exists;
	if (pre_post_exists == 0){
	alert("BEFORE YOU START!!! Close all open browsers except this one before taking the test. Otherwise your answers will not be saved correctly. Thanks!");
	}
})
}

//Code from original thgeme js scripts.js
/*  Toggle header search
/* ------------------------------------ */
	$('.toggle-search').click(function(){
		$('.toggle-search').toggleClass('active');
		$('.search-expand').fadeToggle(250);
            setTimeout(function(){
                $('.search-expand input').focus();
            }, 300);
	});
	
/*  Scroll to top
/* ------------------------------------ */
	$('a#back-to-top').click(function() {
		$('html, body').animate({scrollTop:0},'slow');
		return false;
	});
	
/*  Tabs widget
/* ------------------------------------ */	
	(function() {
		var $tabsNav       = $('.alx-tabs-nav'),
			$tabsNavLis    = $tabsNav.children('li'),
			$tabsContainer = $('.alx-tabs-container');

		$tabsNav.each(function() {
			var $this = $(this);
			$this.next().children('.alx-tab').stop(true,true).hide()
			.siblings( $this.find('a').attr('href') ).show();
			$this.children('li').first().addClass('active').stop(true,true).show();
		});

		$tabsNavLis.on('click', function(e) {
			var $this = $(this);

			$this.siblings().removeClass('active').end()
			.addClass('active');
			
			$this.parent().next().children('.alx-tab').stop(true,true).hide()
			.siblings( $this.find('a').attr('href') ).fadeIn();
			e.preventDefault();
		}).children( window.location.hash ? 'a[href=' + window.location.hash + ']' : 'a:first' ).trigger('click');

	})();
	
/*  Comments / pingbacks tabs
/* ------------------------------------ */	
    $(".comment-tabs li").click(function() {
        $(".comment-tabs li").removeClass('active');
        $(this).addClass("active");
        $(".comment-tab").hide();
        var selected_tab = $(this).find("a").attr("href");
        $(selected_tab).fadeIn();
        return false;
    });

/*  Table odd row class
/* ------------------------------------ */
	$('table tr:odd').addClass('alt');

/*  Sidebar collapse
/* ------------------------------------ */
	$('body').addClass('s1-collapse');
	$('body').addClass('s2-collapse');

	$('.s1 .sidebar-toggle').click(function(){
		$('body').toggleClass('s1-collapse').toggleClass('s1-expand');
		if ($('body').is('.s2-expand')) { 
			$('body').toggleClass('s2-expand').toggleClass('s2-collapse');
		}
	});
	$('.s2 .sidebar-toggle').click(function(){
		$('body').toggleClass('s2-collapse').toggleClass('s2-expand');
		if ($('body').is('.s1-expand')) { 
			$('body').toggleClass('s1-expand').toggleClass('s1-collapse');
		}
	});

/*  Dropdown menu animation
/* ------------------------------------ */
	$('.nav ul.sub-menu').hide();
	$('.nav li').hover( 
		function() {
			$(this).children('ul.sub-menu').slideDown('fast');
		}, 
		function() {
			$(this).children('ul.sub-menu').hide();
		}
	);
	
/*  Mobile menu smooth toggle height
/* ------------------------------------ */	
	$('.nav-toggle').on('click', function() {
		slide($('.nav-wrap .nav', $(this).parent()));
	});
	 
	function slide(content) {
		var wrapper = content.parent();
		var contentHeight = content.outerHeight(true);
		var wrapperHeight = wrapper.height();
	 
		wrapper.toggleClass('expand');
		if (wrapper.hasClass('expand')) {
		setTimeout(function() {
			wrapper.addClass('transition').css('height', contentHeight);
		}, 10);
	}
	else {
		setTimeout(function() {
			wrapper.css('height', wrapperHeight);
			setTimeout(function() {
			wrapper.addClass('transition').css('height', 0);
			}, 10);
		}, 10);
	}
	 
	wrapper.one('transitionEnd webkitTransitionEnd transitionend oTransitionEnd msTransitionEnd', function() {
		if(wrapper.hasClass('open')) {
			wrapper.removeClass('transition').css('height', 'auto');
		}
	});
	}


//end code from scripts.js

/*
-------------------
Search users on admin page
-------------------
*/
if (current_page.indexOf('wp-admin/users.php') > -1){
$( "#user-search-input" ).autocomplete({
      minLength: 2,
	  source: function( request, response ) {
	    var baseURL = window.location.protocol+"//"+window.location.host;
        var urltoget = baseURL+"/wp-content/themes/hueman-child/autocompleteAjax.php";
          var term = request.term;
		  $.getJSON(urltoget, request, function( data, status, xhr ) {
          response( data );
	    });
	  },
	  select: function( event, ui ) {
	  //set the value of the search input 
		 $("#user-search-input").text(ui.item.value);
      },	  
});
}
/*
-------------------
Student progress on lern, pop up a summary sheet on my lerns
-------------------
*/
if (current_page.indexOf('my-lerns') > -1) {
$(".print_summary_sheet").click(function(event) {
event.preventDefault();
//get the data for the dialog box
var course_id = $(this).data('courseid');
var user_id = $(this).data('userid');
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
 //return the html string for the dialog box
	$.post(urltoget, {'action':'get_lern_summary_sheet_data','user_id':user_id,'course_id':course_id}, function(ret){
     var summary_html = ret;
      //create the dialog and set up the print
	$("#summary_sheet").dialog(
	{
	width: 700,
	modal: true,
	title: "Summary Sheet",
        height: 850,
	},
	{ position: { my: "left top", at: "left 0, top +10", of: wrapper}},
        { buttons: [{ text: "Close", click: function() { $( this ).dialog( "close" ); } },{ text: "Print", click: function() { var mywindow = window.open();
         mywindow.document.write($('#box_msg').html());
         mywindow.print();   mywindow.close(); } }  ] });
        $( "#box_msg" ).html(summary_html);
        $( "#summary_sheet" ).dialog( "open" );
})
});
}

/*
-------------------
Student progress on modules pop up a summary sheet on my modules
-------------------
*/
if (current_page.indexOf('student-progress') > -1 || current_page.indexOf('user-module-progress') > -1 || current_page.indexOf('self-study-progress') > -1  ) {
$(".print_summary_sheet").click(function(event) {
event.preventDefault();
//get the data for the dialog box
var course_id = $(this).data('courseid');
var user_id = $(this).data('userid');
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
 //return the html string for the dialog box
	$.post(urltoget, {'action':'get_summary_sheet_data','user_id':user_id,'course_id':course_id}, function(ret){
        var summary_html = ret;
	//create the dialog and set up the print
	$("#summary_sheet").dialog(
	{
	width: 700,
	modal: true,
	title: "Summary Sheet",
        height: 850,
	},
	{ position: { my: "left top", at: "left 0, top +10", of: wrapper}},
        { buttons: [{ text: "Close", click: function() { $( this ).dialog( "close" ); } },{ text: "Print", click: function() { var mywindow = window.open();
         mywindow.document.write($('#box_msg').html());
         mywindow.print();   mywindow.close(); } }  ] });
        $( "#box_msg" ).html(summary_html);
        $( "#summary_sheet" ).dialog( "open" );
})
});
/*
-------------------
Handle summary sheet click button on rosters
-------------------
*/
$(".show_student_course_summary").click(function(event) {
event.preventDefault();
var position = $(this).offset();
var top = position.top;
var left = position.left;
var params={};
window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(str,key,value){params[key] = value;});
var course_ids = params['course_ids'];
var user_id = $(this).data('userid');
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
$.ajax({
		url: urltoget,
		type: 'POST',
		dataType:'json',
		data: {'action':'get_student_course_summary','user_id': user_id,'course_ids':course_ids,'top':top,'left':left},
		success: function(ret){ 
	              var summary = ret['summary'];
	              var top = ret['top'];
				  var left = ret['left'];
				  var studentname = ret['studentname'];
				  //create the dialog and set up the print
					$("#student_course_summary").dialog(
					{
					width: 900,
					modal: true,
					title: "All selected modules for " + studentname,
					height: 300,
					},
					{ position: { my: "left top", at: "left "+left+", top "+top, of: wrapper}},
					{ buttons: [{ text: "Close", click: function() { $( this ).dialog( "close" ); } }  ] });
      
						$( "#student_course_summary_box_msg" ).html(summary);
						$( "#student_course_summary" ).dialog( "open" );
		}
	 })
});

$(".print_summary_sheet_legacy").click(function(event) {
event.preventDefault();
	//create the dialog and set up the print
	$("#summary_sheet").dialog(
	{
	width: 700,
	modal: true,
	title: "Summary Sheet",
        height: 850,
	},
	{ position: { my: "left top", at: "left 0, top +10", of: wrapper}},
        { buttons: [{ text: "Close", click: function() { $( this ).dialog( "close" ); } },{ text: "Print", click: function() { var mywindow = window.open();
         mywindow.document.write($('#box_msg').html());
         mywindow.print();   mywindow.close(); } }  ] });
        $( "#box_msg" ).html($("#print_module_sheet").html());
        $( "#summary_sheet" ).dialog( "open" );
});

$('#pd_hub_roster_list').change(function(event) {
	event.preventDefault();
	var groupid = $('#pd_hub_roster_select').val();
	var selected_url = "?id="+groupid;
	//append group id value to href
	window.location.href = selected_url;

});
}
//handle student-qi-progress page
if(current_page.indexOf('student-qi-progress') > -1){
     $('#pd_hub_roster_list').change(function(event) {
	event.preventDefault();
	var groupid = $('#pd_hub_roster_select').val();
	var selected_url = "?id="+groupid;
	//append group id value to href
	window.location.href = selected_url;
	});	
}
//show the rosters list and handle functions from a roster
if (current_page.indexOf('display-roster-list') > -1){
    $('#pd_hub_roster_list').change(function() {
	var userid = $('#pd_hub_roster_select').val();
	var selected_url = "?id="+userid;
	window.location.href = selected_url;
	});
	$(".confirmDeleteRoster").click( function(event) {
	event.preventDefault();
	var groupid = $(this).attr('id');
	if (groupid > 0){
		//Define the Dialog and its properties
		$(function() { $('#deleteRosterForm').dialog({
		 		resizable: false,
				modal: true,
				title: "Alert",
				height: 250,
				width: 400,
				buttons: {
					"Yes" : function(){
						var pluginAjaxURL = baseURL +'/wp-content/plugins/user-groups-frontend/processGroupFrontendAjax.php';
						$('#rostergroupid').val(groupid);
						var data = $('#deleteRosterForm').serialize();
						$(this).dialog('close');
						$.ajax({
							url: pluginAjaxURL,
							type: 'POST',
							dataType:'json',
							data : data,
							success: function(response){
				 			},
							error: function(xhr, textStatus, errorThrown){
							alert(textStatus);
							},
							complete: function(response){
							alert("This roster was deleted");
							location.reload();
							},
							});	
								 			
						},
					   "No" : function(){
						$(this).dialog('close');
						alert("This roster was not deleted");
						return false;
						}
				}
			});
			
		});
	}
	});
}
//image map for standardized assessments (course unit)
if (current_page.indexOf('standardized-assessments') > -1){
$('#area1').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_2.png');
	 
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_1.png');
        }
  );
$('#area2').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_3.png');
	  
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_1.png');
        }
  );
  $('#area3').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_4.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_1.png');
        }
  );
  $('#area4').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_5.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_1.png');
        }
  );
  $('#area5').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_6.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_1.png');
        }
  );
   $('#area6').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/moduleshollhex_7.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/hollhex_1.png');
        }
  );
  
}
//map for effective vocational programs (course unit)
else if(current_page.indexOf('effective-vocational-programs') > -1){
$('#area1').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp2.png');
	 
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp1.png');
        }
  );
$('#area2').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp3.png');
	  
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp1.png');
        }
  );
  $('#area3').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp4.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp1.png');
        }
  );
  $('#area4').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp5.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp1.png');
        }
  );
  $('#area5').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp6.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp1.png');
        }
  );
   $('#area6').hover(
        function() {
	 $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp7.png');
        },
        function() {
          $('img[usemap]').attr('src', baseURL + '/wp-content/originalSiteAssets/images/modules/evp1.png');
        }
  );
}
//set up the accordian that shows the form to pick modules from
else if(current_page.indexOf('student-progress') > -1 ){
$( "#nestedAccordionModule" ).accordion({
      collapsible: true,
	  active: false,
	  });
}
//print action plan summary
else if(current_page.indexOf('action-plan-summary') > -1){
//handle the print action plan
$(".printactionplan" ).click(function() {
var textStream = $('#actionPlanSummaryPrint').html();
w=window.open();
w.document.write(textStream);
w.print();
w.close();
});
}
//module course progress display or final steps
else if(current_page.indexOf('module-progress/?courseid') > -1 || current_page.indexOf('final-steps') > -1){
$("#showPostTestLink").click(function(event){
 event.preventDefault();
if ($('#postTestSection').css("visibility") =='hidden'){
$(this).html('<a href="#">Hide Post Test</a>');
$('#postTestSection').css("visibility","visible");
$('#print_module_sheet').css("visibility","hidden");
$('#activityContentSection').css("visibility","hidden");
}
else{
$(this).html('<a href="#">Show Post Test</a>');
$('#postTestSection').css("visibility","hidden");
$('#print_module_sheet').css("visibility","hidden");
$('#activityContentSection').css("visibility","hidden");
}
});
//handle hide and show the activities click
$("#showActivitySummaryLink").click(function(event){
 event.preventDefault();
if ($('#activityContentSection').css("visibility") =='hidden'){
$(this).html("<a href='#'>Hide Activities</a>");
$('#activityContentSection').css("visibility","visible");
$('#print_module_sheet').css("visibility","hidden");
$('#postTestSection').css("visibility","hidden"); 
return false;
}
else{
$(this).html("<a href='#'>Show Activities</a>");
$('#activityContentSection').css("visibility","hidden");
$('#print_module_sheet').css("visibility","hidden");
$('#postTestSection').css("visibility","hidden"); 
return false;
}
});

//handle the print post test click
$(".printposttest" ).click(function() {
w=window.open();
w.document.write('<p>Summary Sheet</p><div id="postTestSummary">' + $('#printSummaryHeader').html()+'</div><p>Activity Questions</p><div id="activityContentSection">' + $('#activityContentSection').html()+'</div>');
w.print();
w.close();
});
//handle email module sumamry
$(".emailModuleSummary").click(function(){
jPrompt('Enter the email address where you want us to send your summary and post test:', 'Type the email address here', 'Email recipient for your post test and summary', function(r) {
     if( r ) {
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      var isValidEmail = pattern.test(r);
      if (!isValidEmail){
      alert ("Please enter a valid email address");
      }
      else{
      //get the text into a string and send it via ajax to send an html encypted email to the recipient
      var courseid = $('.emailModuleSummary').attr('id');
      var emailAddress = r; 
      var emailMessage = '<p>Summary Sheet</p><div id="postTestSummary">' + $('#printSummaryHeader').html()+'</div><p>Activity Questions</p><div id="activityContentSection">' + $('#activityContentSection').html()+'</div>';
      var baseURL = window.location.protocol+"//"+window.location.host;
      var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
	$.post(urltoget, {'action':'send_summary_email','sendto':emailAddress,'message':emailMessage,'courseid':courseid}, function(ret){
 	alert(ret + ' Your email was sent');
	});
}
}
});
});
}
/* ------------------------------------ */
 else if(current_page.indexOf('transition-tips') > -1){
//get a total for the current tips in the database and display it
/* ------------------------------------ */
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var data="action=load_tips";
$.ajax({
		url: urltoget,
		type: 'POST',
		dataType:'json',
		data : data,
		success: function(ret){
	        var content_area =ret['content_area'];
	        var result_summary = ret['result_summary'];
	        // alert windows shows the returned value from the php
                $("#tips_results").html(content_area);
                $(".resultSummary").html(result_summary);
	      }
	});

}//end this is transition tips page

/* ------------------------------------ */
 else if(current_page.indexOf('missouri-agency-search') > -1){
//get a total for the current tips in the database and display it
/* ------------------------------------ */
var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
var data="action=load_agencies";
$.ajax({
		url: urltoget,
		type: 'POST',
		dataType:'json',
		data : data,
		success: function(ret){
	        var content_area =ret['content_area'];
	        var result_summary = ret['result_summary'];
	        // alert windows shows the returned value from the php
                $("#agency_results").html(content_area);
                $(".resultSummary").html(result_summary);
	      }
	});

}//end this is transition tips page



/* ------------------------------------ */
//handle showing the users qi survey list in their portfolio
/* ------------------------------------ */
else if (current_page.indexOf('portfolio-the-qi-survey') > -1){
var baseURL = window.location.protocol+"//"+window.location.host;
           $.ajax({
                   type: "GET",
                   url: baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php',
                   dataType: 'json', 
                   data: {'action':'get_user_qi_quiz_list'},
                   success: function(returndata) {
                   $("#portfolio_qi_header").html(returndata['htmldisplay1']); 
                   $("#portfolio_qi_listing").html(returndata['htmldisplay2']);  
                   }
            });//end ajax call              
}//end if it is the qi portfolio list

/* ------------------------------------ */
//handle showing the summary of the qi quiz to the user
/* ------------------------------------ */
else if(current_page.indexOf('qi-results') > -1){
//get the qi results based on the query string 
if(current_page.indexOf("?surveyref") != -1){
var queries = {}; 
$.each(document.location.search.substr(1).split('&'),function(c,q){ var i = q.split('='); queries[i[0].toString()] = i[1].toString(); });
var baseURL = window.location.protocol+"//"+window.location.host;
           $.ajax({
                   type: "GET",
                   url: baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php',
                   dataType: 'json', 
                   data: {'action':'get_qi_quiz_summary','survey_ref':queries['surveyref']},
                   success: function(returndata) {
		            $("#qi_wpProQuiz_results").html(returndata['htmldisplay']); 
                    $("#headerdisplay").html(returndata['headerdisplay']);					
                    }
            });//end ajax call
            //get the user information and display it in  the user summary area
           $.ajax({
                   type: "GET",
                   url: baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php',
                   dataType: 'json', 
                   data: {'action':'get_qi_user_summary','survey_ref':queries['surveyref'], 'qi_user':queries['qi_user']},
                   success: function(returndata) {
                   $("#qi_user_summary").html(returndata['htmldisplay']);   
                    }
            });//end ajax call
}
}//end this is the qi results page
/* ------------------------------------ */
//handle the accordion layout for module library
/* ------------------------------------ */
else if(current_page.indexOf('learning-module-library') > -1 || current_page.indexOf('new-presentations-page') > -1 || current_page.indexOf('tc-web-resources') > -1){
    var parentDivs = $('#nestedAccordion div'),
    childDivs = $('#nestedAccordion h3').siblings('div');
    var str = window.location;
if(current_page.indexOf("?") != -1){
//query string finds parentdiv and childdiv in url tag to locate area in library modules
//url pattern parentdiv="somenumber"&childdiv="somenumber"
var queries = {}; 
$.each(document.location.search.substr(1).split('&'),function(c,q){ var i = q.split('='); queries[i[0].toString()] = i[1].toString(); });

var currentH3 = $(document.getElementById(queries['childdiv']));
var currentH2 = $(document.getElementById(queries['parentdiv']));
      if(currentH2.next().is(':hidden')){
            currentH2.next().slideDown();
            currentH3.next().slideDown();
            //added this so the page will scroll to the open area on the screen once it is open 
            $('html,body').animate({
                       scrollTop: currentH3.offset().top},
                       'slow');
        }
}
$('#nestedAccordion h2').click(function(){
        parentDivs.slideUp();
        if($(this).next().is(':hidden')){
            $(this).next().slideDown();
        }else{
            $(this).next().slideUp();
        }
    });
    $('#nestedAccordion h3').click(function(){
        childDivs.slideUp();
        if($(this).next().is(':hidden')){
            $(this).next().slideDown();
        }else{
            $(this).next().slideUp();
        }
});
} // end if this is the module library page
/* ------------------------------------ */
/*  handle the role change add users to groups based on role
/* ------------------------------------ */
$('#pods-form-ui-pods-meta-transition-profile-role').change(function(){
var userrole = $(this).attr('value');
var baseURL = window.location.protocol+"//"+window.location.host;
if (userrole == 'College/university faculty or instructor'){
$('#user-group-pd-hub-teachers').attr('checked', true);
}
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
$.post(urltoget, {'action':'setGroup','userrole': userrole}, function(returndata){
});

});
/* ------------------------------------ */
//handle the submit a tip search button on the tips page
/* ------------------------------------ */
$("#submittipsearch" ).click(function() {
  var tip_state = "";
  var tip_category = "";
  var tip_keyword = "";
  var baseURL = window.location.protocol+"//"+window.location.host;
  var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
  tip_state = $("#tip_state").val();
  tip_category = $("#tip_category").val();
  tip_keyword = $("#tip_keyword").val();
  //track tip search form submissions with google analaytics
  ga('send', 'event', { eventCategory: 'Tip search form', eventAction: 'submit', eventLabel: 'state:'+tip_state+' category:'+tip_category+' keyword:'+tip_keyword+''});
  var data = $('#search_tip_form').serialize();
        $.ajax({
		url: urltoget,
		type: 'POST',
		dataType:'json',
		data : data,
		success: function(ret){
	        var content_area =ret['content_area'];
	        var result_summary = ret['result_summary'];
	       
              $("#tips_results").html(content_area);
              $(".resultSummary").html(result_summary);
	      }
	});
});//end tip search

/* ------------------------------------ */
//handle the missouri agency search button
/* ------------------------------------ */
$("#submitagencysearch" ).click(function() {

var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
//track tip search form submissions with google analaytics
 var data = $('#search_agency_form').serialize();
        $.ajax({
		url: urltoget,
		type: 'POST',
		dataType:'json',
		data : data,
		success: function(ret){
	    var content_area =ret['content_area'];
	    var result_summary = ret['result_summary'];
              $("#agency_results").html(content_area);
              $(".resultSummary").html(result_summary);
	      }
	});
});//end agency search
/* ------------------------------------ */
//handle the qi survey agree click function, setting a user meta value
/* ------------------------------------ */
$("#qi_survey_agree" ).click(function() {
  var baseURL = window.location.protocol+"//"+window.location.host;
  var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
    $.post(urltoget, {'action':'set_qi_agree_meta'}, function(ret){
    if (ret == 'success'){
     //redirect the user to the qi survey 
     window.location = baseURL + "/qi-survey/";  
    }
    else{
    alert("We are momentarily unable to direct you to the QI survey.");   
    }
    });
}); //end qi agree button
//show or hide the flash element
$('a.showhideflash').click(function(){
if ($('#hide_flash'). css("visibility") == 'visible'){
$('#show_flash').css("visibility","visible"); 
$('#hide_flash').css("visibility","hidden");
}
else{
$('#show_flash').css("visibility","hidden"); 
$('#hide_flash').css("visibility","visible")
}
});
//sort assessments
$('#sort_assessments').change(function(){

var $items = $('ul.js-sort-assessments');
var	$itemsli = $items.children('li');
	var $selected = $('#sort_assessments').val();
	if($selected == "Select"){
	      location.reload();
	}else if($selected == "(TITLE) A-Z"){
		$itemsli.sort(function(a,b){
		    var $an = a.getAttribute('data-name'),
				$bn = b.getAttribute('data-name');				
			if($an > $bn){
				return 1;
			}
			if($an < $bn){
				return -1;
			}
			return 0;
		});
		
		$itemsli.detach();
		$itemsli.appendTo($items);
	}else if($selected == "(TITLE) Z-A"){
        $itemsli.sort(function(a,b){
              var $an = a.getAttribute('data-name'),
				$bn = b.getAttribute('data-name');

			if($an < $bn){
				return 1;
			}
			if($an > $bn){
				return -1;
			}
			return 0;
		});
		$itemsli.detach();
		$itemsli.appendTo($items);
		
	}else if($selected == "(Rating) High-Low"){
           $itemsli.sort(function(a,b){
           var $an = a.getAttribute('data-rating'),
				$bn = b.getAttribute('data-rating');
			
			if($an < $bn){
				return 1;
			}
			if($an > $bn){
				return -1;
			}
			return 0;
		});
		
		$itemsli.detach();
		$itemsli.appendTo($items);
		
	}else if($selected == "(Rating) Low-High"){

		$itemsli.sort(function(a,b){

			var $an = a.getAttribute('data-rating'),
				$bn = b.getAttribute('data-rating');
			
			if($an > $bn){
				return 1;
			}
			if($an < $bn){
				return -1;
			}
			return 0;
		});
		
		$itemsli.detach();
		$itemsli.appendTo($items);
	}

});
/* ------------------------------------ */
/*  handle school districts when state changes, change the user's group when they select KS MO or GA
/* ------------------------------------ */
$('#user_select_state').change(function() {
//var userstate = $(this).attr('value');
var user_select_state = "";
var user_select_state = $('#user_select_state').val();
if(user_select_state != ''){
	$('#school_district').empty();
	var school_district = $('#school_district');
	var html = '<option value="">Please Select</option><option value="No District">No District</option><option value="Statewide">Statewide</option>';
	$('#school_district').append(html);
	$.ajax({
                   type: "POST",
                   url: baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php",
                   dataType: 'json', 
                   data: {'action':'get_school_districts', 'state':user_select_state},
                   success: function(returnvars) {
				   	$.each(returnvars['school_districts'], function( index, value ) {	
					school_district.append( $('<option></option>').val(value).html(value));	
					});
                   },
				    error: function(xhr, status, error) {
					alert("Error occurred");
					}
            });//end ajax call 
}else{
	$('#school_district').empty();
	var school_district = $('#school_district');
	var html = '<option value="">Please Select</option>';
	$('#school_district').append(html);
}
/*var baseURL = window.location.protocol+"//"+window.location.host;
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
$.post(urltoget, {'action':'setGroup','userstate': userstate}, function(returndata){
});
*/
});
/* ------------------------------------ */
/*handle attribute selection for register and profile pages
/*--------------------------------------*/
if(current_page.indexOf('register-3') > -1 || current_page.indexOf('your-profile') > -1 || current_page.indexOf('/wp-admin/user-edit') > -1){
//handle the toggling of school districts based on state on the user profile page
/* ------------------------------------ */
var user_select_state = "";
var user_select_state = $('#user_select_state').val();
var user_school_district = "";
var user_school_district = $('#user_school_district').val();
if(user_select_state != ''){
	$('#school_district').empty();
	var school_district = $('#school_district');
	if(user_school_district == "No District"){
		var html = '<option value="">Please Select</option><option value="No District" selected>No District</option><option value="Statewide">Statewide</option>';
	}else if(user_school_district == "Statewide"){
		var html = '<option value="">Please Select</option><option value="No District">No District</option><option value="Statewide" selected>Statewide</option>';
	}else{
		var html = '<option value="">Please Select</option><option value="No District">No District</option><option value="Statewide">Statewide</option>';
	}
	
	$('#school_district').append(html);
	$.ajax({
                   type: "POST",
                   url: baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php",
                   dataType: 'json', 
                   data: {'action':'get_school_districts', 'state':user_select_state},
                   success: function(returnvars) {
				   	$.each(returnvars['school_districts'], function( index, value ) {
					if(value == user_school_district){
						school_district.append( $('<option selected></option>').val(value).html(value));
					}else{
					school_district.append( $('<option></option>').val(value).html(value));	
					}
					});
                   },
				    error: function(xhr, status, error) {
					alert("Error occurred");
					}
            });//end ajax call 

}else{
	$('#school_district').empty();
	var school_district = $('#school_district');
	var html = '<option value="">Please Select</option>';
	$('#school_district').append(html);
}
}

});//end document ready
function deletetopic(postid){
document.getElementById(postid).remove();
var baseURL = window.location.protocol+"//"+window.location.host;
var ajaxURL = baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php?action=deletetopic&postid='+postid;
var xhr = new XMLHttpRequest(); // Usual mix-and-matching for x-browser omitted for brevity
xhr.open('GET', ajaxURL, true);
xhr.send(null);
}

function send_qi_email(instance_id,user_email,version) {
var toemail = window.prompt("Enter the email address where you would like to send your results:",user_email);
var xhr = new XMLHttpRequest(); // Usual mix-and-matching for x-browser omitted for brevity
// no need to declare 'response' here
xhr.onreadystatechange = function () {
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    document.getElementById('portfolio_qi_message').innerHTML=response;
  }
}
var baseURL = window.location.protocol+"//"+window.location.host;
var ajaxURL = baseURL +'/wp-content/themes/hueman-child/processCustomAjax.php?action=sendQIEmail&survey_ref='+instance_id+'&emailTo='+toemail;
xhr.open('GET', ajaxURL, true);
xhr.send(null);
}

					
					