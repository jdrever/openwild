<?php

namespace App\Interfaces;

use App\Models\AutocompleteResult;
use App\Models\OccurrenceResult;
use App\Models\QueryResult;

interface QueryService
{
    public function getSpeciesListForDataset(string $speciesName, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, int $currentPage): QueryResult;

    public function getSingleSpeciesRecordsForDataset(string $speciesName, int $currentPage): QueryResult;

    public function getSingleOccurenceRecord(string $occurenceId): OccurrenceResult;

    public function getSiteListForDataset(string $siteName, int $currentPage): QueryResult;

    public function getSpeciesListForSite(string $siteName, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, int $currentPage): QueryResult;

    public function getSingleSpeciesRecordsForSite(string $siteName, string $speciesName, int $currentPage): QueryResult;

    public function getSpeciesListForSquare(string $gridSquare, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, int $currentPage): QueryResult;

    public function getSingleSpeciesRecordsForSquare(string $gridSquare, string $speciesName, int $currentPage): QueryResult;

    public function getSpeciesListForRadius(string $longitude, string $latitude, string $speciesNameType, int $currentPage): QueryResult;

    public function getAllAxiophytes(string $speciesNameType, int $currentPage);

    public function getSpeciesNameAutocomplete(string $speciesName): AutocompleteResult;

    public function getSiteNameAutocomplete(string $siteName): AutocompleteResult;
}
