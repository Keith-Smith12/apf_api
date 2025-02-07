<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends Exception
{
    //

    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Recurso não encontrado'
            ], 404);
        });
    }
}
