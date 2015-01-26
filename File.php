<?php
/* author: Jonathan G. LeRoux
 * email: lerouxjonathan90@gmail.com
 * 
 * 
 * Description: 
 * The File class allows manipulation of S3Objects as File objects
 * 
 * $bucketname (string): The name of the bucket you're accessing in your Amazon S3 bucket
 * $key (string): The S3 key string used to uniquely identify the S3 object within a bucket
 * $connection (object S3object): The S3Connection object connecting you to the S3 service.
 * 
 * 
*/
class File
{
  public $bucketname;
  public $key;
  public $connection;
  public $valid_file_types;

  //Instantiates the File object
  //These will be constructed from pieces of the getListObjects iterator
  function __construct($bucketname, $key, $connection)
  {
    $this->bucketname = $bucketname;
	$this->key = $key;
	$this->connection = $connection;
	$this->valid_file_types = null;
  }



  //Returns the File's S3 key
  function getKey()
  {
    return $this->key;
  }//end: getKey()



  //Returns the File's name
  function getName()
  {
    $pieces = explode("/", $this->getKey());
    return $pieces[count($pieces) - 1];
  }//end: getName()


  //Returns the AWS S3 URL for this object
  //Note: Ensure that proper permission configurations are in place for your S3 bucket, or the returned URL may not work.
  function getUrl()
  {
    $connection = $this->connection;
    return $connection->getObjectUrl($this->bucketname, $this->key);
  }//end: getUrl()


  //Return the File's extension
  function getFileExtension()
  {
    $pieces = explode(".", $this->getName());
    return $pieces[count($pieces) - 1];
  }//end: getFileExtension()

  
  //Determines if the File is of a provided extension
  //$fileExtension (string): The file type extension we're comparing our File's extension to.
  function isFileExtension($fileExtension)
  {
    if ($this->getFileExtension() === $fileExtension || 
	    $this->getFileExtension() === ".".$fileExtension || 
	    ".".$this->getFileExtension() === $fileExtension)
	{
	  return TRUE;
	}
	else 
	{	
	  return FALSE;
	}
  }//end: isFileExtension()


}//end: File class
?>