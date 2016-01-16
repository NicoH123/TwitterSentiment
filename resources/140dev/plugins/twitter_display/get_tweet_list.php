<?php 
/**
* get_tweet_list.php (modified Daniel Wenz & Nico Haubner)
* Return a list of the most recent tweets as HTML and create wiki pages for them
* Older tweets are requested with the query of last=[tweet_id] by site.js
* 
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/

require_once __DIR__ . '/twitter_display_config.php';
require_once __DIR__ . '/display_lib.php';
require_once __DIR__ . '/../../db/db_lib.php';
$oDB = new db;
$wikiURL = 'http://' . $_SERVER['SERVER_NAME'];
$apiEndpoint = $wikiURL . '/api.php?';

$query = 'SELECT profile_image_url, created_at, screen_name, user_id,screen_name,
  name, tweet_text, tweet_id
  FROM tweets ';

// Use the templates for wiki pages
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


$query .= 'ORDER BY tweet_id DESC LIMIT ' . 3; //hier eine Verändung von Dani (und Nico)
$result = $oDB->select($query);

while (($row = mysqli_fetch_assoc($result))
  &&($tweets_found < 3)) { 
  
  ++$tweets_found;
  // create a fresh copy of the empty template
  $current_tweet = $tweet_template;
  $current_tweetpage = $tweet_page_template;
  $current_userpage = $user_page_template;
  $current_hashpage = $hashtag_page_template;
  
  
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
	
	$current_tweetpage = str_replace('{user_id}', $row['user_id'], $current_tweetpage);
	$current_tweetpage = str_replace('{screen_name}', $row['screen_name'], $current_tweetpage);
	$current_tweetpage = str_replace('{tweet_id}', $row['tweet_id'], $current_tweetpage);
	$current_tweetpage = str_replace('{tweet_text}', $row['tweet_text'], $current_tweetpage);
	$current_tweetpage = str_replace('{created_at}', $row['created_at'], $current_tweetpage);
	
	$current_userpage = str_replace('{user_id}', $row['user_id'], $current_userpage);
	$current_userpage = str_replace('{screen_name}', $row['screen_name'], $current_userpage);
	$current_userpage = str_replace('{name}', $row['name'], $current_userpage);
	
	// Look for hashtags to this tweet. If there are 1 or more, add to tweet.
	$hashtags = '';
	$tagQuery = 'SELECT DISTINCT	tag as tag
				FROM				tweet_tags
				WHERE				tweet_id = ' . $row['tweet_id'];
	#echo('Hashtag query: ' . $tagQuery . '</br>');
	$tagResult = $oDB->select($tagQuery);
	$numHashtags = mysqli_num_rows($tagResult);
	if($numHashtags > 0) {
		#echo(' This tweet contains ' . $numHashtags . ' hashtags.');
		while($hRow = mysqli_fetch_assoc($tagResult)) {
			$cSession = curl_init();
			curl_setopt_array($cSession, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $apiEndpoint,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
									'action' => 'edit',
									'title' => 'Hashtag ' . $hRow['tag'],
									'createonly' => 'true',
									'text' => $current_hashpage,
									'summary' => 'Created automatically from the 140dev database.',
									'token' => "+\\",
					)
				)
			);
			$curl_result = curl_exec($cSession);
			#echo $curl_result;
			curl_close($cSession);
			$hashtags = $hashtags . '[[Has hashtag::Hashtag ' . $hRow['tag'] . '|#' . $hRow['tag'] . ']], '; 
		}
		// TODO cut off last ", "
	}
	$current_tweetpage = str_replace('{tags}', $hashtags, $current_tweetpage);
	
	$cSession = curl_init();
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $apiEndpoint,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'User' . $row['user_id'],
							'createonly' => 'true',
							'text' => $current_userpage,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	$curl_result = curl_exec($cSession);
	#echo $curl_result;
	curl_close($cSession);
	
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
	#echo $curl_result;
	curl_close($cSession);
	
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