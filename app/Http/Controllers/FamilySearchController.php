<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Request2;
use Illuminate\Support\Facades\Auth;
use App\Models\Family;

class FamilySearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userFamilies = Auth::user()->userFamilies();
        $userFamilyNames = $userFamilies->select('family_username');
        $search = Request2::input('search');
        $result = Inertia::render('Families/FamilySearch', [
            'families' => Family::where('family.family_username', 'like', '%' . $search . '%')->whereNotIn('family_username', $userFamilyNames)
        ->orderBy('family_username')
        ->paginate(5)
        ->withQueryString(),
        'filters' => Request2::only(['search'])
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
    {
        $validated = $request->validate([
            'family_username' => 'required|string|max:40|exists:family',
            'status' => 'string|required'
        ]);
        $request->user()->userFamilies()->create($validated); // inserts into the user_family_link table
        return redirect(route('families.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilySearchController  $familySearchController
     * @return \Illuminate\Http\Response
     */
    public function show(FamilySearchController $familySearchController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilySearchController  $familySearchController
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilySearchController $familySearchController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FamilySearchController  $familySearchController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilySearchController $familySearchController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilySearchController  $familySearchController
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilySearchController $familySearchController)
    {
        //
    }
}
