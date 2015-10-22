<?php
namespace ESG\Panther\Service;

class EventService extends BaseService
{
    public function fireEvent($obj, $get_email_html = false)
    {
        $result = $this->fireEventOnce($obj);
    }

    private function fireEventOnce($dto)
    {
        if ($acts = $this->getDao('Event')->getEventAction(['e.event_id' => $dto->getEventId()])) {
            foreach ($acts as $act_obj) {
                $classname = ucfirst(strtolower($act_obj->getAction()))."Servcie";

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
