<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Topic;
use Illuminate\Http\Request;
use Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of the topics.
     */
    public function index()
    {
        // Get all topics with their relationships and paginate them (removed questionPapers)
        $topics = Topic::with(['exams', 'questions'])->paginate(10);
        
        // Get all exams for the modal dropdown
        $exams = Exam::all();
        
        // Pass the topics and exams data to the view
        return view('admin.topics.index', [
            'topics' => $topics,
            'exams' => $exams
        ]);
    }

    /**
     * Display the specified topic.
     */
    public function show(Topic $topic)
    {
        // Load the topic with its relationships (removed questionPapers)
        $topic->load(['exams', 'questions']);
        
        // Pass the topic data to the view
        return view('admin.topics.show', [
            'topic' => $topic
        ]);
    }

    /**
     * Show the form for editing the specified topic.
     */
    public function edit(Topic $topic)
    {
        // Load the topic with its relationships (removed questionPapers)
        $topic->load(['exams', 'questions']);
        
        // Get all exams for the dropdown
        $exams = Exam::all();
        
        // Pass the topic and exams data to the view
        return view('admin.topics.edit', [
            'topic' => $topic,
            'exams' => $exams
        ]);
    }

    /**
     * Store a newly created topic in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:topics',
            'exams' => 'nullable|array',
            'exams.*' => 'exists:exams,id',
        ]);

        try {
            // Create the topic record
            $topic = Topic::create([
                'name' => $request->name,
            ]);

            // Attach exams if provided
            if ($request->has('exams')) {
                $topic->exams()->attach($request->exams);
            }

            // Redirect back with success message
            return redirect()->route('admin.topics')->with('success', 'Topic created successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.topics')->with('error', 'Failed to create topic: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified topic in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:topics,name,' . $topic->id,
            'exams' => 'nullable|array',
            'exams.*' => 'exists:exams,id',
        ]);

        try {
            // Update the topic record
            $topic->update([
                'name' => $request->name,
            ]);

            // Sync exams if provided
            if ($request->has('exams')) {
                $topic->exams()->sync($request->exams);
            } else {
                // If no exams are selected, detach all
                $topic->exams()->detach();
            }

            // Redirect back with success message
            return redirect()->route('admin.topics')->with('success', 'Topic updated successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.topics')->with('error', 'Failed to update topic: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified topic from storage.
     */
    public function destroy(Topic $topic)
    {
        try {
            // Removed check for question papers

            // Check if topic has questions
            if ($topic->questions()->count() > 0) {
                return redirect()->route('admin.topics')->with('error', 'Cannot delete topic with questions');
            }

            // Delete the topic record
            $topic->delete();

            return redirect()->route('admin.topics')->with('success', 'Topic deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.topics')->with('error', 'Failed to delete topic: ' . $e->getMessage());
        }
    }
}
