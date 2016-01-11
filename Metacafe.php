<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 *
 * CodeIgniter Metacafe
 * Search and retrieve video content from Metacafe.
 *
 *
 * @package     CodeIgniter
 * @subpackage  Sparks
 * @category    Videos
 * @author      Alexandru Budurovici (@w0rldart)
 * @copyright   Copyright (C) 2012 by Budurovici Alexandru
 * @license     http://www.opensource.org/licenses/mit-license.php
 *
 */

class Metacafe
{
    const URI_BASE = 'http://www.metacafe.com/';

    private $uris = array(
        //Today’s highest rated videos, movies & funny clips by Metacafe.
        'TODAYS_TOP_RATED_VIDEOS'       => 'rss.xml',

        //Today’s recently popular videos, movies & funny clips by Metacafe.
        'TODAYS_RECENTLY_POPULAR'       => 'recently_popular/rss.xml',

        //Today’s most discussed videos, movies & funny clips by Metacafe.
        'TODAYS_MOST_DISCUSSED'         => 'most_interesting/rss.xml',

        //Today’s most viewed videos, movies & funny clips by Metacafe.
        'TODAYS_MOST_VIEWED'            => 'most_popular/rss.xml',

        //Today’s most recent videos, movies & funny clips by Metacafe.
        'TODAYS_MOST_RECENT'            => 'newest/rss.xml',

        //Highest rated videos, movies & funny clips by Metacafe this week/this month/ever.
        'VIDEOS_WEEK'                   => 'videos/rss.xml',
        'VIDEOS_MONTH'                  => 'videos/month/rss.xml',
        'VIDEOS_EVER'                   => 'videos/ever/rss.xml',

        //Recently popular videos, movies & funny clips by Metacafe this week/this month/ever.
        'VIDEOS_RECENTLY_POPULAR_WEEK'  => 'videos/recently_popular/rss.xml',
        'VIDEOS_RECENTLY_POPULAR_MONTH' => 'videos/recently_popular/month/rss.xml',
        'VIDEOS_RECENTLY_POPULAR_EVER'  => 'videos/recently_popular/ever/rss.xml',

        //Most Interesting videos, movies & funny clips by Metacafe this week/this month/ever.
        'VIDEOS_MOST_DISCUSSED_WEEK'    => 'videos/most_interesting/rss.xml',
        'VIDEOS_MOST_DISCUSSED_MONTH'   => 'videos/most_interesting/month/rss.xml',
        'VIDEOS_MOST_DISCUSSED_EVER'    => 'videos/most_interesting/ever/rss.xml',

        //Most Viewed videos, movies & funny clips by Metacafe this week/this month/ever.
        'VIDEOS_MOST_VIEWED_WEEK'       => 'videos/most_popular/rss.xml',
        'VIDEOS_MOST_VIEWED_MONTH'      => 'videos/most_popular/month/rss.xml',
        'VIDEOS_MOST_VIEWED_EVER'       => 'videos/most_popular/ever/rss.xml',

        //Most Recent videos, movies & funny clips by Metacafe this week/this month/ever.
        'VIDEOS_MOST_RECENT_WEEK'       => 'videos/newest/rss.xml',
        'VIDEOS_MOST_RECENT_MONTH'      => 'videos/newest/month/rss.xml',
        'VIDEOS_MOST_RECENT_EVER'       => 'videos/newest/ever/rss.xml',
    );

    /**
     * Executes a request that does not pass data, and returns the response.
     *
     * @param string $uri The URI that corresponds to the data we want.
     * @param array $params additional parameters to pass
     * @return the xml response from metacafe.
     **/
    private function getContent($uri, $params = array())
    {
        if (!empty($params)) {
            $uri .= '?' . http_build_query($params);
        }

        $url = self::URI_BASE . $uri;
        $data = $this->curlGet($url);

        if (!$data) {
            $data = false;
        }

        return $data;
    }

    public function getTopRatedVideoFeed($period = 'ever')
    {
        switch ($period) {
            case 'today':
                return $this->getContent("/{$this->uris['TODAYS_TOP_RATED_VIDEOS']}");
                break;
            case 'week':
                return $this->getContent("/{$this->uris['VIDEOS_WEEK']}");
                break;
            case 'month':
                return $this->getContent("/{$this->uris['VIDEOS_MONTH']}");
                break;
            case 'ever':
                return $this->getContent("/{$this->uris['VIDEOS_EVER']}");
                break;
        }
    }

    public function getMostViewedVideoFeed($period = 'ever')
    {
        switch ($period) {
            case 'today':
                return $this->getContent("/{$this->uris['TODAYS_MOST_VIEWED']}");
                break;
            case 'week':
                return $this->getContent("/{$this->uris['VIDEOS_MOST_VIEWED_WEEK']}");
                break;
            case 'month':
                return $this->getContent("/{$this->uris['VIDEOS_MOST_VIEWED_MONTH']}");
                break;
            case 'ever':
                return $this->getContent("/{$this->uris['VIDEOS_MOST_VIEWED_EVER']}");
                break;
        }
    }

    public function getMostDiscussedVideoFeed($period = 'ever')
    {
        switch ($period) {
            case 'today':
                return $this->getContent("/{$this->uris['TODAYS_MOST_DISCUSSED']}");
                break;
            case 'week':
                return $this->getContent("/{$this->uris['VIDEOS_MOST_DISCUSSED_WEEK']}");
                break;
            case 'month':
                return $this->getContent("/{$this->uris['VIDEOS_MOST_DISCUSSED_MONTH']}");
                break;
            case 'ever':
                return $this->getContent("/{$this->uris['VIDEOS_MOST_DISCUSSED_EVER']}");
                break;
        }
    }

    public function getMostRecentVideoFeed()
    {
        return $this->getContent("/{$this->uris['TODAYS_MOST_RECENT']}");
    }

    public function getKeywordVideoFeed($keywords, array $params = array())
    {
        $commonParams = [
            'start-index' => 1,
            'max-results' => 10,
            'time' => 'all_time'
        ];

        $params['vq'] = str_replace(' ', '+', $keywords);
        $params = array_merge($commonParams, $params);

        return $this->getContent("/api/videos/", $params);
    }

    public function getTagVideosFeed($tag)
    {
        return $this->getContent("/tags/" . str_replace(' ', '+', mb_strtolower($tag)) . "/rss.xml");
    }

    public function getItemData($id)
    {
        return $this->getContent("/api/item/$id/");
    }

    public function getRelatedVideos($id)
    {
        $id = explode('/', $id);
        return $this->getContent("/api/$id[0]/related");
    }

    public function getEmbedData($id)
    {
        $url = "http://www.metacafe.com/fplayer/" . $id . ".swf";
        $data = $this->curlGet($url);

        if ($data == "Video does not exist") {
            return $result = '<span style="width: 640px; height: 330px; display: block; margin: 15px auto;"><a id="loadFrame" style="position: relative; top: 165px;" href="http://www.metacafe.com/watch/' . $id . '/">Click to load the video</a></span>';
        } else {
            return $result = $url;
        }
    }

    private function curlGet($url)
    {
        // Initiate the curl session
        $ch = curl_init();

        // Set the URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // Removes the headers from the output
        curl_setopt($ch, CURLOPT_HEADER, 0);

        #curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Return the output instead of displaying it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // Execute the curl session
        $output = curl_exec($ch);

        // Return headers
        $headers = curl_getinfo($ch);

        // Close the curl session
        curl_close($ch);

        return $output;
    }
}
