<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use App\Link;

class LinkTest extends TestCase
{
    private $link = null;

    public function __construct()
    {
        parent::__construct();

        $this->link = new Link;
    }
    
    public function testBasicTest()
    {   
        $this->assertTrue(true);
    }

    public function testToShort()
    {    
        $this->link->id = 81;
        $this->assertEquals($this->link->toShort(), '1N');
        
        $this->link->id = 100;
        $this->assertEquals($this->link->toShort(), '1g');
        
        $this->link->id = 14200;
        $this->assertEquals($this->link->toShort(), '4Cm');

        $this->link->id = 34563455124523;
        $this->assertEquals($this->link->toShort(), 'FbrW9vlv');
    }

    public function testfromShort()
    {
        $this->assertEquals($this->link->fromShort('1N'), 81);
        $this->assertEquals($this->link->fromShort('1g'), 100);
        $this->assertEquals($this->link->fromShort('4Cm'), 14200);
        $this->assertEquals($this->link->fromShort('FbrW9vlv'), 34563455124523);
    }

    public function testGetSecret()
    {
        $this->link->id = 81;
        $this->assertEquals(
            $this->link->getSecret(),
            '2h44CPOM8hLXM8E7U2ekQaBfl2Zjp9Zu9Q4jY4evRMpVNCsW3RBBQ6j'
        );
        
        $this->link->id = 100;
        $this->assertEquals(
            $this->link->getSecret(),
            '4rsNsdT8aPmPTFFYuIXgqlLdeiJGFmJQFZ1M4kjC5GYd0XS231fAHkH'
        );
        
        $this->link->id = 14200;
        $this->assertEquals(
            $this->link->getSecret(),
            '4m8ltrWQGZjLgT4EtlUaJJMOpTeBifZKPFm9pqT8p8pdKpmajpTXG8H'
        );

        $this->link->id = 34563455124523;
        $this->assertEquals(
            $this->link->getSecret(),
            '2MtHBHTnCJR1INvGTK5QPbJIL1LnoJ9rnI5luXoQomiGsG5PoYpRgCS'
        );
    }

    public function testIsExpiried()
    {
        $this->link->expiration_to = Carbon::parse(strtotime('-1 day'));
        $this->assertTrue($this->link->isExpiried());
        
        $this->link->expiration_to = Carbon::now();
        $this->assertTrue($this->link->isExpiried());

        $this->link->expiration_to = Carbon::parse(strtotime('+1 day'));
        $this->assertFalse($this->link->isExpiried());
    }

    public function testAddTtl()
    {
        $this->link->addTTL(100);
        $this->assertEquals(strtotime($this->link->expiration_to), time() + 100);
    }
}
