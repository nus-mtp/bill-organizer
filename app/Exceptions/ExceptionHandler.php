<?php

//https://mattstauffer.co/blog/bringing-whoops-back-to-laravel-5

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\Exception\FlattenException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;

class ExceptionHandler extends Handler
{

    // will always take in HTTP Exception, refer to base class
    protected function convertExceptionToResponse(Exception $e)
    {
        // use whoops handler if environment is development

        if (config('app.debug'))
        {
            $whoopsExceptionHandler = new Run();

            $whoopsExceptionHandler->pushHandler(new PrettyPageHandler());
            $whoopsExceptionHandler->pushHandler(new HighlightExceptionFunctionHandler());
            $this->returnJsonIfAjaxRequestReceived($whoopsExceptionHandler);

            $whoopsExceptionHandler->register();

            $e = FlattenException::create($e);

            return Response::create(
                $whoopsExceptionHandler->handleException($e),
                $e->getStatusCode(),
                $e->getHeaders()
            );
        }

        // use default handler if environment is production
        return parent::convertExceptionToResponse($e);
    }

    /**
     * NOTE: Overriding this because when error 500 occurs, the custom page is not shown
     * Prepare response containing exception render.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            return $this->toIlluminateResponse($this->renderHttpException($e), $e);
        } else if ($e instanceof TokenMismatchException) {
            return response()->view('errors.403', [], 403);
        } else {
            if (config('app.debug')) {
                return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e);
            } else {
                return response()->view('errors.500', [], 500);
            }
        }
    }

    /**
     * NOTE: Overriding this because we wanna show the error when app.debug is TRUE
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();

        view()->replaceNamespace('errors', [
            resource_path('views/errors'),
            __DIR__.'/views',
        ]);

        if (view()->exists("errors::{$status}") and !config('app.debug')) {
            return response()->view("errors::{$status}", ['exception' => $e], $status, $e->getHeaders());
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }

    /**
     * @param $whoopsExceptionHandler
     */
    protected function returnJsonIfAjaxRequestReceived($whoopsExceptionHandler)
    {
        if (Misc::isAjaxRequest()) {
            $jsonHandler = new JsonResponseHandler();
            // You can also tell JsonResponseHandler to give you a full stack trace:
            // $jsonHandler->addTraceToOutput(true);
            // You can also return a result compliant to the json:api spec
            // re: http://jsonapi.org/examples/#error-objects
            // tl;dr: error[] becomes errors[[]]
            $jsonHandler->setJsonApi(true);
            // And push it into the stack:
            $whoopsExceptionHandler->pushHandler($jsonHandler);
        }
    }
}