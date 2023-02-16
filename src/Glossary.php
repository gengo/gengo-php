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

    /**
     * Retrieves a full representation of a glossary object.
     *
     * Calls glossary (GET)
     *
     * @param int $glossary_id The ID of the glossary to return.
     *
     * @return string Gengo response
     */
    public function getGlossaryDetails($glossary_id)
    {
        return $this->storeResponse(Client::get('glossary/'.$glossary_id));
    } //end getGlossaryDetails()

    /**
     * Creates a new glossary from a given CSV file (POST)
     *
     * Calls glossary (POST)
     *
     * @param string $file_path The path of the glossary file
     *
     * @return string Gengo response
     */
    public function postGlossary($file_path)
    {
        $params = array(
            '_method' => 'post',
        );
        $files = array(
            'file_path' => $file_path
        );

        return $this->storeResponse(Client::post('glossary/', $params, $files));
    } //end postGlossary()

    /**
     * Updates the glossary with the given ID with given CSV file.
     *
     * Calls glossary (PUT)
     *
     * @param int $glossary_id The ID of the glossary to return.
     * @param string $file_path The path of the glossary file
     *
     * @return string Gengo response
     */
    public function putGlossary($glossary_id, $file_path)
    {
        $params = array(
            '_method' => 'put',
        );
        $files = array(
            'file_path' => $file_path
        );

        return $this->storeResponse(Client::put('glossary/'.$glossary_id, $params, $files));
    } //end putGlossary()

    /**
     * Deletes a glossary with the given ID.
     *
     * Calls glossary (DELETE)
     *
     * @param int $glossary_id The ID of the glossary to delete.
     */
    public function deleteGlossary($glossary_id)
    {
        return $this->storeResponse(Client::delete('glossary/'.$glossary_id));
    } //end deleteGlossary()
} //end class
