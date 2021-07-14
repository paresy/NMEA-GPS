# NMEA GPS Splitter
Beschreibung des Moduls.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Führt verschiedene I/O Möglichkeiten zusammen und gibt die Daten an die Geräte weiter.
* Derzeit gibt es Folgende Möglichkeiten
       * Serial Port
       * Webhook (Lokal oder Symcon Connect)

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.5

### 3. Software-Installation

* Über den Module Store das 'NMEA GPS'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'NMEA GPS Splitter'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Am Splitter kann gewält werden welchen Eingangskanal dieser untersützt.

Gateway Mode

Name   | Typ
------ | -------
Webhook | Es wird ein I/O Modul und ein Webhook erstellt was mit dem Splitter veknüpft wird.
Seriel Port | Es wird eine I/O zu einem Seriellen Port erstellt und mit dem Splitter verknüpft.


### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

- Keine

#### Profile

- Keine

### 6. WebFront

- Keine

### 7. PHP-Befehlsreferenz

`boolean GPSSP_BeispielFunktion(integer $InstanzID);`
Erklärung der Funktion.

Beispiel:
`GPSSP_BeispielFunktion(12345);`