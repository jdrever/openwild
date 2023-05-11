<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SpeciesController extends Controller
{
    /**
     * The QueryService implementation.
     *
     * @var QueryService
     */
    protected $queryService;

    /**
     * Create a new controller instance.
     *
     * @param  QueryService  $queryService
     * @return void
     */
    public function __construct(QueryService $queryService)
    {
        $this->queryService = $queryService;
    }

    /**
     * handles initial species search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $speciesName = $request->input('speciesName') ?? $request->cookie('speciesName') ?? '';
        $speciesNameType = $request->input('speciesNameType') ?? $request->cookie('speciesNameType') ?? 'scientific';
        $speciesGroup = $request->input('speciesGroup') ?? $request->cookie('speciesGroup') ?? 'plants';
        $axiophyteFilter = $request->input('axiophyteFilter') ?? $request->cookie('axiophyteFilter') ?? 'false';



        if (!$request->isMethod('post'))
        {
            return view('species-search',
            [
                'speciesName' => $speciesName,
                'speciesNameType' => $speciesNameType,
                'speciesGroup' => $speciesGroup,
                'axiophyteFilter' => $axiophyteFilter,
                'showResults' => false,
            ]);
        }
        else
        {
            return redirect('/species/'.$speciesName.'/type/'.$speciesNameType.'/group/'.$speciesGroup.'/axiophytes/'.$axiophyteFilter);
        }
    }

    /**
     * Displays a list of species in the dataset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $nameSearchString
     * @param  string  $speciesGroup
     * @param  string  $nameType
     * @param  string  $axiophyteFilter
     * @return \Illuminate\View\View
     */
    public function listForDataset(Request $request, string $speciesName, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, string $refresh = '')
    {
        Cookie::queue('speciesName', $speciesName);

        $this->setCookies($speciesNameType, $speciesGroup, $axiophyteFilter);

        $currentPage = $this->getCurrentPage($request);

        $results = $this->queryService->getSpeciesListForDataset($speciesName, $speciesNameType, $speciesGroup, $axiophyteFilter, $currentPage);

        $viewName = ($refresh == 'refresh') ? 'data-tables/species-in-dataset' : 'species-search';

        return view($viewName,
        [
            'speciesName' => $speciesName,
            'speciesNameType' => $speciesNameType,
            'speciesGroup' => $speciesGroup,
            'axiophyteFilter' => $axiophyteFilter,
            'showResults' => true,
            'results' =>$results,
        ]);
    }

    /**
     * Return the species list for a named site.
     *
     * @param  Request  $request
     * @param  string  $siteName
     * @param  string  $speciesNameType
     * @param  string  $speciesGroup
     * @param  string  $axiophyteFilter
     * @return void
     */
    public function listForSite(Request $request, string $siteName, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, $refresh = '')
    {
        $currentPage = $this->getCurrentPage($request);

        $this->setCookies($speciesNameType, $speciesGroup, $axiophyteFilter);

        $results = $this->queryService->getSpeciesListForSite($siteName, $speciesNameType, $speciesGroup, $axiophyteFilter, $currentPage);

        $viewName = ($refresh == 'refresh') ? 'data-tables/species-in-site' : 'site-species-list';

        return view($viewName,
        [
            'siteName' => $siteName,
            'speciesNameType' => $speciesNameType,
            'speciesGroup' => $speciesGroup,
            'axiophyteFilter' => $axiophyteFilter,
            'results' =>$results,
        ]);
    }

    /**
     * Return the species list for a given square.
     *
     * @param  Request  $request
     * @param  string  $gridSquare
     * @param  string  $speciesNameType
     * @param  string  $speciesGroup
     * @param  string  $axiophyteFilter
     * @param  string  $refresh
     * @return void
     */
    public function listforSquare(Request $request, string $gridSquare, string $speciesNameType, string $speciesGroup, string $axiophyteFilter, string $refresh = '')
    {
        $this->setCookies($speciesNameType, $speciesGroup, $axiophyteFilter);

        //update mapState cookie if have query string
        if ($request->has('mapState')) {
            Cookie::queue('mapState', $request->query('mapState'));
        }
        $currentPage = $this->getCurrentPage($request);
        $results = $this->queryService->getSpeciesListForSquare($gridSquare, $speciesNameType, $speciesGroup, $axiophyteFilter, $currentPage);

        $viewName = ($refresh == 'refresh') ? 'data-tables/species-in-square' : 'square-species-list';

        return view($viewName,
        [
            'gridSquare' => $gridSquare,
            'speciesNameType' => $speciesNameType,
            'speciesGroup' => $speciesGroup,
            'axiophyteFilter' => $axiophyteFilter,
            'showResults' => true,
            'results' =>$results,
        ]);
    }

    public function listforRadius(Request $request, string $longitude, string $latitude, string $refresh = '')
    {

        //update mapState cookie if have query string
        if ($request->has('mapState')) {
            Cookie::queue('mapState', $request->query('mapState'));
        }
        $currentPage = $this->getCurrentPage($request);
        //TODO shouldn't be hardcoding the speciesNameType!
        $speciesNameType = 'Common';
        $axiophyteFilter = false;
        $speciesGroup = 'Both';
        $results = $this->queryService->getSpeciesListForRadius($longitude, $latitude, $speciesNameType, $currentPage);

        $viewName = ($refresh == 'refresh') ? 'data-tables/species-in-radius' : 'radius-species-list';

        return view($viewName,
        [
            'results' =>$results,
            'longitude' =>$longitude,
            'latitude' =>$latitude,
            'speciesNameType' =>$speciesNameType,
            'axiophyteFilter' =>$axiophyteFilter,
            'speciesGroup' =>$speciesGroup,
        ]);
    }

    /**
     * Return the axiophyte list for the dataset.
     *
     * @param  Request  $request
     * @return void
     */
    public function listAllAxiophytes(Request $request)
    {
        //TODO: don't hardcode the $speciesNameType
        $speciesNameType = 'scientific';

        $currentPage = $this->getCurrentPage($request);
        $results = $this->queryService->getAllAxiophytes($speciesNameType, $currentPage);

        //dd($results);
        return view('axiophytes',
        [
            'results' => $results,
            'showResults' => true,
            'speciesNameType' => $speciesNameType
        ]);
    }

    public function getSpeciesNameAutocomplete($speciesName, $nameType, $speciesGroup)
    {
        if ($nameType == "commonName") {
            $nameType .= "Single";
        }

        $results = $this->queryService->getSpeciesNameAutocomplete($speciesName, $nameType, $speciesGroup);

        return view('partials/species-search-autocomplete',
        [
            'results' => $results,
            'speciesName' => $speciesName
        ]);
    }
}
