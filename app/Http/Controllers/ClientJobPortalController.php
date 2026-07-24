<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\ClientJob;
use App\Models\JobMessage;
use App\Models\JobMessageAttachment;
use App\Support\SpamProtection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ClientJobPortalController extends Controller
{
    public function show(string $token)
    {
        $clientJob = $this->findPortalJob($token)
            ->load(['enquiry.assignedEngineer', 'assignedEngineer', 'messages' => fn ($query) => $query->where('visibility', 'client')->with('attachments')]);

        $this->markClientThreadRead($clientJob);

        return view('client-jobs.show', [
            'clientJob' => $clientJob,
            'messages' => $clientJob->messages,
        ]);
    }

    public function storeMessage(Request $request, string $token): RedirectResponse|JsonResponse
    {
        SpamProtection::validate($request);

        $clientJob = $this->findPortalJob($token);

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx'],
        ]);

        $attachments = $request->file('attachments', []);
        $this->ensureMessageHasContent($data['body'] ?? null, $attachments);

        $message = $clientJob->messages()->create([
            'sender_type' => 'client',
            'sender_name' => $clientJob->clientName(),
            'body' => filled($data['body'] ?? null) ? trim((string) $data['body']) : 'Attachment sent.',
            'visibility' => 'client',
            'read_by_client_at' => now(),
        ]);

        $this->storeAttachments($clientJob, $message, $attachments, 'client');

        $clientJob->forceFill([
            'last_client_message_at' => now(),
            'admin_unread_count' => $clientJob->admin_unread_count + 1,
        ])->save();

        $message->load('attachments');

        AdminNotification::createForClientJobMessage($clientJob->fresh(['enquiry']), $message);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->messagePayload($message, $clientJob),
                'notice' => 'Your message has been sent to HUMELIX.',
            ]);
        }

        return back()->with('success', 'Your message has been sent to HUMELIX.');
    }

    public function messages(Request $request, string $token): JsonResponse
    {
        $clientJob = $this->findPortalJob($token);
        $after = max(0, (int) $request->query('after', 0));

        $messages = $clientJob->messages()
            ->where('visibility', 'client')
            ->where('id', '>', $after)
            ->limit(50)
            ->get()
            ->load('attachments');

        $this->markClientThreadRead($clientJob);

        return response()->json([
            'messages' => $messages->map(fn (JobMessage $message) => $this->messagePayload($message, $clientJob))->values(),
        ]);
    }

    private function messagePayload(JobMessage $message, ClientJob $clientJob): array
    {
        return [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'sender_name' => $this->clientFacingSenderLabel($message),
            'body' => $message->body,
            'visibility' => $message->visibility,
            'created_at' => $message->created_at?->toIso8601String(),
            'human_time' => $message->created_at?->diffForHumans(),
            'attachments' => $message->attachments->map(fn (JobMessageAttachment $attachment) => [
                'id' => $attachment->id,
                'name' => $attachment->original_name,
                'mime_type' => $attachment->mime_type,
                'is_image' => $attachment->isImage(),
                'size' => $attachment->formattedSize(),
                'url' => route('client-jobs.attachments.show', [$clientJob->portal_token, $attachment], false),
            ])->values(),
        ];
    }

    private function clientFacingSenderLabel(JobMessage $message): string
    {
        if ($message->sender_type === 'admin') {
            return 'Humelix Project Team';
        }

        return $message->senderLabel();
    }

    public function attachment(string $token, JobMessageAttachment $attachment)
    {
        $clientJob = $this->findPortalJob($token);

        abort_unless((int) $attachment->client_job_id === (int) $clientJob->id, 404);
        abort_unless($attachment->message?->visibility === 'client', 404);
        abort_unless(Storage::disk($attachment->disk)->exists($attachment->file_path), 404);

        return Storage::disk($attachment->disk)->response($attachment->file_path, $attachment->original_name, [
            'Content-Type' => $attachment->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => ($attachment->isImage() ? 'inline' : 'attachment').'; filename="'.addslashes($attachment->original_name).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function findPortalJob(string $token): ClientJob
    {
        abort_if(strlen($token) < 32, 404);

        return ClientJob::query()
            ->where('portal_token', $token)
            ->where('portal_enabled', true)
            ->firstOrFail();
    }

    private function markClientThreadRead(ClientJob $clientJob): void
    {
        if ($clientJob->client_unread_count > 0) {
            $clientJob->forceFill(['client_unread_count' => 0])->save();
        }

        $clientJob->messages()
            ->where('visibility', 'client')
            ->whereNull('read_by_client_at')
            ->update(['read_by_client_at' => now()]);
    }

    private function ensureMessageHasContent(?string $body, array $attachments): void
    {
        if (filled($body) || count($attachments) > 0) {
            return;
        }

        throw ValidationException::withMessages([
            'body' => 'Write a message or attach at least one file.',
        ]);
    }

    private function storeAttachments(ClientJob $clientJob, JobMessage $message, array $attachments, string $senderType): void
    {
        foreach ($attachments as $file) {
            $path = $file->store('client-job-attachments/'.$clientJob->id, 'local');

            $message->attachments()->create([
                'client_job_id' => $clientJob->id,
                'sender_type' => $senderType,
                'disk' => 'local',
                'file_path' => $path,
                'original_name' => Str::limit($file->getClientOriginalName(), 180, ''),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize() ?: 0,
            ]);
        }
    }
}
