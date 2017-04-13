<?php

//https://mattstauffer.co/blog/bringing-whoops-back-to-laravel-5

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler;
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
        } else {
            return response()->view('errors.500', [], 500);
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