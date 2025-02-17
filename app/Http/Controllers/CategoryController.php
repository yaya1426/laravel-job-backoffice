<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\JobCategory;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = JobCategory::latest();

            // Filter by active/archived status
            if ($request->input('archived') === 'true') {
                $query->onlyTrashed();
            }

            $categories = $query->paginate(10)->onEachSide(1);
            return view('category.index', compact('categories'));
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'An error occurred while fetching categories.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('category.create');
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'An error occurred while loading the form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            JobCategory::create([
                'name' => $request->input('name'),
            ]);

            return redirect()->route('category.index')->with('success', 'Category created successfully.');
        } catch (QueryException $e) {
            return redirect()->route('category.index')->with('error', 'An error occurred while creating the category.');
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = JobCategory::findOrFail($id);
            return view('category.show', compact('category'));
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'Category not found or an error occurred.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category = JobCategory::findOrFail($id);
            return view('category.edit', compact('category'));
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'Category not found or an error occurred.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        try {
            $category = JobCategory::findOrFail($id);
            $category->update([
                'name' => $request->input('name'),
            ]);

            if ($request->query('redirectToList') == 'false') {
                return redirect()->route('category.show', ['category' => $category->id])
                    ->with('success', 'Category updated successfully.');
            }

            return redirect()->route('category.index')->with('success', 'Category updated successfully.');
        } catch (QueryException $e) {
            return redirect()->route('category.index')->with('error', 'An error occurred while updating the category.');
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(string $id)
    {
        try {
            $category = JobCategory::findOrFail($id);
            $category->delete();

            return redirect()->route('category.index')->with('success', 'Category archived successfully.');
        } catch (QueryException $e) {
            return redirect()->route('category.index')->with('error', 'An error occurred while archiving the category.');
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore(string $id)
    {
        try {
            $category = JobCategory::onlyTrashed()->findOrFail($id);
            $category->restore();

            return redirect()->route('category.index', ['archived' => 'true'])->with('success', 'Category restored successfully.');
        } catch (Exception $e) {
            return redirect()->route('category.index')->with('error', 'An error occurred while restoring the category.');
        }
    }
}
