
var mostrado = false;

$(document).ready(function(){
    /* mostrado = false
     * 
     * Caso 1, click en body:
     *      mostrado es false, por tanto entra en el if y se oculta
     *      vuelve a ponerse false
     *      
     * Caso 2, click en lista (estando oculta):
     *      mostrado es false, se pone true y muestra la lista (toggle)
     *      se ejecuta el evento del body
     *      mostrado es true, por tanto no lo oculta, (salta este paso) y
     *      vuelve a ponerse false
     *      
     *      (ahora el menu estaria visible)
     *      
     * Caso 3, click en lista, (estando visible):
     *      mostrado es false, se pone true y oculta la lista (toggle)
     *      se ejecuta el evento del body
     *      mostrado es true, por tanto no lo vuelve a ocultar y vuelve
     *      a colcarse false
     */
    $("#navegacionPrincipal > ul > li:last-child").click(function(){
        
        mostrado = true;
        $(".navegacionSecundario").toggle();
        
    });
    
    $("body").click(function(){
        
        if(!mostrado)
        $(".navegacionSecundario").hide();
        mostrado = false;
    });
    /*  Fin de eventos del menu desplegable  */
    
});
