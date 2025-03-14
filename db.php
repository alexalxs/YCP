<?php
require_once __DIR__ . "/db/Exceptions/IOException.php";
require_once __DIR__ . "/db/Exceptions/JsonException.php";
require_once __DIR__ . "/db/Classes/IoHelper.php";
require_once __DIR__ . "/db/SleekDB.php";
require_once __DIR__ . "/db/Store.php";
require_once __DIR__ . "/db/QueryBuilder.php";
require_once __DIR__ . "/db/Query.php";
require_once __DIR__ . "/db/Cache.php";
require_once __DIR__ . "/cookies.php";

use SleekDB\Store;

function add_white_click($data, $reason)
{
    $dataDir = __DIR__ . "/logs";
    $wclicksStore = new Store("whiteclicks", $dataDir);

    $calledIp = $data['ip'];
    $country = $data['country'];
    $dt = new DateTime();
    $time = $dt->getTimestamp();
    $os = $data['os'];
    $isp = str_replace(',', ' ', $data['isp']);
    $user_agent = str_replace(',', ' ', $data['ua']);

    parse_str($_SERVER['QUERY_STRING'], $queryarr);

    $click = [
        "time" => $time,
        "ip" => $calledIp,
        "country" => $country,
        "os" => $os,
        "isp" => $isp,
        "ua" => $user_agent,
        "reason" => $reason,
        "subs" => $queryarr
    ];
    $wclicksStore->insert($click);
}

function add_black_click($subid, $data, $preland, $land)
{
    $dataDir = __DIR__ . "/logs";
    $bclicksStore = new Store("blackclicks", $dataDir);

    $calledIp = isset($data['ip']) ? $data['ip'] : '';
    $country = isset($data['country']) ? $data['country'] : '';
    $dt = new DateTime();
    $time = $dt->getTimestamp();
    $os = isset($data['os']) ? $data['os'] : '';
    $isp = isset($data['isp']) ? str_replace(',', ' ', $data['isp']) : '';
    $user_agent = isset($data['ua']) ? str_replace(',', ' ', $data['ua']) : '';
    $prelanding = empty($preland) ? 'unknown' : $preland;
    $landing = empty($land) ? 'unknown' : $land;

    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("add_black_click: subid=$subid, preland=$preland, land=$landing");
        error_log("add_black_click data: " . print_r($data, true));
    }

    parse_str($_SERVER['QUERY_STRING'], $queryarr);

    $click = [
        "subid" => $subid,
        "time" => $time,
        "ip" => $calledIp,
        "country" => $country,
        "os" => $os,
        "isp" => $isp,
        "ua" => $user_agent,
        "subs" => $queryarr,
        "preland" => $prelanding,
        "land" => $landing
    ];
    $bclicksStore->insert($click);
}

function add_lead($subid, $name, $phone, $status = 'Lead')
{
    $dataDir = __DIR__ . "/logs";
    $leadsStore = new Store("leads", $dataDir);

    $fbp = get_cookie('_fbp');
    $fbclid = get_cookie('fbclid');
    if ($fbclid === '') $fbclid = get_cookie('_fbc');

    if ($status == '') $status = 'Lead';

    $dt = new DateTime();
    $time = $dt->getTimestamp();

    $land = get_cookie('landing');
    if (empty($land)) $land = 'unknown';
    $preland = get_cookie('prelanding');
    if (empty($preland)) $preland = 'unknown';

    $lead = [
        "subid" => $subid,
        "time" => $time,
        "name" => $name,
        "phone" => $phone,
        "status" => $status,
        "fbp" => $fbp,
        "fbclid" => $fbclid,
        "preland" => $preland,
        "land" => $land
    ];
    return $leadsStore->insert($lead);
}

function update_lead($subid, $status, $payout)
{
    $dataDir = __DIR__ . "/logs";
    $leadsStore = new Store("leads", $dataDir);
    $lead = $leadsStore->findOneBy([["subid", "=", $subid]]);
    if ($lead === null) {
        $bclicksStore = new Store("blackclicks", $dataDir);
        $click = $bclicksStore->findOneBy([["subid", "=", $subid]]);
        if ($click === null) return false;
        $lead = add_lead($subid, '', '');
    }

    $lead["status"] = $status;
    $lead["payout"] = $payout;
    $leadsStore->update($lead);
    return true;
}

function email_exists_for_subid($subid)
{
    $dataDir = __DIR__ . "/logs";
    $leadsStore = new Store("leads", $dataDir);
    $lead = $leadsStore->findOneBy([["subid", "=", $subid]]);
    if ($lead === null) return false;
    if (array_key_exists("email", $lead)) return true;
    return false;
}

function add_email($subid, $email)
{
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("Iniciando add_email para subid=$subid e email=$email");
    }
    
    $dataDir = __DIR__ . "/logs";
    $leadsStore = new Store("leads", $dataDir);
    $lead = $leadsStore->findOneBy([["subid", "=", $subid]]);
    
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("Lead encontrado: " . ($lead === null ? "NULL" : json_encode($lead)));
    }
    
    if ($lead === null) {
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("ERRO: Lead não encontrado para subid=$subid. Email não será salvo.");
        }
        return false;
    }
    
    try {
        $lead["email"] = $email;
        $result = $leadsStore->update($lead);
        
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("Resultado da atualização: " . ($result ? "SUCESSO" : "FALHA"));
            
            // Verificar se o email foi realmente salvo
            $updatedLead = $leadsStore->findOneBy([["subid", "=", $subid]]);
            error_log("Lead após atualização: " . json_encode($updatedLead));
            error_log("Email foi salvo? " . (isset($updatedLead["email"]) && $updatedLead["email"] === $email ? "SIM" : "NÃO"));
        }
        
        return $result;
    } catch (Exception $e) {
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("ERRO ao salvar email: " . $e->getMessage());
        }
        return false;
    }
}

function add_lpctr($subid, $preland)
{
    $dataDir = __DIR__ . "/logs";
    $lpctrStore = new Store("lpctr", $dataDir);
    $dt = new DateTime();
    $time = $dt->getTimestamp();

    $lpctr = [
        "time" => $time,
        "subid" => $subid,
        "preland" => $preland
    ];
    $lpctrStore->insert($lpctr);
}

//проверяем, есть ли в файле лидов subid текущего пользователя
//если есть, и также есть такой же номер - значит ЭТО ДУБЛЬ!
//И нам не нужно слать его в ПП и не нужно показывать пиксель ФБ!!
function lead_is_duplicate($subid, $phone)
{
    $dataDir = __DIR__ . "/logs";
    $leadsStore = new Store("leads", $dataDir);
    if ($subid != '') {
        $lead = $leadsStore->findOneBy([["subid", "=", $subid]]);
        if ($lead === null) return false;
        header("YWBDuplicate: We have this sub!");
        $phoneexists = ($lead["phone"] === $phone);
        if ($phoneexists) {
            header("YWBDuplicate: We have this phone!");
            return true;
        } else {
            return false;
        }
    } else {
        //если куки c subid у нас почему-то нет, то проверяем по номеру телефона
        $lead = $leadsStore->findOneBy([["phone", "=", $phone]]);
        if ($lead === null) return false;
        return true;
    }
}

