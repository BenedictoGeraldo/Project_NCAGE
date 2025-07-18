<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClearNCAGEData
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah URL bukan pendaftaran-ncage
        if (!str_starts_with($request->path(), 'pendaftaran-ncage')) {
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
