<?php 
/**
* CreateTweetPages.php
* Save tweets from the 140dev DB as mediawiki pages.
*/

// Open curl session
$cSession = curl_init();

// Source database: 140dev
require_once('../resources/140dev/db/db_lib.php' );
$oDB = new db;

// Query for tweets and users
// TODO remove limit
$tweetQuery = 'SELECT 		tw.tweet_id as tid, tw.tweet_text as tx, tw.created_at as cr, 
							tw.user_id as uid, tt.tag as tag, u.screen_name as sn, u.location as loc
				FROM 		tweets tw, tweet_tags tt, users u
				WHERE 		tw.tweet_id = tt.tweet_id
				AND 		tw.user_id = u.user_id
				ORDER BY 	tw.tweet_id
				LIMIT 		50';
$tidQuery = 'SELECT DISTINCT 	tweet_id as tid, tweet_text as tx, 
								created_at as cr, user_id as uid 
			FROM 				tweets 
			LIMIT 				20';
			
$result = $oDB->select($tidQuery);
echo('Number of results found: ' . mysqli_num_rows($result));
echo("</br>");

// Use the templates for wiki pages
$tweet_template = file_get_contents('TweetPageTemplate.txt');
$user_template = file_get_contents('UserPageTemplate.txt');
$hashtag_template = file_get_contents('HashtagPageTemplate.txt');

while ($row = mysqli_fetch_assoc($result)) { 

	echo('Tweet id: ' . $row['tid'] . '. </br>');
	echo('Tweet text: ' . $row['tx'] . '. </br>');
	echo('Author: ' . $row['uid'] . '</br>');
	$tweet_page_text = str_replace('{tweet_id}', $row['tid'], $tweet_template);
	// TODO remove tweet from DB
	
	
	// Look for hashtags to this tweet. If there are 1 or more, add to tweet.
	$hashtags = '';
	$tagQuery = 'SELECT DISTINCT	tag as tag
				FROM				tweet_tags
				WHERE				tweet_id = ' . $row['tid'];
	echo('Hashtag query: ' . $tagQuery . '</br>');
	$tagResult = $oDB->select($tagQuery);
	$numHashtags = mysqli_num_rows($tagResult);
	if($numHashtags > 0) {
		echo(' This tweet contains ' . $numHashtags . ' hashtags.');
		while($hRow = mysqli_fetch_assoc($tagResult)) {
			$hashtags = $hashtags . '[[Has hashtag::Hashtag ' . 
						$hRow['tag'] . '|#' . $hRow['tag'] . ']], ';
			// TODO create hashtag page here and remove from DB
		}
		$hashtags = substr($hashtags, 0, strlen($hashtags) - 2);
	}
	$tweet_page_text = str_replace('{tags}', $hashtags, $tweet_page_text);

	echo("</br>");
		
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
		CURLOPT_URL => 'http://localhost/MediaWiki/api.php?',
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
	$tweet_page_text = str_replace('{tweet_text}', $row['tx'], $tweet_page_text);
	$tweet_page_text = str_replace('{created_at}', $row['cr'], $tweet_page_text);
	
	
	// Setup curl to create the tweet page if it does not exist yet
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/MediaWiki/api.php?',
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
  
// Close curl session
curl_close($cSession);

?>