<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewDocumentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Document $document
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dokumen Baru Masuk: ' . $this->document->original_filename,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $adminUrl = route('filament.admin.resources.documents.edit', $this->document);

        return new Content(
            markdown: 'emails.admin.new-document',
            with: [
                'document' => $this->document,
                'adminUrl' => $adminUrl,
            ],
        );
    }
}
