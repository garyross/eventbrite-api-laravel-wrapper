<?php

namespace Marat555\Eventbrite;

use Marat555\Eventbrite\Exceptions\EventbriteErrorException;
use Marat555\Eventbrite\Factories\Api\DisplaySettings;
use Marat555\Eventbrite\Factories\Api\Format;
use Marat555\Eventbrite\Factories\Api\Media;
use Marat555\Eventbrite\Factories\Api\Venue;
use Marat555\Eventbrite\Factories\Client;
use Marat555\Eventbrite\Factories\Api\Category;
use Marat555\Eventbrite\Factories\Api\Subcategory;
use Marat555\Eventbrite\Factories\Api\Webhook;
use Marat555\Eventbrite\Factories\Api\Event;
use Marat555\Eventbrite\Factories\Api\User;
use Marat555\Eventbrite\Factories\Api\Order;

/**
 * Eventbrite API wrapper for Laravel
 *
 * @package  Eventbrite
 * @author   @marat555
 */
class Eventbrite
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Category
     */
    public function category()
    {
        return new Category($this->client);
    }

    /**
     * @return Subcategory
     */
    public function subcategory()
    {
        return new Subcategory($this->client);
    }

    /**
     * @return Webhook
     */
    public function webhook()
    {
        return new Webhook($this->client);
    }

    /**
     * @return Event
     */
    public function event()
    {
        return new Event($this->client);
    }

    /**
     * @return Venue
     */
    public function venue()
    {
        return new Venue($this->client);
    }

    /**
     * @return User
     */
    public function user()
    {
        return new User($this->client);
    }

    /**
     * @return Format
     */
    public function format()
    {
        return new Format($this->client);
    }

    /**
     * @return DisplaySettings
     */
    public function displaySettings()
    {
        return new DisplaySettings($this->client);
    }

    /**
     * @return Media
     */
    public function media()
    {
        return new Media($this->client);
    }
    
    /**
     * @return Order
     */
    public function order(): Order
    {
        return new Order($this->client);
    }
    
    public function getOrderByIdAndEmail($orderId, string $email, $strict = false)
    {
        $orderId = (int) $orderId;

        try
        {
            $orders = $this->order()->attendees($orderId);
        } catch (EventbriteErrorException $e)
        {
            return null;
        }

        // objects[] , Order entity


        if (!$strict && count($orders->objects) === 1)
        {

            [$order] = $orders->objects;

            return $order ?? null;
        }

        if (empty($email))
        {
            return null;
        }

        foreach ($orders->objects as $order)
        {
            $profileEmail = $order->profile->email ?? null;
            if ($profileEmail === $email)
            {
                return $order ?? null;
            }
        }

        return null;
    }
}
