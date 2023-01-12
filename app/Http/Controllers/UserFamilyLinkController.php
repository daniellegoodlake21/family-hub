<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\UserFamilyLink;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as Request2;
use DB;

class UserFamilyLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $pending = "Pending Admin Approval";
        // get all 'user_family_link' records associated with this user
        $userFamilies = UserFamilyLink::where('user_id', Auth::id())->with('user:id,name')->latest()->get();
        // get all pending requests from other users
        $pendingRequests = UserFamilyLink::with('user:id,name')->where('status', '=', $pending);
        $otherUsersPendingRequests = $pendingRequests->where('user_id', '!=', Auth::id())->latest()->get(); 
        // gets the families from the 'family' table which have the current user set as the Admin
        $adminFamilies = Family::where('admin_id', '=', Auth::id())->latest()->get();
        // create an array of all the family usernames from the families for which the current user is the Admin
        $familiesUserIsAdminAndRequested = [];
        foreach ($adminFamilies as $adminFamily)
        {
            $familiesUserIsAdminAndRequested[] = $adminFamily->family_username;
        }
        // get final set of relevant pending requests
        $relevantPendingRequests = $otherUsersPendingRequests->whereIn('family_username', $familiesUserIsAdminAndRequested); 
        
        $result = Inertia::render('Families/Index', [
            'families' => $userFamilies->where('status', '!=', $pending),
            'pending_families' => $userFamilies->where('status', '=', $pending),
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {;
        $validated = $request->validate([
            'family_username' => 'required|string|max:40|unique:family',
            'status' => 'string|required'
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserFamilyLink  $userFamilyLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFamilyLink $userFamilyLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFamilyLink  $userFamilyLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFamilyLink $userFamilyLink)
    {
        //
    }
}
