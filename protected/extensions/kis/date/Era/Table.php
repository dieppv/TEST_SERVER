<?php
/**
 * Conversion table used by Date_Japanese_Era
 *
 * PHP version 5.2
 *
 * Copyright (c) 2009-2010 Shinya Ohyanagi, All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Shinya Ohyanagi nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Date
 * @package   Date_Japanese
 * @version   $id$
 * @copyright 2009-2010 Shinya Ohyanagi
 * @author    Shinya Ohyanagi <sohyanagi@gmail.com>
 * @license   New BSD License
 * @link      http://search.cpan.org/~miyagawa/Date-Japanese-Era-0.06/
 */

/**
 * Conversion Table for Date_Japanese_Era
 *
 * @category  Date
 * @package   Date_Japanese
 * @version   $id$
 * @copyright 2009-2010 Shinya Ohyanagi
 * @author    Shinya Ohyanagi <sohyanagi@gmail.com>
 * @license   New BSD License
 * @link      http://search.cpan.org/~miyagawa/Date-Japanese-Era-0.06/
 */
class Date_Japanese_Era_Table
{
    /**
     * ERA_TABLE
     *
     * @var    array
     * @access public
     */
    public static $ERA_TABLE = array(
        '明治' => array('M', 1868, 9, 8, 1912, 7, 29),
        '大正' => array('T', 1912, 7, 30, 1926, 12, 24),
        '昭和' => array('S', 1926, 12, 25, 1989, 1, 7),
        '平成' => array('H', 1989, 1, 8, 2038, 12, 31)
    );


    /**
     * Convert era to ascii
     *
     * @param  mixed $ascii
     * @access public
     * @return mixed
     */
    public static function eraJa2Ascii()
    {
        $era  = array_reverse(self::$ERA_TABLE);
        $data = array();
        foreach ($era as $key => $val) {
            $data[$val[0]] = array(
                $key, $val[1], $val[2], $val[3], $val[4], $val[5], $val[6]
            );
        }
        return $data;
    }
}
