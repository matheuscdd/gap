<?php

namespace App\Constraints;

class DeliveryKeysConstraints {
    public const CLIENT = 'client';
    public const DELIVERY_DATE = 'delivery_date';
    public const DELIVERY_ADDRESS = 'delivery_address';
    public const PROVIDER_NAME = 'provider_name';
    public const PROVIDER_CITY = 'provider_city';
    public const UNLOADED = 'unloaded';
    public const PAYMENT_STATUS = 'payment_status';
    public const PAYMENT_METHOD = 'payment_method';
    public const PAYMENT_DATE = 'payment_date';
    public const RECEIPT_DATE = 'receipt_date';
    public const DRIVER = 'driver';
    public const INVOICE = 'invoice';
    public const REVENUE = 'revenue';
    public const TRAVEL_COST = 'travel_cost';
    public const UNLOADING_COST = 'unloading_cost';
    public const STOCKS = 'stocks';
    public const CREATED_BY = 'created_by';
    public const UPDATED_BY = 'updated_by';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DATE_FORMAT = 'Y-m-d';
    public const DELIVERY = 'delivery';
    public const STOCK = 'stock';
    public const ALL = [
        self::CLIENT,
        self::DELIVERY_DATE,
        self::DELIVERY_ADDRESS,
        self::PROVIDER_NAME,
        self::PROVIDER_CITY,
        self::UNLOADED,
        self::PAYMENT_STATUS,
        self::PAYMENT_METHOD,
        self::PAYMENT_DATE,
        self::RECEIPT_DATE,
        self::DRIVER,
        self::INVOICE,
        self::REVENUE,
        self::TRAVEL_COST,
        self::UNLOADING_COST,
        self::STOCKS,
        self::DELIVERY,
        self::STOCK,
    ];
}

