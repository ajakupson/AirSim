<?php
/**
 * Created by Andrei Jakupson
 * Date: 8.03.15
 * Time: 14:17
 */

namespace AirSim\Bundle\CoreBundle\DataTransferObjects;


class ContactUpdatesDTO
{
    /**
     * @var UserWebDataDTO[]
     */
    private $incomingFriendRequestsUpdates;

    /**
     * @var UserWebDataDTO[]
     */
    private $outgoingFriendRequestsUpdates;

    //Getters / setters
    /**
     * @param \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO[] $incomingFriendRequestsUpdates
     */
    public function setIncomingFriendRequestsUpdates($incomingFriendRequestsUpdates)
    {
        $this->incomingFriendRequestsUpdates = $incomingFriendRequestsUpdates;
    }

    /**
     * @return \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO[]
     */
    public function getIncomingFriendRequestsUpdates()
    {
        return $this->incomingFriendRequestsUpdates;
    }

    /**
     * @param \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO[] $outgoingFriendRequestsUpdates
     */
    public function setOutgoingFriendRequestsUpdates($outgoingFriendRequestsUpdates)
    {
        $this->outgoingFriendRequestsUpdates = $outgoingFriendRequestsUpdates;
    }

    /**
     * @return \AirSim\Bundle\CoreBundle\DataTransferObjects\UserWebDataDTO[]
     */
    public function getOutgoingFriendRequestsUpdates()
    {
        return $this->outgoingFriendRequestsUpdates;
    }


}