<?php
namespace STPH\addressAutoComplete;

if (!class_exists("source")) require_once( __DIR__ . "/../classes/source.class.php" );

class services_api_esdc_edsc_canada_ca extends source {

    public function mapAddress($value)
{
    $occupation = new Address;

    $occupation->value = $value->occupationTitle;;

    $occupation->label = $value->occupationTitle;

    // Champs avancés pour ajouter automatiquement street (libéllé) et le number (le code à 5 chiffres)
    $occupation->parts->street = $value->occupationCategoryText;
    $occupation->parts->number = $value->occupationCategoryCode;
    $occupation->parts->keyword = $value->searchTerm;

    return $occupation;
}
     

}
    
