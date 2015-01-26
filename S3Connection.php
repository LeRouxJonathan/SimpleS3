<?php
/* author: Jonathan G. LeRoux
 * email: lerouxjonathan90@gmail.com
 * 
 * 
 * Description: 
 * The Bucket class allows for easy access to the folders and immediate files within a bucket, specified by the folder or file's name.
 * 
 * $bucketname (string):  The name of the bucket you're accessing in your Amazon S3 bucket
 * $connection (object S3object):  The S3Connection object connecting you to the S3 service. (not required)
 * 
 * 
*/
class S3Connection
{
	public $iamkey;
	public $iamsecret;
	
	
	
	
	//Connects to S3 bucket
	//Requires your S3 access credentials 
	function __construct($key = NULL, $secret = NULL)
	{
		  if ($key === NULL)
		  {
		  	 $key = "S3_KEY"; //Your S3 key
		  }
		  
		  if ($secret === NULL)
		  {
		  	 $secret = "S3_SECRET"; //Your S3 secret
		  }
		  
		  $this->iamkey = $key;
		  $this->iamsecret = $secret;
	}//end: __construct
	
	
	
	
	
	
	//Returns a new S3Client connection
	function getConnection()
	{
		return S3Client::factory(array("key"=>$this->iamkey, "secret"=>$this->iamsecret));
	}//end: getConnection()
	
	
	
	
	
	
	
	
	
	//Returns a specific, unique bucket identified by the given name
	//$bucketname (string): The desired name of the bucket within S3
	function getBucket($bucketname)
	{
	  //Enforce S3 bucket-naming protocol that all bucketnames must be purely lowercase and return the Bucket object
	  return (new Bucket(trim(strtolower($bucketname)), $this->getConnection()));
	}//end: getBucket()
	
	
	
	
	
	
	
	
	//Creates a new bucket in the S3 service account
	//$bucketname (string): Establishes the all-lower-case (per S3 protocol) name of the bucket
	//$returnNewBucket (boolean): Returns the newly-created bucket as a Bucket object
	function createBucket($bucketname, $returnNewBucket = NULL)
	{
	  //Enforce S3 bucket-naming protocol that all bucketnames must be purely lowercase
	  $bucketname = trim(strtolower($bucketname));	
		
		
	  //Create the bucket via S3;
	  $connection = $this->getConnection();
	  $connection->createBucket(array(
	                                 "Bucket"=>$bucketname
	                                 ));
		
	  //Return the bucket, if desired as new Bucket object							 
	  if ($returnNewBucket === TRUE)
	  {
	    return new Bucket($bucketname, $connection);
	  }  
	}//end: createBucket()
	
	
}//end: S3Connection class
?>