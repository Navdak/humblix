<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Enquiry extends Model
{
    protected $fillable = [
        'reference_number',
        'source',
        'name',
        'company_name',
        'country',
        'state_city',
        'phone',
        'email',
        'location',
        'project_location',
        'building_type',
        'service_needed',
        'type_of_work',
        'urgency',
        'preferred_contact',
        'message',
        'attachment_path',
        'uploaded_files',
        'assigned_to',
        'status',
        'notes',
        'reviewed_at',
        'contacted_at',
    ];

    public const STATUSES = ['new','reviewed','assigned','contacted','quoted','closed','in_progress','completed'];
    public const TYPE_OF_WORK_OPTIONS = ['HVAC','Solar','Electrical','Maintenance','Vendor','Home Appliance'];
    public const BUILDING_TYPE_OPTIONS = ['Home','Office','Factory','Estate','Hospital','Hotel','School','Warehouse','Retail Store','Data Centre','Government','Religious Centre','Other'];
    public const PREFERRED_CONTACT_OPTIONS = ['Email','Phone','WhatsApp'];

    protected function casts(): array
    {
        return [
            'uploaded_files' => 'array',
            'reviewed_at' => 'datetime',
            'contacted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Enquiry $enquiry): void {
            if (! $enquiry->reference_number) {
                $enquiry->reference_number = self::generateReferenceNumber();
            }

            if (! $enquiry->status) {
                $enquiry->status = 'new';
            }
        });
    }

    public static function generateReferenceNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "HMX-{$date}-";
        $sequence = self::where('reference_number', 'like', "{$prefix}%")->count() + 1;

        do {
            $reference = $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
            $sequence++;
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getPhoneWhatsappAttribute(): string
    {
        return $this->phone;
    }

    public function getBriefDescriptionAttribute(): ?string
    {
        return $this->message;
    }

    public function getDisplayLocationAttribute(): ?string
    {
        return $this->project_location ?: $this->location;
    }

    public function getDisplayTypeOfWorkAttribute(): string
    {
        return $this->type_of_work ?: $this->service_needed;
    }

    public function markWorkflowTimestamps(string $status): void
    {
        if (in_array($status, ['reviewed','assigned','quoted'], true) && ! $this->reviewed_at) {
            $this->reviewed_at = Carbon::now();
        }

        if (in_array($status, ['contacted','quoted','closed'], true) && ! $this->contacted_at) {
            $this->contacted_at = Carbon::now();
        }
    }
}
