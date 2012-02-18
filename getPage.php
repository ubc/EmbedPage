<?php
/**
 * Retrieves content for the Embed Page extension of MediaWiki
 *
 * @author Scott McMillan 
 */



/**
 * return the content as javascript after encoding
 * 
 * 
 * @param string $output raw HTML wiki page 
 * @return string prints the document.write content from $output
 */
function obMakeJavaScriptEmbedHandler($output){
  return sprintf('document.write(unescape(decodeURIComponent("%s")));', rawurlencode($output));
}

ob_start("obMakeJavaScriptEmbedHandler");


/**
 * return the raw HTML content of the action = render URL via cURL
 * 
 * This is probably the worst way to do it but I cannot 
 * * find a hook to pull rendered rawHTML

 * @param string $ptitle of the wiki page 
 * @return string content from the URL/webpage
 */

function getUrlContents($title){

	// This is outside the loop so cannot access Globals 
	// ($wgServer passed with $ptitle)
	
	$embedPageServer = "http://" . $_SERVER["SERVER_NAME"];

	$embedPageUrl = "$embedPageServer"."$title?action=render";
	
	if($_SERVER['HTTP_REFERER']){
		$referer = $_SERVER['HTTP_REFERER'];
	}else{
		$referer = $_GET['referer'];
	}
	
    $crl = curl_init();
    $timeout = 5;
	curl_setopt ($crl, CURLOPT_URL,$embedPageUrl);
	curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt ($crl, CURLOPT_HTTPHEADER, array(
		'Referer: '.$referer, 
		'User-Agent: EmbedPage', 
		));
	
	$ret = curl_exec($crl);
    curl_close($crl);
   
    return $ret;
}

// Get article title passed
$embedPageTitle = $_GET['title'];

if(empty($embedPageTitle)){
	echo"Error: Wiki title was not specified.";
	exit();
}else{ 
	// Get the raw HTML content
	$embedPageContent = getUrlContents($embedPageTitle);
	
	if(empty($embedPageContent)){
		echo"Error: retrieving wiki page content.";
		exit();
	}else{
	 	print $embedPageContent;
	}
}
?>