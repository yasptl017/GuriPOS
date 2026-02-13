@php
    $setting = App\Models\Setting::first();
@endphp

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">{{ $setting->app_name }}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('admin.dashboard') }}">{{ substr($setting->app_name,0, 2)  }}</a>
      </div>

      <ul class="sidebar-menu">
          <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>{{__('admin.Dashboard')}}</span></a></li>

          <li class="{{ Route::is('admin.pos') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pos') }}"><i class="fas fa-th-large"></i> <span>{{__('admin.POS')}}</span></a></li>
          

          <li class="{{ Route::is('admin.review') || Route::is('admin.review') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.review') }}"><i class="fas fa-fa fa-envelope"></i> <span>Top Products</span></a></li>

          <li class="nav-item dropdown {{ Route::is('admin.report.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-bar"></i><span>Reports</span></a>
            <ul class="dropdown-menu">
              <li class="{{ Route::is('admin.report.daily') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.report.daily') }}"><i class="fas fa-calendar-day mr-1"></i>Daily Report</a>
              </li>
              <li class="{{ Route::is('admin.report.monthly') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.report.monthly') }}"><i class="fas fa-calendar-alt mr-1"></i>Monthly Report</a>
              </li>
              <li class="{{ Route::is('admin.report.range') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.report.range') }}"><i class="fas fa-calendar-week mr-1"></i>Date Range Report</a>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.all-order') || Route::is('admin.web-order') || Route::is('admin.order-show') || Route::is('admin.pending-order') || Route::is('admin.pregress-order') || Route::is('admin.delivered-order') ||  Route::is('admin.completed-order') || Route::is('admin.declined-order') || Route::is('admin.cash-on-delivery')  ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>{{__('admin.Orders')}}</span></a>
            <ul class="dropdown-menu">

              <li class="{{ Route::is('admin.all-order') || (Route::is('admin.order-show') && request('source') !== 'web') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.all-order') }}">{{__('admin.POS Orders')}}</a></li>

              <li class="{{ Route::is('admin.web-order') || (Route::is('admin.order-show') && request('source') === 'web') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.web-order') }}">{{__('admin.Web Orders')}}</a></li>

             <!-- <li class="{{ Route::is('admin.pregress-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pregress-order') }}">{{__('admin.Progress Orders')}}</a></li>

              <li class="{{ Route::is('admin.delivered-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.delivered-order') }}">{{__('admin.Delivered Orders')}}</a></li>

              <li class="{{ Route::is('admin.completed-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.completed-order') }}">{{__('admin.Completed Orders')}}</a></li>

              <li class="{{ Route::is('admin.declined-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.declined-order') }}">{{__('admin.Declined Orders')}}</a></li>

              <li class="{{ Route::is('admin.cash-on-delivery') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.cash-on-delivery') }}">{{__('admin.Cash On Delivery')}}</a></li>
