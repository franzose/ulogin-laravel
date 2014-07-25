@if (Session::get('errors'))
<ul class="ulogin-errors">
    @foreach (Session::get('errors')->all(':message') as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif