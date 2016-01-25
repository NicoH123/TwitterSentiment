# TwitterSentiment extension for Semantic MediaWiki

Installation (work in progress):

* Set up a MediaWiki.

* Install Semantic MediaWiki on that MediaWiki.

* git clone this project into your MediaWiki's 'extensions/' directory.

* Add this line at the bottom of your MediaWiki's 'LocalSettings.php':

require\_once \_\_DIR\_\_ . '/extensions/TwitterSentiment/TwitterSentiment.php';

* Perform the following configurations in 'extensions/TwitterSentiment/resources/140dev/db/':

(INTERNAL:) Write twitter API credentials into '140dev_config.php'.

Write your MediaWiki's database credentials into 'db_config.php'.

In your MediaWiki's database, perform the commands in 'mysql_database_schema.sql' in order to create the tables.

Execute the scripts 'get_tweets.php' and 'parse_tweets.php' in the background to populate the database. Remember to stop them after a while and never let two instances run in parallel!

* From your browser or via curl (not via explorer or terminal), execute the script 'extensions/TwitterSentiment/includes/CategoriesAndProperties.php'.

* Open your MediaWiki and go to the special page "Special:TwitterFeed".
