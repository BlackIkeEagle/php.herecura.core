<?php
/**
 * RequestHandler.
 * parse the url path and give us page, action and paramters
 *
 * @author Ike Devolder <ike DOT devolder AT gmail DOT com>
 */
class RequestHandler {
	private $path = null;
	private $page = null;
	private $action = null;
	private $parameters = null;

	private $basePath = null;

	/**
	 * create requesthandler.
	 *
	 * @param array $parameters
	 * @access public
	 * @return void
	 */
	public function __construct($parameters = null) {
		$escapedBasepath = str_replace('/', '\/', BASEPATH);

		$this->basePath = BASEPATH;
		if(!empty($_SERVER['REQUEST_URI'])) {
			$this->path = trim(preg_replace('/^index\.php/', '', preg_replace('/^'.$escapedBasepath.'/', '', $_SERVER['REQUEST_URI'])), '/');
		}

		if($parameters !== null && is_array($parameters) && count($parameters) > 0) {
			$allParams = $parameters;
		} elseif(!empty($this->path)) {
			$allParams = explode('/', $this->path);
		} else {
			$allParams = array();
		}

		if(count($allParams) > 0) {
			$page = array_shift($allParams);
		} else {
			$page = Config::DEFAULTPAGE;
		}

		$this->page = '404';
		if(Acl::acl()->isAllowed($page)) {
			$classname = 'Pages_'.ucfirst($page);
			if(class_exists($classname))
				$this->page = $page;
		} else {
			$classname = 'Pages_Login';
			if(class_exists($classname))
				$this->page = 'login';
		}

		if(count($allParams) > 0) {
			$this->action = array_shift($allParams);
		}

		$cntRest = count($allParams);
		if($cntRest > 0) {
			for($x = 0; $x < $cntRest; $x = $x+2) {
				if(!empty($allParams[$x]) && !empty($allParams[$x+1])) {
					$this->parameters[$allParams[$x]] = $allParams[$x+1];
				}
			}
		}

		unset($allParams); // i don't always trust garbage collection
	}

	/**
	 * get the used path.
	 *
	 * @access public
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * get what the requested page is.
	 *
	 * @access public
	 * @return string
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * get what the requested page action is.
	 *
	 * @access public
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * get the remaining parameters.
	 *
	 * @access public
	 * @return array
	 */
	public function getParameters(){
		return $this->parameters;
	}

	/**
	 * get the basepath
	 *
	 * @access public
	 * @return string
	 */
	public function getBasePath() {
		return $this->basePath;
	}
}
