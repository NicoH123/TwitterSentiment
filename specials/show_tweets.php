<?php 
/**
* get_tweet_list.php
* Return a list of the most recent tweets as HTML
* Older tweets are requested with the query of last=[tweet_id] by site.js
* 
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/

require_once('twitter_display_config.php');
require_once('display_lib.php');
require_once('db_basis.php' ); // hier eine Veränderung
$oDB = new db;

$query = 'SELECT profile_image_url, created_at, screen_name, user_id,screen_name,
  name, tweet_text, tweet_id
  FROM tweets ';


// Use the templates for wiki pages
$tweet_template1 = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\includes\TweetPageTemplate.txt');
$user_template = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\includes\UserPageTemplate.txt');
$hashtag_template = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\includes\HashtagPageTemplate.txt');

// Use the text file tweet_template.txt to construct each tweet in the list
$tweet_template = file_get_contents('C:\wamp\www\TwitterWiki\extensions\TwitterSentiment\specials\tweet_template.txt');
$tweet_list = '';
$tweets_found = 0;	

// Query string of last=[tweet_id] means that this script was called by site.js
// when the More Tweets button was clicked
if (isset($_GET['last'])) {  
  $query .= 'WHERE tweet_id < "' . $_GET['last'] . '" ';
}

#$cSession = curl_init();
#$cSession2 = curl_init();
$query .= 'ORDER BY tweet_id DESC LIMIT ' . 3; //hier eine Verändung von mir
$result = $oDB->select($query);

$cSession = curl_init();
while (($row = mysqli_fetch_assoc($result))
  &&($tweets_found < 3)) { 
  
  ++$tweets_found;
  // create a fresh copy of the empty template
  $current_tweet = $tweet_template;
  $current_tweetpage = $tweet_template1;
  $current_userpage = $user_template;
  $current_hashpage = $hashtag_template;
  
  
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
			$hashtags = $hashtags . '[[Has hashtag::Hashtag ' . 
		$hRow['tag'] . '|#' . $hRow['tag']; 
						
		$hashtags = substr($hashtags, 0, strlen($hashtags));
		$current_hashpage = str_replace('{user_id}', $row['user_id'], $current_hashpage);
		$current_hashpage = str_replace('{tweet_id}', $row['tweet_id'], $current_hashpage);
		$current_hashpage = str_replace('{tag}', $hashtags, $current_hashpage);
	}
	}
	$current_tweetpage = str_replace('{tags}', $hashtags, $current_tweetpage);
	
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'Hashtag' . $row['tweet_id'],
							'createonly' => 'true',
							'text' => $current_hashpage,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	// Execute curl
	$curl_result = curl_exec($cSession);
	
	
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
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
	
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
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
	
  // Add this tweet to the list
  $tweet_list .= $current_tweet;
  }
  
  curl_close($cSession);
  
 function linkify($text,$tweetstring) {

	// Linkify URLs
  $text = preg_replace("/[[:alpha:]]+:\/\/[^<>[:space:]]+[[:alnum:]\/]/i",
  	"<a href=\"\\0\" target=\"_blank\">\\0</a>", $text); 
	
	// Linkify @mentions
  $text = preg_replace("/\B@(\w+(?!\/))\b/i", 
  	'<a href="https://twitter.com/\\1" title="' .
  	USER_MENTION_TITLE . '\\1">@\\1</a>', $text); 
    	
	// Linkify #hashtags
  $text = preg_replace("/\B(?<![=\/])#([\w]+[a-z]+([0-9]+)?)/i", 
  	'<a href="http://localhost/TwitterWiki/index.php/Hashtag'.$tweetstring.'" title="' .
  	HASHTAG_TITLE . '\\1">#\\1</a>', $text); 
    	
  return $text;
}
  
  /*$result = $oDB->select($tidQuery);
  
  while ($row = mysqli_fetch_assoc($result)) { 

	#echo('Tweet id: ' . $row['tid'] . '. </br>');
	#echo('Tweet text: ' . $row['tx'] . '. </br>');
	#echo('Author: ' . $row['uid'] . '</br>');
	$tweet_page_text = str_replace('{tweet_id}', $row['tid'], $tweet_template1);
	// TODO remove tweet from DB
	
	
	// Look for hashtags to this tweet. If there are 1 or more, add to tweet.
	$hashtags = '';
	$tagQuery = 'SELECT DISTINCT	tag as tag
				FROM				tweet_tags
				WHERE				tweet_id = ' . $row['tid'];
	#echo('Hashtag query: ' . $tagQuery . '</br>');
	$tagResult = $oDB->select($tagQuery);
	$numHashtags = mysqli_num_rows($tagResult);
	if($numHashtags > 0) {
		#echo(' This tweet contains ' . $numHashtags . ' hashtags.');
		while($hRow = mysqli_fetch_assoc($tagResult)) {
			$hashtags = $hashtags . '[[Has hashtag::Hashtag ' . 
						$hRow['tag'] . '|#' . $hRow['tag'] . ']], ';
			// TODO create hashtag page here and remove from DB
			// Setup curl to create the hashtag page if it does not exist yet
		curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'Hashtag' . $row['tid'],
							'createonly' => 'true',
							'text' => $hashtag_template,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	// Execute curl
	$curl_result = curl_exec($cSession);
			
		}
		
		$hashtags = substr($hashtags, 0, strlen($hashtags) - 2);
		$tweet_page_text = str_replace('{tags}', $hashtags, $tweet_page_text);
	}
	#$tweet_page_text = str_replace('{tags}', $hashtags, $tweet_page_text);

	#echo("</br>");
		
	// Process the user
	$user_page_text = str_replace('{user_id}', $row['uid'], $user_template);
	$userQuery = 'SELECT 	screen_name as sn, location as loc
					FROM	users
					WHERE	user_id = ' . $row['uid'];
	$userResult = $oDB->select($userQuery);
	while($uRow = mysqli_fetch_assoc($userResult)) {
		$user_page_text = str_replace('{screen_name}', $uRow['sn'], $user_page_text);
		$user_page_text = str_replace('{location}', $uRow['loc'], $user_page_text);
	}
	// Setup curl to create the user page if it does not exist yet
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'User' . $row['uid'],
							'createonly' => 'true',
							'text' => $user_page_text,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	// Execute curl
	$curl_result = curl_exec($cSession);
	
	
	// Fill remaining values into the tweet page template
	$tweet_page_text = str_replace('{user_id}', $row['uid'], $tweet_page_text);
	$tweet_page_text = str_replace('{tweet_text}', $row['tx'], $tweet_page_text);
	$tweet_page_text = str_replace('{created_at}', $row['cr'], $tweet_page_text);
	
	
	// Setup curl to create the tweet page if it does not exist yet
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/TwitterWiki/api.php?',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'Tweet' . $row['tid'],
							'createonly' => 'true',
							'text' => $tweet_page_text,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	
	// Execute curl
	$curl_result = curl_exec($cSession);
	
		
}
   
  //step4
		curl_close($cSession);
		*/
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