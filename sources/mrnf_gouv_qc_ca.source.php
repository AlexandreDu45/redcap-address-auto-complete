<?php
namespace STPH\addressAutoComplete;

if (!class_exists("source")) require_once( __DIR__ . "/../classes/source.class.php" );

class mrnf_gouv_qc_ca extends source {

    public function mapAddress($value) {
    
        $address = new Address;
        
        //  Set label and value to given address
        $address->label = $value->text;
        $address->value = $value->text;
        $address->meta->id = $value->magicKey;

        return $address;       

    }
    
}
