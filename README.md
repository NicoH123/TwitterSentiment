# TwitterSentiment

TwitterSentiment is an extension for Semantic MediaWiki which enables you to quickly analyze the mood about different topics on Twitter, right from your MediaWiki. To do this, we use the three Sentiment Analysis APIs provided by [Datumbox](http://www.datumbox.com/machine-learning-api/), [Meaningcloud](http://www.meaningcloud.com/products/sentiment-analysis), and [Sentiment140](http://help.sentiment140.com/api).

## Installation

* If you are willing to install our extension (if so, thanks!), we assume you have a [MediaWiki](https://www.mediawiki.org/wiki/MediaWiki) with [SemanticMediaWiki](https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki) up and running. If not, set one up first.

* Install the [PHP Chart for MediaWiki](https://www.mediawiki.org/wiki/Extension:Pchart4mw) extension if you don't have it yet, since TwitterSentiment uses it.

* cd into your MediaWiki's `extensions` directory and perform a `git clone <this repository's URL>`.

* Add this line at the bottom of your `LocalSettings.php` to introduce the extension to your MediaWiki:

  `require_once __DIR__ . '/extensions/TwitterSentiment/TwitterSentiment.php';`

* In the newly created `TwitterSentiment` directory, under `resources/140dev/db/`, perform the following configurations:

  * Write your MediaWiki's database credentials into `db_config.php`.

  * In your MediaWiki's database, perform the commands in `mysql_database_schema.sql` in order to create tables needed for the Twitter Streaming API.
  
  * Modify the file `get_tweets.php`: Find the line which starts with `$stream->setTrack` and fill the array with your preferred search terms, e.g. `$stream->setTrack(array('twitter', 'sentiment', 'is', 'awesome'));`. Then save the file.

  * Execute the scripts `get_tweets.php` and `parse_tweets.php` in the background to populate the database. Remember not to let two instances run in parallel (i.e., **stop** them when you are finished / before starting them again)!

* From your browser or, e.g., via [cURL](https://curl.haxx.se/) (i.e., **not** via explorer or terminal, that won't work), execute the script `includes/CategoriesAndProperties.php` in the new `TwitterSentiment` directory.

## Usage

Open your MediaWiki and go to the special page `Special:TwitterFeed`. There you find a feed of tweets related to the search terms defined by you. If you click on a username or user profile pic, you'll get to that user's wiki page. To get to a tweet's wiki page, click on the time specification below the tweet. By clicking on a hashtag in a tweet, you'll get to that hashtag's wiki page.

Once you are on a tweet wiki page, you can see how different Sentiment Analysis web services annotated the tweet. If you click on the small magnifying glasses next to an annotation, an inline query is triggered and you see other tweets with the same annotation by the respective web service. You can also get to the page of the tweet author and, if any, to the pages of all hashtags used in this tweet.

On a user wiki page, you see pie charts of the sentiments expressed in all tweets written by this user, one chart per web service. Apart from that, you see a list of all tweets written by this user.

A hashtag wiki page is quite similar to a user page: You see pie charts about the sentiments in all the tweets that use this respective hashtag and a list with links to all tweets using this hashtag is provided.

Should you have further questions or detect an issue, don't hesitate to drop us a comment here on GitHub!
