<?php

namespace System\Requests;

class Curl {

    private $curl;
    private $url;

    public function __construct($url, $timeout = 5, $http = null) {
        $this->curl = curl_init();
        $this->url = $url;

        curl_setopt_array($this->curl, array(
            CURLOPT_HEADER => true,
            CURLOPT_HTTP_VERSION => (($http ?? "1.1") == "1.1") ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_1_0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_URL => $this->url,
        ));
    }

    public function __destruct() {
        curl_close($this->curl);
    }

    public function Get($split = false, $data = null) {
		curl_setopt($this->curl, CURLOPT_HTTPGET, true);

		if ($data) {
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		}
					
		return $this->Execute($split);
	}

    public function Post($split = false, $data = null) {
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, (is_array($data)) ? http_build_query( $data, '', '&' ): $data);

		return $this->Execute($split);
	}

    private function Execute($split) {
        $result = curl_exec($this->curl);

        if ($split) {
            $header_len = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);

            $result = array(
                "header" => substr($result, 0, $header_len),
                "body" => substr($result, $header_len)
            );
        }

        return $result;
    }
}