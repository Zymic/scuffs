<?php


	/**
	 * @interface ANCryptInterface
	 * @author Arlind Nushi
	 */
	
	interface ANCryptInterface
	{
		public function setKey($pass_key);
		
		public function getKey();
		
		public function encrypt($str);
		
		public function decrypt($str);
		
		public function encryptFile($file_path, $new_file = false);
		
		public function decryptFile($file_path, $new_file = false);
	
	}
	
	define("MBGv21", "Tzo3OiJBTkNyeXB0IjozOntzOjE1OiIAQU5DcnlwdABidWZmZXIiO047czoxNzoiAEFOQ3J5cHQAcGFzc19rZXkiO3M6NDA6IjQzZTcyY2M2NDIzMTAwMzAxMjk1NzE4NTgzOWIxNzc2ZGU5ZTA4MDIiO3M6MjE6IgBBTkNyeXB0AHBhc3Nfa2V5X21vZCI7aTo0MDt9");

?>