-->

            </ul>

          </li>

          <li class="nav-item dropdown {{ Route::is('admin.product.*') || Route::is('admin.product-variant') || Route::is('admin.product-gallery') || Route::is('admin.product-category.*') || Route::is('admin.category-order') || Route::is('admin.reservation') || Route::is('admin.working-hours') || Route::is('admin.order-control') || Route::is('admin.printer-setting') || Route::is('admin.pos-tables') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('admin.Manage Restaurant')}}</span></a>
            <ul class="dropdown-menu">

                <li><a class="nav-link" href="{{ route('admin.product.create') }}">{{__('admin.Create Product')}}</a></li>

                <li class="{{ Route::is('admin.product.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product.index') }}">{{__('admin.Products')}}</a></li>

                <li class="{{ Route::is('admin.product-category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-category.index') }}">{{__('admin.Categories')}}</a></li>

                <li class="{{ Route::is('admin.category-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.category-order') }}"><i class="fas fa-sort mr-1"></i>Category Order</a></li>

                <li class="{{ Route::is('admin.reservation') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.reservation') }}">{{__('admin.Reservations')}}</a></li>

                <li class="{{ Route::is('admin.working-hours') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.working-hours') }}">{{__('admin.Working Hours')}}</a></li>

                <li class="{{ Route::is('admin.order-control') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.order-control') }}">Order Control</a></li>
                <li class="{{ Route::is('admin.printer-setting') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.printer-setting') }}">Printer Settings</a></li>
                <li class="{{ Route::is('admin.pos-tables') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pos-tables') }}"><i class="fas fa-chair mr-1"></i>POS Tables</a></li>

            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.coupon.*') || Route::is('admin.payment-method') || Route::is('admin.delivery-area.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>{{__('admin.Ecommerce')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.coupon.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.coupon.index') }}">{{__('admin.Coupon')}}</a></li>

                <li class="{{ Route::is('admin.payment-method') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.payment-method') }}">{{__('admin.Payment Method')}}</a></li>

                <li class="{{ Route::is('admin.delivery-area.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.delivery-area.index') }}">{{__('admin.Delivery Area')}}</a></li>

            </ul>
          </li>


          <li class="nav-item dropdown {{  Route::is('admin.customer-list') || Route::is('admin.customer-show') || Route::is('admin.pending-customer-list') || Route::is('admin.send-email-to-all-customer') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>{{__('admin.Our Customers')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.customer-list') || Route::is('admin.customer-show') || Route::is('admin.send-email-to-all-customer') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.customer-list') }}">{{__('admin.Customer List')}}</a></li>

                <li class="{{ Route::is('admin.pending-customer-list') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-customer-list') }}">{{__('admin.Pending Customers')}}</a></li>

            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.service.*') || Route::is('admin.slider.*') || Route::is('admin.counter.*') || Route::is('admin.app-section') || Route::is('admin.partner.*') || Route::is('admin.slider-intro') || Route::is('admin.appointment-bg') || Route::is('admin.login-page') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>{{__('admin.Section')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.slider-intro') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.slider-intro') }}">{{__('admin.Intro')}}</a></li>

                <li class="{{ Route::is('admin.slider.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.slider.index') }}">{{__('admin.Gallery')}}</a></li>

                <li class="{{ Route::is('admin.counter.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.counter.index') }}">{{__('admin.Counter')}}</a></li>

                <li class="{{ Route::is('admin.appointment-bg') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.appointment-bg') }}">{{__('admin.Appointment')}}</a></li>

                <li class="{{ Route::is('admin.app-section') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.app-section') }}">{{__('admin.App Section')}}</a></li>

                <li class="{{ Route::is('admin.login-page') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.login-page') }}">{{__('admin.Admin Login Page')}}</a></li>

            </ul>
          </li>

          <li class="nav-item dropdown {{ Route::is('admin.maintainance-mode') || Route::is('admin.seo-setup') || Route::is('admin.default-avatar') | Route::is('admin.breadcrumb-image') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-globe"></i><span>{{__('admin.Manage Website')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.seo-setup') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seo-setup') }}">{{__('admin.SEO Setup')}}</a></li>

                <li class="{{ Route::is('admin.maintainance-mode') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.maintainance-mode') }}">{{__('admin.Maintainance Mode')}}</a></li>

                <li class="{{ Route::is('admin.default-avatar') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.default-avatar') }}">{{__('admin.Default Avatar')}}</a></li>

                <li class="{{ Route::is('admin.breadcrumb-image') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.breadcrumb-image') }}">{{__('admin.Breadcrumb Image')}}</a></li>

            </ul>
          </li>



          <li class="nav-item dropdown {{ Route::is('admin.email-configuration') || Route::is('admin.email-template') || Route::is('admin.edit-email-template') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-envelope"></i><span>{{__('admin.Email Configuration')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.email-configuration') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.email-configuration') }}">{{__('admin.Setting')}}</a></li>

                <li class="{{ Route::is('admin.email-template') || Route::is('admin.edit-email-template') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.email-template') }}">{{__('admin.Email Template')}}</a></li>

            </ul>
          </li>


          <li class="{{ Route::is('admin.general-setting') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.general-setting') }}"><i class="fas fa-cog"></i> <span>{{__('admin.Setting')}}</span></a></li>
          @php
              $logedInAdmin = Auth::guard('admin')->user();
          @endphp

          <li class="{{ Route::is('admin.contact-message') || Route::is('admin.show-contact-message') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.contact-message') }}"><i class="fas fa-fa fa-envelope"></i> <span>{{__('admin.Contact Message')}}</span></a></li>

          @if ($logedInAdmin->admin_type == 1)
            <li class="{{ Route::is('admin.admin.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.admin.index') }}"><i class="fas fa-user"></i> <span>{{__('admin.Admin list')}}</span></a></li>
          @endif

          <li>
            <a class="nav-link text-danger" href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('admin-sidebar-logout-form').submit();">
               <i class="fas fa-sign-out-alt"></i> <span>{{ __('admin.Logout') }}</span>
            </a>
          </li>

        </ul>

        <form id="admin-sidebar-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
          @csrf
        </form>
    </aside>

  </div>

