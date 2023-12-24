<?php

namespace Utils;

class ApiException extends \Exception {
    public const isProduction               = true;//是否生产环境
    public const ERROR_SHOW_USER            = '系统错误';
    public const ERROR_MSG_STORE_ID_INVALID = '门店id为空';

    public static function getErrorDesc(\Throwable $e): string {
        return self::isProduction ? self::ERROR_SHOW_USER : sprintf('%s,line：%s, trace:%s', $e->getMessage(), $e->getLine(), _j($e->getTrace()));
    }

    public static function getSuccessDesc(): string {
        return '操作成功';
    }
}


