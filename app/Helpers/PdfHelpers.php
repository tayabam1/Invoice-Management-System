<?php

if (!function_exists('formatDate')) {
    /**
     * Format date for PDF template.
     */
    function formatDate($date, $format = 'M d, Y')
    {
        if ($date instanceof \Carbon\Carbon) {
            return $date->format($format);
        }
        
        if (is_string($date)) {
            return \Carbon\Carbon::parse($date)->format($format);
        }
        
        return $date;
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format currency for PDF template.
     */
    function formatCurrency($amount, $showSymbol = true, $currencyCode = 'USD', $position = 'left', $showCode = false)
    {
        $formattedAmount = number_format((float)$amount, 2);
        
        // Get currency symbol from code
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
            'SEK' => 'kr',
            'NOK' => 'kr',
            'DKK' => 'kr',
            'PLN' => 'zł',
            'CZK' => 'Kč',
            'HUF' => 'Ft',
            'RON' => 'lei',
            'BGN' => 'лв',
            'HRK' => 'kn',
            'RUB' => '₽',
            'TRY' => '₺',
            'BRL' => 'R$',
            'MXN' => '$',
            'ARS' => '$',
            'CLP' => '$',
            'COP' => '$',
            'PEN' => 'S/',
            'UYU' => '$U',
            'KRW' => '₩',
            'THB' => '฿',
            'SGD' => 'S$',
            'MYR' => 'RM',
            'IDR' => 'Rp',
            'PHP' => '₱',
            'VND' => '₫',
            'ZAR' => 'R',
            'EGP' => '£',
            'MAD' => 'DH',
            'TND' => 'د.ت',
            'DZD' => 'د.ج',
            'LYD' => 'ل.د',
            'SDG' => 'ج.س.',
            'ETB' => 'Br',
            'KES' => 'KSh',
            'UGX' => 'USh',
            'TZS' => 'TSh',
            'RWF' => 'RF',
            'BIF' => 'FBu',
            'DJF' => 'Fdj',
            'SOS' => 'S',
            'ERN' => 'Nfk',
            'XOF' => 'CFA',
            'XAF' => 'FCFA',
            'GMD' => 'D',
            'GHS' => '₵',
            'SLL' => 'Le',
            'LRD' => 'L$',
            'CVE' => '$',
            'GNF' => 'FG',
            'MRU' => 'UM',
            'SHP' => '£',
            'STN' => 'Db',
            'AZN' => '₼',
            'GEL' => '₾',
            'AMD' => '֏',
            'BYN' => 'Br',
            'MDL' => 'L',
            'UAH' => '₴',
            'UZS' => 'soʻm',
            'KZT' => '₸',
            'KGS' => 'с',
            'TJS' => 'ЅМ',
            'TMT' => 'm',
            'AFN' => '؋',
            'PKR' => '₨',
            'BDT' => '৳',
            'LKR' => '₨',
            'MVR' => '.ރ',
            'NPR' => '₨',
            'BTN' => 'Nu.',
            'MMK' => 'K',
            'LAK' => '₭',
            'KHR' => '៛',
            'MNT' => '₮',
            'ILS' => '₪',
            'JOD' => 'د.ا',
            'LBP' => 'ل.ل',
            'SYP' => '£',
            'IQD' => 'ع.د',
            'KWD' => 'د.ك',
            'BHD' => '.د.ب',
            'QAR' => 'ر.ق',
            'AED' => 'د.إ',
            'OMR' => 'ر.ع.',
            'YER' => '﷼',
            'SAR' => 'ر.س',
            'IRR' => '﷼',
        ];
        
        $symbol = $symbols[$currencyCode] ?? $currencyCode;
        
        if (!$showSymbol) {
            return $formattedAmount;
        }
        
        if ($position === 'left') {
            return $symbol . $formattedAmount;
        } else {
            return $formattedAmount . ' ' . $symbol;
        }
    }
}
