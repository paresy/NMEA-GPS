<?php

namespace BultonFr\NMEA\Frames\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class GSV extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\GSV $frame The GSV frame instance used by unit test
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
        $this->frame = new \BultonFr\NMEA\Frames\GSV(
            '$GPGSV,2,1,08,01,40,083,46,02,17,308,41,12,07,344,39,14,22,228,45*75'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GSV::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frames\GSV::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('GSV');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GSV::decodeFrame method and call all getters
     * 
     * @return void
     */
    public function testDecodeFrameAndGetters()
    {
        $this->assert('Frames\GSV::decodeFrame()')
            ->variable($this->invoke($this->frame)->readFrame())
                ->isNull()
        ;
        
        $this->testGetSentenceTotalNumber();
        $this->testGetSentenceCurrentNumber();
        $this->testGetSatellitesNumber();
        $this->testGetSatellitesInfos();
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSV::getSentenceTotalNumber
     * 
     * @return void
     */
    protected function testGetSentenceTotalNumber()
    {
        $this->assert('Frames\GSV::getSentenceTotalNumber()')
            ->integer($this->frame->getSentenceTotalNumber())
                ->isEqualTo(2)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSV::getSentenceCurrentNumber
     * 
     * @return void
     */
    protected function testGetSentenceCurrentNumber()
    {
        $this->assert('Frames\GSV::getSentenceCurrentNumber()')
            ->integer($this->frame->getSentenceCurrentNumber())
                ->isEqualTo(1)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSV::getSatellitesNumber
     * 
     * @return void
     */
    protected function testGetSatellitesNumber()
    {
        $this->assert('Frames\GSV::getSatellitesNumber()')
            ->integer($this->frame->getSatellitesNumber())
                ->isEqualTo(8)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GSV::getSatellitesInfos
     * 
     * @return void
     */
    protected function testGetSatellitesInfos()
    {
        $this->assert('Frames\GSV::getSatellitesInfos()')
            ->array($svInfos = $this->frame->getSatellitesInfos())
                ->size
                    ->isEqualTo(4)
        ;
        
        $this->testGetSatellitesInfosObject($svInfos[0], 0, 1, 40, 83, 46);
        $this->testGetSatellitesInfosObject($svInfos[1], 1, 2, 17, 308, 41);
        $this->testGetSatellitesInfosObject($svInfos[2], 2, 12, 7, 344, 39);
        $this->testGetSatellitesInfosObject($svInfos[3], 3, 14, 22, 228, 45);
    }
    
    /**
     * Test method for an object of the array
     * returned by \BultonFr\NMEA\Frames\GSV::getSatellitesInfos
     * 
     * @return void
     */
    protected function testGetSatellitesInfosObject(
        $obj,
        $index,
        $expectedPnrNumber,
        $expectedElevation,
        $expectedAzimuth,
        $expectedSNR
    ) {
        $this->assert('Frames\GSV::getSatellitesInfos() for index '.$index)
            ->object($obj)
                ->isInstanceOf('\stdClass')
            ->boolean(property_exists($obj, 'prnNumber'))
                ->isTrue()
            ->integer($obj->prnNumber)
                ->isEqualTo($expectedPnrNumber)
            ->boolean(property_exists($obj, 'elevation'))
                ->isTrue()
            ->integer($obj->elevation)
                ->isEqualTo($expectedElevation)
            ->boolean(property_exists($obj, 'azimuth'))
                ->isTrue()
            ->integer($obj->azimuth)
                ->isEqualTo($expectedAzimuth)
            ->boolean(property_exists($obj, 'SNR'))
                ->isTrue()
            ->integer($obj->SNR)
                ->isEqualTo($expectedSNR)
        ;
    }
    
    public function testNotFourSatellite()
    {
        $this->assert('Frames\GSV::getSatellitesInfos() without four satellites');
        
        $this->frame = new \BultonFr\NMEA\Frames\GSV(
            '$GPGSV,2,1,08,01,40,083,46,02,17,308,41,12,07,344,*43'
        );
        
        $this
            ->if($this->invoke($this->frame)->readFrame())
            ->then
            ->array($svInfos = $this->frame->getSatellitesInfos())
                ->size
                    ->isEqualTo(4)
        ;
        
        $this->testGetSatellitesInfosObject($svInfos[0], 0, 1, 40, 83, 46);
        $this->testGetSatellitesInfosObject($svInfos[1], 1, 2, 17, 308, 41);
        $this->testGetSatellitesInfosObject($svInfos[2], 2, 12, 7, 344, 0);
        $this->testGetSatellitesInfosObject($svInfos[3], 3, 0, 0, 0, 0);
    }
}
