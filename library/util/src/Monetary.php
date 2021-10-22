<?php

namespace util;
use Money\Currency;
use Money\Money;

class Monetary{

    public $money;

    public function __construct($number, $moeda='BRL'){
        $number = ltrim(str_replace(array('.',','), '', $number),"0");
        return $this->setMoney($number, $moeda='BRL');
    }

    public function add($money){
        $this->setMoney(($this->getMoney())->add($money->getMoney())->getAmount());
        return $this;
    }

    public function subtract($money){
        $this->setMoney(($this->getMoney())->subtract($money->getMoney())->getAmount());
        return $this;
    }

    public function multiply($money){
        $this->setMoney(($this->getMoney())->multiply($money)->getAmount());
        return $this;
    }

    public function divide($parcela){
        $this->setMoney(($this->getMoney())->divide($parcela)->getAmount());
        return $this;
    }

    public function symmetrical(){
        $this->setMoney(($this->getMoney())->multiply(-1)->getAmount());
        return $this;
    }

    public function allocateTo($parcela){
        $parcelas=($this->getMoney())->allocateTo($parcela);
        $parcelasMonetary=array();
        if(count($parcela)>0){
            foreach ($parcelas as $key => $value) {
                $parcelasMonetary[] = new Monetary($value->getAmount());
            }
        }

        return $parcelasMonetary;
    }

    public function percentage($pct){

        $money = round($this->getMoney()->getAmount()+(($this->getMoney()->getAmount()/100)*((float)$pct)));
        $money = $this->setMoney($money);
        return $this->getMoney();
    }

    public function moneyDb(){
        $number=($this->getMoney()->getAmount());
        $numberTmp = str_replace('-', '', $number);
        if(strlen($numberTmp) >= 2){
            $number=substr($number, 0, -2).'.'.str_pad(substr($number,-2), 2, '0', STR_PAD_LEFT);
        }else{
            $number=substr($number, 0, -1).'.'.str_pad(substr($number,-1), 2, '0', STR_PAD_LEFT);
        }

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