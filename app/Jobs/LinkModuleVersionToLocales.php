<?php

namespace App\Jobs;

use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\Locale;
use App\Models\Xlsforms\XlsformModuleVersion;
use App\Services\XlsformTranslationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

class LinkModuleVersionToLocales implements ShouldQueue
{
    use Queueable;

    /** @var Collection<Language> */
    public Collection $languages;

    /**
     * Create a new job instance.
     */
    public function __construct(public XlsformModuleVersion $xlsformModuleVersion, Collection $headings)
    {
        $this->languages = $headings->map(fn(Collection $headings) => $headings
            ->map(fn(string $heading) => (new XlsformTranslationHelper)
                ->getLanguageFromColumnHeader($heading)
            )
        )->flatten();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->xlsformModuleVersion->locales()->syncWithPivotValues(
            ids: $this->languages->map(fn(Language $language): int => $language->defaultLocale->id)->toArray(),
            values: ['has_language_strings' => true, 'needs_update' => false, 'updated_during_import' => true],
            detaching: false,
        );

        // TODO: refactor this so we don't have n+1 database hits for each part.
        // mark locale pivots as needing update if they were not updated during this import
        $this->xlsformModuleVersion->locales()
            ->wherePivot('updated_during_import', false)
            ->get()
            ->each(fn(Locale $locale) => $locale->pivot->update(['needs_update' => true]));

        // mark all locales as no longer updated during import
        $this->xlsformModuleVersion->locales()
            ->wherePivot('updated_during_import', true)
            ->get()
            ->each(fn(Locale $locale) => $locale->pivot->update(['updated_during_import' => false]));
    }
}
