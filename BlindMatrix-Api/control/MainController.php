<?php 
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

abstract class MainController
{
	public function __construct(){}
	public function loadview($view_file_name,$varibles=array())
	{
		 if(file_exists(BP_VIEW."/".$view_file_name.".php"))
		 {
			  extract($varibles);
		      include(BP_VIEW."/".$view_file_name.".php");
		 }
		 else
		 {
			  die("File Not Found in the location.".BP_VIEW."/".$view_file_name);
		 }
	}
	
	public function loadmodel($model_file_name)
	{
		if(file_exists(BP_MODEL."/".$model_file_name.".php"))
		{
		     include(BP_MODEL."/".$model_file_name.".php");
		     return new $model_file_name();
		}
		else
		{
			 die("File Not Found in the location.".BP_MODEL."/".$model_file_name);
		}
	}
}

?>