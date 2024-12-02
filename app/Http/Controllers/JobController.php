<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Traits\ResponseHelper;
use App\Services\JobService;

class JobController extends Controller
{
    use ResponseHelper;

    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function create(CreateJobRequest $request)
    {
        $jobId = $this->jobService->createJob($request->input('urls'), $request->input('selectors'));

        return $this->successResponse(
            ['job_id' => $jobId],
            'Job created successfully.',
            201
        );
    }

    public function retrieve($id)
    {
        $job = $this->jobService->getJob($id);

        if (!$job) {
            return $this->errorResponse('Job not found.', null, 404);
        }

        return $this->successResponse($job, 'Job retrieved successfully.');
    }

    public function delete($id)
    {
        $deleted = $this->jobService->deleteJob($id);

        if (!$deleted) {
            return $this->errorResponse('Job not found.', null, 404);
        }

        return $this->successResponse(null, 'Job deleted successfully.');
    }
}
