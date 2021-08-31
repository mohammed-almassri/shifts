<?php

namespace App\Exceptions;

use Core\App\Traits\SendsApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use SendsApiResponse;

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
        'current_password',
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
        if (request()->expectsJson()) {
            $this->renderable(function (AccessDeniedHttpException $exception) {
                return $this->errorResponse([], __('lang.no_permission'), 403, $exception);
            });

            $this->renderable(function (MethodNotAllowedHttpException $exception) {
                return $this->errorResponse([], 'The specified method for the request is invalid', 405, $exception);
            });

            $this->renderable(function (NotFoundHttpException $exception) {
                return $this->errorResponse([], 'The specified URL or resource cannot be found', 404, $exception);
            });

            $this->renderable(function (HttpException $exception) {
                return $this->errorResponse([], $exception->getMessage(), $exception->getStatusCode(), $exception);
            });
            $this->renderable(function (Throwable $e) {
                return $this->errorResponse([], __('lang.unknown_error'), 500, $e);
            });
        }
    }
}
