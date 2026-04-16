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
        if (!is_file($localPath) || !is_readable($localPath)) {
            return null;
        }

        $defaultEndpoint = defined('MEDIA_UPLOAD_ENDPOINT') ? MEDIA_UPLOAD_ENDPOINT : '';
        $base = trim(Setting::get('media_upload_endpoint', $defaultEndpoint));
        $configured = $endpoint !== '' ? $endpoint : $base;
        
        if ($configured === '') {
            return null;
        }

        $mime = $mimeType !== '' ? $mimeType : 'application/octet-stream';
        $name = $originalName !== '' ? $originalName : basename($localPath);

        $ch = curl_init();
        if ($ch === false) {
            return null;
        }

        $file = new \CURLFile($localPath, $mime, $name);

        curl_setopt_array($ch, [
            CURLOPT_URL => $configured,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['file' => $file],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'TuquinhaUploader/1.0',
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false || $httpCode < 200 || $httpCode >= 300) {
            return null;
        }

        $data = json_decode($response, true);
        if (!is_array($data) || ($data['status'] ?? '') !== 'success') {
            return null;
        }

        $mediaUrl = $data['url'] ?? null;
        return is_string($mediaUrl) && $mediaUrl !== '' ? $mediaUrl : null;
    }
}
