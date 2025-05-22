<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function (string $message, mixed $data = null, int $statusCode = 200) {
            $response = [
                'status' => 0,
                'message' => $message,
                'data' => $data ?? [],
            ];

            if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
                $request = request();

                if (!$request->has('search')) {
                    $paginator = $data->resource;
                    $response['pagination'] = [
                        'total' => $paginator->total(),
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                        'per_page' => $paginator->perPage(),
                        'next_page_url' => $paginator->nextPageUrl(),
                        'prev_page_url' => $paginator->previousPageUrl(),
                    ];
                }
            }

            return response()->json($response, $statusCode);
        });

        Response::macro('failed', function (string $message, mixed $data = null, int $statusCode = 400) {
            return response()->json([
                'status' => 1,
                'message' => $message,
                'data' => $data ?? [],
            ], $statusCode);
        });
    }
}
