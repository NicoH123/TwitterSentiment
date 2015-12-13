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
$result = $oDB->select($tweetQuery);
echo('Number of results found: ' . mysqli_num_rows($result));
echo("</br>");

// Use the text file tweet_template.txt to construct each tweet in the list
$tweet_template = file_get_contents('TweetPageTemplate.txt');
$user_template = file_get_contents('UserPageTemplate.txt');
$hashtag_template = file_get_contents('HashtagPageTemplate.txt');

while ($row = mysqli_fetch_assoc($result)) { 

	echo('Tweet id: ' . $row['tid']);
	echo("</br>");

	// Fill values into the tweet page template
	$tweet_page_text = str_replace('{tweet_id}', $row['tid'], $tweet_template);
	$tweet_page_text = str_replace('{user_id}', $row['uid'], $tweet_page_text);
	$tweet_page_text = str_replace('{tweet_text}', $row['tx'], $tweet_page_text);
	$tweet_page_text = str_replace('{tag}', $row['tag'], $tweet_page_text);
	$tweet_page_text = str_replace('{created_at}', $row['cr'], $tweet_page_text);

	// Setup curl to create the page if it does not exist yet
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://localhost/MediaWiki/api.php?',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => 'Tweet' . $row['tid'],
							# 'createonly' => 'true',
							'text' => $tweet_page_text,
							'summary' => 'Created automatically from the 140dev database.',
							'token' => "+\\",
			)
		)
	);
	
	// Execute curl
	$curl_result=curl_exec($cSession);
		
}
  
// Close curl session
curl_close($cSession);

?>