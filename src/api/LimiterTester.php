<?php

require_once "Limiter.php";

class LimiterAssertionError extends \RuntimeException {}
class LimiterTester {
    
    public $limiter;
    private $test_num;
    
    
    public function __construct(&$limiter) {
        $this->limiter = $limiter;
        $this->test_num = 1;
    }
    
    
    public function assert($condition, $details=false, $description="") {
        
        if (!$condition) {
            $msg = "Test failed in file '".__FILE__ ."' at line ".__LINE__.".";
            
            if ($description) {
                $msg .= PHP_EOL."Description: ".$description;
            }
                        
            if ($details) {
                $in_cooldown = $this->limiter->is_in_cooldown() ? "Yes" : "No";
                $last_activity = $this->limiter->get_when() ?: "None";
                $msg .= PHP_EOL."More details: "
                     .  PHP_EOL."1. Slot count: ".$this->limiter->get_num_slots()
                     .  PHP_EOL."2. Inactive: ".$this->limiter->get_num_obs()
                     .  PHP_EOL."3. Cooldown?: ".$in_cooldown
                     .  PHP_EOL."4. Rate: ".$this->limiter->get_rate()
                     .  PHP_EOL."5. Last activity: ".$last_activity
                     .  PHP_EOL.PHP_EOL;
            }
            
            try {
                throw new LimiterAssertionError($msg);
            } catch (LimiterAssertionError $e) {
                echo nl2br($e);
                exit;
            }
        }
        
        echo nl2br("Test success for test #".$this->test_num++.PHP_EOL.PHP_EOL);
    }
    
}


function test_general_requesting(&$tester) {
    
    echo nl2br("General Requests".PHP_EOL);
    
    $limiter = &$tester->limiter;
    
    // Test general requestingH
    for ($i = 0; $i < $limiter->get_num_slots(); $i++) {
        $tester->assert($limiter->make_new_req(), true, "Request failed");
    }
    
    // The limiter should be in cooldown after that burst
    $tester->assert($limiter->is_in_cooldown(), true, "Cooldown not activated");
    
}


function test_cooldown(&$tester) {
    
    echo nl2br("Cooldown Tests".PHP_EOL);
    
    $limiter = &$tester->limiter;
    
    // Set all slots to active
    $limiter->set_all_active();
    
    // The limiter should be in cooldown after that burst
    $tester->assert($limiter->is_in_cooldown(), true, "Cooldown not activated");
    
    $cooldown_length = $limiter->get_cooldown();
    
    // Test if a request is allowed during cooldown
    usleep(($cooldown_length - 0.15) * ONE_SECOND_IN_MICRO);
    $tester->assert(!$limiter->make_new_req(), true, "Success during cooldown");
    

    // Test if a request is rejected after cooldown
    usleep(0.15 * ONE_SECOND_IN_MICRO);
    $tester->assert($limiter->make_new_req(), true, "Rejection after cooldown");

}

// Limiter with 1s rate, 5s burst cooldown, and 7 slots
$limiter = new Limiter(1.0, 5.0, 7);
$tester = new LimiterTester($limiter);

// Test basic requesting
test_general_requesting($tester);

// Let the cooldown expire
usleep($limiter->get_cooldown() * ONE_SECOND_IN_MICRO);

// Test basic cooldown
test_cooldown($tester);

?>