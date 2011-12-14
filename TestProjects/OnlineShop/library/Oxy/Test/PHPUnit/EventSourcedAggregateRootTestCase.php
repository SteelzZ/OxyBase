<?php
namespace Oxy\Test\PHPUnit;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\EventStore\Event\StorableEventInterface;
use Oxy\Test\PHPUnit\Exception;
use Oxy\Domain\AggregateRoot\ChildEntityInterface;
use Oxy\EventStore\Event\StorableEventsCollection;
use Oxy\EventStore\Event\StorableEvent;
use Oxy\Guid;
use Oxy\EventStore\Event\ArrayableAbstract;

abstract class EventSourcedAggregateRootTestCase extends \PHPUnit_Framework_TestCase
{    
    /**
     * Assert if collection contains required events
     * 
     * @param EventProviderInterface $changes
     * @param array $expected
     * @param boolean $outPutgeneratedEvents
     */
    public static function assertEvents(
        EventProviderInterface $aggregateRoot, 
        array $expected,
        $outputGeneratedEvents = false
    )
    {   
        if($outputGeneratedEvents){
            var_dump($aggregateRoot->getChanges());
            var_dump($aggregateRoot->getChanges()->count());
            var_dump(count($expected));
        }
        parent::assertEquals(count($expected), $aggregateRoot->getChanges()->count());
        foreach($aggregateRoot->getChanges() as $index => $event){
            if($event instanceof StorableEventInterface){
                $currentExpected = array_shift($expected);                
                $eventName = ArrayableAbstract::extractEventName(get_class($event->getEvent()));
                
                // We need to assert event data
                if(isset($currentExpected[$eventName]) && is_array($currentExpected[$eventName])){
                    parent::assertEquals(
                        array_shift(array_keys($currentExpected)), 
                        $eventName
                    );
                    
                    foreach($currentExpected[$eventName] as $property => $propertyValue){
                        $method = 'get' . ucfirst($property);
                        $realEvent = $event->getEvent();
                        
                        parent::assertEquals(
                            $propertyValue, 
                            $realEvent->$method()
                        );
                    }
                } else {
                    parent::assertEquals(
                        $currentExpected[0], 
                        $eventName
                    );
                }
            } else {
                throw new Exception(
                    sprintf('Event must implement StorableEventInterface interface')
                );
            }
        }     
    }
    
    /**
     * Assert if AR has required child entities and assert properties
     * 
     * @param EventProviderInterface $changes
     * @param array $expected
     */
    public static function assertChildEntities(
        EventProviderInterface $aggregateRoot, 
        array $expected
    )
    {   
        parent::assertEquals(count($expected), $aggregateRoot->getChildEntities()->count());
        foreach($aggregateRoot->getChildEntities() as $entityGuid => $entity){
            if($entity instanceof ChildEntityInterface){
                $entityClass = get_class($entity);
                parent::assertContains($entityClass, $expected);
            } else {
                throw new Exception(
                    sprintf('Child Entity must implement ChildEntityInterface interface')
                );
            }
        }     
    }
    
    /**
     * @param EventProviderInterface $aggregateRoot
     * @param array $eventsToLoad
     */
    protected function _prepareAggregateRoot(
        EventProviderInterface $aggregateRoot, 
        array $eventsToLoad
    )
    {
        $collection = new StorableEventsCollection();
        foreach ($eventsToLoad as $guid => $events){
            foreach ($events as $eventName => $event){
                $collection->addEvent(
                    new StorableEvent(
                        new Guid($guid),
                        $event
                    )
                );
            }
        }
        
        $aggregateRoot->loadEvents($collection);                
    }
}