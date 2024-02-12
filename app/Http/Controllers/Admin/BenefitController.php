<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!userCan('benefits.view'), 403);

        $benefits = Benefit::latest('id')->paginate(15);
        return view('admin.benefit.index', compact('benefits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!userCan('benefits.create'), 403);

        $request->validate([
            'name' => 'required|string|unique:benefits,name|max:255',
        ]);

        $benefit = Benefit::create($request->only('name'));

        $benefit ? flashSuccess('Benefit created successfully') : flashError('Something went wrong...');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Benefit $benefit)
    {
        abort_if(!userCan('benefits.update'), 403);

        $benefits = Benefit::latest()->paginate(15);

        return view('admin.benefit.index', compact('benefit', 'benefits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Benefit $benefit)
    {
        abort_if(!userCan('benefits.update'), 403);

        $request->validate([
            'name' => 'required|max:255|unique:benefits,name,' . $benefit->id,
        ]);

        $benefit->name = $request->name;
        $edited = $benefit->save();

        $edited ? flashSuccess('Benefit updated successfully') : flashSuccess('Something went wrong...');

        return redirect()->route('benefit.edit', $benefit->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Benefit $benefit)
    {
        abort_if(!userCan('benefits.delete'), 403);

        $success = $benefit->delete();
        $success ? flashSuccess('Benefit deleted successfully!') : flashSuccess('Something went wrong...');
        return redirect()->route('benefit.index');
    }
}
