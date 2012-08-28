# CodeIgniter-Metacafe

CodeIgniter-Metacafe is a CodeIgniter library to be used to search and retrieve content from Metacafe.

## Demo usage

This library is curently in use at http://www.videouri.com and it works great. Enjoy

## Requirements

1. PHP 5 (configured with cURL enabled)
2. CodeIgniter 2.0 + (tested only on CI 2.0 and above)

## Examples

	$this->load->library('metacafe'); 

### Basic usage

	$page = isset($parameters['page']) ? 1 + ($parameters['page']-1) * 10 : 1;
	
	switch ($parameters['action'])
	{
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
	
## Support

I will write a blog entry with more detailed example usage of the library, so, stay tuned!

You may also find me at http://twitter.com/w0rldart

Contribute
----------

1. Check for open issues or open a new issue for a feature request or a bug
2. Fork [the repository][] on Github to start making your changes
4. Send a pull request

[the repository]: https://github.com/w0rldart/codeigniter-metacafe