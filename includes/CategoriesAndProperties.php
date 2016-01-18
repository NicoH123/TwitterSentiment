<?php
/**
* Category and property pages need to be created on installation of the TwitterSentiment extension.
*/

// Define endpoint here
$wikiURL = 'http://' . $_SERVER['SERVER_NAME'];
$apiEndpoint = $wikiURL . '/api.php?';

// Summary text to document the automatic creation
$summary = "Created automatically by the TwitterSentiment extension.";

// List pages: (title, text)
$pages = array(	array("Category:Tweet",
						"This is the common category of all Twitter posts which have been stored in this wiki."),
				array("Category:Twitter User",
						"This is the common category of all Twitter users of whom Tweets have been stored in this wiki."),
				array("Category:Hashtag",
						"A hashtag is used in social networks such as Twitter and Facebook in order to make posts searchable and trends recognizable."),
				array("Property:Was written by",
						"This property links a written text such as a book or a blogpost to its author. It is of the type [[Has type::Page]]."),
				array("Property:Has text",
						"This property links an information resource like a book or blogpost to its contents. It is of the type [[Has type::Text]]."),
				array("Property:Has hashtag",
						"This property links a social media post to an adjacent [[:Category:Hashtag|Hashtag]]. It is of the type [[Has type::Page]]."),
				array("Property:Was created at",
						"This property links any kind of resource to the date and time it was created. It is of the type [[Has type::Date]]."),
				array("Property:Has username",
						"This property links a user of a social or other network to his or her alias. It is of the type [[Has type::Text]]."),
				array("Property:Has name",
						"This property links any kind of resource which is identified by a unique identifier to its representatory name. It is of the type [[Has type::Text]]."));

// Create pages via curl
for($i = 0; $i < count($pages); $i++) {
	
	$cSession = curl_init();
	curl_setopt_array($cSession, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $apiEndpoint,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
							'action' => 'edit',
							'title' => $pages[$i][0],
							'createonly' => 'true',
							'text' => $pages[$i][1],
							'summary' => $summary,
							'token' => "+\\",
			)
		)
	);
	$curl_result = curl_exec($cSession);
	echo $curl_result;
	curl_close($cSession);
	
}		


						
						

