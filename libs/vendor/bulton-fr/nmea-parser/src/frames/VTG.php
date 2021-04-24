<?php

namespace BultonFr\NMEA\Frames;

/**
 * Define the parser system for VTG frame type
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 * @link http://www.gpsinformation.org/dale/nmea.htm#VTG
 * @link http://aprs.gids.nl/nmea/#vtg
 */
class VTG extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'VTG';

    /**
     * Format: $--VTG,x.x,T,x.x,M,x.x,N,x.x,K
     * {@inheritdoc}
     */
    protected $frameRegex = '/^'
        .'([A-Z]{2}[A-Z]{3}),' //Equipment and trame type
        .'([0-9\.]*),T,' //True track made good (degrees)
        .'([0-9\.]*),M,' //Magnetic track made good
        .'([0-9\.]*),N,' //Ground speed, knots
        .'([0-9\.]*),K' //Ground speed, Kilometers per hour
        .'(,(A|D|E|N|S)?)?' //Mode indicator (NMEA >= 2.3)
        .'$/m';
    
    /**
     * @var float $trueTrack True track made good (degrees)
     */
    protected $trueTrack;
    
    /**
     * @var float $magneticTrack Magnetic track made good
     */
    protected $magneticTrack;
    
    /**
     * @var float $speedKnots Ground speed, knots
     */
    protected $speedKnots;
    
    /**
     * @var float $speedKmH Ground speed, Kilometers per hour
     */
    protected $speedKmH;
    
    /**
     * @var string $mode Mode indicator (NMEA >= 2.3)
     * * A : autonomous
     * * D : differential
     * * E : Estimated
     * * N : not valid
     * * S : Simulator
     */
    protected $mode;
    
    /**
     * Getter to property trueTrack
     * 
     * @return float
     */
    public function getTrueTrack()
    {
        return $this->trueTrack;
    }

    /**
     * Getter to property magneticTrack
     * 
     * @return float
     */
    public function getMagneticTrack()
    {
        return $this->magneticTrack;
    }

    /**
     * Getter to property speedKnots
     * 
     * @return float
     */
    public function getSpeedKnots()
    {
        return $this->speedKnots;
    }

    /**
     * Getter to property speedKmH
     * 
     * @return float
     */
    public function getSpeedKmH()
    {
        return $this->speedKmH;
    }

    /**
     * Getter to property mode
     * 
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * {@inheritdoc}
     */
    protected function decodeFrame($msgParts)
    {
        $this->trueTrack     = (float) $msgParts[2];
        $this->magneticTrack = (float) $msgParts[3];
        $this->speedKnots    = (float) $msgParts[4];
        $this->speedKmH      = (float) $msgParts[5];
        
        if (isset($msgParts[7])) {
            $this->mode = (string) $msgParts[7];
        }
        
    }
}
