<?php

require_once('twitter.php');

// Twitter OAuth Config options
$oauth_access_token = '262433789-fsAq37SQpW0BWB2DBaqF0upJsPB5kuyhJlsClSKV';
$oauth_access_token_secret = 'GYclON6IaZIAIAGOCH9VsbyMEosalNn7qSesW3Exk9bUa';
$consumer_key = 'RHALiJUCwTZoTRfOrnG13JZCq';
$consumer_secret = 'M86eBarcMkWcusVhuaqQalcvmBVYtXfeqHCryK9LntzUzSbUi6';
$user_id = '262433789';
$screen_name = 'siva01sankar';

if (isset($_POST['count'])) {
	$count = $_POST['count'];
}else {
	$count = 10;
}
$twitter_url = 'statuses/user_timeline.json';
$twitter_url .= '?user_id=' . $user_id;
$twitter_url .= '&screen_name=' . $screen_name;
$twitter_url .= '&count=' . $count;

if (isset($_POST['max_id'])) {
$twitter_url .= '&max_id=' . $_POST['max_id'];
}

if (isset($_POST['since_id'])) {
$twitter_url .= '&since_id=' . $_POST['since_id'];
}

// Create a Twitter Proxy object from our twitter_proxy.php class
$twitter_proxy = new TwitterProxy(
	$oauth_access_token,			// 'Access token' on https://apps.twitter.com
	$oauth_access_token_secret,		// 'Access token secret' on https://apps.twitter.com
	$consumer_key,					// 'API key' on https://apps.twitter.com
	$consumer_secret,				// 'API secret' on https://apps.twitter.com
	$user_id,						// User id (http://gettwitterid.com/)
	$screen_name,					// Twitter handle
	$count							// The number of tweets to pull out
);

// Invoke the get method to retrieve results via a cURL request
$tweets = $twitter_proxy->get($twitter_url);
echo $tweets;
?>