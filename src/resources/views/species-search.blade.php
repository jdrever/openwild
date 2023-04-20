
<x-layout>

<h2 class="text-start text-md-center">Search for @if (config('core.axiophytesOnly')) an Axiophyte @else a Species @endif in {{ config('core.region') }}</h2>

<form action="{{ url()->current() }}" method="post">
@csrf
<div class="row mb-2">
	<div id="search-container" class="col-lg-8 mx-auto" position="relative">
		<label for="search" class="form-label visually-hidden">Species name</label>
		<div class="input-group">
            <input type="text" id="speciesName" class="form-control" name="speciesName" aria-describedby="search-help" placeholder="Species name" value="{{ $speciesName }}"  autocomplete="off"/>
            <button type="submit" data-refresh="true" class="btn btn-primary">List Species</button>
		</div>
        <div id="autocomplete-container"></div>
		<small id="search-help" class="form-text text-start text-md-center d-block">Enter all or part of a species name. Try something like "{{ config('core.speciesNameExample')}}".</small>
	</div>
</div>

@include('partials/search-selections')
</form>

<div id="data-table">
    @include('data-tables/species-in-dataset')
</div>

<script type="text/javascript" src="/js/species-name-autocomplete.js"></script>

<script>
function getUpdateUrl(pageNumber)
{
    let speciesName=document.getElementById("speciesName").value;
    let speciesNameType=document.querySelector('input[name="speciesNameType"]:checked').value;
    let speciesGroup=document.querySelector('input[name="speciesGroup"]:checked').value;
    let axiophyteFilter=false;
    if (document.getElementById("axiophyteFilter")!==null)
    {
        axiophyteFilter=document.getElementById("axiophyteFilter").checked;
    }
    let updateUrl='/species/'+speciesName+'/type/'+speciesNameType+'/group/'+speciesGroup+'/axiophytes/'+axiophyteFilter+'/refresh?page='+pageNumber;
    return updateUrl;
}
</script>

<script type="text/javascript" src="/js/update-dataset.js"></script>

</x-layout>
