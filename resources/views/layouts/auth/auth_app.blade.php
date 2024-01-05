<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

    <!-- Layout config Js -->
    <script src="{{asset('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/js/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css" />



</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
               OMG SHOP
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index-2.html" class="text-white d-inline-block auth-logo" style="font-size:30px;font-family:fantasy">
                                   OMG SHOP
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- end row -->

                @yield('content')
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                          
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    
    <script src="{{asset('commonjs/jquery.min.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              
            }
        });
        </script>
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
   
    <!-- particles js -->
    <script src="{{asset('assets/libs/particles.js/particles.js')}}"></script>
    <!-- particles app js -->
    <script src="{{asset('assets/js/pages/particles.app.js')}}"></script>
    <!-- password-addon init -->
    <script src="{{asset('assets/js/pages/password-addon.init.js')}}"></script>
	  {{-- <script src="{{ asset('assets/js/pages-auth.js') }}"></script> --}}
      <script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
    <script src="{{ asset('commonjs/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('commonjs/selection_related.js') }}"></script>
    <script src="{{ asset('commonjs/formvalidationcommon.js') }}"></script>
    <script src="{{ asset('commonjs/custom_form_validation.js') }}"></script>
    <script src="{{ asset('commonjs/commonjs_functions.js') }}"></script>
    <script>
        $(document).ready(function () {
    if ($("form").length > 0) initializeFormAjaxSubmitAndValidation();
    
   
});

    </script>

    
</body>


<!-- Mirrored from themesbrand.com/velzon/html/material/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 27 Jun 2023 11:35:46 GMT -->
</html>