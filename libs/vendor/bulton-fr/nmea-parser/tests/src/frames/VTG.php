<?php

namespace BultonFr\NMEA\Frames\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class VTG extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\VTG $frame The VTG frame instance used by unit test
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
        $this->frame = new \BultonFr\NMEA\Frames\VTG(
            '$GPVTG,054.7,T,034.4,M,005.5,N,010.2,K*48'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\VTG::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frames\VTG::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('VTG');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\VTG::decodeFrame method and call all getters
     * 
     * @return void
     */
    public function testDecodeFrameAndGetters()
    {
        $this->assert('Frames\VTG::decodeFrame()')
            ->variable($this->invoke($this->frame)->readFrame())
                ->isNull()
        ;
        
        $this->testGetTrueTrack();
        $this->testGetMagneticTrack();
        $this->testGetSpeedKnots();
        $this->testGetSpeedKmH();
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\VTG::getTrueTrack
     * 
     * @return void
     */
    protected function testGetTrueTrack()
    {
        $this->assert('Frames\VTG::getTrueTrack()')
            ->float($this->frame->getTrueTrack())
                ->isEqualTo(54.7)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\VTG::getMagneticTrack
     * 
     * @return void
     */
    protected function testGetMagneticTrack()
    {
        $this->assert('Frames\VTG::getMagneticTrack()')
            ->float($this->frame->getMagneticTrack())
                ->isEqualTo(34.4)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\VTG::getSpeedKnots
     * 
     * @return void
     */
    protected function testGetSpeedKnots()
    {
        $this->assert('Frames\VTG::getSpeedKnots()')
            ->float($this->frame->getSpeedKnots())
                ->isEqualTo(5.5)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\VTG::getSpeedKmH
     * 
     * @return void
     */
    protected function testGetSpeedKmH()
    {
        $this->assert('Frames\VTG::getSpeedKmH()')
            ->float($this->frame->getSpeedKmH())
                ->isEqualTo(10.2)
        ;
    }
    
    public function testGetMode()
    {
        $this->assert('Frames\RMC::getMode()');
        
        $this->frame = new \BultonFr\NMEA\Frames\VTG(
            '$GPVTG,324.24,T,,M,4.23,N,7.8,K,A*04'
        );
        
        $this->if($this->invoke($this->frame)->readFrame())
            ->then
            ->string($this->frame->getMode())
                ->isEqualTo('A')
        ;
    }
}
