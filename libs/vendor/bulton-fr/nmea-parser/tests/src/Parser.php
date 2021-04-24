<?php

namespace BultonFr\NMEA\tests\units;

use mageekguy\atoum;

/**
 * Unit test class for class \BultonFr\NMEA\Parser
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class Parser extends atoum\test
{
    /**
     * @var \BultonFr\NMEA\Parser $parser The parser instance used by unit test
     */
    protected $parser;
    
    /**
     * Called before each test method
     * 
     * @param string $methodName The name of the test method which be called
     * 
     * @return void
     */
    public function beforeTestMethod($methodName)
    {
        $this->parser = new \BultonFr\NMEA\Parser;
    }
    
    /**
     * Test method for \BultonFr\NMEA\Parser::detectFrameType method
     * 
     * @return void
     */
    public function testDetectFrameType()
    {
        $this->assert('Parser::detectFrameType : Test with not correct line')
            ->exception(function() {
                $this->invoke($this->parser)->detectFrameType('unit_test');
            })
                ->hasCode(\BultonFr\NMEA\Parser::ERR_FRAME_DETECT_FAILED)
                ->hasMessage('The detection of the frame type has failed.')
        ;
        
        $this->assert('Parser::detectFrameType : Test with a correct line')
            ->string(
                $this->invoke($this->parser)->detectFrameType(
                    '$GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E'
                )
            )
                ->isEqualTo('GGA')
        ;
    }
    
    /**
     * Test method for \BultonFr\NMEA\Parser::obtainFrameParser method
     * 
     * @return void
     */
    public function testObtainFrameParser()
    {
        $this->assert('Parser::obtainFrameParser : Test with not existing frame type')
            ->exception(function() {
                $this->invoke($this->parser)->obtainFrameParser(
                    '$GPTUN,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E',
                    'TUN'
                );
            })
                ->hasCode(\BultonFr\NMEA\Parser::ERR_NO_PARSER_FOR_FRAME_TYPE)
                ->hasMessage('There is no class defined for frame type TUN.')
        ;
        
        //Somes problems with xdebug (use $obj var, else segfault)
        //See https://github.com/atoum/atoum/issues/554
        $this->assert('Parser::obtainFrameParser : Test with existing frame type');
        $obj = $this->invoke($this->parser)->obtainFrameParser(
            '$GPTUN,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E',
            'Fakes\TUN'
        );
        $this->object($obj)
            ->isInstanceOf('\BultonFr\NMEA\Frames\Fakes\TUN')
        ;
    }
}
