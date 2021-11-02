<?php

namespace Astrotomic\Translatable\TranslationResolvers;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseFallbackResolver extends BaseTranslationResolver
{
    /**
     * @param string $locale
     *
     * @return string[]
     */
    abstract protected function fallbackLocales(string $locale): array;

    protected function getFallbackLocales(string $locale, TranslatableContract $translatable): array
    {
        return $this->fallbackLocales($locale);
    }

    public function resolve(
        TranslatableContract $translatable,
        string               $locale,
        bool                 $withFallback,
        Collection           $alreadyCheckedLocales
    ): ?Model
    {
        if (!$withFallback) {
            return null;
        }

        foreach (array_filter($this->getFallbackLocales($locale, $translatable)) as $fallbackLocale) {
            $translation = $this->resolveTranslationByLocale(
                $translatable,
                $fallbackLocale,
                $alreadyCheckedLocales
            );

            if ($translation instanceof Model) {
                return $translation;
            }
        }

        return null;
    }

    public function resolveWithAttribute(
        TranslatableContract $translatable,
        string               $locale,
        bool                 $withFallback,
        Collection           $alreadyCheckedLocales,
        string               $attribute
    ): ?Model
    {
        if (!$withFallback) {
            return null;
        }

        foreach (array_filter($this->getFallbackLocales($locale, $translatable)) as $fallbackLocale) {
            $translation = $this->resolveTranslationWithAttributeByLocale(
                $translatable,
                $fallbackLocale,
                $alreadyCheckedLocales,
                $attribute
            );

            if ($translation instanceof Model) {
                return $translation;
            }
        }

        return null;
    }
}
