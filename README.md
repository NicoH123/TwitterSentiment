# TwitterSentiment extension for Semantic MediaWiki

Installation (work in progress):

*Set up a MediaWiki.

2. Install Semantic MediaWiki on that MediaWiki.

3. git clone this project into your MediaWiki's 'extensions/' directory.

4. Add this line at the bottom of your MediaWiki's 'LocalSettings.php':

require\_once \_\_DIR\_\_ . '/extensions/TwitterSentiment/TwitterSentiment.php';

5. Perform the following configurations in 'extensions/TwitterSentiment/resources/140dev/':

(INTERNAL:) Write twitter API credentials into '140dev_config.php'.

Write your MediaWiki's database credentials into 'db_config.php'.

In your MediaWiki's database, perform the commands in 'mysql_database_schema.sql' in order to create the tables.

Execute the scripts 'get_tweets.php' and 'parse_tweets.php' in the background to populate the database. Remember to stop them after a while and never let two instances run in parallel!

6. From your browser or via curl (not via explorer or terminal), execute the script 'extensions/TwitterSentiment/includes/CategoriesAndProperties.php'.

7. Open your MediaWiki and go to the special page "Special:TwitterFeed".
