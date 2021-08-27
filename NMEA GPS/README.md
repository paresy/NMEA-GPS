# NMEA GPS
Empfängt GPS Daten von einem NMEA kompatibelen Gerät

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Module, welches die GPS Daten über serieller Schnittstelle im NMEA Format empfängt. Dabei werden die Datenpakete GGA und VTG ausgewertet, um diverse Status-Variablen mit Werten zu befüllen.

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.5
- NMEA kompatibles GPS Gerät mit seriellem Anschluss

### 3. Software-Installation

* Über den Module Store das 'NMEA GPS'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen: https://github.com/paresy/NMEA-GPS

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'NMEA GPS'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Keine Konfiguration notwendig.

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

Name                  | Typ    
--------------------- | -------
Datum/Uhrzeit         | Integer 
Breitengrad           | Float
Längengrad            | Float
Höhe über n.N.        | Float
Geschwindkeit         | Float
Anzahl der Satelliten | Integer
GPS Qualität          | Integer

#### Profile

Name         | Typ
------------ | -------
GPS.Position | Float
GPS.Altitude | Float
GPS.Quality  | Integer

### 6. WebFront

Keine spezielle Funktion. Variablen werden im WebFront angezeigt.

### 7. PHP-Befehlsreferenz

Keine Funktionen vorhanden
