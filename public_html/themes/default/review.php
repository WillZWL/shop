<?php $this->load->view('/default/header') ?>
<!-- header -->
<div id="review_order" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Shopping Cart') ?></h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center"><?= _('Image') ?></td>
                            <td class="text-left"><?= _('Product Name') ?></td>
                            <td class="text-left"><?= _('Quantity') ?></td>
                            <td class="text-right"><?= _('Unit Price') ?></td>
                            <td class="text-right"><?= _('Total') ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_info['item'] as $sku => $item): ?>
                        <tr>
                            <td class="text-center">
                                <a href="#"><img src="<?= get_image_file($item->get_image(), "m", $sku) ?>" alt="<?= $item->get_prod_name() ?>" title="<?= $item->get_prod_name() ?>" class="img-thumbnail"></a>
                            </td>
                            <td class="text-left"><a href="#"><?= $item->get_prod_name() ?></a>
                            </td>
                            <td class="text-left">
                                <div class="input-group btn-block" style="max-width: 200px;">
                                    <input type="text" name="quantity[YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjMzO30=]" value="1" size="1" class="form-control">
                                    <span class="input-group-btn">
                                        <button type="submit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Update"><i class="fa fa-refresh"></i></button>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-primary" onclick="cart.remove('YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjMzO30=');" data-original-title="Remove"><i class="fa fa-times-circle"></i></button>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right"><?= $item->get_price() ?></td>
                            <td class="text-right"><?= $item->get_price() ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <?= _('Shipping Information') ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <form action="#" method="POST" role="form">
                            <legend><?= _('Where would you like to deliver to ?') ?></legend>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-2">
                                    <div class="form-group">
                                        <label for=""><?= _('Country') ?></label>
                                        <select name="country" id="inputCountry" class="form-control input-sm" required="required">
                                            <option value=""><?= _(' --- Please Select --- ') ?></option>
                                            <option value="244"><?= _('Aaland Islands') ?></option>
                                            <option value="1"><?= _('Afghanistan') ?></option>
                                            <option value="2"><?= _('Albania') ?></option>
                                            <option value="3"><?= _('Algeria') ?></option>
                                            <option value="4"><?= _('American Samoa') ?></option>
                                            <option value="5"><?= _('Andorra') ?></option>
                                            <option value="6"><?= _('Angola') ?></option>
                                            <option value="7"><?= _('Anguilla') ?></option>
                                            <option value="8"><?= _('Antarctica') ?></option>
                                            <option value="9"><?= _('Antigua and Barbuda') ?></option>
                                            <option value="10"><?= _('Argentina') ?></option>
                                            <option value="11"><?= _('Armenia') ?></option>
                                            <option value="12"><?= _('Aruba') ?></option>
                                            <option value="252"><?= _('Ascension Island (British)') ?></option>
                                            <option value="13"><?= _('Australia') ?></option>
                                            <option value="14"><?= _('Austria') ?></option>
                                            <option value="15"><?= _('Azerbaijan') ?></option>
                                            <option value="16"><?= _('Bahamas') ?></option>
                                            <option value="17"><?= _('Bahrain') ?></option>
                                            <option value="18"><?= _('Bangladesh') ?></option>
                                            <option value="19"><?= _('Barbados') ?></option>
                                            <option value="20"><?= _('Belarus') ?></option>
                                            <option value="21"><?= _('Belgium') ?></option>
                                            <option value="22"><?= _('Belize') ?></option>
                                            <option value="23"><?= _('Benin') ?></option>
                                            <option value="24"><?= _('Bermuda') ?></option>
                                            <option value="25"><?= _('Bhutan') ?></option>
                                            <option value="26"><?= _('Bolivia') ?></option>
                                            <option value="245"><?= _('Bonaire, Sint Eustatius and Saba') ?></option>
                                            <option value="27"><?= _('Bosnia and Herzegovina') ?></option>
                                            <option value="28"><?= _('Botswana') ?></option>
                                            <option value="29"><?= _('Bouvet Island') ?></option>
                                            <option value="30"><?= _('Brazil') ?></option>
                                            <option value="31"><?= _('British Indian Ocean Territory') ?></option>
                                            <option value="32"><?= _('Brunei Darussalam') ?></option>
                                            <option value="33"><?= _('Bulgaria') ?></option>
                                            <option value="34"><?= _('Burkina Faso') ?></option>
                                            <option value="35"><?= _('Burundi') ?></option>
                                            <option value="36"><?= _('Cambodia') ?></option>
                                            <option value="37"><?= _('Cameroon') ?></option>
                                            <option value="38"><?= _('Canada') ?></option>
                                            <option value="251"><?= _('Canary Islands') ?></option>
                                            <option value="39"><?= _('Cape Verde') ?></option>
                                            <option value="40"><?= _('Cayman Islands') ?></option>
                                            <option value="41"><?= _('Central African Republic') ?></option>
                                            <option value="42"><?= _('Chad') ?></option>
                                            <option value="43"><?= _('Chile') ?></option>
                                            <option value="44"><?= _('China') ?></option>
                                            <option value="45"><?= _('Christmas Island') ?></option>
                                            <option value="46"><?= _('Cocos (Keeling) Islands') ?></option>
                                            <option value="47"><?= _('Colombia') ?></option>
                                            <option value="48"><?= _('Comoros') ?></option>
                                            <option value="49"><?= _('Congo') ?></option>
                                            <option value="50"><?= _('Cook Islands') ?></option>
                                            <option value="51"><?= _('Costa Rica') ?></option>
                                            <option value="52"><?= _("Cote D'Ivoire") ?></option>
                                            <option value="53"><?= _('Croatia') ?></option>
                                            <option value="54"><?= _('Cuba') ?></option>
                                            <option value="246"><?= _('Curacao') ?></option>
                                            <option value="55"><?= _('Cyprus') ?></option>
                                            <option value="56"><?= _('Czech Republic') ?></option>
                                            <option value="237"><?= _('Democratic Republic of Congo') ?></option>
                                            <option value="57"><?= _('Denmark') ?></option>
                                            <option value="58"><?= _('Djibouti') ?></option>
                                            <option value="59"><?= _('Dominica') ?></option>
                                            <option value="60"><?= _('Dominican Republic') ?></option>
                                            <option value="61"><?= _('East Timor') ?></option>
                                            <option value="62"><?= _('Ecuador') ?></option>
                                            <option value="63"><?= _('Egypt') ?></option>
                                            <option value="64"><?= _('El Salvador') ?></option>
                                            <option value="65"><?= _('Equatorial Guinea') ?></option>
                                            <option value="66"><?= _('Eritrea') ?></option>
                                            <option value="67"><?= _('Estonia') ?></option>
                                            <option value="68"><?= _('Ethiopia') ?></option>
                                            <option value="69"><?= _('Falkland Islands (Malvinas)') ?></option>
                                            <option value="70"><?= _('Faroe Islands') ?></option>
                                            <option value="71"><?= _('Fiji') ?></option>
                                            <option value="72"><?= _('Finland') ?></option>
                                            <option value="74"><?= _('France, Metropolitan') ?></option>
                                            <option value="75"><?= _('French Guiana') ?></option>
                                            <option value="76"><?= _('French Polynesia') ?></option>
                                            <option value="77"><?= _('French Southern Territories') ?></option>
                                            <option value="126"><?= _('FYROM') ?></option>
                                            <option value="78"><?= _('Gabon') ?></option>
                                            <option value="79"><?= _('Gambia') ?></option>
                                            <option value="80"><?= _('Georgia') ?></option>
                                            <option value="81"><?= _('Germany') ?></option>
                                            <option value="82"><?= _('Ghana') ?></option>
                                            <option value="83"><?= _('Gibraltar') ?></option>
                                            <option value="84"><?= _('Greece') ?></option>
                                            <option value="85"><?= _('Greenland') ?></option>
                                            <option value="86"><?= _('Grenada') ?></option>
                                            <option value="87"><?= _('Guadeloupe') ?></option>
                                            <option value="88"><?= _('Guam') ?></option>
                                            <option value="89"><?= _('Guatemala') ?></option>
                                            <option value="256"><?= _('Guernsey') ?></option>
                                            <option value="90"><?= _('Guinea') ?></option>
                                            <option value="91"><?= _('Guinea-Bissau') ?></option>
                                            <option value="92"><?= _('Guyana') ?></option>
                                            <option value="93"><?= _('Haiti') ?></option>
                                            <option value="94"><?= _('Heard and Mc Donald Islands') ?></option>
                                            <option value="95"><?= _('Honduras') ?></option>
                                            <option value="96"><?= _('Hong Kong') ?></option>
                                            <option value="97"><?= _('Hungary') ?></option>
                                            <option value="98"><?= _('Iceland') ?></option>
                                            <option value="99"><?= _('India') ?></option>
                                            <option value="100"><?= _('Indonesia') ?></option>
                                            <option value="101"><?= _('Iran (Islamic Republic of)') ?></option>
                                            <option value="102"><?= _('Iraq') ?></option>
                                            <option value="103"><?= _('Ireland') ?></option>
                                            <option value="254"><?= _('Isle of Man') ?></option>
                                            <option value="104"><?= _('Israel') ?></option>
                                            <option value="105"><?= _('Italy') ?></option>
                                            <option value="106"><?= _('Jamaica') ?></option>
                                            <option value="107"><?= _('Japan') ?></option>
                                            <option value="257"><?= _('Jersey') ?></option>
                                            <option value="108"><?= _('Jordan') ?></option>
                                            <option value="109"><?= _('Kazakhstan') ?></option>
                                            <option value="110"><?= _('Kenya') ?></option>
                                            <option value="111"><?= _('Kiribati') ?></option>
                                            <option value="113"><?= _('Korea, Republic of') ?></option>
                                            <option value="253"><?= _('Kosovo, Republic of') ?></option>
                                            <option value="114"><?= _('Kuwait') ?></option>
                                            <option value="115"><?= _('Kyrgyzstan') ?></option>
                                            <option value="116"><?= _("Lao People's Democratic Republic") ?></option>
                                            <option value="117"><?= _('Latvia') ?></option>
                                            <option value="118"><?= _('Lebanon') ?></option>
                                            <option value="119"><?= _('Lesotho') ?></option>
                                            <option value="120"><?= _('Liberia') ?></option>
                                            <option value="121"><?= _('Libyan Arab Jamahiriya') ?></option>
                                            <option value="122"><?= _('Liechtenstein') ?></option>
                                            <option value="123"><?= _('Lithuania') ?></option>
                                            <option value="124"><?= _('Luxembourg') ?></option>
                                            <option value="125"><?= _('Macau') ?></option>
                                            <option value="127"><?= _('Madagascar') ?></option>
                                            <option value="128"><?= _('Malawi') ?></option>
                                            <option value="129"><?= _('Malaysia') ?></option>
                                            <option value="130"><?= _('Maldives') ?></option>
                                            <option value="131"><?= _('Mali') ?></option>
                                            <option value="132"><?= _('Malta') ?></option>
                                            <option value="133"><?= _('Marshall Islands') ?></option>
                                            <option value="134"><?= _('Martinique') ?></option>
                                            <option value="135"><?= _('Mauritania') ?></option>
                                            <option value="136"><?= _('Mauritius') ?></option>
                                            <option value="137"><?= _('Mayotte') ?></option>
                                            <option value="138"><?= _('Mexico') ?></option>
                                            <option value="139"><?= _('Micronesia, Federated States of') ?></option>
                                            <option value="140"><?= _('Moldova, Republic of') ?></option>
                                            <option value="141"><?= _('Monaco') ?></option>
                                            <option value="142"><?= _('Mongolia') ?></option>
                                            <option value="242"><?= _('Montenegro') ?></option>
                                            <option value="143"><?= _('Montserrat') ?></option>
                                            <option value="144"><?= _('Morocco') ?></option>
                                            <option value="145"><?= _('Mozambique') ?></option>
                                            <option value="146"><?= _('Myanmar') ?></option>
                                            <option value="147"><?= _('Namibia') ?></option>
                                            <option value="148"><?= _('Nauru') ?></option>
                                            <option value="149"><?= _('Nepal') ?></option>
                                            <option value="150"><?= _('Netherlands') ?></option>
                                            <option value="151"><?= _('Netherlands Antilles') ?></option>
                                            <option value="152"><?= _('New Caledonia') ?></option>
                                            <option value="153"><?= _('New Zealand') ?></option>
                                            <option value="154"><?= _('Nicaragua') ?></option>
                                            <option value="155"><?= _('Niger') ?></option>
                                            <option value="156"><?= _('Nigeria') ?></option>
                                            <option value="157"><?= _('Niue') ?></option>
                                            <option value="158"><?= _('Norfolk Island') ?></option>
                                            <option value="112"><?= _('North Korea') ?></option>
                                            <option value="159"><?= _('Northern Mariana Islands') ?></option>
                                            <option value="160"><?= _('Norway') ?></option>
                                            <option value="161"><?= _('Oman') ?></option>
                                            <option value="162"><?= _('Pakistan') ?></option>
                                            <option value="163"><?= _('Palau') ?></option>
                                            <option value="247"><?= _('Palestinian Territory, Occupied') ?></option>
                                            <option value="164"><?= _('Panama') ?></option>
                                            <option value="165"><?= _('Papua New Guinea') ?></option>
                                            <option value="166"><?= _('Paraguay') ?></option>
                                            <option value="167"><?= _('Peru') ?></option>
                                            <option value="168"><?= _('Philippines') ?></option>
                                            <option value="169"><?= _('Pitcairn') ?></option>
                                            <option value="170"><?= _('Poland') ?></option>
                                            <option value="171"><?= _('Portugal') ?></option>
                                            <option value="172"><?= _('Puerto Rico') ?></option>
                                            <option value="173"><?= _('Qatar') ?></option>
                                            <option value="174"><?= _('Reunion') ?></option>
                                            <option value="175"><?= _('Romania') ?></option>
                                            <option value="176"><?= _('Russian Federation') ?></option>
                                            <option value="177"><?= _('Rwanda') ?></option>
                                            <option value="178"><?= _('Saint Kitts and Nevis') ?></option>
                                            <option value="179"><?= _('Saint Lucia') ?></option>
                                            <option value="180"><?= _('Saint Vincent and the Grenadines') ?></option>
                                            <option value="181"><?= _('Samoa') ?></option>
                                            <option value="182"><?= _('San Marino') ?></option>
                                            <option value="183"><?= _('Sao Tome and Principe') ?></option>
                                            <option value="184"><?= _('Saudi Arabia') ?></option>
                                            <option value="185"><?= _('Senegal') ?></option>
                                            <option value="243"><?= _('Serbia') ?></option>
                                            <option value="186"><?= _('Seychelles') ?></option>
                                            <option value="187"><?= _('Sierra Leone') ?></option>
                                            <option value="188"><?= _('Singapore') ?></option>
                                            <option value="189"><?= _('Slovak Republic') ?></option>
                                            <option value="190"><?= _('Slovenia') ?></option>
                                            <option value="191"><?= _('Solomon Islands') ?></option>
                                            <option value="192"><?= _('Somalia') ?></option>
                                            <option value="193"><?= _('South Africa') ?></option>
                                            <option value="194"><?= _('South Georgia &amp; South Sandwich Islands') ?></option>
                                            <option value="248"><?= _('South Sudan') ?></option>
                                            <option value="195"><?= _('Spain') ?></option>
                                            <option value="196"><?= _('Sri Lanka') ?></option>
                                            <option value="249"><?= _('St. Barthelemy') ?></option>
                                            <option value="197"><?= _('St. Helena') ?></option>
                                            <option value="250"><?= _('St. Martin (French part)') ?></option>
                                            <option value="198"><?= _('St. Pierre and Miquelon') ?></option>
                                            <option value="199"><?= _('Sudan') ?></option>
                                            <option value="200"><?= _('Suriname') ?></option>
                                            <option value="201"><?= _('Svalbard and Jan Mayen Islands') ?></option>
                                            <option value="202"><?= _('Swaziland') ?></option>
                                            <option value="203"><?= _('Sweden') ?></option>
                                            <option value="204"><?= _('Switzerland') ?></option>
                                            <option value="205"><?= _('Syrian Arab Republic') ?></option>
                                            <option value="206"><?= _('Taiwan') ?></option>
                                            <option value="207"><?= _('Tajikistan') ?></option>
                                            <option value="208"><?= _('Tanzania, United Republic of') ?></option>
                                            <option value="209"><?= _('Thailand') ?></option>
                                            <option value="210"><?= _('Togo') ?></option>
                                            <option value="211"><?= _('Tokelau') ?></option>
                                            <option value="212"><?= _('Tonga') ?></option>
                                            <option value="213"><?= _('Trinidad and Tobago') ?></option>
                                            <option value="255"><?= _('Tristan da Cunha') ?></option>
                                            <option value="214"><?= _('Tunisia') ?></option>
                                            <option value="215"><?= _('Turkey') ?></option>
                                            <option value="216"><?= _('Turkmenistan') ?></option>
                                            <option value="217"><?= _('Turks and Caicos Islands') ?></option>
                                            <option value="218"><?= _('Tuvalu') ?></option>
                                            <option value="219"><?= _('Uganda') ?></option>
                                            <option value="220"><?= _('Ukraine') ?></option>
                                            <option value="221"><?= _('United Arab Emirates') ?></option>
                                            <option value="222" selected="selected"><?= _('United Kingdom') ?></option>
                                            <option value="223"><?= _('United States') ?></option>
                                            <option value="224"><?= _('United States Minor Outlying Islands') ?></option>
                                            <option value="225"><?= _('Uruguay') ?></option>
                                            <option value="226"><?= _('Uzbekistan') ?></option>
                                            <option value="227"><?= _('Vanuatu') ?></option>
                                            <option value="228"><?= _('Vatican City State (Holy See)') ?></option>
                                            <option value="229"><?= _('Venezuela') ?></option>
                                            <option value="230"><?= _('Viet Nam') ?></option>
                                            <option value="231"><?= _('Virgin Islands (British)') ?></option>
                                            <option value="232"><?= _('Virgin Islands (U.S.)') ?></option>
                                            <option value="233"><?= _('Wallis and Futuna Islands') ?></option>
                                            <option value="234"><?= _('Western Sahara') ?></option>
                                            <option value="235"><?= _('Yemen') ?></option>
                                            <option value="238"><?= _('Zambia') ?></option>
                                            <option value="239"><?= _('Zimbabwe') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><?= _('State') ?></label>
                                        <select name="state" id="input" class="form-control input-sm" required="required">
                                            <option value=""><?= _(' --- Please Select --- ') ?></option>
                                            <option value="3513"><?= _('Aberdeen') ?></option>
                                            <option value="3514"><?= _('Aberdeenshire') ?></option>
                                            <option value="3515"><?= _('Anglesey') ?></option>
                                            <option value="3516"><?= _('Angus') ?></option>
                                            <option value="3517"><?= _('Argyll and Bute') ?></option>
                                            <option value="3518"><?= _('Bedfordshire') ?></option>
                                            <option value="3519"><?= _('Berkshire') ?></option>
                                            <option value="3520"><?= _('Blaenau Gwent') ?></option>
                                            <option value="3521"><?= _('Bridgend') ?></option>
                                            <option value="3522"><?= _('Bristol') ?></option>
                                            <option value="3523"><?= _('Buckinghamshire') ?></option>
                                            <option value="3524"><?= _('Caerphilly') ?></option>
                                            <option value="3525"><?= _('Cambridgeshire') ?></option>
                                            <option value="3526"><?= _('Cardiff') ?></option>
                                            <option value="3527"><?= _('Carmarthenshire') ?></option>
                                            <option value="3528"><?= _('Ceredigion') ?></option>
                                            <option value="3529"><?= _('Cheshire') ?></option>
                                            <option value="3530"><?= _('Clackmannanshire') ?></option>
                                            <option value="3531"><?= _('Conwy') ?></option>
                                            <option value="3532"><?= _('Cornwall') ?></option>
                                            <option value="3949"><?= _('County Antrim') ?></option>
                                            <option value="3950"><?= _('County Armagh') ?></option>
                                            <option value="3951"><?= _('County Down') ?></option>
                                            <option value="3952"><?= _('County Fermanagh') ?></option>
                                            <option value="3953"><?= _('County Londonderry') ?></option>
                                            <option value="3954"><?= _('County Tyrone') ?></option>
                                            <option value="3955"><?= _('Cumbria') ?></option>
                                            <option value="3533"><?= _('Denbighshire') ?></option>
                                            <option value="3534"><?= _('Derbyshire') ?></option>
                                            <option value="3535"><?= _('Devon') ?></option>
                                            <option value="3536"><?= _('Dorset') ?></option>
                                            <option value="3537"><?= _('Dumfries and Galloway') ?></option>
                                            <option value="3538"><?= _('Dundee') ?></option>
                                            <option value="3539"><?= _('Durham') ?></option>
                                            <option value="3540"><?= _('East Ayrshire') ?></option>
                                            <option value="3541"><?= _('East Dunbartonshire') ?></option>
                                            <option value="3542"><?= _('East Lothian') ?></option>
                                            <option value="3543"><?= _('East Renfrewshire') ?></option>
                                            <option value="3544"><?= _('East Riding of Yorkshire') ?></option>
                                            <option value="3545"><?= _('East Sussex') ?></option>
                                            <option value="3546"><?= _('Edinburgh') ?></option>
                                            <option value="3547"><?= _('Essex') ?></option>
                                            <option value="3548"><?= _('Falkirk') ?></option>
                                            <option value="3549"><?= _('Fife') ?></option>
                                            <option value="3550"><?= _('Flintshire') ?></option>
                                            <option value="3551"><?= _('Glasgow') ?></option>
                                            <option value="3552"><?= _('Gloucestershire') ?></option>
                                            <option value="3553"><?= _('Greater London') ?></option>
                                            <option value="3554"><?= _('Greater Manchester') ?></option>
                                            <option value="3555"><?= _('Gwynedd') ?></option>
                                            <option value="3556"><?= _('Hampshire') ?></option>
                                            <option value="3557"><?= _('Herefordshire') ?></option>
                                            <option value="3558"><?= _('Hertfordshire') ?></option>
                                            <option value="3559"><?= _('Highlands') ?></option>
                                            <option value="3560"><?= _('Inverclyde') ?></option>
                                            <option value="3561"><?= _('Isle of Wight') ?></option>
                                            <option value="3562"><?= _('Kent') ?></option>
                                            <option value="3563"><?= _('Lancashire') ?></option>
                                            <option value="3564"><?= _('Leicestershire') ?></option>
                                            <option value="3565"><?= _('Lincolnshire') ?></option>
                                            <option value="3566"><?= _('Merseyside') ?></option>
                                            <option value="3567"><?= _('Merthyr Tydfil') ?></option>
                                            <option value="3568"><?= _('Midlothian') ?></option>
                                            <option value="3569"><?= _('Monmouthshire') ?></option>
                                            <option value="3570"><?= _('Moray') ?></option>
                                            <option value="3571"><?= _('Neath Port Talbot') ?></option>
                                            <option value="3572"><?= _('Newport') ?></option>
                                            <option value="3573"><?= _('Norfolk') ?></option>
                                            <option value="3574"><?= _('North Ayrshire') ?></option>
                                            <option value="3575"><?= _('North Lanarkshire') ?></option>
                                            <option value="3576"><?= _('North Yorkshire') ?></option>
                                            <option value="3577"><?= _('Northamptonshire') ?></option>
                                            <option value="3578"><?= _('Northumberland') ?></option>
                                            <option value="3579"><?= _('Nottinghamshire') ?></option>
                                            <option value="3580"><?= _('Orkney Islands') ?></option>
                                            <option value="3581"><?= _('Oxfordshire') ?></option>
                                            <option value="3582"><?= _('Pembrokeshire') ?></option>
                                            <option value="3583"><?= _('Perth and Kinross') ?></option>
                                            <option value="3584"><?= _('Powys') ?></option>
                                            <option value="3585"><?= _('Renfrewshire') ?></option>
                                            <option value="3586"><?= _('Rhondda Cynon Taff') ?></option>
                                            <option value="3587"><?= _('Rutland') ?></option>
                                            <option value="3588"><?= _('Scottish Borders') ?></option>
                                            <option value="3589"><?= _('Shetland Islands') ?></option>
                                            <option value="3590"><?= _('Shropshire') ?></option>
                                            <option value="3591"><?= _('Somerset') ?></option>
                                            <option value="3592"><?= _('South Ayrshire') ?></option>
                                            <option value="3593"><?= _('South Lanarkshire') ?></option>
                                            <option value="3594"><?= _('South Yorkshire') ?></option>
                                            <option value="3595"><?= _('Staffordshire') ?></option>
                                            <option value="3596"><?= _('Stirling') ?></option>
                                            <option value="3597"><?= _('Suffolk') ?></option>
                                            <option value="3598"><?= _('Surrey') ?></option>
                                            <option value="3599"><?= _('Swansea') ?></option>
                                            <option value="3600"><?= _('Torfaen') ?></option>
                                            <option value="3601"><?= _('Tyne and Wear') ?></option>
                                            <option value="3602"><?= _('Vale of Glamorgan') ?></option>
                                            <option value="3603"><?= _('Warwickshire') ?></option>
                                            <option value="3604"><?= _('West Dunbartonshire') ?></option>
                                            <option value="3605"><?= _('West Lothian') ?></option>
                                            <option value="3606"><?= _('West Midlands') ?></option>
                                            <option value="3607"><?= _('West Sussex') ?></option>
                                            <option value="3608"><?= _('West Yorkshire') ?></option>
                                            <option value="3609"><?= _('Western Isles') ?></option>
                                            <option value="3610"><?= _('Wiltshire') ?></option>
                                            <option value="3611"><?= _('Worcestershire') ?></option>
                                            <option value="3612"><?= _('Wrexham') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-2">
                                    <div class="form-group">
                                        <label for=""><?= _('First Name') ?></label>
                                        <input type="text" class="form-control input-sm" id="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><?= _('Last Name') ?></label>
                                        <input type="text" class="form-control input-sm" id="" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <label><?= _('Address') ?></label>
                                        <input type="text" name="address1" id="inputAddress1" class="form-control input-sm" value="" required="required" title="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <input type="text" name="address1" id="inputAddress1" class="form-control input-sm" value="" title="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <input type="text" name="address1" id="inputAddress1" class="form-control input-sm" value="" title="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2 col-md-offset-5">
                                    <button type="submit" class="btn btn-primary"><?= _('next') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <?= _('Billing Information') ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <form action="#" method="POST" role="form">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-2">
                                    <div class="form-group">
                                        <label for=""><?= _('Country') ?></label>
                                        <select name="country" id="inputCountry" class="form-control input-sm" required="required">
                                            <option value=""><?= _(' --- Please Select --- ') ?></option>
                                            <option value="13"><?= _('Australia') ?></option>
                                            <option value="21"><?= _('Belgium') ?></option>
                                            <option value="74"><?= _('France, Metropolitan') ?></option>
                                            <option value="105"><?= _('Italy') ?></option>
                                            <option value="153"><?= _('New Zealand') ?></option>
                                            <option value="170"><?= _('Poland') ?></option>
                                            <option value="171"><?= _('Portugal') ?></option>
                                            <option value="222" selected="selected"><?= _('United Kingdom') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><?= _('State') ?></label>
                                        <select name="state" id="input" class="form-control input-sm" required="required">
                                            <option value=""><?= _(' --- Please Select --- ') ?></option>
                                            <option value="3513"><?= _('Aberdeen') ?></option>
                                            <option value="3514"><?= _('Aberdeenshire') ?></option>
                                            <option value="3515"><?= _('Anglesey') ?></option>
                                            <option value="3516"><?= _('Angus') ?></option>
                                            <option value="3517"><?= _('Argyll and Bute') ?></option>
                                            <option value="3518"><?= _('Bedfordshire') ?></option>
                                            <option value="3519"><?= _('Berkshire') ?></option>
                                            <option value="3520"><?= _('Blaenau Gwent') ?></option>
                                            <option value="3521"><?= _('Bridgend') ?></option>
                                            <option value="3522"><?= _('Bristol') ?></option>
                                            <option value="3523"><?= _('Buckinghamshire') ?></option>
                                            <option value="3524"><?= _('Caerphilly') ?></option>
                                            <option value="3525"><?= _('Cambridgeshire') ?></option>
                                            <option value="3526"><?= _('Cardiff') ?></option>
                                            <option value="3527"><?= _('Carmarthenshire') ?></option>
                                            <option value="3528"><?= _('Ceredigion') ?></option>
                                            <option value="3529"><?= _('Cheshire') ?></option>
                                            <option value="3530"><?= _('Clackmannanshire') ?></option>
                                            <option value="3531"><?= _('Conwy') ?></option>
                                            <option value="3532"><?= _('Cornwall') ?></option>
                                            <option value="3949"><?= _('County Antrim') ?></option>
                                            <option value="3950"><?= _('County Armagh') ?></option>
                                            <option value="3951"><?= _('County Down') ?></option>
                                            <option value="3952"><?= _('County Fermanagh') ?></option>
                                            <option value="3953"><?= _('County Londonderry') ?></option>
                                            <option value="3954"><?= _('County Tyrone') ?></option>
                                            <option value="3955"><?= _('Cumbria') ?></option>
                                            <option value="3533"><?= _('Denbighshire') ?></option>
                                            <option value="3534"><?= _('Derbyshire') ?></option>
                                            <option value="3535"><?= _('Devon') ?></option>
                                            <option value="3536"><?= _('Dorset') ?></option>
                                            <option value="3537"><?= _('Dumfries and Galloway') ?></option>
                                            <option value="3538"><?= _('Dundee') ?></option>
                                            <option value="3539"><?= _('Durham') ?></option>
                                            <option value="3540"><?= _('East Ayrshire') ?></option>
                                            <option value="3541"><?= _('East Dunbartonshire') ?></option>
                                            <option value="3542"><?= _('East Lothian') ?></option>
                                            <option value="3543"><?= _('East Renfrewshire') ?></option>
                                            <option value="3544"><?= _('East Riding of Yorkshire') ?></option>
                                            <option value="3545"><?= _('East Sussex') ?></option>
                                            <option value="3546"><?= _('Edinburgh') ?></option>
                                            <option value="3547"><?= _('Essex') ?></option>
                                            <option value="3548"><?= _('Falkirk') ?></option>
                                            <option value="3549"><?= _('Fife') ?></option>
                                            <option value="3550"><?= _('Flintshire') ?></option>
                                            <option value="3551"><?= _('Glasgow') ?></option>
                                            <option value="3552"><?= _('Gloucestershire') ?></option>
                                            <option value="3553"><?= _('Greater London') ?></option>
                                            <option value="3554"><?= _('Greater Manchester') ?></option>
                                            <option value="3555"><?= _('Gwynedd') ?></option>
                                            <option value="3556"><?= _('Hampshire') ?></option>
                                            <option value="3557"><?= _('Herefordshire') ?></option>
                                            <option value="3558"><?= _('Hertfordshire') ?></option>
                                            <option value="3559"><?= _('Highlands') ?></option>
                                            <option value="3560"><?= _('Inverclyde') ?></option>
                                            <option value="3561"><?= _('Isle of Wight') ?></option>
                                            <option value="3562"><?= _('Kent') ?></option>
                                            <option value="3563"><?= _('Lancashire') ?></option>
                                            <option value="3564"><?= _('Leicestershire') ?></option>
                                            <option value="3565"><?= _('Lincolnshire') ?></option>
                                            <option value="3566"><?= _('Merseyside') ?></option>
                                            <option value="3567"><?= _('Merthyr Tydfil') ?></option>
                                            <option value="3568"><?= _('Midlothian') ?></option>
                                            <option value="3569"><?= _('Monmouthshire') ?></option>
                                            <option value="3570"><?= _('Moray') ?></option>
                                            <option value="3571"><?= _('Neath Port Talbot') ?></option>
                                            <option value="3572"><?= _('Newport') ?></option>
                                            <option value="3573"><?= _('Norfolk') ?></option>
                                            <option value="3574"><?= _('North Ayrshire') ?></option>
                                            <option value="3575"><?= _('North Lanarkshire') ?></option>
                                            <option value="3576"><?= _('North Yorkshire') ?></option>
                                            <option value="3577"><?= _('Northamptonshire') ?></option>
                                            <option value="3578"><?= _('Northumberland') ?></option>
                                            <option value="3579"><?= _('Nottinghamshire') ?></option>
                                            <option value="3580"><?= _('Orkney Islands') ?></option>
                                            <option value="3581"><?= _('Oxfordshire') ?></option>
                                            <option value="3582"><?= _('Pembrokeshire') ?></option>
                                            <option value="3583"><?= _('Perth and Kinross') ?></option>
                                            <option value="3584"><?= _('Powys') ?></option>
                                            <option value="3585"><?= _('Renfrewshire') ?></option>
                                            <option value="3586"><?= _('Rhondda Cynon Taff') ?></option>
                                            <option value="3587"><?= _('Rutland') ?></option>
                                            <option value="3588"><?= _('Scottish Borders') ?></option>
                                            <option value="3589"><?= _('Shetland Islands') ?></option>
                                            <option value="3590"><?= _('Shropshire') ?></option>
                                            <option value="3591"><?= _('Somerset') ?></option>
                                            <option value="3592"><?= _('South Ayrshire') ?></option>
                                            <option value="3593"><?= _('South Lanarkshire') ?></option>
                                            <option value="3594"><?= _('South Yorkshire') ?></option>
                                            <option value="3595"><?= _('Staffordshire') ?></option>
                                            <option value="3596"><?= _('Stirling') ?></option>
                                            <option value="3597"><?= _('Suffolk') ?></option>
                                            <option value="3598"><?= _('Surrey') ?></option>
                                            <option value="3599"><?= _('Swansea') ?></option>
                                            <option value="3600"><?= _('Torfaen') ?></option>
                                            <option value="3601"><?= _('Tyne and Wear') ?></option>
                                            <option value="3602"><?= _('Vale of Glamorgan') ?></option>
                                            <option value="3603"><?= _('Warwickshire') ?></option>
                                            <option value="3604"><?= _('West Dunbartonshire') ?></option>
                                            <option value="3605"><?= _('West Lothian') ?></option>
                                            <option value="3606"><?= _('West Midlands') ?></option>
                                            <option value="3607"><?= _('West Sussex') ?></option>
                                            <option value="3608"><?= _('West Yorkshire') ?></option>
                                            <option value="3609"><?= _('Western Isles') ?></option>
                                            <option value="3610"><?= _('Wiltshire') ?></option>
                                            <option value="3611"><?= _('Worcestershire') ?></option>
                                            <option value="3612"><?= _('Wrexham') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-2">
                                    <div class="form-group">
                                        <label for=""><?= _('First Name') ?></label>
                                        <input type="text" class="form-control input-sm" id="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><?= _('Last Name') ?></label>
                                        <input type="text" class="form-control input-sm" id="" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <label><?= _('Address') ?></label>
                                        <input type="text" name="address1" id="inputAddress1" class="form-control input-sm" value="" required="required" title="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <input type="text" name="address1" id="inputAddress1" class="form-control input-sm" value="" title="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <input type="text" name="address1" id="inputAddress1" class="form-control input-sm" value="" title="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2 col-md-offset-5">
                                    <button type="submit" class="btn btn-primary"><?= _('next') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-8">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="text-right"><strong><?= _('Total:') ?></strong></td>
                            <td class="text-right"><?= $cart_info['total_amount'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttons">
            <div class="pull-left"><a href="/" class="btn btn-default"><?= _('Continue Shopping') ?></a></div>
            <div class="pull-right"><a href="#" class="btn btn-primary"><?= _('Checkout') ?></a></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php $this->load->view('/default/footer') ?>