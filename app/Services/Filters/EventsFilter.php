<?php

declare(strict_types=1);

namespace App\Services\Filters;

use App\Models\Colla;
use App\Enums\EventTypeEnum;
use App\Enums\FilterSearchTypesEnum;
use App\Enums\AttendanceStatusEnum;
use App\Models\Event;
use App\Models\Period;
use App\Models\Tag;
use App\Traits\DatatablesFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

class EventsFilter extends BaseFilter
{
    use DatatablesFilterTrait;
    private Builder $eloquentBuilder;

    protected $connection = 'mysql'; // TODO: can we get this from event model?

    public function __construct(Colla $colla)
    {
        parent::__construct($this->eloquentBuilder = Event::query()
            ->where('events.colla_id', $colla->getId())
            ->select('events.*'));
    }

    public function showCastellerAttendance(int $id_casteller): self
    {
        $this->eloquentBuilder
        ->leftJoin('attendance', function($join) {
            $join->on('events.id_event', '=', 'attendance.event_id')
                 ->where('attendance.casteller_id', 1);
        })->addSelect('attendance.status');

        return $this;
    }

    public function upcoming(): self
    {
        $this->eloquentBuilder
            ->where('start_date', '>', Carbon::now());

        return $this;
    }

    public function liveOrUpcoming(): self
    {
        $currentTime = Carbon::now();
        $this->eloquentBuilder
            ->whereRaw("DATE_ADD(start_date, INTERVAL duration MINUTE) > '{$currentTime}'");

        return $this;
    }

    public function past(): self
    {
        $this->eloquentBuilder
            ->where('start_date', '<', Carbon::now());

        return $this;
    }

    public function open(): self
    {
        $this->eloquentBuilder
            ->where('open_date', '<', Carbon::now())
            ->where('close_date', '>', Carbon::now());

        return $this;
    }

    public function visible(): self
    {
        $this->eloquentBuilder
            ->where('visibility', true);

        return $this;
    }

    public function withTags(array $includedTags, string $includedSearchType = FilterSearchTypesEnum::AND): self
    {
        if (in_array(Tag::TAG_ALL, $includedTags) || empty($includedTags)) {
            return $this;
        }

        $this->eloquentBuilder
            ->joinSub($this->getEventsIDByTags($includedTags, $includedSearchType), 'events_id_by_tags', function ($join) {
                $join->on('events.id_event', '=', 'events_id_by_tags.event_id');
            });

        return $this;
    }

    public function withoutTags(array $excludedTags, string $excludedSearchType = FilterSearchTypesEnum::AND): self
    {
        if (empty($excludedTags)) {
            return $this;
        }
        $this->eloquentBuilder
            ->leftJoinSub($this->getEventsIDByTags($excludedTags, $excludedSearchType), 'events_id_by_tags', function ($join) {
                $join->on('events.id_event', '=', 'events_id_by_tags.event_id');
            })->whereNull('events_id_by_tags.event_id');

        return $this;
    }

    public function withCastellerTags(array $includedTags, string $includedSearchType = FilterSearchTypesEnum::OR): self
    {

        $this->eloquentBuilder
            ->joinSub($this->getEventsIDByCastellerTags($includedTags, $includedSearchType), 'events_id_by_tags', function ($join) {
                $join->on('events.id_event', '=', 'events_id_by_tags.id_event');
            });

        return $this;
    }

    public function withPeriod(?Period $period = null): self
    {
        if (! $period) {
            return $this;
        }

        $this->eloquentBuilder
            ->where('start_date', '>', $period->getStartPeriod())
            ->where('start_date', '<', $period->getEndPeriod());

        return $this;
    }

    public function showAnswered(): self
    {
        // TODO: raise an exception if we haven't join attendance table using showCastellerAttendance?
        $this->eloquentBuilder->where(function ($query) {
            $query->where('attendance.status', AttendanceStatusEnum::YES)
              ->orWhere('attendance.status', AttendanceStatusEnum::NO);
        });
        return $this;
    }

    public function showUnknown(): self
    {
        // TODO: raise an exception if we haven't join attendance table using showCastellerAttendance?
        $this->eloquentBuilder->where(function ($query) {
            $query->whereNull('attendance.status')
              ->orWhere('attendance.status', AttendanceStatusEnum::UNKNOWN);
        });
        return $this;
    }

    public function withTypes(array $status): self
    {
        $status = array_filter($status, function ($value) {
            return in_array($value, AttendanceStatusEnum::getStatus());
        });

        $this->eloquentBuilder->where(function ($query) use ($status) {
            foreach ($status as $value) {
            $query->orWhere('events.type', $value);
            }
        });

        return $this;
    }

    public function beforeDate(string $date): self
    {
        $this->eloquentBuilder->where('start_date', '<', $date);

        return $this;
    }

    public function afterDate(string $date): self
    {
        $this->eloquentBuilder->where('start_date', '>', $date);

        return $this;
    }

    private function getEventsIDByTags(array $includedTags, string $includedSearchType): QueryBuilder
    {
        $event_tags = DB::connection($this->connection)->table('event_tag')
            ->leftJoin('tags', 'event_tag.tag_id', '=', 'tags.id_tag')
            ->whereIn('tags.id_tag', $includedTags)
            ->select(DB::raw('event_tag.event_id'))
            ->groupBy('event_tag.event_id');

        if ($includedSearchType === FilterSearchTypesEnum::AND) {
            $event_tags->having(DB::raw('count(tags.id_tag)'), '=', count($includedTags));
        }

        return $event_tags;
    }

    private function getEventsIDByCastellerTags(array $includedTags, string $includedSearchType): QueryBuilder
    {
        $event_tags = DB::connection($this->connection)->table('events')
            ->leftJoin('event_casteller_tag', 'events.id_event', '=', 'event_casteller_tag.event_id')
            ->leftJoin('tags', 'event_casteller_tag.tag_id', '=', 'tags.id_tag')
            ->whereNull('tags.id_tag')
            ->orWhereIn('tags.id_tag', $includedTags)
            ->select(DB::raw('events.id_event'))
            ->groupBy('events.id_event');

        if ($includedSearchType === FilterSearchTypesEnum::AND) {
            $event_tags->having(DB::raw('count(tags.id_tag)'), '=', count($includedTags));
        }

        return $event_tags;
    }
}
