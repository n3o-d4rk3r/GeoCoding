<?php
class Geocoding_UK_Geocode_v2_00
{

   //Now supports property level geocoding in urban areas. Please supply a full address for this
   //Credit: Thanks to Stuart Sillitoe (http://stu.so/me) for the original PHP that these samples are based on.

   private $Key; //The key to use to authenticate to the service.
   private $Location; //The location to geocode. This can be a full or partial postcode, a place name, street comma town, address (comma separated lines) or an ID from PostcodeAnywhere/Find web services.
   private $Data; //Holds the results of the query

   function __construct($Key, $Location)
   {
      $this->Key = $Key;
      $this->Location = $Location;
   }

   function MakeRequest()
   {
      $url = "postcodeanywhere update version link";
      $url .= "&Key=" . urlencode($this->Key);
      $url .= "&Location=" . urlencode($this->Location);

      //Make the request to Postcode Anywhere and parse the XML returned
      $file = simplexml_load_file($url);

      //Check for an error, if there is one then throw an exception
      if ($file->Columns->Column->attributes()->Name == "Error") 
      {
         //throw new Exception("[ID] " . $file->Rows->Row->attributes()->Error . " [DESCRIPTION] " . $file->Rows->Row->attributes()->Description . " [CAUSE] " . $file->Rows->Row->attributes()->Cause . " [RESOLUTION] " . $file->Rows->Row->attributes()->Resolution);
         return 'error';
      }

      //Copy the data
      if ( !empty($file->Rows) )
      {
         foreach ($file->Rows->Row as $item)
         {
             $this->Data[] = array('Location'=>$item->attributes()->Location,'Easting'=>$item->attributes()->Easting,'Northing'=>$item->attributes()->Northing,'Latitude'=>$item->attributes()->Latitude,'Longitude'=>$item->attributes()->Longitude,'OsGrid'=>$item->attributes()->OsGrid,'Accuracy'=>$item->attributes()->Accuracy);
         }
      }
   }

   function HasData()
   {
      if ( !empty($this->Data) )
      {
         return $this->Data;
      }
      return false;
   }

}

//Example usage
//-------------
//$pa = new Geocoding_UK_Geocode_v2_00 ("AA11-AA11-AA11-AA11","WR2 6NJ");
//$pa->MakeRequest();
//if ($pa->HasData())
//{
//   $data = $pa->HasData();
//   foreach ($data as $item)
//   {
//      echo $item["Location"] . "<br/>";
//      echo $item["Easting"] . "<br/>";
//      echo $item["Northing"] . "<br/>";
//      echo $item["Latitude"] . "<br/>";
//      echo $item["Longitude"] . "<br/>";
//      echo $item["OsGrid"] . "<br/>";
//      echo $item["Accuracy"] . "<br/>";
//   }
//}



?>
