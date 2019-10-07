<?php

namespace App\Exceptions;

use Exception;
use Route;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientErrorResponseException;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
             if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }

        if ($exception instanceof ModelNotFoundException) {
                 if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }

        if ($exception instanceof MethodNotAllowedHttpException){
                if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }
        if($exception instanceof ClientException){
                if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }

        if($exception instanceof RequestException){
                if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }

        if($exception instanceof Exception){
                if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }

        if($exception instanceof ClientErrorResponseException){
                if ($request->is('api/*')) {
                    return response()->json(['response'=>['code'=>$exception->getCode() ,'exception'=>$exception->getMessage()]]);
                }
            return Route::respondWithRoute('fallback');
        }

        return parent::render($request, $exception);
    }
}
