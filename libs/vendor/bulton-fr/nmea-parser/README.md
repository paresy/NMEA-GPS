# NMEA parser

[![Build Status](https://travis-ci.org/bulton-fr/nmea-parser.svg?branch=master)](https://travis-ci.org/bulton-fr/nmea-parser) [![Code Coverage](https://scrutinizer-ci.com/g/bulton-fr/nmea-parser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bulton-fr/nmea-parser/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bulton-fr/nmea-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bulton-fr/nmea-parser/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/bulton-fr/nmea-parser/v/stable.svg)](https://packagist.org/packages/bulton-fr/nmea-parser) [![Latest Unstable Version](https://poser.pugx.org/bulton-fr/nmea-parser/v/unstable.svg)](https://packagist.org/packages/bulton-fr/nmea-parser) [![License](https://poser.pugx.org/bulton-fr/nmea-parser/license.svg)](https://packagist.org/packages/bulton-fr/nmea-parser)

Read a NMEA line, detect the frame type and parse the line to obtain all datas.

Supported frame type :

* GGA
* GLL
* GSA
* GSV
* RMC
* VTG

## Install it

Use composer

Add into `composer.json` file :

```json
{
    "require": {
        "bulton-fr/nmea-parser": "master",
    }
}
```

## Use it

```php
<?php

//Require composer autoload
require_once(__DIR__.'/vendor/autoload.php');

//Instanciate the parser
$parser = new BultonFr\NMEA\Parser;

//Déclare a line to parse
$line = '$GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E';

//Parse the line
$frame = $parser->readLine($line);
```

`$frame` contains all datas about the readed line. If you `var_dump($frame)` :
```
class BultonFr\NMEA\Frames\GGA#2 (19) {
  protected $frameType => string(3) "GGA"
  protected $frameRegex => string(175) "/^([A-Z]{2}[A-Z]{3}),(\d{6}\.\d{2,3}),([0-9\.]+),(N|S),([0-9\.]+),(E|W),(\d{0,1}),(\d{0,2}),([0-9\.]*),([0-9\.]*),([A-Z]{0,1}),([0-9\.-]*),([A-Z]{0,1}),([0-9\.]*),(\d{0,4})$/m"
  protected $utcTime => class DateTime#5 (3) {
    public $date => string(26) "2017-09-29 06:40:36.289000"
    public $timezone_type => int(3)
    public $timezone => string(3) "UTC"
  }
  protected $latitude => string(9) "4836.5375"
  protected $latitudeDirection => string(1) "N"
  protected $longitude => string(10) "00740.9373"
  protected $longitudeDirection => string(1) "E"
  protected $gpsQuality => int(1)
  protected $nbSatellites => int(4)
  protected $horizontalDilutionPrecision => double(3.2)
  protected $altitude => double(200.2)
  protected $altitudeUnit => string(1) "M"
  protected $geoidalSeparation => double(0)
  protected $geoidalSeparationUnit => string(0) ""
  protected $ageGpsData => double(0)
  protected $differentialRefStationId => int(0)
  protected $line => string(70) "$GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000*0E"
  protected $message => string(66) "GPGGA,064036.289,4836.5375,N,00740.9373,E,1,04,3.2,200.2,M,,,,0000"
  protected $checksum => string(2) "0E"
}
```

There is a getter for all properties, except for `frameRegex`, `message` and `checksum`.

## Add a new frame type

Add a new class into the namespace `BultonFr\NMEA\Frames`. Else, you need to extends `Parser` class and redefine the method `obtainFrameParser`.

The name of the class need to be the frame type name. Example `GGA` for frame type GGA. This class should extends the class `\BultonFr\NMEA\Frame`.

You should have properties `$frameType` and `$frameRegex`. And the method `decodeFrame` should be declared.

`$frameType` should contains the name of the frame type.

`$frameRegex` is the regex to use for parse the line.

`decodeFrame` is the method called after the parse of the line with the regex. The argument of this method is the third argument of `preg_match` function. So this argument contain all part of the message. Into this method, you can populate your properties with the line value.
