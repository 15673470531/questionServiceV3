<?php

namespace Constants;

class RedisKeyConstant {
    public static function makeKeyQuestionCommit($userid){
        return sprintf('{%s:%s}','questionCommit',$userid);
    }

    public static function makeKeyEventStreamRepeat(string $key) {
        return sprintf('{eventStream:%s}', $key);
    }

    public static function makeKeyQueueSaveQuestion(): string {
        return 'queue_key_save_question';
    }

    public static function makeKeyUserCommitQuestion(string $userid, string $randomId): string {
        return sprintf('userCommitQuestion:%s:%s', $userid, $randomId);
    }
}
