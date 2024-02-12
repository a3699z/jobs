<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!userCan('skills.view'), 403);

        $skills = Skill::paginate(15);
        return view('admin.skill.index', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!userCan('skills.create'), 403);

        $request->validate([
            'name' => 'required|string|unique:skills,name|max:255',
        ]);

        $newSkill = Skill::create($request->only('name'));

        $newSkill ? flashSuccess('Skill created successfully') : flashError('Something went wrong...');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Skill $skill)
    {
        abort_if(!userCan('skills.update'), 403);

        $skills = Skill::latest()->paginate(15);
        $skilll = $skill;

        return view('admin.skill.index', compact('skilll', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Skill $skill)
    {
        abort_if(!userCan('skills.update'), 403);

        $request->validate([
            'name' => 'required|max:255|unique:skills,name,' . $skill->id,
        ]);

        $skill->name = $request->name;
        $edited = $skill->save();

        $edited ? flashSuccess('Skill updated successfully') : flashSuccess('Something went wrong...');

        return redirect()->route('skill.edit', $skill->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Skill $skill)
    {
        abort_if(!userCan('skills.delete'), 403);

        $success = $skill->delete();
        $success ? flashSuccess('Skill deleted successfully!') : flashSuccess('Something went wrong...');
        return redirect()->route('skill.index');
    }
}
