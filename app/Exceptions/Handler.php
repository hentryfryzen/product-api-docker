<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable; // Don't forget to import the Throwable class

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e  // Updated type hint
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        // If the exception is a 404 (route not found or model not found)
        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            // You can customize the message as needed
            return response()->json([
                'statusCode' => 404,
                'message' => 'The requested resource was not found.'
            ], 404);
        }

        // Default exception rendering
        return parent::render($request, $e);
    }
}
