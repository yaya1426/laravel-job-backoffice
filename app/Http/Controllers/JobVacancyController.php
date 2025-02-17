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

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = JobVacancy::latest();

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
            $companies = Company::all();
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
            JobVacancy::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'type' => $request->input('type'),
                'salary' => $request->input('salary'),
                'companyId' => $request->input('companyId'),
                'categoryId' => $request->input('categoryId'),
            ]);

            return redirect()->route('job-vacancy.index')->with('success', 'Job vacancy created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while creating the job vacancy.');
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // try {
        $jobVacancy = JobVacancy::with('company', 'jobCategory', 'jobApplications')->findOrFail($id);

        return view('job-vacancy.show', compact('jobVacancy'));
        // } catch (Exception $e) {
        //     return redirect()->route('job-vacancy.index')->with('error', 'Job vacancy not found.');
        // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $jobVacancy = JobVacancy::findOrFail($id);
            $companies = Company::all();
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
            $jobVacancy->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'type' => $request->input('type'),
                'salary' => $request->input('salary'),
                'companyId' => $request->input('companyId'),
                'categoryId' => $request->input('categoryId'),
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
            $jobVacancy->restore();

            return redirect()->route('job-vacancy.index', ['archived' => 'true'])->with('success', 'Job vacancy restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('job-vacancy.index')->with('error', 'An error occurred while restoring the job vacancy.');
        }
    }
}
