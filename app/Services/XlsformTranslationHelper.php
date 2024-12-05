<?php

namespace App\Services;

use App\Imports\XlsformTemplate\XlsformTemplateHeadingRowImport;
use App\Models\Language;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplate;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\HeadingRowImport;

class XlsformTranslationHelper
{
    public Collection $languageStringTypes;
    public Collection $languages;

    public function __construct()
    {
        // make 1 set of db queries on instantiation
        $this->languageStringTypes = LanguageStringType::all();
        $this->languages = Language::all();
    }

    public function getLanguageStringTypeFromColumnHeader(string $columnHeader): LanguageStringType
    {
        preg_match($this->getRegexPattern(), $columnHeader, $matches);

        return $this->languageStringTypes->firstWhere('name', $matches[1]);
    }

    public function getLanguageFromColumnHeader(string $columnHeader): Language
    {
        preg_match($this->getRegexPattern(), $columnHeader, $matches);

        return $this->languages->firstWhere('iso_alpha2', $matches[3]);
    }

    public function getDefaultLanguageTemplateFromColumnHeaderAndTemplate(XlsformTemplate $xlsformTemplate, string $columnHeader): XlsformTemplateLanguage
    {
        $language = $this->getLanguageFromColumnHeader($columnHeader);

        return $xlsformTemplate->xlsformTemplateLanguages()
            ->where('language_id', $language->id)
            ->whereHas('locale', fn($query) => $query->where('description', null))
            ->first();
    }

    private function isTranslatableColumn(string $columnHeader): bool
    {
        return preg_match($this->getRegexPattern(), $columnHeader);
    }

    public function getTreanslatableColumnsFromFile(string $filePath): Collection
    {
        // return a keyed collection for survey + choices headings.
        return (new XlsformTemplateHeadingRowImport)
            ->toCollection($filePath)
            ->mapWithKeys(fn($value, $key) => [
                $key => $value[0]
                    ->map(fn($columnHeader) => self::isTranslatableColumn($columnHeader) ? $columnHeader : null)
                    ->filter(),
            ]);
    }

    public function getRegexPattern(): string
    {
        $typeNames = $this->languageStringTypes->pluck('name')->toArray();
        $languageCodes = $this->languages->pluck('iso_alpha2')->toArray();

        return '/^(' . implode('|', $typeNames) . ')([a-z]+)_(' . implode('|', $languageCodes) . ')$/';
    }
}
