<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\ApplicationUpdateRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the applications.
     */
    public function index(Request $request)
    {
        try {
            $query = JobApplication::latest()->with(['user', 'jobVacancy']);

            // If user is company owner, only show applications for their company
            if (Auth::user()->role === 'company-owner') {
                $query->whereHas('jobVacancy.company', function($q) {
                    $q->where('ownerId', Auth::id());
                });
            }

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
            $application = JobApplication::with(['user', 'jobVacancy.company'])->findOrFail($id);

            // Check if user has access to this application
            if (Auth::user()->role === 'company-owner' &&
                $application->jobVacancy->company->ownerId !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

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
            $application = JobApplication::with(['jobVacancy.company'])->findOrFail($id);

            // Check if user has access to this application
            if (Auth::user()->role === 'company-owner' &&
                $application->jobVacancy->company->ownerId !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $users = User::where('role', 'job-seeker')->get();
            $jobs = JobVacancy::when(Auth::user()->role === 'company-owner', function($query) {
                $query->whereHas('company', function($q) {
                    $q->where('ownerId', Auth::id());
                });
            })->get();

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
            $application = JobApplication::with(['jobVacancy.company'])->findOrFail($id);

            // Check if user has access to this application
            if (Auth::user()->role === 'company-owner' &&
                $application->jobVacancy->company->ownerId !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

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
            // Check if user is admin
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }

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
            // Check if user is admin
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }

            $application = JobApplication::onlyTrashed()->findOrFail($id);
            $application->restore();

            return redirect()->route('application.index', ['archived' => 'true'])->with('success', 'Application restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('application.index')->with('error', 'An error occurred while restoring the application.');
        }
    }
}
