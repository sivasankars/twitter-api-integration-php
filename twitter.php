<?php
class TwitterProxy {

	private $config = [
		'base_url' => 'https://api.twitter.com/1.1/'
	];

	public function __construct($oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret, $user_id, $screen_name, $count = 5) {
		$this->config = array_merge($this->config, compact('oauth_access_token', 'oauth_access_token_secret', 'consumer_key', 'consumer_secret', 'user_id', 'screen_name', 'count'));
	}

	private function buildBaseString($baseURI, $method, $params) {
		$r = [];
		ksort($params);
		foreach($params as $key=>$value){
			$r[] = "$key=" . rawurlencode($value);
		}
		return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
	}

	private function buildAuthorizationHeader($oauth) {
		$r = 'Authorization: OAuth ';
		$values = [];
		foreach($oauth as $key => $value) {
			$values[] = "$key=\"" . rawurlencode($value) . "\"";
		}
		$r .= implode(', ', $values);
		return $r;
	}

	public function get($url) {
		if (! isset($url)){
			die('No URL set');
		}

		$url_parts = parse_url($url);
		parse_str($url_parts['query'], $url_arguments);
		 
		$full_url = $this->config['base_url'] . $url; // URL with the query on it
		$base_url = $this->config['base_url'] . $url_parts['path']; // URL without the query
		 
		// Set up the OAuth Authorization array
		$oauth = [
			'oauth_consumer_key' => $this->config['consumer_key'],
			'oauth_nonce' => time(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token' => $this->config['oauth_access_token'],
			'oauth_timestamp' => time(),
			'oauth_version' => '1.0'
		];

		$base_info = $this->buildBaseString($base_url, 'GET', array_merge($oauth, $url_arguments));
		$composite_key = rawurlencode($this->config['consumer_secret']) . '&' . rawurlencode($this->config['oauth_access_token_secret']);
		$oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
		
		// Make Requests
		$header = [
			$this->buildAuthorizationHeader($oauth), 
			'Expect:'
		];
		$options = [
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_HEADER => false,
			CURLOPT_URL => $full_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false
		];
		 
		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$result = curl_exec($feed);
		$info = curl_getinfo($feed);
		curl_close($feed);
		 
		// Send suitable headers to the end user.
		if (isset($info['content_type']) && isset($info['size_download'])){
			header('Content-Type: ' . $info['content_type']);
			header('Content-Length: ' . $info['size_download']);
		}

		return $result;
	}
}