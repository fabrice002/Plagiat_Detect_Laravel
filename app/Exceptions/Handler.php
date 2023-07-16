<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\ErrorHandler\Error\FatalError;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        // Augmenter la limite de temps d'exécution
        ini_set('max_execution_time', 0);

        if ($exception instanceof FatalError && $exception->getMessage() === 'Maximum execution time of 180 seconds exceeded') {
            // Gérer l'erreur de dépassement du temps d'exécution ici
            // ...
            return response()->json(['message' => 'Le temps d\'exécution maximum a été dépassé.'], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
