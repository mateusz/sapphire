<?php
/**
 * Initialises the versioned stage when a request is made.
 *
 * @package framework
 * @subpackage control
 */
class VersionedRequestFilter implements RequestFilter {

	public function preRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, Session $session, DataModel $model) {
		Versioned::choose_site_stage($session);
		return $response;
	}

	public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model) {
		return $response;
	}

}
