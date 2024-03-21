<?php

/*/
/* 
/*  TuneShare
/* 
/*  Request * Version 1 * 3/20/24
/* 
/*  When throttler is implemented, the identifiers will play more of a role-
/*  we will use some algorithm like leaky bucket, where multiple requests can
/*  be bursted and queued simultaneously.
/* 
/*/

namespace api;

/*--/
/* 
/*  Basic data structure to encapsulate SQL queries and assist traffic control
/*
/*  @author Marcus Antonelli * contact@marcusantonelli.com
/* 
*/
class Request {

    /*--/
    /*
    /*  Creation timestamp
    */
    public $when;
   
    /*--/
    /*
    /*  String SQL query
    */
    public $query;
     
    /*--/
    /*
    /*  Unique integer identifier
    */
    public $identifier; 
    
    /*--/
    /*
    /*  Current query/request status
    */
    public $status;
     
    /*--/
    /*
    /*  Array of returned values from query, null by default
    */
    public $response;
    
    /*--/
    /*
    /*  Constructor - Instantiate 
    /* 
    /*  @param int $id - Unique identifier for the request
    /*  @param string $sql - 
    /*  @param ?array $params - 
    /* 
    /*
    */
    function __construct(int $id, string $sql, ?array $params = []) {
        
        $this->when = microtime(true);
        $this->query = $sql;
        $this->identifier = $id;
        $this->params = $params;
        $this->status = RequestStatus::IDLE;
        $this->response = null;
        
    }
    
    /*--/
    /* 
    /*  Checks how much time has elapsed since the request was created
    /* 
    /*  @returns float _ - Difference of creation time and current time (UNIX)
    /*
    */
    public function time_diff() {
        
        return microtime(true) - $this->when;
        
    }

}

/*--/
/* 
/*  Basic enumerator for request status
/* 
/*  @case IDLE - Default, inactive
/*  @case SENT - Database has been queried
/*  @case DONE - Query was successful/complete
/*  @case FAIL - Query was unsuccessful
/*
*/
enum RequestStatus {    
    
    case IDLE;
    case SENT;
    case DONE;
    case FAIL; 
    
}

?>