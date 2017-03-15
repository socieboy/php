<?php

namespace PubNub\Endpoints\ChannelGroups;

use PubNub\Endpoints\Endpoint;
use PubNub\Enums\PNHttpMethod;
use PubNub\Enums\PNOperationType;
use PubNub\Exceptions\PubNubValidationException;
use PubNub\Models\Consumer\ChannelGroup\PNChannelGroupsAddChannelResult;
use PubNub\PubNubUtil;

class AddChannelToChannelGroup extends Endpoint
{
    const PATH = "/v1/channel-registration/sub-key/%s/channel-group/%s";

    /** @var string[] */
    protected $channels = [];

    /** @var  string */
    protected $group;

    /**
     * @param string|array $ch
     * @return $this
     */
    public function channels($ch)
    {
        $this->channels = PubNubUtil::extendArray($this->channels, $ch);

        return $this;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function group($group)
    {
        $this->group = $group;

        return $this;
    }

    protected function validateParams()
    {
        $this->validateSubscribeKey();

        if (count($this->channels) === 0) {
            throw new PubNubValidationException("Channels missing");
        }

        if (strlen($this->group) === 0) {
            throw new PubNubValidationException("Channel group missing");
        }
    }

    /**
     * @param array $json Decoded json
     * @return PNChannelGroupsAddChannelResult
     */
    protected function createResponse($json)
    {
        return new PNChannelGroupsAddChannelResult();
    }

    /**
     * @return int
     */
    protected function getOperationType()
    {
        return PNOperationType::PNAddChannelsToGroupOperation;
    }

    /**
     * @return bool
     */
    protected function isAuthRequired()
    {
        return True;
    }

    /**
     * @return null|string
     */
    protected function buildData()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function buildPath()
    {
        return sprintf(
            static::PATH,
            $this->pubnub->getConfiguration()->getSubscribeKey(),
            $this->group
        );
    }

    /**
     * @return array
     */
    protected function customParams()
    {
        $params = $this->defaultParams();

        $params['add'] = PubNubUtil::joinItems($this->channels);

        return $params;
    }

    /**
     * @return string PNHttpMethod
     */
    protected function httpMethod()
    {
        return PNHttpMethod::GET;
    }

    /**
     * @return int
     */
    protected function getRequestTimeout()
    {
        return $this->pubnub->getConfiguration()->getNonSubscribeRequestTimeout();
    }

    /**
     * @return int
     */
    protected function getConnectTimeout()
    {
        return $this->pubnub->getConfiguration()->getConnectTimeout();
    }
}