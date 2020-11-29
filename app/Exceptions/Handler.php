<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Exception $ex, $request) {
            return $this->handleException($ex, $request);
        });
    }

    private function handleException(Exception $ex, $request)
    {
        if ($ex instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('The specified method for this request is invalid', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($ex instanceof NotFoundHttpException) {
            return $this->errorResponse('Requested resource not found', Response::HTTP_NOT_FOUND);
        }

        if ($ex instanceof AccessDeniedHttpException) {
            return $this->errorResponse('Forbidden', Response::HTTP_FORBIDDEN);
        }

        return $this->errorResponse($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function errorResponse($message, $code)
    {
        return response()->json([
            'status' => $code,
            'message' => $message
        ], $code);
    }
}
