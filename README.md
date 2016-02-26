# TwitterSentiment

TwitterSentiment is an extension for Semantic MediaWiki which enables you to quickly analyze the mood about different topics on Twitter, right from your MediaWiki.

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

Open your MediaWiki and go to the special page `Special:TwitterFeed`. There you find a feed of tweets related to the search terms defined by you:

![Example of TwitterSentiment feed](Unbenannt.png)

