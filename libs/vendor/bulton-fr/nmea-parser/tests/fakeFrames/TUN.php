<?php

namespace BultonFr\NMEA\Frames\Fakes;

/**
 * Fake frame type for unit test
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class TUN extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'TUN'; //TUN for Test UNit

    /**
     * {@inheritdoc}
     */
    protected $frameRegex = '/^(.*)$/';
    
    /**
     * @var null|array $lastMsgPart The last parameter passed to decodeFrame()
     */
    protected $lastMsgPart;
    
    /**
     * Constructor without call to child method.
     * Only save the line into the property line.
     * 
     * {@inheritdoc}
     */
    public function __construct($line)
    {
        $this->line = $line;
        //Not call parent !
    }
    
    /**
     * Magic getter
     * 
     * @param string $name The name of the property to get
     * 
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }
    
    /**
     * Magic setter
     * 
     * @param string $name The name of the property to set
     * @param string $value The new value for the property
     * 
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function decodeFrame($msgParts)
    {
        $this->lastMsgPart = $msgParts;
    }
}
