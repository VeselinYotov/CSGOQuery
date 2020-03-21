<?php
namespace Crawler;

// Set error report to 0
error_reporting(0);

// Include CSGO engine class
use Crawler\CSGO;

// If class's exists include them
spl_autoload_register(function ($class) {
	$class = 'lib/'.str_replace('\\', '/', substr($class, 7)).'.class.php';
	if (file_exists($class)) {
		require_once($class);
	}
});



// Get ip and socket
$ip = $_POST['ip'];
$socket = $_POST['socket'];

// Create new CSGO instance
$cod = new CSGO($ip, $socket);

// Get number of active players
$players = $cod->getPlayers();

// Server name
echo $cod->getServerName()."<br />";

// Map name
echo $cod->getCurrentMap()." - ".$cod->getCurrentMode()."<br />";

// Players active out of total (A/T)
echo $cod->getCurrentPlayerCount()."/".$cod->getMaxPlayers(); ?>) :<br />

<!-- All players -->
<table width="100%" border="1">
	<tr>
		<td width="14.29%">name</td>
		<td width="14.29%">score</td>
	</tr>
<?php
		if (!empty($players)) {
			foreach ($players as $id => $player) {
				echo "\t<tr>\n";
				echo "\t\t<td>".$player['name']."</td>\n";
				echo "\t\t<td>".$player['score']."</td>\n";
				echo "\t</tr>\n";
			}
		}
	?>
</table>