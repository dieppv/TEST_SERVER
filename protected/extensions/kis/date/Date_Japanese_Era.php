<?php
/**
 * Conversion between Japanese Era / Gregorian calendar
 *
 * This module is inspired by Date::Japanese::Era (Perl module).
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
 * @see Date_Japanese_Era_Table
 */
require_once dirname(__FILE__) . '/Era/Table.php';

/**
 * @see Date_Japanese_Era_Exception
 */
require_once dirname(__FILE__) . '/Era/Exception.php';

/**
 * Conversion between Japanese Era / Gregorian calendar
 *
 * <pre>
 *   This module is inspired by Date::Japanese::Era (Perl module).
 * </pre>
 *
 * @category  Date
 * @package   Date_Japanese
 * @version   $id$
 * @copyright 2009-2010 Shinya Ohyanagi
 * @author    Shinya Ohyanagi <sohyanagi@gmail.com>
 * @license   New BSD License
 * @link      http://search.cpan.org/~miyagawa/Date-Japanese-Era-0.06/
 */
class Date_Japanese_Era
{
    /**
     * Version
     */
    const VERSION = '0.1.1';

    /**
     * Era name
     *
     * @var mixed
     * @access private
     */
    private $_name;

    /**
     * Ailas of $_name
     *
     * @var    mixed
     * @access private
     */
    private $_gengou;

    /**
     * Era year
     *
     * @var    mixed
     * @access private
     */
    private $_year;

    /**
     * Gregorian year
     *
     * @var    mixed
     * @access private
     */
    private $_gregorianYear;

    /**
     * Era name as ascii
     *
     * @var    mixed
     * @access private
     */
    private $_nameAscii;

    /**
     * __construct
     *
     * @param  array $args Args
     * @access public
     * @return void
     */
    public function __construct(array $args)
    {
        if (count($args) === 3) {
            $this->_fromYmd($args);
        } else if (count($args) === 2) {
            $this->_fromEra($args);
        } else {
            throw new Date_Japanese_Era_Exception(
                'Invalid number of arguments: ' . count($args)
            );
        }
    }


    /**
     * Get property
     *
     * @param  mixed $name Property name
     * @access public
     * @return mixed Property if exists
     */
    public function __get($name)
    {
        if (property_exists(__CLASS__, '_' . $name)) {
            return $this->{'_' . $name};
        }
        return null;
    }

    /**
     * Convert from Ymd
     *
     * @param  array $args
     * @access private
     * @throws Date_Japanese_Era_Exception Invalid date
     * @return void
     */
    private function _fromYmd(array $args)
    {
        list($year, $month, $day) = $args;
        if (checkdate($month, $day, $year) === false) {
            throw new Date_Japanese_Era_Exception('Invalid date.');
        }

        $era   = Date_Japanese_Era_Table::$ERA_TABLE;
        $table = array_reverse($era);
        foreach ($table as $key => $val) {
            $start = sprintf('%02d%02d%02d', $val[1], $val[2], $val[3]);
            $end   = sprintf('%02d%02d%02d', $val[4], $val[5], $val[6]);
            $ymd   = sprintf('%02d%02d%02d', $year, $month, $day);

            if ($ymd >= $start && $ymd <= $end) {
                $this->_name          = $key;
                $this->_year          = ($year - $val[1]) + 1;
                $this->_gengou        = $key;
                $this->_nameAscii     = $val[0];
                $this->_gregorianYear = $year;
                return;
            }
        }
        throw new Date_Japanese_Era_Exception(
            'Unsupported date: ' . implode('-', $args)
        );
    }

    /**
     * Convert from era
     *
     * @param  array $args Args
     * @access private
     * @throws Date_Japanese_Era_Exception Unsupported era name
     * @return void
     */
    private function _fromEra(array $args)
    {
        list($era, $year) = $args;
        $data = Date_Japanese_Era_Table::$ERA_TABLE;
        if (preg_match('/^[a-zA-Z]+$/', $era)) {
            $era = $this->_ascii2ja($era);
        }

        if (!isset($data[$era])) {
            throw new Date_Japanese_Era_Exception('Unknown era name: ' . $era);
        }

        $gYear = $data[$era][1] + $year - 1;
        if ($gYear > $data[$era][4]) {
            throw new Date_Japanese_Era_Exception(
                'Invalid combination of era and year: ' . $era . '-' . $year
            );
        }
        $this->_name          = $era;
        $this->_year          = $year;
        $this->_gengou        = $era;
        $this->_nameAscii     = $data[$era][0];
        $this->_gregorianYear = $gYear;
    }


    /**
     * Convert ascii era name to japanese
     *
     * @param  mixed $ascii Era name written in ascii
     * @access private
     * @return string Era name
     */
    private function _ascii2ja($ascii)
    {
        $era = Date_Japanese_Era_Table::eraJa2Ascii();
        return isset($era[$ascii][0]) ? $era[$ascii][0] : null;
    }
}
