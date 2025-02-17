<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\ApplicationUpdateRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the applications.
     */
    public function index(Request $request)
    {
        try {
            $query = JobApplication::latest()->with(['user', 'jobVacancy']);

            // Filter by active/archived status
            if ($request->input('archived') === 'true') {
                $query->onlyTrashed();
            }

            $applications = $query->paginate(10)->onEachSide(1);
            return view('application.index', compact('applications'));
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'An error occurred while fetching applications.');
        }
    }

    /**
     * Display the specified application.
     */
    public function show(string $id)
    {
        try {
            $application = JobApplication::with(['user', 'jobVacancy'])->findOrFail($id);
            return view('application.show', compact('application'));
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'Application not found or an error occurred.');
        }
    }

    /**
     * Show the form for editing the specified application.
     */
    public function edit(string $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $users = User::where('role', 'job-seeker')->get();
            $jobs = JobVacancy::all();
            return view('application.edit', compact('application', 'users', 'jobs'));
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'Application not found or an error occurred.');
        }
    }

    /**
     * Update the specified application in storage.
     */
    public function update(ApplicationUpdateRequest $request, string $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $application->update([
                'status' => $request->input('status'),
            ]);

            if ($request->query('redirectToList') == 'false') {
                return redirect()->route('application.show', ['application' => $application->id])
                    ->with('success', 'Application updated successfully.');
            }

            return redirect()->route('application.index')->with('success', 'Application updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('application.index')->with('error', 'An error occurred while updating the application.');
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Remove the specified application (soft delete).
     */
    public function destroy(string $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $application->delete();

            return redirect()->route('application.index')->with('success', 'Application archived successfully.');
        } catch (QueryException $e) {
            return redirect()->route('application.index')->with('error', 'An error occurred while archiving the application.');
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Restore a soft-deleted application.
     */
    public function restore(string $id)
    {
        try {
            $application = JobApplication::onlyTrashed()->findOrFail($id);
            $application->restore();

            return redirect()->route('application.index', ['archived' => 'true'])->with('success', 'Application restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'An error occurred while restoring the application.');
        }
    }
}
