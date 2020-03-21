<?php
namespace Crawler\Query;

use Crawler\Exception\BaseException;
use Crawler\Exception\SocketException;
abstract class BaseQuery {

	/**
	 * Protocol
	 * @var	string
	*/
	protected $protocol = 'tcp';

	/**
	 * RCON socket resource
	 * @var	resource
	*/
	protected $socket = null;
	
	/**
	 * Server Data
	 * @var	string
	*/
	protected $data = '';
	
	
	/**
	 * Class constructor
	 *
	 * @param	string	$server
	 * @param	integer	$port
	*/
	public function __construct ($ip, $port) {
		
		try{
			if(!$this->socket = fsockopen($this->protocol."://".$ip, $port, $ErrNo, $ErrStr, $timeout = 5))
			{
				throw new SocketException("Could not create socket");
			}
		}
		catch(SocketException $e)
		{
			echo $e->errorMessage();
		}
		stream_set_blocking($this->socket, 0);
	}
	
	/**
	 * Class destructor
	*/
	public function __destruct () {
		$this->close();
	}
	
	/**
	 * Send a command to the server
	 *
	 * @param	string	$string
	 * @return	array
	*/
	public function sendCommand ($string) {
		fputs($this->socket, $string);
		$data = $this->receive();
		
		return $data;
	}
	
	/**
	 * Close connection with server
	*/
	public function close () {
		fclose($this->socket);
	}
	
	/**
	 * Receive data from the server
	 *
	 * @return	string
	*/
	abstract protected function receive();
	
	/**
	 * Check if the package is full and readable
	 *
	 * @param	string	$data
	 * @return	boolean
	*/
	protected function containsCompletePacket($data) {
		if (empty($data)) {
			return false;
		}
		
		$meta = stream_get_meta_data($this->socket);
		if (mb_strlen($data) < $meta['unread_bytes']) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get currently active players
	 *
	 * @return	array
	*/
	abstract public function getPlayers();
	
	/**
	 * Get maximum number of players
	 *
	 * @return	integer
	*/
	abstract public function getMaxPlayers();
	
	/**
	 * Get number of currently active players
	 *
	 * @return	integer
	*/
	abstract public function getCurrentPlayerCount();
	
	/**
	 * Get current map 
	 *
	 * @return	string
	*/
	abstract public function getCurrentMap();
	
	/**
	 * Get gamemode
	 *
	 * @return	string
	*/
	abstract public function getCurrentMode();
	
	/**
	 * Get server name
	 *
	 * @return	string
	*/
	abstract public function getServerName();
	
	
	/**
	 * Split package
	 *
	 * @param	string	$type
	 * @return	mixed
	*/
	protected function splitData ($type) {
		if ($type == "byte") {
			$temp = substr($this->data, 0, 1);
			$this->data = substr($this->data, 1);
			return ord($temp);
		}
		else if ($type == "int32") {
			$temp = substr($this->data, 0, 4);
			$this->data = substr($this->data, 4);
			$unpacked = unpack('iint', $temp);
			return $unpacked["int"];
		}
		else if ($type == "float32") {
			$temp = substr($this->data, 0, 4);
			$this->data = substr($this->data, 4);
			$unpacked = unpack('fint', $temp);
			return $unpacked["int"];
		}
		else if ($type == "plain") {
			$temp = substr($this->data, 0, 1);
			$this->data = substr($this->data, 1);
			return $temp;
		}
		else if ($type == "string") {
			$str = '';
			while (($char = $this->splitData('plain')) != chr(0)) {
				$str .= $char;
			}
			return $str;
		}
	}
}
