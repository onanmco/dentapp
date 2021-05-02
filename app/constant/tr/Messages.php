<?php

namespace app\constant\tr;

class Messages
{
    public static function MIN_LEN($value, $field)
    {
        return "$field alanı en az $value karakter içermelidir.";
    }
    
    public static function MAX_LEN($value, $field)
    {
        return "$field alanı en fazla $value karakter içermelidir.";
    }

    public static function REGEXP_UPPERCASE($count, $field)
    {
        return "$field alanı en az $count büyük harf içermelidir.";
    }

    public static function REGEXP_DIGIT($count, $field)
    {
        return "$field alanı en az $count rakam içermelidir.";
    }

    public static function PASSWORD_REGEXP($field)
    {
        return "$field alanı yalnızca harf, sayı, özel karakterler, tire ve nokta içerebilir.";
    }

    public static function NAME_REGEXP($field)
    {
        return "$field alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire içerebilir.";
    }

    public static function PHONE_REGEXP($field)
    {
        return "$field alanı 0NNNNNNNNN formatında olmalıdır.";
    }

    public static function TCKN_REGEXP($field)
    {
        return "$field alanı 11 haneli geçerli bir TCKN olmalıdır.";
    }

    public static function ID_REGEXP($field)
    {
        return "$field alanı 0'dan büyük bir tamsayı olmalıdır.";
    }

    public static function COMPOSITE_SEARCH_REGEXP($field)
    {
        return "$field alanı yalnızca harf, rakam, boşluk, nokta, kesme işareti ve tire içerebilir.";
    }

    public static function MISSING_FIELD($field)
    {
        return "$field alanı eksik.";
    }

    public static function SERVICE_UNAVAILABLE($service_name)
    {
        return "$service_name servisine ulaşılamıyor. Lütfen daha sonra tekrar deneyin.";
    }

    public static function MAIL_SENT_SUCCESSFULLY()
    {
        return "Mailiniz karşı tarafa iletildi.";
    }

    public static function JSON_DECODING_ERROR()
    {
        return "JSON gövdesi parse edilemedi.";
    }

    public static function APPLICATION_JSON()
    {
        return "Veri türü 'application/json' olmalıdır.";
    }

    public static function POST_METHOD()
    {
        return "İstek yöntemi 'POST' olmalıdır.";
    }

    public static function DB_READ_ERROR()
    {
        return "Şuanda isteğinizi gerçekleştiremiyoruz. Lütfen destek ekibimizle görüşün.";
    }

    public static function DB_WRITE_ERROR()
    {
        return "Şuanda isteğinizi gerçekleştiremiyoruz. Lütfen destek ekibimizle görüşün.";
    }

    public static function ACCOUNT_CANNOT_FOUND()
    {
        return "Girmiş olduğunuz e-mail adresi kayıtlarımızda bulunamadı.";
    }

    public static function WRONG_PASSWORD()
    {
        return "Hatalı şifre girdiniz. Lütfen tekrar deneyin.";
    }

    public static function EMAIL_ALREADY_USED()
    {
        return "E-mail adresi başka bir personel tarafından kullanılıyor.";
    }

    public static function REGISTER_SUCCESSFUL()
    {
        return "Kayıt yapıldı. Anasayfaya yönlendiriliyorsunuz.";
    }

    public static function LOGIN_SUCCESSFUL()
    {
        return "Giriş yapıldı. En son ziyaret ettiğiniz sayfaya yönlendiriliyorsunuz.";
    }

    public static function LOGOUT_SUCCESSFUL()
    {
        return "Çıkış yapıldı. Anasayfaya yönlendiriliyorsunuz.";
    }

    public static function UNAUTHORIZED_ACCESS()
    {
        return "Bu sayfayı görüntülemek için yetkili değilsiniz. Devam etmek için yetkili hesabınızla giriş yapın.";
    }

    public static function UNKNOWN_ERROR()
    {
        return "Şuanda isteğinizi gerçekleştiremiyoruz. Lütfen destek ekibimizle görüşün.";
    }

