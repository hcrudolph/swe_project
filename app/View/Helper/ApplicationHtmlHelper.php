<?php
App::uses('HtmlHelper', 'View/Helper');
class ApplicationHtmlHelper extends HtmlHelper {
	/*function __construct()
	{
		$_tags['polymertemplate'] = '<link rel="import" href="%s">';
	}*/
/*
 * Add polymer-template
*/
    public function polymerTemplate($path) {
        //Load path-array
        if(is_array($path))
        {
            foreach ($path as $pathValue)
            {
                this.polymer($pathValue);
            }
            return;
        }
        
        //Create Import-URL
        if (strpos($path, '//') !== false) {
		$url = $path;
	} else {
		$url = $this->assetUrl($path, array('pathPrefix' => 'polymer'));
	}
	return sprintf('<link rel="import" href="%s">', $url);
	//return sprintf($this->_tags['polymertemplate'], $url);
    }
}
?>
