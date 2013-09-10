<?php
	/**
	 *	Obscura Photo Management System
	 *	http://www.github.com/arychj/obscura
	 *	©2013 Erik J. Olson
	 *
	 *	-----------------------------------------------------------------------------
	 *	"THE BEER-WARE LICENSE" (Revision 42):
	 *	<erikjolson@arych.com> wrote this file. As long as you retain this notice you
	 *	can do whatever you want with this stuff. If we meet some day, and you think
	 *	this stuff is worth it, you can buy me a beer in return. Erik J. Olson.
	 *	-----------------------------------------------------------------------------
	 *
	 *	Obscura.Core.Web.TemplateManager
	 *	<Description>
	 *
	 *	@changelog
	 *	2013.09.07
	 *		Created
	 */

	PackageManager::Import('Config');
	PackageManager::Import('Core.Settings');
	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.DataTools');
	PackageManager::Import('Core.Common.Exceptions.TemplateException');
	PackageManager::Import('Core.Web.Security');

	class TemplateManager extends AccessorClass {
		var $template;

		public function __construct($template = null) {
			$this->template = ($template == null ? Settings::GetSettingValue('Template') : $template);

			$templatePath = Config::TemplateDirectory . '/' . $this->template;

			if(!is_dir($templatePath))
				throw new TemplateException("Specified template '$template' not found.");
		}

		public function Write($subtemplate, $vars = null) {
			$security = new Security();

			if($vars == null)
				$vars = array();

			$template = self::Compile($this->GetTemplate('Main'), array_merge(array(
				'content' => $this->GetTemplate($subtemplate),
				'templatepath' => Settings::GetSettingValue('TemplateBaseUrl') . '/' . $this->template,
				'sitetitle' => Settings::GetSettingValue('SiteTitle'),
				'pagename' => substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1, -4),
				'pagetitle' => (isset($vars['title']) ? $vars['title'] : $subtemplate),
				'username' => $security->Username
				),
				$vars
			));

			echo $template;
		}

		private function GetTemplate($template){
			$path = Config::TemplateDirectory . '/' . $this->template . '/' . $template . '.tpl';

			if(file_exists($path))
				return file_get_contents($path);
			else
				throw new TemplateException("Specified template '$template' not found.");
		}

		public static function Compile($template, $vars) {
			if($vars != null){
				$vars = self::FlattenArray($vars);
				$template = DataTools::BuildString($template, $vars);
			}

			return $template;
		}

		public static function FlattenArray($array, $prefix = ''){
			$flat = array();

			foreach($array as $var => $val){
				if(is_array($val)){
					if(ctype_digit(implode('', array_keys($val))))
						@$flat["$prefix$var"] = implode(',', $val);
					elseif(sizeof($val) == 0)
						$flat["$prefix$var"] = '';
					else
						$flat += self::FlattenArray($val, "$prefix$var-");
				}
				else
					$flat["$prefix$var"] = $val;
			}

			return $flat;
		}
	}

?>
