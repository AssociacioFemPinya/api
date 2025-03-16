<?php

namespace App\State;

use App\Models\Attendance;
use App\Models\Event;
use ApiPlatform\Laravel\Eloquent\State\PersistProcessor;
use ApiPlatform\Laravel\Eloquent\State\RemoveProcessor;
use App\Dto\MobileEventDto;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Support\Facades\Log;

class MobileEventsStateProcessor extends AbstractStateProcessor
{
    public function __construct(PersistProcessor $persistProcessor, RemoveProcessor $removeProcessor)
    {
        parent::__construct($persistProcessor, $removeProcessor);
    }

    protected function preProcessProcessor(mixed $data, array $uriVariables = []): mixed
    {
        if (!$data instanceof MobileEventDto) {
            throw new BadRequestHttpException('Invalid DTO');
        }
        if (is_null($this->casteller)) {
            abort(404, 'Events not found');
        }

        $id = $uriVariables['id'] ?? null;
        if (is_null($id)) {
            abort(404, 'Event ID is required');
        }
        
        $attendance = Attendance::where('event_id', $id)
            ->where('casteller_id', $this->casteller->getId())
            ->first();

        $this->updateAttendanceStatus($attendance, $data->status);
        $this->updateAttendanceOptions($attendance, $data->tags, $id);
        $attendance->companions = $data->companions;

        $attendance->save();
        return $data;
    }

    protected function postProcessProcessor(mixed $data, array $uriVariables = []): mixed
    {
        // // Transform back into a DTO after saving
        // if ($data instanceof MobileEventDto) {
        //     return MobileEventDto::fromModel($data);
        // }

        $data->id = $uriVariables['id'];
        return $data;
    }

    private function updateAttendanceStatus(Attendance $attendance, string $status): void
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
            if ($tag["isEnabled"] && in_array($tag["id"], $eventTags)) {
                $options[] = $tag["id"];
            }
        }
        $attendance->options = json_encode($options);
    }
}
