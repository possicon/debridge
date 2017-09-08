@extends('layouts.master')
@section('header')
    <!-- navigations/links right here -->
        <nav class="navbar navbar-toggleable-sm navbar-light transparent p-t-15 p-b-15 no-shadow border-top border-bottom" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav1" aria-controls="navbarNav1" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div id="navbarNav1" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                    @if(auth()->check())
                        @if(auth()->user()->checkRole())
                            <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('view_friends', auth()->user()->reference) }}">Friends</a></li>
                        @else
                            <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('view_partners', auth()->user()->reference) }}">Trade Partners</a></li>
                        @endif
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('following', auth()->user()->reference) }}">Following</a></li>
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('followers', auth()->user()->reference) }}">Followers</a></li>
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('timeline', auth()->user()->reference) }}">Tradeline</a></li>
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('community', auth()->user()->reference) }}">Trade Community</a></li>
                    @else
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" data-toggle="modal" data-target="#basicExample">Following</a></li>
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" data-toggle="modal" data-target="#basicExample">Followers</a></li>
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" data-toggle="modal" data-target="#basicExample">Tradeline</a></li>
                        <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" data-toggle="modal" data-target="#basicExample">Trade Community</a></li>
                    @endif
                    <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('bridge_shops') }}">Bridger Shops</a></li>
                    <li class="nav-item m-r-10"><a class="nav-link hover-underline text-uppercase" href="{{ route('araha_market') }}">Araha Market</a></li>
                    <li class="nav-item"><a class="nav-link hover-underline text-uppercase" href="{{ route('exhibition') }}">Exhibition Stand</a></li>
                    <li class="nav-item"><a class="nav-link hover-underline text-uppercase" href="{{ route('hiring') }}">Hiring</a></li>
                </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>
    <!-- navigations/links ends here -->
@endsection

@section('content')
    <!--main section begins here-->
    <section class="main">
    	<div class="container">
    		<div id="navbarNav1 m-t-40">
	            <h3 class="h3-responsive c-brand f-24 m-t-30 m-b-8">{{ $user->full_name() }} - FRIENDS</h3>
	            <div class="h-58">
	            	{{-- <p class="text-fluid c-gray f-14 dis-inline-b">All users on the bridge u can follow</p> --}}
                    @if(auth()->user()->id != $user->id)
	            	    <a href="{{ route('view_profile', $user->reference) }}" class="c-brand"><button class="btn btn-sm pull-right btn-outline-brand">View Profile <i class="fa fa-user"></i></button></a>
                    @else
                        <a href="{{ route('more_friends') }}"><button class="btn btn-sm pull-right btn-outline-brand"><span class="fa fa-plus m-r-10"></span>ADD MORE FIENDS</button></a>
                    @endif
	            </div>
	        </div>
	        <!-- friends display  -->
	        <div class="m-t-20 m-b-50">

                <hr>
	        	<div class="row" id="users_list">
		        		<!-- first column of friends -->
		        		@include('bridger.partials.friends')
		        		<!-- / second column of friends -->
                        @if($users->count() < 1)
                            <h4 class="h4-responsive c-gray p-20">{{ $user->full_name() }} has no friends yet. </h4>
                        @endif
		        	</div>

	        </div>
    	</div>
    </section>
    <!-- main section ends here -->
@endsection

@section('scripts')
	<script>
        $(document).ready(function () {
            var page = 2;
            $(window).scroll(function(){
               	var scroll_height = $(window).scrollTop() + $(window).height();
                var doc_height = $(document).height() - 10;
                if ( scroll_height >= doc_height ) {
                   $.ajax({
                        url: document.URL,
                        type:'GET',
                        data:{'page': page},
                        success:function(data){
                            $('#users_list').append(data) ;
                        },
                        error: function (data) {
                        }
                    });
                    page += 1;
                }
            });
        }); 
    </script>
@endsection