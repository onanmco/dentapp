<?php

namespace app\constant;

abstract class Messages
{
    const BILINMEYEN_HATA = [
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
}