<?php

/**
 * Frizzy Slim View - Extending the Slim Framework View class (http://www.slimframework.com)
 *
 * @author      Bernard van Niekerk
 * @link        http://github.com/frizzy/slim-view
 * @copyright   2013 Bernard van Niekerk
 * @version     0.1.0
 * @package     Frizzy/Slim-View
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Frizzy\Slim\View;

use Closure;
use Slim\View as BaseView;

/**
 * View - extending Slim\View
 *
 * The view is responsible for rendering a template. The view
 * should subclass \Slim\View and implement this interface:
 *
 * public render(string $template);
 *
 * This method should render the specified template and return
 * the resultant string.
 */
class View extends BaseView
{
    /**
     * Render engines
     * @var array
     */
    private $renderEngines = array();
    
    /**
     * Set render engine
     *
     * @param mixed $engine Engine
     * @param Closure $renderCallback   Render callback function
     * @param Closure $resolverCallback Resolver callback function
     *
     * @return boolean New render engine set
     */
    public function setRenderEngine($engine, Closure $renderCallback, Closure $resolverCallback)
    {
        foreach ($this->renderEngines as list($compareEngine,,)) {
            if ($compareEngine === $engine) {
                return false;
            }
        }
        $this->renderEngines[] = array($engine, $renderCallback, $resolverCallback);
        
        return true;
    }
    
    /**
     * Render
     *
     * {@inheritDoc}
     */
    public function render($template)
    {
        $fallback = true;
        foreach ($this->renderEngines as list($engine, $renderCallback, $resolverCallback)) {
            if ($resolverCallback->__invoke($template)) {
                return $renderCallback->__invoke($engine, $template, $this->all());
            }
        }
        
        return parent::render($template);
    }
}