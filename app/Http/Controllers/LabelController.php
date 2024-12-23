<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Label;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::paginate();

        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        return view('labels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        Label::create([
            'name' => $request->name,
        ]);
    
        return redirect(route('labels.index'))->with('success', 'Label created successfully.');
    }
    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        Label::findorfail($label->id)->update(['name' => $request->name]);
    
        return redirect(route('labels.index'))->with('success', 'Label created successfully.');
    }
    public function destroy(Label $label)
    {
        $label->delete();

        return to_route('labels.index');
    }
}
