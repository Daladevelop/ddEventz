<?php



class DDeventz
{
	public function __construct()
	{
		// init the application
		$this->initApp();
	}


	public function initApp()
	{

		//for now we dont need to do anythin else then generate the feed, this will for sure change when we get further away
		$this->generateFeed(); 
	
	
		if(isset($_REQUEST['callback']))
		{
			echo $_REQUEST['callback'];
			echo "(";
			echo json_encode($this->feed);
			echo ")"; 
		}

		elseif(isset($_REQUEST['debug']))
		{
			echo "<pre>".print_r($this->feed,true)."</pre>"; 

		}
		
		else
		{
			echo "<a href='index.php?callback=?'>Callback</a><br/>";
		    echo "<a href='index.php?debug=1'>Debug</a><br/>"; 	
		}
	}

	public function generateFeed()
	{


		global $parameters; 
		$this->feed = array();
		$this->mediaHook = array();

		//iterate through all plugins
		foreach(pluginLoader::plugins() as $plugin)
		{
			//throw our parameters to the plugin
			$plugin->setParameters($parameters); 

			//get the feed from the plugin
			$newItems = json_decode($plugin->getFeed());
			foreach($newItems as $item)
			{
				array_push($this->feed, $item);
			}			
		}

        //Sort the feed 2.0 :) 
        usort($this->feed, function($a,$b){
            if($a->time == $b->time)
                return 0;
            elseif($a->time > $b->time)
                return -1;
            elseif($a->time < $b->time)
                return 1; 


        });


	}




}



?>
