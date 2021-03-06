<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use App\Models\Follower;
use App\Models\State;
use App\Models\TradeCommunity;
use App\Models\Notification;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    public function show($reference)
    {
        $user = User::where('reference', $reference)->with([
            'profile_picture', 
            ])->first();
    }

    public function following(Request $request, $reference, $filter = null)
    {
        $user = User::where('reference', $reference)->with(['following' => function ($q){
            $q->with(['profile_picture', 'role']);
        }])->first();

        $following_count = count($user->following);

        $user_role = Role::where('name', 'User')->pluck('id')->toArray();
          
        if (strtolower($filter) == 'merchant') {
            $following = $user->following()->whereNotIn('role_id', $user_role)
                              ->orderBy('first_name')->orderBy('last_name')->paginate(20);
        } elseif (strtolower($filter) == 'user') {
            $following = $user->following()->whereIn('role_id', $user_role)
                              ->orderBy('first_name')->orderBy('last_name')->paginate(20);
        } else {
            $following = $user->following()->orderBy('first_name')->orderBy('last_name')->paginate(20);
            $filter = '';
        }

        // manual pigination since $following is not a query builder but a colletion
        if ($this->isValidPageNumber($request->page)) {
            return view('users.partials.following_bridger', 
            compact('user', 'following', 'following_ids', 'filter')
            );
        }
        return view('users.following_bridger', 
            compact('user', 'following', 'following_count', 'following_ids', 'filter')
            );
    }

    public function followers(Request $request, $reference, $filter = null)
    {
        $user = User::where('reference', $reference)->with(['following' => function ($q){
            $q->with('profile_picture');
        }])->first();


        $followers_count =  count($user->followers);

        $user_role = Role::where('name', 'User')->pluck('id')->toArray();
          
        if (strtolower($filter) == 'merchant') {
            $followers = $user->followers()->whereNotIn('role_id', $user_role)
                              ->orderBy('first_name')->orderBy('last_name')->paginate(20);
        } elseif (strtolower($filter) == 'user') {
            $followers = $user->followers()->whereIn('role_id', $user_role)
                              ->orderBy('first_name')->orderBy('last_name')->paginate(20);
        } else {
            $followers = $user->followers()->orderBy('first_name')->orderBy('last_name')->paginate(20);
            $filter = '';
        }

        if ($this->isValidPageNumber($request->page)) {
            return view('users.partials.followers_bridger', 
            compact('user', 'followers', 'followers_ids', 'filter')
            );
        }

        $following_ids = Follower::where('follower_user_id', auth()->user()->id)->pluck('user_id')->toArray();
        return view('users.followers_bridger', 
            compact('user', 'followers', 'followers_count', 'following_ids', 'filter')
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        $role_user = Role::where('name', 'User')->get();
        $role_merchant = Role::where('name', 'Merchant')->get();
        
        auth()->user()->following()->attach($user->id);
        // notify $user the he has been followed by auth user
        $notification = Notification::create([
            'user_id' => auth()->user()->id,
            'message' => auth()->user()->full_name() . ' is now following you!',
            ]);
        $notification->users()->attach($user);
        $notification->save();

        $num_of_user = count(auth()->user()->following()->whereIn('role_id', $role_user)->get());
        $num_of_merchant = count(auth()->user()->following()->whereIn('role_id', $role_merchant)->get());
         return response()->json([
          'reference' => $user->reference,
          'user_count'    =>  $num_of_user,
          'merchant_count' => $num_of_merchant
        ]);
        // return response()->json($user->reference]);
        // return back()->with('success', 'Now following ' . $user->email);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        // get the id of the users that the auth user follows
        $num_of_user = $num_of_merchant = 0;
        $following_ids = Follower::where('follower_user_id', auth()->user()->id)->pluck('user_id')->toArray();
        $role_id = Role::where('name', 'User')->pluck('id')->toArray();
        $users = User::with(['profile_picture', 'community' ])->where('id', '!=', auth()->user()->id)->whereIn('role_id', $role_id)->paginate(12);
        return view('users.follow_friends', compact('users', 'following_ids', 'num_of_merchant', 'num_of_user'));
    }

    public function friendsFollowComplete()
    {
        auth()->user()->registration_status = 2;
        auth()->user()->save();
        return redirect(route('follow_merchants'));
    }

    public function getMerchant(Request $request)
    {
        // dd(auth()->user()->email);
        // get the id of the users that the auth user follows
        $communities = TradeCommunity::all();
        $following_ids = Follower::where('follower_user_id', auth()->user()->id)->pluck('user_id')->toArray();
        $role_id = Role::where('name', 'Merchant')->pluck('id')->toArray();
        $users = User::with(['profile_picture', 'community' ])->where('id', '!=', auth()->user()->id)->whereIn('role_id', $role_id);

        if($request->filter != null && filter_var($request->filter, FILTER_VALIDATE_INT)){
            $community = TradeCommunity::where('id', $request->filter)->first();
            $users = $users->where('community_id', $community->id);
        }

        $users = $users->paginate(12);
        return view('users.follow_brands', compact('users', 'following_ids', 'communities'));
    }

    public function merchantsFollowComplete()
    {
        $done = auth()->user()->registration_status = null;
        auth()->user()->save();

        return response()->json([
          'success' => $done
          
        ]);
        // return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $role_user = Role::where('name', 'User')->get();
        $role_merchant = Role::where('name', 'Merchant')->get();
        
        auth()->user()->following()->detach($user->id);
        //notify $user that he has been unfollowed by auth user
        $notification = Notification::create([
            'user_id' => auth()->user()->id,
            'message' => auth()->user()->full_name() . ' unfollowed you!',
            ]);
        $notification->users()->attach($user);
        $notification->save();

        $num_of_user = count(auth()->user()->following()->whereIn('role_id', $role_user)->get());
        $num_of_merchant = count(auth()->user()->following()->whereIn('role_id', $role_merchant)->get());
        // return response()->json($user->reference, $num_of_merchant, $num_of_user);
        return response()->json([
          'reference' => $user->reference,
          'user_count'    =>  $num_of_user,
          'merchant_count' => $num_of_merchant
        ]);
        
        // return back()->with('info', 'unfollowed ' . $email);
    }

}
