<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['id' => 1, 'iso_alpha2' => 'ab', 'name' => 'Abkhazian'],
            ['id' => 2, 'iso_alpha2' => 'aa', 'name' => 'Afar'],
            ['id' => 3, 'iso_alpha2' => 'af', 'name' => 'Afrikaans'],
            ['id' => 4, 'iso_alpha2' => 'ak', 'name' => 'Akan'],
            ['id' => 5, 'iso_alpha2' => 'sq', 'name' => 'Albanian'],
            ['id' => 6, 'iso_alpha2' => 'am', 'name' => 'Amharic'],
            ['id' => 7, 'iso_alpha2' => 'ar', 'name' => 'Arabic'],
            ['id' => 8, 'iso_alpha2' => 'an', 'name' => 'Aragonese'],
            ['id' => 9, 'iso_alpha2' => 'hy', 'name' => 'Armenian'],
            ['id' => 10, 'iso_alpha2' => 'as', 'name' => 'Assamese'],
            ['id' => 11, 'iso_alpha2' => 'av', 'name' => 'Avaric'],
            ['id' => 12, 'iso_alpha2' => 'ae', 'name' => 'Avestan'],
            ['id' => 13, 'iso_alpha2' => 'ay', 'name' => 'Aymara'],
            ['id' => 14, 'iso_alpha2' => 'az', 'name' => 'Azerbaijani'],
            ['id' => 15, 'iso_alpha2' => 'bm', 'name' => 'Bambara'],
            ['id' => 16, 'iso_alpha2' => 'ba', 'name' => 'Bashkir'],
            ['id' => 17, 'iso_alpha2' => 'eu', 'name' => 'Basque'],
            ['id' => 18, 'iso_alpha2' => 'be', 'name' => 'Belarusian'],
            ['id' => 19, 'iso_alpha2' => 'bn', 'name' => 'Bengali'],
            ['id' => 20, 'iso_alpha2' => 'bh', 'name' => 'Bihari languages'],
            ['id' => 21, 'iso_alpha2' => 'bi', 'name' => 'Bislama'],
            ['id' => 22, 'iso_alpha2' => 'bs', 'name' => 'Bosnian'],
            ['id' => 23, 'iso_alpha2' => 'br', 'name' => 'Breton'],
            ['id' => 24, 'iso_alpha2' => 'bg', 'name' => 'Bulgarian'],
            ['id' => 25, 'iso_alpha2' => 'my', 'name' => 'Burmese'],
            ['id' => 26, 'iso_alpha2' => 'ca', 'name' => 'Catalan'],
            ['id' => 27, 'iso_alpha2' => 'ch', 'name' => 'Chamorro'],
            ['id' => 28, 'iso_alpha2' => 'ce', 'name' => 'Chechen'],
            ['id' => 29, 'iso_alpha2' => 'zh', 'name' => 'Chinese'],
            ['id' => 30, 'iso_alpha2' => 'cu', 'name' => 'Church Slavic'],
            ['id' => 31, 'iso_alpha2' => 'cv', 'name' => 'Chuvash'],
            ['id' => 32, 'iso_alpha2' => 'kw', 'name' => 'Cornish'],
            ['id' => 33, 'iso_alpha2' => 'co', 'name' => 'Corsican'],
            ['id' => 34, 'iso_alpha2' => 'cr', 'name' => 'Cree'],
            ['id' => 35, 'iso_alpha2' => 'hr', 'name' => 'Croatian'],
            ['id' => 36, 'iso_alpha2' => 'cs', 'name' => 'Czech'],
            ['id' => 37, 'iso_alpha2' => 'da', 'name' => 'Danish'],
            ['id' => 38, 'iso_alpha2' => 'dv', 'name' => 'Divehi'],
            ['id' => 39, 'iso_alpha2' => 'nl', 'name' => 'Dutch'],
            ['id' => 40, 'iso_alpha2' => 'dz', 'name' => 'Dzongkha'],
            ['id' => 41, 'iso_alpha2' => 'en', 'name' => 'English'],
            ['id' => 42, 'iso_alpha2' => 'eo', 'name' => 'Esperanto'],
            ['id' => 43, 'iso_alpha2' => 'et', 'name' => 'Estonian'],
            ['id' => 44, 'iso_alpha2' => 'ee', 'name' => 'Ewe'],
            ['id' => 45, 'iso_alpha2' => 'fo', 'name' => 'Faroese'],
            ['id' => 46, 'iso_alpha2' => 'fj', 'name' => 'Fijian'],
            ['id' => 47, 'iso_alpha2' => 'fi', 'name' => 'Finnish'],
            ['id' => 48, 'iso_alpha2' => 'fr', 'name' => 'French'],
            ['id' => 49, 'iso_alpha2' => 'ff', 'name' => 'Fulah'],
            ['id' => 50, 'iso_alpha2' => 'gl', 'name' => 'Galician'],
            ['id' => 51, 'iso_alpha2' => 'ka', 'name' => 'Georgian'],
            ['id' => 52, 'iso_alpha2' => 'de', 'name' => 'German'],
            ['id' => 53, 'iso_alpha2' => 'el', 'name' => 'Greek'],
            ['id' => 54, 'iso_alpha2' => 'gn', 'name' => 'Guarani'],
            ['id' => 55, 'iso_alpha2' => 'gu', 'name' => 'Gujarati'],
            ['id' => 56, 'iso_alpha2' => 'ht', 'name' => 'Haitian Creole'],
            ['id' => 57, 'iso_alpha2' => 'ha', 'name' => 'Hausa'],
            ['id' => 58, 'iso_alpha2' => 'he', 'name' => 'Hebrew'],
            ['id' => 59, 'iso_alpha2' => 'hz', 'name' => 'Herero'],
            ['id' => 60, 'iso_alpha2' => 'hi', 'name' => 'Hindi'],
            ['id' => 61, 'iso_alpha2' => 'ho', 'name' => 'Hiri Motu'],
            ['id' => 62, 'iso_alpha2' => 'hu', 'name' => 'Hungarian'],
            ['id' => 63, 'iso_alpha2' => 'is', 'name' => 'Icelandic'],
            ['id' => 64, 'iso_alpha2' => 'io', 'name' => 'Ido'],
            ['id' => 65, 'iso_alpha2' => 'ig', 'name' => 'Igbo'],
            ['id' => 66, 'iso_alpha2' => 'id', 'name' => 'Indonesian'],
            ['id' => 67, 'iso_alpha2' => 'ia', 'name' => 'Interlingua'],
            ['id' => 68, 'iso_alpha2' => 'ie', 'name' => 'Interlingue'],
            ['id' => 69, 'iso_alpha2' => 'iu', 'name' => 'Inuktitut'],
            ['id' => 70, 'iso_alpha2' => 'ik', 'name' => 'Inupiaq'],
            ['id' => 71, 'iso_alpha2' => 'ga', 'name' => 'Irish'],
            ['id' => 72, 'iso_alpha2' => 'it', 'name' => 'Italian'],
            ['id' => 73, 'iso_alpha2' => 'ja', 'name' => 'Japanese'],
            ['id' => 74, 'iso_alpha2' => 'jv', 'name' => 'Javanese'],
            ['id' => 75, 'iso_alpha2' => 'kl', 'name' => 'Kalaallisut'],
            ['id' => 76, 'iso_alpha2' => 'kn', 'name' => 'Kannada'],
            ['id' => 77, 'iso_alpha2' => 'kr', 'name' => 'Kanuri'],
            ['id' => 78, 'iso_alpha2' => 'ks', 'name' => 'Kashmiri'],
            ['id' => 79, 'iso_alpha2' => 'kk', 'name' => 'Kazakh'],
            ['id' => 80, 'iso_alpha2' => 'km', 'name' => 'Khmer'],
            ['id' => 81, 'iso_alpha2' => 'ki', 'name' => 'Kikuyu'],
            ['id' => 82, 'iso_alpha2' => 'rw', 'name' => 'Kinyarwanda'],
            ['id' => 83, 'iso_alpha2' => 'ky', 'name' => 'Kirghiz'],
            ['id' => 84, 'iso_alpha2' => 'kv', 'name' => 'Komi'],
            ['id' => 85, 'iso_alpha2' => 'kg', 'name' => 'Kongo'],
            ['id' => 86, 'iso_alpha2' => 'ko', 'name' => 'Korean'],
            ['id' => 87, 'iso_alpha2' => 'kj', 'name' => 'Kuanyama'],
            ['id' => 88, 'iso_alpha2' => 'ku', 'name' => 'Kurdish'],
            ['id' => 89, 'iso_alpha2' => 'lo', 'name' => 'Lao'],
            ['id' => 90, 'iso_alpha2' => 'la', 'name' => 'Latin'],
            ['id' => 91, 'iso_alpha2' => 'lv', 'name' => 'Latvian'],
            ['id' => 92, 'iso_alpha2' => 'li', 'name' => 'Limburgish'],
            ['id' => 93, 'iso_alpha2' => 'ln', 'name' => 'Lingala'],
            ['id' => 94, 'iso_alpha2' => 'lt', 'name' => 'Lithuanian'],
            ['id' => 95, 'iso_alpha2' => 'lu', 'name' => 'Luba-Katanga'],
            ['id' => 96, 'iso_alpha2' => 'lb', 'name' => 'Luxembourgish'],
            ['id' => 97, 'iso_alpha2' => 'mk', 'name' => 'Macedonian'],
            ['id' => 98, 'iso_alpha2' => 'mg', 'name' => 'Malagasy'],
            ['id' => 99, 'iso_alpha2' => 'ms', 'name' => 'Malay'],
            ['id' => 100, 'iso_alpha2' => 'ml', 'name' => 'Malayalam'],
            ['id' => 101, 'iso_alpha2' => 'mt', 'name' => 'Maltese'],
            ['id' => 102, 'iso_alpha2' => 'gv', 'name' => 'Manx'],
            ['id' => 103, 'iso_alpha2' => 'mi', 'name' => 'Maori'],
            ['id' => 104, 'iso_alpha2' => 'mr', 'name' => 'Marathi'],
            ['id' => 105, 'iso_alpha2' => 'mh', 'name' => 'Marshallese'],
            ['id' => 106, 'iso_alpha2' => 'mn', 'name' => 'Mongolian'],
            ['id' => 107, 'iso_alpha2' => 'na', 'name' => 'Nauru'],
            ['id' => 108, 'iso_alpha2' => 'nv', 'name' => 'Navajo'],
            ['id' => 109, 'iso_alpha2' => 'nd', 'name' => 'North Ndebele'],
            ['id' => 110, 'iso_alpha2' => 'nr', 'name' => 'South Ndebele'],
            ['id' => 111, 'iso_alpha2' => 'ng', 'name' => 'Ndonga'],
            ['id' => 112, 'iso_alpha2' => 'ne', 'name' => 'Nepali'],
            ['id' => 113, 'iso_alpha2' => 'no', 'name' => 'Norwegian'],
            ['id' => 114, 'iso_alpha2' => 'nb', 'name' => 'Norwegian Bokmål'],
            ['id' => 115, 'iso_alpha2' => 'nn', 'name' => 'Norwegian Nynorsk'],
            ['id' => 116, 'iso_alpha2' => 'ny', 'name' => 'Nyanja'],
            ['id' => 117, 'iso_alpha2' => 'oc', 'name' => 'Occitan'],
            ['id' => 118, 'iso_alpha2' => 'oj', 'name' => 'Ojibwa'],
            ['id' => 119, 'iso_alpha2' => 'or', 'name' => 'Oriya'],
            ['id' => 120, 'iso_alpha2' => 'om', 'name' => 'Oromo'],
            ['id' => 121, 'iso_alpha2' => 'os', 'name' => 'Ossetian'],
            ['id' => 122, 'iso_alpha2' => 'pa', 'name' => 'Panjabi'],
            ['id' => 123, 'iso_alpha2' => 'fa', 'name' => 'Persian'],
            ['id' => 124, 'iso_alpha2' => 'pl', 'name' => 'Polish'],
            ['id' => 125, 'iso_alpha2' => 'ps', 'name' => 'Pashto'],
            ['id' => 126, 'iso_alpha2' => 'pt', 'name' => 'Portuguese'],
            ['id' => 127, 'iso_alpha2' => 'qu', 'name' => 'Quechua'],
            ['id' => 128, 'iso_alpha2' => 'rm', 'name' => 'Romansh'],
            ['id' => 129, 'iso_alpha2' => 'rn', 'name' => 'Rundi'],
            ['id' => 130, 'iso_alpha2' => 'ro', 'name' => 'Romanian'],
            ['id' => 131, 'iso_alpha2' => 'ru', 'name' => 'Russian'],
            ['id' => 132, 'iso_alpha2' => 'sm', 'name' => 'Samoan'],
            ['id' => 133, 'iso_alpha2' => 'sg', 'name' => 'Sango'],
            ['id' => 134, 'iso_alpha2' => 'sa', 'name' => 'Sanskrit'],
            ['id' => 135, 'iso_alpha2' => 'sc', 'name' => 'Sardinian'],
            ['id' => 136, 'iso_alpha2' => 'sr', 'name' => 'Serbian'],
            ['id' => 137, 'iso_alpha2' => 'sn', 'name' => 'Shona'],
            ['id' => 138, 'iso_alpha2' => 'sd', 'name' => 'Sindhi'],
            ['id' => 139, 'iso_alpha2' => 'si', 'name' => 'Sinhala'],
            ['id' => 140, 'iso_alpha2' => 'sk', 'name' => 'Slovak'],
            ['id' => 141, 'iso_alpha2' => 'sl', 'name' => 'Slovenian'],
            ['id' => 142, 'iso_alpha2' => 'so', 'name' => 'Somali'],
            ['id' => 143, 'iso_alpha2' => 'st', 'name' => 'Southern Sotho'],
            ['id' => 144, 'iso_alpha2' => 'es', 'name' => 'Spanish'],
            ['id' => 145, 'iso_alpha2' => 'su', 'name' => 'Sundanese'],
            ['id' => 146, 'iso_alpha2' => 'sw', 'name' => 'Swahili'],
            ['id' => 147, 'iso_alpha2' => 'ss', 'name' => 'Swati'],
            ['id' => 148, 'iso_alpha2' => 'sv', 'name' => 'Swedish'],
            ['id' => 149, 'iso_alpha2' => 'tl', 'name' => 'Tagalog'],
            ['id' => 150, 'iso_alpha2' => 'ty', 'name' => 'Tahitian'],
            ['id' => 151, 'iso_alpha2' => 'tg', 'name' => 'Tajik'],
            ['id' => 152, 'iso_alpha2' => 'ta', 'name' => 'Tamil'],
            ['id' => 153, 'iso_alpha2' => 'tt', 'name' => 'Tatar'],
            ['id' => 154, 'iso_alpha2' => 'te', 'name' => 'Telugu'],
            ['id' => 155, 'iso_alpha2' => 'th', 'name' => 'Thai'],
            ['id' => 156, 'iso_alpha2' => 'bo', 'name' => 'Tibetan'],
            ['id' => 157, 'iso_alpha2' => 'ti', 'name' => 'Tigrinya'],
            ['id' => 158, 'iso_alpha2' => 'to', 'name' => 'Tonga'],
            ['id' => 159, 'iso_alpha2' => 'ts', 'name' => 'Tsonga'],
            ['id' => 160, 'iso_alpha2' => 'tn', 'name' => 'Tswana'],
            ['id' => 161, 'iso_alpha2' => 'tr', 'name' => 'Turkish'],
            ['id' => 162, 'iso_alpha2' => 'tk', 'name' => 'Turkmen'],
            ['id' => 163, 'iso_alpha2' => 'tw', 'name' => 'Twi'],
            ['id' => 164, 'iso_alpha2' => 'ug', 'name' => 'Uighur'],
            ['id' => 165, 'iso_alpha2' => 'uk', 'name' => 'Ukrainian'],
            ['id' => 166, 'iso_alpha2' => 'ur', 'name' => 'Urdu'],
            ['id' => 167, 'iso_alpha2' => 'uz', 'name' => 'Uzbek'],
            ['id' => 168, 'iso_alpha2' => 've', 'name' => 'Venda'],
            ['id' => 169, 'iso_alpha2' => 'vi', 'name' => 'Vietnamese'],
            ['id' => 170, 'iso_alpha2' => 'vo', 'name' => 'Volapük'],
            ['id' => 171, 'iso_alpha2' => 'wa', 'name' => 'Walloon'],
            ['id' => 172, 'iso_alpha2' => 'cy', 'name' => 'Welsh'],
            ['id' => 173, 'iso_alpha2' => 'wo', 'name' => 'Wolof'],
            ['id' => 174, 'iso_alpha2' => 'xh', 'name' => 'Xhosa'],
            ['id' => 175, 'iso_alpha2' => 'yi', 'name' => 'Yiddish'],
            ['id' => 176, 'iso_alpha2' => 'yo', 'name' => 'Yoruba'],
            ['id' => 177, 'iso_alpha2' => 'za', 'name' => 'Zhuang'],
            ['id' => 178, 'iso_alpha2' => 'zu', 'name' => 'Zulu'],
        ];

        DB::table('languages')->insert($languages);

    }
}
