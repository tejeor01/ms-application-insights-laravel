<?php
namespace Marchie\MSApplicationInsightsLaravel\Handlers;

use Throwable;
use Marchie\MSApplicationInsightsLaravel\MSApplicationInsightsHelpers;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class MSApplicationInsightsExceptionHandler extends ExceptionHandler
{

    /**
     * @var MSApplicationInsightsHelpers
     */
    private $msApplicationInsightsHelpers;


    public function __construct(Container $container)
    {
        $this->msApplicationInsightsHelpers = app(MSApplicationInsightsHelpers::class);
        parent::__construct($container);
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        foreach ($this->dontReport as $type)
        {
            if ($e instanceof $type)
            {
                return parent::report($e);
            }
        }

        $this->msApplicationInsightsHelpers->trackException($e);

        return parent::report($e);
    }
}