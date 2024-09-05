<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaryEntry;
use Illuminate\Support\Facades\Auth;

use App\Models\Emotion;

class DiaryEntryController extends Controller
{
    //
    // public function index()
    // {
    //     $diaryEntries = Auth::user()->diaryEntries()->get();
    //     return view('diary.index', compact('diaryEntries'));
    // }

    // public function create()
    // {
    //     return view('diary.create');
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'date' => 'required|date',
    //         'content' => 'required|string',
    //     ]);
    //     Auth::user()->diaryEntries()->create($validated);
    //     return redirect()->route('diary.index')->with('status','Diary entry added successfully!');
    // }

    public function show(string $id)
    {   
        $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
        return view('diary.show', compact('diaryEntry'));
    }

    // public function edit(string $id)
    // {
    //     $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
    //     return view('diary.edit', compact('diaryEntry'));
    // }

    // public function update(Request $request, string $id)
    // {
    //     // Retrieve the diary entry by its ID
    //     $diaryEntry = DiaryEntry::findOrFail($id);
    //     $validated = $request->validate([
    //         'date' => 'required|date',
    //         'content' => 'required|string',
    //     ]);
    //     $diaryEntry->update($validated);
    //     return redirect()->route('diary.index')->with('status','Diary entry updated successfully!');
    // }

    public function destroy(string $id)
    {
        // Retrieve the diary entry by its ID
        $diaryEntry = DiaryEntry::findOrFail($id);
        // Delete the retrieved diary entry
        $diaryEntry->delete();
        // Redirect back to the diary index with a success message
        return redirect()->route('diary.index')->with('status','Diary entry deleted successfully!');
    }

    ///////////////////////////////////////////

    public function index()
    {
        $diaryEntries = Auth::user()->diaryEntries()->with('emotions')->get();
        return view('diary.index', compact('diaryEntries'));
    }

    public function create()
    {
        $emotions = Emotion::all(); // Fetch all emotions for selection
        return view('diary.create', compact('emotions')); // Pass emotions to the view
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'emotions' => 'array', // Validate emotions as an array
            'intensity' => 'array', // Validate intensity as an array
        ]);
        // Create the diary entry
        $diaryEntry = Auth::user()->diaryEntries()->create([
            'date' => $validated['date'],
            'content' => $validated['content'],
        ]);
        // Handle emotions and intensities
        if (!empty($validated['emotions']) &&!empty($validated['intensity'])) 
        {
            foreach ($validated['emotions'] as $emotionId) {
                $intensity = $validated['intensity'][$emotionId] ?? null;
                // Attach emotions and intensities to the diary entry
                $diaryEntry->emotions()->attach($emotionId, ['intensity' => $intensity]);
            }           
        }
        return redirect()->route('diary.index')->with('status', 'Diary entry added successfully!');
    }

    public function edit(string $id)
    {
        $diaryEntry = Auth::user()->diaryEntries()->with('emotions')->findOrFail($id);
        $emotions = Emotion::all(); // you must have a model called Emotion to fetch all emotions
        return view('diary.edit', compact('diaryEntry', 'emotions'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'emotions' => 'array', // Validate emotions as an array
            'intensity' => 'array', // Validate intensity as an array
        ]);
        // Find and update the diary entry
        $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
        $diaryEntry->update([
            'date' => $validated['date'],
            'content' => $validated['content'],
        ]);
        // Sync emotions and intensities
        if (!empty($validated['emotions'])) {
            $emotions = [];
            foreach ($validated['emotions'] as $emotionId) {
                $intensity = $validated['intensity'][$emotionId] ?? null;
                $emotions[$emotionId] = ['intensity' => $intensity];
            }
            $diaryEntry->emotions()->sync($emotions);
        } else {
            // If no emotions are selected, clear all associated emotions
            $diaryEntry->emotions()->sync([]);
        }
        return redirect()->route('diary.index')->with('status', 'Diary entry updated successfully!');
    }
}
