<?php

class ApplicationContainer extends sfServiceContainer
{
  protected $shared = array();

  public function __construct()
  {
    parent::__construct($this->getDefaultParameters());
  }

  protected function getAccountWebServiceAccountGeneralV1r0ServiceService()
  {
    if (isset($this->shared['accountWebServiceAccountGeneralV1r0Service'])) return $this->shared['accountWebServiceAccountGeneralV1r0Service'];

    $instance = new AccountAccountGeneral($this->getService('accountLibRemoteInvokerReadService'), $this->getService('accountLibRemoteInvokerWriteService'));

    return $this->shared['accountWebServiceAccountGeneralV1r0Service'] = $instance;
  }

  protected function getAccountWebServiceAccountGeneralV1r0ServiceMetaInfoService()
  {
    if (isset($this->shared['accountWebServiceAccountGeneralV1r0ServiceMetaInfo'])) return $this->shared['accountWebServiceAccountGeneralV1r0ServiceMetaInfo'];

    $instance = new Account_WebService_Account_General_V1r0_ServiceMetaInfo();

    return $this->shared['accountWebServiceAccountGeneralV1r0ServiceMetaInfo'] = $instance;
  }

  protected function getAccountLibRemoteInvokerReadServiceService()
  {
    if (isset($this->shared['accountLibRemoteInvokerReadService'])) return $this->shared['accountLibRemoteInvokerReadService'];

    $instance = new Account_Lib_Remote_Invoker_ReadService($this->getService('accountLibServiceReadAccountManagementService'));

    return $this->shared['accountLibRemoteInvokerReadService'] = $instance;
  }

  protected function getAccountLibRemoteInvokerWriteServiceService()
  {
    if (isset($this->shared['accountLibRemoteInvokerWriteService'])) return $this->shared['accountLibRemoteInvokerWriteService'];

    $instance = new Account_Lib_Remote_Invoker_WriteService($this->getService('accountLibServiceWriteAccountManagementService'), $this->getService('accountLibServiceWriteAuthService'), $this->getService('accountLibServiceWriteProductsManagementService'));

    return $this->shared['accountLibRemoteInvokerWriteService'] = $instance;
  }

  protected function getAccountLibServiceWriteAccountManagementServiceService()
  {
    if (isset($this->shared['accountLibServiceWriteAccountManagementService'])) return $this->shared['accountLibServiceWriteAccountManagementService'];

    $instance = new Account_Lib_Service_Write_AccountManagementService($this->getService('oxyCqrsQueueAccount'));

    return $this->shared['accountLibServiceWriteAccountManagementService'] = $instance;
  }

  protected function getAccountLibServiceWriteAuthServiceService()
  {
    if (isset($this->shared['accountLibServiceWriteAuthService'])) return $this->shared['accountLibServiceWriteAuthService'];

    $instance = new Account_Lib_Service_Write_AuthService($this->getService('oxyCqrsQueueAccount'));

    return $this->shared['accountLibServiceWriteAuthService'] = $instance;
  }

  protected function getAccountLibServiceReadAccountManagementServiceService()
  {
    if (isset($this->shared['accountLibServiceReadAccountManagementService'])) return $this->shared['accountLibServiceReadAccountManagementService'];

    $instance = new Account_Lib_Service_Read_AccountManagementService($this->getService('accountLibQueryAccountInformation'));

    return $this->shared['accountLibServiceReadAccountManagementService'] = $instance;
  }

  protected function getAccountLibServiceReadProductManagementServiceService()
  {
    if (isset($this->shared['accountLibServiceReadProductManagementService'])) return $this->shared['accountLibServiceReadProductManagementService'];

    $instance = new Account_Lib_Service_Read_ProductManagementService($this->getService('accountLibQueryProductInformation'));

    return $this->shared['accountLibServiceReadProductManagementService'] = $instance;
  }

  protected function getAccountLibServiceReadDeviceManagementServiceService()
  {
    if (isset($this->shared['accountLibServiceReadDeviceManagementService'])) return $this->shared['accountLibServiceReadDeviceManagementService'];

    $instance = new Account_Lib_Service_Read_DeviceManagementService($this->getService('accountLibQueryDeviceInformation'));

    return $this->shared['accountLibServiceReadDeviceManagementService'] = $instance;
  }

