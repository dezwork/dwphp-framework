<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 02-04-2018 09:03:51
 */

namespace util;

class DateTime{


    /**
     * A constant representing the number of seconds in a minute, for
     * making code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_MINUTE = 60;
    /**
     * A constant representing the number of seconds in an hour, for making
     * code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_HOUR = 3600;
    const SECONDS_IN_AN_HOUR = 3600;
    /**
     * A constant representing the number of seconds in a day, for making
     * code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_DAY = 86400;
    /**
     * A constant representing the number of seconds in a week, for making
     * code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_WEEK = 604800;
    /**
     * A constant representing the number of seconds in a month (30 days),
     * for making code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_MONTH = 2592000;
    /**
     * A constant representing the number of seconds in a year (365 days),
     * for making code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_YEAR = 31536000;


    private $date;

    private $timeZone;

    private $timeZonesBR = array(
        'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
        'AP' => 'America/Belem',        'AM' => 'America/Manaus',
        'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
        'DF' => 'America/Sao_Paulo',    'ES' => 'America/Sao_Paulo',
        'GO' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
        'MT' => 'America/Cuiaba',       'MS' => 'America/Campo_Grande',
        'MG' => 'America/Sao_Paulo',    'PR' => 'America/Sao_Paulo',
        'PB' => 'America/Fortaleza',    'PA' => 'America/Belem',
        'PE' => 'America/Recife',       'PI' => 'America/Fortaleza',
        'RJ' => 'America/Sao_Paulo',    'RN' => 'America/Fortaleza',
        'RS' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
        'RR' => 'America/Boa_Vista',    'SC' => 'America/Sao_Paulo',
        'SE' => 'America/Maceio',       'SP' => 'America/Sao_Paulo',
        'TO' => 'America/Araguaia'
    );


    public function __construct($d='now', $ufTimeZone='SP'){
        $this->setTimeZone((isset($this->timeZonesBR[$ufTimeZone])?$this->timeZonesBR[$ufTimeZone]:$this->timeZonesBR['SP']));
        $this->setDateOBJ(new \DateTime(str_replace('/', '-', $d)));
        $this->getDateOBJ()->setTimezone(new \DateTimeZone($this->getTimeZone()));
    }

    /**
     * @return date d/m/y - 14/08/1992
     */
    public function dateBR($s='/'){
        return $this->getDateOBJ()->format("d".$s."m".$s."Y");
    }

    /**
     * @return date Y/m/d - 1992/08/14
     */
    public function dateUS($s='-'){
        return $this->getDateOBJ()->format("Y".$s."m".$s."d");
    }

    /**
     * @return date d/m/Y H:i:s - 14/08/1992 13:45:28
     */
    public function dateTimeBR($d='/',$h=':'){
        return $this->getDateOBJ()->format("d".$d."m".$d."Y H".$h."i".$h."s");
    }

    /**
     * @return date Y-m-d H:i:s - 1992/08/14 13:45:28
     */
    public function dateTimeUS($d='-',$h=':'){
        return $this->getDateOBJ()->format("Y".$d."m".$d."d H".$h."i".$h."s");
    }

    /**
     * @return timestamp 1434966025
     */
    public function timestamp($d='-',$h=':'){
        return $this->getDateOBJ()->getTimestamp();
    }

    /**
     * @return America/Sao_Paulo
     */
    public function timeZone(){
        return $this->getDateOBJ()->format("e");
    }

    public function month(){
        return $this->getDateOBJ()->format('m');
    }

    public function year(){
        return $this->getDateOBJ()->format('y');
    }

    public function day(){
        return $this->getDateOBJ()->format('d');
    }

    public function hour(){
        return $this->getDateOBJ()->format('hour');
    }

    public function minut(){
        return $this->getDateOBJ()->format('i');
    }

    public function second(){
        return $this->getDateOBJ()->format('s');
    }

    /**
     * @return mixed
     */
    public function getDateOBJ(){
        return $this->dateOBJ;
    }

    /**
     * @param mixed $dateOBJ
     *
     * @return self
     */
    public function setDateOBJ(\DateTime $dateOBJ){
        $this->dateOBJ = $dateOBJ;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeZone(){
        return $this->timeZone;
    }

    /**
     * @param mixed $timeZone
     *
     * @return self
     */
    public function setTimeZone($timeZone){
        $this->timeZone = $timeZone;

        return $this;
    }
}