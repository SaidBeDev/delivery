<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                {{-- Backend --}}
                <li class="app-sidebar__heading">Dashboard</li>
                {{-- Coulis --}}
                <li>
                    <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.boxes') ? 'mm-active' : '') : '' }}">
                        <i class="metismenu-icon pe-7s-box2"></i>
                        Coulis
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.boxes.index') }}">
                                <i class="metismenu-icon"></i>
                                {{ Auth::user()->profile_type->name == "superAdmin" ? 'Tous les Coulis' : 'Mes Coulis' }}
                            </a>
                            @if (in_array(Auth::user()->profile_type->name, ["superAdmin", "distributor"]))
                                <a href="{{ route('admin.getNonReceivedBoxes') }}">
                                    <i class="metismenu-icon"></i>
                                    Tous les Coulis Non Reçu
                                </a>
                                <a href="{{ route('admin.boxes.create') }}">
                                    <i class="metismenu-icon"></i>
                                    Nouveau Coulis
                                </a>
                            @endif

                            @if (Auth::user()->profile_type->name == "superAdmin")
                                <a href="{{ route('admin.setRecievedPage') }}" style="background: limegreen;color: #fff;">
                                    <i class="metismenu-icon"></i>
                                    Recevoir d'une Coulis
                                </a>
                                <a href="{{ route('admin.setReturnedPage') }}" style="background: orangered;color: #fff;">
                                    <i class="metismenu-icon"></i>
                                    Retour d'une Coulis
                                </a>
                            @endif
                        </li>
                    </ul>
                </li>
                {{-- Users --}}
                @if (Auth::user()->profile_type->name == "superAdmin")
                    <li>
                        <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.users') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-user"></i>
                            Utilisateurs
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.users.index') }}">
                                    <i class="metismenu-icon"></i>
                                    Tous les Utilisateurs
                                </a>
                                <a href="elements-buttons-standard.html">
                                    <i class="metismenu-icon"></i>
                                    Nouveau Utilisateur
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Wilayas --}}
                    <li>
                        <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.wilayas') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-map-marker"></i>
                            Wilayas
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.wilayas.index') }}">
                                    <i class="metismenu-icon"></i>
                                    Tous les Wilayas
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Contacts --}}
                    <li>
                        <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.contacts') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-map-marker"></i>
                            Contacts
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.contacts.index') }}">
                                    <i class="metismenu-icon"></i>
                                    Tous les Contacts
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contacts.create') }}">
                                    <i class="metismenu-icon"></i>
                                    Nouveau Contact
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Configs --}}
                    <li>
                        <a href="{{ route('admin.configs.index') }}" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.configs') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-settings"></i>
                            Configurations
                        </a>
                    </li>
                    {{-- Services --}}
                    <li>
                        <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.services') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-tools"></i>
                            Services
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.services.index') }}">
                                    <i class="metismenu-icon"></i>
                                    Tous les Services
                                </a>
                                <a href="{{ route('admin.services.create') }}">
                                    <i class="metismenu-icon"></i>
                                    Nouveau Service
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Frontend --}}
                    <li class="app-sidebar__heading">Façade du site</li>

                    {{-- Services --}}
                    <li>
                        <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.services_front') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-tools"></i>
                            Notre Services
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{-- route('frontend.services.index') --}}">
                                    <i class="metismenu-icon"></i>
                                    Tous les Services
                                </a>
                                <a href="{{-- route('frontend.services.create') --}}">
                                    <i class="metismenu-icon"></i>
                                    Nouveau Service
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- About us --}}
                    <li>
                        <a href="#" class="{{ isset($info['title']) ? ($info['title'] == trans('menu.services_front') ? 'mm-active' : '') : '' }}">
                            <i class="metismenu-icon pe-7s-tools"></i>
                            A propos de nous
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                        <li>
                            <a href="{{-- route('frontend.about.index') --}}">
                                <i class="metismenu-icon"></i>
                                Modifier
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
