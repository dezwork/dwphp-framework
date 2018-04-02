<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 28-03-2018 17:26:22
 */
namespace util;
use Money\Currency;
use Money\Money;

class Monetary{

    public $money;

    public function __construct($number, $moeda='BRL'){
        return $this->setMoney($number, $moeda='BRL');
    }

    public function add($money){
        return ($this->getMoney())->add($money->getMoney());
    }

    public function subtract($money){
        return ($this->getMoney())->subtract($money->getMoney());
    }

    public function multiply($money){
        return ($this->getMoney())->multiply($money);
    }

    public function divide($money){
        return ($this->getMoney())->divide($money);
    }

    public function percentage($pct){

        $money = round($this->getMoney()->getAmount()+(($this->getMoney()->getAmount()/100)*((float)$pct)));
        $money = $this->setMoney($money);
        return $this->getMoney();
    }


    public function moneyDb(){
        $number=($this->getMoney()->getAmount());
        $number=substr($number, 0, -2).'.'.str_pad(substr($number,-2), 2, '0', STR_PAD_LEFT);
        return number_format($number, 2, '.', '');
    }

    public function moneyBR(){
        $number=($this->moneyDb());
        return 'R$ '.number_format($number, 2, ',', '.');
    }

    public function moneyUS(){
        $number=($this->moneyDb());
        return '$ '.number_format($number, 2, '.', ',');
    }





    /**
     * @return mixed
     */
    public function getMoney(){
        return $this->money;
    }

    /**
     * @param mixed $money
     *
     * @return self
     */
    public function setMoney($number, $moeda='BRL'){
        $this->money = Money::BRL($number, new Currency($moeda));
        return $this;
    }
}