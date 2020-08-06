<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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

    protected $message;
    protected $code;
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
        if ($request->wantsJson()){
//            dd($exception);
            $exception = $this->prepareException($exception);
//            dd($exception);
            $this->setCode($exception);
            $this->setMessage($exception);
            return response(['error'=>$this->message],$this->code);
        }
        return parent::render($request,$exception);
    }

    private function setCode(Throwable $exception){
        switch ($exception){
            case method_exists($exception,'getStatusCode'):
                $this->code = $exception->getStatusCode();
                break;
            case property_exists($exception,'status'):
                $this->code = $exception->status;
                break;
            case ($exception instanceof AuthenticationException):
            case ($exception instanceof OAuthServerException ):
                $this->code = 401;
                break;
            default:
                $this->code = 500;
        }
    }

    private function setMessage(Throwable $exception)
    {
        switch ($exception){
            case ($exception instanceof ValidationException):
                $this->message = $exception->errors();
                break;
            case $this->code==500:
                $this->message = "Server Internal Error";
                break;
            case ($exception instanceof NotFoundHttpException):
//                $ex = $exception->getPrevious();
                $this->message = "Not Found";
                break;
            case ($exception instanceof AuthenticationException):
                $this->message = "Access Denied";
                break;
//            case ($exception instanceof OAuthServerException ):
//                $this->message
            default:
                $this->message = $exception->getMessage();
        }
    }
}
