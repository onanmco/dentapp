<?php

namespace app\utility;

class TcDogrulama
{
    private $errors = [];
    private $tckn = 0;
    private $isim = '';
    private $soyisim = '';
    private $dogum_yili = 0;

    public function __construct($tckn, $isim, $soyisim, $dogum_yili)
    {
        $this->tckn = $tckn;
        $this->isim = $isim;
        $this->soyisim = $soyisim;
        $this->dogum_yili = $dogum_yili;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        if (!preg_match('/^\d{11}$/', $this->tckn)) {
            $this->errors[] = 'Lütfen geçerli bir tckn girin.';
        }
        if (!preg_match('/^[\w ]+$/', $this->isim)) {
            $this->errors[] = 'İsim alanı yalnızca harf ve boşluklardan oluşabilir.';
        }
        if (!preg_match('/^[\w ]+$/', $this->soyisim)) {
            $this->errors[] = 'Soyisim alanı yalnızca harf ve boşluklardan oluşabilir.';
        }
        if (!preg_match('/^\d{4}$/', $this->dogum_yili)) {
            $this->errors[] = 'Lütfen geçerli bir doğum yılı girin.';
        }        
        if (!empty($this->errors)) {
            return false;
        }        
        $this->isim = UtilityFunctions::turkish_lowercase(trim($this->isim, "\s"));
        $this->soyisim = UtilityFunctions::turkish_lowercase(trim($this->soyisim, "\s"));

        $request = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                        <TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
                        <TCKimlikNo>' . $this->tckn .'</TCKimlikNo>
                        <Ad>' . $this->isim . '</Ad>
                        <Soyad>' . $this->soyisim . '</Soyad>
                        <DogumYili>' . $this->dogum_yili . '</DogumYili>
                        </TCKimlikNoDogrula>
                    </soap:Body>
                    </soap:Envelope>';
        $header = [
                'POST /Service/KPSPublic.asmx HTTP/1.1',
                'Host: tckimlik.nvi.gov.tr',
                'Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request),
                'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"'
            ];
        $options = [
                CURLOPT_URL => 'https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => $header
            ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = strip_tags(curl_exec($ch));
        if ($response !== 'false' && $response !== 'true') {
            $errors[] = 'SOAP request hatası oluştu.';
            return false;
        }
        if ($response === 'false') {
            $this->errors = [];
            return false;
        }
        if ($response === 'true') {
            return true;
        }
    }
}