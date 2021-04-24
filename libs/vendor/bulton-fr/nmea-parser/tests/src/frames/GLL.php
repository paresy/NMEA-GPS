<?php

namespace BultonFr\NMEA\Frames\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class GLL extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\GLL $frame The GLL frame instance used by unit test
     */
    protected $frame;
    
    /**
     * Called before each test method
     * 
     * @param string $methodName The name of the test method which be called
     * 
     * @return void
     */
    public function beforeTestMethod($methodName)
    {
        $this->frame = new \BultonFr\NMEA\Frames\GLL(
            '$GPGLL,4916.45,N,12311.12,W,225444,A,*1D'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frames\GLL::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('GLL');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::decodeFrame method and call all getters
     * 
     * @return void
     */
    public function testDecodeFrameAndGetters()
    {
        $this->assert('Frames\GLL::decodeFrame()')
            ->variable($this->invoke($this->frame)->readFrame())
                ->isNull()
        ;
        
        $this->testGetLatitude();
        $this->testGetLatitudeDirection();
        $this->testGetLongitude();
        $this->testGetLongitudeDirection();
        $this->testGetUtcTime();
        $this->testGetStatus();
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getLatitude
     * 
     * @return void
     */
    protected function testGetLatitude()
    {
        $this->assert('Frames\GLL::getLatitude()')
            ->string($this->frame->getLatitude())
                ->isEqualTo('4916.45')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getLatitudeDirection
     * 
     * @return void
     */
    protected function testGetLatitudeDirection()
    {
        $this->assert('Frames\GLL::getLatitudeDirection()')
            ->string($this->frame->getLatitudeDirection())
                ->isEqualTo('N')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getLongitude
     * 
     * @return void
     */
    protected function testGetLongitude()
    {
        $this->assert('Frames\GLL::getLongitude()')
            ->string($this->frame->getLongitude())
                ->isEqualTo('12311.12')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getLongitudeDirection
     * 
     * @return void
     */
    protected function testGetLongitudeDirection()
    {
        $this->assert('Frames\GLL::getLongitudeDirection()')
            ->string($this->frame->getLongitudeDirection())
                ->isEqualTo('W')
        ;
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getUtcTime
     * 
     * @return void
     */
    protected function testGetUtcTime()
    {
        $this->assert('Frames\GLL::getUtcTime()')
            ->object($utcTime = $this->frame->getUtcTime())
                ->isInstanceOf('\DateTime')
            ->string($utcTime->format('H'))
                ->isEqualTo('22')
            ->string($utcTime->format('i'))
                ->isEqualTo('54')
            ->string($utcTime->format('s'))
                ->isEqualTo('44')
            ->object($timezone = $utcTime->getTimeZone())
                ->isInstanceOf('\DateTimeZone')
            ->string($timezone->getName())
                ->isEqualTo('UTC')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GLL::getStatus
     * 
     * @return void
     */
    protected function testGetStatus()
    {
        $this->assert('Frames\GLL::getStatus()')
            ->string($this->frame->getStatus())
                ->isEqualTo('A')
        ;
    }
    
    public function testGetMode()
    {
        $this->assert('Frames\RMC::getMode()');
        
        $this->frame = new \BultonFr\NMEA\Frames\GLL(
            '$GPGLL,4331.1611,N,00407.6114,E,120250.000,A,A*5E'
        );
        
        $this->if($this->invoke($this->frame)->readFrame())
            ->then
            ->string($this->frame->getMode())
                ->isEqualTo('A')
        ;
    }
}
