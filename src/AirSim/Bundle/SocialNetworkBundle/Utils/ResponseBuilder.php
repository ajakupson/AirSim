<?php

namespace AirSim\Bundle\SocialNetworkBundle\Utils;


class ResponseBuilder
{
    public static function BuildResponse($senderData_ = null, $receiverData_ = null, $receiverId, $receiverUsername,
                                         $eventData_, $sessionData)
    {
        $senderData = $senderData_;
        $receiverData = $receiverData_;
        $eventData = $eventData_;

        if($senderData == null)
        {
            $senderData = array
            (
                'senderId' => $sessionData['userInfo']['id'],
                'senderUsername' => $sessionData['userInfo']['username'],
                'senderName' => $sessionData['userInfo']['firstName'],
                'senderFamily' => $sessionData['userInfo']['lastName'],
                'senderWebPic' => $sessionData['userInfo']['webPic']
            );
        }

        if($receiverData == null)
        {
            $receiverData = array
            (
                'receiverId' => $receiverId,
                'receiverUsername' => $receiverUsername
            );
        }

        $response = array
        (
            'senderData' => $senderData,
            'receiverData' => $receiverData,
            'eventData' => $eventData
        );

        $jsonResponse = json_encode($response);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://127.0.0.1:5555");
        $socket->send(json_encode($jsonResponse));

        return $response;
    }
}