<?php

const ONE_SECOND_IN_MICRO = 1_000_000; // μs

/*--/
/* 
/*  
/*
/*  @author Marcus Antonelli * contact@marcusantonelli.com
/* pre
*/
class DatabaseObject extends \mysqli {
  
    
    public $post_fetch_ct;
    
    /**
    *  Default database connection settings
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
    public function __construct(?array $settings = []) {
        
        // Apply settings
        $intersect = array_intersect_key($settings, self::$default_settings);
        $merged_settings = array_merge(self::$default_settings, $intersect);
        parent::__construct(...$merged_settings);
        
        if ($this->connect_errno) {
            echo "<h1>Debug- Database error: " . $this->connect_error . "</h1>";
        }
        
        // Enforce utf8mb4 charset
        $this->set_charset("utf8mb4");
        
        $this->post_fetch_ct = 0;
        
    }
    
    public function query_with(string $query, ?array $params) {
        
        $this->begin_transaction();
        $response = $this->execute_query($query, $params);
        $this->commit();
        
        usleep(ONE_SECOND_IN_MICRO/2);
        return $response;
        
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
    public function get_query(string $query, ?array $params = []) {      
        
        // Shared lock
        $sql = $query
               . " FOR SHARE;";
        $response = $this->query_with($query, $params);
        return $response ? $response->fetch_all(MYSQLI_ASSOC) : false; 
        
    }
   
    /**
    **
    ** 
    **  public function set_query:
    ** 
    **  Carries out CREATE, UPDATE, or DELETE query on database,
    **  indicates success(true)/failure(false) in doing so.
    ** 
    **  @param string $query
    **  @param ?array $params
    ** 
    **  @returns bool $response - True/false to indicate query success/failure
    **
    ** 
    */
    public function set_query(string $query, ?array $params = []) {
        
        // Exclusive lock
        $sql = $query
               . " FOR UPDATE;";
        return $this->query_with($sql, $params);
        
    }
    
}

?>