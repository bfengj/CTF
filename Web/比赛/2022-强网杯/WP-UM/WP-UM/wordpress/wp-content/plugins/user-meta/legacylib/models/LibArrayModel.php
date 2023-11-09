<?php
namespace UserMeta;

class LibArrayModel
{

    function settingsArray($key = null)
    {
        $data = array(
            'nonce' => 'plugin_framework'
        );

        if ($key) {
            if (isset($data[$key]))
                return $data[$key];
        }

        return $data;
    }

    /**
     *
     * @return array: wp default userfields. key as field name, val as title
     */
    function defaultUserFieldsArray($key = null)
    {
        global $pfInstance;

        $data = array(
            'user_login' => __('Username', $pfInstance->name),
            'user_email' => __('E-mail', $pfInstance->name),
            'user_pass' => __('Password', $pfInstance->name),
            'user_nicename' => __('Nicename', $pfInstance->name),
            'user_url' => __('Website', $pfInstance->name),
            'display_name' => __('Display Name', $pfInstance->name),
            'nickname' => __('Nickname', $pfInstance->name),
            'first_name' => __('First Name', $pfInstance->name),
            'last_name' => __('Last Name', $pfInstance->name),
            'description' => __('Description', $pfInstance->name),
            'user_registered' => __('Registration Date', $pfInstance->name),
            'role' => __('Role', $pfInstance->name),
            'jabber' => __('Jabber', $pfInstance->name),
            'aim' => __('Aim', $pfInstance->name),
            'yim' => __('Yim', $pfInstance->name)
        );

        if ($key) {
            if (isset($data[$key]))
                return $data[$key];
        }

        return $data;
    }

    function wpUserTableFieldsArray()
    {
        return array(
            'ID',
            'user_login',
            'user_pass',
            'user_nicename',
            'user_email',
            'user_url',
            'user_registered',
            'user_activation_key',
            'user_status',
            'display_name'
        );
    }

