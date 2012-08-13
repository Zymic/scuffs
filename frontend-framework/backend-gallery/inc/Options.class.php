<?php

	class MBG_Options
	{
		private $options_arr = array();
		
		public function __construct()
		{
			$q = mysql_query("SELECT * FROM `".dbprefix()."options`");
			
			$options = iterate($q);
			
			foreach($options as $option)
			{
				$name = $option['option_name'];
				$value = $option['option_value'];
				
				$this->options_arr[$name] = $value;
			}
		}
		
		public function get($option_name)
		{			
			return $this->options_arr[$option_name];
		}
		
		public function update($name, $val = "")
		{			
			if( isset($this->options_arr[$name]) )
			{
				mysql_query( sprintf("UPDATE `".dbprefix()."options` SET `option_value` = '%s' WHERE `option_name` = '%s'", mysql_real_escape_string($val), mysql_real_escape_string($name)) );
			}
			else
			{
				mysql_query( sprintf("INSERT INTO `".dbprefix()."options` (`option_name`,`option_value`) VALUES('%s','%s')", mysql_real_escape_string($name), mysql_real_escape_string($val)) );
			}
		}
	}
?>