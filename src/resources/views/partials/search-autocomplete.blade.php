@isset($results->records)
<p onclick="copy('{{$results->queryUrl}}')">Searching for {{$nameType}} '{{$speciesName}}'</p>
@forelse ($results->records as $record)
    <p class="autocomplete-item" onclick="autocomplete('{{$record}}')">{{$record}}</p>
@empty
    <p style="color:#d4d4d4;">No records match "{{$speciesName}}"</p>
@endforelse
@endisset
