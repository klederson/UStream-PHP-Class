<?php
/**
 * This class has been created to help developers adapt the UStream Services to their sites
 * 
 * @author Klederson Bueno <klederson@klederson.com>
 * @version 0.1a
 * @license GPL
 */
class UStream {
	
	static public $apiUrl = 'http://api.ustream.tv'; //complete url reference http://api.ustream.tv/[html|json|xml|php]/[subject]/[subjectUID|scope]/[command]/[otherparams]/?page=[n]&limit=[l]&key=[devkey]
	
	/**
	 * The main response method *** DO NOT CHANGE IT BECAUSE THE CLASS USES JSON TO COMMUNICATE ***
	 * @var unknown_type
	 */
	static private $responseMode = 'json'; //html, json, xml, php
	
	/**
	 * The main request mode you can change it using setRequestMode
	 * @var String
	 */
	static public $requestMode = 'channel'; //user, channel, stream, video, system
	
	/**
	 * Your UStream API Key
	 * @var String
	 */
	static protected $apiKey = null;
	
	public $cacheResult = null;
	
	public $limit = 20;
	public $page = 1;
	
	public function __construct($apiKey, $requestMode = 'channel') {
		self::$apiKey = $apiKey;
		self::$requestMode = $requestMode;
		$this->cacheResult = new stdClass();
	}
	
