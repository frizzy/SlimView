# Slim View

Inject any rendering engine into your Slim View. Multiple engines are supported
and the view will fall back to standard PHP templates if a template cannot be resolved.

## Usage

Add the following in your root composer.json file:

    {
        "require": {
            "frizzy/slim-view": "0.*"
        }
    }


Adding Twig to your view:

    
    $view   = new \Frizzy\Slim\View\View;
    $loader = new \Twig_Loader_Filesystem(__DIR__ . '/my_templates'); 
    
    $view->setRenderEngine(
        new \Twig_Environment($loader),
        function ($engine, $template, $data) {        
            return $engine->loadTemplate($template)->render($data); 
        },
        function ($template) {
            return preg_match('/\.twig$/', $template);
        }
    );
