# OxyBase

## What is OxyBase in overall?

OxyBase is a project which aims to provide a use case how to create system by applying *SOA*, *DDD* centric approach, *CQRS* architecture as *service* architecture and all this would be done in PHP.

## What OxyBase provides?

OxyBase has a framework to aid developers while creating *DDD* centric domains that applies *Event Sourcing* pattern, while implementing *CQS pattern* as an application architecture, while publishing application's services/interface over web service, while managing development life-cycle, while deploying application. It also has out of the box ready project structure.

## When you should use OxyBase?

 * If you have complex domain (very complex business rules)
 * If your project is not data centric
 * If you want to create a back-end application with PHP and integrate it into existing system(does not matter what platform it's built on)
 * If you have at least 1 experienced developer in your team
 * If you know what is OOP
 * If you know what is DDD 
 * If it's likely that your entities will have to handle concurrent requests

## When you should not use OxyBase?

You can use it for any kind of project, but more likely that you will not benefit from packages and features it provides. 
Although, if you will try to implement some small, not complex application by using all those "things" described above - most likely that you will fail, because it will be just to much work and very little benefit.
[why to avoid cqrs](http://www.udidahan.com/2011/04/22/when-to-avoid-cqrs/) read this before you start redesigning anything.  

## History

###### 0.1.0 - In Development, unstable

# Overview
This article is tend to be a short guide how to get things working. This is live document and I will keep updating it.

## Oxy library
If you have a framework that you already are working with and you just want to start implementing your business logic (your domain) in DDD way then you just need to download Oxy library and add it to your include path. Once you have it just pick what you need, check documentation and start using.
(Sorry reference guide is not finished yet, some packages are documented but not all of them)  

## OxyBase
Another option is to download whole OxyBase framework that has predefined structure, extract from your current application a part that you want to rebuild in CQRS, DDD way and build it by using OxyBase. And then simply connect two apps (how to do that we will discuss later). 

## Intro
Ok, let's build some application. And let's do something more complex then just "hello world", what about "Account" mini system ? Most of our applications has "Account" feature, right? So let's create some basic "Account" system that would serve as an example how things work in OxyBase.
I will assume that you have RabbitMQ and MongoDB installed and ready for use. Of course you will be able to use any messaging system or storage engine it's just a matter of creating interface implementations, but at the moment I will stick to this configuration, because it works best for me (why we will discuss in later posts).

## Setup OxyBase
Clone this repo. Extract the content in your workspace. You should have structure like that:
```
/documentation
/html
/project
  /apps
  /build
  /config
  /library
/public 
/tests
/tmp
```

Once you got it make a copy of *${app.path}.project/build/build.properties* file and amend it. At the moment file has the following properties: 
```
[System]
;path to our app
app.path=/home/root/workspace/OxyBase/trunk/

;path to tmp dir
tmp.dir=/home/root/workspace/OxyBase/trunk/tmp/ 

;URL to our BC(Bounded Context) API end point
api.url=r100.account.local.oxybase.com 

;HTTPS or HTTP
protocol=http 

;environment development|staging|production
environment=development

[Application]
;what bounded contexts we are hosting within this instance
application.domains=Account 

[Mongo connection]
mongo.connection.string=mongodb://localhost:27017
mongo.replica.set=false

[Database names]
account.eventstore.dbname = r100_account_events
account.reporting.dbname = r100_account

[Messaging options]
;should our consumer become a listener after he finished consuming messages or not? Because sometimes you want just to spawn some consumers to help your current ones and once queue is empty just kill them
messaging.consumer.become.listener = true 

;consumer name prefix
messaging.consumer.name.prefix = consumer-root- 

;connection string to messaging system, *txt-mode=true* defines that it is transactional queue
messaging.broker.host = tcp://guest:guest@192.168.1.245:5672/?txt-mode=true

;this is service prefix that is used by daemon to get from container command handlers builder (we will discuss it in more details later)
messaging.borker.command.handler.service.prefix = oxyCqrsCommandHandlerBuilder 

;connection string to messaging system to concrete queue
messaging.broker.account.connection = tcp://guest:guest@192.168.1.245:5672/?txt-mode=true|queue-id=queue.account

;auto-commit messages or leave it to client
messaging.broker.account.autocommit = true 
```

Now open console and navigate to your *${app.path}/project/build* folder and execute the following command (replace my.build.properties name with the one you have created earlier):
```
phing -f dev-build.xml -DpropertyFile=config/my.build.properties prepare
```
This will prepare your project. 

Then execute the following command:
```
phing -f dev-build.xml -DpropertyFile=config/my.build.properties rebuild-di
```
This will rebuild DI container.

## Let's test it
Now set-up a virtual host and set a document root to *${app.path}/public*.
Open your browser and navigate to *protocol*+*api.url*/api/account/account/general/v1r0/wsdl . You should see your account web service WSDL.

Now open console and navigate to *${app.path}/tests* and execute the following commands:
```
$sudo rabbitmqctl list_queues
#output should be
Listing queues ...
queue.account	0
...done.

$php TestClient.php
#there should be no output

$sudo rabbitmqctl list_queues
#output should be like this (it means one command is waiting to be processed)
Listing queues ...
queue.account	1
...done.
```

No let's turn on consumer that would process our command. Navigate to *${app.path}/project/apps/Account/daemon* folder and execute the following command (set required permissions if required):
```
$./consumer-daemon.php ${app.path}/project/build/config/my.build.properties 1 queue.account Account

$sudo rabbitmqctl list_consumers
#outpur should be like
Listing consumers ...
queue.account	<rabbit@ubuntu.2847.0>	consumer-root-1	true
...done.
```

This will start daemon and daemon will consume a message.
In console execute the following commands to see if it actually consumed message and account was created.
```
$sudo rabbitmqctl list_queues
#output should be like this (it means no commands left in queue)
Listing queues ...
queue.account	0
...done.

$mongo
> use r100_account
> db.accounts.find()
#output should be like this
{ "_id" : "afa6f4a5-7f96-6074-b9ab-40f1a12e0818", "primaryEmail" : "wazonikas+201103311957@gmail.com", "password" : "7ab515d12bd2cf431745511ac4ee13fed15ab578", "date" : "2011-04-22 15:12:20", "lastResurectDate" : "", "state" : "initialized", "personalInformation" : { "firstName" : "Tomas", "lastName" : "Bartkus", "dateOfBirth" : "1984-11-26", "gender" : "male", "nickName" : "Meilas", "mobileNumber" : "0037068123462", "homeNumber" : "00370612317462", "additionalInformation" : "Something" }, "deliveryInformation" : { "country" : "Lithuania", "city" : "5", "postCode" : "5", "street" : "Street", "houseNumber" : "114", "secondAddressLine" : "Street2", "thirdAddressLine" : "Street3", "additionalInformation" : "Info" }, "settings" : { "locale" : { "country" : { "code" : "GB", "title" : "United Kingdom" }, "language" : { "code" : "DK", "title" : "Denmark" } }, "acceptance" : { "spam" : false, "terms" : true }, "emailingTemplate" : "default" }, "activationKey" : "835a2760-5c66-8404-317e-06ba46a07543", "loginState" : "logged-out", "generatedPassword" : "", "isAutoGenerated" : false }

```
