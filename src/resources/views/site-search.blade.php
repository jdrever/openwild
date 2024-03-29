<x-layout>

<h2 class="text-start text-md-center">Search for a Site in {{ config('core.region') }}</h2>
<form action="/sites/" method="post">
    @csrf
    <div class="row mb-2">
        <div class="col-lg-8 mx-auto">
            <label for="search" class="form-label visually-hidden">Site name</label>
            <div class="input-group">
                <input type="text" class="form-control" name="siteName" id="siteName" aria-describedby="search-help" placeholder="Enter a site" value="{{ $siteName }}"
                autocomplete="off"/>
                <button type="submit" data-refresh="true" class="btn btn-primary">List Sites</button>
            </div>
            <div id="autocomplete-container"></div>
            <small id="search-help" class="form-text text-start text-md-center d-block">Enter all or part of a site name. Try something like "Aston".</small>
        </div>
    </div>
</form>

<div id="data-table">
    @include('data-tables/sites-in-dataset')
</div>

<script type="text/javascript" src="/js/site-name-autocomplete.js"></script>

<script>
function getUpdateUrl(pageNumber)
{
    let siteName=document.getElementById("siteName").value;
    let updateUrl='/sites/'+siteName+'/refresh?page='+pageNumber;
    return updateUrl;
}
</script>

<script type="text/javascript" src="/js/update-dataset.js"></script>

</x-layout>

