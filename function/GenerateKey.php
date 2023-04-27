<?php

namespace Api\WebApi; 

class GenerateKey
{
    
    public function findRandom() {
        $mRandom = rand(48, 122);
        return $mRandom;
    }
    
    
    public function isRandomInRange($mRandom) {
        if(($mRandom >=58 && $mRandom <= 64) ||(($mRandom >=91 && $mRandom <= 96))) {
            return 0;
        } else {
            return $mRandom;
        }
    }
    public static function thisRandom ($char='')
    {
        
        $output ='';
        for($loop = 0; $loop <= 31; $loop++) 
        {
                for($isRandomInRange = 0; $isRandomInRange === 0;)
                {
                    $isRandomInRange = self::isRandomInRange(self::findRandom());
                }
            $output .= html_entity_decode('&#' . $isRandomInRange . ';');
        }

        echo  $output;
        
    }
    
}

