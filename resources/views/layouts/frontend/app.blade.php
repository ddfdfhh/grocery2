<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
      <link href="{{asset('assets/frontend/css/animate.css')}}" rel="stylesheet">
      <link href="{{asset('assets/frontend/css/bootstrap.min.css')}}" rel="stylesheet">
      <link href="https://rawgit.com/OwlCarousel2/OwlCarousel2/develop/dist/assets/owl.carousel.min.css" rel="stylesheet">
      <link href="{{asset('assets/frontend/css/style.css')}}" rel="stylesheet">
      <title>::House Cleaning::</title>
      <style>
         #pin_error{
            color:red;
         }
         </style>
   </head>
   <body >
     
      <header>
         <section class="top_header py-3">
            <div class="container-fluid">
               <div class="row align-items-center">
                  <div class="col-md-6">
                     <div class="d-flex align-items-center">
                        <div class="header_logo me-3">
                           <a href="#">
                           <img src="{{asset('assets/frontend/img/header-logo.svg')}}" >
                           </a>
                        </div>
                        <div class="dropdown">
                           <button class="dropdown-toggle loxt_btn" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                           Explore
                           </button>
                           <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                              <li><a class="dropdown-item" href="#">Explore</a></li>
                              <li><a class="dropdown-item" href="#">Explore</a></li>
                              <li><a class="dropdown-item" href="#">Explore</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="d-flex align-items-center justify-content-end">
                        <!--
                           <form class="d-flex me-3 search-form-header">
                           <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                           <button class="" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                           </form>
                           -->
                        <a href="@if(Auth::check()){{route('orders')}} @else # @endif" class="loxt_btn me-3">@if(Auth::check()) {{ucwords(auth()->user()->name)}} @else Login @endif</a>
                        <a href="#" class="btn rounded-0 btn-primary d-flex align-items-center seller-create-button px-3"><i class="me-2 fa fa-user-circle-o" aria-hidden="true"></i> Join as a Professional</a>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </header>
    @yield('content')
      <footer>
         <section class="footer_sec pt-5">
            <div class="container">
               <div class="row">
                  <div class="col-md-3 wow bounceInUp">
                     <h4 class="mb-3">For Customers</h4>
                     <ul class="footer-menu">
                        <li><a href="#">Find a Professional</a></li>
                        <li><a href="#">How it works</a></li>
                        <li><a href="#">Login</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 wow bounceInUp">
                     <h4 class="mb-3">For Professionals</h4>
                     <ul class="footer-menu">
                        <li><a href="#">How it works</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">Join as a Professional</a></li>
                        <li><a href="#">Help centre</a></li>
                        <li><a href="#">Mobile App</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 wow bounceInUp">
                     <h4 class="mb-3">About</h4>
                     <ul class="footer-menu">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">How it works</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Affiliates</a></li>
                        <li><a href="#">Blog</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 contact_details wow bounceInUp">
                     <p class="mb-0"><a href="mailto:team@info.com">team@info.com</a></p>
                     <p class="mb-0"><a href="tel:020 3697 0237">020 3697 0237</a></p>
                     <small><i>(open 24 hours a day, 7 days a week)</i></small>
                     <ul class="social_media">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                     </ul>
                  </div>
                  <div class="col-md-12 coppyright mt-5 py-3">
                     <p class="mb-0">© 2022 housecleaning.com Global Limited. <a href="#">Terms & Conditions</a> / <a href="#">Cookie policy</a> / <a href="#">Privacy policy</a></p>
                  </div>
               </div>
            </div>
         </section>
      </footer>
      <!-- Modal -->
  
      <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
      <script src="{{asset('assets/frontend/js/bootstrap.bundle.min.js')}}"></script>
      <script src="https://rawgit.com/OwlCarousel2/OwlCarousel2/develop/dist/owl.carousel.min.js"></script>
      <script src="{{asset('assets/frontend/js/wow.min.js')}}"></script>
      <script>
         new WOW().init();
      </script>
      <script>
         var ar={};
         var ar_info={};
         var chosen_date=null;
         var pincode=null;
         var final_data={};
         function openModal(){
            if(($('#postcode_front').val()).length>0)
            {
               var myModal = new bootstrap.Modal(document.getElementById('selectModal'))
               myModal.show()
            }
         }
          function getHtml(id)
   {
     btn=$(event.target)
     let par=btn.closest('fieldset');
   
      jQuery.ajax({
                     url: "{{ url('getHtml') }}",
                     method: 'post','async':false,
                     data: {
                        category: id,'_token':'{!!csrf_token()!!}',
                     },
                     success: function(result){
                   
                     $(result).insertAfter(par);
                     }
         });
               
     }
     function setPincode(value)
     {
        pincode=value
     }
     function setDate(value)
     {
        chosen_date=value
     }
     function setNearestNextPrice(label_id,price,label_value_title,label_value_id,label_title){
        $('#next-'+label_id).data('price',price);
        ar["label-"+label_id]={
                                'lable_id':label_id,'label_title':label_title, 'val_id':label_value_id,price:price,'val_title':label_value_title
                              }
                   
     }
     function setUserData(){
        ar_info["name"]=$('#name').val();
        ar_info["email"]=$('#email').val();
        ar_info["phone"]=$('#phone').val();
        ar_info["address"]=$('#address').val();
        ar_info["comment"]=$('#comment').val();
     }
     function validatePincode(pin){
        let reg= /^[A-Z]{1,2}[0-9]{1,2}[A-Z]{0,1} ?[0-9][A-Z]{2}$/i; 
        if(reg.test(pin))
        {
         $('#pin_error').text('')
         $('.fa-map-marker').css('top','50%')
         }
        else{
          $('#pin_error').text('Invalid  postcode enterd')
          $('.fa-map-marker').css('top','33%')
          $('#postcode_front').val('');
        }
     }
     function getOrderDetail(){
      btn=$(event.target)
     let par=btn.closest('fieldset');
   
     final_data={
                      'pincode':pincode,'chosen_date':chosen_date,'val_ar':JSON.stringify(ar),'user':JSON.stringify(ar_info),'_token':'{!!csrf_token()!!}',
               } 
      jQuery.ajax({
                     url: "{{ url('getorderDetail') }}",
                     method: 'post','async':false,
                     data:final_data,
                     success: function(result){
                   
                     $(result).insertAfter(par);
                     }
         });
     }
     function submitForm(){
     final_data={
                      'pincode':pincode,'chosen_date':chosen_date,'val_ar':JSON.stringify(ar),'user':JSON.stringify(ar_info),'_token':'{!!csrf_token()!!}',
               } 
      jQuery.ajax({
                     url: "{{ url('submitForm') }}",
                     method: 'post','async':false,
                     data:final_data,
                     success: function(result){
                        setTimeout(function(){
                           location.reload()
                        },5000)
                    }
         });
     }
         $(document).ready(function(){
        let  category=null;
         var current_fs, next_fs, previous_fs; //fieldsets
         var opacity;
         var counter = 1;            
         var total_amount=0;
         
         $(document).on('click',".next",function(){
          
          if($(this).data('isfirst')!==undefined)
         {
            category=$("input[name='category']:checked").val()
            getHtml(category)
         }
         if($(this).data('userinfo')!==undefined)
         {
          
            setUserData()
           
            getOrderDetail()
          
         }
         if($(this).val()=='Submit')
         {
           
          submitForm();
          
         }
      
         counter = counter + 1;
         var step = 'step'+counter;
         //alert(step);
         
         if(counter == 2)
         {
         $("#progressbar li#step1").addClass('green');
         }
         if(counter == 3)
         {
         $("#progressbar li#step2").addClass('green');
         }
         if(counter == 4)
         {
         $("#progressbar li#step3").addClass('green');
         }
         if(counter == 5)
         {
         $("#progressbar li#step4").addClass('green');
         }
         
         current_fs = $(this).parent();
         next_fs = $(this).parent().next();
         //$("#progressbar li").prev().addClass('green');
         //Add Class Active
         $("#progressbar li").eq($("fieldset").index(next_fs)).addClass('active');
         /*$("#progressbar li").eq($("fieldset").index(next_fs)).addClass(step);*/
         
         //show the next fieldset
         next_fs.show();
         //hide the current fieldset with style
         current_fs.addClass("test");
         current_fs.animate({opacity: 0}, {
         step: function(now) {
         // for making fielset appear animation
         opacity = 1 - now;
         
         current_fs.css({
         'display': 'none',
         'position': 'relative'
         });
         next_fs.css({'opacity': opacity});
         },
         duration: 600
         });
         });
         
         $(document).on('click',".previous",function(){
         //alert(counter);
         counter = counter - 1;
         
         
         if(counter == 1)
         {
         $("#progressbar li#step1").removeClass('green');
         }
         if(counter == 2)
         {
         $("#progressbar li#step2").removeClass('green');
         }
         if(counter == 3)
         {
         $("#progressbar li#step3").removeClass('green');
         }
         if(counter == 4)
         {
         $("#progressbar li#step4").removeClass('green');
         }
         
         
         current_fs = $(this).parent();
         previous_fs = $(this).parent().prev();
         
         //Remove class active
         $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
         
         //show the previous fieldset
         previous_fs.show();
         
         //hide the current fieldset with style
         current_fs.animate({opacity: 0}, {
         step: function(now) {
         // for making fielset appear animation
         opacity = 1 - now;
         
         current_fs.css({
         'display': 'none',
         'position': 'relative'
         });
         previous_fs.css({'opacity': opacity});
         },
         duration: 600
         });
         });
         
         $('.radio-group .radio').click(function(){
         $(this).parent().find('.radio').removeClass('selected');
         $(this).addClass('selected');
         });
         
         $(".submit").click(function(){
            submitForm()
            alert();
        // return false;
         })
         
         });
      </script>
       <script>// A Testimonials slider for a 
// friend's (fb.com/computer.doctor.xanthi/) 
// website i am developing

// Made with awesome -> Owl Carousel 2:
// https://github.com/OwlCarousel2/OwlCarousel2

$(function() {
  $('.owl-carousel.testimonial-carousel').owlCarousel({
    nav: true,
    navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
    dots: false,
    responsive: {
      0: {
        items: 1,
      },
      750: {
        items: 2,
      }
    }
  });
});</script>
   </body>
</html>