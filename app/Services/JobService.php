<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Jobs\ScrapeJob;

class JobService {

    public function createJob( array $urls, array $selectors ) {
        $jobId   = uniqid( '', true );
        $jobData = [
            'id'           => $jobId,
            'urls'         => $urls,
            'selectors'    => $selectors,
            'status'       => 'pending',
            'scraped_data' => null,
        ];

        Redis::set( "job:$jobId", json_encode( $jobData ) );
        dispatch( new ScrapeJob( $jobData ) );

        return $jobId;
    }

    public function getJob( string $jobId ) {
        $jobData = Redis::get( "job:$jobId" );

        return $jobData ? json_decode( $jobData, true, 512, JSON_THROW_ON_ERROR ) : null;
    }

    public function deleteJob( string $jobId ) {
        if ( ! Redis::exists( "job:$jobId" ) ) {
            return false;
        }

        Redis::del( "job:$jobId" );

        return true;
    }
}
