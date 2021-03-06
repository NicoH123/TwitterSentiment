<?php
/**
* Category and property pages need to be created after installation of the TwitterSentiment extension.
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
				array("Category:Twitter Sentiment Algorithm",
						"This is the common category of Machine Learning (ML) web services which are used to perform sentiment analysis on the [[:Category:Tweet|Tweets]] stored in this wiki."),
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
						"This property links any kind of resource which is identified by a unique identifier to its representatory name. It is of the type [[Has type::Text]]."),
				array("Datumbox Sentiment Analysis",
						"The Datumbox Twitter Sentiment Analysis web service is used to analyze [[:Category:Tweet|Tweets]] on this wiki regarding the expressed sentiments. Find more information [[http://www.datumbox.com/machine-learning-api/ here]]. [[Category:Twitter Sentiment Algorithm]]"),
				array("Meaningcloud Sentiment Analysis",
						"The Meaningcloud Sentiment Analysis web service is used to analyze [[:Category:Tweet|Tweets]] on this wiki regarding the expressed sentiments. Find more information [https://www.meaningcloud.com/developer/sentiment-analysis/doc here]. [[Category:Twitter Sentiment Algorithm]]"),
				array("Sentiment140 Sentiment Analysis",
						"The Sentiment140 Twitter Sentiment Analysis web service is used to analyze [[:Category:Tweet|Tweets]] on this wiki regarding the expressed sentiments. Find more information [http://help.sentiment140.com/api here]. [[Category:Twitter Sentiment Algorithm]]"),
				array("Property:Has Datumbox annotation",
						"This property displays the sentiment of a [[:Category:Tweet|Tweet]] as analyzed by the [[Datumbox Sentiment Analysis|Datumbox]] web service. It is of the type [[Has type::Text]]. \n\n\nPossible values are: [[Allows value::positive]], [[Allows value::neutral]], [[Allows value::negative]], [[Allows value::none]]"),
				array("Property:Has Meaningcloud annotation",
						"This property displays the sentiment of a [[:Category:Tweet|Tweet]] as analyzed by the [[Meaningcloud Sentiment Analysis|Meaningcloud]] web service. It is of the type [[Has type::Text]]. \n\n\nPossible values are: [[Allows value::strong positive]], [[Allows value::positive]], [[Allows value::neutral]], [[Allows value::negative]], [[Allows value::strong negative]], [[Allows value::none]]"),
				array("Property:Has Sentiment140 annotation",
						"This property displays the sentiment of a [[:Category:Tweet|Tweet]] as analyzed by the [[Sentiment140 Sentiment Analysis|Sentiment140]] web service. It is of the type [[Has type::Text]]. \n\n\nPossible values are: [[Allows value::positive]], [[Allows value::neutral]], [[Allows value::negative]], [[Allows value::none]]"));

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

