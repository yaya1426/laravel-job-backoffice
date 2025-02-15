<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Company::latest();

            // Filter by active/archived status
            if ($request->input('archived') === 'true') {
                $query->onlyTrashed();
            }

            $companies = $query->paginate(10)->onEachSide(1);
            return view('company.index', compact('companies'));
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while fetching companies.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $industries = [
                'Technology',
                'Finance',
                'Healthcare',
                'Education',
                'Retail',
                'Manufacturing',
                'Real Estate',
                'Hospitality',
                'Entertainment',
                'Transportation'
            ];
            return view('company.create', compact('industries'));
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while loading the form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyCreateRequest $request)
    {
        try {
            // Create new owner user
            $owner = User::create([
                'name' => $request->input('owner_name'),
                'email' => $request->input('owner_email'),
                'password' => Hash::make($request->input('owner_password')),
                'role' => 'company-owner',
            ]);

            // Create the company and assign the new owner
            Company::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'industry' => $request->input('industry'),
                'website' => $request->input('website'),
                'ownerId' => $owner->id,
            ]);

            return redirect()->route('company.index')->with('success', 'Company and owner created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while creating the company. Please ensure all fields are correctly filled.');
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $company = Company::findOrFail($id);  // Find the company or throw 404

            $applicants = JobApplication::with('user', 'jobVacancy')
                ->whereIn('jobId', $company->jobVacancies->pluck('id'))
                ->get();

            return view('company.show', compact('company', 'applicants'));
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'Company not found or an error occurred.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $industries = [
                'Technology',
                'Finance',
                'Healthcare',
                'Education',
                'Retail',
                'Manufacturing',
                'Real Estate',
                'Hospitality',
                'Entertainment',
                'Transportation'
            ];
            $company = Company::findOrFail($id);
            return view('company.edit', compact('company', 'industries'));
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'Company not found or an error occurred.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        try {
            // Find the company and update its details
            $company = Company::findOrFail($id);
            $company->update([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'industry' => $request->input('industry'),
                'website' => $request->input('website'),
            ]);

            // Update owner password if provided
            if ($request->filled('owner_password')) {
                $company->owner->update([
                    'password' => Hash::make($request->input('owner_password')),
                ]);
            }

            return redirect()->route('company.index')->with('success', 'Company updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while updating the company.');
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('company.index')->with('error', 'Cannot delete this company because it has associated job vacancies.');
            }
            return redirect()->route('company.index')->with('error', 'An error occurred while deleting the company.');
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An unexpected error occurred.');
        }
    }

    public function restore(string $id)
    {
        try {
            $company = Company::onlyTrashed()->findOrFail($id);
            $company->restore(); // Restore the company

            return redirect()->route('company.index', ['archived' => 'true'])->with('success', 'Company restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while restoring the company.');
        }
    }
}
