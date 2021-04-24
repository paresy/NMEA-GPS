<?php

namespace BultonFr\NMEA\Frames;

use \DateTime;
use \DateTimeZone;

/**
 * Define the parser system for GLL frame type
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 * @link http://www.gpsinformation.org/dale/nmea.htm#GLL
 * @link http://aprs.gids.nl/nmea/#gll
 */
class GLL extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'GLL';

    /**
     * Format : ----GLL,lll.ll,a,yyyyy.yy,a,hhmmss.ss,A
     * {@inheritdoc}
     */
    protected $frameRegex = '/^'
        .'([A-Z]{2}[A-Z]{3}),' //Equipment and trame type
        .'([0-9\.]+),' //Latitude
        .'(N|S),' //N or S (North or South)
        .'([0-9\.]+),' //Longitude
        .'(E|W),' //E or W (East or West)
        .'(\d{6})(\.\d{2,3})?,' //Time (UTC)
        .'(A|V)' //Status A=active or V=Void
        .'(,(A|D|E|N|S)?)?' //Mode indicator (NMEA >= 2.3)
        .'$/m';

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
     * @var \DateTime $utcTime Time of the line (UTC)
     */
    protected $utcTime;
    
    /**
     * @var string $status Status A=active or V=Void
     */
    protected $status;
    
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
        $this->latitude           = (string) $msgParts[2];
        $this->latitudeDirection  = (string) $msgParts[3];
        $this->longitude          = (string) $msgParts[4];
        $this->longitudeDirection = (string) $msgParts[5];
        
        $this->utcTime = DateTime::createFromFormat(
            'His',
            $msgParts[6],
            new DateTimeZone('UTC')
        );
        
        $this->status = (string) $msgParts[8];
        
        if (isset($msgParts[10])) {
            $this->mode = (string) $msgParts[10];
        }
    }
}
