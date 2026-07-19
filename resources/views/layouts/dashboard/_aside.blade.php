<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dashboard_files/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">

            <li>
                <a href="{{ route('admin.dashboard.index') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>@lang('site.dashboard')</span>
                </a>
            </li>

            @if (auth()->user()->hasPermission('brands_read'))
                <li>
                    <a href="{{ route('admin.brands.index') }}">
                        <i class="fa fa-tags"></i>
                        <span>@lang('site.brands')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('categories_read'))
                <li>
                    <a href="{{ route('admin.categories.index') }}">
                        <i class="fa fa-sitemap"></i>
                        <span>@lang('site.categories')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('products_read'))
                <li>
                    <a href="{{ route('admin.products.index') }}">
                        <i class="fa fa-cubes"></i>
                        <span>@lang('site.products')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('clients_read'))
                <li>
                    <a href="{{ route('admin.clients.index') }}">
                        <i class="fa fa-users"></i>
                        <span>@lang('site.clients')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('orders_read'))
                <li>
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span>@lang('site.orders')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('maintenances_read'))
                <li>
                    <a href="{{ route('admin.maintenances.index') }}">
                        <i class="fa fa-wrench"></i>
                        <span>@lang('site.maintenances')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('space_parts_read'))
                <li>
                    <a href="{{ route('admin.space_parts.index') }}">
                        <i class="fa fa-cog"></i>
                        <span>@lang('site.space_parts')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('users_read'))
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-user"></i>
                        <span>@lang('site.users')</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('activity_logs_read'))
                <li>
                    <a href="{{ route('admin.activity_logs') }}">
                        <i class="fa fa-user"></i>
                        <span>@lang('site.activity_logs')</span>
                    </a>
                </li>
            @endif

        </ul>

    </section>

</aside>
