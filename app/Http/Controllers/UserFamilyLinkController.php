<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\UserFamilyLink;
use App\Models\Family;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserFamilyLinkRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as Request2;

class UserFamilyLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $pending = 'Pending Admin Approval';
        // get all 'user_family_link' records associated with this user and split them into two categories based on status: Pending and Joined (note that Joined is anything other than pending, so it can either be 'Joined' or 'Admin')
        $userJoinedFamilies = UserFamilyLink::with('user:id,name')->where('user_id', Auth::id())->where('status', '!=', $pending)->paginate(3);
        $userPendingFamilies = UserFamilyLink::with('user:id,name')->where('user_id', Auth::id())->where('status', '=', $pending)->paginate(3);
        // get all pending requests from other users
        $pendingRequests = UserFamilyLink::with('user:id,name')->where('status', '=', $pending);
        $otherUsersPendingRequests = $pendingRequests->where('user_id', '!=', Auth::id()); 
        // gets the families from the 'family' table which have the current user set as the Admin
        $adminFamilies = Family::where('admin_id', '=', Auth::id());
        // create an array of all the family usernames from the families for which the current user is the Admin
        $familiesUserIsAdminAndRequested = [];
        foreach ($adminFamilies as $adminFamily)
        {
            $familiesUserIsAdminAndRequested[] = $adminFamily->family_username;
        }
        // get final set of relevant pending requests
        $relevantPendingRequests = $otherUsersPendingRequests->whereIn('family_username', $familiesUserIsAdminAndRequested)->paginate(3);
        $result = Inertia::render('Families/Index', [
            'families' => $userJoinedFamilies,
            'pending_families' => $userPendingFamilies,
            'pending_family_join_requests' => $relevantPendingRequests
        ]);
        return $result;
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
     * @param  \Illuminate\Http\Request; $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
                'family_username' => 'required|string|max:40|unique:family',
                'status' => 'string|required'
            ]
        );
        $request->user()->userFamilies()->create($validated); // inserts into the user_family_link table
        $familyExists = Family::where('family_username', $request->family_userame)->exists();
        if (!$familyExists && $validated)
        {
            $family = new Family;
            $family->family_username = $request->family_username;
            $family->admin_id = Auth::id();
            $family->save(); // if the family does not already exist, inserts into the family table
        }
        return redirect(route('families.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserFamilyLink  $userFamilyLink
     * @return \Illuminate\Http\Response
     */
    public function show(UserFamilyLink $userFamilyLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserFamilyLink  $userFamilyLink
     * @return \Illuminate\Http\Response
     */
    public function edit(UserFamilyLink $userFamilyLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserFamilyLinkRequest $request
     * @param  \App\Models\UserFamilyLink  $userFamilyLink
     * @return \Illuminate\Http\Response
     */
    public function update(UserFamilyLinkRequest $request, UserFamilyLink $family)
    {
            // REMOVE THE COMMENT BELOW WHEN NO LONGER NEEDED FOR NOTIFICATIONS
            //->with('message', 'You denied ' . $requester . ' entry to the ' . $userFamilyLink->family_username . ' Family Group.');
            $family->status = $request->status;
            $validated = $request->validated();
            $family->update($validated);
            return redirect(route('families.index'));
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFamilyLink  $userFamilyLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFamilyLink $family)
    {
        $family->delete();
        return redirect(route('families.index'));
    }
}
