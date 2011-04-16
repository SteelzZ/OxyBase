<?php
class MySecureAccount_Lib_Query_ProductInformation
    extends Oxy_Cqrs_Query_AbstractMongo
{
    const PRODUCTS_COLLECTION = 'products';
    
    const MY_MOBILE_PROTECTION_PRODUCT = 'my-mobile-protection'; 
    const MY_MOBILE_THEFT_PROTECTION_PRODUCT = 'my-mobile-theft-protection'; 
    const MY_THEFT_PROTECTION_PRODUCT = 'my-theft-protection'; 
    const MY_ONLINE_BACKUP_PRODUCT = 'my-secure-online-backup'; 
    const MY_INTERNET_SECURITY_GOLD = 'my-internet-security-gold'; 
    const MY_INTERNET_SECURITY_SILVER = 'my-internet-security-silver'; 
    const MY_INTERNET_SECURITY_BRONZE = 'my-internet-security-bronze'; 
    const MY_FREE_ANTIVIRUS = 'my-free-antivirus'; 
    const MY_PC_TUNEUP = 'mypctune-up'; 
    const MY_USB_DRIVE = 'my-secure-usb-drive'; 
    const MY_VIP_SERVICE_AND_SUPPORT = 'my-vip-service-and-support'; 
    const MY_MOBILE_VIP_SERVICE_AND_SUPPORT = 'my-mobile-vip-service-and-support'; 
    const MY_EXTENDED_DOWNLOAD = 'my-extended-download'; 
    const MY_ULTIMATE_PROTECTION = 'my-ultimate-protection'; 
    const MY_MOBILE_PROTECTION_PREMIUM = 'my-mobile-protection-premium'; 
    const MY_ANDROID_PROTECTION_PREMIUM = 'my-android-protection-premium'; 
    const MY_SHIPPING = 'my-shipping'; 
    
	/**
	 * Return account information
	 *   
     * @see Msc_Query_Dto_Builder_AbstractBuilder::getDto()
     */
    public function getDto(array $args = array())
    {
        $this->_initParams($args);
        try{
            $collection = $this->_db->selectCollection(self::PRODUCTS_COLLECTION);
            $query = array(
                "email" => $this->_email,
                "productName" => $this->_productName
            );
            
            if(isset($this->_license)){
                $query['license'] = $this->_license;
            }
            
            $results = $collection->findOne($query);
            return $results;
        } catch (Exception $ex){
            return null;
        } 
    }
}