    $(document).ready(function(){
        var postForm = $("#postForm1"); // i change the class from postForm to PostForm1
        postForm.submit(function(e){
            e.preventDefault();
            var formData = postForm.serialize();
            // console.log(formData);
            // $( '#register-errors-name' ).html( "" );
            // $( '#register-errors-email' ).html( "" );
            // $( '#register-errors-password' ).html( "" );
            // $("#register-name").removeClass("has-error");
            // $("#register-email").removeClass("has-error");
            // $("#register-password").removeClass("has-error");

            $.ajax({
                url:'/post',
                type:'POST',
                data:formData,
                success:function(data){
                	toastr.options.preventDuplicates = true;
                	toastr.success("Post achieved successfully!");
                	
                	// location.reload(true);
                },
                error: function (data) {
                	toastr.options.preventDuplicates = true;
                	toastr.error("An error occured while posting...");
                    // console.log(data.responseText);
                    var obj = jQuery.parseJSON( data.responseText );
                }
            });
        });
    });



// var reciever_email = $(".send_").data("email");
// console.log(reciever_email);
$(document).ready(function(){
    
        $(document).on('click', '.send_request',function(e){
            e.preventDefault();
            var reciever_email = $(this).data("email");
            $(this).removeClass('send_request').text('undo request');
            $(this).addClass('undo_request');
            $.ajax({
                url:'send_request/'+reciever_email,
                type:'POST',
                data: {email:reciever_email},
                success:function(data){
                    toastr.options.preventDuplicates = true;
                    toastr.success("Request sent to " + reciever_email);
                        
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured sending request...");
                }
            });

        });
    });

