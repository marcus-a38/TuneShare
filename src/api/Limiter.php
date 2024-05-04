<?php

/*
* 
*  Implementation of/spin on token bucket algorithm
* 
*  This is NOT secure or tested for production.
* 
*/

const ONE_SECOND_IN_MICRO = 1e6; // μs

// Value for inactive/obsolete slots.
const OBSOLETE = -1; 

class Limiter {
    
    private static $rate;       // Rate at which an active slot becomes obsolete
    private static $limit;      // Limit of total slots at a given time
    private static $cooldown;   // Rate at which 100% active limiter replenishes
    private $slots;             // Array holding "slots" (or tokens)
    private $cooldown_flag;     // Flag (on/off) for cooldown period
    private $when;              // Last slot occupation timestamp
    private $num_obs;           // Total number of obsolete/inactive slots
    
    
    public function __construct($request_rate, $burst_cooldown, $num_slots) {
        
        // Set static attributes and initial value for total obsolete slots.
        self::$limit = $this->num_obs = $num_slots;
        self::$rate = $request_rate;
        self::$cooldown = $burst_cooldown;
        
        // Fill an entire, new array with obsolete slots.
        $this->slots = array_fill(0, self::$limit, OBSOLETE);  
        
    }
    
    
    public function make_new_req() {

        // Cooldown in effect? Check if it's expired. If not, exit function.
        if ($this->cooldown_flag) {
            
            $elapsed = microtime(true) - $this->when;
            
            if ($elapsed < self::$cooldown) {
                return false;
            }
            
            $this->cooldown_flag = false;
        } 
        
        // Flag, true when the request has successfully occupied a slot
        $flag = false;
        
        // Check the status of every slot in the array.
        foreach ($this->slots as &$slot) {
            
            $time = microtime(true) - $slot;
            
            // Encountered an active slot that has expired (now obsolete)
            if ($slot != OBSOLETE && $time > self::$rate) { 
                $slot = OBSOLETE;
                $this->num_obs++;
            } 
            
            // Encountered an obsolete (empty) slot, let's use for this request.
            else if ($slot == OBSOLETE && !$flag) {
                $slot = $this->when = microtime(true);
                $this->num_obs--;
                $flag = true;
            }
        }
        
        // No slot was occupied? All must be active, so cooldown from the burst.
        if (!$flag || $this->num_obs == 0) { $this->cooldown_flag = true; }   
        return $flag;
        
    }
    
    
    // Don't allow if >= limit
    public function add_slot() {
        
        // Number of slots at limit, reject slot addition.
        if ($this->get_num_slots() == self::$limit) {
            return false;
        } 
        
        // Number of slots exceeds limit, remove the excess slots and reject.
        else if ($this->get_num_slots() > self::$limit) {
            do {
                $this->rem_slot();
            } while ($this->num_slots() > self::$limit);
            return false;
        }
        
        // We can now push a new (obsolete) slot to the end of the slots array.
        array_push($this->slots, OBSOLETE);
        
        // Increment number of obsolete slots
        $this->num_obs++;
        
        return true;
        
    }
    
    
    public function rem_slot() {
        
        // Number of slots at minimum (1), reject slot removal.
        if ($this->get_num_slots() == 1) {
            return false;
        }
        
        // There are no/negative slots, add the missing slots and reject.
        else if ($this->get_num_slots() < 1) {
            do {
                $this->add_slot();
            } while ($this->num_slots() < 1);
            return false;
        }
        
        // Remove the first encountered obsolete slot.
        foreach ($this->slots as $i => &$slot) {
            if ($slot == OBSOLETE) {
                unset($this->slots[$i]);
                $this->num_obs--;
                return true;
            }
        }
        
        // There were no encountered obsolete slots.
        return false;
    }
    
    
    public function set_all_active() {
        while ($this->num_obs > 0) { 
            $this->make_new_req();
        }
    }
    
    // Inline functions
    public function get_num_obs() { return $this->num_obs; }
    public function get_num_slots() { return sizeof($this->slots); }
    public function get_cooldown() { return self::$cooldown; }
    public function get_rate() { return self::$rate; }
    public function get_when() { return $this->when; }
    public function is_in_cooldown() { return $this->cooldown_flag; }
    public function inactive() { return $this->num_obs==$this->get_num_slots(); }
    
}

?>