    function countryArray($key = null)
    {
        global $pfInstance;

        $countries = array(
            "AF" => __("Afghanistan", $pfInstance->name),
            "AL" => __("Albania", $pfInstance->name),
            "DZ" => __("Algeria", $pfInstance->name),
            // AS is not an official country
            "AS" => __("American Samoa", $pfInstance->name),
            "AD" => __("Andorra", $pfInstance->name),
            "AO" => __("Angola", $pfInstance->name),
            "AI" => __("Anguilla", $pfInstance->name),
            // AQ is not an official country
            "AQ" => __("Antarctica", $pfInstance->name),
            "AG" => __("Antigua and Barbuda", $pfInstance->name),
            "AR" => __("Argentina", $pfInstance->name),
            "AM" => __("Armenia", $pfInstance->name),
            "AW" => __("Aruba", $pfInstance->name),
            "AU" => __("Australia", $pfInstance->name),
            "AT" => __("Austria", $pfInstance->name),
            "AZ" => __("Azerbaijan", $pfInstance->name),
            "BS" => __("Bahamas", $pfInstance->name),
            "BH" => __("Bahrain", $pfInstance->name),
            "BD" => __("Bangladesh", $pfInstance->name),
            "BB" => __("Barbados", $pfInstance->name),
            "BY" => __("Belarus", $pfInstance->name),
            "BE" => __("Belgium", $pfInstance->name),
            "BZ" => __("Belize", $pfInstance->name),
            "BJ" => __("Benin", $pfInstance->name),
            "BM" => __("Bermuda", $pfInstance->name),
            "BT" => __("Bhutan", $pfInstance->name),
            "BO" => __("Bolivia", $pfInstance->name),
            "BA" => __("Bosnia and Herzegovina", $pfInstance->name),
            "BW" => __("Botswana", $pfInstance->name),
            // BV is an official country. No one lives there
            "BV" => __("Bouvet Island", $pfInstance->name),
            "BR" => __("Brazil", $pfInstance->name),
            "IO" => __("British Indian Ocean Territory", $pfInstance->name),
            "BN" => __("Brunei Darussalam", $pfInstance->name),
            "BG" => __("Bulgaria", $pfInstance->name),
            "BF" => __("Burkina Faso", $pfInstance->name),
            "BI" => __("Burundi", $pfInstance->name),
            "KH" => __("Cambodia", $pfInstance->name),
            "CM" => __("Cameroon", $pfInstance->name),
            "CA" => __("Canada", $pfInstance->name),
            "CV" => __("Cape Verde", $pfInstance->name),
            "KY" => __("Cayman Islands", $pfInstance->name),
            "CF" => __("Central African Republic", $pfInstance->name),
            "TD" => __("Chad", $pfInstance->name),
            "CL" => __("Chile", $pfInstance->name),
            "CN" => __("China", $pfInstance->name),
            "CX" => __("Christmas Island", $pfInstance->name),
            "CC" => __("Cocos (Keeling) Islands", $pfInstance->name),
            "CO" => __("Colombia", $pfInstance->name),
            "KM" => __("Comoros", $pfInstance->name),
            "CG" => __("Congo", $pfInstance->name),
            "CD" => __("Congo, the Democratic Republic of the", $pfInstance->name),
            "CK" => __("Cook Islands", $pfInstance->name),
            "CR" => __("Costa Rica", $pfInstance->name),
            "CI" => __("Cote D'Ivoire", $pfInstance->name),
            "HR" => __("Croatia", $pfInstance->name),
            "CU" => __("Cuba", $pfInstance->name),
            // Newly added country
            "CW" => __("Curacao", $pfInstance->name),
            "CY" => __("Cyprus", $pfInstance->name),
            "CZ" => __("Czech Republic", $pfInstance->name),
            "DK" => __("Denmark", $pfInstance->name),
            "DJ" => __("Djibouti", $pfInstance->name),
            "DM" => __("Dominica", $pfInstance->name),
            "DO" => __("Dominican Republic", $pfInstance->name),
            "EC" => __("Ecuador", $pfInstance->name),
            "EG" => __("Egypt", $pfInstance->name),
            "SV" => __("El Salvador", $pfInstance->name),
            "GQ" => __("Equatorial Guinea", $pfInstance->name),
            "ER" => __("Eritrea", $pfInstance->name),
            "EE" => __("Estonia", $pfInstance->name),
            "ET" => __("Ethiopia", $pfInstance->name),
            "FK" => __("Falkland Islands (Malvinas)", $pfInstance->name),
            "FO" => __("Faroe Islands", $pfInstance->name),
            "FJ" => __("Fiji", $pfInstance->name),
            "FI" => __("Finland", $pfInstance->name),
            "FR" => __("France", $pfInstance->name),
            // GF is not an official country
            "GF" => __("French Guiana", $pfInstance->name),
            "PF" => __("French Polynesia", $pfInstance->name),
            "TF" => __("French Southern Territories", $pfInstance->name),
            "GA" => __("Gabon", $pfInstance->name),
            "GM" => __("Gambia", $pfInstance->name),
            "GE" => __("Georgia", $pfInstance->name),
            "DE" => __("Germany", $pfInstance->name),
            "GH" => __("Ghana", $pfInstance->name),
            "GI" => __("Gibraltar", $pfInstance->name),
            "GR" => __("Greece", $pfInstance->name),
            "GL" => __("Greenland", $pfInstance->name),
            "GD" => __("Grenada", $pfInstance->name),
            "GP" => __("Guadeloupe", $pfInstance->name),
            "GU" => __("Guam", $pfInstance->name),
            "GT" => __("Guatemala", $pfInstance->name),
            // Newly added country
            "GG" => __("Guernsey", $pfInstance->name),
            "GN" => __("Guinea", $pfInstance->name),
            "GW" => __("Guinea-Bissau", $pfInstance->name),
            "GY" => __("Guyana", $pfInstance->name),
            "HT" => __("Haiti", $pfInstance->name),
            // HM is not an official counry
            "HM" => __("Heard Island and Mcdonald Islands", $pfInstance->name),
            "VA" => __("Holy See (Vatican City State)", $pfInstance->name),
            "HN" => __("Honduras", $pfInstance->name),
            "HK" => __("Hong Kong", $pfInstance->name),
            "HU" => __("Hungary", $pfInstance->name),
            "IS" => __("Iceland", $pfInstance->name),
            "IN" => __("India", $pfInstance->name),
            "ID" => __("Indonesia", $pfInstance->name),
            "IR" => __("Iran, Islamic Republic of", $pfInstance->name),
            "IQ" => __("Iraq", $pfInstance->name),
            "IE" => __("Ireland", $pfInstance->name),
            // Newly added country
            "IM" => __("Isle of Man", $pfInstance->name),
            "IL" => __("Israel", $pfInstance->name),
            "IT" => __("Italy", $pfInstance->name),
            "JM" => __("Jamaica", $pfInstance->name),
            "JP" => __("Japan", $pfInstance->name),
            // Newly added country
            "JE" => __("Jersy", $pfInstance->name),
            "JO" => __("Jordan", $pfInstance->name),
            "KZ" => __("Kazakhstan", $pfInstance->name),
            "KE" => __("Kenya", $pfInstance->name),
            "KI" => __("Kiribati", $pfInstance->name),
            // Newly added country
            "XK" => __("Kosovo", $pfInstance->name),
            "KP" => __("Korea, Democratic People's Republic of", $pfInstance->name),
            "KR" => __("Korea, Republic of", $pfInstance->name),
            "KW" => __("Kuwait", $pfInstance->name),
            "KG" => __("Kyrgyzstan", $pfInstance->name),
            "LA" => __("Lao People's Democratic Republic", $pfInstance->name),
            "LV" => __("Latvia", $pfInstance->name),
            "LB" => __("Lebanon", $pfInstance->name),
            "LS" => __("Lesotho", $pfInstance->name),
            "LR" => __("Liberia", $pfInstance->name),
            "LY" => __("Libyan Arab Jamahiriya", $pfInstance->name),
            "LI" => __("Liechtenstein", $pfInstance->name),
            "LT" => __("Lithuania", $pfInstance->name),
            "LU" => __("Luxembourg", $pfInstance->name),
            "MO" => __("Macao", $pfInstance->name),
            "MK" => __("Macedonia, the Former Yugoslav Republic of", $pfInstance->name),
            "MG" => __("Madagascar", $pfInstance->name),
            "MW" => __("Malawi", $pfInstance->name),
            "MY" => __("Malaysia", $pfInstance->name),
            "MV" => __("Maldives", $pfInstance->name),
            "ML" => __("Mali", $pfInstance->name),
            "MT" => __("Malta", $pfInstance->name),
            "MH" => __("Marshall Islands", $pfInstance->name),
            // MQ not an official country
            "MQ" => __("Martinique", $pfInstance->name),
            "MR" => __("Mauritania", $pfInstance->name),
            "MU" => __("Mauritius", $pfInstance->name),
            "YT" => __("Mayotte", $pfInstance->name),
            "MX" => __("Mexico", $pfInstance->name),
            "FM" => __("Micronesia, Federated States of", $pfInstance->name),
            "MD" => __("Moldova, Republic of", $pfInstance->name),
            "MC" => __("Monaco", $pfInstance->name),
            "MN" => __("Mongolia", $pfInstance->name),
            // Newly added country
            "ME" => __("Montenegro", $pfInstance->name),
            "MS" => __("Montserrat", $pfInstance->name),
            "MA" => __("Morocco", $pfInstance->name),
            "MZ" => __("Mozambique", $pfInstance->name),
            "MM" => __("Myanmar", $pfInstance->name),
            "NA" => __("Namibia", $pfInstance->name),
            "NR" => __("Nauru", $pfInstance->name),
            "NP" => __("Nepal", $pfInstance->name),
            "NL" => __("Netherlands", $pfInstance->name),
            "AN" => __("Netherlands Antilles", $pfInstance->name),
            "NC" => __("New Caledonia", $pfInstance->name),
            "NZ" => __("New Zealand", $pfInstance->name),
            "NI" => __("Nicaragua", $pfInstance->name),
            "NE" => __("Niger", $pfInstance->name),
            "NG" => __("Nigeria", $pfInstance->name),
            "NU" => __("Niue", $pfInstance->name),
            "NF" => __("Norfolk Island", $pfInstance->name),
            "MP" => __("Northern Mariana Islands", $pfInstance->name),
            "NO" => __("Norway", $pfInstance->name),
            "OM" => __("Oman", $pfInstance->name),
            "PK" => __("Pakistan", $pfInstance->name),
            "PW" => __("Palau", $pfInstance->name),
            "PS" => __("Palestinian Territory, Occupied", $pfInstance->name),
            "PA" => __("Panama", $pfInstance->name),
            "PG" => __("Papua New Guinea", $pfInstance->name),
            "PY" => __("Paraguay", $pfInstance->name),
            "PE" => __("Peru", $pfInstance->name),
            "PH" => __("Philippines", $pfInstance->name),
            "PN" => __("Pitcairn", $pfInstance->name),
            "PL" => __("Poland", $pfInstance->name),
            "PT" => __("Portugal", $pfInstance->name),
            "PR" => __("Puerto Rico", $pfInstance->name),
            "QA" => __("Qatar", $pfInstance->name),
            "RE" => __("Reunion", $pfInstance->name),
            "RO" => __("Romania", $pfInstance->name),
            "RU" => __("Russian Federation", $pfInstance->name),
            "RW" => __("Rwanda", $pfInstance->name),
            // Newly added country
            "BL" => __("Saint Barthelemy", $pfInstance->name),
            "SH" => __("Saint Helena", $pfInstance->name),
            "KN" => __("Saint Kitts and Nevis", $pfInstance->name),
            "LC" => __("Saint Lucia", $pfInstance->name),
            "PM" => __("Saint Pierre and Miquelon", $pfInstance->name),
            "VC" => __("Saint Vincent and the Grenadines", $pfInstance->name),
            "WS" => __("Samoa", $pfInstance->name),
            "SM" => __("San Marino", $pfInstance->name),
            "ST" => __("Sao Tome and Principe", $pfInstance->name),
            "SA" => __("Saudi Arabia", $pfInstance->name),
            "SN" => __("Senegal", $pfInstance->name),
            // CS is not a country anymore. It divided into 2 new countries
            // "CS" => __("Serbia and Montenegro", $pfInstance->name),
            // Newly added country
            "RS" => __("Serbia", $pfInstance->name),
            "SC" => __("Seychelles", $pfInstance->name),
            "SL" => __("Sierra Leone", $pfInstance->name),
            "SG" => __("Singapore", $pfInstance->name),
            // Newly added country
            "SX" => __("Sint Marteen", $pfInstance->name),
            "SK" => __("Slovakia", $pfInstance->name),
            "SI" => __("Slovenia", $pfInstance->name),
            "SB" => __("Solomon Islands", $pfInstance->name),
            "SO" => __("Somalia", $pfInstance->name),
            "ZA" => __("South Africa", $pfInstance->name),
            "GS" => __("South Georgia and the South Sandwich Islands", $pfInstance->name),
            "ES" => __("Spain", $pfInstance->name),
            "LK" => __("Sri Lanka", $pfInstance->name),
            "SD" => __("Sudan", $pfInstance->name),
            "SR" => __("Suriname", $pfInstance->name),
            "SJ" => __("Svalbard and Jan Mayen", $pfInstance->name),
            "SZ" => __("Swaziland", $pfInstance->name),
            "SE" => __("Sweden", $pfInstance->name),
            "CH" => __("Switzerland", $pfInstance->name),
            "SY" => __("Syrian Arab Republic", $pfInstance->name),
            "TW" => __("Taiwan, Province of China", $pfInstance->name),
            "TJ" => __("Tajikistan", $pfInstance->name),
            "TZ" => __("Tanzania, United Republic of", $pfInstance->name),
            "TH" => __("Thailand", $pfInstance->name),
            "TL" => __("Timor-Leste", $pfInstance->name),
            "TG" => __("Togo", $pfInstance->name),
            "TK" => __("Tokelau", $pfInstance->name),
            "TO" => __("Tonga", $pfInstance->name),
            "TT" => __("Trinidad and Tobago", $pfInstance->name),
            "TN" => __("Tunisia", $pfInstance->name),
            "TR" => __("Turkey", $pfInstance->name),
            "TM" => __("Turkmenistan", $pfInstance->name),
            "TC" => __("Turks and Caicos Islands", $pfInstance->name),
            "TV" => __("Tuvalu", $pfInstance->name),
            "UG" => __("Uganda", $pfInstance->name),
            "UA" => __("Ukraine", $pfInstance->name),
            "AE" => __("United Arab Emirates", $pfInstance->name),
            "GB" => __("United Kingdom", $pfInstance->name),
            "US" => __("United States", $pfInstance->name),
            // UM is not an official country
            "UM" => __("United States Minor Outlying Islands", $pfInstance->name),
            "UY" => __("Uruguay", $pfInstance->name),
            "UZ" => __("Uzbekistan", $pfInstance->name),
            "VU" => __("Vanuatu", $pfInstance->name),
            "VE" => __("Venezuela", $pfInstance->name),
            "VN" => __("Vietnam", $pfInstance->name),
            "VG" => __("Virgin Islands, British", $pfInstance->name),
            "VI" => __("Virgin Islands, U.S.", $pfInstance->name),
            "WF" => __("Wallis and Futuna", $pfInstance->name),
            "EH" => __("Western Sahara", $pfInstance->name),
            "YE" => __("Yemen", $pfInstance->name),
            "ZM" => __("Zambia", $pfInstance->name),
            "ZW" => __("Zimbabwe", $pfInstance->name)
        );

        $countries = apply_filters('user_meta_countries_list', $countries);

        if ($key) {
            if (isset($countries[$key]))
                return $countries[$key];
        }

        return $countries;
    }
}
