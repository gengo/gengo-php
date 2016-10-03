<?php

/**
 * PHP version 5.6.
 *
 * @package Gengo
 */

namespace Gengo;

/**
 * Glossary API client class.
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
class Glossary extends API
{
    /**
     * Retrieves a list of glossaries.
     *
     * Calls translate/glossary (GET)
     *
     * @return string Gengo response
     *
     * @untranslatable v2/translate/glossary
     *
     * @api
     */
    public function getGlossaries()
    {
        return $this->storeResponse(Client::get('v2/translate/glossary'));
    } //end getGlossaries()

    /**
     * Retrieves a glossary.
     *
     * Calls translate/glossary (GET)
     *
     * @param int $glossaryid The ID of the glossary to return
     *
     * @return string Gengo response
     *
     * @untranslatable v2/translate/glossary/
     *
     * @api
     */
    public function getGlossary($glossaryid)
    {
        return $this->storeResponse(Client::get('v2/translate/glossary/'.$glossaryid));
    } //end getGlossary()
} //end class
