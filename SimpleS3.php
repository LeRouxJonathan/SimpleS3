<?php
/* author: Jonathan G. LeRoux
 * email: lerouxjonathan90@gmail.com
 * 
 * 
 * Description: 
 * SimpleS3 allows for easy
 * 
 * SimpleS3.php sets up all of the required class files for interaction with your Amazon S3 account
 * 
 * 
*/
require_once("aws.phar");
require_once("S3Connection.php");
require_once("Bucket.php");
require_once("Folder.php");
require_once("File.php");
require_once("S3Object.php");

//Requirement:
//S3Client connection from aws.phar
use Aws\S3\S3Client;
?>