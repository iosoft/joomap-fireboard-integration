After installation, open the file  **/administrator/**components/com\_joomap/plugins/fireboard.plugin.php

Search for the line -

```
define("timeFrame", 2); // (0 to 9999) integer only
```

This variable is the _Time Frame Controller_.

> Usage:

> timeFrame=**0**   This will not show any threads of the forum at all.
> > This is for specially those who wants to show categories only, but not the threads/posts.


> timeFrame=**1**     This will show only those forum threads which are 1 year old.
> timeFrame=**2**     This will show only those forum threads which are 2 years old.
> timeFrame=**9999**  This will show only those forum threads which are 9999 year old. Actually all the threads.



Note:

  * It is recommended that you don't set value above 5, else, the sitemap will become very big with links to very old topics.
  * Keep the default value to 2, as in this time (2 years), in that time, your links will be crawled and indexed by search bots, so, links will be saved in Search engine databases.
  * Users looking for old post can easily find it using search engines like Google, Yahoo, MSN etc.