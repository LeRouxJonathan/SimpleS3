# SimpleS3
Allows file-and-folder, object-oriented interaction with Amazon S3 

Much more to be added to the readme with the update to version 3. 
Simple example of usage below:

<h2>Example: Adding a user-uploaded file to S3 folder</h2>
```php
$file_input = $_FILES["file_input"]; //File uploaded from the user

$s3client = new S3Connection();
$s3connection = $s3client->getConnection();

//Retrieve a bucket by name from your S3 account:
$bucket = $s3connection->getBucket("mybucket");

//Get a folder from your bucket
$folder = $bucket->getFolder("myfolder");

//Add our file to the folder and return the new File object
$file = $folder->addHTTPFile($file_input, TRUE);

//Return our new File's name
echo $file->getName();
```
