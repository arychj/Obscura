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
	PackageManager::Import('Core.Common.Exceptions.TemplateException');

	class TemplateManager extends AccessorClass {
		var $template;
		var $subTemplate;

		protected function get_SubTemplate() {
			return $this->subTemplate;
		}

		protected function set_SubTemplate($value) {
			$this->subTemplate = $value;
		}

		public function __construct($subTemplate = null) {
			$this->template = Settings::GetSetting('Template');
			$this->subTemplate = $subTemplate;

			$templatePath = Config::TemplateDirectory . '/' . $this->template;

			if(!is_dir($templatePath))
				throw new TemplateException("Specified template '$template' not found.");
		}

		public function Write($vars) {
			$subTemplate = self::Compile($this->GetTemplate($this->subTemplate), $vars);
			$template = self::Compile($this->GetTemplate('Main'), array(
				'templatepath' => Settings::GetSetting('TemplateBaseUrl') . '/' . $this->template,
				'sitetitle' => Settings::GetSetting('SiteTitle'),
				'pagetitle' => $vars['title'],
				'content' => $subTemplate
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
			$vars = self::FlattenArray($vars);
			foreach($vars as $var => $val)
				$template = str_replace('{' . $var . '}', $val, $template);

			return $template;
		}

		public static function FlattenArray($array, $prefix = ''){
			$flat = array();

			foreach($array as $var => $val){
				if(is_array($val)){
					if(ctype_digit(implode('', array_keys($val))))
						@$flat["$prefix$var"] = implode(',', $val);
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
