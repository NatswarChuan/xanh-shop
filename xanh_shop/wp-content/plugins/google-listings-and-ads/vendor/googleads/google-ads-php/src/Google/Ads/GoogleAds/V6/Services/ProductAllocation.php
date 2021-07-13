<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v6/services/reach_plan_service.proto

namespace Google\Ads\GoogleAds\V6\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * An allocation of a part of the budget on a given product.
 *
 * Generated from protobuf message <code>google.ads.googleads.v6.services.ProductAllocation</code>
 */
class ProductAllocation extends \Google\Protobuf\Internal\Message
{
    /**
     * Selected product for planning. The product codes returned are within the
     * set of the ones returned by ListPlannableProducts when using the same
     * location id.
     *
     * Generated from protobuf field <code>string plannable_product_code = 3;</code>
     */
    protected $plannable_product_code = null;
    /**
     * The value to be allocated for the suggested product in requested currency.
     * Amount in micros. One million is equivalent to one unit.
     *
     * Generated from protobuf field <code>int64 budget_micros = 4;</code>
     */
    protected $budget_micros = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $plannable_product_code
     *           Selected product for planning. The product codes returned are within the
     *           set of the ones returned by ListPlannableProducts when using the same
     *           location id.
     *     @type int|string $budget_micros
     *           The value to be allocated for the suggested product in requested currency.
     *           Amount in micros. One million is equivalent to one unit.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V6\Services\ReachPlanService::initOnce();
        parent::__construct($data);
    }

    /**
     * Selected product for planning. The product codes returned are within the
     * set of the ones returned by ListPlannableProducts when using the same
     * location id.
     *
     * Generated from protobuf field <code>string plannable_product_code = 3;</code>
     * @return string
     */
    public function getPlannableProductCode()
    {
        return isset($this->plannable_product_code) ? $this->plannable_product_code : '';
    }

    public function hasPlannableProductCode()
    {
        return isset($this->plannable_product_code);
    }

    public function clearPlannableProductCode()
    {
        unset($this->plannable_product_code);
    }

    /**
     * Selected product for planning. The product codes returned are within the
     * set of the ones returned by ListPlannableProducts when using the same
     * location id.
     *
     * Generated from protobuf field <code>string plannable_product_code = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setPlannableProductCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->plannable_product_code = $var;

        return $this;
    }

    /**
     * The value to be allocated for the suggested product in requested currency.
     * Amount in micros. One million is equivalent to one unit.
     *
     * Generated from protobuf field <code>int64 budget_micros = 4;</code>
     * @return int|string
     */
    public function getBudgetMicros()
    {
        return isset($this->budget_micros) ? $this->budget_micros : 0;
    }

    public function hasBudgetMicros()
    {
        return isset($this->budget_micros);
    }

    public function clearBudgetMicros()
    {
        unset($this->budget_micros);
    }

    /**
     * The value to be allocated for the suggested product in requested currency.
     * Amount in micros. One million is equivalent to one unit.
     *
     * Generated from protobuf field <code>int64 budget_micros = 4;</code>
     * @param int|string $var
     * @return $this
     */
    public function setBudgetMicros($var)
    {
        GPBUtil::checkInt64($var);
        $this->budget_micros = $var;

        return $this;
    }

}

