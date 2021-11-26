<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;
use Tymon\JWTAuth\Exceptions\{TokenExpiredException, TokenInvalidException};

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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException && $request->expectsJson()) {
            return response()->json(['error' => 'Not found uri'], $exception->getStatusCode());
        }

        if ($exception instanceof MethodNotAllowedException && $request->expectsJson()) {
            return response()->json(['error' => 'Method Not Allowed'], $exception->getStatusCode());
        }

        if ($exception instanceof TokenExpiredException) {
            return response()->json(['token_expired'], $exception->getStatusCode());
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json(['token_invalid'], $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }
}
