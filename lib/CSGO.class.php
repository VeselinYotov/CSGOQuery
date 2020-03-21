<?php
namespace Crawler;
use Crawler\Query\SourceQuery;

class CSGO extends SourceQuery {
	
	/**
	 * Get active players
	 *
	 * @return	array
	*/
	public function getPlayers() {

		// Request ID
		$data = $this->sendCommand("\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF");
		$data = substr($data, 5, 4);

		// Request data by ID
		$this->data = $this->sendCommand("\xFF\xFF\xFF\xFF\x55".$data);

		// Parse player data
		$this->splitData('int32');
		$this->splitData('byte');

		$count = $this->splitData('byte');
		$players = array();
		for ($i=1; $i <= $count; $i++) {
			$player = array();
			$player["index"] = $this->splitData('byte');
			$player["name"] = $this->splitData('string');
			$player["score"] = $this->splitData('int32');
			$player["time"] = date('H:i:s', round($this->splitData('float32'), 0)+82800);
			$players[] = $player;
		}

		return $players;
	}
	
	/**
	 * Get maximum number of players
	 *
	 * @return	integer
	*/
	public function getMaxPlayers() {
		$data = $this->getServerData();
		
		return $data['playersmax'];
	}
	
	/**
	 * Get number of active players
	 *
	 * @return	integer
	*/
	public function getCurrentPlayerCount() {
		$data = $this->getServerData();
		
		return $data['players'];
	}
	
	/**
	 * Get current map
	 *
	 * @return	string
	*/
	public function getCurrentMap() {
		$data = $this->getServerData();
		
		return $data['map'];
	}
	
	/**
	 * Get gamemode
	 *
	 * @return	string
	*/
	public function getCurrentMode() {
		// not available
	}
	
	/**
	 * Get server name
	 *
	 * @return	string
	*/
	public function getServerName() {
		$data = $this->getServerData();
		
		return $data['name'];
	}
}
