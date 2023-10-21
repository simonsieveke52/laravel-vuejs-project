<?php

if (! function_exists('cleanedSubstr')) {
    /**
     * @param  string      $str
     * @param  int|integer $lenght
     *
     * @return string
     */
    function cleanedSubstr($str, int $lenght = 100)
    {
        if (strlen(trim($str)) > $lenght) {
            return implode(' ', array_slice(explode(' ', substr(trim(strip_tags($str)), 0, $lenght)), 0, -1));
        }

        return $str;
    }
}

if (! function_exists('getCartKey')) {
    /**
     * This helper is used to generate a unique cart item id for product
     * You can check example on CartController, CartRepository
     *
     * @param  mixed $product
     * @param  mixed $prefix
     * @return string
     */
    function getCartKey(\App\Product $product, $prefix = ''): string
    {
        return md5($prefix  . $product->id);
    }
}

/**
 * Get Site Base domain name
 * @return string
 */
if (! function_exists('siteBaseDomain')) {
    function siteBaseDomain() : string
    {
        return str_replace(['https://', 'http://', 'www'], '', config('app.url'));
    }
}

if (! function_exists('xmlToArray')) {
    function xmlToArray($xml, bool $toArray = true)
    {
        $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($obj), $toArray);
    }
}

/**
 * Read boh file and return csv
 *
 * @param string $file
 * @return string
 */
if (!function_exists('readCsvFile')) {
    function readCsvFile(string $file)
    {
        $file = storage_path('app/public/csv/'. $file);
        return \League\Csv\Reader::createFromPath($file, 'r');
    }
}

/**
 * ------------------------------------------
 * Get credit card type
 * ------------------------------------------
 *
 * @return  string
 */
if (!function_exists('getCreditCardType')) {
    function getCreditCardType($str, $format = 'string')
    {
        if (strlen($str) === 0) {
            return '';
        }

        $matchingPatterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
        ];

        $ctr = 1;

        foreach ($matchingPatterns as $key => $pattern) {
            if (preg_match($pattern, $str)) {
                return $format === 'string' ? $key : $ctr;
            }
            
            $ctr++;
        }

        return '';
    }
}

/**
 * ---------------------------------------
 * Parse the given formated number
 * ---------------------------------------
 *
 * @return  float
 */
if (!function_exists('parseNumber')) {
    function parseNumber($number, $decimalPoint = null)
    {
        if (! is_null($decimalPoint)) {
            $locale = localeconv();
            $decimalPoint = $locale['decimal_point'];
        }

        return doubleval(str_replace($decimalPoint, '.', preg_replace('/[^\d'.preg_quote($decimalPoint).']/', '', $number)));
    }
}

/**
 * ----------------------------------------
 * Display phone number
 * ----------------------------------------
 *
 * @param  string $phone
 * @return string
 */
if (!function_exists('formatPhone')) {
    function formatPhone(string $phone)
    {
        return '('. substr($phone, 0, 3) .') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
    }
}
