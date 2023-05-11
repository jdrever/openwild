<?php

namespace App\Services;

/**
 * Facade for the NBN records end point.
 *
 * See the NBN Atlas Query Primer for details about using the API
 * https://docs.google.com/document/d/1FiVasGGZ3kRPnu5347GPAef7Tr5LvvghCS6x82xnfu4/edit
 *
 * @author  Careful Digital <hello@careful.digital>
 */
class NbnQueryBuilder
{
    const BASE_URL = 'https://records-ws.nbnatlas.org';
    const SPECIES_URL = 'https://species-ws.nbnatlas.org';

    const OCCURRENCES_SEARCH = '/occurrences/search';
    const OCCURRENCE = '/occurrence';
    const OCCURRENCE_DOWNLOAD = '/occurrences/index/download';

    const AUTOCOMPLETE_SEARCH_SPECIES = '/search';
    const AUTOCOMPLETE_SEARCH = '/search/auto';
    const AUTOCOMPLETE_OCCURRENCES_SEARCH = '/autocomplete/search';

    const SCIENTIFIC_NAME = 'taxon_name';

    const SORT_BY_YEAR = 'year';
    const SORT_DESCENDING = 'desc';
    /**
     * The unique data resource id code.
     *
     * The id can by found by searching the NBN Atlas data sets at
     * https://registry.nbnatlas.org/datasets. The id is located in the URL of a
     * data resource page and consists of the letters "dr" followed by a number;
     * e.g., https://registry.nbnatlas.org/public/showDataResource/dr782
     *
     * dr782 is the SEDN data set.
     *
     * Use dr1323 for Worcestershire data if SEDN data not available:
     * https://registry.nbnatlas.org/public/showDataResource/dr1323
     *
     * @var string
     */
    private $dataResourceUid = '';

    /**
     * The filter for axiophytes in Shropshire, as supplied by Sophie at NBN is species_list_uid:dr1940.
     *
     * @var string
     */
    private $axiophyteFilter = '';

    /**
     * TODO: Describe what the $searchType member variable is for.
     *
     * @var string
     */
    public $searchType = '';

    /**
     * TODO: Describe what the $facets member variable is for.
     *
     * @var string
     */
    public $facets;

    /**
     * TODO: Describe what the $fsort member variable is for.
     *
     * @var string
     */
    public $fsort;

    /**
     * Sets the number of paged records returned by each NBN query.
     *
     * @var int
     */
    public $pageSize;

    /**
     * Sets the number of the current page.
     *
     * @var int
     */
    public $currentPage;

    /**
     * TODO: Describe what the $sort member variable is for.
     *
     * @var string
     */
    public $sort;

    /**
     * The direction of the sort.
     *
     * @var string
     */
    public $dir = 'asc';

    /**
     * Constructor.
     *
     * Accepts a searchType fragment which indicates the NBN Atlas API search type to
     * perform. Defaults to Occurrence search: https://api.nbnatlas.org/#ws3
     *
     * See https://api.nbnatlas.org/ for others.
     *
     * @param  string  $searchType  NBN Atlas API search type
     */
    public function __construct(string $searchType = self::OCCURRENCES_SEARCH)
    {
        $this->searchType = $searchType;
        $this->pageSize = config('core.resultsPerPage');
        $this->dataResourceUid = config('core.dataResourceId');
        $this->axiophyteFilter = config('core.axiophyteFilter');
        $this->startingLongitude = config('core.startingLongitude');
        $this->startingLatitude = config('core.startingLatitude');
        $this->radius = config('core.radius');
        $this->showAllData = config('core.showAllData');
        $this->currentPage = 1;
    }

    /**
     * Return the base search query string.
     *
     * @param  string  $url  The full url to query
     * @return string
     */
    private function getQueryString(string $url): string
    {
        $queryString = $url.'?';
        $queryParameters = array_merge($this->getCoreParameters(), $this->extraQueryParameters);

        //dd($this->extraQueryParameters);
        $fqAndParameters = implode('%20AND%20', $this->filterQueryParameters);
        $fqNotParameters = '';
        if (count($this->filterNotQueryParameters) > 0) {
            $fqNotParameters = '%20AND%20NOT%20'.implode('%20AND%20NOT%20', $this->filterNotQueryParameters);
        }
        $queryString .= 'q='.implode('%20AND%20', $queryParameters).'&';
        $queryString .= 'fq='.$fqAndParameters.$fqNotParameters.'&';
        $queryString .= 'facets='.$this->facets.'&';
        $queryString .= 'sort='.$this->sort.'&';
        $queryString .= 'fsort='.$this->fsort.'&';
        $queryString .= 'dir='.$this->dir.'&';

        return $queryString;
    }

