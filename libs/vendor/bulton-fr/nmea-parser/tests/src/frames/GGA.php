<?php

namespace BultonFr\NMEA\Frames\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class GGA extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\GGA $frame The GGA frame instance used by unit test
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
        $this->frame = new \BultonFr\NMEA\Frames\GGA(
            '$GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frames\GGA::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('GGA');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::decodeFrame method and call all getters
     * 
     * @return void
     */
    public function testDecodeFrameAndGetters()
    {
        $this->assert('Frames\GGA::decodeFrame()')
            ->variable($this->invoke($this->frame)->readFrame())
                ->isNull()
        ;
        
        $this->testGetUtcTime();
        $this->testGetLatitude();
        $this->testGetLatitudeDirection();
        $this->testGetLongitude();
        $this->testGetLongitudeDirection();
        $this->testGetGpsQuality();
        $this->testGetNbSatellites();
        $this->testGetHorizontalDilutionPrecision();
        $this->testGetAltitude();
        $this->testGetAltitudeUnit();
        $this->testGetGeoidalSeparation();
        $this->testGetGeoidalSeparationUnit();
        $this->testGetAgeGpsData();
        $this->testGetDifferentialRefStationId();
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getUtcTime
     * 
     * @return void
     */
    protected function testGetUtcTime()
    {
        $this->assert('Frames\GGA::getUtcTime()')
            ->object($utcTime = $this->frame->getUtcTime())
                ->isInstanceOf('\DateTime')
            ->string($utcTime->format('H'))
                ->isEqualTo('06')
            ->string($utcTime->format('i'))
                ->isEqualTo('40')
            ->string($utcTime->format('s'))
                ->isEqualTo('36')
            ->string($utcTime->format('u'))
                ->isEqualTo('289000')
            ->object($timezone = $utcTime->getTimeZone())
                ->isInstanceOf('\DateTimeZone')
            ->string($timezone->getName())
                ->isEqualTo('UTC')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getLatitude
     * 
     * @return void
     */
    protected function testGetLatitude()
    {
        $this->assert('Frames\GGA::getLatitude()')
            ->string($this->frame->getLatitude())
                ->isEqualTo('4836.5375')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getLatitudeDirection
     * 
     * @return void
     */
    protected function testGetLatitudeDirection()
    {
        $this->assert('Frames\GGA::getLatitudeDirection()')
            ->string($this->frame->getLatitudeDirection())
                ->isEqualTo('N')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getLongitude
     * 
     * @return void
     */
    protected function testGetLongitude()
    {
        $this->assert('Frames\GGA::getLongitude()')
            ->string($this->frame->getLongitude())
                ->isEqualTo('00740.9373')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getLongitudeDirection
     * 
     * @return void
     */
    protected function testGetLongitudeDirection()
    {
        $this->assert('Frames\GGA::getLongitudeDirection()')
            ->string($this->frame->getLongitudeDirection())
                ->isEqualTo('E')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getGpsQuality
     * 
     * @return void
     */
    protected function testGetGpsQuality()
    {
        $this->assert('Frames\GGA::getGpsQuality()')
            ->integer($this->frame->getGpsQuality())
                ->isEqualTo(1)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getNbSatellites
     * 
     * @return void
     */
    protected function testGetNbSatellites()
    {
        $this->assert('Frames\GGA::getNbSatellites()')
            ->integer($this->frame->getNbSatellites())
                ->isEqualTo(4)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getHorizontalDilutionPrecision
     * 
     * @return void
     */
    protected function testGetHorizontalDilutionPrecision()
    {
        $this->assert('Frames\GGA::getHorizontalDilutionPrecision()')
            ->float($this->frame->getHorizontalDilutionPrecision())
                ->isEqualTo(3.2)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getAltitude
     * 
     * @return void
     */
    protected function testGetAltitude()
    {
        $this->assert('Frames\GGA::getAltitude()')
            ->float($this->frame->getAltitude())
                ->isEqualTo(200.2)
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getAltitudeUnit
     * 
     * @return void
     */
    protected function testGetAltitudeUnit()
    {
        $this->assert('Frames\GGA::getAltitudeUnit()')
            ->string($this->frame->getAltitudeUnit())
                ->isEqualTo('M')
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getGeoidalSeparation
     * 
     * @return void
     */
    protected function testGetGeoidalSeparation()
    {
        $this->assert('Frames\GGA::getGeoidalSeparation()')
            ->float($this->frame->getGeoidalSeparation())
                ->isZero()
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getGeoidalSeparationUnit
     * 
     * @return void
     */
    protected function testGetGeoidalSeparationUnit()
    {
        $this->assert('Frames\GGA::getGeoidalSeparationUnit()')
            ->string($this->frame->getGeoidalSeparationUnit())
                ->isEmpty()
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getAgeGpsData
     * 
     * @return void
     */
    protected function testGetAgeGpsData()
    {
        $this->assert('Frames\GGA::getAgeGpsData()')
            ->float($this->frame->getAgeGpsData())
                ->isZero()
        ;
    }

    /**
     * Test method for \BultonFr\NMEA\Frames\GGA::getDifferentialRefStationId
     * 
     * @return void
     */
    protected function testGetDifferentialRefStationId()
    {
        $this->assert('Frames\GGA::getDifferentialRefStationId()')
            ->integer($this->frame->getDifferentialRefStationId())
                ->isZero()
        ;
    }
}
