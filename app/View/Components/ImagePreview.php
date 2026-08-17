<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImagePreview extends Component
{
    public $options;
    public $pathName,$imageName,$style;
    public $className,$idName,$altName,$widthName,$heightName;
    public $lazyName="eager",$pictureClass,$pictureTag=true,$fetchpriority="high";
    public function __construct($imagepath,$image,$options=null)
    {
        $this->pathName = $imagepath;
        $this->imageName = $image;
        $this->options = $options;
        if(!empty($options['class'])){ $this->className = "class='".$options['class']."'"; }
        if(!empty($options['alt'])){ $this->altName = "alt='".$options['alt']."'"; }
        if(!empty($options['id'])){ $this->idName = "id='".$options['id']."'"; }
        if(!empty($options['width'])){ $this->widthName = "width='".$options['width']."'"; }
        if(!empty($options['height'])){ $this->heightName = "height='".$options['height']."'"; }
        if(!empty($options['lazy']) && $options['lazy']==true){ $this->lazyName = "lazy"; $this->fetchpriority = "low"; }
        if(!empty($options['pictureClass'])){ $this->pictureClass = "class='".$options['pictureClass']."'"; }
        if(!empty($options['pictureTag'])){ $this->pictureTag = $options['pictureTag']; }
        if(!empty($options['style'])){ $this->style = "style='".$options['style']."'"; }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.image-preview');
    }
}
