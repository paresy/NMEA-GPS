<?php

declare(strict_types=1);

include_once __DIR__ . '/../libs/vendor/autoload.php';

class NMEAGPS extends IPSModule
{
   
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->RequireParent('{B4397469-A727-2DC7-F7A4-16D1A399643C}');

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
        $this->RegisterVariableFloat('Latitude',  $this->Translate('Latitude'), 'GPS.Position', 1);
        $this->RegisterVariableFloat('Longitude',  $this->Translate('Longitude'), 'GPS.Position', 2);
        $this->RegisterVariableFloat('Altitude',  $this->Translate('Altitude'), 'GPS.Altitude', 3);
        $this->RegisterVariableFloat('Speed',  $this->Translate('Speed'), '~WindSpeed.kmh', 4);
        $this->RegisterVariableInteger('NumberOfSatellites',  $this->Translate('Number of Satellites'), '', 5);
        $this->RegisterVariableInteger('GPSQuality',  $this->Translate('GPS Quality'), 'GPS.Quality', 6);
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
 
    public function Send()
    {
        #$this->SendDataToParent(json_encode(['DataID' => '{906A476C-2501-DF19-9E5A-DF56A33C2B57}']));
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Device RECV', utf8_decode($data->Buffer));
        $buffer = utf8_decode($data->Buffer);
        $lines = explode("\r\n", $buffer);

        $parser = new BultonFr\NMEA\Parser();

        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }
            $matches = [];

            if (preg_match('/^\$DeviceID,/', $line, $matches)) {
                $this->RegisterVariableString('DeviceID', $this->Translate('Device ID'),'', 7);
                $Value = explode(',', $line);
                $this->SendDebug('GPS Device', 'Device ID: ' . $Value[1], 0);
                $this->SetValueWhenChanged('DeviceID', strval($Value[1]));
                continue;
            }

            if (preg_match('/^\$DeviceIMEI,/', $line, $matches)) {
                $this->RegisterVariableString('DeviceIMEI', $this->Translate('Device IMEI'),'', 8);
                $Value = explode(',', $line);
                $this->SendDebug('GPS Device', 'Device IMEI: ' . $Value[1], 0);
                $this->SetValueWhenChanged('DeviceIMEI', strval($Value[1]));
                continue;
            }

            if (preg_match('/^\$PTLTGSM,/', $line, $matches)) {
                $this->RegisterVariableString('GSMConnectionType', $this->Translate('GSM Connection Type'),'', 9);
                $this->RegisterVariableString('GSMConnectionStrengh', $this->Translate('GSM Connection Strengh'),'', 10);
                $Value = explode(',', $line);
                $this->SendDebug('GPS Device', 'GSM Connection Type: ' . $Value[1], 0);
                $this->SetValueWhenChanged('GSMConnectionType', strval($Value[1]));
                $this->SendDebug('GPS Device', 'GSM Connection Strengh: ' . $Value[2], 0);
                $this->SetValueWhenChanged('GSMConnectionStrengh', strval($Value[2]));
                continue;
            }

            $this->SendDebug('GPS Device', $line, 0 /* Text */);

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
