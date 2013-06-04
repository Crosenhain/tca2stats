SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stats`
--

-- --------------------------------------------------------

--
-- Table structure for table `harvest`
--

CREATE TABLE IF NOT EXISTS `harvest` (
  `service_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `online` int(11) NOT NULL,
  `max_players` int(11) NOT NULL,
  `players` int(11) NOT NULL,
  `cpu` double NOT NULL,
  `memory` bigint(20) NOT NULL,
  KEY `service_id` (`service_id`),
  KEY `datetime` (`datetime`),
  KEY `max_players` (`max_players`,`players`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Stats collected from tcadmin2 tc_game_service_live_stats tab';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
