<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancyCreateRequest;
use App\Http\Requests\JobVacancyUpdateRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Company;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = JobVacancy::latest();

            // If user is company owner, only show their company's job vacancies
            if (Auth::user()->role === 'company-owner') {
                $query->whereHas('company', function ($q) {
                    $q->where('ownerId', Auth::id());
                });
            }

            // Filter by active/archived status
            if ($request->input('archived') === 'true') {
                $query->onlyTrashed();
            }

            $jobVacancies = $query->paginate(10)->onEachSide(1);
            return view('job-vacancy.index', compact('jobVacancies'));
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while fetching job vacancies.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // If user is company owner, only show their company
            if (Auth::user()->role === 'company-owner') {
                $companies = Company::where('ownerId', Auth::id())->get();
            } else {
                $companies = Company::all();
            }
            $categories = JobCategory::all();
            return view('job-vacancy.create', compact('companies', 'categories'));
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while loading the form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobVacancyCreateRequest $request)
    {
        try {
            // If user is company owner, verify they own the company
            if (Auth::user()->role === 'company-owner') {
                $company = Company::findOrFail($request->input('companyId'));
                if ($company->ownerId !== Auth::id()) {
                    return redirect()->route('job-vacancy.index')->with('error', 'You can only create job vacancies for your own company.');
                }
            }

            // Log the request data for debugging
            Log::info('Creating job vacancy with data:', $request->all());

            $jobVacancy = JobVacancy::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'type' => $request->input('type'),
                'salary' => $request->input('salary'),
                'companyId' => $request->input('companyId'),
                'categoryId' => $request->input('categoryId'),
                'required_skills' => $request->input('required_skills'),
            ]);

            return redirect()->route('job-vacancy.index')->with('success', 'Job vacancy created successfully.');
        } catch (QueryException $e) {
            Log::error('Job Vacancy Creation Error: ' . $e->getMessage());
            Log::error('Error Code: ' . $e->getCode());
            return redirect()->route('job-vacancy.index')
                ->with('error', 'An error occurred while creating the job vacancy. Error: ' . $e->getMessage())
                ->withInput();
        } catch (Exception $e) {
            Log::error('Job Vacancy Creation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('job-vacancy.index')
                ->with('error', 'An unexpected error occurred. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobVacancy = JobVacancy::with('company', 'jobCategory', 'jobApplications')->findOrFail($id);

        // If user is company owner, verify they own the company
        if (Auth::user()->role === 'company-owner' && $jobVacancy->company->ownerId !== Auth::id()) {
            return redirect()->route('job-vacancy.index')->with('error', 'You can only view job vacancies for your own company.');
        }

        return view('job-vacancy.show', compact('jobVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $jobVacancy = JobVacancy::findOrFail($id);

            // If user is company owner, verify they own the company
            if (Auth::user()->role === 'company-owner' && $jobVacancy->company->ownerId !== Auth::id()) {
                return redirect()->route('job-vacancy.index')->with('error', 'You can only edit job vacancies for your own company.');
            }

            // If user is company owner, only show their company
            if (Auth::user()->role === 'company-owner') {
                $companies = Company::where('ownerId', Auth::id())->get();
            } else {
                $companies = Company::all();
            }
            $categories = JobCategory::all();
            return view('job-vacancy.edit', compact('jobVacancy', 'companies', 'categories'));
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'Job vacancy not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobVacancyUpdateRequest $request, string $id)
    {
        try {
            $jobVacancy = JobVacancy::findOrFail($id);

            // If user is company owner, verify they own the company
            if (Auth::user()->role === 'company-owner' && $jobVacancy->company->ownerId !== Auth::id()) {
                return redirect()->route('job-vacancy.index')->with('error', 'You can only update job vacancies for your own company.');
            }

            $jobVacancy->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'type' => $request->input('type'),
                'salary' => $request->input('salary'),
                'companyId' => $request->input('companyId'),
                'categoryId' => $request->input('categoryId'),
                'required_skills' => $request->input('required_skills'),
            ]);

            if ($request->query('redirectToList') == 'false') {
                return redirect()->route('job-vacancy.show', ['job_vacancy' => $jobVacancy->id])
                    ->with('success', 'Job vacancy updated successfully.');
            }

            return redirect()->route('job-vacancy.index')->with('success', 'Job vacancy updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while updating the job vacancy.');
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id)
    {
        try {
            $jobVacancy = JobVacancy::findOrFail($id);

            // If user is company owner, verify they own the company
            if (Auth::user()->role === 'company-owner' && $jobVacancy->company->ownerId !== Auth::id()) {
                return redirect()->route('job-vacancy.index')->with('error', 'You can only archive job vacancies for your own company.');
            }

            $jobVacancy->delete();

            return redirect()->route('job-vacancy.index')->with('success', 'Job vacancy archived successfully.');
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while archiving the job vacancy.');
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        try {
            $jobVacancy = JobVacancy::onlyTrashed()->findOrFail($id);

            // If user is company owner, verify they own the company
            if (Auth::user()->role === 'company-owner' && $jobVacancy->company->ownerId !== Auth::id()) {
                return redirect()->route('job-vacancy.index')->with('error', 'You can only restore job vacancies for your own company.');
            }

            $jobVacancy->restore();

            return redirect()->route('job-vacancy.index', ['archived' => 'true'])->with('success', 'Job vacancy restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while restoring the job vacancy.');
        }
    }
}
