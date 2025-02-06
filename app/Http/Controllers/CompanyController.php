<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Paginate companies (10 per page)
        $companies = Company::paginate(10);
        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'website' => 'nullable|url',
            'ownerId' => 'required|exists:users,id',
        ]);

        // Create a new company
        Company::create([
            'id' => (string) Str::uuid(),  // Generate UUID manually
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'industry' => $request->input('industry'),
            'website' => $request->input('website'),
            'ownerId' => $request->input('ownerId'),
        ]);

        return redirect()->route('company.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);  // Find the company or throw 404

        $applicants = JobApplication::with('user', 'jobVacancy')
            ->whereIn('jobId', $company->jobVacancies->pluck('id'))
            ->get();
        return view('company.show', compact('company', 'applicants'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'website' => 'nullable|url',
        ]);

        // Find the company and update its details
        $company = Company::findOrFail($id);
        $company->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'industry' => $request->input('industry'),
            'website' => $request->input('website'),
        ]);

        return redirect()->route('company.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();  // Delete the company

        return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
    }
}
