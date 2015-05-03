
<?php 

	$url='http://svcs.ebay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=USC3d911d-ce7a-4086-85c3-66729afcd22&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=XML&outputSelector[1]=SellerInfo&outputSelector[2]=PictureURLSuperSize&outputSelector[3]=StoreInfo&paginationInput.pageNumber='.(int)$_GET['number'];

 
	$j=0;
	$keywords='';
	$from='';
	$to='';
	$new='';
	$used='';
	$verygood='';
	$good='';
	$acceptable='';
	$buyitnow='';
	$auction='';
	$classifiedads='';
	$return='';
	$free='';
	$expedited='';
	$maxhandling='';
	$sort='';
	$condtest='';
	$buyingtest='';
	$results='';
	$toprated='';
	
	$ru=0;
	
	
		
		$keywords=$_GET['key_words'];
		$tempkey=urlencode($keywords);
		$temp="&keywords=".$tempkey;
		$url.=$temp;
		$sort=$_GET['sort'];
		$temp="&sortOrder=".$sort;
		$url.=$temp;
		$results=$_GET['result'];
		$temp="&paginationInput.entriesPerPage=".$results;
		$url.=$temp;
		$from=$_GET['price_from'];
		if($from!='')
		{
			$temp="&itemFilter(".$j.").name=MinPrice&itemFilter(".$j.").value=".$from;
			$url.=$temp;
			$j++;
		}
		$to=$_GET['price_to'];
		if($to!='')
		{
			
			$temp="&itemFilter(".$j.").name=MaxPrice&itemFilter(".$j.").value=".$to;
			$url.=$temp;
			$j++;

		}
		
if (isset($_GET['cond']) && is_array($_GET['cond']))
{
   // let's iterate thru the array
	$i=0;
	
  

   	$condtest="&itemFilter(".$j.").name=Condition";
      if (in_array('new', $_GET['cond']))
      {
      	$condtest.="&itemFilter(".$j.").value(".$i.")=1000";
      	$i++;
      }
      if (in_array('used', $_GET['cond']))
      {
      	$condtest.="&itemFilter(".$j.").value(".$i.")=3000";
      	$i++;
      }
      if (in_array('verygood', $_GET['cond']))
      {
      	$condtest.="&itemFilter(".$j.").value(".$i.")=4000";
      	$i++;
      }
      if (in_array('good', $_GET['cond']))
      {
      	$condtest.="&itemFilter(".$j.").value(".$i.")=5000";
      	$i++;
      }
      if (in_array('acceptable', $_GET['cond']))
      {
      	$condtest.="&itemFilter(".$j.").value(".$i.")=6000";
      	$i++;
      }
      
   $url.=$condtest;
   $j++;
}	

  if (isset($_GET['buying']) && is_array($_GET['buying']))
{
   // let's iterate thru the array
	$i=0;
	
  

   	$buyingtest="&itemFilter(".$j.").name=ListingType";
      if (in_array('buyitnow', $_GET['buying']))
      {
      	$buyingtest.="&itemFilter(".$j.").value(".$i.")=FixedPrice";
      	$i++;
      }
      if (in_array('auction', $_GET['buying']))
      {
      	$buyingtest.="&itemFilter(".$j.").value(".$i.")=Auction";
      	$i++;
      }
      if (in_array('classifiedads', $_GET['buying']))
      {
      	$buyingtest.="&itemFilter(".$j.").value(".$i.")=Classified";
      	$i++;
      }
 
      
   $url.=$buyingtest;
   $j++;
}

	if(isset($_GET['return']))
		{		
			
		$temp="&itemFilter(".$j.").name=ReturnsAcceptedOnly&itemFilter(".$j.").value=true";
		$url.=$temp;
		$j++;
	}

if(isset($_GET['free']))
{
		$temp="&itemFilter(".$j.").name=FreeShippingOnly&itemFilter(".$j.").value=true";
		$url.=$temp;
		$j++;
	}

	if(isset($_GET['expedited']))
		{
		$temp="&itemFilter(".$j.").name=ExpeditedShippingType&itemFilter(".$j.").value=Expedited";
		$url.=$temp;
		$j++;
	}
		$maxhandling=$_GET['max_days'];
		if($maxhandling!='')
		{
			$temp="&itemFilter(".$j.").name=MaxHandlingTime&itemFilter(".$j.").value=".$maxhandling;
			$url.=$temp;
			$j++;

		}
		


$loop=0;
//echo$url;
$fetchxml = simplexml_load_file($url);

if(($fetchxml->paginationOutput->totalEntries)==0)
{
	$global_array= array('ack'=>'No Resuts Found');
}
else{

	
if ($fetchxml->ack == "Success") {

$count=0;
$page_count=0;
$item_count=0;
$temp_array_count=0;

$global_array['ack']= (string)$fetchxml->ack;
$global_array["resultCount"]=(string)$fetchxml->paginationOutput->totalEntries;
$global_array["pageNumber"]=(string)$fetchxml->paginationOutput->pageNumber;
$global_array["itemCount"]=(string)$fetchxml->paginationOutput->entriesPerPage;
foreach($fetchxml->searchResult->item as $item){
	$itemNum = "item".$item_count;
	//echo $item->title."<br><br><br><br>";
	$arr['basicInfo'] = array("title"=>(string)$item->title,"viewItemURL"=>(string)$item->viewItemURL,
			"galleryURL"=>(string)$item->galleryURL,
			"pictureURLSuperSize"=>(string)$item->pictureURLSuperSize,
			"convertedCurrentPrice"=>(string)$item->sellingStatus->convertedCurrentPrice,
			"shippingServiceCost"=>(string)$item->shippingInfo->shippingServiceCost,
			"conditionDisplayName"=>(string)$item->condition->conditionDisplayName,
			"listingType"=>(string)$item->listingInfo->listingType,
			"location"=>(string)$item->location,
			"categoryName"=>(string)$item->primaryCategory->categoryName,
			"topRatedListing"=>(string)$item->topRatedListing);
			
			$arr["sellerInfo"] = array("sellerUserName"=>(string)$item->sellerInfo->sellerUserName,
			"feedbackScore"=>(string)$item->sellerInfo->feedbackScore,
			"positiveFeedbackPercent"=>(string)$item->sellerInfo->positiveFeedbackPercent,
			"feedbackRatingStar"=>(string)$item->sellerInfo->feedbackRatingStar,
			"topRatedSeller"=>(string)$item->sellerInfo->topRatedSeller,
			"sellerStoreName"=>(string)$item->storeInfo->storeName,
			"sellerStoreURL"=>(string)$item->storeInfo->storeURL);
			
			$arr["shippingInfo"] = array("shippingType"=>(string)$item->shippingInfo->shippingType,
									"shipToLocations"=>(string)$item->shippingInfo->shipToLocations,
									"expeditedShipping"=>(string)$item->shippingInfo->expeditedShipping,
									"oneDayShippingAvailable"=>(string)$item->shippingInfo->oneDayShippingAvailable,
									"returnsAccepted"=>(string)$item->returnsAccepted,
									"handlingTime"=>(string)$item->shippingInfo->handlingTime
									);
									
	$global_array[$itemNum] = $arr; 
	$item_count++;
	}
	
	

	}
	 }
$json= json_encode($global_array);
	$json = urldecode(str_replace('//',"",$json));
	$json_fixquotes=urldecode(str_replace('\"',"&quot;",$json));
	$json_nolsashes=stripslashes($json_fixquotes);

	echo $json_nolsashes;
?>

    