<?php
require_once 'htmlprocessing.php';
require_once 'cookies.php';
require_once 'redirect.php';
require_once 'pixels.php';
require_once 'abtest.php';

//Включение отладочной информации
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Конец включения отладочной информации

function white($use_js_checks)
{
    global $white_action,$white_folder_names,$white_redirect_urls,$white_redirect_type;
    global $white_curl_urls,$white_error_codes,$white_use_domain_specific,$white_domain_specific;
    global $save_user_flow;

    // Forçar action para "folder" para garantir carregamento direto
    $action = "folder";
    $folder_names = $white_folder_names;
    
    // Forçar headers para garantir que o conteúdo seja exibido
    header('Content-Type: text/html; charset=UTF-8');
    
    // Para evitar output vazio
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Iniciar novo buffer
    ob_start();
    
    //грязный хак для прокидывания реферера через куки
    if ($use_js_checks && 
        isset($_SERVER['HTTP_REFERER']) && 
        !empty($_SERVER['HTTP_REFERER'])){
        ywbsetcookie("referer",$_SERVER['HTTP_REFERER']);
    }

    // Verificar se a pasta branca existe
    if (file_exists('branca') && is_dir('branca')) {
        $content_path = 'branca/index.html';
        if (file_exists($content_path)) {
            // Carregar conteúdo diretamente do arquivo
            $html_content = file_get_contents($content_path);
            if (!empty($html_content)) {
                // Método direto para substituir links CSS e JS
                $html_content = str_replace('href="styles.css"', 'href="/branca/styles.css"', $html_content);
                $html_content = str_replace('src="script.js"', 'src="/branca/script.js"', $html_content);
                
                // Método direto para substituir links de imagens
                $html_content = preg_replace('/src="([^"]+\.(png|jpg|jpeg|gif|webp))"/', 'src="/branca/$1"', $html_content);
                
                // Método direto para substituir links de áudio e vídeo
                $html_content = preg_replace('/src="([^"]+\.(mp3|mp4|wav|ogg))"/', 'src="/branca/$1"', $html_content);
                
                // Método direto para substituir background-image no CSS inline
                $html_content = preg_replace('/background-image:\s*url\(([^\)]+)\)/', 'background-image: url(/branca/$1)', $html_content);
                
                // Adicionar link para a página de oferta
                if (strpos($html_content, 'Ver Oferta Especial') === false) {
                    $html_content = str_replace('</body>', '<div class="vogue-cta" style="text-align: center; margin: 20px 0;"><a href="/oferta1/" class="vogue-button" style="display: inline-block; padding: 10px 20px; background-color: #ff6b6b; color: white; text-decoration: none; border-radius: 5px;">Ver Oferta Especial</a></div></body>', $html_content);
                }
                
                echo $html_content;
                
                // Obter conteúdo do buffer e enviar para o navegador
                $output = ob_get_clean();
                
                // Garantir que há conteúdo antes de enviar
                if (!empty($output)) {
                    // Definir tamanho do conteúdo
                    header('Content-Length: ' . strlen($output));
                    echo $output;
                } else {
                    die("Não foi possível carregar a página branca. Verifique se os arquivos existem.");
                }
                
                return;
            }
        }
    }

    // Se não encontrou a pasta branca, continuar com o comportamento padrão
    $curfolder = select_item($folder_names, $save_user_flow, 'white', true);
    // Verificar se o caminho existe
    $content_path = $curfolder[0].'/index.html';
    
    if (file_exists($content_path)) {
        // Carregar conteúdo diretamente do arquivo
        $html_content = file_get_contents($content_path);
        if (!empty($html_content)) {
            // Processar o HTML para caminhos relativos
            $baseurl = $curfolder[0].'/';
            $html_content = rewrite_relative_urls($html_content, $baseurl);
            
            // Adicionar scripts adicionais, incluindo o botão de estatísticas
            $html_content = insert_additional_scripts($html_content);
            
            echo $html_content;
        } else {
            // Se o arquivo está vazio, tenta carregar com a função normal
            echo load_white_content($curfolder[0], false);
        }
    } else {
        // Se não encontrar o arquivo, tenta outros métodos
        $content_path = $curfolder[0].'/index.php';
        if (file_exists($content_path)) {
            include $content_path;
        } else {
            echo load_white_content($curfolder[0], false);
        }
    }
    
    // Obter conteúdo do buffer e enviar para o navegador
    $output = ob_get_clean();
    
    // Garantir que há conteúdo antes de enviar
    if (!empty($output)) {
        // Definir tamanho do conteúdo
        header('Content-Length: ' . strlen($output));
        echo $output;
    } else {
        die("Não foi possível carregar a página branca. Verifique se os arquivos existem.");
    }
    
    return;
}

