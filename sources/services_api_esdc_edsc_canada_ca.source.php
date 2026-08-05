<?php
namespace STPH\addressAutoComplete;

if (!class_exists("source")) require_once( __DIR__ . "/../classes/source.class.php" );

class services_api_esdc_edsc_canada_ca extends source {

    public function mapAddress($value) {

        $occupation = new Address;
        
        //  Set label and value to given address
        $code = $value->occupationCategoryCode->occupationCategoryCode;

        $occupationText = $value->occupationCategoryText[1]->value;

        /*
        $titleText = $value->occupationCategoryTitleList[0]
          ->occupationCategoryTitle[0]
          ->occupationCategoryTitleText
          ->value;
          */
        $titles = [];

        foreach ($value->occupationCategoryTitleList as $titleList) {
            foreach ($titleList->occupationCategoryTitle as $title) {
                if ($title->occupationCategoryTitleText->lang === 'fr') {
                    $titles[] = $title->occupationCategoryTitleText->value;
                }
            }
        }

        $titleText = implode(' | ', $titles);

        error_log("----------------------------");
        error_log(print_r($value, true));
        error_log("----------------------------");
        
        
        $occupation->label = $occupationText . " - " . $titleText." (" . $code .")";
        $occupation->value = $occupationText . " - " . $titleText." (" . $code .")";

        return $occupation;


    }
     

}
    
