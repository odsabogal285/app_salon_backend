<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable  $exception)
    {
        $type = $request->header('Content-Type');
        if ($type == "application/json") {
            $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400;
            return response()->json([
                'response' => 'error',
                'data' => null,
                'error' => $exception->getMessage()
            ], $status);
        }
        return parent::render($request, $exception);
    }
}
