<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* CodeIgniter Metacafe
*
* Search and retrieve video content from Metacafe.
*
*
* @package		CodeIgniter
* @subpackage	Sparks
* @category    	Videos
* @author 		Alexandru Budurovici (@w0rldart)
* @copyright	Copyright (C) 2012 by Budurovici Alexandru
* @license		http://www.opensource.org/licenses/mit-license.php
*
*/

class Metacafe {

	const URI_BASE = 'http://www.metacafe.com/';

    private $_uris = array(
    	//Today’s highest rated videos, movies & funny clips by Metacafe.
    	'TODAYS_TOP_RATED_VIDEOS'			=> 'rss.xml',

		//Today’s recently popular videos, movies & funny clips by Metacafe.
    	'TODAYS_RECENTLY_POPULAR'			=> 'recently_popular/rss.xml',

    	//Today’s most discussed videos, movies & funny clips by Metacafe.
    	'TODAYS_MOST_DISCUSSED'				=> 'most_interesting/rss.xml',

    	//Today’s most viewed videos, movies & funny clips by Metacafe.
    	'TODAYS_MOST_VIEWED'				=> 'most_popular/rss.xml',

    	//Today’s most recent videos, movies & funny clips by Metacafe.
    	'TODAYS_MOST_RECENT'				=> 'api/videos/?time=today',

    	//Highest rated videos, movies & funny clips by Metacafe this week/this month/ever.
        'TOP_VIDEOS_WEEK'		            => 'videos/rss.xml',
        'TOP_VIDEOS_MONTH'		            => 'videos/month/rss.xml',
        'TOP_VIDEOS_EVER'		            => 'videos/ever/rss.xml',

        //Recently popular videos, movies & funny clips by Metacafe this week/this month/ever.
        'TOP_VIDEOS_RECENTLY_POPULAR_WEEK'	=> 'videos/recently_popular/rss.xml',
        'TOP_VIDEOS_RECENTLY_POPULAR_MONTH'	=> 'videos/recently_popular/month/rss.xml',
        'TOP_VIDEOS_RECENTLY_POPULAR_EVER'	=> 'videos/recently_popular/ever/rss.xml',

        //Most Interesting videos, movies & funny clips by Metacafe this week/this month/ever.
        'TOP_VIDEOS_MOST_DISCUSSED_WEEK'	=> 'videos/most_interesting/rss.xml',
        'TOP_VIDEOS_MOST_DISCUSSED_MONTH'	=> 'videos/most_interesting/month/rss.xml',
        'TOP_VIDEOS_MOST_DISCUSSED_EVER'	=> 'videos/most_interesting/ever/rss.xml',

        //Most Viewed videos, movies & funny clips by Metacafe this week/this month/ever.
        'TOP_VIDEOS_MOST_VIEWED_WEEK'		=> 'videos/most_popular/rss.xml',
        'TOP_VIDEOS_MOST_VIEWED_MONTH'		=> 'videos/most_popular/month/rss.xml',
        'TOP_VIDEOS_MOST_VIEWED_EVER'		=> 'videos/most_popular/ever/rss.xml',

        //Most Recent videos, movies & funny clips by Metacafe this week/this month/ever.
        'TOP_VIDEOS_MOST_RECENT_WEEK'		=> 'videos/recent/ever/rss.xml',
        'TOP_VIDEOS_MOST_RECENT_MONTH'		=> 'videos/recent/ever/rss.xml',
        'TOP_VIDEOS_MOST_RECENT_EVER'		=> 'videos/recent/ever/rss.xml'
    );

    public function __construct() {}

    /**
     * Executes a request that does not pass data, and returns the response.
     *
     * @param string $uri The URI that corresponds to the data we want.
     * @param array $params additional parameters to pass
     * @return the xml response from metacafe.
     **/
    private function _response_request($uri, array $params = array())
    {
        if( ! empty($params))
            $uri .= '?'.http_build_query($params);

        $url = self::URI_BASE.substr($uri, 1);

        $data = $this->_curl_get($url);
        if($data)
            return $data;
        else
        	return false;
    }

    public function getTopRatedVideoFeed($case = 'EVER')
    {
    	switch($case)
    	{
    		case 'WEEK':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_WEEK']}");
    		break;
    		case 'MONTH':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MONTH']}");
    		break;
    		case 'EVER':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_EVER']}");
    		break;
    	}
    }
    
    public function getMostViewedVideoFeed($case = 'EVER')
    {
    	switch($case)
    	{
    		case 'WEEK':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MOST_VIEWED_WEEK']}");
    		break;
    		case 'MONTH':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MOST_VIEWED_MONTH']}");
    		break;
    		case 'EVER':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MOST_VIEWED_EVER']}");
    		break;
    	}
    }
    
    public function getMostDiscussedVideoFeed($case = 'EVER')
    {
    	switch($case)
    	{
    		case 'WEEK':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MOST_DISCUSSED_WEEK']}");
    		break;
    		case 'MONTH':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MOST_DISCUSSED_MONTH']}");
    		break;
    		case 'EVER':
    			return $this->_response_request("/{$this->_uris['TOP_VIDEOS_MOST_DISCUSSED_EVER']}");
    		break;
    	}
    }
    
    public function getMostRecentVideoFeed()
    {
        return $this->_response_request("/{$this->_uris['TODAYS_MOST_RECENT']}");
    }

    public function getKeywordVideoFeed($keywords, array $params = array())
    {
        $params['vq'] = str_replace(' ', '+', $keywords);
        return $this->_response_request("/api/videos/", array_merge(array('start-index'=>1, 'max-results'=>10, 'time' => 'all_time'), $params));
    }

    public function getTagVideosFeed($tag)
    {
        return $this->_response_request("/tags/".str_replace(' ', '+',mb_strtolower($tag))."/rss.xml");
    }

    public function getItemData($id)
    {
        return $this->_response_request("/api/item/$id/");
    }

    public function getRelatedVideos($id)
    {
        $id = explode('/', $id);
        return $this->_response_request("/api/$id[0]/related");
    }

    public function getEmbedData($id)
    {
        $url = "http://www.metacafe.com/fplayer/".$id.".swf";
        $data = $this->_curl_get($url);
        if($data == "Video does not exist")
        {
            return $result = '<span style="width: 640px; height: 330px; display: block; margin: 15px auto;"><a id="loadFrame" style="position: relative; top: 165px;" href="http://www.metacafe.com/watch/'.$id.'/">Click to load the video</a></span>';
        }
        else
        {
            return $result = $url;
        }
    }
	
	private function _curl_get($url)
	{
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		$output = curl_exec($ch);

		curl_close($ch);

		return $output;    
	}
	
}