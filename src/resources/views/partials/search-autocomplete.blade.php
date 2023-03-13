@isset($results->records)
@forelse ($results->records as $record)
    <option value="{{$record}}">
@empty
    <option value="" readonly>No records match "{{$query}}"</option>
@endforelse
@endisset
