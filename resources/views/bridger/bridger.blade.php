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
	            <h3 class="h3-responsive c-brand f-24 m-t-30 m-b-8">BRIDGERS</h3>
	            <div class="h-58">
	            	<p class="text-fluid c-gray f-14 dis-inline-b">All users on the bridge u can follow</p>
	            	@if(!auth()->user()->checkRole())
	            		<a href="{{ route('add_more_partners') }}"><button class="btn btn-sm f-14 pull-right btn-outline-brand">Add more Partners <i class="fa fa-plus f-17"></i></button></a>
	            	@endif
	            </div>
	        </div>
	        <!-- friends display  -->
	        <div class="m-t-20 m-b-140">
	        	<div class="m-b-20">
	        		<nav class="navbar user-type-navbar no-shadow">
                        <ul class="nav user-type-nav text-center">
                            <li class="nav-item"><a class="nav-link hover-underline @if(strtolower($filter == ''))active @endif" href="{{ route('view_users') }}">ALL</a></li>
                            <li class="nav-item"><a class="nav-link hover-underline @if($filter == 'merchant')active @endif" href="{{ route('view_users', 'merchant') }}">MERCHANT</a></li>
                            <li class="nav-item"><a class="nav-link hover-underline @if($filter == 'user')active @endif" href="{{ route('view_users', 'user') }}">INDIVIDUALS</a></li>
                        </ul>
                    </nav>
	        	</div>
	        	
	        	<div class="row" id="users_list">
	        		<!-- first column of friends -->
	        		@foreach($users as $user)
	        			<div class="col-md-6 col-sm-6 col-xs-6 col-12">
        				<div class="">
	        				<div class="row">
	        					<div class="col-md-3 col-sm-3 col-xs-6">
	        							<div class="profile-picture dis-inline">
		        							@if($user->profile_picture != null)
		        								<img src="{{ route('image', [$user->profile_picture->image_reference,'']) }}" class="p-10 h-100 width-100 card image-resposive">
					        				@else
					        					<img src="{{ asset('img/icons/profile.png') }}" class="p-10 h-100 width-100 card image-resposive">
					        				@endif
			        					</div>
	        					</div>
		        				<div class="col-md-4 col-sm-4 col-xs-4 col-12">
		        					<div class="profile-description dis-inline c-gray-medium">
										<a href="{{ route('timeline', $user->reference) }}"><p class="f-14 m-t-30 text-fluid c-brand">
											{{ $user->full_name() }} 
											@if($user->role_id != null && $user->role->name == 'Merchant')
												<small>(Merchant)</small>
											@endif
										</p></a>
										<p class="f-12 text-fluid">{{ $user->email }}</p>
									</div>
		        				</div>
		        				<div class="col-md-5 col-sm-5 col-xs-5">
		        				@if(in_array($user->id, $following_ids))
		        					{{-- <form method="post" action="{{ route('unfollow', $user->reference) }}"> --}}
		        						<button class="btn unfollow btn-sm f-14 waves-light waves-effect btn-outline-brand m-t-40 m-b-50" data-email="{{$user->reference}}" data-id="{{$user->id}}" data-fname="{{$user->full_name()}}" >Unfollow</button>
		        					{{-- </form> --}}
		        				@else
		        					{{-- <form method="post" action="{{ route('follow', $user->reference) }}"> --}}
		        						<button class="btn follow btn-sm f-14 waves-light waves-effect btn-outline-brand m-t-40 m-b-50" data-email="{{$user->reference}}" data-id="{{$user->id}}" data-fname="{{$user->full_name()}}" >Follow</button>
		        					{{-- </form> --}}
		        				@endif
		        				</div>
	        				</div>
	        			</div>
	        		</div>
	        		@endforeach
	        		<!-- / second column of friends -->
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
