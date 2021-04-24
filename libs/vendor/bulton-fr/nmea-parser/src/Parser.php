<?php

namespace BultonFr\NMEA;

use \Exception;

/**
 * Parse a nmea line to determine the frame type of the line and create an
 * instance of the frame type parser.
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class Parser
{
    /**
     * @const ERR_FRAME_DETECT_FAILED Error code if the frame type can not be
     * detected.
     */
    const ERR_FRAME_DETECT_FAILED = 10101;
    
    /**
     * @const ERR_NO_PARSER_FOR_FRAME_TYPE Error code if there is not parser
     * for the readed line
     */
    const ERR_NO_PARSER_FOR_FRAME_TYPE = 10102;
    
    /**
     * Read a new line, find the frame type, create a new instance of the frame
     * type parser and return the parser instanciated after reading and
     * parsing the line.
     * 
     * @param string $line The line to parse
     * 
     * @return \BultonFr\NMEA\Frame The frame type parser instance
     */
    public function readLine($line)
    {
        $frameType = $this->detectFrameType($line);
        $frame     = $this->obtainFrameParser($line, $frameType);
        
        $frame->readFrame();
        return $frame;
    }
    
    /**
     * Detect the frame type from the line
     * 
     * @param string $line The line to read
     * 
     * @return string
     * 
     * @throws Exception If the line format not corresponding.
     */
    protected function detectFrameType($line)
    {
        $matches = [];
        if (!preg_match('/^\$([A-Z]{2})([A-Z]{3}),/', $line, $matches)) {
            throw new Exception(
                'The detection of the frame type has failed.',
                static::ERR_FRAME_DETECT_FAILED
            );
        }
        
        return $matches[2]; //Always exist, else preg_match failed.
    }
    
    /**
     * Instanciate the frame type class parser.
     * 
     * @param string $line The line to read
     * @param string $frameType The frame type
     * 
     * @return \BultonFr\NMEA\Frame
     * 
     * @throws Exception If no parse exist for this frame type
     */
    protected function obtainFrameParser($line, $frameType)
    {
        $frameClassName = '\BultonFr\NMEA\Frames\\'.$frameType;
        
        if (!class_exists($frameClassName)) {
            throw new Exception(
                'There is no class defined for frame type '.$frameType.'.',
                static::ERR_NO_PARSER_FOR_FRAME_TYPE
            );
        }
        
        return new $frameClassName($line);
    }
}
