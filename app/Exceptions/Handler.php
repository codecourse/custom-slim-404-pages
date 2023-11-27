<?php

namespace App\Exceptions;

use Throwable;
use ReflectionClass;
use Slim\Views\Twig;
use Slim\Handlers\ErrorHandler;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Handler
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $responseFactory;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $view;

    /**
     * Undocumented function
     *
     * @param ResponseFactoryInterface $responseFactory
     * @return void
     */
    public function __construct(ResponseFactoryInterface $responseFactory, Twig $view)
    {
        $this->responseFactory = $responseFactory;
        $this->view = $view;
    }

    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @return void
     */
    public function __invoke(ServerRequestInterface $request, Throwable $exception)
    {
        if (method_exists($this, $handler = 'handle' . (new ReflectionClass($exception))->getShortName())) {
            return $this->{$handler}($request);
        }

        throw $exception;
    }

    /**
     * Undocumented function
     *
     * @param ServerRequestInterface $request
     * @return void
     */
    public function handleHttpNotFoundException(ServerRequestInterface $request)
    {
        return $this->view->render(
            $this->responseFactory->createResponse(),
            'errors/404.twig'
        )
            ->withStatus(404);
    }
}
