<?php

namespace app\constant;

abstract class Messages
{
    const UNKNOWN_ERROR = [
        'title' => 'Bilinmeyen Hata',
        'message' => 'Şuanda isteğinizi gerçekleştiremiyoruz. Lütfen destek ekibimizle görüşün.',
        'code' => 500
    ];
    const GECERSIZ_EMAIL = [
        'title' => 'Geçersiz E-mail Adresi',
        'message' => 'E-mail adresi başka bir personel tarafından kullanılıyor.',
        'code' => 400
    ];
    const KAYIT_BASARILI = [
        'title' => 'Kayıt Başarılı',
        'message' => 'Anasayfaya yönlendiriliyorsunuz.',
        'code' => 200
    ];
    const HOSGELDINIZ = [
        'title' => 'Hoş Geldiniz.',
        'message' => 'Sizi en son ziyaret ettiğiniz sayfaya yönlendiriyoruz.',
        'code' => 200
    ];
    const CIKIS_BASARILI = [
        'title' => 'Çıkış Yapıldı.',
        'message' => 'Bizi ziyaret ettiğiniz için teşekkürler. Sizi anasayfaya yönlendiriyoruz.',
        'code' => 200
    ];
    const ERISIM_KISITLANDI = [
        'title' => 'Erişim Kısıtlandı',
        'message' => 'Bu sayfayı görüntülemek için yetkili değilsiniz. Devam etmek için yetkili hesabınızla giriş yapın.',
        'code' => 401
    ];
    const HESAP_BULUNAMADI = [
        'title' => 'Giriş Başarısız',
        'message' => 'Girmiş olduğunuz e-mail adresi kayıtlarımızda bulunamadı.',
        'code' => 400
    ];
    const SIFRE_YANLIS = [
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
    ];
}