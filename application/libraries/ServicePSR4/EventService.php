<?php
namespace ESG\Panther\Service;

class EventService extends BaseService
{
    public function fireEvent($obj)
    {
        $result = $this->fireEventOnce($obj);
    }

    private function fireEventOnce($dto, $get_email_html = FALSE)
    {
        if ($acts = $this->getDao('Event')->getEventAction(['e.event_id' => $dto->getEventId()])) {
            foreach ($acts as $act_obj) {
                $classname = ucfirst(strtolower($act_obj->getAction()));

                try {
                    $eventObj = new $classname();
                    $eventObj->run($dto);
                } catch (Exception $e) {
                    // should write log
                }
            }
        }

        return false;
    }
}
