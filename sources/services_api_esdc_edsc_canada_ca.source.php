<?php
namespace STPH\addressAutoComplete;

if (!class_exists("source")) require_once( __DIR__ . "/../classes/source.class.php" );

class services_api_esdc_edsc_canada_ca extends source {

    public function mapAddress($value)
{
    $occupation = new Address;

    $occupation->value =
        $value->occupationCategoryText .
        " - " .
        $value->occupationTitle .
        " (" .
        $value->occupationCategoryCode .
        ")";

    $occupation->label = $value->occupationTitle;

    return $occupation;
}
     

}
    
