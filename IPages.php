<?php
/**
 * IPages
 * Pages have to implement this interface before being useable
 *
 * @author Ike Devolder <ike DOT devolder AT gmail DOT com>
 */
interface IPages{

	// default pagetypes you could add mail, pdf, ...
	const HTML='html';
	const JSON='json';

	/**
	 * default comment.
	 *
	 * @param mixed $document
	 * @param mixed $pageparams
	 * @access public
	 * @return void
	 */
	public function __construct(&$document, $pageparams);

	/**
	 * add parameters to the page.
	 *
	 * @param array $parameters
	 * @access public
	 * @return void
	 */
	public function setParameters($parameters);

	/**
	 * this is the default output of the page
	 *
	 * @access public
	 * @return void
	 */
	public function index();
}
