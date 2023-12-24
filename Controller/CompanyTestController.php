<?php

namespace Controller;

class CompanyTestController extends Controller {
    public function eventStream(){
        ob_end_flush();
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering:no');
        $counter = 0;
        while (true){
            if ($counter == 5){
                $this->sendEvent('[done]');
                break;
            }
            $counter++;
            $this->sendEvent($counter);
//            flush();
            sleep(5);
        }
    }

    function sendEvent($message, $event = 'message') {
        echo "event: $event\n";
        echo "data: $message\n\n";
        ob_flush();
        flush();
    }

}
