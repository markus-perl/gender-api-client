<?php

namespace GenderApi\Client;

/**
 * Class CountryList
 * @package GenderApi\Client
 */
class CountryList
{

    /**
     * @var array
     */
    private $countryList = array(
        'Afghanistan' => 'AF',
        'Albania' => 'AL',
        'Algeria' => 'DZ',
        'American Samoa' => 'AS',
        'Andorra' => 'AD',
        'Angola' => 'AO',
        'Anguilla' => 'AI',
        'Antarctica' => 'AQ',
        'Antigua and Barbuda' => 'AG',
        'Argentina' => 'AR',
        'Armenia' => 'AM',
        'Aruba' => 'AW',
        'Australia' => 'AU',
        'Austria' => 'AT',
        'Azerbaijan' => 'AZ',
        'Bahamas' => 'BS',
        'Bahrain' => 'BH',
        'Bangladesh' => 'BD',
        'Barbados' => 'BB',
        'Belarus' => 'BY',
        'Belgium' => 'BE',
        'Belize' => 'BZ',
        'Benin' => 'BJ',
        'Bermuda' => 'BM',
        'Bhutan' => 'BT',
        'Bolivia' => 'BO',
        'Bosnia and Herzegovina' => 'BA',
        'Botswana' => 'BW',
        'Bouvet Island' => 'BV',
        'Brazil' => 'BR',
        'British Antarctic Territory' => 'BQ',
        'British Indian Ocean Territory' => 'IO',
        'British Virgin Islands' => 'VG',
        'Brunei' => 'BN',
        'Bulgaria' => 'BG',
        'Burkina Faso' => 'BF',
        'Burundi' => 'BI',
        'Cambodia' => 'KH',
        'Cameroon' => 'CM',
        'Canada' => 'CA',
        'Canton and Enderbury Islands' => 'CT',
        'Cape Verde' => 'CV',
        'Cayman Islands' => 'KY',
        'Central African Republic' => 'CF',
        'Chad' => 'TD',
        'Chile' => 'CL',
        'China' => 'CN',
        'Paracel Islands' => 'CN',
        'Christmas Island' => 'CX',
        'Cocos [Keeling] Islands' => 'CC',
        'Colombia' => 'CO',
        'Comoros' => 'KM',
        'Congo - Brazzaville' => 'CG',
        'Congo - Kinshasa' => 'CD',
        'Congo (Kinshasa)' => 'CG',
        'Congo (Brazzaville)' => 'CG',
        'Cook Islands' => 'CK',
        'Costa Rica' => 'CR',
        'Croatia' => 'HR',
        'Cuba' => 'CU',
        'Cyprus' => 'CY',
        'Czech Republic' => 'CZ',
        'Côte d’Ivoire' => 'CI',
        'Cote D\'Ivoire' => 'CI',
        'Denmark' => 'DK',
        'Djibouti' => 'DJ',
        'Dominica' => 'DM',
        'Dominican Republic' => 'DO',
        'Dronning Maud Land' => 'NQ',
        'Ecuador' => 'EC',
        'Egypt' => 'EG',
        'El Salvador' => 'SV',
        'Equatorial Guinea' => 'GQ',
        'Eritrea' => 'ER',
        'Estonia' => 'EE',
        'Ethiopia' => 'ET',
        'Falkland Islands' => 'FK',
        'Faroe Islands' => 'FO',
        'Fiji' => 'FJ',
        'Finland' => 'FI',
        'France' => 'FR',
        'French Guiana' => 'GF',
        'French Polynesia' => 'PF',
        'French Southern Territories' => 'TF',
        'French Southern and Antarctic Territories' => 'FQ',
        'Gabon' => 'GA',
        'Gambia' => 'GM',
        'Georgia' => 'GE',
        'Germany' => 'DE',
        'Ghana' => 'GH',
        'Gibraltar' => 'GI',
        'Greece' => 'GR',
        'Greenland' => 'GL',
        'Grenada' => 'GD',
        'Guadeloupe' => 'GP',
        'Guam' => 'GU',
        'Guatemala' => 'GT',
        'Guernsey' => 'GG',
        'Guinea' => 'GN',
        'Guinea-Bissau' => 'GW',
        'Guyana' => 'GY',
        'Haiti' => 'HT',
        'Heard Island and McDonald Islands' => 'HM',
        'Honduras' => 'HN',
        'Hong Kong SAR China' => 'HK',
        'Hong Kong' => 'HK',
        'Hungary' => 'HU',
        'Iceland' => 'IS',
        'India' => 'IN',
        'Indonesia' => 'ID',
        'Iran' => 'IR',
        'Iraq' => 'IQ',
        'Ireland' => 'IE',
        'Isle of Man' => 'IM',
        'Israel' => 'IL',
        'Italy' => 'IT',
        'Jamaica' => 'JM',
        'Japan' => 'JP',
        'Jersey' => 'JE',
        'Johnston Island' => 'JT',
        'Jordan' => 'JO',
        'Kazakhstan' => 'KZ',
        'Kenya' => 'KE',
        'Kiribati' => 'KI',
        'Kuwait' => 'KW',
        'Kyrgyzstan' => 'KG',
        'Laos' => 'LA',
        'Latvia' => 'LV',
        'Lebanon' => 'LB',
        'Lesotho' => 'LS',
        'Liberia' => 'LR',
        'Libya' => 'LY',
        'Liechtenstein' => 'LI',
        'Lithuania' => 'LT',
        'Luxembourg' => 'LU',
        'Macau SAR China' => 'MO',
        'Macedonia' => 'MK',
        'Madagascar' => 'MG',
        'Malawi' => 'MW',
        'Malaysia' => 'MY',
        'Maldives' => 'MV',
        'Mali' => 'ML',
        'Malta' => 'MT',
        'Marshall Islands' => 'MH',
        'Martinique' => 'MQ',
        'Mauritania' => 'MR',
        'Mauritius' => 'MU',
        'Mayotte' => 'YT',
        'Metropolitan France' => 'FX',
        'Mexico' => 'MX',
        'Micronesia' => 'FM',
        'Federated States of Micronesia' => 'FM',
        'Midway Islands' => 'MI',
        'Moldova' => 'MD',
        'Monaco' => 'MC',
        'Mongolia' => 'MN',
        'Montenegro' => 'ME',
        'Montserrat' => 'MS',
        'Morocco' => 'MA',
        'Mozambique' => 'MZ',
        'Myanmar [Burma]' => 'MM',
        'Myanmar' => 'MM',
        'Namibia' => 'NA',
        'Nauru' => 'NR',
        'Nepal' => 'NP',
        'Netherlands' => 'NL',
        'Netherlands Antilles' => 'AN',
        'Neutral Zone' => 'NT',
        'New Caledonia' => 'NC',
        'New Zealand' => 'NZ',
        'Nicaragua' => 'NI',
        'Niger' => 'NE',
        'Nigeria' => 'NG',
        'Niue' => 'NU',
        'Norfolk Island' => 'NF',
        'North Korea' => 'KP',
        'North Vietnam' => 'VD',
        'Northern Mariana Islands' => 'MP',
        'Norway' => 'NO',
        'Oman' => 'OM',
        'Pacific Islands Trust Territory' => 'PC',
        'Pakistan' => 'PK',
        'Palau' => 'PW',
        'Palestinian Territories' => 'PS',
        'Panama' => 'PA',
        'Panama Canal Zone' => 'PZ',
        'Papua New Guinea' => 'PG',
        'Paraguay' => 'PY',
        'People\'s Democratic Republic of Yemen' => 'YD',
        'Peru' => 'PE',
        'Philippines' => 'PH',
        'Pitcairn Islands' => 'PN',
        'Poland' => 'PL',
        'Portugal' => 'PT',
        'Puerto Rico' => 'PR',
        'Qatar' => 'QA',
        'Romania' => 'RO',
        'Russia' => 'RU',
        'Rwanda' => 'RW',
        'Réunion' => 'RE',
        'Reunion' => 'RE',
        'Saint-Denis' => 'RE',
        'Saint Barthélemy' => 'BL',
        'Saint Helena' => 'SH',
        'Saint Kitts and Nevis' => 'KN',
        'Saint Lucia' => 'LC',
        'Saint Martin' => 'MF',
        'Saint Pierre and Miquelon' => 'PM',
        'Saint Vincent and the Grenadines' => 'VC',
        'Samoa' => 'WS',
        'San Marino' => 'SM',
        'Saudi Arabia' => 'SA',
        'Senegal' => 'SN',
        'Serbia' => 'RS',
        'Serbia and Montenegro' => 'CS',
        'Seychelles' => 'SC',
        'Sierra Leone' => 'SL',
        'Singapore' => 'SG',
        'Slovakia' => 'SK',
        'Slovenia' => 'SI',
        'Solomon Islands' => 'SB',
        'Somalia' => 'SO',
        'South Africa' => 'ZA',
        'South Georgia and the South Sandwich Islands' => 'GS',
        'South Korea' => 'KR',
        'Spain' => 'ES',
        'Sri Lanka' => 'LK',
        'Sudan' => 'SD',
        'South Sudan' => 'SD',
        'Suriname' => 'SR',
        'Svalbard and Jan Mayen' => 'SJ',
        'Swaziland' => 'SZ',
        'Sweden' => 'SE',
        'Malmö' => 'SE',
        'Switzerland' => 'CH',
        'Syria' => 'SY',
        'São Tomé and Príncipe' => 'ST',
        'Sao Tome and Principe' => 'ST',
        'Taiwan' => 'TW',
        'Tajikistan' => 'TJ',
        'Tanzania' => 'TZ',
        'Thailand' => 'TH',
        'Timor-Leste' => 'TL',
        'East Timor' => 'TL',
        'Togo' => 'TG',
        'Tokelau' => 'TK',
        'Tonga' => 'TO',
        'Trinidad and Tobago' => 'TT',
        'Tunisia' => 'TN',
        'Turkey' => 'TR',
        'Turkmenistan' => 'TM',
        'Turks and Caicos Islands' => 'TC',
        'Tuvalu' => 'TV',
        'U.S. Minor Outlying Islands' => 'UM',
        'U.S. Miscellaneous Pacific Islands' => 'PU',
        'U.S. Virgin Islands' => 'VI',
        'Uganda' => 'UG',
        'Ukraine' => 'UA',
        'United Arab Emirates' => 'AE',
        'United Kingdom' => 'GB',
        'United States' => 'US',
        'Uruguay' => 'UY',
        'Uzbekistan' => 'UZ',
        'Vanuatu' => 'VU',
        'Vatican City' => 'VA',
        'Venezuela' => 'VE',
        'Vietnam' => 'VN',
        'Wake Island' => 'WK',
        'Wallis and Futuna' => 'WF',
        'Western Sahara' => 'EH',
        'Yemen' => 'YE',
        'Zambia' => 'ZM',
        'Zimbabwe' => 'ZW',
        'Åland Islands' => 'AX',
    );

    /**
     * Get the country code by country name
     *
     * @param string $name
     * @return null|string
     */
    public function getCountryCodeByName($name)
    {
        if (isset($this->countryList[$name])) {
            return $this->countryList[$name];
        }

        //nothing found we try case insensitive
        $name = strtolower($name);
        foreach ($this->countryList as $countryName => $countryCode) {
            if (strtolower($countryName) == $name) {
                return $countryCode;
            }
        }

        return null;
    }

    /**
     * Returns true if the country code is valid
     *
     * @param string $countryCode
     * @return bool
     */
    public function isValidCountryCode($countryCode)
    {
        return in_array(strtoupper($countryCode), $this->countryList);
    }

    /**
     * return true if the locale is valid
     *
     * @param string $locale
     * @return bool
     */
    public function isValidLocale($locale)
    {
        $locale = strtoupper($locale);
        if (substr_count($locale, '_')) {
            $locale = substr($locale, strpos($locale, '_') + 1);
        }

        if (substr_count($locale, '-')) {
            $locale = substr($locale, strpos($locale, '-') + 1);
        }

        return in_array($locale, $this->countryList);
    }

}