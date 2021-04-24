<?php

namespace BultonFr\NMEA\Frames;

/**
 * Define the parser system for GSA frame type
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 * @link http://www.gpsinformation.org/dale/nmea.htm#GSA
 * @link http://aprs.gids.nl/nmea/#gsa
 */
class GSA extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'GSA';

    /**
     * {@inheritdoc}
     */
    protected $frameRegex = '/^'
        .'([A-Z]{2}[A-Z]{3}),' //Equipment and trame type
        .'(A|M),' // selection type of 2D or 3D (manual or automatic)
        .'(1|2|3),' // Mode (2d / 3d)
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'(\d*),' //PRNs of satellites used for fix
        .'([0-9\.]+),' //PDOP (dilution of precision)
        .'([0-9\.]+),' //Horizontal dilution of precision (HDOP)
        .'([0-9\.]+)' //Vertical dilution of precision (VDOP)
        .'$/m';

    /**
     * @var float $selection Auto selection of 2D or 3D fix
     * * A : Automatic, 3D/2D
     * * M : Manual, forced to operate in 2D or 3D
     */
    protected $selection;

    /**
     * @var string $mode Mode type
     * * 1 : no fix
     * * 2 : 2d fix
     * * 3 : 3d fix
     */
    protected $mode;
    
    /**
     * @var array $satellitesPNR PRNs of satellites used for fix
     */
    protected $satellitesPNR;
    
    /**
     * @var float $pdop PDOP (dilution of precision)
     */
    protected $pdop;
    
    /**
     * @var float $hdop Horizontal dilution of precision (HDOP)
     */
    protected $hdop;
    
    /**
     * @var float $vdop Vertical dilution of precision (VDOP)
     */
    protected $vdop;
    
    /**
     * Getter to property selection
     * 
     * @return string
     */
    public function getSelection()
    {
        return $this->selection;
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
     * Getter to property satellitesPNR
     * 
     * @return string
     */
    public function getSatellitesPNR()
    {
        return $this->satellitesPNR;
    }

    /**
     * Getter to property pdop
     * 
     * @return string
     */
    public function getPdop()
    {
        return $this->pdop;
    }

    /**
     * Getter to property hdop
     * 
     * @return string
     */
    public function getHdop()
    {
        return $this->hdop;
    }

    /**
     * Getter to property vdop
     * 
     * @return string
     */
    public function getVdop()
    {
        return $this->vdop;
    }

    /**
     * {@inheritdoc}
     */
    protected function decodeFrame($msgParts)
    {
        $this->selection = (string) $msgParts[2];
        $this->mode      = (int) $msgParts[3];
        
        for ($msgIndex = 4; $msgIndex <= 15; $msgIndex++) {
            $this->satellitesPNR[] = (int) $msgParts[$msgIndex];
        }
        
        $this->pdop = (float) $msgParts[16];
        $this->hdop = (float) $msgParts[17];
        $this->vdop = (float) $msgParts[18];
    }
}
