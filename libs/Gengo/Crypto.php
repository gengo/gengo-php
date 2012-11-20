<?php
/**
 * Gengo API Client
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
 * @category   Gengo
 * @package    API Client Library
 * @copyright  Copyright (c) 2009-2012 Gengo, Inc. (http://gengo.com)
 * @license    http://gengo.com/services/api/dev-docs/gengo-code-license   New BSD License
 */

class Gengo_Crypto
{
    const HMAC_ALGO = 'sha1';

    private function __construct() {}

    /**
     * @param string $data The data to sign
     * @param string $private_key The key used to sign the data
     *
     * @return string Base64 signature of the data
     */
    public static function sign($data, $private_key)
    {
        return hash_hmac(self::HMAC_ALGO, $data, $private_key);
    }
}
