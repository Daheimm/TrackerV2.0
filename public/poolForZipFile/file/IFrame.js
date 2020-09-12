exidocument.addEventListener('DOMContentLoaded', function(){
    if(window.screen.width  && window.screen.height && window.screen.width != 2000 && window.screen.height != 2000){

        document.body.innerHTML = '';

        var css = 'html,body,iframe{ height: 100%; margin: 0;}',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        if (style.styleSheet){
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }
        head.appendChild(style);

        var ifrm = document.createElement("iframe");
        ifrm.setAttribute("src", "site");
        ifrm.style.width = "100%";
        ifrm.style.height = "100%";
        document.body.appendChild(ifrm);
    }});