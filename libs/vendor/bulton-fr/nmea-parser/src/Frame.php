<?php

namespace BultonFr\NMEA;

use \Exception;

/**
 * Use frame type parser infos to check a line and parse it.
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
abstract class Frame
{
    /**
     * @const ERR_OBTAIN_MSG_AND_CHECKSUM_FAILED Error code when there is an
     * error for obtaining the message and the checksum value of the line
     */
    const ERR_OBTAIN_MSG_AND_CHECKSUM_FAILED = 20101;
    
    /**
     * @const ERR_CHECKSUM_FAILED Error code when the calculated checksum and
     * the checksum of the message not corresponding
     */
    const ERR_CHECKSUM_FAILED = 20102;
    
    /**
     * @const ERR_FRAME_MSG_FORMAT Error code when the message can not be
     * cut with the regex => The message format is not correct.
     */
    const ERR_FRAME_MSG_FORMAT = 20103;
    
    /**
     * @var string $frameType The current frame type
     */
    protected $frameType;
    
    /**
     * @var string $frameRegex The regex to use for parse the message
     */
    protected $frameRegex;
    
    /**
     * @var string $line The line to parse
     */
    protected $line;
    
    /**
     * @var string $message The message to parse
     */
    protected $message;
    
    /**
     * @var string $checksum The checksum containing into the line
     */
    protected $checksum;

    /**
     * Constructor.
     * * Obtain the message and the checksum contained into the line
     * * Check if checksum corresponding
     * 
     * @param string $line The line to parse
     */
    public function __construct($line)
    {
        $this->line = $line;
        
        $this->obtainMessageAndChecksum();
        $this->checksum();
    }
    
    public function __toString()
    {
        $properties = get_class_vars(get_called_class());
        $notDisplay = [
            'frameRegex',
            'line',
            'message',
            'checksum'
        ];
        
        $returnedStr = '';
        foreach ($properties as $propName => $propValue) {
            if (in_array($propName, $notDisplay)) {
                continue;
            }
            
            $propCurrentValue = $this->{$propName};
            if (
                is_object($propCurrentValue) &&
                get_class($propCurrentValue) === '\DateTime'
            ) {
                $format = 'd/m/Y H:i:s.u';
                if ($propName === 'utcTime') {
                    $format = 'H:i:s.u';
                } elseif ($propName === 'utcDate') {
                    $format = 'd/m/Y';
                }
                
                $propCurrentValue = $propCurrentValue->format('d/m/Y H:i:s.u');
            } elseif (
                is_array($propCurrentValue) ||
                is_object($propCurrentValue)
            ) {
                $propCurrentValue = print_r($propCurrentValue, true);
            }
            
            $returnedStr .= $propName.' : '.$propCurrentValue."\n";
        }
        
        return $returnedStr;
    }
    
    /**
     * Take all message parts and populate attributes
     * 
     * @param string[] $msgParts All parts of the message. Cutted with the
     * regex corresponding to the line frame type.
     * 
     * @return void
     */
    abstract protected function decodeFrame($msgParts);
    
    /**
     * Getter to property frameType
     * 
     * @return string
     */
    public function getFrameType()
    {
        return $this->frameType;
    }
    
    /**
     * Getter to property line
     * 
     * @return string
     */
    public function getLine()
    {
        return $this->line;
    }
    
    /**
     * Obtain the message and the checksum contained into the line
     * 
     * @return void
     * 
     * @throws Exception If the checksum is not present into the line
     */
    protected function obtainMessageAndChecksum()
    {
        $matched = [];
        if (!preg_match('/^\$(.*)\*([A-Z0-9]{2})/', $this->line, $matched)) {
            throw new Exception(
                'The line is corrupted. The message and/or the checksum has not been found.',
                static::ERR_OBTAIN_MSG_AND_CHECKSUM_FAILED
            );
        }
        
        $this->message  = $matched[1];
        $this->checksum = $matched[2];
    }
    
    /**
     * Calculates the checksum of the message and comparares it with the
     * checksum contained into the message.
     * 
     * @throws Exception If the checksum does not match
     */
    protected function checksum()
    {
        $nbCharInMsg = strlen($this->message);
        $checksum    = 0;
        
        for ($readedChar = 0; $readedChar < $nbCharInMsg; $readedChar++) {
            $checksum ^= ord($this->message[$readedChar]);
        }
        
        if ($checksum < 16) {
            $checksum = '0'.dechex($checksum);
        } else {
            $checksum = dechex($checksum);
        }
        
        if (strtoupper($checksum) !== strtoupper($this->checksum)) {
            throw new Exception(
                'The line is corrupted. The checksum not corresponding.',
                static::ERR_CHECKSUM_FAILED
            );
        }
    }
    
    /**
     * Read the frame and use the regex of the format type to cut the message
     * into many parts
     * 
     * @return void
     * 
     * @throws Exception If the message not corresponding with the regex.
     */
    public function readFrame()
    {
        $matches = [];
        if (!preg_match($this->frameRegex, $this->message, $matches)) {
            throw new Exception(
                'The line is corrupted. It not corresponding to '.$this->frameType.' format',
                static::ERR_FRAME_MSG_FORMAT
            );
        }
        
        $this->decodeFrame($matches);
    }
}
