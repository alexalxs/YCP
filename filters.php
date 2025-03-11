<?php
// Funções de filtro para o sistema

// Verificar filtro de sistema operacional
function check_os_filter($os, $allowed_os) {
    if (empty($allowed_os)) {
        return true;
    }
    
    return in_array($os, $allowed_os);
}

// Verificar filtro de país
function check_country_filter($country, $allowed_countries) {
    if (empty($allowed_countries)) {
        return true;
    }
    
    return in_array($country, $allowed_countries);
}

// Verificar filtro de URL
function check_url_filter($url, $should_contain, $mode) {
    if (empty($should_contain)) {
        return true;
    }
    
    $found = false;
    foreach ($should_contain as $needle) {
        if (strpos($url, $needle) !== false) {
            $found = true;
            break;
        }
    }
    
    return $mode === 'include' ? $found : !$found;
}

// Verificar filtro de tokens
function check_tokens_filter($token, $blocked_tokens) {
    if (empty($blocked_tokens)) {
        return true;
    }
    
    return !in_array($token, $blocked_tokens);
}

// Verificar filtro de user agent
function check_ua_filter($ua, $blocked_ua) {
    if (empty($blocked_ua)) {
        return true;
    }
    
    foreach ($blocked_ua as $needle) {
        if (stripos($ua, $needle) !== false) {
            return false;
        }
    }
    
    return true;
}

// Verificar filtro de ISP
function check_isp_filter($isp, $blocked_isp) {
    if (empty($blocked_isp)) {
        return true;
    }
    
    foreach ($blocked_isp as $needle) {
        if (stripos($isp, $needle) !== false) {
            return false;
        }
    }
    
    return true;
}

// Verificar filtro de idioma
function check_lang_filter($lang, $allowed_langs) {
    if (empty($allowed_langs)) {
        return true;
    }
    
    return in_array($lang, $allowed_langs);
}

// Verificar filtro de referrer
function check_referer_filter($referer, $block_without_referer, $referer_stopwords) {
    if ($block_without_referer && empty($referer)) {
        return false;
    }
    
    if (empty($referer_stopwords)) {
        return true;
    }
    
    foreach ($referer_stopwords as $stopword) {
        if (stripos($referer, $stopword) !== false) {
            return false;
        }
    }
    
    return true;
}

// Verificar filtro de VPN/TOR
function check_vpntor_filter($is_vpn, $is_tor, $block_vpnandtor) {
    if (!$block_vpnandtor) {
        return true;
    }
    
    return !($is_vpn || $is_tor);
}

// Verificar filtro de IP
function check_ip_filter($ip, $ip_black_filename, $ip_black_cidr) {
    // Verificar arquivo de IPs bloqueados
    if (!empty($ip_black_filename) && file_exists($ip_black_filename)) {
        $blocked_ips = file($ip_black_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($ip, $blocked_ips)) {
            return false;
        }
    }
    
    // Verificar CIDRs bloqueados
    if (!empty($ip_black_cidr)) {
        $ip_long = ip2long($ip);
        foreach ($ip_black_cidr as $cidr) {
            list($subnet, $mask) = explode('/', $cidr);
            $subnet_long = ip2long($subnet);
            $mask_long = ~((1 << (32 - $mask)) - 1);
            if (($ip_long & $mask_long) == ($subnet_long & $mask_long)) {
                return false;
            }
        }
    }
    
    return true;
}

// Funções de log
function log_white_click($ip, $country, $isp, $os, $ua, $reasons, $params) {
    // Implementação simplificada
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $ip,
        'country' => $country,
        'isp' => $isp,
        'os' => $os,
        'ua' => $ua,
        'reasons' => $reasons,
        'params' => $params
    ];
    
    // Aqui você pode salvar o log em um arquivo ou banco de dados
}

function log_black_click($subid, $ip, $country, $isp, $os, $ua, $prelanding, $landing, $params) {
    // Implementação simplificada
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'subid' => $subid,
        'ip' => $ip,
        'country' => $country,
        'isp' => $isp,
        'os' => $os,
        'ua' => $ua,
        'prelanding' => $prelanding,
        'landing' => $landing,
        'params' => $params
    ];
    
    // Aqui você pode salvar o log em um arquivo ou banco de dados
} 