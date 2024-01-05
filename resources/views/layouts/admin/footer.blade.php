{{-- <script src="{{ asset('js/app.js') }}"></script> --}}

<!----my own js-->


<script src="{{ asset('commonjs/jquery.min.js') }}"></script>
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        
      }
  });
  var baseurl = window.location.origin + '/admin/';
</script>
  <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
   


    <script src="{{ asset('commonjs/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('commonjs/jquery.filer.min.js') }}"></script>
    <script src="{{ asset('commonjs/select2/select2.js') }}"></script>
<script src="{{ asset('commonjs/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('commonjs/generate.js') }}"></script>
<script src="{{ asset('commonjs/multiselect.min.js') }}"></script>
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script src="{{ asset('commonjs/block-ui.js') }}"></script>
<script src="{{asset('commonjs/spartan.js')}}"></script>
<script src="{{ asset('commonjs/formvalidationcommon.js') }}?v=6"></script>
<script  type="text/javascript"  src="{{ asset('commonjs/spartan-multi-image-picker-min.js')}}"></script>
<script src="{{ asset('commonjs/custom_form_validation.js') }}?v=3"></script>
<script src="{{ asset('commonjs/commonjs_functions.js') }}?v=34"></script>
<script src="{{ asset('commonjs/index_table_sort_pagination.js') }}"></script>
<script src="{{ asset('commonjs/summernote.min.js') }}"></script>
<script src="{{ asset('commonjs/repeatable_json_related.js') }}?v=4"></script>
<script src="{{ asset('commonjs/file_input_related.js') }}?v=4"></script>
<script src="{{ asset('commonjs/selection_related.js') }}?v=4"></script>
<script src="{{ asset('commonjs/custom.js') }}?v=4"></script>
<script src="{{ asset('ecommerce.js') }}"></script>
@if(request()->segment(2)=='dashboard' || request()->segment(1)=='dashboard')
{{-- <script src="{{ asset('commonjs/chart.js') }}"></script> --}}
@endif
<script src="{{ asset('commonjs/lightbox.min.js') }}"></script>
{{-- <script src="{{ asset('commonjs/invoice.js') }}"></script> --}}

<script>
   


   
</script>

@stack('scripts')
