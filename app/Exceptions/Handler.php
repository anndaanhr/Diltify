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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Don't handle validation exceptions - let Laravel handle them normally
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return parent::render($request, $exception);
        }

        // Don't handle 404s with custom page - let Laravel handle them
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $statusCode = 404;
            $message = 'The page you are looking for could not be found.';
        } else {
            // Log the exception for debugging
            if (app()->bound('log')) {
                app('log')->error($exception->getMessage(), [
                    'exception' => $exception,
                    'url' => $request->url(),
                ]);
            }

            $statusCode = method_exists($exception, 'getStatusCode') 
                ? $exception->getStatusCode() 
                : 500;

            $message = $exception->getMessage() ?: 'An error occurred. Please try again.';
        }

        return response()->view('error', [
            'statusCode' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}
