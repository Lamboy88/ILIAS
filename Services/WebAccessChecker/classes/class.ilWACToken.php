<?php
require_once('class.ilWACSignedPath.php');

/**
 * Class ilWACToken
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilWACToken {

	protected static $SALT = 'f7c832ce6d8b64e46861a6c5b33e113664d4b25c';
	const TYPE_FILE = ilWACSignedPath::TYPE_FILE;
	const TYPE_FOLDER = ilWACSignedPath::TYPE_FOLDER;
	/**
	 * @var string
	 */
	protected $session_id = '';
	/**
	 * @var int
	 */
	protected $timestamp = 0;
	/**
	 * @var int
	 */
	protected $type = self::TYPE_FILE;
	/**
	 * @var string
	 */
	protected $ip = '';
	/**
	 * @var string
	 */
	protected $token = '';
	/**
	 * @var string
	 */
	protected $path = '';


	/**
	 * @param $path
	 */
	protected function __construct($path) {
		$parts = parse_url($path);
		$this->setPath($parts['path']);
		$this->setSessionId(session_id() ? session_id() : '-');
		$this->setIp($_SERVER['REMOTE_ADDR']);
		$this->setTimestamp(time());
		$this->generateToken();
	}


	protected function generateToken() {
		$token = implode('-', array( $this->getSessionId(), $this->getIp() ));
		$token = $token * self::$SALT;
		$token = sha1($token);
		$this->setToken($token);
	}


	/**
	 * @param $path
	 *
	 * @return ilWACToken
	 */
	public static function getInstance($path) {
		return new self($path);
	}


	/**
	 * @return string
	 */
	public function getSessionId() {
		return $this->session_id;
	}


	/**
	 * @param string $session_id
	 */
	public function setSessionId($session_id) {
		$this->session_id = $session_id;
	}


	/**
	 * @return int
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}


	/**
	 * @param int $timestamp
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}


	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param int $type
	 */
	public function setType($type) {
		$this->type = $type;
	}


	/**
	 * @return string
	 */
	public function getIp() {
		return $this->ip;
	}


	/**
	 * @param string $ip
	 */
	public function setIp($ip) {
		$this->ip = $ip;
	}


	/**
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}


	/**
	 * @param string $token
	 */
	public function setToken($token) {
		$this->token = $token;
	}


	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}


	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}


	/**
	 * @return string
	 */
	public static function getSALT() {
		return self::$SALT;
	}


	/**
	 * @param string $SALT
	 */
	public static function setSALT($SALT) {
		self::$SALT = $SALT;
	}
}

?>
