/**
 * Verifica se o host é localhost, caso seja retorna a baseUrl 
 * com o acréscimo do nome da pasta no servidor local
 * Se for um host online, apenas retora o seu domínio.
 * 
 * @returns {String} Retorna a base url do site.
*/
function getBaseUrl() {
     //Nome do host
    var hostName = location.hostname;

    /*if (hostName === "localhost") {
        // Endereço após o domínio do site
        pathname = window.location.pathname;
        // Separa o pathname com uma barra transformando o resultado em um array
        splitPath = pathname.split('/');
        
        // Obtém o segundo valor do array, que é o nome da pasta do servidor local
        path = splitPath[1];

        baseUrl = "http://" + hostName + "/" + path;
    } else {
        baseUrl = "http://" + hostName;
    }*/

    baseUrl = "https://" + hostName + "/";

    return baseUrl;
}