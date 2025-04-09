<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Company::latest();

            // If user is company owner, only show their company
            if (Auth::user()->role === 'company-owner') {
                $query->where('ownerId', Auth::id());
            }

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
        // Only admin can create companies
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('company.index')->with('error', 'You do not have permission to create companies.');
        }

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

            print_r($request->query('redirectToList'));

            if ($request->query('redirectToList') == 'false') {
                return redirect()->route('company.show', ['company' => $company->id])
                    ->with('success', 'Company updated successfully.');
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
        // Only admin can archive companies
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('company.index')->with('error', 'You do not have permission to archive companies.');
        }

        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return redirect()->route('company.index')->with('success', 'Company archived successfully.');
        } catch (QueryException $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while archiving the company.');
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An unexpected error occurred.');
        }
    }

    public function restore(string $id)
    {
        // Only admin can restore companies
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('company.index')->with('error', 'You do not have permission to restore companies.');
        }

        try {
            $company = Company::onlyTrashed()->findOrFail($id);
            $company->restore();

            return redirect()->route('company.index', ['archived' => 'true'])->with('success', 'Company restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('company.index')->with('error', 'An error occurred while restoring the company.');
        }
    }
}
