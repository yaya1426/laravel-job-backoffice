<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'company-owner') {
            return $this->companyOwnerDashboard();
        }

        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        // Basic Analytics for Admin
        $analytics = [
            'active_users' => User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count(),
            'active_jobs' => JobVacancy::whereNull('deleted_at')->count(),
            'total_applications' => JobApplication::count(),
        ];

        // Most Applied Jobs
        $mostAppliedJobs = JobVacancy::withCount('jobApplications as applications_count')
            ->whereNull('deleted_at')
            ->orderByDesc('applications_count')
            ->take(5)
            ->get();

        // Jobs with Conversion Rates
        $jobsWithApplications = JobVacancy::withCount('jobApplications as applications_count')
            ->whereNull('deleted_at')
            ->having('applications_count', '>', 0)
            ->get()
            ->map(function ($job) {
                $job->conversion_rate = ($job->applications_count / ($job->view_count ?: 1)) * 100;
                return $job;
            })
            ->sortByDesc('conversion_rate')
            ->take(5)
            ->values();

        return view('dashboard.index', compact(
            'analytics',
            'mostAppliedJobs',
            'jobsWithApplications'
        ));
    }

    private function companyOwnerDashboard()
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'No company associated with your account.');
        }

        // Company-specific Analytics
        $analytics = [
            'active_jobs' => JobVacancy::where('companyId', $company->id)
                ->whereNull('deleted_at')
                ->count(),
            'total_applications' => JobApplication::whereIn('jobId', function($query) use ($company) {
                $query->select('id')
                    ->from('job_vacancies')
                    ->where('companyId', $company->id);
            })->count(),
            'pending_applications' => JobApplication::whereIn('jobId', function($query) use ($company) {
                $query->select('id')
                    ->from('job_vacancies')
                    ->where('companyId', $company->id);
            })
            ->where('status', 'pending')
            ->count(),
        ];

        // Company's Most Applied Jobs
        $mostAppliedJobs = JobVacancy::withCount('jobApplications as applications_count')
            ->where('companyId', $company->id)
            ->whereNull('deleted_at')
            ->orderByDesc('applications_count')
            ->take(5)
            ->get();

        // Company's Jobs with Conversion Rates
        $jobsWithApplications = JobVacancy::withCount('jobApplications as applications_count')
            ->where('companyId', $company->id)
            ->whereNull('deleted_at')
            ->having('applications_count', '>', 0)
            ->get()
            ->map(function ($job) {
                $job->conversion_rate = ($job->applications_count / ($job->view_count ?: 1)) * 100;
                return $job;
            })
            ->sortByDesc('conversion_rate')
            ->take(5)
            ->values();

        return view('dashboard.index', compact(
            'analytics',
            'mostAppliedJobs',
            'jobsWithApplications'
        ));
    }
}
