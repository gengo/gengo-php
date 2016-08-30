<?php

/**
 * PHP version 5.6
 *
 * @package Gengo
 */

namespace Gengo;

use \Exception;

/**
 * Order API client class
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that came
 * with this package in the file LICENSE.txt. It is also available
 * through the world-wide-web at this URL:
 * http://gengo.com/services/api/dev-docs/gengo-code-license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@gengo.com so we can send you a copy immediately.
 *
 * @author    Vladimir Bashkirtsev <vladimir@bashkirtsev.com>
 * @copyright 2009-2016 Gengo, Inc. (http://gengo.com)
 * @license   http://gengo.com/services/api/dev-docs/gengo-code-license New BSD License
 * @version   GIT: $Id:$
 * @link      https://github.com/gengo/gengo-php
 */

class Order extends API
    {

	/**
	 * Retrieves a specific order and return various information and statistics.
	 *
	 * Calls translate/order/{id} (GET)
	 *
	 * @param int $id The ID of the job to retrieve
	 *
	 * @return string Gengo response
	 *
	 * @untranslatable job_id
	 * @untranslatable v2/translate/order/
	 *
	 * @api
	 */

	public function getOrder($id = null)
	    {
		$id = $this->getID($id, "job_id");
		return $this->storeResponse(Client::get("v2/translate/order/" . $id));
	    } //end getOrder()


	/**
	 * Cancels all jobs in an order that can be cancelled (available jobs)
	 *
	 * Calls translate/order/{id} (DELETE)
	 *
	 * This feature is EXPERIMENTAL
	 *
	 * @param int $id The ID of the order to cancel
	 *
	 * @return string Gengo response
	 *
	 * @untranslatable job_id
	 * @untranslatable v2/translate/order/
	 *
	 * @api
	 */

	public function cancel($id = null)
	    {
		$id = $this->getID($id, "job_id");
		return $this->storeResponse(Client::delete("v2/translate/order/" . $id));
	    } //end cancel()


	/**
	 * Retrieves the comment thread for a order
	 *
	 * Calls translate/order/{id}/comments (GET)
	 *
	 * @param int $id The ID of the order to retrieve
	 *
	 * @return string Gengo response
	 *
	 * @untranslatable job_id
	 * @untranslatable v2/translate/order/
	 * @untranslatable /comments
	 *
	 * @api
	 */

	public function getComments($id)
	    {
		$id = $this->getID($id, "job_id");
		return $this->storeResponse(Client::get("v2/translate/order/" . $id . "/comments"));
	    } //end getComments()


	/**
	 * Submits a new comment to the order's comment thread.
	 *
	 * Calls translate/order/{id}/comment (POST)
	 *
	 * @param int    $id   The ID of the order to comment on
	 * @param string $body The comment's actual contents
	 *
	 * @return string Gengo response
	 *
	 * @throws Exception Incomplete or invalid calling parameters
	 *
	 * @exceptioncode GENGO_EXCEPTION_MUST_CONTAIN_VALID_BODY
	 *
	 * @untranslatable post
	 * @untranslatable \"body\"
	 * @untranslatable job_id
	 * @untranslatable v2/translate/order/
	 * @untranslatable /comment
	 *
	 * @api
	 */

	public function postComment($id, $body)
	    {
		if (empty($body) === false)
		    {
			$data = array("body" => $body);

			$params = array(
				   "_method" => "post",
				   "data"    => json_encode($data),
				  );
		    }
		else
		    {
			throw new Exception(
			    _("In method") . " " . __METHOD__ . ": " . _("must contain a valid") . " \"body\" " . _("parameter as the comment"),
			    GENGO_EXCEPTION_MUST_CONTAIN_VALID_BODY
			);
		    }

		$id = $this->getID($id, "job_id");
		return $this->storeResponse(Client::post("v2/translate/order/" . $id . "/comment", $params));
	    } //end postComment()


    } //end class

?>