    private function getCoreParameters(): array
    {
        $coreParameters = [];
        if ($this->dataResourceUid) {
            $coreParameters[] = 'data_resource_uid:'.$this->dataResourceUid;
        }
        if (isset($this->showAllData)) {
            $coreParameters[] = '*:*';
        }

        if ($this->radius) {
            $coreParameters[] = '*:*&lat='.$this->startingLatitude.'&lon='.$this->startingLongitude.'&radius='.$this->radius;
        }

        return $coreParameters;
    }

    /**
     * Return the url for single record download querty.
     *
     * @param  string  $url  The full url to query
     * @return string
     */
    private function getSingleRecordDownloadUrl(string $url, string $occurrenceId): string
    {
        $queryString = $url.'?';
        $queryString .= 'fq=occurrence_id:'.$occurrenceId.'&';

        return $queryString;
    }

    /**
     * Return the base url and searchType (really only used for getting a single
     * occurence record).
     *
     * @return string
     */
    public function url(): string
    {
        return $this::BASE_URL.$this->searchType;
    }

    public function isFacetedSearch(): bool
    {
        return ! empty($this->facets);
    }

    /**
     * Return the query string without paging
     * Used to determine total number of records for query
     * without paging.
     *
     * @return string
     */
    public function getUnpagedQueryString($flimit = -1): string
    {
        $queryString = $this->getQueryString($this::BASE_URL.$this->searchType);
        $queryString .= 'pageSize=0&flimit=' . $flimit;

        return $queryString;
    }

    /**
     * Return the query string for paging.
     *
     * @return string
     */
    public function getPagingQueryString(): string
    {
        $queryString = $this->getQueryString($this::BASE_URL.$this->searchType);

        if ($this->isFacetedSearch()) {
            $queryString .= 'flimit='.$this->pageSize.'&facet.offset='.(($this->currentPage - 1) * $this->pageSize);
        } else {
            $queryString .= 'pageSize='.$this->pageSize.'&start='.(($this->currentPage - 1) * $this->pageSize);
        }

        return $queryString;
    }

    /**
     * Return the query string for downloading the data.
     *
     * @return string
     */
    public function getDownloadQueryString(): string
    {
        $queryString = $this->getQueryString($this::BASE_URL.'/occurrences/index/download');
        $queryString .= '&reasonTypeId=11&fileType=csv';

        return $queryString;
    }

    /**
     * Return the query string for downloading the data.
     *
     * @return string
     */
    public function getSingleRecordDownloadQueryString($occurrenceId): string
    {
        $queryString = $this->getSingleRecordDownloadUrl($this::BASE_URL.'occurrences/index/download', $occurrenceId);
        $queryString .= '&reasonTypeId=11';

        return $queryString;
    }

    public function getDotMapQueryString($speciesGuid)
    {
        return $this::BASE_URL.'/mapping/wms/reflect?Q=lsid:'.$speciesGuid.'&ENV=colourmode:osgrid;color:ffff00;name:circle;size:4;opacity:0.5;gridlabels:true;gridres:singlegrid&fq='.implode('%20AND%20', $this->getCoreFQParameters());
    }

    private function getCoreFQParameters(): array
    {
        $coreFQParameters = [];
        if (isset($this->dataResourceUid)) {
            $coreFQParameters[] = 'data_resource_uid:'.$this->dataResourceUid;
        }

        if (isset($this->radius)) {
            $coreFQParameters[] = 'lat_long:'.$this->startingLatitude.','.$this->startingLongitude;
        }

        return $coreFQParameters;
    }

    /**
     * Return the query string for downloading the data.
     *
     * @return string
     */
    public function getAutocompleteQueryString($speciesName, $speciesNameType, $speciesGroup): string
    {
        return $this::SPECIES_URL.$this->searchType.'?q='.$speciesNameType.':'.$this->prepareAutocompleteSearchString($speciesName, true).'&fq=rank:species%20AND%20speciesGroup:'.$speciesGroup.'&pageSize=10';
    }

    /**
     * Keeps an internal array of query filter parameters.
     *
     * A list of available index fields can be found at
     * https://species-ws.nbnatlas.org/admin/indexFields
     *
     * @var string[] Array of strings
     */
    protected $filterQueryParameters = [];

