<?php

declare(strict_types=1);
	class NMEAGPSSplitter extends IPSModule
	{
		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("GatewayMode",0);


			switch($this->ReadPropertyInteger("GatewayMode")) {
				case 0: //Webhook bei Modus 0 erstellen
					$this->ForceParent('{4613423B-9DD3-7908-3279-B2C8C1F37325}');
					break;
				case 1: //SerialPort bei Modus 1 erstellen
					$this->ForceParent('{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}');
					break;
			}

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

			// Je nach Konfiguration unterschiedliche I/O Instanzen erstellen
			switch($this->ReadPropertyInteger("GatewayMode")) {
				case 0: //Webhook bei Modus 0 erstellen
					$this->ForceParent('{4613423B-9DD3-7908-3279-B2C8C1F37325}');
					break;
				case 1: //SerialPort bei Modus 1 erstellen
					$this->ForceParent('{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}');
					break;
			}

		}

		public function ForwardData($JSONString)
		{
			$data = json_decode($JSONString);
			#IPS_LogMessage('Splitter FRWD', utf8_decode($data->Buffer));

			$this->SendDataToParent(json_encode(['DataID' => '{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}', 'Buffer' => $data->Buffer]));
			$this->SendDebug('Splitter FRWD', 'JSONString: ' . $JSONString, 0);
			return 'String data for device instance!';
		}

		public function ReceiveData($JSONString)
		{
			$data = json_decode($JSONString);
			$this->SendDebug('Splitter RECV', 'JSONString: ' . $JSONString, 0);
			$this->Send($data->Buffer);
		}
		public function Send(string $Text)
		{
			$this->SendDataToChildren(json_encode(['DataID' => '{565C8B85-ABA6-C334-6D4B-4B1643CAD2E9}', 'Buffer' => $Text]));
		}
	}