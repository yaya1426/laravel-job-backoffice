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

            // If user is company owner, verify they have a company and show only their applications
            if (auth()->user()->role === 'company-owner') {
                $company = auth()->user()->company;
                
                // If company owner doesn't have a company associated, redirect to dashboard
                if (!$company) {
                    return redirect()->route('dashboard')->with('error', 'No company associated with your account. Please contact an administrator.');
                }

                $query->whereHas('jobVacancy', function($q) use ($company) {
                    $q->where('companyId', $company->id);
                });
            }

            // Filter by active/archived status
            if ($request->input('archived') === 'true') {
                $query->onlyTrashed();
            }

            $applications = $query->paginate(10)->onEachSide(1);
            return view('application.index', compact('applications'));
        } catch (Exception $e) {
            // Redirect to dashboard instead of creating a loop
            return redirect()->route('dashboard')->with('error', 'An error occurred while fetching applications.');
        }
    }

    /**
     * Display the specified application.
     */
    public function show(string $id)
    {
        try {
            // If user is company owner, verify they have a company
            if (auth()->user()->role === 'company-owner') {
                $company = auth()->user()->company;
                if (!$company) {
                    return redirect()->route('dashboard')->with('error', 'No company associated with your account. Please contact an administrator.');
                }
            }

            $application = JobApplication::with(['user', 'jobVacancy'])->findOrFail($id);

            // Check if user is company owner and has access to this application
            if (auth()->user()->role === 'company-owner' && $application->jobVacancy->companyId !== auth()->user()->company->id) {
                return redirect()->route('dashboard')->with('error', 'You do not have permission to view this application.');
            }

            return view('application.show', compact('application'));
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Application not found or an error occurred.');
        }
    }

    /**
     * Show the form for editing the specified application.
     */
    public function edit(string $id)
    {
        try {
            // If user is company owner, verify they have a company
            if (auth()->user()->role === 'company-owner') {
                $company = auth()->user()->company;
                if (!$company) {
                    return redirect()->route('dashboard')->with('error', 'No company associated with your account. Please contact an administrator.');
                }
            }

            $application = JobApplication::findOrFail($id);

            // Check if user is company owner and has access to this application
            if (auth()->user()->role === 'company-owner' && $application->jobVacancy->companyId !== auth()->user()->company->id) {
                return redirect()->route('dashboard')->with('error', 'You do not have permission to edit this application.');
            }

            $users = User::where('role', 'job-seeker')->get();
            
            // If user is company owner, only show their company's job vacancies
            if (auth()->user()->role === 'company-owner') {
                $jobs = JobVacancy::where('companyId', auth()->user()->company->id)->get();
            } else {
                $jobs = JobVacancy::all();
            }

            return view('application.edit', compact('application', 'users', 'jobs'));
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Application not found or an error occurred.');
        }
    }

    /**
     * Update the specified application in storage.
     */
    public function update(ApplicationUpdateRequest $request, string $id)
    {
        try {
            // If user is company owner, verify they have a company
            if (auth()->user()->role === 'company-owner') {
                $company = auth()->user()->company;
                if (!$company) {
                    return redirect()->route('dashboard')->with('error', 'No company associated with your account. Please contact an administrator.');
                }
            }

            $application = JobApplication::findOrFail($id);

            // Check if user is company owner and has access to this application
            if (auth()->user()->role === 'company-owner' && $application->jobVacancy->companyId !== auth()->user()->company->id) {
                return redirect()->route('dashboard')->with('error', 'You do not have permission to update this application.');
            }

            $application->update($request->validated());

            return redirect()->route('application.index')->with('success', 'Application updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'An error occurred while updating the application.');
        }
    }

    /**
     * Remove the specified application from storage (soft delete).
     */
    public function destroy(string $id)
    {
        try {
            // If user is company owner, verify they have a company
            if (auth()->user()->role === 'company-owner') {
                $company = auth()->user()->company;
                if (!$company) {
                    return redirect()->route('dashboard')->with('error', 'No company associated with your account. Please contact an administrator.');
                }
            }

            $application = JobApplication::findOrFail($id);

            // Check if user is company owner and has access to this application
            if (auth()->user()->role === 'company-owner' && $application->jobVacancy->companyId !== auth()->user()->company->id) {
                return redirect()->route('dashboard')->with('error', 'You do not have permission to delete this application.');
            }

            $application->delete();

            return redirect()->route('application.index')->with('success', 'Application archived successfully.');
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'An error occurred while archiving the application.');
        }
    }

    /**
     * Restore the specified application from storage.
     */
    public function restore(string $id)
    {
        try {
            // If user is company owner, verify they have a company
            if (auth()->user()->role === 'company-owner') {
                $company = auth()->user()->company;
                if (!$company) {
                    return redirect()->route('dashboard')->with('error', 'No company associated with your account. Please contact an administrator.');
                }
            }

            $application = JobApplication::onlyTrashed()->findOrFail($id);

            // Check if user is company owner and has access to this application
            if (auth()->user()->role === 'company-owner' && $application->jobVacancy->companyId !== auth()->user()->company->id) {
                return redirect()->route('dashboard')->with('error', 'You do not have permission to restore this application.');
            }

            $application->restore();

            return redirect()->route('application.index', ['archived' => 'true'])->with('success', 'Application restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'An error occurred while restoring the application.');
        }
    }
}
