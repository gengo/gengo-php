<?php

/**
 * PHP version 5.6.
 */

namespace Gengo;

/**
 * Account API client class.
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
 *
 * @version   GIT: $Id:$
 *
 * @link      https://github.com/gengo/gengo-php
 */
class Account extends API
{
    /**
     * Retrieves account stats, such as orders made.
     *
     * Calls account/stats (GET)
     *
     * @return string Gengo response
     *
     * @untranslatable v2/account/stats
     *
     * @api
     */
    public function getStats()
    {
        return $this->storeResponse(Client::get('v2/account/stats'));
    } //end getStats()

    /**
     * Retrieves account balance in credits.
     *
     * Calls account/balance (GET)
     *
     * @return string Gengo response
     *
     * @untranslatable v2/account/balance
     *
     * @api
     */
    public function getBalance()
    {
        return $this->storeResponse(Client::get('v2/account/balance'));
    } //end getBalance()

    /**
     * Retrieves preferred translators as array by langs and tier.
     *
     * Calls account/preferred_translators (GET)
     *
     * @return string Gengo response
     *
     * @untranslatable v2/account/preferred_translators
     *
     * @api
     */
    public function getPreferredTranslators()
    {
        return $this->storeResponse(Client::get('v2/account/preferred_translators'));
    } //end getPreferredTranslators()
} //end class
;