  protected function getAccountLibServiceWriteDeviceManagementServiceService()
  {
    if (isset($this->shared['accountLibServiceWriteDeviceManagementService'])) return $this->shared['accountLibServiceWriteDeviceManagementService'];

    $instance = new Account_Lib_Service_Write_DeviceManagementService($this->getService('oxyCqrsQueueAccount'));

    return $this->shared['accountLibServiceWriteDeviceManagementService'] = $instance;
  }

  protected function getAccountLibServiceWriteProductsManagementServiceService()
  {
    if (isset($this->shared['accountLibServiceWriteProductsManagementService'])) return $this->shared['accountLibServiceWriteProductsManagementService'];

    $instance = new Account_Lib_Service_Write_ProductsManagementService($this->getService('oxyCqrsQueueAccount'));

    return $this->shared['accountLibServiceWriteProductsManagementService'] = $instance;
  }

  protected function getAccountLibQueryAccountInformationService()
  {
    if (isset($this->shared['accountLibQueryAccountInformation'])) return $this->shared['accountLibQueryAccountInformation'];

    $instance = new Account_Lib_Query_AccountInformation($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibQueryAccountInformation'] = $instance;
  }

  protected function getAccountLibQueryProductInformationService()
  {
    if (isset($this->shared['accountLibQueryProductInformation'])) return $this->shared['accountLibQueryProductInformation'];

    $instance = new Account_Lib_Query_ProductInformation($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibQueryProductInformation'] = $instance;
  }

  protected function getAccountLibQueryDeviceInformationService()
  {
    if (isset($this->shared['accountLibQueryDeviceInformation'])) return $this->shared['accountLibQueryDeviceInformation'];

    $instance = new Account_Lib_Query_DeviceInformation($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibQueryDeviceInformation'] = $instance;
  }

  protected function getOxyCqrsCommandHandlerBuilderAccountService()
  {
    if (isset($this->shared['oxyCqrsCommandHandlerBuilderAccount'])) return $this->shared['oxyCqrsCommandHandlerBuilderAccount'];

    $instance = new Oxy_Cqrs_Command_Handler_Builder_Standard(array('accountLibCommandHandlerDoSetupAccount' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoSetupAccount')), 'accountLibCommandHandlerDoActivateAccount' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoActivateAccount')), 'accountLibCommandHandlerDoLogin' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoLogin')), 'accountLibCommandHandlerDoLogout' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoLogout')), 'accountLibCommandHandlerDoRemindPassword' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoRemindPassword')), 'accountLibCommandHandlerDoAddProductsInAccount' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoAddProductsInAccount')), 'accountLibCommandHandlerDoAddDevicesInAccount' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoAddDevicesInAccount')), 'accountLibCommandHandlerDoChangePassword' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoChangePassword')), 'accountLibCommandHandlerDoRemindEmailActivationKey' => array('callback' => array(0 => $this, 1 => 'getService'), 'parameters' => array(0 => 'accountLibCommandHandlerDoRemindEmailActivationKey'))));

    return $this->shared['oxyCqrsCommandHandlerBuilderAccount'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoSetupAccountService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoSetupAccount'])) return $this->shared['accountLibCommandHandlerDoSetupAccount'];

    $instance = new Account_Lib_Command_Handler_DoSetupAccount($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoSetupAccount'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoActivateAccountService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoActivateAccount'])) return $this->shared['accountLibCommandHandlerDoActivateAccount'];

    $instance = new Account_Lib_Command_Handler_DoActivateAccount($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoActivateAccount'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoLoginService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoLogin'])) return $this->shared['accountLibCommandHandlerDoLogin'];

    $instance = new Account_Lib_Command_Handler_DoLogin($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoLogin'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoLogoutService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoLogout'])) return $this->shared['accountLibCommandHandlerDoLogout'];

    $instance = new Account_Lib_Command_Handler_DoLogout($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoLogout'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoRemindPasswordService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoRemindPassword'])) return $this->shared['accountLibCommandHandlerDoRemindPassword'];

    $instance = new Account_Lib_Command_Handler_DoRemindPassword($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoRemindPassword'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoAddProductsInAccountService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoAddProductsInAccount'])) return $this->shared['accountLibCommandHandlerDoAddProductsInAccount'];

    $instance = new Account_Lib_Command_Handler_DoAddProductsInAccount($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoAddProductsInAccount'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoAddDevicesInAccountService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoAddDevicesInAccount'])) return $this->shared['accountLibCommandHandlerDoAddDevicesInAccount'];

    $instance = new Account_Lib_Command_Handler_DoAddDevicesInAccount($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoAddDevicesInAccount'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoChangePasswordService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoChangePassword'])) return $this->shared['accountLibCommandHandlerDoChangePassword'];

    $instance = new Account_Lib_Command_Handler_DoChangePassword($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoChangePassword'] = $instance;
  }

  protected function getAccountLibCommandHandlerDoRemindEmailActivationKeyService()
  {
    if (isset($this->shared['accountLibCommandHandlerDoRemindEmailActivationKey'])) return $this->shared['accountLibCommandHandlerDoRemindEmailActivationKey'];

    $instance = new Account_Lib_Command_Handler_DoRemindEmailActivationKey($this->getService('oxyDomainRepositoryEventStore'));

    return $this->shared['accountLibCommandHandlerDoRemindEmailActivationKey'] = $instance;
  }

  protected function getAccountEventsPublisherService()
  {
    if (isset($this->shared['accountEventsPublisher'])) return $this->shared['accountEventsPublisher'];

    $instance = new Oxy_EventStore_EventPublisher(array('eventsHandlersMap' => array('NewAccountCreated' => array(0 => 'accountLibEventHandlerAccountAccountCreateNewAccountInDatabase', 1 => 'accountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmail'), 'AccountActivated' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase'), 'AccountDeactivated' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase', 1 => 'accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase'), 'AccountResurected' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountDetailsInDatabase'), 'UserLoggedInSuccessfully' => array(0 => 'accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase'), 'UserLoggedOutSuccessfully' => array(0 => 'accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase'), 'NewPasswordGeneratedForAccount' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase', 1 => 'accountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmail'), 'PasswordChanged' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase'), 'ProductsAddedToAccount' => array(0 => 'accountLibEventHandlerAccountAccountAddNewAccountProductsInDatabase', 1 => 'accountLibEventHandlerAccountAccountCreateNewProductsInDatabase'), 'DevicesAddedToAccount' => array(0 => 'accountLibEventHandlerAccountAccountCreateNewDevicesInDatabase', 1 => 'accountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabase')), 'eventHandlers' => array('accountLibEventHandlerAccountAccountCreateNewAccountInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountCreateNewAccountInDatabase')), 'accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase')), 'accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase')), 'accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase')), 'accountLibEventHandlerAccountAccountAddNewAccountProductsInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountAddNewAccountProductsInDatabase')), 'accountLibEventHandlerAccountAccountCreateNewProductsInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountCreateNewProductsInDatabase')), 'accountLibEventHandlerAccountAccountCreateNewDevicesInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountCreateNewDevicesInDatabase')), 'accountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabase')), 'accountLibEventHandlerAccountAccountUpateConfigurationWithActionResultsInDatabase' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountUpateConfigurationWithActionResultsInDatabase')), 'accountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmail' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmail')), 'accountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmail' => array('callback' => array(0 => $this, 1 => 'getService'), 'param' => array(0 => 'accountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmail')))));

    return $this->shared['accountEventsPublisher'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountCreateNewAccountInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountCreateNewAccountInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountCreateNewAccountInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_CreateNewAccountInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountCreateNewAccountInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountUpdateAccountStatusInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_UpdateAccountStatusInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountUpdateAccountStatusInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_ChangeAccountAuthStatusDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountChangeAccountAuthStatusDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_UpdateAccountPasswordInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountUpdateAccountPasswordInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountAddNewAccountProductsInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountAddNewAccountProductsInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountAddNewAccountProductsInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_AddNewAccountProductsInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountAddNewAccountProductsInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_AddNewAccountDevicesInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountAddNewAccountDevicesInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountCreateNewProductsInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountCreateNewProductsInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountCreateNewProductsInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_CreateNewProductsInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountCreateNewProductsInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountCreateNewDevicesInDatabaseService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountCreateNewDevicesInDatabase'])) return $this->shared['accountLibEventHandlerAccountAccountCreateNewDevicesInDatabase'];

    $instance = new Account_Lib_EventHandler_Account_Account_CreateNewDevicesInDatabase($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountCreateNewDevicesInDatabase'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmailService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmail'])) return $this->shared['accountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmail'];

    $instance = new Account_Lib_EventHandler_Account_Account_NotificationRequestToSendActivationEmail($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountNotificationRequestToSendActivationEmail'] = $instance;
  }

  protected function getAccountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmailService()
  {
    if (isset($this->shared['accountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmail'])) return $this->shared['accountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmail'];

    $instance = new Account_Lib_EventHandler_Account_Account_NotificationRequestToSendPasswordReminderEmail($this->getService('mongo'), 'account_r100_mysecureaccount');

    return $this->shared['accountLibEventHandlerAccountAccountNotificationRequestToSendPasswordReminderEmail'] = $instance;
  }

  protected function getAccountDomainEventsStorageService()
  {
    if (isset($this->shared['accountDomainEventsStorage'])) return $this->shared['accountDomainEventsStorage'];

    $instance = new Oxy_EventStore_Storage_MongoDb($this->getService('mongo'), 'account_r100_mysecureaccount_events');

    return $this->shared['accountDomainEventsStorage'] = $instance;
  }

  protected function getAccountEventStoreService()
  {
    if (isset($this->shared['accountEventStore'])) return $this->shared['accountEventStore'];

    $instance = new Oxy_EventStore($this->getService('accountDomainEventsStorage'));

    return $this->shared['accountEventStore'] = $instance;
  }

  protected function getOxyDomainRepositoryEventStoreService()
  {
    if (isset($this->shared['oxyDomainRepositoryEventStore'])) return $this->shared['oxyDomainRepositoryEventStore'];

    $instance = new Oxy_Domain_Repository_EventStore($this->getService('accountEventStore'), $this->getService('accountEventsPublisher'));

    return $this->shared['oxyDomainRepositoryEventStore'] = $instance;
  }

  protected function getMongoService()
  {
    if (isset($this->shared['mongo'])) return $this->shared['mongo'];

    $instance = new Mongo('mongodb://localhost:27017', array('replicaSet' => false));

    return $this->shared['mongo'] = $instance;
  }

  protected function getOxyQueueAdapterRabbitmqAccountService()
  {
    if (isset($this->shared['oxyQueueAdapterRabbitmqAccount'])) return $this->shared['oxyQueueAdapterRabbitmqAccount'];

    $instance = new Oxy_Queue_Adapter_Rabbitmq('tcp://guest:guest@192.168.1.245:5672/?txt-mode=true|queue-id=queue.account');

    return $this->shared['oxyQueueAdapterRabbitmqAccount'] = $instance;
  }

  protected function getOxyCqrsQueueAccountService()
  {
    if (isset($this->shared['oxyCqrsQueueAccount'])) return $this->shared['oxyCqrsQueueAccount'];

    $instance = new Oxy_Cqrs_Queue($this->getService('oxyQueueAdapterRabbitmqAccount'), array('auto-commit' => true));

    return $this->shared['oxyCqrsQueueAccount'] = $instance;
  }

  protected function getDefaultParameters()
  {
    return array(
      'api.url' => 'r100.msa.local.oxybase.com',
      'protocol' => 'http',
      'mysecureaccount.reporting.dbname' => '${account.reporting.dbname}',
    );
  }
}
