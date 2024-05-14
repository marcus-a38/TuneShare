<?php

require_once "Limiter.php";

/**
 * 
 * @author Marcus Antonelli
 * 
 * 
*/
class DatabaseObject extends \mysqli {
  
    /**
    *  @var type
    **/
    public $post_fetch_ct;
    
    
    /**
    *  @var type
    **/
    private static $default_settings = [
        
        // Database
        "hostname" => "localhost",
        "username" => "root", 
        "password" => null,
        "database" => "tuneshare",  
        
        // Limiter | [rate, cooldown, slots]
        "reqlimit" => [1.0, 5.0, 7],
        
    ];
    
    
    /**
    *  Rate limiter
    * 
    *  @var type
    **/
    private $limiter;
    
    /**
    *  Binds optional parameters and executes an SQL statement.
    *  @since PHP 8 >= 8.1
    *
    *  @param string $query  SQL query string, optionally parameterized.
    *  @param ?array $params  Optional list of parameters for prepared statements.
    *  @return mysqli_result|bool  True/false for set queries, result/false for get queries.
    **/
    public function __construct(?array $settings = []) {
        
        // Instantiate with database settings
        $intersect = array_intersect_key($settings, self::$default_settings);
        $merged_settings = array_merge(self::$default_settings, $intersect);
        parent::__construct(...array_slice($merged_settings, 0, 4));

        if ($this->connect_errno) {
            echo "<h1>Debug- Database error: " . $this->connect_error . "</h1>";
        }
        
        // Enforce utf8mb4 charset
        $this->set_charset("utf8mb4");
        
        $this->post_fetch_ct = 0;
        $this->limiter = new Limiter(...$merged_settings['reqlimit']);
        
    }
    
    
    /**
    *  Binds optional parameters and executes an SQL statement.
    *  @since PHP 8 >= 8.1
    *
    *  @param string $query  SQL query string, optionally parameterized.
    *  @param ?array $params  Optional list of parameters for prepared statements.
    *  @return mysqli_result|bool  True/false for set queries, result/false for get queries.
    **/
    
    // Rename "query_generic()"
    public function query_with(
            string $query, 
            ?array $params = [],
            bool $bypass = false) {
        
        // Abort query if the limiter rejects the request.
        if (!$this->limiter->make_new_req() && !$bypass) {
            return -1;
        }
        
        // Encapsulate the given query in an individual transaction.
        $this->begin_transaction();
        $response = $this->execute_query($query, $params);
        $this->commit();
    
        return $response;
        
    }
    
    
    /**
    * Binds optional parameters and executes an SQL statement.
    * @since PHP 8 >= 8.1
    *
    * @param string $query  SQL query string, optionally parameterized.
    * @param ?array $params  Optional list of parameters for prepared statements.
    * @return mysqli_result|bool  True/false for set queries, result/false for get queries.
    **/
    public function get_query(
            string $query, 
            ?array $params = [], 
            bool $bypass = false) {      
        
        // Acquire a shared lock.
        $sql = $query
               . " LOCK IN SHARE MODE;";
        $response = $this->query_with($sql, $params);

        // Return an associative array of the results, or false if no results.
        return $response ? $response->fetch_all(MYSQLI_ASSOC) : false; 
        
    }
   
    
    /**
    * Binds optional parameters and executes an SQL statement.
    * @since PHP 8 >= 8.1
    *
    * @param string $query  SQL query string, optionally parameterized.
    * @param ?array $params  Optional list of parameters for prepared statements.
    * @return mysqli_result|bool  True/false for set queries, result/false for get queries.
    **/
    public function set_query(
            string $query, 
            ?array $params = [], 
            bool $bypass = false){
        
        // Acquire an exclusive lock.
        $sql = $query
               . " FOR UPDATE;";
        
        // Return true on successful queries, false on unsucessful.
        return $this->query_with($sql, $params);
        
    }
    
}

?>