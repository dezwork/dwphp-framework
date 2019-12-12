<?php

namespace util;

class HumanizeBR{


    const DICTIONARY  = array(
            0                   => 'zero',
            1                   => 'um',
            2                   => 'dois',
            3                   => 'três',
            4                   => 'quatro',
            5                   => 'cinco',
            6                   => 'seis',
            7                   => 'sete',
            8                   => 'oito',
            9                   => 'nove',
            10                  => 'dez',
            11                  => 'onze',
            12                  => 'doze',
            13                  => 'treze',
            14                  => 'quatorze',
            15                  => 'quinze',
            16                  => 'dezesseis',
            17                  => 'dezessete',
            18                  => 'dezoito',
            19                  => 'dezenove',
            20                  => 'vinte',
            30                  => 'trinta',
            40                  => 'quarenta',
            50                  => 'cinquenta',
            60                  => 'sessenta',
            70                  => 'setenta',
            80                  => 'oitenta',
            90                  => 'noventa',
            100                 => array('cem', 'cento'),
            200                 => 'duzentos',
            300                 => 'trezentos',
            400                 => 'quatrocentos',
            500                 => 'quinhentos',
            600                 => 'seiscentos',
            700                 => 'setecentos',
            800                 => 'oitocentos',
            900                 => 'novecentos',
            1000                => 'mil',
            1000000             => array('milhão', 'milhões'),
            1000000000          => array('bilhão', 'bilhões'),
            1000000000000       => array('trilhão', 'trilhões'),
            1000000000000000    => array('quatrilhão', 'quatrilhões'),
            1000000000000000000 => array('quinquilhão', 'quinquilhões')
    );


    /**
     * Converts a unix timestamp to a relative time string, such as "3 days ago"
     * or "2 weeks ago".
     *
     * @param  int    number
     * @return string
     */
    public static function numberToWords($number, $measurement='') {
        if(strpos($number, ',')!== false) {
            $number = str_replace(',', '.', $number);
        }
        if (!is_numeric($number)) {
            return false;
        }

        if ($number > PHP_INT_MAX) {
            // overflow
            return false;
        }

        if ($number < 0) {
            return 'menos ' . HumanizeBR::numberToWords(abs($number));
        }

        $string = $fraction = null;

        if(strpos($number, '.')!== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = HumanizeBR::DICTIONARY[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = HumanizeBR::DICTIONARY[$tens];
                if ($units) {
                    $string .= ' e ' . HumanizeBR::DICTIONARY[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = floor($number / 100)*100;
                $remainder = $number % 100;

                if($number==100){
                    $string = HumanizeBR::DICTIONARY[$hundreds][0];
                }else if($number<200){
                    $string = HumanizeBR::DICTIONARY[$hundreds][1];
                }else{
                    $string = HumanizeBR::DICTIONARY[$hundreds];
                }

                if($remainder){
                    $string .= ' e ' . HumanizeBR::numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                if($baseUnit == 1000) {
                    $string = HumanizeBR::numberToWords($numBaseUnits) . ' ' . HumanizeBR::DICTIONARY[1000];
                }elseif($numBaseUnits == 1) {
                    $string = HumanizeBR::numberToWords($numBaseUnits) . ' ' . HumanizeBR::DICTIONARY[$baseUnit][0];
                }else{
                    $string = HumanizeBR::numberToWords($numBaseUnits) . ' ' . HumanizeBR::DICTIONARY[$baseUnit][1];
                }
                if($remainder){
                    $string .= $remainder < 100 ? ' e ' : ', ';
                    $string .= HumanizeBR::numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= ' ponto ';
            $string .= HumanizeBR::numberToWords($fraction);
        }

        return $string.($measurement!=''?' '. $measurement : '' );
    }

    /**
     * Converts a unix timestamp to a relative time string, such as "3 days ago"
     * or "2 weeks ago".
     *
     * @param  int    $from   The date to use as a starting point
     * @param  int    $to     The date to compare to, defaults to now
     * @param  string $suffix The string to add to the end, defaults to " ago"
     * @return string
     */
    public static function humanTimeDiff($from, $to = '', $as_text = false, $suffix = ' atrás'){
        if ($to == '') {
            $to = time();
        }
        $from = new \DateTime(date('Y-m-d H:i:s', $from));
        $to   = new \DateTime(date('Y-m-d H:i:s', $to));
        $diff = $from->diff($to);

        if ($diff->y > 1) {
            $text = $diff->y . ' anos';
        } elseif ($diff->y == 1) {
            $text = '1 ano';
        } elseif ($diff->m > 1) {
            $text = $diff->m . ' meses';
        } elseif ($diff->m == 1) {
            $text = '1 mês';
        } elseif ($diff->d > 7) {
            $text = ceil($diff->d / 7) . ' semanas';
        } elseif ($diff->d == 7) {
            $text = '1 semana';
        } elseif ($diff->d > 1) {
            $text = $diff->d . ' dias';
        } elseif ($diff->d == 1) {
            $text = '1 dia';
        } elseif ($diff->h > 1) {
            $text = $diff->h . ' horas';
        } elseif ($diff->h == 1) {
            $text = ' 1 hora';
        } elseif ($diff->i > 1) {
            $text = $diff->i . ' minutos';
        } elseif ($diff->i == 1) {
            $text = '1 minuto';
        } elseif ($diff->s > 1) {
            $text = $diff->s . ' segundos';
        } else {
            $text = '1 segundo';
        }
        if ($as_text) {
            $text = explode(' ', $text, 2);
            $text = HumanizeBR::numberToWords($text[0]) . ' ' . $text[1];
        }
        return trim($text) . $suffix;
    }


}