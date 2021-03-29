<?php

namespace app\constant;

abstract class Messages
{
    const UNKNOWN_ERROR = [
        'title' => 'Bilinmeyen Hata',
        'message' => 'Şuanda isteğinizi gerçekleştiremiyoruz. Lütfen destek ekibimizle görüşün.',
        'code' => 500
    ];
    const INVALID_EMAIL = [
        'title' => 'Geçersiz E-mail Adresi',
        'message' => 'E-mail adresi başka bir personel tarafından kullanılıyor.',
        'code' => 400
    ];
    const REGISTER_SUCCESSFUL = [
        'title' => 'Kayıt Başarılı',
        'message' => 'Anasayfaya yönlendiriliyorsunuz.',
        'code' => 200
    ];
    const WELCOME = [
        'title' => 'Hoş Geldiniz.',
        'message' => 'Sizi en son ziyaret ettiğiniz sayfaya yönlendiriyoruz.',
        'code' => 200
    ];
    const LOGOUT_SUCCESSFUL = [
        'title' => 'Çıkış Yapıldı.',
        'message' => 'Bizi ziyaret ettiğiniz için teşekkürler. Sizi anasayfaya yönlendiriyoruz.',
        'code' => 200
    ];
    const ACCESS_DENIED = [
        'title' => 'Erişim Kısıtlandı',
        'message' => 'Bu sayfayı görüntülemek için yetkili değilsiniz. Devam etmek için yetkili hesabınızla giriş yapın.',
        'code' => 401
    ];
    const ACCOUNT_CANNOT_FOUND = [
        'title' => 'Giriş Başarısız',
        'message' => 'Girmiş olduğunuz e-mail adresi kayıtlarımızda bulunamadı.',
        'code' => 400
    ];
    const WRONG_PASSWORD = [
        'title' => 'Giriş Başarısız',
        'message' => 'Hatalı şifre girdiniz. Lütfen tekrar deneyin.',
        'code' => 400
    ];
    const POST_METHOD = [
        'title' => 'Yanlış İstek',
        'message' => 'İstek sadece POST methoduyla yapılabilir.',
        'code' => 400
    ];
    const APPLICATION_JSON = [
        'title' => 'Yanlış İstek',
        'message' => 'İsteğin Content-Type\'ı application/json olmalıdır.',
        'code' => 400
    ];
    const JSON_DECODING_ERROR = [
        'title' => 'JSON Decoding Hatası',
        'message' => 'İsteğin body\'si parse edilirken bir hata oluştu.',
        'code' => 400
    ];
    const DB_WRITE_ERROR = [
        'title' => 'Veritabanı Hatası',
        'message' => 'İstek veritabanına kaydedilirken bir hata oluştu.',
        'code' => 500
    ];
    const DB_READ_ERROR = [
        'title' => 'Veritabanı Hatası',
        'message' => 'İstek veritabanından okunurken bir hata oluştu.',
        'code' => 500
    ];
    const MAIL_SENT_SUCCESSFULLY = [
        'title' => 'Başarılı',
        'message' => 'Mesajınız tarafımıza iletilmiştir.',
        'code' => 200
    ];
}