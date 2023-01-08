<?php

namespace CaioMarcatti12\Request;

class RequestHttp
{
    private string $_url = '';
    private array $_headers = [];
    private string $_method = '';
    private mixed $_body = null;
    private mixed $_response = null;

    public function url(string $url): RequestHttp{
        $this->_url = $url;

        return $this;
    }

    public function header(string $key, mixed $value): RequestHttp{
        $key= trim($key);
        $value= trim($value);

        $this->_headers[$key] = $value;

        return $this;
    }

    public function bodyJson(array $json): RequestHttp{
        $this->_body = json_encode($json);

        $this->header('content-length', strlen($this->_body));

        return $this;
    }

    public function get(): RequestHttp{
        $this->_method = 'GET';
        return $this->request();
    }

    public function put(): RequestHttp{
        $this->_method = 'PUT';
        return $this->request();
    }

    public function post(): RequestHttp{
        $this->_method = 'POST';
        return $this->request();
    }

    private function request(): RequestHttp {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->_method);

        if($this->_body !== null){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_body);
        }
        if($this->_headers !== []){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->transformSimpleHeader());
        }

        $this->_response = curl_exec($curl);

        curl_close($curl);

        return $this;
    }

    private function transformSimpleHeader(): array{
        $headers = [];
        foreach($this->_headers as $key => $value){
            $headers[] = "{$key}: {$value}";
        }

        return $headers;
    }

    public function responseJsonDecode(): array {
        return json_decode($this->_response, true);
    }
}