<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="{{ Request::segment(1) == 'home' ? 'active' : '' }}">
        {!! link_to_route('home', trans('nav_menu.dashboard')) !!}
    </li>
    <li class="{{ Request::segment(1) == 'profile' ? 'active' : '' }}">
        {!! link_to_route('users.profile.show', trans('auth.profile')) !!}
    </li>
</ul>
<br>