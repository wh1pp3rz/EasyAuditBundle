<?php

/*
 * This file is part of the XiideaEasyAuditBundle package.
 *
 * (c) Xiidea <http://www.xiidea.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xiidea\EasyAuditBundle\Resolver;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\EventDispatcher\Event;
use Xiidea\EasyAuditBundle\Events\DoctrineDocumentEvent;
use Xiidea\EasyAuditBundle\Events\DoctrineEvents;

/** Custom Event Resolver Example Class */
class DocumentEventResolver implements EventResolverInterface
{
    protected $eventShortName;

    /**
     * @var  $event DoctrineDocumentEvent
     */
    protected $event;

    protected $document;

    protected $eventName;

    private $identity = ['', ''];

    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @param Event|DoctrineDocumentEvent $event
     * @param $eventName
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getEventLogInfo(Event $event, $eventName)
    {
        if (!$event instanceof DoctrineDocumentEvent) {
            return null;
        }

        $this->initialize($event, $eventName);

        if ($this->isUpdateEvent() && null === $this->getChangeSets($this->document)) {
            return null;
        }

        $reflectionClass = $this->getReflectionClassFromObject($this->document);

        return [
            'description' => $this->getDescription($reflectionClass->getShortName()),
            'type'        => $this->getEventType($reflectionClass->getShortName())
        ];
    }

    protected function getSingleIdentity()
    {
        foreach ($this->event->getIdentity() as $field => $value) {
            return [$field, $value];
        }

        return ['', ''];
    }

    /**
     * @param DoctrineDocumentEvent $event
     * @param string $eventName
     */
    private function initialize(DoctrineDocumentEvent $event, $eventName)
    {
        $this->eventShortName = null;
        $this->eventName = $eventName;
        $this->event = $event;
        $this->document = $event->getLifecycleEventArgs()->getDocument();
        $this->identity = $this->getSingleIdentity();
    }

    private function getIdField()
    {
        return $this->identity[0];
    }

    private function getIdValue()
    {
        return $this->identity[1];
    }

    protected function getChangeSets($document)
    {
        return $this->isUpdateEvent() ? $this->getUnitOfWork()->getDocumentChangeSet($document) : null;
    }

    protected function isUpdateEvent()
    {
        return $this->getEventShortName() === 'updated';
    }

    /**
     * @param string $typeName
     * @return string
     */
    protected function getEventType($typeName)
    {
        return $typeName . ' ' . $this->getEventShortName();
    }

    /**
     * @param string $shortName
     * @return string
     */
    protected function getDescription($shortName)
    {
        return sprintf(
            '%s has been %s with %s = "%s"',
            $shortName,
            $this->getEventShortName(),
            $this->getIdField(),
            $this->getIdValue()
        );
    }

    /**
     * @return string
     */
    protected function getEventShortName()
    {
        if (null === $this->eventShortName) {
            $this->eventShortName = DoctrineEvents::getShortEventType($this->getName());
        }

        return $this->eventShortName;
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->eventName;
    }

    /**
     * @param $object
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    protected function getReflectionClassFromObject($object)
    {
        return new \ReflectionClass(ClassUtils::getClass($object));
    }

    protected function getUnitOfWork()
    {
        return $this->getDoctrine()->getManager()->getUnitOfWork();
    }

    /**
     * @return ManagerRegistry|object
     */
    protected function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @required
     * @param ManagerRegistry $doctrine
     */
    public function setDoctrine(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
}
