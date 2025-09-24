<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;

require_once base_path('huaweicloud-sdk/vendor/autoload.php');

use Obs\ObsClient;
use Exception;

class PostController extends Controller
{
    public function store(PostRequest $request)
    {
        $client = new ObsClient([
            'key'      => env('OBS_ACCESS_KEY'),
            'secret'   => env('OBS_SECRET_KEY'),
            'endpoint' => env('OBS_ENDPOINT_URL'),
        ]);

        $datos = $request->validated();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if (!$file->isValid()) {
                return response()->json(['error' => 'Archivo no válido.'], 400);
            }
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return response()->json(['error' => 'El archivo debe ser una imagen.'], 400);
            }
            $fileName = 'commu/posts/' . time() . '_' . $file->getClientOriginalName();

            try {
                $client->putObject([
                    'Bucket' => 'commu',
                    'Key'    => $fileName,
                    'Body'   => fopen($file->getPathname(), 'rb'),
                ]);

                $signedUrl = $client->createSignedUrl([
                    'Bucket' => 'commu',
                    'Key'    => $fileName,
                    'Method' => 'GET',
                    'Expires' => 3600 * 2,
                    'ResponseContentDisposition' => 'inline',
                ]);

                $datos['foto_url'] = $signedUrl['SignedUrl'] ?? null;
            } catch (Exception $e) {
                return response()->json(['error' => 'Error al subir la imagen: ' . $e->getMessage()], 500);
            }
        }

        $post = Post::create([
            ...$datos,
            'expira_en' => now()->addHours(2),
        ]);

        return response()->json(['data' => $post, 'message' => 'Post creado correctamente']);
    }
}
