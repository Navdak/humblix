<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Branch;
use App\Models\Enquiry;
use App\Models\EquipmentItem;
use App\Models\JobOpening;
use App\Models\Project;
use App\Models\Review;
use App\Models\TeamMember;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $period = in_array((int) $request->integer('period'), [7, 14, 30], true) ? (int) $request->integer('period') : 14;
        $trendStart = now()->startOfDay()->subDays($period - 1);
        $trendDates = collect(range(0, $period - 1))->map(fn (int $offset) => $trendStart->copy()->addDays($offset));

        $totalReviews = $this->countRecords(Review::class);
        $approvedReviews = $this->countRecords(Review::class, fn ($query) => $query->where('is_approved', true));

        return view('admin.dashboard', [
            'stats' => [
                'totalEnquiries' => $this->countRecords(Enquiry::class),
                'newEnquiries' => $this->countRecords(Enquiry::class, fn ($query) => $query->where('status', 'new')),
                'projects' => $this->countRecords(Project::class),
                'branches' => $this->countRecords(Branch::class),
                'jobs' => $this->countRecords(JobOpening::class),
                'equipmentItems' => $this->countRecords(EquipmentItem::class),
                'videos' => $this->countRecords(Video::class),
                'reviews' => $totalReviews,
                'articles' => $this->countRecords(Article::class),
                'teamMembers' => $this->countRecords(TeamMember::class),
            ],
            'recentEnquiries' => $this->latestRecords(Enquiry::class, 5),
            'recentProjects' => $this->latestRecords(Project::class, 5, 'updated_at'),
            'recentVideos' => $this->latestRecords(Video::class, 5, 'updated_at'),
            'recentEquipment' => $this->latestRecords(EquipmentItem::class, 5, 'updated_at'),
            'openJobs' => $this->latestRecords(JobOpening::class, 5, 'updated_at', fn ($query) => $query->where('status', 'open')),
            'pendingReviews' => $this->latestRecords(Review::class, 5, 'updated_at', fn ($query) => $query->where('is_approved', false)),
            'reviewSummary' => [
                'average' => round((float) ($this->tableAvailable(Review::class) ? Review::avg('rating') : 0), 1),
                'approved' => $approvedReviews,
                'pending' => max(0, $totalReviews - $approvedReviews),
            ],
            'chartData' => [
                'enquiryTrend' => $this->enquiryTrend($trendDates, $trendStart),
                'enquiryTypes' => $this->groupCounts(Enquiry::class, 'type_of_work', 'service_needed'),
                'projects' => $this->groupCounts(Project::class, 'service_division', 'sector'),
                'videos' => $this->groupCounts(Video::class, 'category', 'status'),
                'videoStatuses' => $this->groupCounts(Video::class, 'status'),
                'equipment' => $this->groupCounts(EquipmentItem::class, 'category'),
                'catalogue' => $this->catalogueCounts(),
                'reviews' => [
                    'labels' => collect(['Approved', 'Pending']),
                    'values' => collect([$approvedReviews, max(0, $totalReviews - $approvedReviews)]),
                ],
            ],
            'operationalAlerts' => [
                ['label' => 'New enquiries awaiting review', 'count' => $this->countRecords(Enquiry::class, fn ($query) => $query->where('status', 'new')), 'route' => 'admin.enquiries.index', 'module' => 'enquiries', 'tone' => 'warn'],
                ['label' => 'Draft videos', 'count' => $this->countRecords(Video::class, fn ($query) => $query->where('status', 'draft')), 'route' => 'admin.videos.index', 'module' => 'videos', 'tone' => 'warn'],
                ['label' => 'Draft articles', 'count' => $this->countRecords(Article::class, fn ($query) => $query->where('status', 'draft')), 'route' => 'admin.articles.index', 'module' => 'articles', 'tone' => 'warn'],
                ['label' => 'Open jobs', 'count' => $this->countRecords(JobOpening::class, fn ($query) => $query->where('status', 'open')), 'route' => 'admin.jobs.index', 'module' => 'jobs', 'tone' => 'good'],
                ['label' => 'Equipment pending publish', 'count' => $this->countRecords(EquipmentItem::class, fn ($query) => $query->where('is_published', false)), 'route' => 'admin.equipment.index', 'module' => 'equipment', 'tone' => 'warn'],
                ['label' => 'Branches inactive/unpublished', 'count' => $this->countRecords(Branch::class, fn ($query) => $query->where(function ($query) { $query->where('status', '!=', 'active')->orWhere('is_published', false); })), 'route' => 'admin.branches.index', 'module' => 'branches', 'tone' => 'warn'],
            ],
            'systemStatus' => [
                'environment' => app()->environment(),
                'debug' => (bool) config('app.debug'),
                'storageLinked' => is_link(public_path('storage')) || is_dir(public_path('storage')),
                'updatedAt' => now(),
            ],
            'period' => $period,
        ]);
    }

    private function tableAvailable(string $modelClass): bool
    {
        /** @var Model $model */
        $model = new $modelClass;

        return Schema::hasTable($model->getTable());
    }

    private function countRecords(string $modelClass, ?callable $callback = null): int
    {
        if (! $this->tableAvailable($modelClass)) {
            return 0;
        }

        $query = $modelClass::query();
        $callback?->call($this, $query);

        return (int) $query->count();
    }

    private function latestRecords(string $modelClass, int $limit = 5, string $column = 'created_at', ?callable $callback = null): Collection
    {
        if (! $this->tableAvailable($modelClass)) {
            return collect();
        }

        $query = $modelClass::query();
        $callback?->call($this, $query);

        return $query->latest($column)->limit($limit)->get();
    }

    private function enquiryTrend(Collection $trendDates, Carbon $trendStart): array
    {
        if (! $this->tableAvailable(Enquiry::class)) {
            return ['labels' => $trendDates->map(fn (Carbon $date) => $date->format('M j'))->values(), 'values' => $trendDates->map(fn () => 0)->values()];
        }

        $enquiriesByDay = $trendDates->mapWithKeys(fn (Carbon $date) => [
            $date->format('Y-m-d') => Enquiry::whereDate('created_at', $date->toDateString())->where('created_at', '>=', $trendStart)->count(),
        ]);

        return [
            'labels' => $trendDates->map(fn (Carbon $date) => $date->format('M j'))->values(),
            'values' => $trendDates->map(fn (Carbon $date) => (int) ($enquiriesByDay[$date->format('Y-m-d')] ?? 0))->values(),
        ];
    }

    private function groupCounts(string $modelClass, string $column, ?string $fallbackColumn = null): array
    {
        if (! $this->tableAvailable($modelClass)) {
            return ['labels' => collect(), 'values' => collect()];
        }

        $rows = $modelClass::query()
            ->selectRaw("{$column} as label, COUNT(*) as aggregate")
            ->groupBy($column)
            ->orderByDesc('aggregate')
            ->limit(8)
            ->get();

        if (($rows->sum('aggregate') === 0 || $rows->filter(fn ($row) => filled($row->label))->isEmpty()) && $fallbackColumn) {
            $rows = $modelClass::query()
                ->selectRaw("{$fallbackColumn} as label, COUNT(*) as aggregate")
                ->groupBy($fallbackColumn)
                ->orderByDesc('aggregate')
                ->limit(8)
                ->get();
        }

        return [
            'labels' => collect($rows->map(fn ($row) => filled($row->label) ? ucwords(str_replace('_', ' ', (string) $row->label)) : 'Unspecified')->all())->values(),
            'values' => collect($rows->pluck('aggregate')->map(fn ($value) => (int) $value)->all())->values(),
        ];
    }

    private function catalogueCounts(): array
    {
        $videos = $this->groupCounts(Video::class, 'category', 'status');
        $equipment = $this->groupCounts(EquipmentItem::class, 'category');

        return [
            'labels' => $videos['labels']->map(fn ($label) => "Video: {$label}")
                ->merge($equipment['labels']->map(fn ($label) => "Equipment: {$label}"))
                ->values(),
            'values' => $videos['values']->merge($equipment['values'])->values(),
        ];
    }
}
