@php

    //dd(url()->full());
    $last_uri = str_contains(url()->full(), 'admin/') ? request()->segment(2) : request()->segment(1);

    $routes_arr = ['roles', 'categories', 'products', 'brands', 'attribute_famlies', 'product_discount_rules', 'combo_offers'];
@endphp
<ul class="navbar-nav" id="navbar-nav">
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'dashboard') active @endif" href="{{ url('/admin/dashboard') }}">
            <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
            aria-expanded="false" aria-controls="sidebarDashboards">
            <i class="mdi mdi-speedometer"></i> <span data-key="t-dashboards">Master</span>
        </a>

        <div class="collapse menu-dropdown" id="sidebarDashboards">
            <ul class="nav nav-sm flex-column">


                <li class="nav-item @if ($last_uri == 'users') active @endif">
                    <a href="{{ route('users.index', ['role' => 'Customer']) }}" class="nav-link" data-key="t-crm">
                        Manage
                        Customers </a>
                </li>
                <li class="nav-item @if ($last_uri == 'users') active @endif">
                    <a href="{{ route('users.index', ['role' => 'Driver']) }}" class="nav-link" data-key="t-crm"> Manage
                        Drivers </a>
                </li>

                {{-- <li class="nav-item @if ($last_uri == 'roles') active @endif">
                    <a href="{{ route('roles.index') }}" class="nav-link" data-key="t-ecommerce"> Manage Roles </a>
                </li> --}}
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('list_attributes'))
                    <li class="nav-item @if ($last_uri == 'attributes') active @endif">
                        <a href="{{ route('attributes.index') }}" class="nav-link">

                            <div data-i18n="Calendar">Manage Attributes</div>
                        </a>
                    </li>
                @endif


                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('list_brands'))
                    <li class="nav-item @if ($last_uri == 'brands') active @endif">
                        <a href="{{ route('brands.index') }}" class="nav-link">

                            <div data-i18n="Calendar">Manage Brands</div>
                        </a>
                    </li>
                @endif


            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'orders') active @endif"
            href="{{ route('orders.index') }}">
            <i class="mdi mdi-package-variant"></i> <span data-key="t-widgets">Orders</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'return_items') active @endif"
            href="{{ route('return_items.index') }}">
            <i class="mdi mdi-refresh"></i> <span data-key="t-widgets">Return Orders</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'payments') active @endif" href="">
            <i class="mdi mdi-credit-card-outline"></i> <span data-key="t-widgets">Payments</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'payments') active @endif"
            href="{{ route('products.index') }}">
            <i class="mdi mdi-package"></i> <span data-key="t-widgets">Products</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'payments') active @endif"
            href="{{ route('refunds.index') }}">
            <i class="mdi mdi-assistant"></i> <span data-key="t-widgets">Refund </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'payments') active @endif"
            href="{{ route('categories.index') }}">
            <i class="mdi mdi-shape-plus"></i> <span data-key="t-widgets">Categories</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'payments') active @endif"
            href="{{ route('collections.index') }}">
            <i class="mdi mdi-shape-plus"></i> <span data-key="t-widgets">Collections</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'payments') active @endif"
            href="{{ route('coupons.index') }}">
            <i class="mdi mdi-ticket-percent"></i> <span data-key="t-widgets">Discounts/Coupons</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link  @if ($last_uri == 'customer_groups') active @endif"
            href="{{ route('customer_groups.index') }}">
            <i class="mdi mdi-account-multiple-plus"></i> <span data-key="t-widgets">Customer Group</span>
        </a>
    </li>
    @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_product_addons'))
            <li class="nav-item @if($last_uri=='product_addons') active  @endif">
              <a href="{{route('product_addons.index')}}" class="nav-link">
                <i class="mdi mdi-account-multiple-plus"></i>
                <div data-i18n="Calendar">Product  Addons</div>
              </a>
            </li>
     @endif
    <li class="nav-item">
        <a class="nav-link menu-link" href="#sidebarDashboards3" data-bs-toggle="collapse" role="button"
            aria-expanded="false" aria-controls="sidebarDashboards">
            <i class="mdi mdi-cog-outline"></i> <span data-key="t-dashboards">Frontend/App Settings</span>
        </a>

        <div class="collapse menu-dropdown" id="sidebarDashboards3">
            <ul class="nav nav-sm flex-column">
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('list_settings'))
                    <li class="nav-item @if ($last_uri == 'settings') active @endif">
                        <a href="{{ route('settings.index') }}" class="nav-link">

                            <div data-i18n="Calendar">General Setting</div>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_website_banners'))
                        <li class="nav-item @if($last_uri=='website_banners') active  @endif">
                        <a href="{{route('website_banners.index')}}" class="nav-link">
                        
                            <div data-i18n="Calendar">Website  Banners</div>
                        </a>
                        </li>
                @endif


                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('list_banners'))
                    <li class="nav-item @if ($last_uri == 'banners') active @endif">
                        <a href="{{ route('banners.index') }}" class="nav-link">

                            <div data-i18n="Calendar">Manage Banners</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('list_content_sections'))
                    <li class="nav-item @if ($last_uri == 'content_sections') active @endif">
                        <a href="{{ route('content_sections.index') }}" class="nav-link">

                            <div data-i18n="Calendar">Manage Content Sections</div>
                        </a>
                    </li>
                @endif
               
                    @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('list_website_content_sections'))
                    <li class="nav-item @if($last_uri=='website_content_sections') active  @endif">
                    <a href="{{route('website_content_sections.index')}}" class="nav-link">
                    
                        <div data-i18n="Calendar"> Website  Content  Sections</div>
                    </a>
                    </li>
                    @endif

            </ul>
        </div>
    </li>

</ul>

