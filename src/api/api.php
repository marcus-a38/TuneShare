<?php

/*--/
/* 
/*  TuneShare
/* 
/*  API * Version 1 * 3/20/24
/* 
/--*/

namespace api;

require_once 'Request.php';
use api\Request;

/*--/
/* 
/*  Inherits mysqli object, to be used with throttler
/*
/*  @author Marcus Antonelli * contact@marcusantonelli.com
/* 
*/
class DatabaseObject extends \mysqli {
   
    /*--/
    /*
    /*  Implement next
    */
    private $throttler;
    
    /*--/
    /*
    /*  Most recent action (timestamp, UNIX)
    */  
    private $time;
    
    /*--/
    /*
    /*  Last unique request ID
    */
    private $curr;
    
    /*--/
    /*
    /*  User-provided database connection settings
    */
    private $settings;
    
    /*--/
    /*
    /*  Default database connection settings
    */
    private static $default_settings = [
        "hostname" => "localhost",
        "username" => "root", 
        "password" => null, 
        "database" => "tuneshare"
    ];
    
    /*--/
    /* 
    /*  Constructor - Uses provided settings to create a connection to a MySQL
    /*  database. Setups up data format and throttler.
    /* 
    /*  @param array $settings - Connection settings for DatabaseObject
    /*
    */
    public function __construct(array $settings) {
        
        // Apply settings
        $intersect = array_intersect_key($settings, self::$default_settings);
        $this->settings = array_merge(self::$default_settings, $intersect);
        
        // Connect
        parent::__construct(...$this->settings);
        
        // Initialize time and starting request ID
        $this->time = microtime(true);
        $this->curr = 0;
        
        // Enforce utf8mb4 charset
        $this->set_charset("utf8mb4");
        
    }
    
    /*--/
    /*
    /*  Destructor - Ensures proper commit and closure of connection on exit
    /*  
    */
    public function __destruct() {
        
        $this->commit();
        $this->close();
        
    }
    
    /*--/
    /* 
    /*  Instantiates a request object to encapsulate SQL query
    /* 
    /*  @param string $query - Pure, parameterized SQL query
    /*  @param ?array $params - Parameters to insert into the query
    /*
    /*  @returns Request _ - Encapsulates query, setting up for throttling
    /*
    */
    public function prepare_request(string $query, ?array $params = []) {
        
        // Request($id, $sql, $params)
        return new Request($this->curr++, $query, $params);
        
    }
    
    /*--/
    /* 
    /*  Executes READ queries on MySQL database, and either returns
    /*  desired rows, or indicates failure (false value).
    /* 
    /*  @param Request $request - MySQL request/query to execute
    /* 
    /*  @returns [array | bool] _ - If there are successful results, return 
    /*  array containing individual associative arrays for each SELECTed row.
    /*  on failure, return false
    /* 
    /*  Footnote: This method needs to be adjusted in the future. The
    /*  Request attributes are unused, but will be necessary in the future,
    /*  when throttling methods are implemeneted.
    /*
    */
    public function get_query(Request $request) {
        
        $response = $this->execute_query($request->query, $request->params);
        $request->response = $response;
        $this->time = microtime(true);
        
        return ($response) ? $response->fetch_all(MYSQLI_ASSOC) : false;
        
    }
   
    /*--/
    /*
    /*  Executes CREATE, UPDATE, or DELETE queries on MySQL database
    /*  and indicates success or failure in doing so (true or false value)
    /* 
    /*  @param Request $request - MySQL request/query to execute 
    /* 
    /*  @returns bool $response - True/false to indicate query success/failure
    /*
    /*  Footnote: This method needs to be adjusted in the future. The
    /*  Request attributes are unused, but will be necessary in the future,
    /*  when throttling methods are implemeneted.
    /* 
    */
    public function set_query(Request $request) {
        
        $response = $this->execute_query($request->query, $request->params);
        $request->response = $response;
        $this->time = microtime(true);
        
        return $response;
        
    }
    
}

// If no database connection currently exists, create one
global $connection;
if (!isset($connection)) { $connection = new DatabaseObject([""]); }

?>