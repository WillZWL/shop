<?php

include "my_plugin_validator_abstract.php";

class Postal_validator extends My_plugin_validator_abstract
{
    public function is_valid($value_array)
    {
        $LangCountryPair = $value_array["LangCountryPair"];
        $PostalCode = $value_array["PostalCode"];

        if (stripos($LangCountryPair, "_") !== false)
        {
            $CountryID = explode("_", $LangCountryPair);
            $CountryID = $CountryID[1];
        }
        else
            $CountryID = $LangCountryPair;

        $CountryID = strtoupper($CountryID);
        $PostalCode = strtoupper($PostalCode);
        
        $postal['AX'] = '/\b[0-9]{5}\b/';
        $postal['AL'] = '/\b[0-9]{4}\b/';
        $postal['DZ'] = '/\b[0-9]{5}\b/';
        $postal['AD'] = '/\b(AD)[0-9]{3}\b/';
        $postal['AR'] = '/\b[A-Z][0-9]{4}\b/';
        $postal['AM'] = '/\b[0-9]{4}\b/';
        $postal['AC'] = '/\b(ASCN 1ZZ)\b/';
        $postal['AU'] = '/\b[0-9]{4}\b/';
        $postal['AT'] = '/\b[0-9]{4}\b/';
        $postal['AZ'] = '/\b(AZ)[0-9]{4}\b/';
        $postal['BD'] = '/\b[0-9]{4}\b/';
        $postal['BB'] = '/\b(BB)[0-9]{5}\b/';
        $postal['BY'] = '/\b[0-9]{6}\b/';
        $postal['BE'] = '/\b[0-9]{4}\b/';
        $postal['BR'] = '/\b[0-9]{5}([-]{0,1}[0-9]{3}){0,1}\b/';
        $postal['BR'] = '/\b[0-9]{5}\b/';
        $postal['IO'] = '/\b(BIQQ 1ZZ)\b/';
        $postal['VG'] = '/\b(VG)[0-9]{4}\b/';
        $postal['BN'] = '/\b[A-Z]{2}[0-9]{4}\b/';
        $postal['BG'] = '/\b[0-9]{4}\b/';
        $postal['KH'] = '/\b[0-9]{5}\b/';
        $postal['CV'] = '/\b[0-9]{4}\b/';
        $postal['CL'] = '/\b[0-9]{3}[-]{0,1}[0-9]{4}\b/';
        $postal['CN'] = '/\b[0-9]{6}\b/';

        // teik: change postal code requirement from 32dddd to dddddd for columbia
        //      postal['CO'] = /\b(32)[0-9]{4}\b/;
        $postal['CO'] = '/\b[0-9]{6}\b/';
        $postal['CR'] = '/\b[0-9]{5}\b/';
        $postal['HR'] = '/\b[0-9]{5}\b/';
        $postal['CY'] = '/\b[0-9]{4}\b/';
        $postal['CZ'] = '/\b[0-9]{3}( ){0,1}[0-9]{2}\b/';
        $postal['DK'] = '/\b[0-9]{4}\b/';
        $postal['EC'] = '/\b(EC)[0-9]{6}\b/';
        $postal['EE'] = '/\b[0-9]{5}\b/';
        $postal['FK'] = '/\b(BIQQ 1ZZ)\b/';
        $postal['FI'] = '/\b[0-9]{5}\b/';
        $postal['FR'] = '/\b[0-9]{5}\b/';
        $postal['GE'] = '/\b[0-9]{4}\b/';
        $postal['DE'] = '/\b[0-9]{4}\b/';
        $postal['DE'] = '/\b[0-9]{5}\b/';
        $postal['GR'] = '/\b[0-9]{5}\b/';
        $postal['GG'] = '/\b(GG)[0-9][0-9][A-Z]{2}\b/';
        $postal['HU'] = '/\b[0-9]{4}\b/';
        $postal['IS'] = '/\b[0-9]{3}\b/';
        $postal['IN'] = '/\b[0-9]{3}( ){0,1}[0-9]{3}\b/';
        $postal['ID'] = '/\b[0-9]{5}\b/';
        $postal['IR'] = '/\b[0-9]{5}(-)[0-9]{5}\b/';
        $postal['IQ'] = '/\b[0-9]{5}\b/';
        $postal['IL'] = '/\b[0-9]{5}\b/';
        $postal['IT'] = '/\b[0-9]{5}\b/';
        $postal['JP'] = '/\b[0-9]{3}[-]{0,1}[0-9]{4}\b/';
        $postal['JE'] = '/\b(JE)[0-9][0-9][A-Z]{2}\b/';
        $postal['KZ'] = '/\b[0-9]{6}\b/';
        $postal['LV'] = '/\b(LV-)[0-9]{4}\b/';
        $postal['LI'] = '/\b[0-9]{4}\b/';
        $postal['LT'] = '/\b[0-9]{5}\b/';
        $postal['LU'] = '/\b[0-9]{4}\b/';
        $postal['MY'] = '/\b[0-9]{5}\b/';
        $postal['MT'] = '/\b[A-Z]{3}[0-9]{4}\b/';
        $postal['MX'] = '/\b[0-9]{5}\b/';
        $postal['MD'] = '/\b(MD)(-){0,1}[0-9]{4}\b/';
        $postal['MC'] = '/\b(980)[0-9]{2}\b/';
        $postal['ME'] = '/\b[0-9]{5}\b/';
        $postal['MA'] = '/\b[0-9]{5}\b/';
        $postal['NL'] = '/\b[0-9]{4}( ){0,1}[A-Z]{2}\b/';
        $postal['NZ'] = '/\b[0-9]{4}\b/';
        $postal['NI'] = '/\b[0-9]{6}\b/';
        $postal['NO'] = '/\b[0-9]{4}\b/';
        $postal['PA'] = '/\b[0-9]{6}\b/';
        $postal['PH'] = '/\b[0-9]{4}\b/';
        $postal['PN'] = '/\b(PCRN 1ZZ)\b/';
        $postal['PL'] = '/\b[0-9]{2}[-]{0,1}[0-9]{3}\b/';
        $postal['PT'] = '/\b[0-9]{4}[ -]{0,1}[0-9]{3}\b/';
        $postal['PR'] = '/\b[0-9]{5}\b/';
        $postal['RO'] = '/\b[0-9]{6}\b/';
        $postal['RU'] = '/\b[0-9]{6}\b/';
        $postal['SM'] = '/\b[0-9]{5}\b/';
        $postal['RS'] = '/\b[0-9]{5}\b/';
        $postal['SG'] = '/\b[0-9]{6}\b/';
        $postal['SK'] = '/\b[0-9]{3}( ){0,1}[0-9]{2}\b/';
        $postal['SI'] = '/\b(SI)(-){0,1}[0-9]{4}\b/';
        $postal['ZA'] = '/\b[0-9]{4}\b/';
        $postal['GS'] = '/\b(SIQQ 1ZZ)\b/';
        $postal['KR'] = '/\b[0-9]{3}(-){0,1}[0-9]{3}\b/';
        $postal['ES'] = '/\b[0-9]{5}\b/';
        $postal['LK'] = '/\b[0-9]{5}\b/';
        $postal['SE'] = '/\b[0-9]{3}( ){0,1}[0-9]{2}\b/';
        $postal['CH'] = '/\b[0-9]{4}\b/';
        $postal['TW'] = '/\b[0-9]{5}\b/';
        $postal['TH'] = '/\b[0-9]{5}\b/';
        $postal['TR'] = '/\b[0-9]{5}\b/';
        $postal['TC'] = '/\b[0-9]{5}\b/';
        $postal['UA'] = '/\b[0-9]{5}\b/';
        $postal['VA'] = '/\b[0-9]{5}\b/';
        $postal['VN'] = '/\b[0-9]{6}\b/';   

        $postal['IM'] = '/\b(IM)[0-9]{1,2}[0-9][A-Z]{2}\b/';
        $postal['CA'] = '/\b[ABCEGHJKLMNPRSTVXY][0-9][A-Z][0-9][A-Z][0-9]\b/';
        $postal['GB'] = '/\b([A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]|[A-HK-Y][0-9]([0-9]|[ABEHMNPRV-Y]))|[0-9][A-HJKS-UW])[0-9][ABD-HJLNP-UW-Z]{2}|(GIR\ 0AA)|(SAN\ TA1)|(BFPO\ (C\/O\ )?[0-9]{1,4})|((ASCN|BBND|[BFS]IQQ|PCRN|STHL|TDCU|TKCA)\ 1ZZ))\b/';
        $postal['US'] = '/\b[0-9]{5}(?:-[0-9]{4})?\b/';

        // Rules
        // 1. country blank,  postalcode blank  - fail, no country
        // 2. country blank,  postalcode filled - fail, no country
        // 3. country filled, postalcode blank  - depends on regex
        // 4. country filled, postalcode filled - depends on regex
        
        // if no country was passed in, we fail the check, rule 1 and 2
        if ($CountryID == "") return false;
        
        // if we cannot find the country, regex will be empty and we will pass for all cases
        $regex = $postal[$CountryID];
        
        // strip all the spaces from PostalCode
        $PostalCode = preg_replace("/ /",'', $PostalCode);

        
        // assume we pass until we fail
        $retcode = true;
        if ($PostalCode != "")
        {
            // rule 4
            if (!preg_match($regex, $PostalCode, $matches))
            {
                // postal code has failed the regex
                $retcode = false;               
            }
        }
        else
        {           
            // if regex is not empty and postal code is empty, we must fail
            // rule 3
            if ($regex != "") $retcode = false;
        }
        
        $debug_output = false;
        if ($debug_output)
        {
            // if ($ExpectedResult == 0)
                // if ($retcode != false)
                    // alert(PostalCode + "\r\nCountry: " + CountryID + ", Regex: " + regex + "\r\n\r\nDid not meet expected result of FALSE");
            // 
            // if ($ExpectedResult != 0)
                // if ($retcode != true)
                    // alert(PostalCode + "\r\nCountry: " + CountryID + ", Regex: " + regex + "\r\n\r\nDid not meet expected result of TRUE");
                    // 
            // alert("End of IsValidPostal\r\nPostalCode : " + PostalCode + "\r\nCountry: " + CountryID);
        }
        
        /*
        // test cases supposed to pass
        isValidPostalCode("GB", "E16 1SW")
        isValidPostalCode("GB", "E161SW")
        isValidPostalCode("GB", "E161SW ")
        isValidPostalCode("GB", "E161SW  ")
        isValidPostalCode("GB", " E161SW")
        isValidPostalCode("GB", "E1 61SW")
        isValidPostalCode("US", "12345")
        isValidPostalCode("PT", "7777-777",1)
        isValidPostalCode("KR", "999999",1)
        isValidPostalCode("KR", "999-999",1)
        isValidPostalCode("KR", "999 999",1)
        isValidPostalCode("IM", "IM 2 2 2 A A",1)
        isValidPostalCode("IM", "IM 2 2 A A",1)
        
        isValidPostalCode("PT", "777-7777",0)
        isValidPostalCode("CA", "A1A 2A2",1)
        isValidPostalCode("CA", "A1A2A2 ",1)
        isValidPostalCode("CA", "A1A2A 2 ",1)

        isValidPostalCode("IM", "IM222 AA",1)
        isValidPostalCode("IM", "IM22 AA",1)
        isValidPostalCode("IM", "IM222AA",1)
        isValidPostalCode("IM", "IM22AA",1)
        isValidPostalCode("IM", "IM2 2 2 AA",1)
        isValidPostalCode("IM", "IM22 AA",1)
        isValidPostalCode("IM", "IM22 2AA",1)
        isValidPostalCode("IM", "IM22 AA",1)
        isValidPostalCode("IM", "IM 2 2 2 A A",1)
        isValidPostalCode("IM", "IM 2 2 A A",1)
        
        // test cases supposed to fail
        isValidPostalCode("SG", "This message must pop up", 1)
        */
            
        return $retcode;        
    }       
}

?>