	###########################################
	# Misc commands
	###########################################

	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @return stdObj
	 */
	public function getInfo($subject) {
		$requestUrl = $this->buildRequestUrl($subject, 'getInfo');
		
		return $this->getResult($requestUrl);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @param String $property
	 * 
	 * @return stdObj
	 */
	public function getValueOf($subject, $property) {
		$requestUrl = $this->buildRequestUrl($subject, 'getValueOf/'.$property);
				
		return $this->getResult($requestUrl);
	}
	
	/** 
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $scope
	 * @param String $where
	 * @param String $how
	 * @param String $what
	 * @param Number $page
	 * 
	 * @return stdObj
	 */
	public function search($scope, $where, $how, $what,$page = 1) {
		$fullCommand = sprintf("search/%s:%s:%s", $where, $how, $what);
		
		$requestUrl = $this->buildRequestUrl($scope, $fullCommand);
				
		return $this->getResult($requestUrl, $page);
	}
	
	###########################################
	# Stream commands
	###########################################
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @return stdObj
	 */
	public function getRecent() {
		$requestUrl = $this->buildRequestUrl('all', 'getRecent');
		
		return $this->getResult($requestUrl);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @return stdObj
	 */
	public function getMostViewers() {
		$requestUrl = $this->buildRequestUrl('all', 'getMostViewers');
		
		return $this->getResult($requestUrl);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @return stdObj
	 */
	public function getRandom() {
		$requestUrl = $this->buildRequestUrl('all', 'getRandom');
		
		return $this->getResult($requestUrl);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $page
	 * @return stdObj
	 */
	public function getAllNew($page = 1) {
		$requestUrl = $this->buildRequestUrl('all', 'getAllNew');
		
		return $this->getResult($requestUrl, $page);
	}
	
	###########################################
	# Video commands
	###########################################
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @return stdObj
	 */
	public function getTags($subject) {
		$requestUrl = $this->buildRequestUrl($subject, 'getTags');
		
		return $this->getResult($requestUrl);
	}
	
	###########################################
	# Channel commands
	###########################################
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @return stdObj
	 */
	public function getEmbedTag($subject) {
		$requestUrl = $this->buildRequestUrl($subject, 'getEmbedTag');
		
		return $this->getResult($requestUrl);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @param Number $width
	 * @param Number $height
	 * @param Boolean $autoPlay
	 * @param Boolean $mute
	 * 
	 * @return stdObj
	 */
	public function getCustomEmbedTag($subject,$width = 100, $height = 100, $autoPlay = false, $mute = false) {
		$parms['autoplay'] = $autoPlay;
		$parms['mute'] = $mute;
		$parms['width'] = $width;
		$parms['height'] = $height;
		
		$requestUrl = $this->buildRequestUrl($subject, 'getCustomEmbedTag', $parms);
				
		return $this->getResult($requestUrl);
	}
	
	###########################################
	# User commands
	###########################################
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * 
	 * @return stdObj
	 */
	public function getId($subject) {
		$requestUrl = $this->buildRequestUrl($subject, 'getId');
		
		return $this->getResult($requestUrl);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @param Number $page
	 * 
	 * @return stdObj
	 */
	public function listAllChannels($subject, $page = 1) {
		$requestUrl = $this->buildRequestUrl($subject, 'listAllChannels');
		
		return $this->getResult($requestUrl, $page);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @param Number $page
	 * 
	 * @return stdObj
	 */
	public function listAllVideos($subject, $page = 1) {
		$requestUrl = $this->buildRequestUrl($subject, 'listAllVideos');
		
		return $this->getResult($requestUrl, $page);
	}
	
	/**
	 * @see http://developer.ustream.tv/data_api/docs
	 * 
	 * @param String $subject
	 * @param Number $page
	 * 
	 * @return stdObj
	 */
	public function getComments($subject, $page = 1) {
		$requestUrl = $this->buildRequestUrl($subject, 'getComments');
		
		return $this->getResult($requestUrl, $page);
	}
	
	###########################################
	# Util methods
	###########################################	
	
	/**
	 * isLive checks if the channel is broadcasting live at the moment
	 * 
	 * @param String $channel
	 * @return Boolean
	 */
	public function isLive($channel) {
		$channelData = $this->getInfo($channel);
		
		if($channelData->results->status == 'live') {
			return true;
		} else {
			false;
		}
	}
	
	/**
	 * This method builds the request url to call the UStream API this builds all requests to UStream
	 * 
	 * @param String $subject
	 * @param String $command
	 * @param Array $parms
	 * @return String
	 */
	private function buildRequestUrl($subject, $command, array $parms = array()) {
		foreach($parms as $index => $value) {
			$trueParms = !empty($trueParms) && $trueParms != null ? $trueParms .';' : $trueParms;
			$trueParms .= sprintf("%s:%s",$index,$value);
		}
		
		$trueParms = !empty($trueParms) && $trueParms != null ? $trueParms .'&params=' : $trueParms;
		
		return sprintf("%s/%s/%s/%s/%s?key=%s%s",self::$apiUrl, self::$responseMode, self::$requestMode, $subject, $command, self::$apiKey, $trueParms);
	}
	
	###########################################
	# Class methods
	###########################################
	
	/**
	 * Perform the call based in a requestUrl and than add the parm limit (see setLimit) and page as parm
	 * 
	 * @param String $requestUrl
	 * @param Number $page
	 * 
	 * @return stdObj
	 */
	public function getResult($requestUrl, $page = 1) {
		
		$requestUrl = sprintf("$requestUrl&limit=%s&page=%s",$this->limit,$this->page);
		
		$this->cacheResult = json_decode(file_get_contents($requestUrl));
		
		if($this->error() == true) {
			return false;
		} else {
			return $this->cacheResult;
		}
	}
	
	/**
	 * It checks if after the getResult call there are some errors
	 * 
	 * @return Boolean
	 */
	private function error() {
		if($this->cacheResult->error != null) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This method gets the error message and code
	 * 
	 * @return Array
	 */
	public function getError() {
		$return['code'] = $this->cacheResult->error;
		$return['message'] = $this->cacheResult->msg;
		
		return $return;
	}
	
	/**
	 * Set the mode of response from UStream API
	 * 
	 * @param String $responseMode
	 */
	public function setResponseMode($responseMode) {
		self::$responseMode = $responseMode;
	}
	
	/**
	 * Set the request mode: channel, user, system, video, stream
	 * 
	 * @param String $requestMode
	 */
	public function setRequestMode($requestMode) {
		self::$requestMode = $requestMode;
	}
	
	/**
	 * Define your UStream API Key
	 * 
	 * @param String $apiKey
	 */
	public function setApiKey($apiKey) {
		self::$apiKey = $apiKey;
	}
	
}

?>