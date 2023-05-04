<?php

namespace App\Services;

use App\Interfaces\QueryService;
use App\Models\AutocompleteResult;
use App\Models\OccurrenceResult;
use App\Models\QueryResult;
use Illuminate\Support\Facades\Cache;

class CachedNbnQueryService implements QueryService
{
    public function getSpeciesListForDataset(string $speciesName, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSpeciesListForDataset:'.$this->getSubDomain().'-'.$speciesName.'-'.$speciesNameType.'-'.$speciesGroup.'-'.$axiophyteFilter.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSpeciesListForDataset($speciesName, $speciesNameType, $speciesGroup, $axiophyteFilter, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    /**
     * Get the records for a single species.
     *
     * e.g. https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=9
     *
     * The taxon needs to be in double quotes so the complete string is searched for rather than a partial.
     */
    public function getSingleSpeciesRecordsForDataset(string $speciesName, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSingleSpeciesRecordsForDataset:'.$this->getSubDomain().'-'.$speciesName.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSingleSpeciesRecordsForDataset($speciesName, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSingleOccurenceRecord(string $uuid): OccurrenceResult
    {
        $cacheKey = 'getSingleOccurenceRecord:'.$this->getSubDomain().'-'.$uuid;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSingleOccurenceRecord($uuid);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSiteListForDataset(string $siteName, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSiteListForDataset:'.$this->getSubDomain().'-'.$siteName.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSiteListForDataset($siteName, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSpeciesListForSite(string $siteName, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSpeciesListForSite:'.$this->getSubDomain().'-'.$siteName.'-'.$speciesNameType.'-'.$speciesGroup.'-'.$axiophyteFilter.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSpeciesListForSite($siteName, $speciesNameType, $speciesGroup, $axiophyteFilter, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSingleSpeciesRecordsForSite(string $siteName, string $speciesName, int $currentPage): QueryResult
    {
        $cacheKey = 'getSingleSpeciesRecordsForSite:'.$this->getSubDomain().'-'.$siteName.'-'.$speciesName.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSingleSpeciesRecordsForSite($siteName, $speciesName, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSpeciesListForSquare(string $gridSquare, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSpeciesListForSquare:'.$this->getSubDomain().'-'.$gridSquare.'-'.$speciesNameType.'-'.$speciesGroup.'-'.$axiophyteFilter.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSpeciesListForSquare($gridSquare, $speciesNameType, $speciesGroup, $axiophyteFilter, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getAllAxiophytes(string $speciesNameType, int $currentPage)
    {
        $cacheKey = 'getAllAxiophytes:'.$this->getSubDomain().'-'.$speciesNameType.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getAllAxiophytes($speciesNameType, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSingleSpeciesRecordsForSquare(string $gridSquare, string $speciesName, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSingleSpeciesRecordsForSquare:'.$this->getSubDomain().'-'.$gridSquare.'-'.$speciesName.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSingleSpeciesRecordsForSquare($gridSquare, $speciesName, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSpeciesListForRadius(string $longitude, string $latitude, string $speciesNameType, int $currentPage = 1): QueryResult
    {
        $cacheKey = 'getSpeciesListForSquare:'.$this->getSubDomain().'-'.$longitude.'-'.$latitude.'-'.$speciesNameType.'-'.$currentPage;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSpeciesListForRadius($longitude, $latitude, $speciesNameType, $currentPage);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSpeciesNameAutocomplete(string $speciesName, string $speciesNameType, string $speciesGroup): AutocompleteResult
    {
        $cacheKey = 'getSpeciesNameAutocomplete:'.$this->getSubDomain().'-'.$speciesName.'-'.$speciesNameType.'-'.$speciesGroup;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSpeciesNameAutocomplete($speciesName, $speciesNameType, $speciesGroup);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    public function getSiteNameAutocomplete(string $siteName): AutocompleteResult
    {
        $cacheKey = 'getSiteNameAutocomplete:'.$this->getSubDomain().'-'.$siteName;
        if (config('core.caching') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $nbnQueryService = new NbnQueryService();
        $queryResult = $nbnQueryService->getSiteNameAutocomplete($siteName);

        Cache::put($cacheKey, $queryResult);

        return $queryResult;
    }

    private function getSubDomain()
    {
        return explode('.', $_SERVER['HTTP_HOST'])[0];
    }
}
?>



