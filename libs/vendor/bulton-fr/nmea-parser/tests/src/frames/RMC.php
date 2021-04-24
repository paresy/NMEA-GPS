<?php

namespace BultonFr\NMEA\Frames\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class RMC extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\RMC $frame The RMC frame instance used by unit test
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
        $this->frame = new \BultonFr\NMEA\Frames\RMC(
            '$GPRMC,123519,A,4807.038,N,01131.000,E,022.4,084.4,230394,003.1,W*6A'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frames\RMC::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('RMC');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::decodeFrame method and call all getters
     * 
     * @return void
     */
    public function testDecodeFrameAndGetters()
    {
        $this->assert('Frames\RMC::decodeFrame()')
            ->variable($this->invoke($this->frame)->readFrame())
                ->isNull()
        ;
        
        $this->testGetUtcTime();
        $this->testGetStatus();
        $this->testGetLatitude();
        $this->testGetLatitudeDirection();
        $this->testGetLongitude();
        $this->testGetLongitudeDirection();
        $this->testGetSpeed();
        $this->testGetAngle();
        $this->testGetUtcDate();
        $this->testGetMagneticVariation();
        $this->testGetMagneticVariationDirection();
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getUtcTime
     * 
     * @return void
     */
    protected function testGetUtcTime()
    {
        $this->assert('Frames\RMC::getUtcTime()')
            ->object($utcTime = $this->frame->getUtcTime())
                ->isInstanceOf('\DateTime')
            ->string($utcTime->format('H'))
                ->isEqualTo('12')
            ->string($utcTime->format('i'))
                ->isEqualTo('35')
            ->string($utcTime->format('s'))
                ->isEqualTo('19')
            ->object($timezone = $utcTime->getTimeZone())
                ->isInstanceOf('\DateTimeZone')
            ->string($timezone->getName())
                ->isEqualTo('UTC')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getStatus
     * 
     * @return void
     */
    protected function testGetStatus()
    {
        $this->assert('Frames\RMC::getStatus()')
            ->string($this->frame->getStatus())
                ->isEqualTo('A')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getLatitude
     * 
     * @return void
     */
    protected function testGetLatitude()
    {
        $this->assert('Frames\RMC::getLatitude()')
            ->string($this->frame->getLatitude())
                ->isEqualTo('4807.038')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getLatitudeDirection
     * 
     * @return void
     */
    protected function testGetLatitudeDirection()
    {
        $this->assert('Frames\RMC::getLatitudeDirection()')
            ->string($this->frame->getLatitudeDirection())
                ->isEqualTo('N')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getLongitude
     * 
     * @return void
     */
    protected function testGetLongitude()
    {
        $this->assert('Frames\RMC::getLongitude()')
            ->string($this->frame->getLongitude())
                ->isEqualTo('01131.000')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getLongitudeDirection
     * 
     * @return void
     */
    protected function testGetLongitudeDirection()
    {
        $this->assert('Frames\RMC::getLongitudeDirection()')
            ->string($this->frame->getLongitudeDirection())
                ->isEqualTo('E')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getSpeed
     * 
     * @return void
     */
    protected function testGetSpeed()
    {
        $this->assert('Frames\RMC::getSpeed()')
            ->float($this->frame->getSpeed())
                ->isEqualTo(022.4)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getAngle
     * 
     * @return void
     */
    protected function testGetAngle()
    {
        $this->assert('Frames\RMC::getAngle()')
            ->float($this->frame->getAngle())
                ->isEqualTo(084.4)
        ;
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getUtcDate
     * 
     * @return void
     */
    protected function testGetUtcDate()
    {
        $this->assert('Frames\RMC::getUtcDate()')
            ->object($utcTime = $this->frame->getUtcDate())
                ->isInstanceOf('\DateTime')
            ->string($utcTime->format('d'))
                ->isEqualTo('23')
            ->string($utcTime->format('m'))
                ->isEqualTo('03')
            ->string($utcTime->format('y'))
                ->isEqualTo('94')
            ->object($timezone = $utcTime->getTimeZone())
                ->isInstanceOf('\DateTimeZone')
            ->string($timezone->getName())
                ->isEqualTo('UTC')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getMagneticVariation
     * 
     * @return void
     */
    protected function testGetMagneticVariation()
    {
        $this->assert('Frames\RMC::getMagneticVariation()')
            ->float($this->frame->getMagneticVariation())
                ->isEqualTo(003.1)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\RMC::getMagneticVariationDirection
     * 
     * @return void
     */
    protected function testGetMagneticVariationDirection()
    {
        $this->assert('Frames\RMC::getMagneticVariationDirection()')
            ->string($this->frame->getMagneticVariationDirection())
                ->isEqualTo('W')
        ;
    }
    
    public function testGetMode()
    {
        $this->assert('Frames\RMC::getMode()');
        
        $this->frame = new \BultonFr\NMEA\Frames\RMC(
            '$GPRMC,120250.000,A,4331.1611,N,00407.6114,E,4.17,324.43,170917,,,A*60'
        );
        
        $this->if($this->invoke($this->frame)->readFrame())
            ->then
            ->string($this->frame->getMode())
                ->isEqualTo('A')
        ;
    }
}
