<?php



class DDeventz
{
	public static $eventId;

	public function __construct()
	{

		// init the application
		$this->initApp();

	}


	public function initApp()
	{

        //for now we dont need to do anythin else then generate the feed, this will for sure change when we get further away
        //

        if(isset($_REQUEST['eventId']))
		{
            $this->generateFeed($_REQUEST['eventId']); 


            if(isset($_REQUEST['callback']))
            {
                logger::log(DEBUG,"jsonp callback found. Serving feed as jsonp"); 
                echo $_REQUEST['callback'];
                echo "(";
                echo json_encode($this->feed);
                echo ")"; 
            }

            elseif(isset($_REQUEST['debug']))
            {
                logger::log(DEBUG,"Debug mode. Serving as nice array");
                echo "<pre>".print_r($this->feed,true)."</pre>"; 

            }

            else
            {
                echo "<a href='index.php?callback=?&eventId=".$_REQUEST['eventId']."'>Callback</a><br/>";
                echo "<a href='index.php?debug=1&eventId=".$_REQUEST['eventId']."'>Debug</a><br/>"; 	
            }
        }
        else
            logger::log(DEBUG,"App loaded without eventId. Halting"); 
	}



	public function generateFeed($eventId)
	{


		$this->feed = array();
		$this->mediaHook = array();

		//iterate through all plugins
		foreach(pluginLoader::plugins() as $plugin)
		{
            //get the parameters for the plugin from the db
            $sql = "select parm,value,instance from events_plugins where eventId =$eventId and plugin='".get_class($plugin)."'";

//            logger::log(DEBUG,$sql); 
            $parameters = array();  

            //have it the correct way in an array. key => value. 
            foreach(DB::$dbh->query($sql) as $parm)
			{
                $parameters[$parm['instance']][$parm['parm']] = $parm['value'];
                

            }
			foreach($parameters as $key => $instance)
			{

				$plugin->setParameters($instance); 

				//get the feed from the plugin
				$newItems = json_decode($plugin->getFeed($key));
				logger::log(DEBUG, "Getting the feed-items from ".get_class($plugin)); 

				if(!is_array($newItems))
				{
					logger::log(DEBUG,get_class($plugin)." returned no objects. "); 
					continue; 
				}	
				//loop through the items
				foreach($newItems as $item)
				{
					array_push($this->feed, $item);
				}
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
