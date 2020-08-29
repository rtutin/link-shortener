@extends('layouts.app')

@section('content')
<form class="form-shortener" method="POST" action="/shortener">
 @csrf
 <label for="url" class="sr-only">URL</label>
 <input type="text" id="url" name="url" class="form-control" placeholder="https://ya.ru/" required autofocus>
 @error('url')
   <div class="alert alert-danger">{{ $message }}</div>
 @enderror
 <label for="ttl" class="sr-only">Время жизни ссылки в секундах</label>
 <input type="number" id="ttl" name="ttl" class="form-control" placeholder="Время жизни ссылки в секундах">
 <button class="btn btn-lg btn-primary btn-block" type="submit">Уменьшить</button>
</form>
@endsection