<?php
function ParseXML_new($bibitResult, $reply)
{
    global $replyArray;
    $replyArray = $reply;
  $xml_parser = xml_parser_create();
  // set callback functions
  xml_set_element_handler($xml_parser, "startElement_new", "endElement_new");
  xml_set_character_data_handler($xml_parser, "characterData_new");
  if (!xml_parse($xml_parser, $bibitResult))
  {
    die(sprintf("XML error: %s at line %d",
        xml_error_string(xml_get_error_code($xml_parser)),
        xml_get_current_line_number($xml_parser)));
  }
  // clean up
  xml_parser_free($xml_parser);
  return $replyArray;
}

function StartElement_new($parser, $name, $attrs)
{
  global $replyArray;
  $replyArray['currentTag'] = $name;

  switch ($name)
  {
      case "CVCRESULTCODE":
      $replyArray['cvc_desc'] = $attrs['DESCRIPTION'];
      break;
      case "RISKSCORE":
      $replyArray['riskscore'] = $attrs['VALUE'];
      break;
      case "ISO8583RETURNCODE":
      $replyArray['refuse_code'] = $attrs['CODE'];
      $replyArray['refuse_desc'] = $attrs['DESCRIPTION'];
      break;
      case "AVSRESULTCODE":
      $replyArray['avs_desc'] = $attrs['DESCRIPTION'];
      break;
      case "ERROR":
      $replyArray['error_code'] = $attrs['CODE']; //example of how to catch the error code number (i.e. 1 to 7)
      break;
      case "CANCELRECEIVED":
      $replyArray['order_cancelled'] = $attrs['ORDERCODE'];
      break;
      case "REQUEST3DSECURE":
      $replyArray['check_3d'] = true;
      break;
      default:
      break;
  }

}


function EndElement_new($parser, $name)
{
  global $replyArray;
  $replyArray['currentTag'] = "";
}


function CharacterData_new($parser, $result)
{
  global $replyArray;
  switch ($replyArray['currentTag'])
  {
      case "LASTEVENT":
      $replyArray['cc_result'] = $result;
      break;
      case "ERROR":
      $replyArray['cc_result'] = "ERROR";
      $replyArray['error_desc'] = $result;
      break;
      case "PAREQUEST":
      $replyArray['parequest'] = $result;
      break;
      case "ISSUERURL":
      $replyArray['issuerurl'] = $result;
      break;
      case "ECHODATA":
      $replyArray['echodata'] = $result;
      break;
      default:
      break;
  }
}

