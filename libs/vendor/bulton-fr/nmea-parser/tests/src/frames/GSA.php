<?php

namespace BultonFr\NMEA\Frames\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class GSA extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\GSA $frame The GSA frame instance used by unit test
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
        $this->frame = new \BultonFr\NMEA\Frames\GSA(
            '$GPGSA,A,3,04,05,,09,12,,,24,,,,,2.5,1.3,2.1*39'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frames\GSA::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('GSA');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::decodeFrame method and call all getters
     * 
     * @return void
     */
    public function testDecodeFrameAndGetters()
    {
        $this->assert('Frames\GSA::decodeFrame()')
            ->variable($this->invoke($this->frame)->readFrame())
                ->isNull()
        ;
        
        $this->testGetSelection();
        $this->testGetMode();
        $this->testGetSatellitesPNR();
        $this->testGetPdop();
        $this->testGetHdop();
        $this->testGetVdop();
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getSelection
     * 
     * @return void
     */
    protected function testGetSelection()
    {
        $this->assert('Frames\GSA::getSelection()')
            ->string($this->frame->getSelection())
                ->isEqualTo('A')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getMode
     * 
     * @return void
     */
    protected function testGetMode()
    {
        $this->assert('Frames\GSA::getMode()')
            ->integer($this->frame->getMode())
                ->isEqualTo(3)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getSatellitesPNR
     * 
     * @return void
     */
    protected function testGetSatellitesPNR()
    {
        $this->assert('Frames\GSA::getSatellitesPNR()')
            ->array($pnr = $this->frame->getSatellitesPNR())
                ->size
                    ->isEqualTo(12)
            ->integer($pnr[0])->isEqualTo(4)
            ->integer($pnr[1])->isEqualTo(5)
            ->integer($pnr[2])->isZero()
            ->integer($pnr[3])->isEqualTo(9)
            ->integer($pnr[4])->isEqualTo(12)
            ->integer($pnr[5])->isZero()
            ->integer($pnr[6])->isZero()
            ->integer($pnr[7])->isEqualTo(24)
            ->integer($pnr[8])->isZero()
            ->integer($pnr[9])->isZero()
            ->integer($pnr[10])->isZero()
            ->integer($pnr[11])->isZero()
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getPdop
     * 
     * @return void
     */
    protected function testGetPdop()
    {
        $this->assert('Frames\GSA::getPdop()')
            ->float($this->frame->getPdop())
                ->isEqualTo(2.5)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getHdop
     * 
     * @return void
     */
    protected function testGetHdop()
    {
        $this->assert('Frames\GSA::getHdop()')
            ->float($this->frame->getHdop())
                ->isEqualTo(1.3)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSA::getVdop
     * 
     * @return void
     */
    protected function testGetVdop()
    {
        $this->assert('Frames\GSA::getVdop()')
            ->float($this->frame->getVdop())
                ->isEqualTo(2.1)
        ;
    }
}
