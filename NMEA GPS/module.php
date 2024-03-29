<?php

declare(strict_types=1);

include_once __DIR__ . '/../libs/vendor/autoload.php';

class NMEAGPS extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->RequireParent('{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}');

        if (!IPS_VariableProfileExists('GPS.Position')) {
            IPS_CreateVariableProfile('GPS.Position', VARIABLETYPE_FLOAT);
            IPS_SetVariableProfileValues('GPS.Position', 0, 360, 1);
            IPS_SetVariableProfileText('GPS.Position', '', '°');
            IPS_SetVariableProfileDigits('GPS.Position', 4);
        }

        if (!IPS_VariableProfileExists('GPS.Altitude')) {
            IPS_CreateVariableProfile('GPS.Altitude', VARIABLETYPE_FLOAT);
            IPS_SetVariableProfileText('GPS.Altitude', '', ' m');
            IPS_SetVariableProfileDigits('GPS.Altitude', 1);
        }

        if (!IPS_VariableProfileExists('GPS.Quality')) {
            IPS_CreateVariableProfile('GPS.Quality', VARIABLETYPE_INTEGER);
            IPS_SetVariableProfileValues('GPS.Quality', 0, 8, 0);
            IPS_SetVariableProfileAssociation('GPS.Quality', 0, 'No Fix', '', 0xFF0000);
            IPS_SetVariableProfileAssociation('GPS.Quality', 1, 'GPS Fix', '', 0x00FF00);
            IPS_SetVariableProfileAssociation('GPS.Quality', 2, 'Differential GPS Fix', '', -1);
            IPS_SetVariableProfileAssociation('GPS.Quality', 3, 'PPS Fix', '', -1);
            IPS_SetVariableProfileAssociation('GPS.Quality', 4, 'Real Time Kinematic', '', -1);
            IPS_SetVariableProfileAssociation('GPS.Quality', 5, 'Float RTK', '', -1);
            IPS_SetVariableProfileAssociation('GPS.Quality', 6, 'Estimated', '', -1);
            IPS_SetVariableProfileAssociation('GPS.Quality', 7, 'Manual Input Mode', '', -1);
            IPS_SetVariableProfileAssociation('GPS.Quality', 8, 'Simulation Mode', '', -1);
        }

        $this->RegisterVariableInteger('DateTime', $this->Translate('Date/Time'), '~UnixTimestamp', 0);
        $this->RegisterVariableFloat('Latitude', $this->Translate('Latitude'), 'GPS.Position', 1);
        $this->RegisterVariableFloat('Longitude', $this->Translate('Longitude'), 'GPS.Position', 2);
        $this->RegisterVariableFloat('Altitude', $this->Translate('Altitude'), 'GPS.Altitude', 3);
        $this->RegisterVariableFloat('Speed', $this->Translate('Speed'), '~WindSpeed.kmh', 4);
        $this->RegisterVariableInteger('NumberOfSatellites', $this->Translate('Number of Satellites'), '', 5);
        $this->RegisterVariableInteger('GPSQuality', $this->Translate('GPS Quality'), 'GPS.Quality', 6);
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        $buffer = utf8_decode($data->Buffer);
        $lines = explode("\r\n", $buffer);

        $parser = new BultonFr\NMEA\Parser();

        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }

            // RUTX is sending empty GGA packets, which are not really NMEA compliant. Filter them.
            if (strpos($line, "GPGGA,,,,,,0,,,,,,,,") !== false) {
                continue;
            }

            $this->SendDebug('GPS', $line, 0 /* Text */);
            $frame = $parser->readLine($line);
            switch ($frame->getFrameType()) {
                case 'GGA':
                    $this->SetValueWhenChanged('DateTime', $frame->getUtcTime()->getTimestamp());
                    $this->SetValueWhenChanged('Latitude', $this->GPSToDecimal($frame->getLatitude(), $frame->getLatitudeDirection()));
                    $this->SetValueWhenChanged('Longitude', $this->GPSToDecimal($frame->getLongitude(), $frame->getLongitudeDirection()));
                    $this->SetValueWhenChanged('Altitude', $frame->getAltitude());
                    $this->SetValueWhenChanged('NumberOfSatellites', $frame->getNbSatellites());
                    $this->SetValueWhenChanged('GPSQuality', $frame->getGpsQuality());
                    break;
                case 'VTG':
                    $this->SetValueWhenChanged('Speed', $frame->getSpeedKmH());
                    break;
            }
        }
    }

    private function GPSToDecimal($dms, $direction)
    {
        $split = explode('.', $dms);
        switch (strlen($split[0])) {
            case 4: // Latitude
                $d = substr($dms, 0, 2);
                $m = substr($dms, 2, 2);
                $s = '0.' . $split[1];
                break;
            case 5: // Longitude
                $d = substr($dms, 0, 3);
                $m = substr($dms, 3, 2);
                $s = '0.' . $split[1];
                break;
            default:
                throw new Exception('Invalid DMS format!');
        }

        $dec = $d + ($m / 60) + (($s * 60) / 3600);

        if (in_array($direction, ['S', 'W'])) {
            $dec = -1 * $dec;
        }

        return $dec;
    }

    private function SetValueWhenChanged($Ident, $Value)
    {
        if ($this->GetValue($Ident) != $Value) {
            $this->SetValue($Ident, $Value);
        }
    }
}