function black($clkrdetect)
{
    global $black_preland_action,$black_preland_folder_names;
    global $black_land_action,$black_land_folder_names, $black_land_redirect_urls, $black_land_redirect_type;
    global $save_user_flow,$jsconnect_type;

    // Forçar headers para garantir que o conteúdo seja exibido
    header('Content-Type: text/html; charset=UTF-8');
    
    // Para evitar output vazio
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Iniciar novo buffer
    ob_start();
    
    $cursubid = set_subid();
    set_facebook_cookies();
 
    // Forçar modo folder para garantir carregamento direto
    $black_land_action = 'folder';
    
    // Verificar se a pasta oferta1 existe
    if (file_exists('oferta1') && is_dir('oferta1')) {
        $content_path = 'oferta1/index.html';
        if (file_exists($content_path)) {
            // Carregar conteúdo diretamente do arquivo
            $html_content = file_get_contents($content_path);
            if (!empty($html_content)) {
                // Método direto para substituir links CSS e JS
                $html_content = str_replace('href="styles.css"', 'href="/oferta1/styles.css"', $html_content);
                $html_content = str_replace('src="script.js"', 'src="/oferta1/script.js"', $html_content);
                
                // Método direto para substituir links de imagens
                $html_content = preg_replace('/src="([^"]+\.(png|jpg|jpeg|gif|webp))"/', 'src="/oferta1/$1"', $html_content);
                
                // Método direto para substituir links de áudio e vídeo
                $html_content = preg_replace('/src="([^"]+\.(mp3|mp4|wav|ogg))"/', 'src="/oferta1/$1"', $html_content);
                
                // Método direto para substituir background-image no CSS inline
                $html_content = preg_replace('/background-image:\s*url\(([^\)]+)\)/', 'background-image: url(/oferta1/$1)', $html_content);
                
                // Remover qualquer script inline existente para evitar duplicação
                $html_content = preg_replace('/<script>\s*\/\*\*\s*\*\s*Script de rastreamento.*?<\/script>/s', '', $html_content);
                
                // Adicionar script de conversão se necessário
                if (file_exists('scripts/conversion_tracker.js')) {
                    $script_content = file_get_contents('scripts/conversion_tracker.js');
                    if (strpos($html_content, '</body>') !== false) {
                        $html_content = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html_content);
                    } else {
                        $html_content .= '<script>' . $script_content . '</script>';
                    }
                } else {
                    // Se o arquivo não existir, adicionar um script básico
                    $script_content = "
                    /**
                     * Script de rastreamento de conversões de email
                     */
                    (function() {
                        console.log('Rastreador de conversões inicializado');
                        
                        // Adicionar campos ocultos de oferta aos formulários
                        const forms = document.querySelectorAll('form');
                        forms.forEach(form => {
                            let ofertaField = form.querySelector('input[name=\"oferta\"]');
                            if (!ofertaField) {
                                ofertaField = document.createElement('input');
                                ofertaField.type = 'hidden';
                                ofertaField.name = 'oferta';
                                ofertaField.value = 'oferta1';
                                form.appendChild(ofertaField);
                            }
                        });
                    })();";
                    
                    $html_content = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html_content);
                }
                
                // Processar formulários para adicionar campo oculto de oferta
                $html_content = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="oferta" value="oferta1">', $html_content);
                
                // Adicionar link para voltar à página inicial
                if (strpos($html_content, 'Voltar à página inicial') === false) {
                    $html_content = str_replace('</body>', '<div style="text-align: center; margin: 20px 0;"><a href="/" style="display: inline-block; padding: 10px 20px; background-color: #2E86DE; color: white; text-decoration: none; border-radius: 5px;">Voltar à página inicial</a></div></body>', $html_content);
                }
                
                // Adicionar rodapé com disclaimers se não existir
                if (strpos($html_content, 'Este site e sua oferta não são afiliados') === false) {
                    $footer_html = '
                    <div class="disclaimer" style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d;">
                        <p>Este site e sua oferta não são afiliados, associados, endossados ou patrocinados pelo Facebook. Facebook é uma marca registrada de Meta Platforms, Inc.</p>
                        <p>Os resultados individuais podem variar. Os depoimentos apresentados são experiências reais de usuários do nosso método, mas os resultados não são garantidos e dependem de diversos fatores individuais.</p>
                        <p>As informações contidas neste site destinam-se apenas a fins informativos e educacionais e não substituem aconselhamento profissional em relacionamentos, psicológico ou médico.</p>
                        <p>&copy; 2023 Gatillos Invisibles de la Atracción. Todos los derechos reservados.</p>
                    </div>';
                    
                    $html_content = str_replace('</body>', $footer_html . '</body>', $html_content);
                }
                
                echo $html_content;
                
                // Obter conteúdo do buffer e enviar para o navegador
                $output = ob_get_clean();
                
                // Garantir que há conteúdo antes de enviar
                if (!empty($output)) {
                    // Definir tamanho do conteúdo
                    header('Content-Length: ' . strlen($output));
                    echo $output;
                } else {
                    die("Não foi possível carregar a página de oferta. Verifique se os arquivos existem.");
                }
                
                return;
            }
        }
    }
    
    // Se não encontrou a pasta oferta1, continuar com o comportamento padrão
    if ($black_preland_action == 'none')
    {
        if (empty($black_land_folder_names)){
            die('No landings configured!');
        }

        // Selecionar landing
        $res = select_landing($save_user_flow, $black_land_folder_names, true);
        $landing = $res[0];
        
        if (empty($landing)) {
            die('Unable to select landing!');
        }

        add_black_click($cursubid, $clkrdetect, '', $landing);

        // Carregar o conteúdo diretamente
        $content_path = $landing.'/index.html';
        
        if (file_exists($content_path)) {
            // Carregar conteúdo diretamente do arquivo
            $html_content = file_get_contents($content_path);
            if (!empty($html_content)) {
                // Processar o HTML para caminhos relativos
                $baseurl = $landing.'/';
                $html_content = rewrite_relative_urls($html_content, $baseurl);
                echo $html_content;
            } else {
                // Se o arquivo está vazio, tenta carregar com a função normal
                echo load_landing($landing);
            }
        } else {
            // Se não encontrar o arquivo, tenta outros métodos
            $content_path = $landing.'/index.php';
            if (file_exists($content_path)) {
                include $content_path;
            } else {
                echo load_landing($landing);
            }
        }
    }
    else if ($black_preland_action == 'folder')
    {
        // Código para prelands
        $prelandings = $black_preland_folder_names;
        if (empty($prelandings)) {
            die('No prelands configured!');
        }
        
        $res = select_prelanding($save_user_flow, $prelandings);
        $prelanding = $res[0];
        
        $res = select_landing($save_user_flow, $black_land_folder_names, true);
        $landing = $res[0];
        $t = $res[1];

        echo load_prelanding($prelanding, $t);
        add_black_click($cursubid, $clkrdetect, $prelanding, $landing);
    }
    
    // Obter conteúdo do buffer e enviar para o navegador
    $output = ob_get_clean();
    
    // Garantir que há conteúdo antes de enviar
    if (!empty($output)) {
        // Definir tamanho do conteúdo
        header('Content-Length: ' . strlen($output));
        echo $output;
    } else {
        die("Não foi possível carregar a página de oferta. Verifique se os arquivos existem.");
    }
}

?>
