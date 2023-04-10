@isset($results->records)
@forelse ($results->records as $record)
    <p class="autocomplete-item" onclick="autocomplete('{{$record}}')">{{$record}}</p>
@empty
    <p style="color:#d4d4d4;">No sites match "{{$siteName}}"</p>
@endforelse
@endisset
