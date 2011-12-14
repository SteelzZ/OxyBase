<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Domain\AggregateRoot;
use Oxy\Domain\EntityInterface;
use Oxy\Domain\AggregateRoot\AggregateRootInterface;

interface ChildEntityInterface
    extends EntityInterface
{
    /**
     * @return AggregateRootInterface
     */
    public function getAggregateRoot();
}