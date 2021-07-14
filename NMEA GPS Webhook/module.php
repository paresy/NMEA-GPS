<?php

declare(strict_types=1);
include_once __DIR__ . '/../libs/WebHookModule.php';

	class NMEAGPSWebhook extends WebHookModule
	{

		public function __construct($InstanceID)
		{
			parent::__construct($InstanceID, 'NMEAGPS/' . $InstanceID);
		}

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			$this->RegisterAttributeString("WebHookTransformtOutput","");
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

		public function ForwardData($JSONString)
		{
			$data = json_decode($JSONString);
			IPS_LogMessage('IO FRWD', utf8_decode($data->Buffer));
		}

		public function Send(string $Text)
		{
			$this->SendDataToChildren(json_encode(['DataID' => '{0E17898B-555A-9908-FE60-3D7BF2B42631}', 'Buffer' => $Text]));
		}

	    protected function ProcessHookData()
		{

			header("Access-Control-Allow-Origin:*");
			header('Content-Type: text/plain; charset=ASCII');

			if (array_key_exists("serial_num",$_GET))
			{
				$WebHookTransformtOutput = $this->ReadAttributeString("WebHookTransformtOutput");
				$DeviceID = $_GET['serial_num'];
				$this->SendDebug('NMEAGPSWebhook', 'Device ID: ' . $DeviceID, 0);
				$WebHookTransformtOutput = $WebHookTransformtOutput . '$DeviceID,' . $DeviceID . "\r\n";
				$this->WriteAttributeString("WebHookTransformtOutput",$WebHookTransformtOutput);
			}

			if (array_key_exists("imei", $_GET)) 
			{
				$WebHookTransformtOutput = $this->ReadAttributeString("WebHookTransformtOutput");
				$DeviceIMEI = $_GET['imei'];
				$this->SendDebug('NMEAGPSWebhook', 'Device IMEI: ' . $DeviceIMEI, 0);
				$WebHookTransformtOutput = $WebHookTransformtOutput . '$DeviceIMEI,' . $DeviceIMEI . "\r\n";
				$this->WriteAttributeString("WebHookTransformtOutput",$WebHookTransformtOutput);
			}

			$WebHookTransformtOutput = $this->ReadAttributeString("WebHookTransformtOutput");
			$NMEARAWData = file_get_contents("php://input");
			$lines = preg_split('/\n|\r\n?\s*/', $NMEARAWData);
			foreach ($lines as $line) {
			if (!$line) {
					continue;
				}
				$this->SendDebug('NMEAGPSWebhook', 'NMEA Code: ' . $line, 0 /* Text */);
				$WebHookTransformtOutput = $WebHookTransformtOutput . $line . "\r\n";
			}

			$this->Send(strval($WebHookTransformtOutput));
			$this->WriteAttributeString("WebHookTransformtOutput","");
		}

		public function FindHook()
		{
			$WebHook  = '/hook/NMEAGPS/' . $this->InstanceID;
			$ids = IPS_GetInstanceListByModuleID('{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}');
			if (count($ids) > 0) {
				$hooks = json_decode(IPS_GetProperty($ids[0], 'Hooks'), true);
				$found = false;
				foreach ($hooks as $index => $hook) {
					if ($hook['Hook'] == $WebHook) {
						if ($hook['TargetID'] == $this->InstanceID) {
							$WebHookLabel = 'Die Webhook URL lautet: http://<Servername>:<Port>' . $WebHook ;
							return $WebHookLabel;
						}
						$found = true;
					}
				}
				if (!$found) {
					$WebHookLabel = 'Keine passende Webhook URL gefunden I/O prüfen.';
				}
			}
		}

		public function GetConfigurationForm()
		{
			$HookURL = $this->FindHook();
			$jsonForm = json_decode(file_get_contents(__DIR__ . '/form.json'), true);
	
			$jsonForm['actions'][0]['caption'] = $HookURL;
	
			return json_encode($jsonForm);
		}

	}