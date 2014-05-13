<?php

/**
 * Represents a request processer that delegates pre and post request handling to nested request filters
 * 
 * @package framework
 * @subpackage control
 */
class RequestProcessor implements RequestFilter {

	/**
	 * List of currently assigned request filters
	 *
	 * @var array
	 */
	private $filters = array();

	public function __construct($filters = array()) {
		$this->filters = $filters;
	}

	/**
	 * Assign a list of request filters
	 * 
	 * @param array $filters
	 */
	public function setFilters($filters) {
		$this->filters = $filters;
	}

	/**
	 * Add a filter with the highest priority.
	 *
	 * @param RequestFilter $filter
	 */
	public function unshiftFilter($filter) {
		$this->filters[] = $filter;
	}

	/**
	 * Add a filter with the lowest priority.
	 *
	 * @param RequestFilter $filter
	 */
	public function pushFilter($filter) {
		$this->filters[] = $filter;
	}

	public function preRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, Session $session, DataModel $model) {
		foreach ($this->filters as $filter) {
			$response = $filter->preRequest($request, $response, $session, $model);

			// Should we skip further filtering?
			if ($response->shouldTerminate('immediately')) return $response;
		}
		return $response;
	}

	public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model) {
		foreach ($this->filters as $filter) {
			$response = $filter->postRequest($request, $response, $model);

			// Should we skip further filtering?
			if ($response->shouldTerminate('immediately')) return $response;
		}
		return $response;
	}
}
