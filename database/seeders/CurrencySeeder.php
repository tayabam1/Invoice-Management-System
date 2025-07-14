<?php

namespace Database\Seeders;

use App\Models\Currencies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding currencies...');

        $currencies = [
            ["currency_code" => "AED", "currency_symbol" => "د.إ", "currency_name" => "United Arab Emirates Dirham", "native_name" => "د.إ", "exchange_rate" => 1, "iso" => "AE", "iso3" => "ARE"],
            ["currency_code" => "AFN", "currency_symbol" => "؋", "currency_name" => "Afghan Afghani", "native_name" => "افغانی", "exchange_rate" => 1, "iso" => "AF", "iso3" => "AFG"],
            ["currency_code" => "ALL", "currency_symbol" => "Lek", "currency_name" => "Albanian Lek", "native_name" => "Lek", "exchange_rate" => 1, "iso" => "AL", "iso3" => "ALB"],
            ["currency_code" => "AMD", "currency_symbol" => "֏", "currency_name" => "Armenian Dram", "native_name" => "Դրամ", "exchange_rate" => 1, "iso" => "AM", "iso3" => "ARM"],
            ["currency_code" => "ANG", "currency_symbol" => "ƒ", "currency_name" => "Netherlands Antillean Guilder", "native_name" => "ƒ", "exchange_rate" => 1, "iso" => "AN", "iso3" => "ANT"],
            ["currency_code" => "AOA", "currency_symbol" => "Kz", "currency_name" => "Angolan Kwanza", "native_name" => "Kz", "exchange_rate" => 1, "iso" => "AO", "iso3" => "AGO"],
            ["currency_code" => "ARS", "currency_symbol" => "$", "currency_name" => "Argentine Peso", "native_name" => "$", "exchange_rate" => 1, "iso" => "AR", "iso3" => "ARG"],
            ["currency_code" => "AUD", "currency_symbol" => "$", "currency_name" => "Australian Dollar", "native_name" => "$", "exchange_rate" => 1, "iso" => "AU", "iso3" => "AUS"],
            ["currency_code" => "AWG", "currency_symbol" => "ƒ", "currency_name" => "Aruban Florin", "native_name" => "ƒ", "exchange_rate" => 1, "iso" => "AW", "iso3" => "ABW"],
            ["currency_code" => "AZN", "currency_symbol" => "₼", "currency_name" => "Azerbaijani Manat", "native_name" => "Манат", "exchange_rate" => 1, "iso" => "AZ", "iso3" => "AZE"],
            ["currency_code" => "BAM", "currency_symbol" => "KM", "currency_name" => "Bosnia and Herzegovina Convertible Mark", "native_name" => "KM", "exchange_rate" => 1, "iso" => "BA", "iso3" => "BIH"],
            ["currency_code" => "BBD", "currency_symbol" => "$", "currency_name" => "Barbadian Dollar", "native_name" => "$", "exchange_rate" => 1, "iso" => "BB", "iso3" => "BRB"],
            ["currency_code" => "BDT", "currency_symbol" => "৳", "currency_name" => "Bangladeshi Taka", "native_name" => "৳", "exchange_rate" => 1, "iso" => "BD", "iso3" => "BGD"],
            ["currency_code" => "BGN", "currency_symbol" => "лв", "currency_name" => "Bulgarian Lev", "native_name" => "Лев", "exchange_rate" => 1, "iso" => "BG", "iso3" => "BGR"],
            ["currency_code" => "BHD", "currency_symbol" => ".د.ب", "currency_name" => "Bahraini Dinar", "native_name" => ".د.ب", "exchange_rate" => 1, "iso" => "BH", "iso3" => "BHR"],
            ["currency_code" => "BIF", "currency_symbol" => "FBu", "currency_name" => "Burundian Franc", "native_name" => "FBu", "exchange_rate" => 1, "iso" => "BI", "iso3" => "BDI"],
            ["currency_code" => "BMD", "currency_symbol" => "$", "currency_name" => "Bermudian Dollar", "native_name" => "$", "exchange_rate" => 1, "iso" => "BM", "iso3" => "BMU"],
            ["currency_code" => "BND", "currency_symbol" => "$", "currency_name" => "Brunei Dollar", "native_name" => "$", "exchange_rate" => 1, "iso" => "BN", "iso3" => "BRN"],
            ["currency_code" => "BOB", "currency_symbol" => "Bs.", "currency_name" => "Bolivian Boliviano", "native_name" => "Bs.", "exchange_rate" => 1, "iso" => "BO", "iso3" => "BOL"],
            ["currency_code" => "BOV", "currency_symbol" => "", "currency_name" => "Bolivian Mvdol", "native_name" => "", "exchange_rate" => 1, "iso" => "BO", "iso3" => "BOL"],
            ["currency_code" => "BRL", "currency_symbol" => "R$", "currency_name" => "Brazilian Real", "native_name" => "Real", "exchange_rate" => 1, "iso" => "BR", "iso3" => "BRA"],
            ["currency_code" => "BSD", "currency_symbol" => "$", "currency_name" => "Bahamian Dollar", "native_name" => "$", "exchange_rate" => 1, "iso" => "BS", "iso3" => "BHS"],
            ["currency_code" => "BTN", "currency_symbol" => "Nu.", "currency_name" => "Bhutanese Ngultrum", "native_name" => "དངུལ་ཀྲམ", "exchange_rate" => 1, "iso" => "BT", "iso3" => "BTN"],
            ["currency_code" => "BWP", "currency_symbol" => "P", "currency_name" => "Botswana Pula", "native_name" => "Pula", "exchange_rate" => 1, "iso" => "BW", "iso3" => "BWA"],
            ["currency_code" => "BYN", "currency_symbol" => "Br", "currency_name" => "Belarusian Ruble", "native_name" => "Рубль", "exchange_rate" => 1, "iso" => "BY", "iso3" => "BLR"],
            ["currency_code" => "BZD", "currency_symbol" => "BZ$", "currency_name" => "Belize Dollar", "native_name" => "BZ$", "exchange_rate" => 1, "iso" => "BZ", "iso3" => "BLZ"],
            ["currency_code" => "CAD", "currency_symbol" => "$", "currency_name" => "Canadian Dollar", "native_name" => "$", "exchange_rate" => 1, "iso" => "CA", "iso3" => "CAN"],
            ["currency_code" => "CDF", "currency_symbol" => "FC", "currency_name" => "Congolese Franc", "native_name" => "Franc", "exchange_rate" => 1, "iso" => "CD", "iso3" => "COD"],
            ["currency_code" => "CHE", "currency_symbol" => "", "currency_name" => "WIR Euro", "native_name" => "", "exchange_rate" => 1, "iso" => "CH", "iso3" => "CHE"],
            ["currency_code" => "CHF", "currency_symbol" => "CHF", "currency_name" => "Swiss Franc", "native_name" => "Franken", "exchange_rate" => 1, "iso" => "CH", "iso3" => "CHE"],
            ["currency_code" => "CHW", "currency_symbol" => "", "currency_name" => "WIR Franc", "native_name" => "", "exchange_rate" => 1, "iso" => "CH", "iso3" => "CHE"],
            ["currency_code" => "CLF", "currency_symbol" => "UF", "currency_name" => "Chilean Unit of Account (UF)", "native_name" => "Unidad de Fomento", "exchange_rate" => 1, "iso" => "CL", "iso3" => "CHL"],
            ["currency_code" => "CLP", "currency_symbol" => "$", "currency_name" => "Chilean Peso", "native_name" => "Peso", "exchange_rate" => 1, "iso" => "CL", "iso3" => "CHL"],
            ["currency_code" => "CNY", "currency_symbol" => "¥", "currency_name" => "Chinese Yuan", "native_name" => "人民币", "exchange_rate" => 1, "iso" => "CN", "iso3" => "CHN"],
            ["currency_code" => "COP", "currency_symbol" => "$", "currency_name" => "Colombian Peso", "native_name" => "Peso", "exchange_rate" => 1, "iso" => "CO", "iso3" => "COL"],
            ["currency_code" => "COU", "currency_symbol" => "", "currency_name" => "Colombian Real Value Unit", "native_name" => "", "exchange_rate" => 1, "iso" => "CO", "iso3" => "COL"],
            ["currency_code" => "CRC", "currency_symbol" => "₡", "currency_name" => "Costa Rican Colón", "native_name" => "Colón", "exchange_rate" => 1, "iso" => "CR", "iso3" => "CRI"],
            ["currency_code" => "CUC", "currency_symbol" => "₱", "currency_name" => "Cuban Convertible Peso", "native_name" => "Peso Convertible", "exchange_rate" => 1, "iso" => "CU", "iso3" => "CUB"],
            ["currency_code" => "CUP", "currency_symbol" => "₱", "currency_name" => "Cuban Peso", "native_name" => "Peso Cubano", "exchange_rate" => 1, "iso" => "CU", "iso3" => "CUB"],
            ["currency_code" => "CVE", "currency_symbol" => "$", "currency_name" => "Cape Verdean Escudo", "native_name" => "Escudo Cabo-verdiano", "exchange_rate" => 1, "iso" => "CV", "iso3" => "CPV"],
            ["currency_code" => "CZK", "currency_symbol" => "Kč", "currency_name" => "Czech Koruna", "native_name" => "Koruna česká", "exchange_rate" => 1, "iso" => "CZ", "iso3" => "CZE"],
            ["currency_code" => "DJF", "currency_symbol" => "Fdj", "currency_name" => "Djiboutian Franc", "native_name" => "فرنك جيبوتي", "exchange_rate" => 1, "iso" => "DJ", "iso3" => "DJI"],
            ["currency_code" => "DKK", "currency_symbol" => "kr", "currency_name" => "Danish Krone", "native_name" => "Dansk krone", "exchange_rate" => 1, "iso" => "DK", "iso3" => "DNK"],
            ["currency_code" => "DOP", "currency_symbol" => "RD$", "currency_name" => "Dominican Peso", "native_name" => "Peso Dominicano", "exchange_rate" => 1, "iso" => "DO", "iso3" => "DOM"],
            ["currency_code" => "DZD", "currency_symbol" => "د.ج", "currency_name" => "Algerian Dinar", "native_name" => "دينار جزائري", "exchange_rate" => 1, "iso" => "DZ", "iso3" => "DZA"],
            ["currency_code" => "EGP", "currency_symbol" => "E£", "currency_name" => "Egyptian Pound", "native_name" => "جنيه مصري", "exchange_rate" => 1, "iso" => "EG", "iso3" => "EGY"],
            ["currency_code" => "ERN", "currency_symbol" => "Nfk", "currency_name" => "Eritrean Nakfa", "native_name" => "ናቕፋ", "exchange_rate" => 1, "iso" => "ER", "iso3" => "ERI"],
            ["currency_code" => "ETB", "currency_symbol" => "Br", "currency_name" => "Ethiopian Birr", "native_name" => "ብር", "exchange_rate" => 1, "iso" => "ET", "iso3" => "ETH"],
            ["currency_code" => "EUR", "currency_symbol" => "€", "currency_name" => "Euro", "native_name" => "Euro", "exchange_rate" => 1, "iso" => "EU", "iso3" => "EUU"],
            ["currency_code" => "FJD", "currency_symbol" => "$", "currency_name" => "Fijian Dollar", "native_name" => "Dollar Fijian", "exchange_rate" => 1, "iso" => "FJ", "iso3" => "FJI"],
            ["currency_code" => "FKP", "currency_symbol" => "£", "currency_name" => "Falkland Islands Pound", "native_name" => "Pound", "exchange_rate" => 1, "iso" => "FK", "iso3" => "FLK"],
            ["currency_code" => "GBP", "currency_symbol" => "£", "currency_name" => "British Pound Sterling", "native_name" => "Pound Sterling", "exchange_rate" => 1, "iso" => "GB", "iso3" => "GBR"],
            ["currency_code" => "GEL", "currency_symbol" => "₾", "currency_name" => "Georgian Lari", "native_name" => "ლარი", "exchange_rate" => 1, "iso" => "GE", "iso3" => "GEO"],
            ["currency_code" => "GHS", "currency_symbol" => "GH¢", "currency_name" => "Ghanaian Cedi", "native_name" => "Cedi", "exchange_rate" => 1, "iso" => "GH", "iso3" => "GHA"],
            ["currency_code" => "GIP", "currency_symbol" => "£", "currency_name" => "Gibraltar Pound", "native_name" => "Pound", "exchange_rate" => 1, "iso" => "GI", "iso3" => "GIB"],
            ["currency_code" => "GMD", "currency_symbol" => "D", "currency_name" => "Gambian Dalasi", "native_name" => "Dalasi", "exchange_rate" => 1, "iso" => "GM", "iso3" => "GMB"],
            ["currency_code" => "GNF", "currency_symbol" => "FG", "currency_name" => "Guinean Franc", "native_name" => "Franc Guinéen", "exchange_rate" => 1, "iso" => "GN", "iso3" => "GIN"],
            ["currency_code" => "GTQ", "currency_symbol" => "Q", "currency_name" => "Guatemalan Quetzal", "native_name" => "Quetzal", "exchange_rate" => 1, "iso" => "GT", "iso3" => "GTM"],
            ["currency_code" => "GYD", "currency_symbol" => "$", "currency_name" => "Guyanese Dollar", "native_name" => "Dollar Guyanais", "exchange_rate" => 1, "iso" => "GY", "iso3" => "GUY"],
            ["currency_code" => "HKD", "currency_symbol" => "$", "currency_name" => "Hong Kong Dollar", "native_name" => "港元", "exchange_rate" => 1, "iso" => "HK", "iso3" => "HKG"],
            ["currency_code" => "HNL", "currency_symbol" => "L", "currency_name" => "Honduran Lempira", "native_name" => "Lempira", "exchange_rate" => 1, "iso" => "HN", "iso3" => "HND"],
            ["currency_code" => "HRK", "currency_symbol" => "kn", "currency_name" => "Croatian Kuna", "native_name" => "Kuna", "exchange_rate" => 1, "iso" => "HR", "iso3" => "HRV"],
            ["currency_code" => "HTG", "currency_symbol" => "G", "currency_name" => "Haitian Gourde", "native_name" => "Gourde Haïtienne", "exchange_rate" => 1, "iso" => "HT", "iso3" => "HTI"],
            ["currency_code" => "HUF", "currency_symbol" => "Ft", "currency_name" => "Hungarian Forint", "native_name" => "Forint", "exchange_rate" => 1, "iso" => "HU", "iso3" => "HUN"],
            ["currency_code" => "IDR", "currency_symbol" => "Rp", "currency_name" => "Indonesian Rupiah", "native_name" => "Rupiah", "exchange_rate" => 1, "iso" => "ID", "iso3" => "IDN"],
            ["currency_code" => "ILS", "currency_symbol" => "₪", "currency_name" => "Israeli New Shekel", "native_name" => "שקל חדש", "exchange_rate" => 1, "iso" => "IL", "iso3" => "ISR"],
            ["currency_code" => "INR", "currency_symbol" => "₹", "currency_name" => "Indian Rupee", "native_name" => "भारतीय रुपया", "exchange_rate" => 1, "iso" => "IN", "iso3" => "IND"],
            ["currency_code" => "IQD", "currency_symbol" => "ع.د", "currency_name" => "Iraqi Dinar", "native_name" => "دينار عراقي", "exchange_rate" => 1, "iso" => "IQ", "iso3" => "IRQ"],
            ["currency_code" => "IRR", "currency_symbol" => "ریال", "currency_name" => "Iranian Rial", "native_name" => "ریال ایران", "exchange_rate" => 1, "iso" => "IR", "iso3" => "IRN"],
            ["currency_code" => "ISK", "currency_symbol" => "kr", "currency_name" => "Icelandic Króna", "native_name" => "Íslensk Króna", "exchange_rate" => 1, "iso" => "IS", "iso3" => "ISL"],
            ["currency_code" => "JMD", "currency_symbol" => "$", "currency_name" => "Jamaican Dollar", "native_name" => "Jamaican Dollar", "exchange_rate" => 1, "iso" => "JM", "iso3" => "JAM"],
            ["currency_code" => "JOD", "currency_symbol" => "د.أ", "currency_name" => "Jordanian Dinar", "native_name" => "دينار أردني", "exchange_rate" => 1, "iso" => "JO", "iso3" => "JOR"],
            ["currency_code" => "JPY", "currency_symbol" => "¥", "currency_name" => "Japanese Yen", "native_name" => "日本円", "exchange_rate" => 1, "iso" => "JP", "iso3" => "JPN"],
            ["currency_code" => "KES", "currency_symbol" => "KSh", "currency_name" => "Kenyan Shilling", "native_name" => "Shilingi ya Kenya", "exchange_rate" => 1, "iso" => "KE", "iso3" => "KEN"],
            ["currency_code" => "KGS", "currency_symbol" => "с", "currency_name" => "Kyrgyzstani Som", "native_name" => "Кыргыз сом", "exchange_rate" => 1, "iso" => "KG", "iso3" => "KGZ"],
            ["currency_code" => "KHR", "currency_symbol" => "៛", "currency_name" => "Cambodian Riel", "native_name" => "រៀល", "exchange_rate" => 1, "iso" => "KH", "iso3" => "KHM"],
            ["currency_code" => "KMF", "currency_symbol" => "CF", "currency_name" => "Comorian Franc", "native_name" => "فرنك قمري", "exchange_rate" => 1, "iso" => "KM", "iso3" => "COM"],
            ["currency_code" => "KPW", "currency_symbol" => "₩", "currency_name" => "North Korean Won", "native_name" => "조선 원", "exchange_rate" => 1, "iso" => "KP", "iso3" => "PRK"],
            ["currency_code" => "KRW", "currency_symbol" => "₩", "currency_name" => "South Korean Won", "native_name" => "대한민국 원", "exchange_rate" => 1, "iso" => "KR", "iso3" => "KOR"],
            ["currency_code" => "KWD", "currency_symbol" => "د.ك", "currency_name" => "Kuwaiti Dinar", "native_name" => "دينار كويتي", "exchange_rate" => 1, "iso" => "KW", "iso3" => "KWT"],
            ["currency_code" => "KYD", "currency_symbol" => "$", "currency_name" => "Cayman Islands Dollar", "native_name" => "Cayman Dollar", "exchange_rate" => 1, "iso" => "KY", "iso3" => "CYM"],
            ["currency_code" => "KZT", "currency_symbol" => "₸", "currency_name" => "Kazakhstani Tenge", "native_name" => "Қазақстан теңгесі", "exchange_rate" => 1, "iso" => "KZ", "iso3" => "KAZ"],
            ["currency_code" => "LAK", "currency_symbol" => "₭", "currency_name" => "Lao Kip", "native_name" => "ກີບ", "exchange_rate" => 1, "iso" => "LA", "iso3" => "LAO"],
            ["currency_code" => "LBP", "currency_symbol" => "ل.ل", "currency_name" => "Lebanese Pound", "native_name" => "الليرة اللبنانية", "exchange_rate" => 1, "iso" => "LB", "iso3" => "LBN"],
            ["currency_code" => "LKR", "currency_symbol" => "Rs", "currency_name" => "Sri Lankan Rupee", "native_name" => "ශ්‍රී ලංකා රුපියල", "exchange_rate" => 1, "iso" => "LK", "iso3" => "LKA"],
            ["currency_code" => "LRD", "currency_symbol" => "$", "currency_name" => "Liberian Dollar", "native_name" => "Liberian Dollar", "exchange_rate" => 1, "iso" => "LR", "iso3" => "LBR"],
            ["currency_code" => "LSL", "currency_symbol" => "M", "currency_name" => "Lesotho Loti", "native_name" => "Loti", "exchange_rate" => 1, "iso" => "LS", "iso3" => "LSO"],
            ["currency_code" => "LTL", "currency_symbol" => "Lt", "currency_name" => "Lithuanian Litas", "native_name" => "Litas", "exchange_rate" => 1, "iso" => "LT", "iso3" => "LTU"],
            ["currency_code" => "LVL", "currency_symbol" => "Ls", "currency_name" => "Latvian Lats", "native_name" => "Latu", "exchange_rate" => 1, "iso" => "LV", "iso3" => "LVA"],
            ["currency_code" => "LYD", "currency_symbol" => "ل.د", "currency_name" => "Libyan Dinar", "native_name" => "دينار ليبي", "exchange_rate" => 1, "iso" => "LY", "iso3" => "LBY"],
            ["currency_code" => "MAD", "currency_symbol" => "د.م.", "currency_name" => "Moroccan Dirham", "native_name" => "درهم مغربي", "exchange_rate" => 1, "iso" => "MA", "iso3" => "MAR"],
            ["currency_code" => "MDL", "currency_symbol" => "lei", "currency_name" => "Moldovan Leu", "native_name" => "Leu", "exchange_rate" => 1, "iso" => "MD", "iso3" => "MDA"],
            ["currency_code" => "MGA", "currency_symbol" => "Ar", "currency_name" => "Malagasy Ariary", "native_name" => "Ariary", "exchange_rate" => 1, "iso" => "MG", "iso3" => "MDG"],
            ["currency_code" => "MKD", "currency_symbol" => "ден", "currency_name" => "Macedonian Denar", "native_name" => "Денар", "exchange_rate" => 1, "iso" => "MK", "iso3" => "MKD"],
            ["currency_code" => "MMK", "currency_symbol" => "Ks", "currency_name" => "Myanmar Kyat", "native_name" => "ကျပ်", "exchange_rate" => 1, "iso" => "MM", "iso3" => "MMR"],
            ["currency_code" => "MNT", "currency_symbol" => "₮", "currency_name" => "Mongolian Tugrik", "native_name" => "төгрөг", "exchange_rate" => 1, "iso" => "MN", "iso3" => "MNG"],
            ["currency_code" => "MOP", "currency_symbol" => "P", "currency_name" => "Macanese Pataca", "native_name" => "Pataca", "exchange_rate" => 1, "iso" => "MO", "iso3" => "MAC"],
            ["currency_code" => "MRO", "currency_symbol" => "UM", "currency_name" => "Mauritanian Ouguiya", "native_name" => "أوقية", "exchange_rate" => 1, "iso" => "MR", "iso3" => "MRT"],
            ["currency_code" => "MRU", "currency_symbol" => "MRU", "currency_name" => "Mauritanian Ouguiya", "native_name" => "أوقية", "exchange_rate" => 1, "iso" => "MR", "iso3" => "MRT"],
            ["currency_code" => "MUR", "currency_symbol" => "₨", "currency_name" => "Mauritian Rupee", "native_name" => "ரூபி", "exchange_rate" => 1, "iso" => "MU", "iso3" => "MUS"],
            ["currency_code" => "MVR", "currency_symbol" => "MVR", "currency_name" => "Maldivian Rufiyaa", "native_name" => "ރ.", "exchange_rate" => 1, "iso" => "MV", "iso3" => "MDV"],
            ["currency_code" => "MWK", "currency_symbol" => "MK", "currency_name" => "Malawian Kwacha", "native_name" => "Kwacha", "exchange_rate" => 1, "iso" => "MW", "iso3" => "MWI"],
            ["currency_code" => "MXN", "currency_symbol" => "$", "currency_name" => "Mexican Peso", "native_name" => "Peso Mexicano", "exchange_rate" => 1, "iso" => "MX", "iso3" => "MEX"],
            ["currency_code" => "MYR", "currency_symbol" => "RM", "currency_name" => "Malaysian Ringgit", "native_name" => "Ringgit Malaysia", "exchange_rate" => 1, "iso" => "MY", "iso3" => "MYS"],
            ["currency_code" => "MZN", "currency_symbol" => "MT", "currency_name" => "Mozambican Metical", "native_name" => "Metical", "exchange_rate" => 1, "iso" => "MZ", "iso3" => "MOZ"],
            ["currency_code" => "NAD", "currency_symbol" => "$", "currency_name" => "Namibian Dollar", "native_name" => "Namibian Dollar", "exchange_rate" => 1, "iso" => "NA", "iso3" => "NAM"],
            ["currency_code" => "NGN", "currency_symbol" => "₦", "currency_name" => "Nigerian Naira", "native_name" => "Naira", "exchange_rate" => 1, "iso" => "NG", "iso3" => "NGA"],
            ["currency_code" => "NIO", "currency_symbol" => "C$", "currency_name" => "Nicaraguan Córdoba", "native_name" => "Córdoba", "exchange_rate" => 1, "iso" => "NI", "iso3" => "NIC"],
            ["currency_code" => "NOK", "currency_symbol" => "kr", "currency_name" => "Norwegian Krone", "native_name" => "Norsk krone", "exchange_rate" => 1, "iso" => "NO", "iso3" => "NOR"],
            ["currency_code" => "NPR", "currency_symbol" => "Rs", "currency_name" => "Nepalese Rupee", "native_name" => "नेपाली रूपैयाँ", "exchange_rate" => 1, "iso" => "NP", "iso3" => "NPL"],
            ["currency_code" => "NZD", "currency_symbol" => "$", "currency_name" => "New Zealand Dollar", "native_name" => "New Zealand Dollar", "exchange_rate" => 1, "iso" => "NZ", "iso3" => "NZL"],
            ["currency_code" => "OMR", "currency_symbol" => "ر.ع.", "currency_name" => "Omani Rial", "native_name" => "ريال عماني", "exchange_rate" => 1, "iso" => "OM", "iso3" => "OMN"],
            ["currency_code" => "PAB", "currency_symbol" => "B/.", "currency_name" => "Panamanian Balboa", "native_name" => "Balboa", "exchange_rate" => 1, "iso" => "PA", "iso3" => "PAN"],
            ["currency_code" => "PEN", "currency_symbol" => "S/.", "currency_name" => "Peruvian Nuevo Sol", "native_name" => "Nuevo Sol", "exchange_rate" => 1, "iso" => "PE", "iso3" => "PER"],
            ["currency_code" => "PGK", "currency_symbol" => "K", "currency_name" => "Papua New Guinean Kina", "native_name" => "Kina", "exchange_rate" => 1, "iso" => "PG", "iso3" => "PNG"],
            ["currency_code" => "PHP", "currency_symbol" => "₱", "currency_name" => "Philippine Peso", "native_name" => "Piso", "exchange_rate" => 1, "iso" => "PH", "iso3" => "PHL"],
            ["currency_code" => "PKR", "currency_symbol" => "₨", "currency_name" => "Pakistani Rupee", "native_name" => "روپیہ", "exchange_rate" => 1, "iso" => "PK", "iso3" => "PAK"],
            ["currency_code" => "PLN", "currency_symbol" => "zł", "currency_name" => "Polish Zloty", "native_name" => "Złoty", "exchange_rate" => 1, "iso" => "PL", "iso3" => "POL"],
            ["currency_code" => "PYG", "currency_symbol" => "₲", "currency_name" => "Paraguayan Guarani", "native_name" => "Guaraní", "exchange_rate" => 1, "iso" => "PY", "iso3" => "PRY"],
            ["currency_code" => "QAR", "currency_symbol" => "ر.ق", "currency_name" => "Qatari Rial", "native_name" => "ريال قطري", "exchange_rate" => 1, "iso" => "QA", "iso3" => "QAT"],
            ["currency_code" => "RON", "currency_symbol" => "lei", "currency_name" => "Romanian Leu", "native_name" => "Leu", "exchange_rate" => 1, "iso" => "RO", "iso3" => "ROU"],
            ["currency_code" => "RSD", "currency_symbol" => "дин", "currency_name" => "Serbian Dinar", "native_name" => "Динар", "exchange_rate" => 1, "iso" => "RS", "iso3" => "SRB"],
            ["currency_code" => "RUB", "currency_symbol" => "₽", "currency_name" => "Russian Ruble", "native_name" => "Рубль", "exchange_rate" => 1, "iso" => "RU", "iso3" => "RUS"],
            ["currency_code" => "RWF", "currency_symbol" => "", "currency_name" => "Rwandan Franc", "native_name" => "Franco Ruandese", "exchange_rate" => 1, "iso" => "RW", "iso3" => "RWA"],
            ["currency_code" => "SAR", "currency_symbol" => "ر.س", "currency_name" => "Saudi Riyal", "native_name" => "ريال سعودي", "exchange_rate" => 1, "iso" => "SA", "iso3" => "SAU"],
            ["currency_code" => "SBD", "currency_symbol" => "$", "currency_name" => "Solomon Islands Dollar", "native_name" => "Solomon Islands Dollar", "exchange_rate" => 1, "iso" => "SB", "iso3" => "SLB"],
            ["currency_code" => "SCR", "currency_symbol" => "₨", "currency_name" => "Seychellois Rupee", "native_name" => "Roupie Seychelloise", "exchange_rate" => 1, "iso" => "SC", "iso3" => "SYC"],
            ["currency_code" => "SDG", "currency_symbol" => "ج.س.", "currency_name" => "Sudanese Pound", "native_name" => "جنيه سوداني", "exchange_rate" => 1, "iso" => "SD", "iso3" => "SDN"],
            ["currency_code" => "SEK", "currency_symbol" => "kr", "currency_name" => "Swedish Krona", "native_name" => "Krona", "exchange_rate" => 1, "iso" => "SE", "iso3" => "SWE"],
            ["currency_code" => "SGD", "currency_symbol" => "$", "currency_name" => "Singapore Dollar", "native_name" => "Dollar Singapura", "exchange_rate" => 1, "iso" => "SG", "iso3" => "SGP"],
            ["currency_code" => "SHP", "currency_symbol" => "£", "currency_name" => "Saint Helena Pound", "native_name" => "Pound Saint-Hélène", "exchange_rate" => 1, "iso" => "SH", "iso3" => "SHN"],
            ["currency_code" => "SLL", "currency_symbol" => "Le", "currency_name" => "Sierra Leonean Leone", "native_name" => "Leone", "exchange_rate" => 1, "iso" => "SL", "iso3" => "SLE"],
            ["currency_code" => "SOS", "currency_symbol" => "Sh", "currency_name" => "Somali Shilling", "native_name" => "Shilin Soomaali", "exchange_rate" => 1, "iso" => "SO", "iso3" => "SOM"],
            ["currency_code" => "SPL", "currency_symbol" => "", "currency_name" => "São Tomé and Príncipe Dobra", "native_name" => "Dobra", "exchange_rate" => 1, "iso" => "ST", "iso3" => "STP"],
            ["currency_code" => "SRD", "currency_symbol" => "$", "currency_name" => "Surinamese Dollar", "native_name" => "Surinaamse Dollar", "exchange_rate" => 1, "iso" => "SR", "iso3" => "SUR"],
            ["currency_code" => "SSP", "currency_symbol" => "", "currency_name" => "South Sudanese Pound", "native_name" => "جنيه سوداني", "exchange_rate" => 1, "iso" => "SS", "iso3" => "SSD"],
            ["currency_code" => "STN", "currency_symbol" => "Db", "currency_name" => "São Tomé and Príncipe Dobra", "native_name" => "Dobra", "exchange_rate" => 1, "iso" => "ST", "iso3" => "STP"],
            ["currency_code" => "SYP", "currency_symbol" => "ل.س", "currency_name" => "Syrian Pound", "native_name" => "ليرة سورية", "exchange_rate" => 1, "iso" => "SY", "iso3" => "SYR"],
            ["currency_code" => "SZL", "currency_symbol" => "L", "currency_name" => "Swazi Lilangeni", "native_name" => "Lilangeni", "exchange_rate" => 1, "iso" => "SZ", "iso3" => "SWZ"],
            ["currency_code" => "THB", "currency_symbol" => "฿", "currency_name" => "Thai Baht", "native_name" => "บาท", "exchange_rate" => 1, "iso" => "TH", "iso3" => "THA"],
            ["currency_code" => "TJS", "currency_symbol" => "SM", "currency_name" => "Tajikistani Somoni", "native_name" => "Сомони", "exchange_rate" => 1, "iso" => "TJ", "iso3" => "TJK"],
            ["currency_code" => "TMT", "currency_symbol" => "m", "currency_name" => "Turkmenistan Manat", "native_name" => "Manat", "exchange_rate" => 1, "iso" => "TM", "iso3" => "TKM"],
            ["currency_code" => "TND", "currency_symbol" => "د.ت", "currency_name" => "Tunisian Dinar", "native_name" => "دينار تونسي", "exchange_rate" => 1, "iso" => "TN", "iso3" => "TUN"],
            ["currency_code" => "TOP", "currency_symbol" => "T$", "currency_name" => "Tongan Paʻanga", "native_name" => "Paʻanga", "exchange_rate" => 1, "iso" => "TO", "iso3" => "TON"],
            ["currency_code" => "TRY", "currency_symbol" => "₺", "currency_name" => "Turkish Lira", "native_name" => "Türk Lirası", "exchange_rate" => 1, "iso" => "TR", "iso3" => "TUR"],
            ["currency_code" => "TTD", "currency_symbol" => "$", "currency_name" => "Trinidad and Tobago Dollar", "native_name" => "Trinidad and Tobago Dollar", "exchange_rate" => 1, "iso" => "TT", "iso3" => "TTO"],
            ["currency_code" => "TWD", "currency_symbol" => "$", "currency_name" => "New Taiwan Dollar", "native_name" => "新台幣", "exchange_rate" => 1, "iso" => "TW", "iso3" => "TWN"],
            ["currency_code" => "TZS", "currency_symbol" => "Sh", "currency_name" => "Tanzanian Shilling", "native_name" => "Shilingi ya Tanzania", "exchange_rate" => 1, "iso" => "TZ", "iso3" => "TZA"],
            ["currency_code" => "UAH", "currency_symbol" => "₴", "currency_name" => "Ukrainian Hryvnia", "native_name" => "Гривня", "exchange_rate" => 1, "iso" => "UA", "iso3" => "UKR"],
            ["currency_code" => "UGX", "currency_symbol" => "Sh", "currency_name" => "Ugandan Shilling", "native_name" => "Shillingi ya Uganda", "exchange_rate" => 1, "iso" => "UG", "iso3" => "UGA"],
            ["currency_code" => "USD", "currency_symbol" => "$", "currency_name" => "United States Dollar", "native_name" => "Dollar", "exchange_rate" => 1, "iso" => "US", "iso3" => "USA"],
            ["currency_code" => "UYU", "currency_symbol" => "$", "currency_name" => "Uruguayan Peso", "native_name" => "Peso Uruguayo", "exchange_rate" => 1, "iso" => "UY", "iso3" => "URY"],
            ["currency_code" => "UZS", "currency_symbol" => "so'm", "currency_name" => "Uzbekistani Som", "native_name" => "so'm", "exchange_rate" => 1, "iso" => "UZ", "iso3" => "UZB"],
            ["currency_code" => "VES", "currency_symbol" => "Bs.S", "currency_name" => "Venezuelan Bolívar Soberano", "native_name" => "Bolívar Soberano", "exchange_rate" => 1, "iso" => "VE", "iso3" => "VEN"],
            ["currency_code" => "VND", "currency_symbol" => "₫", "currency_name" => "Vietnamese Dong", "native_name" => "Đồng", "exchange_rate" => 1, "iso" => "VN", "iso3" => "VNM"],
            ["currency_code" => "VUV", "currency_symbol" => "Vt", "currency_name" => "Vanuatu Vatu", "native_name" => "Vatu", "exchange_rate" => 1, "iso" => "VU", "iso3" => "VUT"],
            ["currency_code" => "WST", "currency_symbol" => "$", "currency_name" => "Samoan Tala", "native_name" => "Tālā", "exchange_rate" => 1, "iso" => "WS", "iso3" => "WSM"],
            ["currency_code" => "XAF", "currency_symbol" => "FCFA", "currency_name" => "Central African CFA Franc", "native_name" => "Franc CFA", "exchange_rate" => 1, "iso" => "CM", "iso3" => "CMR"],
            ["currency_code" => "XCD", "currency_symbol" => "$", "currency_name" => "East Caribbean Dollar", "native_name" => "Dollar des Caraïbes orientales", "exchange_rate" => 1, "iso" => "XC", "iso3" => "XCD"],
            ["currency_code" => "XOF", "currency_symbol" => "CFA", "currency_name" => "West African CFA Franc", "native_name" => "Franc CFA", "exchange_rate" => 1, "iso" => "WF", "iso3" => "GAB"],
            ["currency_code" => "XPF", "currency_symbol" => "F", "currency_name" => "CFP Franc", "native_name" => "Franc CFP", "exchange_rate" => 1, "iso" => "PF", "iso3" => "PYF"],
            ["currency_code" => "YER", "currency_symbol" => "ر.ي", "currency_name" => "Yemeni Rial", "native_name" => "ريال يمني", "exchange_rate" => 1, "iso" => "YE", "iso3" => "YEM"],
            ["currency_code" => "ZAR", "currency_symbol" => "R", "currency_name" => "South African Rand", "native_name" => "Rand", "exchange_rate" => 1, "iso" => "ZA", "iso3" => "ZAF"],
            ["currency_code" => "ZMW", "currency_symbol" => "K", "currency_name" => "Zambian Kwacha", "native_name" => "Kwacha", "exchange_rate" => 1, "iso" => "ZM", "iso3" => "ZMB"],
            ["currency_code" => "ZWL", "currency_symbol" => "$", "currency_name" => "Zimbabwean Dollar", "native_name" => "Zimbabwe Dollar", "exchange_rate" => 1, "iso" => "ZW", "iso3" => "ZWE"]
        ];

        try {
            DB::table('currencies')->delete();

            $bar = $this->command->getOutput()->createProgressBar(count($currencies));
            $bar->start();

            foreach ($currencies as $currency) {
                $currencyData = collect($currency)->except('native_name')->toArray();

                try {
                    Currencies::create($currencyData);
                } catch (\Exception $e) {
                    $this->command->error("\nError creating currency {$currency['currency_code']}: {$e->getMessage()}");
                    continue;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->command->info("\nCurrencies seeded successfully!");

        } catch (\Exception $e) {
            $this->command->error("Error seeding currencies: {$e->getMessage()}");
        }
    }
}
