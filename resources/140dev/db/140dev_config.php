<?php
/**
* 140dev_config.php
* Constants for the entire 140dev Twitter framework
* You MUST modify these to match your server setup when installing the framework
* 
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/

// OAuth settings for connecting to the Twitter streaming API
// Fill in the values for a valid Twitter app
define('TWITTER_CONSUMER_KEY','kSjzxDpue2LLGSdCmmY0leTUF');
define('TWITTER_CONSUMER_SECRET','KlyHpNaj6b6f7SIup6PWf8qGBXVZZkQFuyaogr3LL76OyEDmGC');
define('OAUTH_TOKEN','4203412523-hHv6MJ21OMlvFFfUgnX9NdzMCmUuWPf5yyphPrT');
define('OAUTH_SECRET','dkL08fHaGXV6SYrO0j4loBCDHMDFTo03NtrVyS864t3LB');

// Settings for monitor_tweets.php
define('TWEET_ERROR_INTERVAL',10);
// Fill in the email address for error messages
define('TWEET_ERROR_ADDRESS','nico.haubner@gmail.com');
?>