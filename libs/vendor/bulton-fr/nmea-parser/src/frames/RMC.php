<?php

namespace BultonFr\NMEA\Frames;

use \DateTime;
use \DateTimeZone;

/**
 * Define the parser system for RMC frame type
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 * @link http://www.gpsinformation.org/dale/nmea.htm#RMC
 * @link http://aprs.gids.nl/nmea/#rmc
 */
class RMC extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'RMC';

    /**
     * Format : --RMC,hhmmss,A,llll.ll,a,yyyyy.yy,a,x.x,x.x,ddmmyy,x.x,a,m
     * {@inheritdoc}
     */
    protected $frameRegex = '/^'
        .'([A-Z]{2}[A-Z]{3}),' //Equipment and trame type
        .'(\d{6})(\.\d{2,3})?,' //Time (UTC)
        .'(A|V),' //Status A=active or V=Void
        .'([0-9\.]+),' //Latitude
        .'(N|S),' //N or S (North or South)
        .'([0-9\.]+),' //Longitude
        .'(E|W),' //E or W (East or West)
        .'([0-9\.]*),' //Speed over ground in knots
        .'([0-9\.]*),' //Track angle in degrees True
        .'(\d{6}),' //Date
        .'([0-9\.]*),' //Magnetic variation degrees
        //(Easterly var. subtracts from true course)
        .'(E|W)?' //E or W (East or West)
        .'(,(A|D|E|N|S)?)?' //Mode indicator (NMEA >= 2.3)
        .'$/m';

    /**
     * @var \DateTime $utcTime Time of the line (UTC)
     */
    protected $utcTime;
    
    /**
     * @var string $status Status A=active or V=Void
     */
    protected $status;

    /**
     * @var string $latitude Latitude
     */
    protected $latitude;

    /**
     * @var string $latitudeDirection N or S (North or South)
     */
    protected $latitudeDirection;

    /**
     * @var string $longitude Longitude
     */
    protected $longitude;

    /**
     * @var string $longitudeDirection E or W (East or West)
     */
    protected $longitudeDirection;
    
    /**
     * @var string $speed Speed over ground in knots
     */
    protected $speed;
    
    /**
     * @var string $angle Track angle in degrees True
     */
    protected $angle;
    
    /**
     * @var \DateTime $utcDate Date of the line (UTC)
     */
    protected $utcDate;
    
    /**
     * @var string $magneticVariation Magnetic variation degrees
     * (Easterly var. subtracts from true course)
     */
    protected $magneticVariation;
    
    /**
     * @var string $magneticVariationDirection E or W (East or West)
     */
    protected $magneticVariationDirection;
    
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
     * Getter to property utcTime
     * 
     * @return \DateTime
     */
    public function getUtcTime()
    {
        return $this->utcTime;
    }
    
    /**
     * Getter to property status
     * 
     * @return float
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Getter to property latitude
     * 
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Getter to property latitudeDirection
     * 
     * @return string
     */
    public function getLatitudeDirection()
    {
        return $this->latitudeDirection;
    }

    /**
     * Getter to property longitude
     * 
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Getter to property longitudeDirection
     * 
     * @return string
     */
    public function getLongitudeDirection()
    {
        return $this->longitudeDirection;
    }
    
    /**
     * Getter to property speed
     * 
     * @return string
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Getter to property angle
     * 
     * @return string
     */
    public function getAngle()
    {
        return $this->angle;
    }
    
    /**
     * Getter to property date
     * 
     * @return string
     */
    public function getUtcDate()
    {
        return $this->utcDate;
    }

    /**
     * Getter to property magneticVariation
     * 
     * @return string
     */
    public function getMagneticVariation()
    {
        return $this->magneticVariation;
    }

    /**
     * Getter to property magneticVariationDirection
     * 
     * @return string
     */
    public function getMagneticVariationDirection()
    {
        return $this->magneticVariationDirection;
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
        $utcTimeZone = new DateTimeZone('UTC');
        
        $this->utcTime = DateTime::createFromFormat(
            'His',
            $msgParts[2],
            $utcTimeZone
        );
        
        $this->status = (string) $msgParts[4];

        $this->latitude           = (string) $msgParts[5];
        $this->latitudeDirection  = (string) $msgParts[6];
        $this->longitude          = (string) $msgParts[7];
        $this->longitudeDirection = (string) $msgParts[8];

        $this->speed = (float) $msgParts[9];
        $this->angle = (float) $msgParts[10];
        
        $this->utcDate = DateTime::createFromFormat(
            'dmy',
            $msgParts[11],
            $utcTimeZone
        );
        $this->utcDate->setTime(0, 0, 0);
        
        $this->magneticVariation          = (float) $msgParts[12];
        $this->magneticVariationDirection = (string) $msgParts[13];
        
        if (isset($msgParts[15])) {
            $this->mode = (string) $msgParts[15];
        }
    }
}
