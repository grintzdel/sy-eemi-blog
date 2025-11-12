<?php

declare(strict_types=1);

namespace App\Modules\Shared\Presentation\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AppController extends AbstractController
{
    protected function handleForm(
        Request       $request,
        FormInterface $form,
        callable      $onSuccess,
        string        $template,
        array         $templateData = [],
        array         $exceptionHandlers = []
    ): Response
    {
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                $result = $onSuccess($form->getData());

                if($result instanceof Response)
                {
                    return $result;
                }
            } catch(\Throwable $e)
            {
                $response = $this->handleException($e, $exceptionHandlers, $template, $form, $templateData);
                if($response !== null)
                {
                    return $response;
                }
            }
        }

        return $this->render($template, array_merge($templateData, ['form' => $form]));
    }

    private function handleException(
        \Throwable     $e,
        array          $exceptionHandlers = [],
        ?string        $template = null,
        ?FormInterface $form = null,
        array          $templateData = []
    ): ?Response
    {
        foreach($exceptionHandlers as $exceptionClass => $handler)
        {
            if($e instanceof $exceptionClass)
            {
                if(is_callable($handler))
                {
                    return $handler($e);
                }

                if(is_array($handler))
                {
                    $message = $handler['message'] ?? $e->getMessage();
                    $type = $handler['type'] ?? 'error';
                    $redirect = $handler['redirect'] ?? null;

                    $this->addFlash($type, $message);

                    if($redirect)
                    {
                        return $this->redirectToRoute($redirect);
                    }

                    return null;
                }
            }
        }

        $this->addFlash('error', 'Erreur : ' . $e->getMessage());

        return null;
    }

    protected function executeWithExceptionHandling(
        callable $operation,
        array    $exceptionHandlers = [],
        ?string  $defaultRedirect = null
    ): Response
    {
        try
        {
            $result = $operation();

            if($result instanceof Response)
            {
                return $result;
            }

            if($defaultRedirect)
            {
                return $this->redirectToRoute($defaultRedirect);
            }

            throw new \LogicException('Operation must return a Response or defaultRedirect must be provided');
        } catch(\Throwable $e)
        {
            $response = $this->handleException($e, $exceptionHandlers);
            if($response !== null)
            {
                return $response;
            }

            if($defaultRedirect)
            {
                return $this->redirectToRoute($defaultRedirect);
            }

            throw $e;
        }
    }

    protected function successRedirect(string $message, string $route, array $parameters = []): Response
    {
        $this->addFlash('success', $message);
        return $this->redirectToRoute($route, $parameters);
    }

    protected function errorRedirect(string $message, string $route, array $parameters = []): Response
    {
        $this->addFlash('error', $message);
        return $this->redirectToRoute($route, $parameters);
    }
}
