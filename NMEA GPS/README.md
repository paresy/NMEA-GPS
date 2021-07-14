# NMEA GPS
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

*

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.5

### 3. Software-Installation

* Über den Module Store das 'NMEA GPS'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'NMEA GPS'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
         |
         |

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

- Keine

#### Profile

Name   | Typ
------ | -------
GPS.Position | FLOAT
GPS.Altitude | FLOAT
GPS.Quality | INTEGER

### 6. WebFront

#### Statusvariablen

Anzeige der GPS Informationen des Gerätes. Sollte das Gerät GSM Daten im NMEA Format übertragen werden diese ebenfalls angezeigt.

### 7. PHP-Befehlsreferenz

`boolean GPS_BeispielFunktion(integer $InstanzID);`
Erklärung der Funktion.

Beispiel:
`GPS_BeispielFunktion(12345);`