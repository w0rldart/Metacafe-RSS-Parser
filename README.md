# What is it?

This is a PHP library to parse Metacafe's RSS feed (which they always change, and don't offer documentation), and play a bit with it :)

## Demo usage

This library is curently in use at http://www.videouri.com and it works great. Enjoy

### Basic usage

	$page = isset($parameters['page']) ? 1 + ($parameters['page']-1) * 10 : 1;
	
	switch ($parameters['action']) {
		case 'newest':
			$result = $this->metacafe->getMostRecentVideoFeed();
			break;
		case 'topRated':
			$result = $this->metacafe->getTopRatedVideoFeed();
			break;
		case 'mostViewed':
			$result = $this->metacafe->getMostViewedVideoFeed();
			break;
		/* Search and tags content */
		case 'search':
			$result = $this->metacafe->getKeywordVideoFeed($parameters['query'], array('start-index'=>$page, 'max-results' => 10));
			break;
		case 'tag':
			$result = $this->metacafe->getTagVideosFeed($parameters['query']);
			break;

		/* Video page with data and related videos */
		case 'getVideoEntry':
			//where $id could be (as an example) 304534 from 304534/bmw_m5_brutalllllllllll_drifting
			$result = $this->metacafe->getItemData($id);
			break;
		case 'related':
			$result = $this->metacafe->getRelatedVideos($id);
			break;
	}
	
Contribute
----------

1. Check for open issues or open a new issue for a feature request or a bug
2. Fork [the repository][] on Github to start making your changes
4. Send a pull request

[the repository]: https://github.com/w0rldart/metacafe-rss-parser