    /**
     * Adds to the internal list of filter query parameters.
     *
     * A list of available index fields can be found at
     * https://species-ws.nbnatlas.org/admin/indexFields
     *
     * @param  string  $filterQueryParameter  A single filter query parameter
     * @return self
     */
    public function add(string $filterQueryParameter): self
    {
        $this->filterQueryParameters[] = $filterQueryParameter;

        return $this;
    }

    /**
     * Keeps an internal array of query filter parameters.
     *
     * A list of available index fields can be found at
     * https://species-ws.nbnatlas.org/admin/indexFields
     *
     * @var string[] Array of strings
     */
    protected $filterNotQueryParameters = [];

    /**
     * Adds to the internal list of filter NOT query parameters.
     *
     * A list of available index fields can be found at
     * https://species-ws.nbnatlas.org/admin/indexFields
     *
     * @param  string  $filterNotQueryParameter  A single filter query parameter
     * @return self
     */
    public function addNot(string $filterNotQueryParameter): self
    {
        $this->filterNotQueryParameters[] = $filterNotQueryParameter;

        return $this;
    }

    /**
     * Adds a taxon_name or common_name query parameter, dependent on $speciesNameType.
     *
     * @param  string  $speciesNameType  either scientific or common
     * @param  string  $speciesName  e.g. Hedera or Ivy
     * @return self
     */
    public function addSpeciesNameType(string $speciesNameType, string $speciesName, bool $isFacetedSearch = false, bool $isPartialName = false): self
    {
        if ($speciesNameType === 'scientific') {
            $this->addScientificName($speciesName, $isFacetedSearch, $isPartialName);
        }

        if ($speciesNameType === 'common') {
            $this->addCommonName($speciesName, $isFacetedSearch, $isPartialName);
        }

        return $this;
    }

    /**
     * Adds a taxon_name and associated facets (names_and_lsid).
     *
     * @param  string  $speciesName
     * @param  bool  $isFacetedSearch
     * @param  bool  $isPartialName
     * @return self
     */
    public function addScientificName(string $speciesName, bool $isFacetedSearch = false, bool $isPartialName = false): self
    {
        $speciesName = $this->prepareSearchString($speciesName, $isPartialName);

        $this->add('taxon_name:'.$speciesName);
        if ($isFacetedSearch) {
            $this->facets = 'names_and_lsid';
            $this->fsort = 'index';
        }

        return $this;
    }

    /**
     * Adds a common_name and associated facets (common_name_and_lsid).
     *
     * @param  string  $speciesName
     * @param  bool  $isFacetedSearch
     * @param  bool  $isPartialName
     * @return self
     */
    public function addCommonName(string $speciesName, bool $isFacetedSearch = false, bool $isPartialName = false): self
    {
        $speciesName = $this->prepareSearchString($speciesName, $isPartialName);
        $this->add('common_name:'.$speciesName);
        if ($isFacetedSearch) {
            $this->facets = 'common_name_and_lsid';
            $this->fsort = 'index';
        }

        return $this;
    }

    /**
     * Adds a query parameter for species_group.
     *
     * @param  string  $speciesGroup  either Plants, Bryophytes or Both
     * @return self
     */
    public function addSpeciesGroup(string $speciesGroup): self
    {
        //TODO: #4 refactor speciesGroup handling to make more generic
        $speciesGroup = ucfirst($speciesGroup);
        if ($speciesGroup === 'Plants') {
            $this->add('species_group:'.'Plants');
            $this->addNot('species_group:'.'Bryophytes');
        } elseif ($speciesGroup === 'Bryophytes') {
            $this->add('species_group:'.'Bryophytes');
        } elseif ($speciesGroup === 'Worms') {
            $this->add('species_group:'.'Worms');
        } else {
            //TODO: this is a bit hacky, could do with implmentation of addOr
            $this->add('(species_group:'.'Plants+OR+species_group:Bryophytes)');
        }

        return $this;
    }

    /**
     * adds an Axiopyte filter query parameter
     * based on the AXIOPHYTE_FILTER environmental variable.
     *
     * @return self
     */
    public function addAxiophyteFilter(): self
    {
        $this->filterQueryParameters[] = $this->axiophyteFilter;

        return $this;
    }

