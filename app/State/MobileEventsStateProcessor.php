<?php

namespace App\State;

use App\Models\Attendance;
use App\Models\Event;
use ApiPlatform\Laravel\Eloquent\State\PersistProcessor;
use ApiPlatform\Laravel\Eloquent\State\RemoveProcessor;

class MobileEventsStateProcessor extends AbstractStateProcessor
{
    public function __construct(PersistProcessor $persistProcessor, RemoveProcessor $removeProcessor)
    {
        parent::__construct($persistProcessor, $removeProcessor);
    }

    protected function preProcessProcessor(mixed $data): mixed
    {
        // Log::info('preProcessProcessor');
        // if (!$data instanceof MobileEventDto) {
        //     throw new BadRequestHttpException('Invalid DTO');
        // }
        // if (is_null($this->casteller)) {
        //     Log::info('Casteller not found');
        //     abort(404, 'Events not found');
        // }

        // $attendance = Attendance::where('event_id', $data->id)
        //     ->where('casteller_id', $this->casteller->getId())
        //     ->first();

        //$this->updateAttendanceStatus($attendance, $data->status);
        //$this->updateAttendanceOptions($attendance, $data->tags, $data->id);
        //$attendance->companions = $data->companions;

        $attendance = Attendance::first();
        return $attendance;
    }

    private function updateAttendanceStatus(Attendance $attendance, ?int $status): void
    {
        $statusMap = [
            null => 'undefined',
            1 => 'accepted',
            2 => 'declined',
            3 => 'unknown',
        ];
        $attendance->status = array_search($status, $statusMap, true);
    }

    private function updateAttendanceOptions(Attendance $attendance, array $tags, int $eventId): void
    {
        $options = [];
        $eventTags = Event::find($eventId)->tags->pluck('id_tag')->toArray();
        foreach ($tags as $tag) {
            if ($tag->isEnabled && in_array($tag->id_tag, $eventTags)) {
                $options[] = $tag->id;
            }
        }
        $attendance->options = json_encode($options);
    }

    protected function postProcessProcessor(mixed $data): mixed
    {
        // // Transform back into a DTO after saving
        // if ($data instanceof MobileEventDto) {
        //     return MobileEventDto::fromModel($data);
        // }

        return $data;
    }
}
