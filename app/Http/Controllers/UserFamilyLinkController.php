<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\UserFamilyLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFamilyLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Families/Index', [
            'families' => UserFamilyLink::where('user_id', Auth::id())->with('user:id,name')->latest()->get(),
        ]);
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
    {
        $validated = $request->validate([
            'family_username' => 'required|string|max:40|unique:user_family_link, family_username',
            'status' => 'string|required'
        ]);
        $request->user()->userFamilies()->create($validated);
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
