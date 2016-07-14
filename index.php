<?php

require_once('twitter.php');

// Twitter OAuth Config options
$oauth_access_token = '262433789-fsAq37SQpW0BWB2DBaqF0upJsPB5kuyhJlsClSKV'; // Access token
$oauth_access_token_secret = 'GYclON6IaZIAIAGOCH9VsbyMEosalNn7qSesW3Exk9bUa'; // Access token secret
$consumer_key = 'RHALiJUCwTZoTRfOrnG13JZCq'; // API key
$consumer_secret = 'M86eBarcMkWcusVhuaqQalcvmBVYtXfeqHCryK9LntzUzSbUi6'; // API secret
$user_id = '262433789'; // Owner ID
$screen_name = 'siva01sankar'; // Owner

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

$twitter_proxy = new TwitterProxy(
    $oauth_access_token,
    $oauth_access_token_secret,
    $consumer_key,
    $consumer_secret,
    $user_id,
    $screen_name,
    $count
);

// Invoke the get method to retrieve results via a cURL request
$tweets = $twitter_proxy->get($twitter_url);
echo $tweets;
?>
