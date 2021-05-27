<?php

namespace BultonFr\NMEA\Frames;

use \DateTime;
use \DateTimeZone;

/**
 * Define the parser system for GGA frame type
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 * @link http://www.gpsinformation.org/dale/nmea.htm#GGA
 */
class GGA extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'GGA';

    /**
     * Format : --GGA,hhmmss.ss,llll.ll,a,yyyyy.yy,a,x,xx,x.x,x.x,M,x.x,M,x.x,xxxx
     * {@inheritdoc}
     */
    protected $frameRegex = '/^'
        .'([A-Z]{2}[A-Z]{3}),' //Equipment and trame type
        .'(\d{6}\.\d{2,3}),' //Time (UTC)
        .'([0-9\.]+),' //Latitude
        .'(N|S),' //N or S (North or South)
        .'([0-9\.]+),' //Longitude
        .'(E|W),' //E or W (East or West)
        .'(\d{0,1}),' //GPS Quality Indicator
        .'(\d{0,2}),' //Number of satellites in view, 00 - 12
        .'([0-9\.-]*),' //Horizontal Dilution of position
        .'([0-9\.-]*),' //Antenna Altitude above/below mean-sea-level (geoid)
        .'([A-Z]{0,1}),' //Units of antenna altitude, meters
        .'([0-9\.-]*),' //Geoidal separation, the difference between the WGS-84
        //earth ellipsoid and mean-sea-level (geoid), "-" means mean-sea-level below ellipsoid
        .'([A-Z]{0,1}),' //Units of geoidal separation, meters
        .'([0-9\.-]*),' //Age of differential GPS data, time in seconds since last SC104
        //type 1 or 9 update, null field when DGPS is not used
        .'(\d{0,4})' //DGPS station ID, 0000-1023
        .'$/m';

    /**
     * @var \DateTime $utcTime Time of the line (UTC)
     */
    protected $utcTime;

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
     * @var int $gpsQuality GPS Quality Indicator
     * * 0 - fix not available,
     * * 1 - GPS fix (SPS),
     * * 2 - Differential GPS fix (DGPS)
     * * 3 - PPS fix
     * * 4 - Real Time Kinematic
     * * 5 - Float RTK
     * * 6 - estimated (dead reckoning) (2.3 feature)
     * * 7 - Manual input mode
     * * 8 - Simulation mode
     */
    protected $gpsQuality;

    /**
     * @var int $nbSatellites Number of satellites in view, 0 to 12
     */
    protected $nbSatellites;

    /**
     * @var float $horizontalDilutionPrecision Horizontal Dilution of precision
     */
    protected $horizontalDilutionPrecision;

    /**
     * @var float $altitude Antenna Altitude above/below mean-sea-level (geoid)
     */
    protected $altitude;

    /**
     * @var string $altitudeUnit Units of antenna altitude, meters
     */
    protected $altitudeUnit;

    /**
     * @var float $geoidalSeparation Geoidal separation, the difference
     * between the WGS-84 earth ellipsoid and mean-sea-level (geoid);
     * "-" means mean-sea-level below ellipsoid
     */
    protected $geoidalSeparation;

    /**
     * @var string $geoidalSeparationUnit Units of geoidal separation, meters
     */
    protected $geoidalSeparationUnit;

    /**
     * @var float $ageGpsData Age of differential GPS data, time in seconds
     * since last SC104. Type 1 or 9 update, null field when DGPS is not used
     */
    protected $ageGpsData;

    /**
     * @var int $differentialRefStationId Differential reference station ID,
     * 0 to 1023
     */
    protected $differentialRefStationId;

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
     * Getter to property gpsQuality
     * 
     * @return int
     */
    public function getGpsQuality()
    {
        return $this->gpsQuality;
    }

    /**
     * Getter to property nbSatellites
     * 
     * @return int
     */
    public function getNbSatellites()
    {
        return $this->nbSatellites;
    }

    /**
     * Getter to property horizontalDilutionPrecision
     * 
     * @return float
     */
    public function getHorizontalDilutionPrecision()
    {
        return $this->horizontalDilutionPrecision;
    }

    /**
     * Getter to property altitude
     * 
     * @return float
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Getter to property altitudeUnit
     * 
     * @return string
     */
    public function getAltitudeUnit()
    {
        return $this->altitudeUnit;
    }

    /**
     * Getter to property geoidalSeparation
     * 
     * @return float
     */
    public function getGeoidalSeparation()
    {
        return $this->geoidalSeparation;
    }

    /**
     * Getter to property geoidalSeparationUnit
     * 
     * @return string
     */
    public function getGeoidalSeparationUnit()
    {
        return $this->geoidalSeparationUnit;
    }

    /**
     * Getter to property ageGpsData
     * 
     * @return float
     */
    public function getAgeGpsData()
    {
        return $this->ageGpsData;
    }

    /**
     * Getter to property differentialRefStationId
     * 
     * @return int
     */
    public function getDifferentialRefStationId()
    {
        return $this->differentialRefStationId;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function decodeFrame($msgParts)
    {
        $this->utcTime = DateTime::createFromFormat(
            'His.u',
            $msgParts[2],
            new DateTimeZone('UTC')
        );

        $this->latitude           = (string) $msgParts[3];
        $this->latitudeDirection  = (string) $msgParts[4];
        $this->longitude          = (string) $msgParts[5];
        $this->longitudeDirection = (string) $msgParts[6];

        $this->gpsQuality                  = (int) $msgParts[7];
        $this->nbSatellites                = (int) $msgParts[8];
        $this->horizontalDilutionPrecision = (float) $msgParts[9];

        $this->altitude     = (float) $msgParts[10];
        $this->altitudeUnit = (string) $msgParts[11];

        $this->geoidalSeparation     = (float) $msgParts[12];
        $this->geoidalSeparationUnit = (string) $msgParts[13];

        $this->ageGpsData               = (float) $msgParts[14];
        $this->differentialRefStationId = (int) $msgParts[15];
    }
}
