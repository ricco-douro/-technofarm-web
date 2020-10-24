<?php

#O arquivo helper.php contém essa classe auxiliar que é usada para recuperar os dados a 
#serem exibidos na saída do módulo. Como mencionado anteriormente, nossa classe auxiliar 
#terá um método: getHello(). Esse método retornará a mensagem 'Hello, World'.

class ModHelloWorldHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getHello($params)
    {
        return 'Hello, World!';
    }
}