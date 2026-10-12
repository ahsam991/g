<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\DocumentAccessLog;
use App\Models\DocumentRequest;
use App\Models\DocumentVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    public function upload(
        Candidate $candidate,
        int $documentTypeId,
        UploadedFile $file,
        int $userId,
        ?string $expiryDate = null,
    ): CandidateDocument {
        $uuid = Str::uuid()->toString();
        $extension = $file->getClientOriginalExtension();
        $storagePath = "candidates/{$candidate->id}/{$uuid}.{$extension}";

        Storage::disk('private')->put($storagePath, file_get_contents($file->getRealPath()));

        $existing = CandidateDocument::where('candidate_id', $candidate->id)
            ->where('document_type_id', $documentTypeId)
            ->first();

        if ($existing) {
            DocumentVersion::create([
                'candidate_document_id' => $existing->id,
                'file_path' => $existing->file_path,
                'file_name' => $existing->file_name,
                'file_size' => $existing->file_size,
                'version' => $existing->version,
                'created_by' => $userId,
            ]);

            $existing->update([
                'uuid' => $uuid,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $storagePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => 'pending',
                'rejection_reason' => null,
                'expiry_date' => $expiryDate,
                'verified_by' => null,
                'verified_at' => null,
                'version' => $existing->version + 1,
            ]);

            $this->fulfillRequest($candidate->id, $documentTypeId);

            return $existing->fresh();
        }

        $doc = CandidateDocument::create([
            'uuid' => $uuid,
            'candidate_id' => $candidate->id,
            'document_type_id' => $documentTypeId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $storagePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'pending',
            'expiry_date' => $expiryDate,
            'created_by' => $userId,
            'version' => 1,
        ]);

        $this->fulfillRequest($candidate->id, $documentTypeId);

        return $doc;
    }

    public function verify(CandidateDocument $document, int $userId): void
    {
        $document->update([
            'status' => 'verified',
            'verified_by' => $userId,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function reject(CandidateDocument $document, string $reason, int $userId): void
    {
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => $userId,
            'verified_at' => now(),
        ]);
    }

    public function reverseRejection(CandidateDocument $document): void
    {
        $document->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    public function logAccess(CandidateDocument $document, int $userId, string $action, ?string $ip, ?string $userAgent): void
    {
        DocumentAccessLog::create([
            'candidate_document_id' => $document->id,
            'user_id' => $userId,
            'action' => $action,
            'ip' => $ip,
            'user_agent' => Str::limit($userAgent, 255),
        ]);
    }

    private function fulfillRequest(int $candidateId, int $documentTypeId): void
    {
        DocumentRequest::where('candidate_id', $candidateId)
            ->where('document_type_id', $documentTypeId)
            ->where('status', 'pending')
            ->update(['status' => 'uploaded']);
    }
}
