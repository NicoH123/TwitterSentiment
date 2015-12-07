# Seminar-SMW
Repository for the winter term 2015/16 seminar.

Installation (work in progress):

1. Install SemanticMediaWiki.

2. cd to [yoursemanticmediawiki]/extensions.

3. Perform "git clone [Repo-URL]" (TODO name of our extension).

4. Add this line to your SemanticMediaWiki.php (after all the other 'require_once' commands: 
    require_once __DIR__ . '/extensions/TwitterSentiment/[NameOfOurExtension].php';

5. Perform the following configurations in [ourextension]/resources/140dev: ...

...
