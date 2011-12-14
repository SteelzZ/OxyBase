<?php
namespace Shop\Tests\Domain\Account\AggregateRoots; 

use Oxy\Test\PHPUnit\EventSourcedAggregateRootTestCase;
use Oxy\Guid;

use Shop\Domain\Account\AggregateRoots\Account; 
use Shop\Domain\Account\ValueObjects\EmailAddress; 

class AccountTest extends EventSourcedAggregateRootTestCase
{
	const ACCOUNT_GUID = 'my-account-guid';
	const ACCOUNT_PRIMARY_EMAIL = 'a@a.com';
	
	/**
     * @var Guid
     */
    private $_accountGuid;
    
    /**
     * @var EmailAddress
     */
    private $_primaryEmail;
    
	/**
     * Prepares the environment before running a test.
     * Just setup everything we will need
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_accountGuid = new Guid(self::ACCOUNT_GUID);
        $this->_primaryEmail = new EmailAddress(self::ACCOUNT_PRIMARY_EMAIL);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }
    
    /**
     * Should setup new account
     * Test by itself
     */
    public function testShouldSetupNewAccount()
    {        
    	// Create an instance of AR, this will be an empty, fresh instance of AR
    	// why there are two __constructor params ill explain in other posts
        $account = new Account(
            $this->_accountGuid,
            (string)$this->_primaryEmail
        );
             
        // Execute a behaviour               
        $account->setup(
            $this->_primaryEmail
        );
        
        // Use parent class assert method to check what events has been generated
        // This is bare minimum you need
        // in later test you will see how you can test if event has correct data
        $this->assertEvents(
            $account,
            array(
                array('NewAccountCreated')
            )
        );
    }
}
