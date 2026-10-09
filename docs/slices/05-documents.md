# Slice 05: Documents

**Tables:** document_types, candidate_documents, document_versions, document_requests, document_access_logs

## Build
- Disk `private` → storage/app/private/candidates/{id}/, UUID filenames. Never under public/.
- Upload validation: size ≤ 10MB, extension allow-list, finfo MIME check, reject double extensions (passport.pdf.php).
- Download/preview only via `DocumentController@download` → policy → document_access_logs row.
- Versioning on re-upload. Verify/reject by the assigned consultant or admin, with reason + notify student. Reversing a rejection is admin-only.
- Document requests from a consultant appear in the student portal with due date.
- Command `documents:check-expiry` daily 09:00: notify at 30 and 7 days, mark expired, create task.

## Done when
Direct storage URL 404/403; double-extension rejected; a consultant cannot verify a document for a student who is not theirs; Student A cannot download Student B's document.
