jQuery(document).ready(function($) {
/*-----------------------------
LERN network page --- get the comment topic from the url and put it into the iframe container
these urls are generated in the daily digest
This is done this way b/c so as to not scrape every url for qs
-----------------------------
*/
var queries = {}; 
var exists = document.location.href.indexOf('&');
if (exists > -1 ) {
$.each(document.location.search.substr(1).split('&'),function(c,q){ var i = q.split('='); queries[i[0].toString()] = i[1].toString(); });
	if (queries['commentpost']){
	var iframeurl = baseURL +"?p="+queries['commentpost'];
	$('#commentiframepanel').html('<iframe id="commentiframe1" src="'+iframeurl+'" width=950 height=1000 frameborder=0 allowfullscreen scrolling="auto"></iframe>');
	}
}

});//end document ready

	