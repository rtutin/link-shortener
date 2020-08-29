@extends('layouts.app')

@section('content')
<div class="row">
  <div class="alert alert-light" role="alert">
    <a href="{{ $link->url }}">{{ $link->url }}</a> ->
    <a href="{{ url('/') }}/{{ $link->toShort() }}">{{ url('/') }}/{{ $link->toShort() }}</a>
  </div>
</div>

<div class="row">
  @if ($link->requests()->count() === 0)
    Нет переходов
  @else
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Время перехода</th>
        <th scope="col">Страна</th>
        <th scope="col">Город</th>
        <th scope="col">User Agent</th>
      </tr>
  </thead>
  <tbody>
    @foreach ($link->requests()->get() as $request)
      <tr>
        <td scope="row">{{ $request->created_at  }}</td>
        <td>{{ $request->country  }}</td>
        <td>{{ $request->city  }}</td>
        <td>{{ $request->user_agent  }}</td>
      </tr>
    @endforeach			
  </table>
  @endif
</div>
@endsection
