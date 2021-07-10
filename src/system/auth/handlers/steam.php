<?php

namespace System\Auth\Handlers;

use Exception;
use System\Members\Member;
use System\Requests\Incoming;
use System\Requests\Curl;

class Steam extends \System\Auth\Handler {
    private $apiKey;

    public static function AuthURL() {
        return "https://steamcommunity.com/openid/login";
    }

    public function Init() {
        $this->apiKey = \System\Config::GetDynamic("steam-api-key");
    }

    public function Process() {
        $this->Init();
        
        if ($this->apiKey == "") {
            throw new Exception("No Valid Steam API Key");
        }

        if (!Incoming::I()->openid_ns) {
            \System\Views\Output::I()->Redirect(self::AuthURL(), array(
                "openid.ns" => "http://specs.openid.net/auth/2.0",
                "openid.mode" => "checkid_setup",
                "openid.return_to" => URL."login?_loginHandler=" . $this->id,
                "openid.realm" => URL,
                "openid.identity" => "http://specs.openid.net/auth/2.0/identifier_select",
                "openid.claimed_id" => "http://specs.openid.net/auth/2.0/identifier_select"
            ));
            exit;
        }

        if ($this->Validate()) {
            $member = Member::GetMemberFromToken($this->id, $this->token);
            return ($member->id) ? $member : $this->HandleNewAccountLink($this->id, $this->token);
        }
    }

    protected function Validate() {
        $params = array(
            "openid.ns" => "http://specs.openid.net/auth/2.0",
            "openid.assoc_handle" => Incoming::I()->openid_assoc_handle,
            "openid.signed" => Incoming::I()->openid_signed,
            "openid.sig" => Incoming::I()->openid_sig,
            "openid.mode" => "check_authentication"
        );

        foreach (explode(",", $params["openid.signed"]) as $item) {
            $parameterName = "openid_" . str_replace(".", "_", $item);

            if (!Incoming::I()->$parameterName) continue;
            $params["openid." . $item] = Incoming::I()->$parameterName;
        }

        preg_match('#^https://steamcommunity.com/openid/id/(\d{17,25})#', Incoming::I()->openid_claimed_id, $matches);
        $steamid = is_numeric($matches[1]) ? $matches[1] : 0;

        if (!preg_match('/is_valid\s*:\s*true/i', (new Curl("https://steamcommunity.com/openid/login"))->Post(false, $params)) && ($steamid !== 0)) throw new Exception("Steam Account Validation Failed");

        $this->token = $steamid;

        return true;
    }

    private function HandleNewAccountLink($handler, $token)
    {
        $info = $this->GetInformation(false);

        $member = parent::CreateAccount($info["personaname"], null);
        
        if ($member->id) {
            if (!\System\DB::I()->Insert("login_account_links (handler_id, account_id, token) VALUES (:handler, :account, :token)", array(
                ":handler" => $this->id,
                ":account" => $member->id,
                ":token" => $this->token
            ))) {
                \System\DB::I()->Delete("login_accounts", "id = :id", array (":id" => $member->id));
                throw new Exception("Failed To Link Accounts");
            }
        }

        return $member;
    }

    protected function GetInformation($cache = true) {
        $response = json_decode((new Curl("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $this->apiKey . "&steamids=" . $this->token))->Get(true)["body"], true);

        if (!$response) throw new Exception("Steam Returned Bad Json");

		return $response['response']['players'][0];
    }

    public function Name() {
        return $this->GetInformation()["personaname"];
    }

    public function URL() {
        return "https://steamcommunity.com/profiles/$this->token/";
    }
}