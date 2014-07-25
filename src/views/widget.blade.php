@if ( ! Auth::check())
    <script src="//ulogin.ru/js/ulogin.js"></script>
    @if ($display != 'window')
    <div id="uLogin-widget" data-ulogin="{{$params}}"></div>
    @else
    <a href="#" id="uLogin-widget" data-ulogin="{{$params}}">{{$button}}</a>
    @endif
@endif