-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Dez 2024 um 10:34
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `immobilien_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bilder`
--

CREATE TABLE `bilder` (
  `BildId` int(11) NOT NULL,
  `BildLink` varchar(100) DEFAULT NULL,
  `HauptBild` tinyint(1) DEFAULT 0,
  `WohnungId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `bilder`
--

INSERT INTO `bilder` (`BildId`, `BildLink`, `HauptBild`, `WohnungId`) VALUES
(1, '../img/Wohnung9/Build2.jpg', 1, 1),
(2, '../img/Wohnung9/Build3.jpg', 0, 1),
(3, '../img/Wohnung9/Build1.jpg', 0, 1),
(4, '../img/Wohnung9/Build4.jpg', 0, 1),
(5, '../img/Wohnung9/Build5.jpg', 0, 1),
(6, '../img/Wohnung10/Bild10_1.jpg', 1, 2),
(7, '../img/Wohnung10/Bild10_2.jpg', 0, 2),
(8, '../img/Wohnung10/Bild10_3.jpg', 0, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `favoriten`
--

CREATE TABLE `favoriten` (
  `NutzerId` int(11) NOT NULL,
  `WohnungId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `favoriten`
--

INSERT INTO `favoriten` (`NutzerId`, `WohnungId`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nutzer`
--

CREATE TABLE `nutzer` (
  `NutzerId` int(10) NOT NULL,
  `Vorname` varchar(50) NOT NULL,
  `Nachname` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Kennwort` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `nutzer`
--

INSERT INTO `nutzer` (`NutzerId`, `Vorname`, `Nachname`, `Email`, `Kennwort`) VALUES
(1, 'Tetiana', 'Luschina', 'alice@example.com', 'ezuwq'),
(2, 'Marina', 'Nastorovich', 'bob@example.com', 'wew89'),
(3, 'Andrey', 'Borodin', 'charlie@example.com', 'qqwqw'),
(6, 'Tetiana', 'Bogach', 'flora.nuta@gmail.com', '$2y$10$PBvRu5rZPlmbuhsqtjZ4C.Hg2G.KvU3AkXABtV4lLOkcqSiHtc8sy');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wohnungen`
--

CREATE TABLE `wohnungen` (
  `WohnungId` int(11) NOT NULL,
  `Stadt` varchar(20) DEFAULT NULL,
  `Postleitzahl` int(10) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Zimmerzahl` int(10) DEFAULT NULL,
  `Wohnflaeche` int(10) DEFAULT NULL,
  `Etage` int(10) DEFAULT NULL,
  `Kaltmiete` int(10) DEFAULT NULL,
  `Nebenkosten` int(10) DEFAULT NULL,
  `Kaution` int(10) DEFAULT NULL,
  `Titel` varchar(250) DEFAULT NULL,
  `Beschreibung` varchar(350) DEFAULT NULL,
  `Haustiere` tinyint(1) DEFAULT NULL,
  `Baujahr` int(4) DEFAULT NULL,
  `NutzerId` int(11) DEFAULT NULL,
  `Wohnungstype` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `wohnungen`
--

INSERT INTO `wohnungen` (`WohnungId`, `Stadt`, `Postleitzahl`, `Adresse`, `Zimmerzahl`, `Wohnflaeche`, `Etage`, `Kaltmiete`, `Nebenkosten`, `Kaution`, `Titel`, `Beschreibung`, `Haustiere`, `Baujahr`, `NutzerId`, `Wohnungstype`) VALUES
(1, 'Hannover', 30159, 'Kröpcke 16', 13, 57, 3, 550, 150, 1000, 'Eine neues Haus des deines Traums', 'Ein modernes, zweigeschossiges Haus im Wald, umgeben von Natur. Das Erdgeschoss bietet ein geräumiges Wohnzimmer mit offener Küche und großen Fenstern, die viel Licht einlassen. Im Obergeschoss befinden sich zwei Schlafzimmer mit Blick auf den Wald. Ein idyllischer Garten und eine Terrasse laden zum Entspannen ein.', 1, 1998, 1, 3),
(2, 'Hannover', 30159, 'Georgstrasse 26', 5, 123, 78, 1200, 350, 3000, 'Luxuriöses Penthouse im 78 Etage', 'Luxuriöses Penthouse mit atemberaubendem Blick über die Stadt. Die großzügige Wohnung bietet ein offenes Wohnzimmer, eine moderne Küche und große Fenster, die viel Tageslicht einlassen. Drei Schlafzimmer, jedes mit eigenem Bad, sowie eine private Dachterrasse mit Pool und Garten machen dieses Penthouse zu einem exklusiven Rückzugsort.', 1, 2024, 1, 5);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bilder`
--
ALTER TABLE `bilder`
  ADD PRIMARY KEY (`BildId`),
  ADD KEY `WohnungId` (`WohnungId`);

--
-- Indizes für die Tabelle `favoriten`
--
ALTER TABLE `favoriten`
  ADD PRIMARY KEY (`NutzerId`,`WohnungId`),
  ADD KEY `WohnungId` (`WohnungId`);

--
-- Indizes für die Tabelle `nutzer`
--
ALTER TABLE `nutzer`
  ADD PRIMARY KEY (`NutzerId`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indizes für die Tabelle `wohnungen`
--
ALTER TABLE `wohnungen`
  ADD PRIMARY KEY (`WohnungId`),
  ADD KEY `NutzerId` (`NutzerId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bilder`
--
ALTER TABLE `bilder`
  MODIFY `BildId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `nutzer`
--
ALTER TABLE `nutzer`
  MODIFY `NutzerId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `wohnungen`
--
ALTER TABLE `wohnungen`
  MODIFY `WohnungId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bilder`
--
ALTER TABLE `bilder`
  ADD CONSTRAINT `bilder_ibfk_1` FOREIGN KEY (`WohnungId`) REFERENCES `wohnungen` (`WohnungId`);

--
-- Constraints der Tabelle `favoriten`
--
ALTER TABLE `favoriten`
  ADD CONSTRAINT `favoriten_ibfk_1` FOREIGN KEY (`NutzerID`) REFERENCES `nutzer` (`NutzerId`),
  ADD CONSTRAINT `favoriten_ibfk_2` FOREIGN KEY (`WohnungId`) REFERENCES `wohnungen` (`WohnungId`);

--
-- Constraints der Tabelle `wohnungen`
--
ALTER TABLE `wohnungen`
  ADD CONSTRAINT `wohnungen_ibfk_1` FOREIGN KEY (`NutzerId`) REFERENCES `nutzer` (`NutzerId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
