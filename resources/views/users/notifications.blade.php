@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">notifications</div>

                <div class="panel-body">
                    @forelse ($notifications as $notification)
                        <div class="col-sm-10 col-sm-offset-1 well">

                            <p><a href="{{ route('user_profile', $notification->foreigner->email) }}">{{ $notification->message }}</a></p>
                        </div>
                    @empty
                        <div class="col-sm-10 col-sm-offset-1 well">
                            <h3><b class="text-danger">No Notifications</b></h3>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Product notifications</div>

                <div class="panel-body">
                    @forelse ($product_notifications as $prod_note)
                        <div class="col-sm-10 col-sm-offset-1 well">

                            <p><a href="{{ route('product', $prod_note->product->id) }}">{{ $prod_note->message }}</a></p>
                        </div>
                    @empty
                        <div class="col-sm-10 col-sm-offset-1 well">
                            <h3><b class="text-danger">No Notifications</b></h3>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
