<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v9/common/criteria.proto

namespace Google\Ads\GoogleAds\V9\Common;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * State the hotel is located in.
 *
 * Generated from protobuf message <code>google.ads.googleads.v9.common.HotelStateInfo</code>
 */
class HotelStateInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * The Geo Target Constant resource name.
     *
     * Generated from protobuf field <code>optional string state_criterion = 2;</code>
     */
    protected $state_criterion = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $state_criterion
     *           The Geo Target Constant resource name.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V9\Common\Criteria::initOnce();
        parent::__construct($data);
    }

    /**
     * The Geo Target Constant resource name.
     *
     * Generated from protobuf field <code>optional string state_criterion = 2;</code>
     * @return string
     */
    public function getStateCriterion()
    {
        return isset($this->state_criterion) ? $this->state_criterion : '';
    }

    public function hasStateCriterion()
    {
        return isset($this->state_criterion);
    }

    public function clearStateCriterion()
    {
        unset($this->state_criterion);
    }

    /**
     * The Geo Target Constant resource name.
     *
     * Generated from protobuf field <code>optional string state_criterion = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setStateCriterion($var)
    {
        GPBUtil::checkString($var, True);
        $this->state_criterion = $var;

        return $this;
    }

}

