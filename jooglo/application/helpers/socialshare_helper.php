<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('get_tweets_count'))
{
	function get_tweets_count($url) {
 
		$json_string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url=' . $url);
		$json = json_decode($json_string, true);
	 
		return intval( $json['count'] );
	}
}



if ( ! function_exists('get_likes_count'))
{
	function get_likes_count($url) {
 
		$json_string = file_get_contents('http://graph.facebook.com/?ids=' . $url);
		$json = json_decode($json_string, true);
 
		return intval( $json[$url]['shares'] );
	}
}


if ( ! function_exists('get_sharefb_count'))
{
	function get_sharefb_count($url) {
 
		global $fbcount;
		$facebook = file_get_contents('http://api.facebook.com/restserver.php?method=links.getStats&urls='.$url);
		$fbbegin = '<share_count>'; $fbend = '</share_count>';
		$fbpage = $facebook;
		$fbparts = explode($fbbegin,$fbpage);
		$fbpage = $fbparts[1];
		$fbparts = explode($fbend,$fbpage);
		$fbcount = $fbparts[0];
		if($fbcount == '') { $fbcount = '0'; }
		
		return $fbcount;
	}
}



if ( ! function_exists('get_plusones_count'))
{
	function get_plusones_count($url) {
 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($curl);
		curl_close ($curl);
	 
		$json = json_decode($curl_results, true);
	 
		return intval( $json[0]['result']['metadata']['globalCounts']['count'] );
	}
}