<?php
    /* Here we add the settings for the database and start up a pdo database handler. Wohoo! */

global $dbh; 
// class to do some checks and db stuff for the app. like checking tables and adding them etc.
class DB
{
    public static $dbh;

    public static function init()
    {
        $hostname = DBHOST;
		$username = DBUSER;
		$password = DBPASSWORD;
		
		/*$hostname = "localhost"; 
        $username = "root";
        $password = "tjipp"; 
		*/

        try{

            self::$dbh = new PDO("mysql:host=$hostname;dbname=ddEventz", $username, $password);

    }
    catch(PDOException $e)
    {

		logger::log(FATAL,$e->getMessage()); 
		return false; 
    }

     //check tables
        
		return self::checkTables();
			


    }

    public static function checkTables()
    {
       //check that tables needed exist
        logger::log(ALL,"Checking if tables exist"); 

        //array with all tables needed for the app. 
        $tables = array("events","events_plugins");


        $errorcode = 0; 

        //check each table if it exists
        foreach($tables as $table)
        {
            $sql = "select 1 from ".$table;
            $result = self::$dbh->query($sql);

            
            if(!is_object($result))
            {
                //table does not exist
                logger::log(DEBUG,"Table $table did not exist. Will attempt to create it");

                $errorCode = self::addTable($table);
            
            }
            else
                logger::log(DEBUG, "Found table $table.");
        }
        
        $errorCodes = array("OK", "COULD NOT CREATE TABLE", "FATAL"); 
        if($errorCode)
        {
			logger::log(DEBUG,"Table check ended with errorcode: $errorCode - ".$errorCodes[$errorCode]); 
			return false; 
        }
		else
		{
			logger::log(DEBUG,"All tables needed where found"); 
			return true;
		}

    }

    public static function addTable($table)
    {
        /* On a new install if the tables are missing, use this method to add them */

        switch($table)
        {
            case("events"):
                
                $sql = "CREATE TABLE IF NOT EXISTS `events` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `namn` varchar(255) NOT NULL,
                        `startDate` datetime NOT NULL,
                        `endDate` datetime NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";


                break;
            
            case("events_plugins"):

                $sql = "CREATE TABLE IF NOT EXISTS `events_plugins` (
                        `eventId` int(11) NOT NULL,
                        `plugin` varchar(100) NOT NULL,
                        `parm` varchar(100) NOT NULL,
                        `value` varchar(100) NOT NULL
                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";


                break;

            default:
                logger::log(DEBUG,"Dont know how to create table $table");
                return 1;
                break; 
        }
    
           
            db::$dbh->exec($sql);
            return 0; 





    }



}
