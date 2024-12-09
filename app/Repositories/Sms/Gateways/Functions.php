<?php

namespace App\Repositories\Sms\Gateways;

trait Functions
{

    public function convertToUnicode($string)
    {
        $returnString = "";
        $string = stripcslashes($string);
        $encode = mb_detect_encoding($string);
        $string = mb_convert_encoding($string, 'UTF-16', $encode);
        for ($i = 0; $i < strlen($string); $i++) {
            $finalText = strtoupper(bin2hex($string[$i]));
            $returnString .= $finalText;
        }
        return $returnString;
    }

    public function convertNumbers($number)
    {
        $easternArabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $westernArabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        return str_replace($easternArabic, $westernArabic, $number);
    }

}
