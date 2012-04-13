<?php
require_once('lib/simplepie/simplepie.inc');
require_once('plugin.interface.php');

class rss implements pluginInterface{

    public function __construct()
    {   
        $this->url="http://larsemil.se/feed/";

    }

    public function setParameters(array $parameters)
    {
		//we get some parameters, but as RSS i dont think we need them do we? 

    }

    public function getFeed()
    {
        return $this->parseRSS();

    }

    private function parseRSS()
	{
		$feed = new SimplePie();

		// Set which feed to process.
		$feed->set_feed_url($this->url);

		// Run SimplePie.
		$feed->init();

		// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
		$feed->handle_content_type();		
		$items = array();
		foreach ($feed->get_items() as $key=>$item):
				array_push($items, array(
					'id' => $item->get_title(),
					'metadata' => array(
									'service' => 'RSS',
									'handle' => $feed->get_title(),
									'URI' => $item->get_permalink() 	
								),
					'content' => htmlentities($item->get_description()),
					'time' => date("U",$item->get_date())
				));
		endforeach;
		return json_encode($items); 


	}


}


?>

