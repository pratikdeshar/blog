<!DOCTYPE html>

<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>Metronic | Dashboard</title>
        <meta name="description" content="Latest updates and statistic charts">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

        <!--end::Web font -->

        <!--begin::Global Theme Styles -->
        <link href="{{ asset("assets/vendors/base/vendors.bundle.css") }}" rel="stylesheet" type="text/css" />

        <!--RTL version:<link href="assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
        <link href="{{ asset("assets/demo/demo6/base/style.bundle.css") }} " rel="stylesheet" type="text/css" />

        <!--RTL version:<link href="assets/demo/demo6/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

        <!--end::Global Theme Styles -->

        <!--begin::Page Vendors Styles -->
        <link href="{{ asset("assets/vendors/custom/fullcalendar/fullcalendar.bundle.css") }}" rel="stylesheet" type="text/css" />

        <!--RTL version:<link href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

        <!--end::Page Vendors Styles -->
        <link rel="shortcut icon" href=" {{ asset("assets/demo/demo6/media/img/logo/favicon.ico") }}" />
    </head>

    <!-- end::Head -->

    <!-- begin::Body -->
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-light m-aside-left--fixed m-aside-left--offcanvas m-aside-left--minimize m-brand--minimize m-footer--push m-aside--offcanvas-default">

        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">

            <!-- BEGIN: Header -->
            @include('cms.layouts.header-nav')

            <!-- END: Header -->

            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

                <!-- BEGIN: Left Aside -->
                @include('cms.layouts.sidebar')

                <!-- END: Left Aside -->
                <div class="m-grid__item m-grid__item--fluid m-wrapper ">
                    <div class="m-subheader-search d-none">
                        <h2 class="m-subheader-search__title">
                            Recent Bookings
                            <span class="m-subheader-search__desc">Onling Bookings Management</span>
                        </h2>
                        <form class="m-form">
                            <div class="m-grid m-grid--ver-desktop m-grid--desktop">
                                <div class="m-grid__item m-grid__item--middle">
                                    <div class="m-input-icon m-input-icon--fixed m-input-icon--fixed-large m-input-icon--right">
                                        <input type="text" class="form-control form-control-lg m-input m-input--pill" placeholder="Booking Number">
                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-puzzle-piece"></i></span></span>
                                    </div>
                                    <div class="m-input-icon m-input-icon--fixed m-input-icon--fixed-md m-input-icon--right">
                                        <input type="text" class="form-control form-control-lg m-input m-input--pill" placeholder="From">
                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-calendar-check-o"></i></span></span>
                                    </div>
                                    <div class="m-input-icon m-input-icon--fixed m-input-icon--fixed-md m-input-icon--right">
                                        <input type="text" class="form-control form-control-lg m-input m-input--pill" placeholder="To">
                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-calendar-check-o"></i></span></span>
                                    </div>
                                </div>
                                <div class="m-grid__item m-grid__item--middle">
                                    <div class="m--margin-top-20 m--visible-tablet-and-mobile"></div>
                                    <button type="button" class="btn m-btn--pill m-subheader-search__submit-btn">Search Bookings</button>
                                    <a href="#" class="m-subheader-search__link m-link">Advance Search</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="m-content">
                        <!--Begin::Section-->
                       @yield('content')
                        <!--End::Section-->
                    </div>
                </div>
            </div>

            <!-- end:: Body -->

            <!-- begin::Footer -->
           @include('cms.layouts.footer')

            <!-- end::Footer -->
        </div>

        <!-- end:: Page -->

        <!-- begin::Quick Sidebar -->
        @include('cms.layouts.quick-sidebar')

        <!-- end::Quick Sidebar -->

        <!-- begin::Scroll Top -->
        <div id="m_scroll_top" class="m-scroll-top">
            <i class="la la-arrow-up"></i>
        </div>

        <!-- end::Scroll Top -->

        <!-- begin::Quick Nav -->
        <ul class="m-nav-sticky" style="margin-top: 30px;">
            <li class="m-nav-sticky__item" data-toggle="m-tooltip" title="Purchase" data-placement="left">
                <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" target="_blank"><i class="la la-cart-arrow-down"></i></a>
            </li>
            <li class="m-nav-sticky__item" data-toggle="m-tooltip" title="Documentation" data-placement="left">
                <a href="https://keenthemes.com/metronic/documentation.html" target="_blank"><i class="la la-code-fork"></i></a>
            </li>
            <li class="m-nav-sticky__item" data-toggle="m-tooltip" title="Support" data-placement="left">
                <a href="https://keenthemes.com/forums/forum/support/metronic5/" target="_blank"><i class="la la-life-ring"></i></a>
            </li>
        </ul>

        <!-- begin::Quick Nav -->

        <!--begin::Global Theme Bundle -->
        <script src="{{ asset("assets/vendors/base/vendors.bundle.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/demo/demo6/base/scripts.bundle.js") }}" type="text/javascript"></script>

        <!--end::Global Theme Bundle -->

        <!--begin::Page Vendors -->
        <script src="{{ asset("assets/vendors/custom/fullcalendar/fullcalendar.bundle.js") }} " type="text/javascript"></script>

        <!--end::Page Vendors -->

        <!--begin::Page Scripts -->
        <script src="{{ asset("assets/app/js/dashboard.js") }} " type="text/javascript"></script>

        <!--end::Page Scripts -->
    </body>

    <!-- end::Body -->
</html>