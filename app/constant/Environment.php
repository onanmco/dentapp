<?php

namespace app\constant;

class Environment
{
    const PASSWORD_MIN_LENGTH = 8;
    const PASSWORD_MAX_LENGTH = 20;
    const PASSWORD_CHARSET = '/^[\@\!\^\+\%\/\(\)\=\?\_\*\-\<\>\#\$\½\{\[\]\}\\\|\w]*$/';
    const NAME_MIN_LENGTH = 1;
    const NAME_MAX_LENGTH = 64;
    const NAME_CHARSET = '/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]*$/';
    const EMAIL_MAX_LENGTH = 64;
    const PHONE_REGEX = '/^0\d{10}$/';
    const TCKN_REGEX = '/^\d{11}$/';
    const ID_REGEX = '/^\d+$/';
    const COMPOSITE_SEARCH_CHARSET = '/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ\d]+$/';
    const COMPOSITE_SEARCH_MIN_LENGTH = 3;
}