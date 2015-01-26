<?php
/* author: Jonathan G. LeRoux
 * email: lerouxjonathan90@gmail.com
 * 
 * 
 * Description: 
 * The S3Objects class allows for easy access to the individual items within an S3 buckets.
 * S3Objects allow for easy manipulation of S3 items 
 * 
 * $bucketname (string):  The name of the bucket you're accessing in your Amazon S3 bucket
 * $key (string): The S3 key string used to uniquely identify the S3 object within a bucket
 * $connection (object S3object):  The S3Connection object connecting you to the S3 service. (not required)
 * 
 * 
*/
class S3Object
{
  public $bucketname;
  public $key;
  public $connection;
 
  //Instantiates the S3Object object
  function __construct($bucketname, $ListIteratorObject, $connection)
  {
	$this->bucketname = $bucketname;
    $this->key = $ListIteratorObject["Key"];
	$this->connection = $connection;
  }//end: __construct
  
  
  
  //Returns the S3Obejct's S3 key
  function getKey()
  {
    return $this->key;
  }//end: getKey()
  
  
  
  //Returns the name of the S3Object derived from the ListIteratorObject's S3 key
  function getName()
  {
    //Harvest the name from the key
	$namepieces = explode("/", $this->key);
	
	//Once exploded on "/", a folder's last array piece will be equal to "", the exploded slash ("/");
	//Else, harvest the last piece, and that's the name of the object that is a file
	if ($namepieces[count($namepieces) - 1] === "")
	{
	  return $namepieces[(count($namepieces) - 1) - 1];
	}
	else
	{
	  return $namepieces[count($namepieces) - 1];
	}
  }
  
  
  
  //Returns the object type (File or Folder) of the S3Object in question
  function getType()
  {
    if ($this->isFolder() === FALSE)
	{
	  return "FILE";
	}
	else
	{
	  return "FOLDER";
	}
  }//end: getType()
  
  
  
  //Examines if the S3Object is a File based on S3 key
  function isFile()
  {
  	//If the last character of the name of the S3Object isn't a slash, and its name does contain a period (for the file extension) 
    if (strpos($this->getName(), ".") !== FALSE && substr($this->getKey(), -1) !== "/")
	{
	  return TRUE;
	}
	else
	{
	  return FALSE;
	}
  }//end: isFile()
  
  
  //Examines if the S3Object is a Folder based on S3 key
  function isFolder()
  {
    if (strpos($this->getName(), ".") === FALSE && substr($this->getKey(), -1) === "/")
	{
	  return TRUE;
	}
	else
	{
	  return FALSE;
	}
  }//end: isFolder()
  
  
  
}//end: S3Object class
?>