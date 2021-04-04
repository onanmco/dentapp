<?php

namespace app\constant\tr;

use config\Config;

class Constants
{
    const CONTACT_EMAIL_TITLE = 'Yeni İletişim Formu';
    const DEFAULT_RESPONSE_MESSAGE = '<HATA: EKSIK RESPONSE MESAJI>';
    const RECIPIENT = 'alıcı email';
    const SENDER = 'gönderici email';
    const CC = 'cc email';
    const BCC = 'bcc email';
    const MAILGUN_SERVICE_NAME = 'Uzak e-mail servisi';
    const ALL_RIGHTS_RESERVED = 'Tüm Hakları Saklıdır.';

    const HOME_PAGE_TITLE = 'Anasayfa';
    const HOME_PAGE_WELCOME = 'Hoş Geldiniz, ';
    const HOME_PAGE_LOGOUT_TEXT =
    '<p>Çıkış yapmak için <a href="user/logout">buraya</a> tıklayın.</p>';
    const HOME_PAGE_VISITOR_TEXT = 
    '<h3>Hoşgeldiniz !</h3>
    <p class="mb-0">İşlemlerinize devam edebilmek için lütfen giriş yapın.</p>
    <p class="mb-0">Giriş ekranına gitmek için <a href="/user/login">buraya</a> tıklayın.</p>
    <p class="mb-0">Hesabınızla ilgili sorunlar için <a href="mailto:' . Config::CLIENT_EMAIL .'">' . Config::CLIENT_EMAIL . '</a> adresinden bizlere ulaşabilirsiniz.</p>';


    const SIGNUP_PAGE_TITLE = 'Personel Kayıt';
    const SIGNUP_PAGE_HELP_DRAWER_TITLE = 'Personel Giriş Ekranı Yardım Penceresi';
    const SIGNUP_PAGE_HELP_DRAWER_CONTENT = 
    '<p>Personel girişleri <strong>e-mail adresi</strong> üzerinden yapılır. Bu yüzden lütfen geçerli bir e-mail adresi girdiğinizden emin olun.</p>
    <p>E-mail adresinde <strong>Türkçe karakterler kullanmayın.</strong></p>
    <p>Hesap güvenliği açısından, <strong>şifre en az 8, en fazla 20 karakterden oluşmalıdır.</strong> Ayrıca şifre <strong>en az 1 adet büyük harf ve en az bir adet rakam içermelidir.</strong></p>
    <p>Şifrede <strong>Türkçe karakterler kullanmayın.</strong></p>
    <p>Yabancı personel ihtimaline karşılık, TCKN alanı boş bırakılabilir. Ancak eğer giriş yapılırsa sadece 11 haneli geçerli bir TCKN girdiğinizden emin olun.</p>
    <p>Maaş alanı boş bırakılabilir. Ancak eğer giriş yapılırsa <strong>ondalıklı değerler için virgül yerine nokta kullanın.</strong></p>
    <p>Meslek alanı kaydetmiş olduğunuz personelin yetkilerini otomatik olarak belirleyecektir. Bu yüzden <strong>kaydedeceğiniz personelin doğru meslek grubunu seçtiğinizden emin olun.</strong></p>';
    const SIGNUP_PAGE_SUBTITLE = 'Personel Kayıt Ekranı';
    const SIGNUP_PAGE_SAVE = 'Personel Kaydet';

    const LOGIN_PAGE_TITLE = 'Personel Giriş';
    const LOGIN_PAGE_SUBTITLE = 'İşlemlerinize devam etmek için lütfen giriş yapın.';
    const LOGIN_PAGE_LOGIN = 'Giriş Yap';
}