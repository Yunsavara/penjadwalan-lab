<section id="sidebar">
    <a href="#" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
    <ul class="side-menu">
        @auth
            @if(in_array(auth()->user()->role, ['superadmin','admin','laboran','user']))
                <li><a href="{{ route(auth()->user()->role . '.dashboard') }}" class="{{ request()->is(auth()->user()->role . '/dashboard') ? 'active' : '' }}">
                    <i class='bx bxs-dashboard icon'></i> Dashboard
                </a></li>
            @endif
        @endauth



        {{-- <li class="divider" data-text="main">Main</li>
        <li>
            <a href="#" class="{{ request()->is('elements/*') ? 'active' : '' }}"><i class='bx bxs-inbox icon'></i> Elements <i class='bx bx-chevron-right icon-right'></i></a>
            <ul class="side-dropdown">
                <li><a href="{{ route('alert') }}" class="{{ request()->is('alert') ? 'active' : '' }}">Alert</a></li>
                <li><a href="{{ route('badges') }}" class="{{ request()->is('badges') ? 'active' : '' }}">Badges</a></li>
                <li><a href="{{ route('breadcrumbs') }}" class="{{ request()->is('breadcrumbs') ? 'active' : '' }}">Breadcrumbs</a></li>
                <li><a href="{{ route('button') }}" class="{{ request()->is('button') ? 'active' : '' }}">Button</a></li>
            </ul>
        </li>
        <li><a href="{{ route('charts') }}" class="{{ request()->is('charts') ? 'active' : '' }}"><i class='bx bxs-chart icon'></i> Charts</a></li>
        <li><a href="{{ route('widgets') }}" class="{{ request()->is('widgets') ? 'active' : '' }}"><i class='bx bxs-widget icon'></i> Widgets</a></li>
        <li class="divider" data-text="table and forms">Table and forms</li>
        <li><a href="{{ route('tables') }}" class="{{ request()->is('tables') ? 'active' : '' }}"><i class='bx bx-table icon'></i> Tables</a></li>
        <li>
            <a href="#" class="{{ request()->is('forms/*') ? 'active' : '' }}"><i class='bx bxs-notepad icon'></i> Forms <i class='bx bx-chevron-right icon-right'></i></a>
            <ul class="side-dropdown">
                <li><a href="{{ route('basic-form') }}" class="{{ request()->is('basic-form') ? 'active' : '' }}">Basic</a></li>
                <li><a href="{{ route('select-form') }}" class="{{ request()->is('select-form') ? 'active' : '' }}">Select</a></li>
                <li><a href="{{ route('checkbox-form') }}" class="{{ request()->is('checkbox-form') ? 'active' : '' }}">Checkbox</a></li>
                <li><a href="{{ route('radio-form') }}" class="{{ request()->is('radio-form') ? 'active' : '' }}">Radio</a></li>
            </ul>
        </li> --}}
    </ul>
</section>
