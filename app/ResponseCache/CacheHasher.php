<?php

namespace App\ResponseCache;

use Illuminate\Http\Request;
use Spatie\ResponseCache\Hasher\RequestHasher;

class CacheHasher implements RequestHasher
{
    /**
     * @param  Request $request
     *
     * @return string
     */
    public function getHashFor(Request $request): string
    {
        $query = [];
        
        if ($request->has('keyword') && $request->input('keyword') !== null) {
            $query[] = [
                'keyword' => $request->input('keyword')
            ];
        }

        $query = count($query) === 0 ? '' : http_build_query($query);

        return 'response-cache-'.md5(
            "{$request->getHost()}-{$request->path()}-{$request->getMethod()}/{$query}"
        );
    }
}
