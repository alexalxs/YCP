<?php
// Funções de detecção de dispositivos, navegadores, sistemas operacionais, etc.

// Detectar sistema operacional
if (!function_exists('getOS')) {
    function getOS($user_agent) {
        $os_platform = "unknown";

        $os_array = array(
            '/windows nt 10/i'      => 'Windows',
            '/windows nt 6.3/i'     => 'Windows',
            '/windows nt 6.2/i'     => 'Windows',
            '/windows nt 6.1/i'     => 'Windows',
            '/windows nt 6.0/i'     => 'Windows',
            '/windows nt 5.2/i'     => 'Windows',
            '/windows nt 5.1/i'     => 'Windows',
            '/windows xp/i'         => 'Windows',
            '/windows nt 5.0/i'     => 'Windows',
            '/windows me/i'         => 'Windows',
            '/win98/i'              => 'Windows',
            '/win95/i'              => 'Windows',
            '/win16/i'              => 'Windows',
            '/macintosh|mac os x/i' => 'OS X',
            '/mac_powerpc/i'        => 'OS X',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Linux',
            '/iphone/i'             => 'iOS',
            '/ipod/i'               => 'iOS',
            '/ipad/i'               => 'iOS',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }
}

// Detectar navegador
if (!function_exists('getBrowser')) {
    function getBrowser($user_agent) {
        $browser = "unknown";

        $browser_array = array(
            '/msie/i'       => 'Internet Explorer',
            '/firefox/i'    => 'Firefox',
            '/safari/i'     => 'Safari',
            '/chrome/i'     => 'Chrome',
            '/edge/i'       => 'Edge',
            '/opera/i'      => 'Opera',
            '/netscape/i'   => 'Netscape',
            '/maxthon/i'    => 'Maxthon',
            '/konqueror/i'  => 'Konqueror',
            '/mobile/i'     => 'Mobile Browser'
        );

        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }

        return $browser;
    }
}

// Detectar dispositivo
if (!function_exists('getDevice')) {
    function getDevice($user_agent) {
        $device = "unknown";

        $device_array = array(
            '/iphone/i'     => 'iPhone',
            '/ipod/i'       => 'iPod',
            '/ipad/i'       => 'iPad',
            '/android/i'    => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i'      => 'Mobile'
        );

        foreach ($device_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $device = $value;
            }
        }

        return $device;
    }
}

// Detectar se é um bot
if (!function_exists('isBot')) {
    function isBot($user_agent) {
        $bot_array = array(
            'googlebot', 'bingbot', 'yandex', 'baiduspider', 'facebookexternalhit',
            'twitterbot', 'rogerbot', 'linkedinbot', 'embedly', 'quora link preview',
            'showyoubot', 'outbrain', 'pinterest', 'slackbot', 'vkShare', 'W3C_Validator'
        );

        foreach ($bot_array as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}

// Detectar se é VPN (simplificado)
if (!function_exists('isVPN')) {
    function isVPN($ip) {
        // Em um ambiente real, você usaria um serviço de detecção de VPN
        // Aqui, retornamos um valor padrão para simplificar
        return false;
    }
}

// Detectar se é Tor (simplificado)
if (!function_exists('isTor')) {
    function isTor($ip) {
        // Em um ambiente real, você usaria um serviço de detecção de Tor
        // Aqui, retornamos um valor padrão para simplificar
        return false;
    }
}

// Obter IP do usuário
if (!function_exists('getIP')) {
    function getIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
} 