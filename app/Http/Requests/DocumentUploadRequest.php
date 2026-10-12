<?php

namespace App\Http\Requests;

use App\Models\CandidateDocument;
use Illuminate\Foundation\Http\FormRequest;

class DocumentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        $candidate = $this->route('candidate');

        return $candidate && $this->user()->can('upload', [CandidateDocument::class, $candidate]);
    }

    public function rules(): array
    {
        return [
            'document_type_id' => ['required', 'exists:document_types,id'],
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => [
                'required', 'file', 'max:10240',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
            ],
            'expiry_date' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->hasFile('files')) {
                return;
            }

            foreach ($this->file('files') as $i => $file) {
                $name = $file->getClientOriginalName();

                // Block double extensions like passport.pdf.php
                $parts = explode('.', $name);
                if (count($parts) > 2) {
                    $dangerous = ['php', 'phtml', 'phar', 'sh', 'bash', 'exe', 'bat', 'cmd', 'js', 'html', 'svg'];
                    foreach ($parts as $part) {
                        if (in_array(strtolower($part), $dangerous)) {
                            $validator->errors()->add("files.{$i}", 'This file type is not allowed.');
                            break;
                        }
                    }
                }

                // Deep MIME check with finfo
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $realMime = $finfo->file($file->getRealPath());
                $laravelMime = $file->getMimeType();
                $allowed = [
                    'application/pdf',
                    'image/jpeg', 'image/png',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/octet-stream',
                    'application/x-empty',
                ];
                if (! in_array($realMime, $allowed) && ! in_array($laravelMime, $allowed)) {
                    $validator->errors()->add("files.{$i}", 'File content does not match an allowed type.');
                }
            }
        });
    }
}
