<?php
/* author: Jonathan G. LeRoux
 * email: lerouxjonathan90@gmail.com
 * 
 * 
 * Description: 
 * The Bucket class allows for easy access to the folders and immediate files within a bucket, specified by the folder or file's name.
 * 
 * $bucketname (string) = The name of the bucket you're accessing in your Amazon S3 bucket
 * $connection (object S3object)= The S3Connection object connecting you to the S3 service. (not required)
 * 
 * 
*/
class Bucket
{
	public $bucketname;
	public $connection;
	
	//Instantiates the Bucket object
	function __construct($bucketname , $S3Connection = NULL)
    {
    	if ($S3Connection === NULL)
		{
			$S3Connection = new S3Connection();
			$S3Connection = $S3Connection->getConnection();
		}

    	$this->bucketname = $bucketname;
		$this->connection = $S3Connection;
    }//end: __construct
	
	
	//Returns the name of the Bucket
	function getName()
	{
	  return $this->bucketname;
	}//end: getName()
	
	
	//Returns the S3 key of the Bucket
	function getKey()
	{
		return $this->bucketname;
	}//end: getKey()
	
	
	
	
	//Returns the S3 iterator object for a specific bucket for all of its contents; accessible by "foreach"-loop processing
    //$returnAsArray (boolean) : specifies whether or not to return an array of iterator objects or the iterator object inherent to the S3 API 
	function getAllObjects($returnAsArray = NULL)
	{
		$connection = $this->connection;
		
		if ($returnAsArray === NULL || $returnAsArray === FALSE)
		{
		   return $connection->getIterator("ListObjects", array("Bucket"=>$this->bucketname));
		}
		
		else if ($returnAsArray !== NULL && $returnAsArray === TRUE)
		{
	       $array = array();
		   $objects = $connection->getIterator("ListObjects", array("Bucket"=>$this->bucketname));
		   
		   foreach($objects as $object)
		   {
		     array_push($array, $object);
		   }
		   
		return $array;
		}
	}//end: getAllObjects()
	
	
	
	
    //Returns all iterator objects within S3 as S3Objects
	function getAllS3Objects()
	{
	  $objects = $this->getAllObjects();
	  $s3objects = array();
	  
      foreach ($objects as $object)
      {
        array_push($s3objects, new S3Object($this->bucketname, $object, $this->connection));
      } 
	  return $s3objects;
	}//end: getAllS3Objects()
	
	
    //Returns all files as an arrray of File objects within the Bucket
	function getFiles()
	{
	  $objects = $this->getAllS3Objects();
	  $files = array();
	  for ($i = 0; $i < count($objects); $i++)
	  {
	    if ($objects[$i]->isFile() === TRUE)
		{
		  array_push($files, $objects[$i]); 
		}
	  }
	  return $files;
	}//end: getFiles
	
	
	//Returns a Folder object from the bucket
	//$folderName (string): the name of the folder we wish to retrieve from this bucket
	//Note: This folder must be a direct child of this Bucket
	function getFolder($folderName)
	{
	  if (substr($folderName, -1) !== "/") //If the folderName lacks a "/", append it as per S3 key standards
	  {
	    $folderName = $folderName."/"; //As per S3 key standards
	  }
	   
	  //These four parameters will become our new Folder object
	  $bucket = $this->bucketname;
	  $key =  $folderName;
	  $connection = $this->connection;
								
	  return new Folder($bucket, $key, $connection);
	}
	
	
	
	//Adds a user-submitted file to the S3 bucket
	//$fileInputName (string): the name of the $_FILES array element
	function addHTTPFile($fileInputName)
	{
	  
	  //Ensure the $_FILE exists
	  if (isset($_FILES[$fileInputName]) === false)
	  {
	    throw new Exception("No file found.");
	  }	
		
		
	  $connection = $this->connection;
	  $connection->putObject(
	                        array(
	                             "Bucket"=>$this->bucketname,
	                             "Key"=>$_FILES[$fileInputName],
	                             "SourceFile"=>$_FILES[$fileInputName]["tmp_name"]
			  					 ));
	}//end: addHTTPFile
  
}//End: Bucket class
?>