//sudo rm -r or -f /var/www/html/de_bridge

    $(document).on('click', "button.undo_request", function(e){
          // alert('clickrf now kilonshele');
            e.preventDefault();
            var reciever_email = $(this).data("email");
            $(this).removeClass('undo_request').text('Send Friend Request');
            $(this).addClass('send_request');

            $.ajax({
                url:'undo_request/'+reciever_email,
                type:'POST',
                data: {email:reciever_email},
                success:function(data){
                    // alert(data);
                    // console.log(data);
                    toastr.options.preventDuplicates = true;
                    toastr.info("Request cancelled!");
                    $(this).hide();
                    $("#send_request").show()
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured cancelling request...");
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });


//follow logic


$(document).on('click', ".follow", function(e){
        // console.log('clicked!');
          // alert('clickrf now kilonshele');
            e.preventDefault();
            // alert('followed');

            var reciever_email = $(this).data("email");
            var reciever_full_name = $(this).data("fname");

            // alert(reciever_email);
            $(this).removeClass('follow').text(' unfollow');
            $(this).addClass('unfollow');
            $(this).addClass('fa fa-check');

            $.ajax({
                url:'/follow/'+reciever_email,
                type:'POST',
                data: {reference:reciever_email},
                success:function(data){
                    // alert(data);
                    // console.log(data);
                    var current_count = $(".follow_count").text();
                    current_count++;
                    $(".follow_count").text(current_count);
                    toastr.options.preventDuplicates = true;
                    // toastr.success("Now following "+reciever_full_name);
                    
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured while following "+ reciever_full_name);
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });

$(document).on('click', ".unfollow", function(e){
          // alert('clickrf now kilonshele');
            e.preventDefault();
            var reciever_email = $(this).data("email");
            var reciever_full_name = $(this).data("fname");

            // alert(reciever_email);
            $(this).removeClass('unfollow').text(' follow');
            $(this).addClass('follow');
            // $(this).removeClass('follow');
            $(this).addClass('fa fa-user');
            $(this).addClass('f-14');


            $.ajax({
                url:'/unfollow/'+reciever_email,
                type:'POST',
                data: {reference:reciever_email},
                success:function(data){
                    // alert(data);
                    // console.log(data);
                    var current_count = $(".follow_count").text();
                    current_count--;
                    $(".follow_count").text(current_count);
                    toastr.options.preventDuplicates = true;
                    // toastr.info("You unfollowed "+reciever_full_name);
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured while following "+ reciever_full_name);
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });



//accept friend_request
$(document).on('click', ".accept_friend", function(e){
          // alert('clickrf now kilonshele');
            e.preventDefault();
            var reciever_email = $(this).data("email");
            var friend_request_div = $(this).data("id");

            // alert(reciever_email);
            $("#user_div"+friend_request_div).hide();
            $.ajax({
                url:'accept_friend/'+reciever_email,
                type:'POST',
                data: {email:reciever_email},
                success:function(data){
                    // alert(data);
                    // console.log(data);
                    toastr.options.preventDuplicates = true;
                    toastr.info("You are now friend with "+reciever_email);
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured while accepting "+ reciever_email + " request");
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });

//decline friend_request
function ConfirmDelete()
              {
              var x = confirm("Are you sure you want to decline this request ?");
              if (x)
                return true;
              else
                return false;
              }
              $("button#a_del").click(function(){
               return ConfirmDelete();
              });
              $("button#a_del").click(function(){
               return ConfirmDelete();
              });


$(document).on('click', ".decline_friend", function(e){
          // alert('clickrf now kilonshele');
            e.preventDefault();
            var reciever_email = $(this).data("email");
            var friend_request_div = $(this).data("id");

            // alert(reciever_email);
            $("#user_div"+friend_request_div).hide();

            $.ajax({
                url:'decline_friend/'+reciever_email,
                type:'POST',
                data: {email:reciever_email},
                success:function(data){
                    // alert(data);
                    // console.log(data);
                    toastr.options.preventDuplicates = true;
                    toastr.info("You declined "+reciever_email+"'s request");
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured while accepting "+ reciever_email + " request");
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });



//continue registeration process for following some certain user and merchant

// var followers_counter = $(".followers_counter").text();
$(document).on('click', ".c_follow", function(e){
        var follow_id = $(this).data("id");

            var reciever_email = $(this).data("email");
            var reciever_full_name = $(this).data("fname");

            $(this).removeClass('c_follow');
            $(this).addClass('c_unfollow');
            $(this).addClass('unfollow_btn');
            $(this).addClass('fa fa-check').css('color', 'white');
            

            $.ajax({
                url:'/follow/'+reciever_email,
                type:'POST',
                data: {reference:reciever_email},
                success:function(data){
                    console.log(data);
                    var f_counter = $(".count").text(data.user_count);
                    var m_counter = $(".m_count").text(data.merchant_count);

                    if ($(".count").text() >= 10) {
                        window.location = "/users/follow/merchants";
                        toastr.options.preventDuplicates = true;
                        toastr.info("Now follow 5 or more stores of interest.");
                    } else if(data.merchant_count >= 5) {
                        // alert('Merchant completed');
                        // users/follow/merc hants

                        $.ajax({
                            url:'/users/follow/merchants',
                            type:'POST',
                            // data: {success,},
                            success:function(data){
                                window.location = "/";
                            },
                            error: function (data) {
                                toastr.options.preventDuplicates = true;
                                toastr.error("An error occured while following "+ reciever_full_name);
                                // var obj = jQuery.parseJSON( data.responseText );
                            }
                        });

                    }
                    // f_counter++;
                    // toastr.options.preventDuplicates = true;
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured while following "+ reciever_full_name);
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });

$(document).on('click', ".c_unfollow", function(e){

          // alert('clickrf now kilonshele');
            e.preventDefault();
        var follow_id = $(this).data("id");
            
            var reciever_email = $(this).data("email");
            var reciever_full_name = $(this).data("fname");

            // alert(reciever_email);
            // $(this).removeClass('unfollow').text(' follow');
            // $(this).addClass('follow');
             $(this).removeClass('c_unfollow');
            $(this).addClass('c_follow');
            $(this).removeClass('unfollow_btn');
            $(this).addClass('follow_btn');
            // $(this).remove('i');
            // $(this).remove('i');
            $(this).removeClass('fa fa-check');
            // $(this).removeClass('follow');
            // $(this).addClass('fa fa-user');
            // $(this).addClass('f-14');


            $.ajax({
                url:'/unfollow/'+reciever_email,
                type:'POST',
                data: {reference:reciever_email},
                success:function(data){
                    console.log(data);
                    var f_counter = $(".count").text(data.user_count);
                    var m_counter = $(".m_count").text(data.merchant_count);

                    if ($(".count").text() >= 10) {
                        window.location = "/users/follow/merchants";
                        toastr.options.preventDuplicates = true;
                        toastr.info("Now follow 5 or more stores of interest.");
                    } else if(data.merchant_count >= 5) {
                        // alert('Merchant completed');


                    }
                    // 
                    // alert(data);
                    // console.log(data);
                    // var current_count = $(".follow_count").text();
                    // current_count--;
                    // $(".follow_count").text(current_count);
                    // toastr.options.preventDuplicates = true;
                    // toastr.info("You unfollowed "+reciever_full_name);
                },
                error: function (data) {
                    toastr.options.preventDuplicates = true;
                    toastr.error("An error occured while following "+ reciever_full_name);
                    // var obj = jQuery.parseJSON( data.responseText );
                }
            });
    });

// function()