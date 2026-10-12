<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\User;

class CandidateDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('documents.view');
    }

    public function view(User $user, CandidateDocument $document): bool
    {
        if (! $user->hasPermission('documents.view')) {
            return false;
        }

        return $this->canAccessCandidate($user, $document->candidate);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('documents.upload');
    }

    public function upload(User $user, Candidate $candidate): bool
    {
        if (! $user->hasPermission('documents.upload')) {
            return false;
        }

        return $this->canAccessCandidate($user, $candidate);
    }

    public function download(User $user, CandidateDocument $document): bool
    {
        if (! $user->hasPermission('documents.view')) {
            return false;
        }

        return $this->canAccessCandidate($user, $document->candidate);
    }

    public function verify(User $user, CandidateDocument $document): bool
    {
        return $user->hasPermission('documents.verify');
    }

    public function reject(User $user, CandidateDocument $document): bool
    {
        return $user->hasPermission('documents.verify');
    }

    public function reverseRejection(User $user, CandidateDocument $document): bool
    {
        return $user->hasPermission('documents.reverse');
    }

    public function request(User $user): bool
    {
        return $user->hasPermission('documents.request');
    }

    public function delete(User $user, CandidateDocument $document): bool
    {
        return $user->isAdmin();
    }

    private function canAccessCandidate(User $user, ?Candidate $candidate): bool
    {
        if (! $candidate) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isConsultant()) {
            return $candidate->assigned_to === $user->id;
        }

        if ($user->isStudent()) {
            return $candidate->user_id === $user->id;
        }

        return false;
    }
}
