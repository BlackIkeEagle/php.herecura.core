<?php
/**
 * basic definition of a page.
 *
 * @author Ike Devolder <ike DOT devolder AT gmail DOT com>
 */
class Page {
	private $document;

	private $pageType;
	private $requestHandler;

	private $modules = array(
		'onEntry' => array(),
		'beforeContent' => array(),
		'afterContent' => array(),
		'onExit' => array()
	);

	/**
	 * construct the basic page stuff
	 *
	 * @param string $pageType
	 * @param string $requestHandler
	 * @access public
	 * @return void
	 */
	public function __construct($pageType, $requestHandler) {
		$this->setPageType($pageType);
		$this->setRequestHandler($requestHandler);
		$document = 'Document_'.ucfirst($this->pageType).'Default';
		$this->document = new $document();
	}

	/**
	 * get the document.
	 *
	 * @access public
	 * @return Document_*
	 */
	public function &getDocument() {
		return $this->document;
	}

	/**
	 * add a module all, or output stuff
	 *
	 * @param AModule $module
	 * @access public
	 * @return void
	 */
	public function addModule($module) {
		if($module instanceof AModules) {
			foreach(array_keys($this->modules) as $function) {
				if(method_exists($module, $function))
					array_push($this->modules[$function], $module);
			}
		}
	}

	/**
	 * define the pagetype (xhtml, json, ...)
	 *
	 * @param string $pageType
	 * @access private
	 * @return void
	 */
	private function setPageType($pageType) {
		$this->pageType = $pageType;
	}

	private function setRequestHandler($requestHandler) {
		$this->requestHandler = $requestHandler;
	}

	/**
	 * run submodules.
	 * run the submodules who define the whatModules function
	 *
	 * @param mixed $whatModules
	 * @access private
	 * @return void
	 */
	private function runModules($whatModules) {
		foreach($this->modules[$whatModules] as $module) {
			if($module->isAvailableForPageType($this->pageType)) {
				$module->$whatModules();
			}
		}
	}

	/**
	 * default page run.
	 * all is controlled by thisone (well thisone is called from __toString)
	 *
	 * @access private
	 * @return void
	 */
	private function run() {
		/* onentry modules */
		$this->runModules('onEntry');

		/* beforeContent modules */
		$this->runModules('beforeContent');

		$classname = "Pages_".ucfirst($this->requestHandler->getPage());
		$page = new $classname($this->document, $this->toArray());
		if(!($page instanceof IPages))
			throw new Exception('the page doesnt implement the page interface');

		if($this->requestHandler->getParameters() != null)
			$page->setParameters($this->requestHandler->getParameters());

		if($this->requestHandler->getAction() != null && method_exists($page, $this->requestHandler->getAction())) {
			$action = $this->requestHandler->getAction();
			$page->$action();
		} else {
			$page->index();
		}

		/* afterContent modules */
		$this->runModules('afterContent');

		/* onExit modules */
		$this->runModules('onExit');
	}

	/**
	 * output the document.
	 *
	 * @access public
	 * @return void
	 */
	public function __toString() {
		$this->run();
		return $this->document->__toString();
	}

	/**
	 * output the parameters which define this page.
	 *
	 * @access public
	 * @return array
	 */
	public function toArray() {
		return array(
			'pagetype' => $this->pageType,
			'basepath' => $this->requestHandler->getBasePath()
		);
	}
}
