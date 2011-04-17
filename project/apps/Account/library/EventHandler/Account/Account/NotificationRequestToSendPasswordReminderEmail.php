<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage EventHandler
 */
class Account_Lib_EventHandler_Account_Account_NotificationRequestToSendPasswordReminderEmail
    extends Account_Lib_EventHandler_Logger 
    implements Oxy_EventStore_EventHandler_EventHandlerInterface
{
    /**
     * @var Zend_Soap_Client
     */
    protected $_notificationSoapClient;
    
    /**
     * @param Mongo $db
     * @param string $dbName
     * @param Zend_Soap_Client $notificationSoapClient
     */
    public function __construct(
        Mongo $db, 
        $dbName, 
        Zend_Soap_Client $notificationSoapClient
    )
    {
        $this->_db = $db->selectDB($dbName);
        $this->_dbName = $dbName;
        $this->_notificationSoapClient = $notificationSoapClient;
    }
    
	/**
     * @see Oxy_EventStore_EventHandler_EventHandlerInterface::handle()
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event)
    {                
        try{
            $settings = $event->getSettings();
            $template = isset($settings['emailingTemplate']) ? $settings['emailingTemplate'] : 'default';
            $this->_notificationSoapClient->requestToSendPasswordReminderEmail(
                $event->getEmail(),
                $event->getPassword(),
                $settings['locale']['language']['code'], 
                $settings['originalReferrer']['brand'], 
                $settings['originalReferrer']['partner'], 
                $settings['originalReferrer']['campaign'], 
                $template
            );
        } catch (Exception $ex){
            $this->_log(
                get_class($this), 
                array(
                    'message' => $ex->getMessage(),
                    'trace' => $ex->getTrace(),
                    'code' => $ex->getCode(),
                ), 
                parent::ERROR_LEVEL_EXCEPTION
            );
        }   
    } 
}
