<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ClearNCAGEData
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('URL Diakses:', [
            'path' => $request->path(),
            'full_url' => $request->fullUrl(),
            'route_name' => optional($request->route())->getName(),
        ]);

        // Cek apakah URL bukan pendaftaran-ncage
        if (!str_starts_with($request->path(), 'pendaftaran-ncage') && !str_starts_with($request->path(), 'notifications')) {
            $userId = auth()->check() ? auth()->user()->id : null;
            if ($userId) {
                $folderPath = public_path("uploads/temp/{$userId}");
                if (is_dir($folderPath)) {
                    $files = glob($folderPath . '/*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                        }
                    }
                    rmdir($folderPath);
                }
            }

            Session::forget('form_ncage');
            Session::forget('form_ncage_progress');
            Session::forget('is_revision');
        }

        return $next($request);
    }
}
