<?php 
/**
* get_tweet_list.php (modified by Daniel Wenz & Nico Haubner, TwitterSentiment)
* Return a list of the most recent tweets as HTML (and create wiki pages for them)
* Older tweets are requested with the query of last=[tweet_id] by site.js
* 
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/

// TwitterSentiment: The Sentiment Analysis web services are included.
require_once __DIR__ . '/twitter_display_config.php';
require_once __DIR__ . '/display_lib.php';
require_once __DIR__ . '/../../db/db_lib.php';
require_once __DIR__ . '/../../../Datumbox/APIclient_PHP_1.0/TwitterSentiment.php';
require_once __DIR__ . '/../../../Meaningcloud/sdk-php-sentiment-2.0/php/TwitterSentiment.php';
require_once __DIR__ . '/../../../Sentiment140/TwitterSentiment.php';

$oDB = new db;
// TwitterSentiment: The API endpoint of the Mediawiki.
$wikiURL = 'http://' . $_SERVER['SERVER_NAME'];
$apiEndpoint = $wikiURL . '/api.php?';

$query = 'SELECT profile_image_url, created_at, screen_name, user_id,screen_name,
  name, tweet_text, tweet_id
  FROM tweets ';

// TwitterSentiment: Use the templates for wiki pages
$tweet_page_template = file_get_contents(__DIR__ . '/../../../../includes/TweetPageTemplate.txt');
$user_page_template = file_get_contents(__DIR__ . '/../../../../includes/UserPageTemplate.txt');
$hashtag_page_template = file_get_contents(__DIR__ . '/../../../../includes/HashtagPageTemplate.txt');

// Use the text file tweet_template.txt to construct each tweet in the list
$tweet_template = file_get_contents(__DIR__ . '/tweet_template.txt');
$tweet_list = '';
$tweets_found = 0;	

// Query string of last=[tweet_id] means that this script was called by site.js
// when the More Tweets button was clicked
if (isset($_GET['last'])) {  
  $query .= 'WHERE tweet_id < "' . $_GET['last'] . '" ';
}

// TwitterSentiment
$query .= 'ORDER BY tweet_id DESC LIMIT ' . 3;
$result = $oDB->select($query);

// TwitterSentiment: Create new classes of the ML services
$datumbox = new DatumboxTwitterSentiment();
$meaningcloud = new MeaningcloudSentiment();
$sentiment140 = new Sentiment140TwitterSentiment();

while (($row = mysqli_fetch_assoc($result))
  &&($tweets_found < 3)) { 
  
  ++$tweets_found;
  // create a fresh copy of the empty template
  $current_tweet = $tweet_template;
  
  // TwitterSentiment: page templates
  $current_tweetpage = $tweet_page_template;
  $current_userpage = $user_page_template;
  $hashpage = $hashtag_page_template;
  
  
  // TwitterSentiment: Communicate with the ML APIs
  $sentiment_datumbox = $datumbox->TwitterSentiment($row['tweet_text']);
  $sentiment_meaningcloud = $meaningcloud->Sentiment($row['tweet_text']);
  $sentiment_sentiment140 = $sentiment140->TwitterSentiment($row['tweet_text']);
  
  // Fill in the template with the current tweet
  $current_tweet = str_replace( '[profile_image_url]', 
    $row['profile_image_url'], $current_tweet);
  $current_tweet = str_replace( '[created_at]', 
    twitter_time($row['created_at']), $current_tweet);    		
  $current_tweet = str_replace( '[screen_name]', 
	  $row['screen_name'], $current_tweet);  
  $current_tweet = str_replace( '[name]', 
    $row['name'], $current_tweet);
 $current_tweet = str_replace( '[user_id]', 
    $row['user_id'], $current_tweet); 	
  $current_tweet = str_replace( '[user_mention_title]', 
    USER_MENTION_TITLE . ' ' . $row['screen_name'] . ' (' . $row['name'] . ')', 
    $current_tweet);  
  $current_tweet = str_replace( '[tweet_display_title]', 
    TWEET_DISPLAY_TITLE, $current_tweet);
	
  $current_tweet = str_replace('[tweet_text]', 
    linkify($row['tweet_text'],$row['tweet_id']), $current_tweet); 
		
  // Include each tweet's id so site.js can request older or newer tweets
  $current_tweet = str_replace( '[tweet_id]', 
    $row['tweet_id'], $current_tweet); 
	
	// TwitterSentiment: Fill page templates
	$current_tweetpage = str_replace('{user_id}', $row['user_id'], $current_tweetpage);
	$current_tweetpage = str_replace('{screen_name}', $row['screen_name'], $current_tweetpage);
	$current_tweetpage = str_replace('{tweet_id}', $row['tweet_id'], $current_tweetpage);
	$current_tweetpage = str_replace('{tweet_text}', $row['tweet_text'], $current_tweetpage);
	$current_tweetpage = str_replace('{created_at}', $row['created_at'], $current_tweetpage);
	$current_tweetpage = str_replace('{datumbox}', $sentiment_datumbox, $current_tweetpage);
	$current_tweetpage = str_replace('{meaningcloud}', $sentiment_meaningcloud, $current_tweetpage);
	$current_tweetpage = str_replace('{sentiment140}', $sentiment_sentiment140, $current_tweetpage);
	
	$current_userpage = str_replace('{user_id}', $row['user_id'], $current_userpage);
	$current_userpage = str_replace('{screen_name}', $row['screen_name'], $current_userpage);
	$current_userpage = str_replace('{name}', $row['name'], $current_userpage);
	
	// TwitterSentiment: Look for hashtags to this tweet. If there are 1 or more, add to tweet.
	$hashtags = '';
	$tagQuery = 'SELECT DISTINCT	tag as tag
				FROM				tweet_tags
				WHERE				tweet_id = ' . $row['tweet_id'];
	$tagResult = $oDB->select($tagQuery);
	$numHashtags = mysqli_num_rows($tagResult);
	
	// TwitterSentiment: 2dim array to save hashtag pages and names in order to create them AFTER the tweet page.
	$hashtag_array = [];
	
	// TwitterSentiment: fill array
	if($numHashtags > 0) {
		
		while($hRow = mysqli_fetch_assoc($tagResult)) {
			
			$current_hashpage = str_replace('{tag}', $hRow['tag'], $hashpage);
			$current_hashtitle = 'Hashtag ' . $hRow['tag'];
			
			$hashtag_array[] = array($current_hashtitle, $current_hashpage);
			
			$hashtags = $hashtags . '[[Has hashtag::Hashtag ' . $hRow['tag'] . '| ]]'; 
			
			
		}

	}
	$current_tweetpage = str_replace('{tags}', $hashtags, $current_tweetpage);
	
	// TwitterSentiment: Add the Tweet as a wiki page.
	$cSession = curl_init();
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $apiEndpoint,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'Tweet' . $row['tweet_id'],
							'createonly' => 'true',
							'text' => $current_tweetpage,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	$curl_result = curl_exec($cSession);
	curl_close($cSession);
	
	// TwitterSentiment: Add the user as a wiki page.
	$cSession = curl_init();
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $apiEndpoint,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'User' . $row['user_id'], #Nico: removed create_only for enabling dynamic updates
							'text' => $current_userpage,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	$curl_result = curl_exec($cSession);
	curl_close($cSession);
	
	// TwitterSentiment: Add all hashtags as wiki pages.
	foreach($hashtag_array as $tweet) {
		$tagtitle = $tweet[0];
		$tagtext = $tweet[1];
		
		$cSession = curl_init();
		curl_setopt_array($cSession, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $apiEndpoint,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
								'action' => 'edit',
								'title' => $tagtitle, #Daniel: removed create_only for enabling dynamic updates
								'text' => $tagtext,
								'summary' => 'Created automatically from the 140dev database.',
								'token' => "+\\",
				)
			)
		);
		$curl_result = curl_exec($cSession);
		curl_close($cSession);
	}
	
	
	
  // Add this tweet to the list
  $tweet_list .= $current_tweet;
  }

  
if (!$tweets_found) {
  if (isset($_GET['last'])) {
    $tweet_list = '<strong>No more tweets found</strong><br />';
  } else {
    $tweet_list = '<strong>No tweets found</strong><br />';	
  }	
}

if (isset($_GET['last'])) {
  // Called by site.js with Ajax, so print HTML to the browser
  
  print $tweet_list;
} else {
  // Called by twitter_display.php with require(), so return the value
  return $tweet_list;
}
?>