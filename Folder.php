<?php
/* author: Jonathan G. LeRoux
 * email: lerouxjonathan90@gmail.com
 * 
 * 
 * Description: 
 * The Folder class allows for object-based access to subfolders, files, and allows for the creation and addition of subfolders and files.
 * 
 * $bucketname (string): The name of the bucket you're accessing in your Amazon S3 bucket
 * $key (string): The S3 key string used to uniquely identify the S3 object within a bucket
 * $connection (object S3object): The S3Connection object connecting you to the S3 service. (not required)
 * 
 * 
*/
class Folder
{

public $bucketname;
public $key;
public $connection;




  //Instantiates the Folder object
  function __construct($bucketname, $key, $connection)
  {
  	$this->bucketname = $bucketname;
    $this->key = $key;
	$this->connection = $connection;
  }//end: __construct
  
  
  
  
  
  //Returns the Folder S3 key
  function getKey()
  {
    return $this->key;		
  }//end: getKey()
  
  
  
 
  //Returns the Folder name
  function getName()
  {
    $key = $this->getKey();
	$namepieces = explode("/", $key);
	return $namepieces[(count($namepieces) - 1) - 1];
  }//end: getName()
  
  
  
  
  
  //Returns all objects as S3Objects within this folder, be they Folder or File
  function getS3Objects()
  {
  	$connection = $this->connection;
  	$objects = $connection->getIterator("ListObjects", array
		                                             (
		                                             "Bucket"=>$this->bucketname,
		                                             "Prefix"=>$this->key
		                                             ));
	$s3objects = array();
	foreach ($objects as $object)
	{
	  array_push($s3objects, new S3Object($this->bucketname, $object, $this->connection));
	}
	return $objects;	
  }//End: getS3Objects()
  
  
  

  
  //Adds a folder (subfolder) to the S3 Folder
  //$folderName (string): The name of the folder we wish to add to the Folder
  //$returnNewFolder (boolean): Determines whether or not to return the newly-created folder (subfolder) as a new Folder object
  function addFolder($folderName, $returnNewFolder = NULL)
  {
  		
	
    $bucketname = $this->bucketname;		
	$newfolderkey = $this->key.$folderName."/"; //As per S3 standard	
  	$connection = $this->connection;
	
	//Add the placeholder file so that this may be harvestable as a ListIteratorObject
	$connection->putObject(
	                      array
	                          (
	                          "Bucket"=>$bucketname,
	                          "Key"=>$newfolderkey."/s3placeholder.txt",
	                          "Body"=>"-"
	                          ));
							  
	//If desired, return the newly-created Folder
	if ($returnNewFolder === TRUE)
	{
	  return new Folder($bucketname, $newfolderkey, $connection);
	}
  }//end: addFolder()
  
  
  
  
  

  //Adds an HTTPFile to a given folder
  //$fileInputName (string): The input field name of the $_FILES array
  //$returnNewFile (boolean): Determines whether or not to return the newly-created file as a File object
  function addHTTPFile($fileInputName, $returnNewFile = NULL)
  {
  	
	//Ensure the $_FILE exists
	if (isset($_FILES[$fileInputName]) === false)
	{
	  throw new Exception("No file found.");
	}
	
	
	$bucketname = $this->bucketname;
	$filekey = $this->key.$_FILES[$fileInputName]; //Ends in the name of the file with the extension, as per S3 standards
    $connection = $this->connection;
	
	//Place the file within the S3 Folder
	$connection->putObject(
	                      array(
	                           "Bucket"=>$bucketname,
	                           "Key"=>$filekey,
	                           "SourceFile"=>$_FILES[$fileInputName]["tmp_name"]
	                           ));
							   
	//If desired, return the new HTTPFile we just uploaded as a File object
	if ($returnNewFile === TRUE)
	{
	  return new File($bucketname, $filekey, $connection);
	}
  }//end: addHTTPFile()

  
}//End: Folder Class
?>