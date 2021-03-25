<?php

namespace App\Exceptions;
use Throwable;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
        
         //ERRO 403
        if ($exception instanceof UnauthorizedException)  {
            return response()->view('pages.error.403');
        }
        //ERRO 403
        if ($exception instanceof HttpException)  {
            return response()->view('pages.error.403');
        }
        // ERRO 404
          if ($exception instanceof ModelNotFoundException) {
            return response()->view('pages.error.404', [], 404);
        }
         // ERRO 404
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('pages.error.404', [], 404);
        }

        // ERRO 500
        
        if ($exception instanceof \ErrorException) {
            return response()->view('pages.error.500', [], 500);
        } else {
            return parent::render($request, $exception);
        }
        


        if ($exception instanceof TokenMismatchException) {
            return response()->view('pages.auth.login', [], 419);
        } else {
            return parent::render($request, $exception);
        }
        

        return parent::render($request, $exception);
    }
}