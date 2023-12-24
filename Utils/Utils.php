<?php

namespace Utils;

use Config\Database;
use Model\CodeModel;
use Model\UserModel;
use Service\UserService;

class Utils {
    private static $dbCond;

    public static function getDbCond() {
        if (is_null(self::$dbCond)) {
            $host         = Database::HOST;
            $user         = Database::USER;
            $pass         = Database::PASS;
            $dbname = Database::DBNAME;
//            self::$dbCond = mysqli_connect($host, $user, $pass);
            self::$dbCond = new \mysqli($host,$user, $pass, $dbname);
        }
        if (!self::$dbCond) {
            die('Could not connect: ' . mysqli_error(self::$dbCond));
        }
        return self::$dbCond;
    }

    public static function checkingMobile(string $mobile): bool {
        if ($mobile && strlen($mobile) == 11 && preg_match("/[0-9]{11}/", $mobile)) {
            return true;
        }
        return false;
    }

    public static function getNoRepeatPasswordString(): string {
        $password      = self::getRandomString();
        $userService = new UserService();
        while ($userService->isExistPassword($password)) {
            $password = self::getRandomString();
        }
        return $password;
    }

    public static function getRandomString($length = 5): string {
//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function getRandomNums($length = 6): string {
        $verifyCode = '';
        $str = '1234567890';
        //定义用于验证的数字和字母;
        $l = strlen($str); //得到字串的长度;
        //循环随机抽取四位前面定义的字母和数字;
        for ($i = 1; $i <= $length; $i++) {
            $num = rand(0, $l - 1);
            //每次随机抽取一位数字;从第一个字到该字串最大长度,
            $verifyCode .= $str[$num];
        }
        return $verifyCode;
    }

    public static function omitRedundantChinese(?string $name, int $maxLength): string {
        $name = $name ?: '';
        $length = mb_strlen($name);
        $suffix = '...';
        return $length > $maxLength ? sprintf('%s%s', mb_substr($name, 0, $maxLength), $suffix) : $name;
    }



    public static function hasChinese($str) {
        $pattern = '/[\x{4e00}-\x{9fa5}]+/u';
        return preg_match($pattern, $str);
    }
}
