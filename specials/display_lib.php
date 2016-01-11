<?php 
/**
* display_lib.php
* Convert entities into links within a tweet's text
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/


// Convert a tweet creation date into Twitter format
function twitter_time($time) {

  // Get the number of seconds elapsed since this date
  $delta = time() - strtotime($time);
  if ($delta < 60) {
    return 'less than a minute ago';
  } else if ($delta < 120) {
    return 'about a minute ago';
  } else if ($delta < (60 * 60)) {
    return floor($delta / 60) . ' minutes ago';
  } else if ($delta < (120 * 60)) {
    return 'about an hour ago';
  } else if ($delta < (24 * 60 * 60)) {
    return floor($delta / 3600) . ' hours ago';
  } else if ($delta < (48 * 60 * 60)) {
    return '1 day ago';
  } else {
    return number_format(floor($delta / 86400)) . ' days ago';
  } 
}
?>