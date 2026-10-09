<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AuditLog::query()
            ->with('user:id,name,email')
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->string('action')))
            ->when($request->filled('type'), fn ($q) => $q->where('auditable_type', 'like', '%'.$request->string('type').'%'))
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->integer('user_id')))
            ->latest('id')
            ->paginate(settings('crm.per_page', 20));

        return view('admin.audit.index', ['logs' => $logs]);
    }
}
