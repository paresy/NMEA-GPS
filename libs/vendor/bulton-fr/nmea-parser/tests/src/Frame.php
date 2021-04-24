<?php

namespace BultonFr\NMEA\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Frame
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class Frame extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Frames\Fakes\TUN $frame The frame instance used by unit test
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
        $this->frame = new \BultonFr\NMEA\Frames\Fakes\TUN(
            '$GPTUN,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E'
        );
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frame::getFrameType method
     * 
     * @return void
     */
    public function testGetFrameType()
    {
        $this->assert('Frame::getFrameType()')
            ->string($this->frame->getFrameType())
                ->isEqualTo('TUN');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frame::getLine method
     * 
     * @return void
     */
    public function testGetLine()
    {
        $this->assert('Frame::getLine()')
            ->string($this->frame->getLine())
                ->isEqualTo('$GPTUN,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E');
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frame::obtainMessageAndChecksum method
     * 
     * @return void
     */
    public function testObtainMessageAndChecksum()
    {
        $this->assert('Frame::obtainMessageAndChecksum() : Test without error')
            ->if($this->invoke($this->frame)->obtainMessageAndChecksum())
            ->then
            ->string($this->frame->message)
                ->isEqualTo('GPTUN,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000')
            ->string($this->frame->checksum)
                ->isEqualTo('0E')
        ;
        
        $this->assert('Frame::obtainMessageAndChecksum() : Test with error')
            ->if($this->frame->line = 'GS3,2,13,25,101,,05,34,,17,06,11D')
            ->then
            ->exception(function() {
                $this->invoke($this->frame)->obtainMessageAndChecksum();
            })
                ->hasCode(\BultonFr\NMEA\Frame::ERR_OBTAIN_MSG_AND_CHECKSUM_FAILED)
                ->hasMessage(
                    'The line is corrupted. The message and/or the checksum has not been found.'
                )
        ;
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frame::checksum method
     * 
     * @return void
     */
    public function testChecksum()
    {
        $this->assert('Frame::checksum : Test without error and with a checksum < 0x10')
            ->if($this->frame->message = 'GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000')
            ->and($this->frame->checksum = '0E')
            ->then
            ->variable($this->invoke($this->frame)->checksum())
                ->isNull()
        ;
        
        $this->assert('Frame::checksum : Test without error and with a checksum > 0x0f')
            ->if($this->frame->message = 'GPGGA,092750.000,5321.6802,N,00630.3372,W,1,8,1.03,61.7,M,55.2,M,,')
            ->and($this->frame->checksum = '76')
            ->then
            ->variable($this->invoke($this->frame)->checksum())
                ->isNull()
        ;
        
        $this->assert('Frame::checksum : Test with error')
            ->if($this->frame->message = 'GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0001')
            ->and($this->frame->checksum = '0E')
            ->then
            ->exception(function() {
                $this->invoke($this->frame)->checksum();
            })
                ->hasCode(\BultonFr\NMEA\Frame::ERR_CHECKSUM_FAILED)
                ->hasMessage('The line is corrupted. The checksum not corresponding.')
        ;
    }
    
    /**
     * Test method for \BultonFr\NMEA\Frame::readFrame method
     * 
     * @return void
     */
    public function testReadFrame()
    {
        $this->assert('Frame::readFrame without error')
            ->if($this->frame->message = 'GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0001')
            ->and($this->invoke($this->frame)->readFrame())
            ->then
            ->array($this->frame->lastMsgPart)
                //Will be better tested into frame type unit test
                ->isEqualTo([
                    'GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0001',
                    'GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0001'
                ])
        ;
        
        $this->assert('Frame::readFrame without error')
            ->if($this->frame->message = 'GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0001')
            ->and($this->frame->frameRegex = '/^UNIT_TEST$/')
            ->then
            ->exception(function() {
                $this->invoke($this->frame)->readFrame();
            })
                ->hasCode(\BultonFr\NMEA\Frame::ERR_FRAME_MSG_FORMAT)
                ->hasMessage(
                    'The line is corrupted. It not corresponding to TUN format'
                )
        ;
    }
}