    /**
     * Adds a location_id query.
     *
     * @param  string  $location
     * @return self
     */
    public function addLocation(string $location): self
    {
        $this->add('location_id:"'.$this->prepareLocationString($location).'"');

        return $this;
    }

    /**
     * Adds a location_id query.
     *
     * @param  string  $gridSquare
     * @return self
     */
    public function add1kmGridSquare(string $gridSquare): self
    {
        $this->add('grid_ref_1000:"'.rawurlencode($gridSquare).'"');

        return $this;
    }

    public function addRadius(string $longitude, $latitude): self
    {
        $this->add('long:'.rawurlencode($longitude));
        $this->add('lat:'.rawurlencode($latitude));
        //TODO: shouldn't be hardcoding radius
        $this->add('radius:5');

        return $this;
    }

    /**
     * Adds a location_id query.
     *
     * @param  string  $location
     * @return self
     */
    public function addWildcardLocationParameter(string $location): self
    {
        $this->facets = 'location_id';
        $this->addExtraQueryParameter('location_id:'.$this->prepareLocationString($location).'*');

        return $this;
    }

    /**
     * List of extra parameters to be added to the query (in addition to data_resource_uid).
     *
     * @var string[] Array of strings
     */
    protected $extraQueryParameters = [];

    /**
     * Adds to the list of extra query parameters.
     *
     * @param  string  $extraQueryParameter  A single extra query parameter
     * @return self
     */
    public function addExtraQueryParameter(string $extraQueryParameter): self
    {
        $this->extraQueryParameters[] = $extraQueryParameter;

        return $this;
    }

    /**
     * Adds a sort.
     *
     * @param  string  $sort
     * @return self
     */
    public function sortBy(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Set the facts for a species name type.
     *
     * @param  string  $nameType  : either scientific or common
     * @return self
     */
    public function setSpeciesNameType(string $nameType): self
    {
        if ($nameType === 'scientific') {
            $this->facets = 'names_and_lsid';
        }

        if ($nameType === 'common') {
            $this->facets = 'common_name_and_lsid';
        }

        return $this;
    }

    /**
     * Adds a faceted sort.
     *
     * @param  string  $facetedSort
     * @return self
     */
    public function setFacetedSort(string $facetedSort): self
    {
        $this->fsort = $facetedSort;

        return $this;
    }

    /**
     * Sets the direction (dir in Solr).
     *
     * @param  string  $direction
     * @return self
     */
    public function setDirection(string $direction): self
    {
        $this->dir = $direction;

        return $this;
    }

    /**
     * Deals with multi-word search terms and prepares
     * theme for use by the NBN API by adding ANDs and
     * setting to all lower case.
     *
     * @param  string  $searchString  the search term to prepare
     * @return string the prepared search search name
     */
    private function prepareSearchString(string $searchString, bool $isPartialName)
    {
        if (! $isPartialName) {
            return '"'.rawurlencode($searchString).'"';
        }

        $searchString = ucfirst(strtolower($searchString));
        $searchWords = explode(' ', $searchString);
        if (count($searchWords) === 1) {
            return '*'.rawurlencode($searchString).'*';
        }
        $preparedSearchString = $searchWords[0].'*';
        unset($searchWords[0]);
        foreach ($searchWords as $searchWord) {
            $preparedSearchString .= '+AND+'.$searchWord;
        }
        $preparedSearchString = str_replace(' ', '+%2B', $preparedSearchString);

        return $preparedSearchString;
    }

    /**
     * Deals with multi-word search terms and prepares
     * theme for use by the NBN API by adding ANDs and
     * setting to all lower case.
     *
     * @param  string  $searchString  the search term to prepare
     * @return string the prepared search search name
     */
    private function prepareAutocompleteSearchString(string $searchString, bool $isPartialName)
    {
        if (! $isPartialName) {
            return '"'.rawurlencode($searchString).'"';
        }

        $searchString = ucfirst(strtolower($searchString));
        $searchWords = explode(' ', $searchString);
        $preparedSearchString = implode('*+AND+*', $searchWords);

        return '*'.$preparedSearchString.'*';
    }

    /**
     * Makes upper cases and replaces spaces.
     *
     * @param  string  $location
     * @return string
     */
    private function prepareLocationString(string $location): string
    {
        // API respects case - upper case all words in search string
        $location = ucwords($location);
        // Replace spaces with "\%20" so the query searches for the whole string
        $location = str_replace(' ', "\%20", $location);

        return $location;
    }
}
