<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penalty;
use App\Models\CollectionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CollectorManagementController extends Controller
{
    /**
     * Display the collector management dashboard
     */
    public function index()
    {
        $collectors = User::whereHas('role', function ($query) {
            $query->where('name', 'Collector');
        })->with('role')->withCount('penalties')->get();

        $assignments = CollectionAssignment::with('collector')->orderBy('scheduled_date', 'desc')->get();

        $areaNames = \App\Models\Bin::distinct()->pluck('area_name')->toArray();

        return view('admin.collector-management', compact('collectors', 'assignments', 'areaNames'));
    }

    /**
     * Update collector details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:18|max:65',
            'daily_salary' => 'nullable|numeric|min:0|max:999999.99',
            'status' => 'required|in:on_duty,on_break,off_duty',
            'assigned_area' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['address', 'age', 'daily_salary', 'status', 'assigned_area']));

        return response()->json(['message' => 'Collector updated successfully', 'user' => $user]);
    }

    /**
     * Apply penalty to collector
     */
    public function applyPenalty(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'violation' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'penalty_date' => 'required|date',
            ]);

            $penalty = Penalty::create([
                'user_id' => $user->id,
                'reason' => $request->violation,
                'amount' => $request->amount,
                'penalty_date' => $request->penalty_date,
            ]);

            return response()->json(['message' => 'Penalty applied successfully', 'penalty' => $penalty]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Collector not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while applying penalty', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all assignments
     */
    public function getAssignments()
    {
        $assignments = CollectionAssignment::with('collector')->orderBy('scheduled_date', 'desc')->get();
        return response()->json($assignments);
    }

    /**
     * Create new assignment
     */
    public function createAssignment(Request $request)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'assigned_area' => 'required|string|max:255',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|date_format:H:i',
        ]);

        $assignment = CollectionAssignment::create($request->all());

        return response()->json(['message' => 'Assignment created successfully', 'assignment' => $assignment->load('collector')]);
    }

    /**
     * Update assignment
     */
    public function updateAssignment(Request $request, $id)
    {
        $request->validate([
            'assigned_area' => 'required|string|max:255',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,in_progress,completed,failed',
        ]);

        $assignment = CollectionAssignment::findOrFail($id);
        $assignment->update($request->only(['assigned_area', 'scheduled_date', 'scheduled_time', 'status']));

        return response()->json(['message' => 'Assignment updated successfully', 'assignment' => $assignment->load('collector')]);
    }

    /**
     * Delete assignment
     */
    public function deleteAssignment($id)
    {
        $assignment = CollectionAssignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted successfully']);
    }

    /**
     * Get collector penalties
     */
    public function getPenalties($id)
    {
        $penalties = Penalty::where('user_id', $id)->orderBy('penalty_date', 'desc')->get();
        return response()->json($penalties);
    }

    /**
     * Schedule collection for collector
     */
    public function scheduleCollection(Request $request)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'bin_ids' => 'required|array',
            'bin_ids.*' => 'exists:bins,id',
            'scheduled_date' => 'required|date|after:today',
        ]);

        // Here you would create collection schedules
        // For now, just return success
        return response()->json(['message' => 'Collection scheduled successfully']);
    }
}
