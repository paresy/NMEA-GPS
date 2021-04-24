<?php

namespace BultonFr\NMEA\Frames;

/**
 * Define the parser system for GSV frame type
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 * @link http://www.gpsinformation.org/dale/nmea.htm#GSV
 * @link http://aprs.gids.nl/nmea/#gsv
 */
class GSV extends \BultonFr\NMEA\Frame
{
    /**
     * {@inheritdoc}
     */
    protected $frameType = 'GSV';

    /**
     * {@inheritdoc}
     */
    protected $frameRegex = '/^'
        .'([A-Z]{2}[A-Z]{3}),' //Equipment and trame type
        .'(\d*),' //Number of sentences for full data
        .'(\d*),' //Sentence number
        .'(\d*)' //Number of satellites in view
        .'(,(\d*),(\d*),(\d*),(\d*)' //Infos about first satellite
        .'(,(\d*),(\d*),(\d*),(\d*)' //Infos about second satellite
        .'(,(\d*),(\d*),(\d*),(\d*)' //Infos about trird satellite
        .'(,(\d*),(\d*),(\d*),(\d*)' //Infos about fourth satellite
        .')?)?)?)?'
        .'$/m';
    
    /**
     * @var int $sentenceTotalNumber Number of sentences for full data
     */
    protected $sentenceTotalNumber;
    
    /**
     * @var int $sentenceCurrentNumber Sentence number
     */
    protected $sentenceCurrentNumber;
    
    /**
     * @var int $satellitesNumber Number of satellites in view
     */
    protected $satellitesNumber;

    /**
     * @var \stdClass[] $satellitesInfos Infos about satellites
     * Array of object; Object contains properties :
     * * prnNumber
     * * elevation in degrees. max 90
     * * azimuth, degrees from tue north. 0 to 359
     * * SNR. 0 to 99dB
     */
    protected $satellitesInfos;
    
    /**
     * Getter to property sentenceTotalNumber
     * 
     * @return int
     */
    public function getSentenceTotalNumber()
    {
        return $this->sentenceTotalNumber;
    }

    /**
     * Getter to property sentenceCurrentNumber
     * 
     * @return int
     */
    public function getSentenceCurrentNumber()
    {
        return $this->sentenceCurrentNumber;
    }

    /**
     * Getter to property satellitesNumber
     * 
     * @return int
     */
    public function getSatellitesNumber()
    {
        return $this->satellitesNumber;
    }

    /**
     * Getter to property satelliteInfos
     * 
     * @return \stdClass[]
     */
    public function getSatellitesInfos()
    {
        return $this->satellitesInfos;
    }

    /**
     * {@inheritdoc}
     */
    protected function decodeFrame($msgParts)
    {
        $this->sentenceTotalNumber   = (int) $msgParts[2];
        $this->sentenceCurrentNumber = (int) $msgParts[3];
        $this->satellitesNumber      = (int) $msgParts[4];
        
        for ($svIndex = 0; $svIndex <= 3; $svIndex++) {
            $this->satellitesInfos[$svIndex] = (object) [
                'prnNumber' => 0,
                'elevation' => 0,
                'azimuth'   => 0,
                'SNR'       => 0,
            ];
            
            $indexPos = 5 * $svIndex;
            if (!isset($msgParts[(6 + $indexPos)])) {
                continue;
            }
            
            $svInfos            = &$this->satellitesInfos[$svIndex];
            $svInfos->prnNumber = (int) $msgParts[(6 + $indexPos)];
            $svInfos->elevation = (int) $msgParts[(7 + $indexPos)];
            $svInfos->azimuth   = (int) $msgParts[(8 + $indexPos)];
            $svInfos->SNR       = (int) $msgParts[(9 + $indexPos)];
        }
    }
}
