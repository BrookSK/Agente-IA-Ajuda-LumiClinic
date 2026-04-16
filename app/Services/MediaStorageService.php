<?php

namespace App\Services;

use App\Models\Setting;

class MediaStorageService
{
    /**
     * Envia um arquivo local para o servidor de mídia externo usando o endpoint padrão
     * configurado (MEDIA_UPLOAD_ENDPOINT / setting media_upload_endpoint).
     */
    public static function uploadFile(string $localPath, string $originalName, string $mimeType = ''): ?string
    {
        return self::uploadFileToEndpoint($localPath, $originalName, $mimeType, '');
    }

    /**
     * Envia um arquivo local para um endpoint específico ou, se não informado,
     * para o endpoint padrão configurado.
     */
    public static function uploadFileToEndpoint(string $localPath, string $originalName, string $mimeType = '', string $endpoint = ''): ?string
    {
        error_log("MEDIA DEBUG - Starting upload: $originalName from $localPath");
        
        if (!is_file($localPath) || !is_readable($localPath)) {
            error_log("MEDIA DEBUG - File not found or not readable: $localPath");
            return null;
        }

        $defaultEndpoint = defined('MEDIA_UPLOAD_ENDPOINT') ? MEDIA_UPLOAD_ENDPOINT : '';
        $base = trim(Setting::get('media_upload_endpoint', $defaultEndpoint));
        $configured = $endpoint !== '' ? $endpoint : $base;
        
        error_log("MEDIA DEBUG - Using endpoint: $configured");
        
        if ($configured === '') {
            error_log("MEDIA DEBUG - No endpoint configured");
            return null;
        }

        // Garante que exista algum MIME simples
        $mime = $mimeType !== '' ? $mimeType : 'application/octet-stream';
        $name = $originalName !== '' ? $originalName : basename($localPath);

        $ch = curl_init();
        if ($ch === false) {
            error_log("MEDIA DEBUG - Failed to initialize cURL");
            return null;
        }

        $file = new \CURLFile($localPath, $mime, $name);
        $postFields = [
            'file' => $file,
        ];

        curl_setopt_array($ch, [
            CURLOPT_URL => $configured,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // Para desenvolvimento
            CURLOPT_USERAGENT => 'TuquinhaUploader/1.0',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],
        ]);

        error_log("MEDIA DEBUG - Executing cURL request to: $configured");
        $response = curl_exec($ch);
        
        if ($response === false) {
            $curlError = curl_error($ch);
            error_log("MEDIA DEBUG - cURL error: $curlError");
            curl_close($ch);
            return null;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        error_log("MEDIA DEBUG - HTTP response code: $httpCode");
        error_log("MEDIA DEBUG - Response: " . substr($response, 0, 500));

        if ($httpCode < 200 || $httpCode >= 300) {
            error_log("MEDIA DEBUG - HTTP error code: $httpCode");
            return null;
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            error_log("MEDIA DEBUG - Invalid JSON response");
            return null;
        }

        if (($data['status'] ?? '') !== 'success') {
            error_log("MEDIA DEBUG - Upload failed, status: " . ($data['status'] ?? 'unknown'));
            return null;
        }

        $mediaUrl = $data['url'] ?? null;
        $result = is_string($mediaUrl) && $mediaUrl !== '' ? $mediaUrl : null;
        error_log("MEDIA DEBUG - Final result: " . ($result ?? 'null'));
        
        return $result;
    }
}