    public static function PATIENT_WITH_TCKN_ALREADY_EXISTS()
    {
        return "Bu TCKN ile zaten kayıtlı bir hasta mevcut.";
    }

    public static function PATIENT_REGISTERED_SUCCESSFULLY()
    {
        return "Hasta başarıyla kaydedildi.";    
    }

    public static function SEARCH_COMPLETED()
    {
        return "Arama başarıyla tamamlandı.";
    }

    public static function USER_ID_CANNOT_FOUND($user_id)
    {
        return "Sistemimize $user_id id'siyle kayıtlı bir personel bulunamadı.";
    }

    public static function PATIENT_ID_CANNOT_FOUND($user_id)
    {
        return "Sistemimize $user_id id'siyle kayıtlı bir hasta bulunamadı.";
    }

    public static function INVALID_START_END_HOURS()
    {
        return "Başlangıç saati bitiş saatinden önce olmalıdır.";
    }

    public static function APPOINTMENT_TO_PAST()
    {
        return "Geçmiş tarihe randevu veremezsiniz.";
    }

    public static function OVERLAPPING_APPOINTMENT()
    {
        return "Girmiş olduğunuz aralıkta başka bir randevu var.";
    }

    public static function SHOULD_BE_STRING($field)
    {
        return "$field sadece String(yazı) olabilir.";
    }

    public static function SHOULD_BE_BOOLEAN($field)
    {
        return "$field sadece Boolean(true/false) olabilir.";
    }

    public static function APPOINTMENT_SAVED()
    {
        return "Randevu başarıyla kaydedildi.";
    }

    public static function USER_COULD_NOT_BE_SAVED($email)
    {
        return "Personel veritabanına kaydedilirken bir sorun oluştu. E-mail: $email";
    }

    public static function FAILED_TO_GET_USER_BY_TOKEN($email, $token)
    {
        return "Personel, token ile veritabanından çekilirken bir sorun oluştu.\nE-mail: $email\nAPI Token: $token";
    }

    public static function USER_GROUP_ID_NOT_EXIST()
    {
        return "Seçmiş olduğunuz meslek türü sistemimizde yer almamaktadır.";
    }

    public static function FAILED_TO_INSERT_API_TOKEN_HASH($email, $id)
    {
        return "Personel satırına api_token_hash eklenirken hata oluştu. Rollback atılıyor.\nE-mail: $email\nId: $id";
    }

    public static function INVALID_EMAIL($field)
    {
        return "$field alanı için lütfen geçerli bir e-mail adresi girin.";
    }

    public static function CANNOT_BE_EMPTY($field)
    {
        return "$field alanı boş bırakılamaz.";
    }

    public static function PAGE_NOT_FOUND()
    {
        return "Sayfa bulunamadı.";
    }

    public static function INVALID_TCKN_USER_SIGNUP($field)
    {
        return "$field alanı yalnızca rakam içerebilir.";
    }

    public static function PLEASE_SELECT_PATIENT()
    {
        return "Lütfen geçerli bir hasta seçimi yapın.";
    }

    public static function SHOULD_BE_INTEGER($field)
    {
        return "$field alanı yalnızca tamsayı değerler içerebilir.";
    }

    public static function SHOULD_BE_NUMERIC($field)
    {
        return "$field alanı yalnızca sayısal değerler içerebilir.";
    }

    public static function SHOULD_BE_IN_BETWEEN($min, $max, $field)
    {
        return "$field alanı $min ile $max arasında değerler içerebilir.";
    }

    public static function INVALID_IP_ADDR($field)
    {
        return "$field alanı için geçerli bir ip adresi girin.";
    }

    public static function MISSING_FIELDS($fields = [])
    {
        $str = implode(", ", $fields) . (count($fields) > 1 ? ' alanları' : ' alanı');
        return "$str eksik.";
    }

    public static function INVALID_FIELD($field)
    {
        return "$field alanı geçersiz.";
    }

    public static function INVALID_UNIX_TIMESTAMP($field)
    {
        return "$field alanı UNIX timestamp formatında olmalıdır.";
    }

    public static function APPOINTMENTS_FETCHED()
    {
        return "Randevular başarıyla çekildi.";
    }
}