<?php

/*
 * -- the different logging levels and how they work
 *
 * if logLevel is set to NONE, it will not log any entries, if its set to FATAL it will only log FATAL entries, if its set to DEBUG it will SHOW warnings, and FATAL entries and if its set to ALL it will show ALL entries what so ever
 */

define(NONE,0);
define(FATAL, 1);
define(DEBUG, 2);
define(ALL, 3);





class logger{

    static $logfile = "log.txt";

    //what level to log, in production it should be set to fatal, in development it can be either DEBUG or ALL. ALl will give some more metadata that can be useful.
    static $logLevel = ALL;

    //filehandler to use 
    static $logFileHandler; 

    public static function init()
    {
		if(self::log(ALL,"\r\n\r\nStarting up logger"))
			return true;
		else
		{
			echo "Could not start logger. Check permissions. "; 
		}

    }

    public static function log($level, $msg)
    {

        $logLevels = array("NONE","FATAL","DEBUG","ALL");
        //only log if loglevel is set to something else then NONE
        if(self::$logLevel)
        {
        
            //check current logLevel and see if the entry should be printed
            if($level <= self::$logLevel)
            {
                //log the entry
				if(file_put_contents(self::$logfile, date("Y-m-d H:i:s")." LOGLEVEL: ".$logLevels[$level]."\t ".$msg."\r\n",FILE_APPEND))
					return true; 


            }



        }        




    }

    public static function shutDown()
    {
        self::log(ALL, "Ending logging session. Have a nice day. ");
    }
}



?>
